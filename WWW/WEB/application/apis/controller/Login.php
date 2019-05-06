<?php
namespace app\apis\controller;

use think\Controller;

class Login extends Com
{
	//密码登录
	function pass_login(){
		$phone=input('mobile');
		if(empty($phone)){
      return json(['code'=>101,'msg'=>'手机号不可为空！','data'=>'']);
    }
    if(!$this->is_phone($phone)){
      return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
    }
    $find=db('user')->where('mobile',$phone)->field('id,password')->find();
    if(empty($find)){
      return json(['code'=>101,'msg'=>'该手机号尚未注册！','data'=>'']);
    }
		$password=input('password');
		if(empty($password)){
      return json(['code'=>101,'msg'=>'密码不可为空！','data'=>'']);
    }
    $new_pass=md5(md5($password).'admin');
    if($new_pass!=$find['password']){
      return json(['code'=>101,'msg'=>'密码不正确！','data'=>'']);
    }
    return json(['code'=>200,'msg'=>'登录成功','data'=>$find['id']]);
	}
	//验证码登录
	function mobile_login(){
		$phone=input('mobile');
		if(empty($phone)){
      return json(['code'=>101,'msg'=>'手机号不可为空！','data'=>'']);
    }
    if(!$this->is_phone($phone)){
      return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
    }
    $find=db('user')->where('mobile',$phone)->field('id')->find();
    if(empty($find)){
      return json(['code'=>101,'msg'=>'该手机号尚未注册！','data'=>'']);
    }
		$code=input('code');
		if(empty($code)){
      return json(['code'=>101,'msg'=>'验证码不可为空！','data'=>'']);
    }
		$new_code=cookie('deadline');
		if(empty($new_code)){
   		return json(['code'=>101,'msg'=>'验证码已过期,请重新获取验证码！','data'=>'']);
    }
   	if($code!=$new_code){
   		return json(['code'=>101,'msg'=>'验证码不正确！','data'=>'']);
   	}
   		return json(['code'=>200,'msg'=>'登录成功！','data'=>$find['id']]);

	}

	//找回密码
	function find_pass(){
		$phone=input('mobile');
		if(empty($phone)){
      return json(['code'=>101,'msg'=>'手机号不可为空！','data'=>'']);
    }
    if(!$this->is_phone($phone)){
      return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
    }
    $find=db('user')->where('mobile',$phone)->field('id')->find();
    if(empty($find)){
      return json(['code'=>101,'msg'=>'该手机号尚未注册！','data'=>'']);
    }
   	$password=input('password');
   	$new_pass=md5(md5($password).'admin');
   	$res=db('user')->where('id',$find['id'])->update(['password'=>$new_pass]);
   	if($res){
   		return json(['code'=>200,'msg'=>'密码重置成功！','data'=>'']);
   	}else{
   		return json(['code'=>100,'msg'=>'发生未知错误！','data'=>'']);
   	}
	}

	//第三方登录
	function login(){
			$wx=input('wx');
			$sex=input('sex');
			$nickname=input('nickname');
			//微信登录
			$find=db('user')->where('wx',$wx)->field('id,mobile')->find();
			if($find){
				return json(['code'=>200,'msg'=>'微信登录成功！','data'=>$find]);
			}else{
				$res=db('user')->insertGetId(['wx'=>$wx,'sex'=>$sex,'nickname'=>$nickname]);
				$data=[
					'id'=>$res,
				];
				if($res){
						return json(['code'=>200,'msg'=>'微信登录成功！','data'=>$data]);
				}else{
						return json(['code'=>100,'msg'=>'遇到未知错误！','data'=>'']);
				}
		}
}

	//是否绑定手机号
	function bind_mobile(){
			$uid=input('uid');
			$mobile=input('mobile');
			$find=db('user')->where('mobile',$mobile)->field('id')->find();
			if($find){
					$rc=db('user')->where('id',$uid)->field('wx')->find();
					$yc=db('user')->where('id',$find['id'])->update(['wx'=>$rc['wx']]);
					if($yc){
						db('user')->where('id',$uid)->delete();
					}
					$uid=$find['id'];
			}else{
				$res=db('user')->where('id',$uid)->update(['mobile'=>$mobile]);
			}
			$data=[
				'id'=>$uid
			];		
			return json(['code'=>200,'msg'=>'微信登录成功！','data'=>$data]);
	}
}	