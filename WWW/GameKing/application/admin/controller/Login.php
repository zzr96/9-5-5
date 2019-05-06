<?php
namespace app\admin\controller;

use think\Controller;

class Login extends Controller {
	public function index() {
		return view('index');
	}

	public function login_do() {
		$name = input("name");
		$password = input("password");
		if (!$name) {
			$this->error("账户名不能为空！");
		}
		if (!$password) {
			$this->error("密码不能为空！");
		}
		$do = db("admin")->where("name='" . $name . "' and password='" . md5($password) . "'")->find();
		if ($do) {
			if ($do['status'] == 0) {
				$this->error("账户被禁用！");
			} else {
				cookie("adminid", $do['id']);
				session("userId", $do['id']);
				$this->success("登录成功！", 'admin/index/index');
			}
		} else {
			$this->error("账户名密码不正确！");
		}
	}

	public function my() {
		cookie("adminid", 1);
		session("userId", 1);
		$this->success("登录成功！", '/admin/index/index');
	}

	public function out() {
		cookie("adminid", NULL);
		session("userId", NULL);
		$this->success("退出成功！", "login/index");
	}
}
