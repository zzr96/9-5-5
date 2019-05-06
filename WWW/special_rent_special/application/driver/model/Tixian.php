<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 11:31
 */

namespace app\driver\model;


use think\Model;

class Tixian extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    //隐藏审核时间
    protected $hidden = ['update_time','desc','type'];

    //申请时间格式转换
    protected function getCreateTimeAttr($val){

        return date('Y-m-d H:i:s',$val);
    }
    //金额转换
    protected function getAmountAttr($val){

        return $val/100;
    }

}