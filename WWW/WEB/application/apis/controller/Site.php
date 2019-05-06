<?php
namespace app\apis\controller;

use think\Controller;

class Site extends Com{
  //设置首页
  function index(){
    $find=db('system')->where('id',1)->field('logo,version')->find();
    if($find){
        return json(['code'=>200,'msg'=>'获取成功','data'=>$find]);
    }else{
        return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
    }
  }
  //修改密码
  function edit_pass(){
      $uid=input('uid');
      if(empty($uid)){
          return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
      }
      $password=input('password');
      if(empty($password)){
          return json(['code'=>101,'msg'=>'请填写当前密码','data'=>'']);
      }
      $pass=input('pass');
      if(empty($pass)){
          return json(['code'=>101,'msg'=>'请填写新密码','data'=>'']);
      }
      $new_pass=input('new_pass');
      if(empty($new_pass)){
          return json(['code'=>101,'msg'=>'请填写确认密码','data'=>'']);
      }
      $find=db('user')->where('id',$uid)->field('password')->find();
      $z_p=md5(md5($password).'admin');
      if($z_p=$find['password']){
        return json(['code'=>101,'msg'=>'原密码输入错误','data'=>'']);
      }
      if($pass!=$new_pass){
          return json(['code'=>101,'msg'=>'两次输入密码不一致','data'=>'']);
      }
      $res=db('user')->where('id',$uid)->update(['password'=>$z_p]);
      if($res){
          return json(['code'=>200,'msg'=>'密码修改成功','data'=>'']);
      }else{
          return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
      }
  }
  //关于我们
  function about_us(){
      $res=db('system')->where('id',1)->field('logo,version,gs_name,name')->find();
      if($res){
        return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
      }else{
        return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
      }
  }

  //意见反馈
  function feedback(){
      $uid=input('uid');
      if(empty($uid)){
          return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
      }
      $content=input('content');
      if(empty($content)){
          return json(['code'=>101,'msg'=>'请填写反馈内容','data'=>'']);
      }
      $img=input('img');  //多个图片用逗号隔开
      $data=[
        'uid'=>$uid,
        'content'=>$content,
        'dateline'=>date('Y-m-d H:i:s',time())
      ];
      $find=db('feedback')->insertGetid($data);
      if(empty($img)){
          $arr=explode(',',$img);
          foreach ($arr as $key => $value) {
              db('feedimg')->insert(['fid'=>$find,'img'=>$value]);
          }
      }
      if($find){
          return json(['code'=>200,'msg'=>'添加成功','data'=>'']);
      }else{
          return json(['code'=>100,'msg'=>'添加失败','data'=>'']);
      }
  }

  //联系我们
  function link(){
      $res=db('system_link')->select();
      if($res){
        return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
      }else{
        return json(['code'=>200,'msg'=>'暂无数据','data'=>'']);
      }
  }

  //步数点击获取金额
  function step_money(){
        $step=input('step');
        if(empty($step)){
            return json(['code'=>101,'msg'=>'缺少参数step','data'=>'']);
        }
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
        }
        $beginToday=mktime(0,0,0,date('m'),date('d'),date('Y'));
        $endToday=mktime(0,0,0,date('m'),date('d')+1,date('Y'))-1;
        $finds=db('money_logo')->where(['uid'=>$uid,'title'=>'运动奖金','dateline'=>['between time',[$beginToday,$endToday]]])->find();
        if($finds){
            return json(['code'=>101,'msg'=>'今天已领取过奖金','data'=>'']);
        }
        $find=db('system')->where('id',1)->field('step_money')->find();
        $money=sprintf('%.2f',($step/$find['step_money']));
        $user=db('user')->where('id',$uid)->field('money')->find();
        $new_money=$user['money']+$money;
        $new_money=sprintf('%.2f',$new_money);
        $res=db('user')->where('id',$uid)->update(['money'=>$new_money]);
        if($res){
            $where=[
                'uid'=>$uid,
                'title'=>'运动奖金',
                'money'=>'+'.$money,
                'time'=>date('Y-m',time()),
                'dateline'=>time()
            ];
            db('money_logo')->insert($where);
            return json(['code'=>200,'msg'=>'领取成功','data'=>'']);
        }else{
            return json(['code'=>100,'msg'=>'领取失败','data'=>'']);
        }
        
  }

}	