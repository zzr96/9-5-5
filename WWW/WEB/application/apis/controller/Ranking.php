<?php
namespace app\apis\controller;

use think\Controller;

class Ranking extends Com
{
	//步数兑换
	function foot(){
		$res=db('system')->where('id',1)->field('step_money')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
	//排行奖品
	function index(){
		$res=db('ranking')->where('uid',null)->field('type,num,goods_img,goods_name,goods_explain,is_lq,content')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
}	