<?php
namespace app\apis\controller;

use think\Controller;

class Index extends Com
{
	//我的页面
	function my_index(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->field('logo,nickname')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>101,'msg'=>'获取失败','data'=>'']);
		}
	}

	//个人信息
	function index(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->field('name,logo,nickname,sex,mobile,is_approve,is_pass,alipay')->find();
		$bank=db('user_band')->where('uid',$uid)->field('bank_num')->find();
		if(empty($res['alipay'])){
			$res['alipay']='未绑定';
		}
		if($bank){
			$res['bank_num']=$bank['bank_num'];
		}else{
			$res['bank_num']='未绑定';
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>101,'msg'=>'获取失败','data'=>'']);
		}
	}
	//获取绑定手机号
	function bind_mobile(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->field('mobile')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>101,'msg'=>'获取失败','data'=>'']);
		}
	}
	//绑定手机号
	function bind_new_mobile(){
		$phone=input('mobile');
		if(empty($phone)){
			return json(['code'=>101,'msg'=>'请填写手机号','data'=>'']);
		}
		if(!$this->is_phone($phone)){
	      	return json(['code'=>101,'msg'=>'手机格式不正确！','data'=>'']);
	    }
		$uid=input('uid');
		$res=db('user')->where('id',$uid)->update(['mobile'=>$phone]);
		if($res){
			return json(['code'=>200,'msg'=>'绑定成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'绑定失败','data'=>'']);
		}
	}

	//实名认证
	function verified(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$find=db('user')->where('id',$uid)->field('name,alipay,is_approve')->find();
		if(empty($find['name'])){
			$find['name']='未设置';
		}
		if(empty($find['alipay'])){
			$find['alipay']='未设置';
		}
		$useb=db('user_band')->where('uid',$uid)->find();
		$finds=db('user_verified')->where('uid',$uid)->field('card_num,card_zimg,card_fimg,status')->find();
		if($finds){
			$find['card_num']=$finds['card_num'];
			$find['card_zimg']=$finds['card_zimg'];
			$find['card_fimg']=$finds['card_fimg'];
			$find['status']=$finds['status'];
		}
		if(empty($useb['bank_num'])){
			$find['bank_num']='未设置';
		}else{
			$find['bank_num']=$useb['bank_num'];
		}
		if($find){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$find]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//实名认证提交
	function sub_verified(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$card_num=input('card_num');
		if(empty($card_num)){
			return json(['code'=>101,'msg'=>'请填写身份证号','data'=>'']);
		}
		$card_zimg=input('card_zimg');
		if(empty($card_zimg)){
			return json(['code'=>101,'msg'=>'请上传身份证正面照','data'=>'']);
		}
		$card_fimg=input('card_fimg');
		if(empty($card_fimg)){
			return json(['code'=>101,'msg'=>'请上传身份证反面照','data'=>'']);
		}
		$find=db('user_verified')->where('uid',$uid)->find();
		if($find){
			return json(['code'=>101,'msg'=>'请勿重新提交','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'card_num'=>$card_num,
			'card_zimg'=>$card_zimg,
			'card_fimg'=>$card_fimg
		];
		$res=db('user_verified')->insert($data);
		if($res){
			return json(['code'=>200,'msg'=>'实名认证提交成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}

	//绑定银行卡
	function band_card(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'请填写姓名','data'=>'']);
		}
		$bank_name=input('bank_name');
		if(empty($bank_name)){
			return json(['code'=>101,'msg'=>'请填写银行卡名称','data'=>'']);
		}
		$bank_num=input('bank_num');
		if(empty($bank_num)){
			return json(['code'=>101,'msg'=>'请填写银行卡号码','data'=>'']);
		}
		$bank_logo=input('bank_logo');
		if(empty($bank_logo)){
			return json(['code'=>101,'msg'=>'请上传银行卡照片','data'=>'']);
		}
		$find=db('user_band')->where('uid',$uid)->find();
		if($find){
			return json(['code'=>101,'msg'=>'请勿重新提交','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'name'=>$name,
			'bank_name'=>$bank_name,
			'bank_num'=>$bank_num,
			'bank_logo'=>$bank_logo
		];
		$res=db('user_band')->insert($data);
		if($res){
			return json(['code'=>200,'msg'=>'提交成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}

	//更新银行卡
	function update_bank(){
		$bank_id=input('bank_id');
		if(empty($bank_id)){
			return json(['code'=>101,'msg'=>'缺少参数bank_id','data'=>'']);
		}
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'请填写姓名','data'=>'']);
		}
		$bank_name=input('bank_name');
		if(empty($bank_name)){
			return json(['code'=>101,'msg'=>'请填写银行卡名称','data'=>'']);
		}
		$bank_num=input('bank_num');
		if(empty($bank_num)){
			return json(['code'=>101,'msg'=>'请填写银行卡号码','data'=>'']);
		}
		$bank_logo=input('bank_logo');
		if(empty($bank_logo)){
			return json(['code'=>101,'msg'=>'请上传银行卡照片','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'name'=>$name,
			'bank_name'=>$bank_name,
			'bank_num'=>$bank_num,
			'bank_logo'=>$bank_logo
		];
		$res=db('user_band')->where('id',$bank_id)->update($data);
		if($res){
			return json(['code'=>200,'msg'=>'更新成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}
	
	//我的银行卡
	function my_band(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user_band')->where('uid',$uid)->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//添加支付宝账号
	function in_alipay(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$alipay=input('alipay');
		if(empty($alipay)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$data=[
			'name'=>$name,
			'alipay'=>$alipay
		];
		$res=db('user')->where('id',$uid)->update($data);
		if($res){
			return json(['code'=>200,'msg'=>'支付宝增加成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'支付宝添加失败','data'=>'']);
		}
	}

	//添加资金密码
	function funds_pass(){
		$funds_pass=input('funds_pass');
		if(empty($funds_pass)){
			return json(['code'=>101,'msg'=>'请填写资金密码','data'=>'']);
		}
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->update(['funds_pass'=>$funds_pass,'is_pass'=>2]);
		if($res){
			return json(['code'=>200,'msg'=>'资金密码添加成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'资金密码设置失败','data'=>'']);
		}
	}

	//修改资金密码---验证密码
	function verify_pass(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$funds_pass=input('funds_pass');
		if(empty($funds_pass)){
			return json(['code'=>101,'msg'=>'请填写新的资金密码','data'=>'']);
		}
		$find=db('user')->where(['id'=>$uid,'funds_pass'=>$funds_pass])->find();
		if($find){
			return json(['code'=>200,'msg'=>'密码验证成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'密码错误','data'=>'']);
		}
	}

	//修改资金密码--修改密码
	function edit_pass(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$new_pass=input('new_pass');
		if(empty($new_pass)){
			return json(['code'=>101,'msg'=>'请填写新的资金密码','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->update(['funds_pass'=>$new_pass]);
		if($res){
			return json(['code'=>200,'msg'=>'密码修改成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'密码修改失败','data'=>'']);
		}
	}

	//重置资金密码--验证身份证
	function reset_pass(){
		$uid=input('uid');
		if(empty('uid')){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'请填写姓名','data'=>'']);
		}
		$card_num=input('card_num');
		if(empty($card_num)){
			return json(['code'=>101,'msg'=>'请填写身份证号','data'=>'']);
		}
		$find=db('user')->where(['id'=>$uid,'name'=>$name,'card_num'=>$card_num])->find();
		if($find){
			return json(['code'=>200,'msg'=>'身份证验证成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'输入错误','data'=>'']);
		}
	}

	//轮播图
	function banner(){
		$res=db('banner')->where('status',1)->field('id,img,link')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//首页图标
	function index_img(){
		$res=db('shop_cat')->where('fid',0)->order('number asc')->limit(7)->field('id,name,img')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//分类主分类
	function index_cate(){
		$res=db('shop_cat')->where('fid',0)->field('id,name')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
	
	//分类二次分类
	function second_cate(){
		$cate_id=input('cate_id');
		if(empty($cate_id)){
			return json(['code'=>101,'msg'=>'缺少参数cate_id','data'=>'']);
		}
		$res=db('shop_cat')->where('fid',$cate_id)->field('id,name,img')->select();
		$find=db('shop_cat')->where('id',$cate_id)->field('name')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'name'=>$find['name']]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//新品速递
	function index_goods(){
		$type=input('type'); //1.最新2.最热3.推荐
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少type值','data'=>'']);
		}
		$cate_id=input('cate_id');
		$p=input('p')?input('p'):1;
		$limit=15;
		if($type==1){
			$data=[
				'is_new'=>2,
				'status'=>1
			];
		}
		if($type==2){
			$data=[
				'is_hot'=>2,
				'status'=>1
			];
		}
		if($type==3){
			$data=[
				'top'=>2,
				'status'=>1
			];
		}
		if($cate_id!=''){
			$data['cate_id']=$cate_id;
		}
		$count=db('goods')->where($data)->count();
		$count_page = ceil($count/$limit);
		$res=db('goods')->where($data)
		->field('id,goods_name,img1,price,yh_price')->page($p,$limit)->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//图片上传
	function imgx(){
		$path = input('path', 'image');
		$image = \think\Image::open(request()->file('file'));
        $image->thumb(100, 100, \think\Image::THUMB_CENTER);
		// $savePath = './uploads/image/' . date("Ymd") . "/";
		$savePath = './uploads/' . $path . '/' . date("Ymd") . "/";
        $saveName = uniqid() . '.jpg';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $image->save($savePath . $saveName);
        $url = $savePath . $saveName;
        $ret['data'] = str_replace("./", "/", $url);
        $ret['code'] = 200;
        $ret['msg'] = '图片上传成功';
        return json($ret);
	}
	
	//图片
	function base64_upload(){
		$base64_image_content=$_POST['img'];
		$url='./upload';
		if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
			//后缀
			$type = $result[2];
			$path = str_replace('\\','/',$url);
			$pathArr = explode('/', $path);
			$filePath = '';
			foreach($pathArr as $k => $v){
				$filePath .= $v.'/';
				if(!is_dir($filePath))
				{
					mkdir($filePath,0700);
				}
			}
			$code = rand(10000, 99999);
			$new_file = $url.'/'.$code.time().".{$type}";
			if (file_put_contents($new_file, base64_decode(str_replace($result[1], '', $base64_image_content)))){
				$new_file=ltrim($new_file,'.');
				return json(['code'=>200,'msg'=>'上传成功','data'=>$new_file]);
			}else{
				return json(['code'=>100,'msg'=>'上传失败','data'=>'']);
			}
		}
	}

	//判定是否设置资金密码
	function is_set_mpas(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->field('funds_pass')->find();
		if(!empty($res['funds_pass'])){
			return json(['code'=>200,'msg'=>'已设置','data'=>$res['funds_pass']]);
		}else{
			return json(['code'=>100,'msg'=>'尚未设置','data'=>'']);
		}
	}

	//提现
	function user_tx(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$type=input('type');	//1.支付宝2.银行卡
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$data=[];
		$data['uid']=$uid;
		$data['type']=$type;
		$user=db('user')->where('id',$uid)->field('alipay,money,f_money')->find();
		if($type==1){
			if(empty($user['alipay'])){
				return json(['code'=>101,'msg'=>'尚未绑定支付宝','data'=>'']); 
			}
			$data['num']=$user['alipay'];
		}else{
			$useb=db('user_band')->where('uid',$uid)->find();
			if(empty($useb)){
				return json(['code'=>101,'msg'=>'尚未绑定银行卡','data'=>'']); 
			}
			$data['num']=$useb['bank_num'];
			$data['card_name']=$useb['bank_name'];
		}
		$money=input('money');
		$sys=db('system')->where('id',1)->field('min_money')->find();
		if(empty($money)){
			return json(['code'=>101,'msg'=>'缺少参数money','data'=>'']);
		}
		if($user['money']<$money){
			return json(['code'=>101,'msg'=>'余额不足','data'=>'']);
		}
		$data['money']=$money;
		if($money<$sys['min_money'] && $money==$sys['min_money']){
			return json(['code'=>101,'msg'=>'提现金额不可小于'.$sys['min_money'],'data'=>'']);
		}
		$data['dateline']=date('Y-m-d H:i:s',time());
		$res=db('user_tx')->insert($data);
		if($res){
			$news_money=$user['money']-$money;
			$new_m=$user['f_money']+$money;
			db('user')->where('id',$uid)->update(['f_money'=>$new_m,'money'=>$news_money]);
			return json(['code'=>200,'msg'=>'提现成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'提现失败','data'=>'']);
		}
		
	}

	//获取支付宝账号
	function get_alipay(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$user=db('user')->where('id',$uid)->field('alipay,name')->find();
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//我的余额
	function get_balance(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$user=db('user')->where('id',$uid)->field('money')->find();
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//余额明细
	function get_money_log(){
		$uid=input('uid');
		$p=input('p')?input('p'):1;
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$where['uid']=$uid;
		$time=input('time');
		if(!empty($time)){
			$where['time']=$time;
		}
		$limit=15;
		$count=db('money_logo')->where('uid',$uid)->count();
		$count_page = ceil($count/$limit);
		$user=db('money_logo')->where('uid',$uid)->page($p,$limit)->select();
		foreach($user as $key=>$value){
			$user[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
		}
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//我的能量
	function get_energy(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$user=db('user')->where('id',$uid)->field('energy_coin')->find();
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//银行卡
	function bank(){
		$res=db('bank')->select();
		foreach($res as $key=>$value){
			$ress[$key]['value']=$value['id'];
			$ress[$key]['text']=$value['bank_name'];
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$ress]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//余额明细
	function get_energy_log(){
		$uid=input('uid');
		$p=input('p')?input('p'):1;
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$where['uid']=$uid;
		$time=input('time');
		if(!empty($time)){
			$where['time']=$time;
		}
		$limit=15;
		$count=db('energy_logo')->where($where)->count();
		$count_page = ceil($count/$limit);
		$user=db('energy_logo')->where($where)->page($p,$limit)->select();
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//我的会员
	function my_vip(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$user=db('user')->where('id',$uid)->field('end_time,is_vip')->find();
		$sys=db('system')->where('id',1)->field('vip_money')->find();
		$user['end_time']=date('Y-m-d',$user['end_time']);
		$user['vip_money']=$sys['vip_money'];
		$find=db('vip_content')->select();
		$user['content']=$find;
		if($user){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$user]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//入职须知
	function check(){
		$res=db('system')->where('id',1)->field('enter_notice')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//是否有银行卡号支付
	function is_tj_card(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$all=0;
		$find=db('user')->where('id',$uid)->field('alipay')->find();
		if(!empty($find['alipay'])){
			$all+=1;
		}
		$finds=db('user_band')->where('uid',$uid)->find();
		if(!empty($finds)){
			$all+=1;
		}
		if($all!=0){
			return json(['code'=>200,'msg'=>'有设置账号','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'暂未设置银行卡，支付宝','data'=>'']);
		}
	}

	//提现页面
	function tx_ym(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$find=db('user')->where('id',$uid)->field('alipay')->find();
		$finds=db('user_band')->where('uid',$uid)->field('bank_name,bank_num')->find();
		$sys=db('system')->where('id',1)->field('min_money,tx_gz')->find();
		$data['alipay']=$find['alipay'];
		$data['bank_name']=$finds['bank_name'];
		$data['bank_num']=$finds['bank_num'];
		$data['min_money']=$sys['min_money'];
		$data['tx_gz']=$sys['tx_gz'];
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
		
	}

	//修改个人信息
	function my_zl(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$nickname=input('nickname');
		if(empty($nickname)){
			return json(['code'=>101,'msg'=>'缺少参数nickname','data'=>'']);
		}
		$sex=input('sex');
		if(empty($sex)){
			return json(['code'=>101,'msg'=>'缺少参数sex','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->update(['nickname'=>$nickname,'sex'=>$sex]);
		if($res){
			return json(['code'=>200,'msg'=>'修改成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'修改失败','data'=>'']);
		}
	}

	//修改个人头像
	function edit_my_logo(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$logo=input('logo');
		if(empty($logo)){
			return json(['code'=>101,'msg'=>'缺少参数logo','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->update(['logo'=>$logo]);
		if($res){
			return json(['code'=>200,'msg'=>'修改成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'修改失败','data'=>'']);
		}
	}

	//系统消息
	function system_xi(){
		$res=db('system_news')->select();
		foreach($res as $key=>$value){
			$res[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}
	//点击查看详情
	function system_mx(){
		$id=input('id');
		$res=db('system_news')->where('id',$id)->find();
		$res['dateline']=date('Y-m-d H:i',$res['dateline']);
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//邀请排行榜
	function yq_list(){
		$p=input('p')?input('p'):1;
		$limit=15;
		$count=db('user')->where('yq_num','<>',0)->count();
		$count_page = ceil($count/$limit);
		$res=db('user')->where('yq_num','<>',0)->field('nickname,logo,yq_num')->page($p,$limit)->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

}	