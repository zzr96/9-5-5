<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 14:28
 */

namespace app\driver\model;


use think\Model;

class Link extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    //隐藏修改时间
    protected $hidden = ['update_time','create_time'];

}