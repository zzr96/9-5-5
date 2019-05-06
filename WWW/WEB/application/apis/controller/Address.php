<?php
namespace app\apis\controller;

use think\Controller;

class Address extends Com
{
	 //我的地址展示
	function index(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'您尚未登录！','data'=>'']);
		}
		$res=db('user_addr')->where('uid',$uid)->select();
		if($res){
			return json(['code'=>200,'msg'=>'地址信息获取成功！','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据,请及时添加！','data'=>'']);
		}
	}

	//地址添加
	function add_addr(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid！','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'缺少参数name！','data'=>'']);
		}
		$mobile=input('mobile');
		if(empty($mobile)){
			return json(['code'=>101,'msg'=>'缺少参数mobile！','data'=>'']);
		}
		$province=input('province');
		if(empty($province)){
			return json(['code'=>101,'msg'=>'缺少参数province','data'=>'']);
		}
		$city=input('city');
		if(empty($city)){
			return json(['code'=>101,'msg'=>'缺少参数city','data'=>'']);
		}
		$area=input('area');
		if(empty($area)){
			return json(['code'=>101,'msg'=>'缺少参数area','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'name'=>$name,
			'mobile'=>$mobile,
			'province'=>$province,
			'city'=>$city,
			'area'=>$area,
		];
		$address=input('address');
		if(!empty($address)){
			$data['address']=$address;
		}
		$is_default=input('is_default');
		if(!empty($is_default)){
			$find=db('user_addr')->where(['uid'=>$uid,'is_default'=>1])->field('id')->find();
			if($find){
				db('user_addr')->where('id',$find['id'])->update(['is_default'=>2]);
			}
			$data['is_default']=1;
		}
		$res=db('user_addr')->insertGetId($data);
		if($res){
			return json(['code'=>200,'msg'=>'地址添加成功！','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'地址添加失败！','data'=>'']);
		}
	}
	//设置默认地址
	function add_default(){
		 $uid=input('uid');
		 if(empty($uid)){
		 	return json(['code'=>101,'msg'=>'缺少参数uid！','data'=>'']);
		 }
		 $id=input('id');
		 if(empty($id)){
		 	return json(['code'=>101,'msg'=>'缺少参数id！','data'=>'']);
		 }
		 $find=db('user_addr')->where(['uid'=>$uid,'is_default'=>1])->field('id')->find();
		 if($find){
		 	db('user_addr')->where('id',$find['id'])->update(['is_default'=>2]);
		 }
		 $res=db('user_addr')->where('id',$id)->update(['is_default'=>1]);
		 if($res){
		 	return json(['code'=>200,'msg'=>'默认修改成功！','data'=>'']);
		 }else{
		 	return json(['code'=>100,'msg'=>'修改默认失败！','data'=>'']);
		 }
	}
	//删除地址
	function del_addr(){
		$addr_id=input('id');
		if(empty($addr_id)){
			return json(['code'=>101,'msg'=>'缺少参数add_id','data'=>'']);
		}
		$res=db('user_addr')->where('id',$addr_id)->delete();
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}
	//修改地址
	function edit_addr(){
		$addr_id=input('id');
		if(empty($addr_id)){
			return json(['code'=>101,'msg'=>'缺少参数addr_id','data'=>'']);
		}
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid！','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'缺少参数name！','data'=>'']);
		}
		$mobile=input('mobile');
		if(empty($mobile)){
			return json(['code'=>101,'msg'=>'缺少参数mobile！','data'=>'']);
		}
		$province=input('province');
		if(empty($province)){
			return json(['code'=>101,'msg'=>'缺少参数province','data'=>'']);
		}
		$city=input('city');
		if(empty($city)){
			return json(['code'=>101,'msg'=>'缺少参数city','data'=>'']);
		}
		$area=input('area');
		if(empty($area)){
			return json(['code'=>101,'msg'=>'缺少参数area','data'=>'']);
		}
		$address=input('address');
		$data=[
			'uid'=>$uid,
			'name'=>$name,
			'mobile'=>$mobile,
			'province'=>$province,
			'city'=>$city,
			'area'=>$area,
		];
		// if(!empty($address)){
			$data['address']=$address;
		// }
		// $find=db('user_addr')->where($data)->find();
		// if($find){
		// 	return json(['code'=>101,'msg'=>'地址信息已存在','data'=>'']);
		// }
		$is_default=input('is_default');
		if($is_default==1){
			$find=db('user_addr')->where(['uid'=>$uid,'is_default'=>1])->field('id')->find();
			if($find){
				db('user_addr')->where('id',$find['id'])->update(['is_default'=>2]);
			}
			$data['is_default']=1;
		}
		$res=db('user_addr')->where('id',$addr_id)->update($data);
		if($res){
			return json(['code'=>200,'msg'=>'地址修改成功','data'=>'']);  
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}
	//根据ID获取地址信息
	function get_id_addr(){
		$addr_id=input('id');
		$uid=input('uid');
		if(empty($addr_id)){
			$res=db('user_addr')->where(['uid'=>$uid,'is_default'=>1])->find();
		}else{
			$res=db('user_addr')->where('id',$addr_id)->find();
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

}	