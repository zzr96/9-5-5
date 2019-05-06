<?php
namespace app\index\controller;

use think\Controller;

class Guest extends Controller
{
    public function _empty()
    {
        echo 'empty';
    }

      public function register()
    {   

        $uid = input('uid',0);
        $this->assign('nid',$uid);
        return view();
    }
    public function register_do()
    {
    		$phone=input('phone');

    		$re=db('user')->where('phone',$phone)->find();

    		if(!$re){
    		$pid=input('pid',0);
          
    		$xitong=db('xitong')->find();

         //生成用户基本信息
                $username = 'Y'.$phone;
                db('user')->insert(['phone'=>$phone,'nicheng'=>$username,'datetime'=>date('Y-m-d H:i:s')]);
                if($pid!=0){
                     db('user')->where('id',$pid)->setInc('yue',123);//增加红包
                     db('user')->where('id',$pid)->setInc('yue',$xitong['hongbao']);//增加红包
                       
                }
    	
    	$ret['code']=1;
    	$ret['msg']='注册成功';
    }else{
    	$ret['code']=1;
    	$ret['msg']='您已经注册过了';
    }
    	
    		return json($ret);
    }
      public function download()
    
    {
        //$data['appver'] = db('xitong')->where('id=1')->value('appver');
        $data['appurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/yungeche.apk';
        $data['iosurl'] = 'http://'.$_SERVER['HTTP_HOST'].'/app/yungeche.apk';

        $this->assign('data', $data);
        return view();
    }
}
