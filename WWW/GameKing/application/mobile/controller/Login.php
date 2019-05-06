<?php

namespace app\mobile\controller;

use think\Db;
use think\Controller;
use think\Request;
use think\Exception;
use think\Cookie;

class Login extends Controller
{

    public function _initialize()
    {
        $uid = cookie('userId');
        $token = cookie('token');
        if ($uid && $token) {
            $user = Db::name('member')->find($uid);
            if (md5($user['uid'].md5($user['password'])) != $token) {
                $this->redirect('mobile/member/index');
            }
        }
    }

    //企业登录
    public function login()
    {
        return view();
    }

    //登录输入密码
    public function password()
    {
        $phone = input('phone');
        $this->assign('phone', $phone);
        return view();
    }

    //注册
    public function register()
    {
        $phone = input('phone');
        $this->assign('phone', $phone);
        return view();
    }

    //忘记密码
    public function forgetPass()
    {
        return view();
    }

    public function forgetPass2()
    {
        $phone = input('phone');
        if($phone){
            //TODO 发送验证码
            $code = mt_rand('1000','9999');
            $content = '您的验证码是：' . $code . '。请不要把验证码泄露给其他人。';
            $url = "http://106.ihuyi.com/webservice/sms.php?method=Submit&account=".config('sms.appid')."&password=".config('sms.appkey')."&mobile=" . $phone . "&content=" . $content;
            $result = file_get_contents($url);
            $xml = simplexml_load_string($result);


            $data['phone'] = $phone;
            $data['code'] = $code;
            $data['addtime'] = time();
            $re = Db::name('sms_code')->insertGetId($data);
        }
        $this->assign('phone',$phone);
        return view();
    }


}