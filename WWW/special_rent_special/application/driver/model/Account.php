<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 16:24
 */

namespace app\driver\model;


use think\Model;

class Account extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

}