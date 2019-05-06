<?php
namespace app\apis\controller;

use think\Controller;

class Activity extends Com
{
	 //活动规则
	function index(){
		$type=input('type');	//1:早起打卡2：奖励发放3：补签卡4：用户邀请
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$res=db('rule_activity')->where('type',$type)->field('content')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
}	