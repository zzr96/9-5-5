<?php

namespace app\admin\controller;

use think\Db;
class CustomerService extends Base
{
    protected $paginate = 10;
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '售后订单');
    }
    //售后订单列表
    public function index()
    {
        $data = Db::name('order_back')->where(['status'=>['>=',6]])->paginate(15)->each(function ($item,$k){
            $item['backInfo'] = Db::name('customer')->where(['back_id'=>$item['id']])->select();
            $item['userInfo'] = Db::name('member')->field('avatar,phone,nickname')->where(['uid'=>$item['uid']])->find();
            $item['orderInfo'] = Db::name('order')->field('order_sn,price')->where(['id'=>$item['order_id']])->find();
            return $item;
        });

        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }
    //售后详情{int} 状态1待同意2待确认3待退款4用户拒绝退款5商家拒绝退款6申请客服介入7客服已介入8客服处理完成9退款已完成
    public function viewBackOrderDetail()
    {
        $id = input('id');
        $data = Db::name('order_back')->where(['id'=>$id])->find();
        $data['backInfo'] = Db::name('customer')->where(['back_id'=>$data['id']])->select();
        $data['userInfo'] = Db::name('member')->where(['uid'=>$data['uid']])->find();
        $data['orderInfo'] = Db::name('order')->where(['id'=>$data['order_id']])->find();
        $data['shopInfo'] = Db::name('shop')->where(['id'=>$data['orderInfo']['shop_id']])->find();
        $this->assign('data', $data);
        return view();
    }

    //客服介入;
    public function interposition(){
        $id = input('id');
        $status = 7;
        $re = Db::name('order_back')->where(['id'=>$id])->update(['status'=>$status]);
        if($re){
            return json(['code'=>200,'msg'=>'SUCCESS']);
        }
        return json(['code'=>400,'msg'=>'FAIL']);
    }

    //客服提交判定结果;
    public function setResult(){
        $id = input('id');
        $desc = input('desc','暂无判定结果');
        $status = 8;
        $data['id'] = $id;
        $data['desc'] = $desc;
        $data['status'] = $status;
        $re = Db::name('order_back')->update($data);
        if($re){
            return json(['code'=>200,'msg'=>'提交成功']);
        }
        return json(['code'=>400,'msg'=>'提交失败']);
    }

    //删除反馈interposition;
    public function del(){
        $id = input('id');
        $re = Db::name('feedback')->where(['id'=>['in',$id]])->delete();
        if($re){
            return json(['code'=>200,'msg'=>'提交成功']);
        }
        return json(['code'=>400,'msg'=>'提交失败']);
    }

}
