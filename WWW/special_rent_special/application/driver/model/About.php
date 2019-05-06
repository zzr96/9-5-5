<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 15:00
 */

namespace app\driver\model;


use think\Model;

class About extends Model
{
    //隐藏修改时间
    protected $hidden = ['update_time','create_time'];
}