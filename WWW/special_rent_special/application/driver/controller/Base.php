<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 15:33
 */

namespace app\driver\controller;


use think\Controller;

class Base extends Controller
{
    public function _empty(){
        return json(['code'=>400,'msg'=>'未定义的方法']);
    }
}