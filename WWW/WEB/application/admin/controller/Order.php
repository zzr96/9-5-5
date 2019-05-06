<?php

namespace app\admin\controller;


use think\Controller;

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
        $order = db('order')->where($map)->order('id desc')->paginate(15, false,['query' => input()]);
        $num = db('order')->where($map)->count();
        $list = $order->items();
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
        $coupons=db('coupon')->where('id',$res['coupon_id'])->find();
        $u_kd=db('kd')->where('b_name',$res['kd_name'])->field('name')->find();
        $kd=db('kd')->select();
        $this->assign('u_kd',$u_kd);
        $this->assign('kd',$kd);
        $this->assign('res',$res);
        $this->assign('goods',$goods);
        $this->assign('coupons',$coupons);
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
        $res=db('order_back')->where('order_id',$id)->find();
        $find=db('order')->where('id',$id)->field('order_sn,paymoney')->find();
        $this->assign('res',$res);
        $this->assign('find',$find);
        return view();
    }

    //退款操作
    function tk_xq_do(){
        $id=input('id');
        $find=db('order_back')->where('id',$id)->field('uid,order_id')->find();
        //用户金额
        $user=db('user')->where('id',$find['uid'])->field('money')->find();
        $order=db('order')->where('id',$find['order_id'])->field('paymoney')->find();
        $new_m=$user['money']+$order['paymoney'];
        $res=db('user')->where('id',$find['uid'])->update(['money'=>$new_m]);
        $r_1=db('order')->where('id',$find['order_id'])->update(['status'=>7]);
        $daa['status']=3;
        $daa['full_time']=date('Y-m-d H:i:s',time());
        db('order_back')->where('id',$id)->update($daa);
        $data=[
            'uid'=>$find['uid'],
            'title'=>'退款成功',
            'money'=>'+'.$order['paymoney'],
            'dateline'=>date('Y-m-d H:i:s',time())
        ];
        db('over_log')->insert($data);
        if($res){
            $this->result('',1,'退款成功');
        }else{
            $this->result('',0,'退款失败');
        }
    }

}