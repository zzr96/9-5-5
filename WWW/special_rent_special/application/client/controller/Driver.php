<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 9:37
 */

namespace app\client\controller;


use app\client\model\Deposit;
use app\client\model\DriverType;
use app\driver\model\Rentot;
use think\Controller;
use think\Exception;
use think\Request;
use app\client\model\User;
use think\Validate;
use app\client\model\DriverOrder;
use think\Db;
use app\common\controller\Base;

class Driver extends Base
{
    /**
     * desc 获取司机类型
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 9:49
     */
    public function getCate(){
        if(Request::instance()->isGet()){
            $type = DriverType::all();
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$type]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);

    }

    /**
     * desc 预约司机展示页面
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 10:00
     * param uid int  用户uid
     */
    public function writeOrder(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $info = User::field('username,mobile,money')->find(['id'=>$uid]);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$info]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 获取预约金
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 10:00
     */
    public function getDeposit(){
        if(Request::instance()->isGet()){
            $data = Deposit::get(1);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 找司机订单确认预约
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 11:34
     */
    public function subOrder(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid' => 'require|number',
                'cate|司机类型' => 'require',
                'address|预约地点' => 'require',
                'long|经度' => 'require|number',
                'lat|纬度' => 'require|number',
                'area|区域' => 'require',
                'city|市区' => 'require',
                'begin_time|预约时间' => 'require',
                'username|联系人' => 'require|chs',
                'mobile|手机号' => 'require|regex:/^1[345789]\d{9}$/',
                'pay_status|支付方式' => 'require|number|between:1,2',
                'is_ban|是否使用余额' => 'require|number|between:1,2',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $param['order_sn'] = createOrderNo();
            $deposit = Deposit::get(1);
            $param['front_amount'] = $deposit->amount;
            $order = DriverOrder::create($param);
            $user = User::get(['id'=>$param['uid']]);
            //选择余额
            if($param['is_ban'] == 1){
                if($user->money >= $deposit->amount){
                    User::startTrans();
                    try{
                        User::where(['id'=>$user->id])->setDec('money',($deposit->ammount * 100));
                        $order->status = 0;
                        $order->balance = $deposit->amount * 100;
                        $order->pay_status = 3;
                        $order->pay_time = time();
                        $order->save();
                        User::commit();
                        return json(['code'=>200,'msg'=>'SUCCESS','data'=>[]]);
                    }catch (Exception $e){
                        User::rollback();
                        return json(['code'=>400,'msg'=>$e->getMessage()]);
                    }
                }else{
                    //支付宝支付
                    if($param['pay_status'] == 1){
                        $order->balance = $user->money;
                        $order->save();
                        $pay = new Pay();
                        $str = $pay->alipayPay($order->front_amount - $user->money * 100,$order->order_sn,config('alipay.alipay_notify_url'));
                        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$str]);
                    }else{
                        //微信支付
                    }
                }
            }else{
                //未选择余额
                if($param['pay_status'] == 1){
                    //支付宝支付
                     $pay = new Pay();
            $str = $pay->alipayPay($order->front_amount,$order->order_sn,config('alipay.alipay_notify_url'));
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$str]);
                }else{
                    //微信支付
                }
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 找司机订单列表
     * author 付鹏
     * createDay: 2019/4/29
     * createTime: 10:23
     * param uid int 用户uid
     * param status int 订单状态
     */
    public function getOrderList(){
        if(Request::instance()->isGet()){
            $uid = input('uid');
            $status = input('status');
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $where['uid'] = $uid;
            if($status){
                $where['status'] = $status;
            }
            $data = Db::name('driver_order')->field('id,uid,driver_id,cate,begin_time,address,status,type')->where($where)->paginate(10)->each(function ($item,$k){
                $item['son'] = Rentot::getSon($item['id'],$item['type']);
                return $item;
            });
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 找司机等待接待详情
     * author 付鹏
     * createDay: 2019/4/29
     * createTime: 10:23
     * param uid int 用户uid
     * param id int 订单id
     */
    public function getOrderDetails(){
        if(Request::instance()->isGet()){
            $uid = input('uid');
            $id = input('id');
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            if(!$id){
                return json(['code'=>400,'msg'=>'缺少参数id']);
            }
            $where['uid'] = $uid;
            $where['id'] = $id;
            $data = DriverOrder::field('id,uid,username,mobile,address,distance,driver_id,driver_name,driver_mobile,trans_amount,order_sn,cate,begin_time,use_time,price,amount,status')->find($where);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 取消订单
     * author 付鹏
     * createDay: 2019/4/29
     * createTime: 13:56
     * param id int 订单id
     * param uid int 用户uid
     */
    public function cancelOrder(){
        if(Request::instance()->isPost()){
            $uid = input('uid/d',0);
            $id = input('id/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            if(!$id){
                return json(['code'=>400,'msg'=>'缺少参数id']);
            }
            $order = DriverOrder::get(['id'=>$id,'uid'=>$uid]);
            if(!$order){
                return json(['code'=>400,'msg'=>'订单不存在']);
            }
            $order->status = -1;
            $order->save();
            return json(['code'=>200,'msg'=>'取消成功']);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }
}