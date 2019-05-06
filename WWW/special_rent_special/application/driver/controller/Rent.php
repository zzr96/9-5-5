<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 14:59
 */

namespace app\driver\controller;

use app\driver\model\Rentot;
use think\Validate;
use app\driver\model\Rentorder;
use think\Controller;
use think\Request;
use app\client\model\User;
class Rent extends Controller
{

    /**
     * desc: 各个状态订单个数
     * path: getOrderCount
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     */
    public function getOrderCount(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = Rentorder::getOrderCount($uid);
//            dump($data);die;
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc: 司机端租车订单列表
     * path: getOrderList
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: status - {int} 状态'-2：已取消-1：未付款 0已付款未分配 1已分配  2带出发  3带施工 4带完工 5已完工',
     */
    public function getOrderList(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            $status = input('status/d',1);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = Rentorder::getOrderList($uid,$status);
//            dump($data);die;
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc: 接单/拒单
     * path: refusalOrder
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     * param: type - {int} 类型 1接单2拒单
     */
    public function refusalOrder(){
        if(Request::instance()->isPost()){
            $uid = input('uid/d',0);
            $id = input('id/d',0);
            $type = input('type/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            if(!in_array($type,[1,2])){
                return json(['code'=>400,'msg'=>'type参数错误']);
            }
            $order = Rentorder::get(['driver_id'=>$uid,'id'=>$id]);
            if(!$order){
                return json(['code'=>400,'未查询到该订单']);
            }
            if($type == 1){
                try{
                    $order->status = 2;
                    $order->save();
                    return json(['code'=>200,'msg'=>'接单成功']);
                }catch (\Exception $e){
                    return json(['code'=>200,'msg'=>$e->getMessage()]);
                }
            }else{
                try{
                    $ids = json_decode($order->refusal_ids,true);
                    $ids[] = $uid;
                    $order->status = 0;
                    $order->driver_id = '';
                    $order->refusal_ids = json_encode($ids);
                    $order->acce_time = time();
                    $order->save();
                    return json(['code'=>200,'msg'=>'已拒单']);
                }catch (\Exception $e){
                    return json(['code'=>200,'msg'=>$e->getMessage()]);
                }

            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * desc: 待接单详情
     * path: getRentDetails
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     */
    public function getRentDetails(){
        if(Request::instance()->isGet()){
            $param = Request::instance()->param();
            $rule = [
              'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $data = Rentorder::field('id,uid,distance,begin_time,time,uname,umobile,uavatar,start_time,order_sn,rentcart,uaddr,content,real_amount,driver_id,status')->find(['uid'=>$param['uid'],'id'=>$param['id']]);
            $data['real_amount'] = $data['real_amount'] / 100;
            $data['start_time'] = date('Y-m-d H:i',$data['start_time']);
            $data['begin_time'] = date('Y-m-d H:i',$data['begin_time']);
//            dump($data);die;
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc: 司机用户出发
     * path: setOut
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     */
    public function setOut(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $order = Rentorder::get(['uid'=>$param['uid'],'id'=>$param['id']]);
            try{
                $order->status = 3;
                $order->save();
                return json(['code'=>200,'msg'=>'SUCCESS']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc: 司机用户开始施工
     * path: startWork
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     */
    public function startWork(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $order = Rentorder::get(['uid'=>$param['uid'],'id'=>$param['id']]);
            if(!$order){
                return json(['code'=>400,'msg'=>'未查询到订单']);
            }
            try{
                $order->status = 4;
                $order->save();
                return json(['code'=>200,'msg'=>'SUCCESS']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }



    /**
     * desc: 司机用户加减时
     * path: changeTime
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     * param: time - {int} 时长
     * param: type - {int} 类型1加时2减时
     */
    public function changeTime(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number',
                'type|类型type'=>'require|number',
                'time|时长'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            if(!in_array($param['type'],[1,2])){
                return json(['code'=>400,'msg'=>'type类型错误']);
            }
            $order = Rentorder::get(['uid'=>$param['uid'],'id'=>$param['id']]);
            if(!$order){
                return json(['code'=>400,'msg'=>'未查询到订单']);
            }
            $price = ($order->rent_amount / $order->hours);
            $data['amount'] = $price * $param['time'];
            $data['type'] = $param['type'];
            $data['order_sn'] = $order->order_sn;
            $data['order_id'] = $order->id;
            $data['time'] = $param['time'];
            if($param['type'] == 1){
                try{
                    $rentot = Rentot::create($data);
                    $id = $rentot->id;
                    return json(['code'=>200,'msg'=>'加时成功','data'=>['amount'=>$data['amount'],'id'=>$id]]);
                }catch (\Exception $e){
                    return json(['code'=>200,'msg'=>$e->getMessage()]);
                }
            }else{
                try{
                    $data['status'] = 1;
                    Rentot::startTrans();
                    Rentot::create($data);
                    User::where(['id'=>$order->uid])->setInc('money',$data['amount']);
                    Rentot::commit();
                    return json(['code'=>200,'msg'=>'减时成功']);
                }catch (\Exception $e){
                    Rentot::rollback();
                    return json(['code'=>400,'msg'=>$e->getMessage()]);
                }

            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * desc: 司机用户发起付款
     * path: transferPayment
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单订单id
     * param: amount - {int} 金额
     */
    public function transferPayment(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'id|加时订单id'=>'require|number',
                'amount|金额'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $order = Rentot::get(['id'=>$param['id']]);
            if(!$order){
                return json(['code'=>400,'msg'=>'未查询到需加时订单']);
            }
            try{
                $order->need_amount = $param['amount'] * 100;
                $order->save();
                return json(['code'=>200,'msg'=>'SUCCESS']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }



    /**
     * desc: 司机用户完工
     * path: workComplete
     * method: post
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     */
    public function workComplete(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $order = Rentorder::get(['uid'=>$param['uid'],'id'=>$param['id']]);
            if(!$order){
                return json(['code'=>400,'msg'=>'未查询到订单']);
            }
            try{
                $order->status = 5;
                $order->save();
                return json(['code'=>200,'msg'=>'SUCCESS']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc: 获取已完工订单详情
     * path: getEndDetails
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: id - {int} 订单id
     */
    public function getEndDetails(){
        if(Request::instance()->isGet()){
            $param = Request::instance()->param();
            $rule = [
                'id|订单id'=>'require|number',
                'uid|用户uid'=>'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $order = Rentorder::get(['uid'=>$param['uid'],'id'=>$param['id']]);
            if(!$order){
                return json(['code'=>400,'msg'=>'未查询到订单']);
            }
            $res = Rentorder::with("rentot")->field('id,uid,distance,begin_time,time,uname,umobile,uavatar,start_time,order_sn,rentcart,uaddr,content,real_amount,driver_id,status')->where(['uid'=>$param['uid'],'id'=>$param['id']])->select();

            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$res]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

}