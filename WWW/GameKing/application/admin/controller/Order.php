<?php

namespace app\admin\controller;


use think\Controller;
use app\apis\controller\Pay;
use think\Db;

class Order extends Base
{
    /* 订单管理开始 */
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '订单管理');
    }

    // 商城管理-订单列表
    public function index()
    {
        $map=[];
        $key = trim(input("key"));
        $status = input('status');
        if ($status) {
            $map['status'] = $status;
        }
        if($key){
            $map['order_sn'] = $key;
        }
        $adminid = cookie("adminid");
        $shop_id=$this->get_shop_id();
        if($adminid==1){
            $order = db('order')->where($map)->order('id desc')->paginate(15, false,['query' => input()]);
            $num = db('order')->where($map)->count();
        }else{
            $map['shop_id'] = $shop_id;
            $order = db('order')->where($map)->order('id desc')->paginate(15, false,['query' => input()]);
            $num = db('order')->where($map)->count();
        }
        $list = $order->items();
        foreach ($list as $key => $value) {
            $arr=db('order_goods')->where('order_id',$value['id'])->select();
            $new_price=0;
            foreach ($arr as $k => $v) {
               $new_price+=$v['price']*$v['goods_num'];
                $new_price=sprintf("%.2f",$new_price);# code...
            }
            $list[$key]['new_price']=$new_price;
        }
        //print_r($list);die;
        $page = $order->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        $this->assign('num', $num);
        return view('index');
    }

    //删除订单
    function delete(){
        $id = input('id');
        $res = db('order')->where('id',$id)->delete();
        if($res){
            db('order_goods')->where('order_id',$id)->delete();
            $this->result('',0,'删除成功');
        }else{
            $this->result('',1,'删除失败');
        }
    }

    //查看订单
    function look(){
        $id=input('id');
        $res=db('order')->where('id',$id)->find();
        $goods=db('order_goods')->where('order_id',$id)->select();
        $new_price=0;
        foreach ($goods as $k => $v) {
           $new_price+=$v['price']*$v['goods_num'];
            $new_price=sprintf("%.2f",$new_price);# code...
        }
        $res['new_price']=$new_price;
        //print_r($res);die;
        $u_kd=db('kd')->where('b_name',$res['kd_name'])->field('name')->find();
        $kd=db('kd')->select();
        $this->assign('u_kd',$u_kd);
        $this->assign('kd',$kd);
        $this->assign('res',$res);
        $this->assign('goods',$goods);
        return view();
    }

    //发货
    function fh(){
        $data=input();
        $id=$data['id'];
        $kd_id=$data['kd_id'];
        $kd=db('kd')->where('id',$kd_id)->field('b_name')->find();
        $kd_num=$data['kd_num'];
        $datas['kd_num']=$kd_num;
        $datas['kd_name']=$kd['b_name'];
        $datas['status']=3;
        $res=db('order')->where('id',$id)->update($datas);
        if($res){
            $this->result('',1,'发货成功');
        }else{
            $this->result('',0,'发货失败');
        }
    }
    //退货详情页面
    function tk_xq(){
        $id=input('id');
        $res=db('order_back')->alias('o')->field('o.*,g.order_id,g.goods_img,goods_name,goods_num,color,spec,price,order_sn')->join('order_goods g','g.id = o.order_id','left')->where(['g.order_id'=>$id])->select();
        foreach ($res as &$v){
            $v['img'] = db('oback_img')->field('img')->where('back_id',$v['id'])->select();
        }

        $find=db('order')->where('id',$id)->find();
        $this->assign('res',$res);
        $this->assign('find',$find);
        return view();
    }
    //同意或者拒绝退款
    function agree(){
        $order_status=input("status");
        $id=input("back_id",0);
        $order_id=input("order_id",0);
        $refund_desc = input('refund_desc');
        $data['status']=$order_status;
        $update = [];
        if($order_status == 8){
            $update['order_status'] = $order_status;
            $update['status'] = 2;
            $update['refund_desc'] = $refund_desc;
            $update['updatetime'] = time();
        }else{
            $update['order_status'] = $order_status;
            $update['status'] = 5;
            $update['refund_desc'] = $refund_desc;
            $update['updatetime'] = time();
        }
        Db::startTrans();
        try{
            Db::name('order')->where('id',$order_id)->update($data);
            Db::name('order_back')->where(['id'=>$id])->update($update);
            Db::commit();
            return json(['code'=>1,'msg'=>'操作成功！']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>0,'msg'=>'操作失败！']);
        }
    }
    //退款操作
    function tk_xq_do(){
        $id=input('id');
        $find=db('order_back')->where('id',$id)->field('uid,order_id')->find();
        $aa=db("order")->where('id',$find['order_id'])->field('price,pay_status,id,order_sn')->find();
        //echo $aa['pay_status'];die;
        if($aa['pay_status']==1){
            //用户金额
            $user=db('member')->where('uid',$find['uid'])->field('balance')->find();
            $new_m=$user['balance']+$aa['price'];
            $res=db('member')->where('uid',$find['uid'])->update(['balance'=>$new_m]);
            $r_1=db('order')->where('id',$find['order_id'])->update(['status'=>9]);
            if($res){
                $this->result('',1,'退款成功');
            }else{
                $this->result('',0,'退款失败');
            }
        }
        if($aa['pay_status']==2){
            $user = new Pay();
            $user = $user -> aliRefund($aa['order_sn'],$aa['price']);
            //$user = $user -> wxRefund($aa['order_sn']);
            $resultCode=header("Location:https://openapi.alipay.com/gateway.do?".$user);
            if($resultCode){
                $r_1=db('order')->where('id',$find['order_id'])->update(['status'=>9]);
                if($res){
                    $this->result('',1,'退款成功');
                }else{
                    $this->result('',0,'退款失败');
                }
            }
        }
        if($aa['pay_status']==3){
            $user = new Pay();
            $user = $user ->wxRefund($aa['order_sn']);
            if($user==1){
                $r_1=db('order')->where('id',$find['order_id'])->update(['status'=>9]);
                if($res){
                    $this->result('',1,'退款成功');
                }else{
                    $this->result('',0,'退款失败');
                }
            }
        }
        //$daa['status']=3;
        //$daa['full_time']=date('Y-m-d H:i:s',time());
        //db('order_back')->where('id',$id)->update($daa);
        /*$data=[
            'uid'=>$find['uid'],
            'title'=>'退款成功',
            'money'=>'+'.$order['paymoney'],
            'dateline'=>date('Y-m-d H:i:s',time())
        ];
        db('over_log')->insert($data);*/
        /*if($res){
            $this->result('',1,'退款成功');
        }else{
            $this->result('',0,'退款失败');
        }*/

    }

}