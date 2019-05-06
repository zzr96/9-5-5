<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/24
 * Time: 18:36
 */

namespace app\driver\model;


use think\Model;
use think\model\relation\BelongsTo;

class Rentot extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'create_time';

    protected $updateTime = 'update_time';

    /**
     * desc: 金额除以100
     * path: getRealAmountAttr
     * method: get
     * time: 2019-04-24
     * param: amount - {int} 金额
     */
    protected function getNeedAmountAttr($val){
        return $val / 100;
    }
    /**
     * desc: 金额除以100
     * path: getRealAmountAttr
     * method: get
     * time: 2019-04-24
     * param: amount - {int} 金额
     */
    protected function getAmountAttr($val){
        return $val / 100;
    }

    /**
     * 获取加减时订单
     * author 付鹏
     * createDay: 2019/4/29
     * createTime: 12:35
     */
    public static function getSon($id,$type){
        $order = self::field('id,type,status,amount,time')->where(['order_id'=>$id,'order_type'=>$type])->select();
        return $order;
    }

}