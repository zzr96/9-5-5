<?php

namespace app\apis\controller;

use think\Db;
use think\Controller;

/**
 * 接口基类，用于权限判断
 */
class Auth extends Controller
{
    protected $uid;

    public function _initialize()
    {
        parent::_initialize();
        $uid = cookie('userId');
        $token = cookie('token');
        if(!$uid){
            exit(json_encode(['code' => 400, 'msg' => 'ACCESS DENY']));
        }
        if(!$token){
            exit(json_encode(['code' => 400, 'msg' => 'ACCESS DENY']));
        }
        $user = Db::name('member')->find($uid);
        if(empty($user)){
            exit(json_encode(['code' => 400, 'msg' => '用户名或密码错误'.$token]));
        }

        if (md5($user['uid'].md5($user['phone'])) != $token) {
            exit(json_encode(['code' => 400, 'msg' => '用户名或密码错误'.$token]));
        }

        $this->uid = $uid;
    }

    public static function checkAuth()
    {
        $uid = cookie('userId');
        $token = cookie('token');
        if(!$uid){
            exit(json_encode(['code' => 400, 'msg' => 'ACCESS DENY']));
        }
        if(!$token){
            exit(json_encode(['code' => 400, 'msg' => 'ACCESS DENY']));
        }
        $user = Db::name('member')->find($uid);
        if(empty($user)){
            exit(json_encode(['code' => 400, 'msg' => '用户名或密码错误']));
        }

        if (md5($user['uid'].md5($user['phone'])) != $token) {
            exit(json_encode(['code' => 400, 'msg' => '用户名或密码错误']));
        }
        return $uid;
    }

    public function _empty(){
        return json(['code'=>400,'msg'=>'出现未知错误']);
    }

}