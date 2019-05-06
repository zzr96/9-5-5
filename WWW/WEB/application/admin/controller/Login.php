<?php
namespace app\admin\controller;

use think\Controller;


class Login extends Controller
{
    public function index()
    {
        return view('index');
    }

    function login_do(){
        $name = input("name");
        if (!$name) {
            return json(['code'=>0,'msg'=>'账号不能为空','data'=>'']);
        }
        $password = input("password");
        if (!$password) {
            return json(['code'=>0,'msg'=>'密码不能为空！','data'=>'']);
        }
        $captcha = input("captcha");
        if(!captcha_check($captcha)){
            return json(['code'=>0,'msg'=>'验证码不正确！','data'=>'']);
        }
        $do = db("admin")->where("name='" . $name . "' and password='" . md5($password) . "'")->find();
        if(!$do){
            return json(['code'=>0,'msg'=>'请核对账号密码','data'=>'']);
        }
        if($do['status'] == 0){
            return json(['code'=>0,'msg'=>'账户被禁用！','data'=>'']);
        }
        cookie("adminid", $do['id']);
        return json(['code'=>1,'msg'=>'登录成功！','data'=>'']);

    }

    public function my()
    {
        cookie("adminid", 1);
        $this->success("登录成功！", '/admin/index/index');
    }

    public function out()
    {
        cookie("adminid", NULL);
        $this->success("退出成功！", "login/index");
    }
}
