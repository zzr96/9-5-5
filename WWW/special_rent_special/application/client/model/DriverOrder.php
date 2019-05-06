<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 11:55
 */

namespace app\client\model;


use think\Model;

class DriverOrder extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    //金额转换
    protected function setFrontAmountAttr($val){
        return $val * 100;
    }
        //金额转换
    protected function setBalanceAttr($val){
        return $val * 100;
    }
    //金额转换
    protected function getAmountAttr($val){
        return $val / 100;
    }
    //金额转换
    protected function getTransAmountAttr($val){
        return $val / 100;
    }
    //金额转换
    protected function getPriceAttr($val){
        return $val / 100;
    }
}