<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 15:23
 */

namespace app\client\model;


use think\Model;

class Address extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    //隐藏字段
    protected $hidden = ['create_time','update_time'];
}