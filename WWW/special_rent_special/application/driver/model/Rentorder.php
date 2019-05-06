<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 15:10
 */

namespace app\driver\model;


use think\Model;

class Rentorder extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'start_time';
    /**
     * desc: 司机端租车订单列表
     * path: getOrderList
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uid
     * param: status - {int} 状态'-2：已取消-1：未付款 0已付款未分配 1已分配  2带出发  3带施工 4带完工 5已完工',
     */
    public static function getOrderList($uid,$status){
        $data = self::field('id,distance,real_amount,begin_time,time,driver_id,rentcart,uaddr')->where(['driver_id'=>$uid,'status'=>$status])->paginate(10)->each(function ($item,$key){
            $item['real_amount'] = $item['real_amount'] / 100;
            $item['begin_time'] = date('Y-m-d H:i',$item['begin_time']);
            return $item;
        });
        return $data;
    }

    /**
     * desc: 各个状态订单个数
     * path: getOrderCount
     * method: get
     * time: 2019-04-24
     * param: uid - {int} 用户uidSELECT num,count(*) AS counts from test_a GROUP BY num;
     */
    public static function getOrderCount($uid){
        $data = self::field('driver_id,status,count(*) as counts')->where(['driver_id'=>$uid,'status'=>['between',[1,5]]])->group('status')->select();
        return $data;
    }

    /**
     * desc: 关联Rentot模型
     * path: rentot
     * method: get
     * time: 2019-04-24
     */
    public function rentot(){
        return $this->hasMany('Rentot','order_id','id')->field('id,order_id,type,time,amount,need_amount');
    }

    /**
     * 获取工时
     * @param int $uid 用户uid
     * @param bool $type 当天/全部
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public static function getHourCounts($uid,$type = false){
        if($type){
            $start = strtotime(date('Y-m-d'));
            $end = strtotime(date("Y-m-d",strtotime("+1 day")));
            $hours = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]],'acce_time'=>['between',[$start,$end]]])->sum('hours');

        }else{
            $hours = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]]])->sum('hours');
        }
        return $hours;
    }


    /**
     * 获取订单数
     * @param int $uid 用户uid
     * @param bool $type 当天/全部
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public static function getOrderCounts($uid,$type = false){
        if($type){
            $start = strtotime(date('Y-m-d'));
            $end = strtotime(date("Y-m-d",strtotime("+1 day")));
            $counts = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]],'acce_time'=>['between',[$start,$end]]])->count();

        }else{
            $counts = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]]])->count();
        }
        return $counts;
    }


    /**
     * 获取工资
     * @param int $uid 用户uid
     * @param bool $type 当天/全部
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public static function getAmountCounts($uid,$type = false){
        if($type){
            $start = strtotime(date('Y-m-d'));
            $end = strtotime(date("Y-m-d",strtotime("+1 day")));
            $amount = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]],'acce_time'=>['between',[$start,$end]]])->sum('rent_amount');
        }else{
            $amount = self::where(['driver_id'=>$uid,'status'=>['between',[2,5]]])->sum('rent_amount');
        }
        return $amount / 100;
    }

    /**
     * desc: 金额除以100
     * path: getRealAmountAttr
     * method: get
     * time: 2019-04-24
     * param: amount - {int} 金额
     */
    protected function getRealAmountAttr($val){
        return $val / 100;
    }

    /**
     * desc: 开工时间格式转换
     * path: getBeginTimeAttr
     * method: get
     * time: 2019-04-24
     * param: begin_time - {int} 时间
     */
    protected function getBeginTimeAttr($val){
        return date('Y-m-d H:i',$val);
    }

    /**
     * desc: 下单时间格式转换
     * path: getStartTimeAttr
     * method: get
     * time: 2019-04-24
     * param: begin_time - {int} 时间
     */
    protected function getStartTimeAttr($val){
        return date('Y-m-d H:i',$val);
    }
    /**
     * desc: 接单时间格式转换
     * path: getAcceTimeAttr
     * method: get
     * time: 2019-04-24
     * param: begin_time - {int} 时间
     */
    protected function getAcceTimeAttr($val){
        return date('Y-m-d H:i',$val);
    }

    /**
     * desc: 工时明细租车订单
     * path: getBeginTimeAttr
     * method: get
     * time: 2019-04-24
     * param: begin_time - {int} 时间
     */
    protected function getRentOrder($uid){
        $order = Rentorder::field('id,uid,driver_id,order_sn,start_time,acce_time,hours')->where(['driver_id'=>$uid,'status'=>['between',[2,5]]])->paginate(15);
        return $order;
    }


}