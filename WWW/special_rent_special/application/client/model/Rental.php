<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 18:58
 */

namespace app\client\model;


use think\Model;

class Rental extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    protected $hidden = ['update_time'];
    //获取租车指南
    public static function getRental(){
        $rental = self::where(['status'=>1])->order('create_time desc')->paginate(15);
        return $rental;
    }

    //时间转换
    public static function getCreateTimeAttr($val){
        return date('m-d H:i',$val);
    }
}