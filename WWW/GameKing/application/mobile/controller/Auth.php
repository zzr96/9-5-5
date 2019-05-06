<?php

namespace app\mobile\controller;

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
            exit(json_encode(['code' => 101, 'msg' => 'ACCESS DENY']));
        }
        if(!$token){
            exit(json_encode(['code' => 101, 'msg' => 'ACCESS DENY']));
        }
        $user = Db::name('member')->find($uid);
        if(empty($user)){
            exit(json_encode(['code' => 101, 'msg' => '用户名或密码错误'.$token]));
        }

        if (md5($user['password'] . $user['logintime']) != $token) {
            exit(json_encode(['code' => 101, 'msg' => '用户名或密码错误'.$token]));
        }

        $this->uid = $uid;
    }

    public static function checkAuth()
    {
        $uid = cookie('userId');
        $token = cookie('token');
        if(!$uid){
            exit(json_encode(['code' => 1, 'msg' => 'ACCESS DENY']));
        }
        if(!$token){
            exit(json_encode(['code' => 1, 'msg' => 'ACCESS DENY']));
        }
        $user = Db::name('member')->find($uid);
        if(empty($user)){
            exit(json_encode(['code' => 1, 'msg' => '用户名或密码错误']));
        }

        if (md5($user['password'] . $user['logintime']) != $token) {
            exit(json_encode(['code' => 1, 'msg' => '用户名或密码错误']));
        }
        return $uid;
    }

}