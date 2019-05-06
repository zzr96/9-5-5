<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/28
 * Time: 14:39
 */

namespace app\common\controller;


use app\client\model\User;
use think\Controller;
use think\Cookie;
use think\Request;

class Base extends Controller
{
    protected $uid;
    protected $token;

    /**
     * desc 初始化验证登录状态
     * author 付鹏
     * createDay: 2019/4/28
     * createTime: 14:54
     */
    protected function _initialize(){
        parent::_initialize();
        Request::instance()->filter('trim,strip_tags,htmlspecialchars');    //移除HTML标签
        $this->token = Cookie::get('token');
        $this->uid = Cookie::get('uid');
        $user = User::get($this->uid);
        if(!$user){
            echo json_encode(['code'=>400,'msg'=>'账号不存在']);exit();
        }
        if(md5($user->id . $user->password) != $this->token){
            echo json_encode(['code'=>400,'msg'=>'登录已失效，请重新登录']);exit();
        }
    }
}