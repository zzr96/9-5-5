<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 9:47
 */

namespace app\client\model;


use think\Model;

class DriverType extends Model
{
    //隐藏时间
    protected $hidden = ['create_time','update_time'];
}