<?php
namespace app\apis\controller;

use think\Controller;

class Register extends Com
{	
	//获取验证码
	function mobile_verify(){
        	$phone =input('mobile');
        	if(empty($phone)){
        		return json(['code'=>101,'msg'=>'手机号为空！','data'=>'']);
        	}
          // if(!$this->is_phone($phone)){
    			// 	return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
    			// }
        	$rands=rand(100000,999999);
        	cookie('deadline',$rands,300);
          $target = "http://cf.51welink.com/submitdata/Service.asmx/g_Submit";
          $content='您的注册验证码为：'.$rands.'。验证码有效期为5分钟，请尽快填写！【养生活】'; 
          $post_data = "sname=dlycwl01&spwd=ycwl123456&scorpid=&sprdid=1012818&sdst=".$phone."&smsg=".rawurlencode($content);
          $gets = $this->post($post_data, $target);
	        if($gets){
	            return json(['code'=>200,'msg'=>'短信已发至您的手机请注意查收！','data'=>$rands]);
	        }else{
	            return json(['code'=>100,'msg'=>'发生未知错误，短信发送失败！','data'=>'']);
	        }
    }

    //二次获取验证码
    function mobile_r_verify(){
          $phone =input('mobile');
          if(empty($phone)){
            return json(['code'=>101,'msg'=>'手机号为空！','data'=>'']);
          }
          // if(!$this->is_phone($phone)){
          //   return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
          // }
          $rands=rand(100000,999999);
          cookie('deadline_r',$rands,300);
          $target = "http://cf.51welink.com/submitdata/Service.asmx/g_Submit";
          $content='您的注册验证码为：'.$rands.'。验证码有效期为5分钟，请尽快填写！【养生活】'; 
          $post_data = "sname=dlycwl01&spwd=ycwl123456&scorpid=&sprdid=1012818&sdst=".$phone."&smsg=".rawurlencode($content);
          $gets = $this->post($post_data, $target);
          if($gets){
              return json(['code'=>200,'msg'=>'短信已发至您的手机请注意查收！','data'=>$rands]);
          }else{
              return json(['code'=>100,'msg'=>'发生未知错误，短信发送失败！','data'=>'']);
          }
    }

    //第三方短信平台
    function post($data, $target){
      $url_info = parse_url($target);
      $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
      $httpheader .= "Host:" . $url_info['host'] . "\r\n";
      $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
      $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
      $httpheader .= "Connection:close\r\n\r\n";
      //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
      $httpheader .= $data;

      $fd = fsockopen($url_info['host'], 80);
      fwrite($fd, $httpheader);
      $gets = "";
      while(!feof($fd)) {
          $gets .= fread($fd, 128);
      }
      fclose($fd);
      if($gets != ''){
          $start = strpos($gets, '<?xml');
          if($start > 0) {
              $gets = substr($gets, $start);
          }        
      }
      return $gets;
    }

    //手机注册
   	function index(){
   		$phone=input('mobile');
   		if(empty($phone)){
   			return json(['code'=>101,'msg'=>'手机号不能为空','data'=>'']);
   		}
      $find=db('user')->where('mobile',$phone)->find();
      if($find){
        return json(['code'=>101,'msg'=>'账号已存在','data'=>'']);
      }
      $password=input('password');
      if(empty($password)){
          return json(['code'=>101,'msg'=>'密码不能为空','data'=>'']);
      }
      //分享着ID
      $shareId=input('shareId');
      $data=[
          'mobile'=>$phone,
          'nickname'=>$phone,
          'yq_code'=>time().rand(100,999),
          'password'=>md5(md5($password).'admin'),
          'dateline'=>time()
      ];
      $wx=input('wx');
      if($wx){
        $data['wx']=$wx;
      }
      $res=db('user')->insertGetId($data);
      if($res){
        if(!empty($shareId)){
          $ws=[
            'uid'=>$shareId,
            'yq_id'=>$res,
            'time'=>date('Y-m',time()),
            'dateline'=>time()
          ];
          $fs=db('user_team')->where(['uid'=>$shareId,'qy_id'=>$res])->find();
          if(empty($fs)){
            $tc=db('user')->where('id',$shareId)->field('yq_num')->find();
            $new_num=$tc['yq_num']+1;
            db('user')->where('id',$shareId)->update(['yq_num'=>$new_num]);
            db('user_team')->insert($ws);
          }
        }
        return json(['code'=>200,'msg'=>'用户注册成功！','data'=>'']);
      }else{
        return json(['code'=>100,'msg'=>'遇到未知错误，请刷新重试！','data'=>'']);
      }
    }
}