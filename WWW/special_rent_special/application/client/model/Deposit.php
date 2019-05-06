<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:22
 */

namespace app\client\model;


use think\Model;

class Deposit extends Model
{
    //金额单位转换
    protected function getAmountAttr($val){
        return $val / 100;
    }
}