<?php
namespace app\apis\controller;

use think\Controller;

class Gift extends Com
{	
		//奖品列表
		function index(){
			$res=db('prize')->field('id,prize,v,gift_id')->select();
			foreach($res as $key=>$value){
				$find=db('gift')->where('id',$value['gift_id'])->field('type')->find();
				if($find){
					$res[$key]['type']=$find['type'];
				}else{
					$res[$key]['type']=0;
				}
			}
			if($res){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
			}else{
				return json(['code'=>100,'msg'=>'暂无数据','data'=>$res]);
			}
		}

		//奖品列表
		function index_gift(){
			$res=db('gift')->select();
			if($res){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
			}else{
				return json(['code'=>100,'msg'=>'暂无数据','data'=>$res]);
			}
		}

		//点击抽奖
		function get_gift(){
			$uid=input('uid');
			$sys=db('system')->where('id',1)->field('prize_num')->find();
			$today_start = strtotime(date('Y-m-d 00:00:00'));
			$today_end = strtotime(date('Y-m-d 23:59:59'));
			$where['uid']=$uid;
			$where['dateline']=['between time',[$today_start,$today_end]];
			$find=db('ugift_num')->where($where)->count();
			if($find>$sys['prize_num']){
				return json(['code'=>100,'msg'=>'今日抽奖次数已完，请明日再来','data'=>'']);
			}else{
				db('ugift_num')->insert(['uid'=>$uid,'dateline'=>time()]);
			}
			$prize_arr=db('prize')->field('id,prize,v')->select();
			foreach ($prize_arr as $key => $val){   
				$arr[$val['id']] = $val['v'];//概率数组          
			}    
			$rid = $this->get_rand($arr); //根据概率获取奖项id   
			$res['yes'] = $prize_arr[$rid-1]['id']; //中奖项
			//中奖
			$zj=db('prize')->where('id',$res['yes'])->field('prize,gift_id')->find();
			if($zj['gift_id']!=0){
				$where_s['uid']=$uid;
				$where_s['log']='恭喜您在'.date('Y-m-d H:i:s',time()).'获得'.$zj['prize'];
				db('user_price')->insert($where_s);
			}
			$num='';
			foreach($prize_arr as $key=>$value){
					if($res['yes']==$value['id']){
							$num=$key+1;
					}
			}   
			unset($prize_arr[$rid-1]); //将中奖项从数组中剔除，剩下未中奖项   
			shuffle($prize_arr); //打乱数组顺序   
			for($i=0;$i<count($prize_arr);$i++){   
				$pr[] = $prize_arr[$i]['prize'];  //未中奖项数组         
			}   
			$res['no'] = $pr;
			return json(['code'=>200,'msg'=>'抽奖成功','data'=>$num]);
		}
		
		//计算中奖概率
		function get_rand($proArr){   
			$result = '';   
			//概率数组的总概率精度   
			$proSum = array_sum($proArr);   
			// var_dump($proSum);
			//概率数组循环   
			foreach ($proArr as $key => $proCur) {   
				$randNum = mt_rand(1, $proSum);  //返回随机整数 
				if ($randNum <= $proCur) {   
				$result = $key;   
				break;   
				} else {   
				$proSum -= $proCur;   
				}   
			}   
			unset ($proArr);   
			return $result;   
		}
		
		//抽奖次数
		function prize_num(){
			$uid=input('uid');
			if(empty($uid)){
				return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
			}
			$today_start = strtotime(date('Y-m-d 00:00:00'));
			$today_end = strtotime(date('Y-m-d 23:59:59'));
			$where['uid']=$uid;
			$where['dateline']=['between time',[$today_start,$today_end]];
			$find=db('ugift_num')->where($where)->count();
			$sys=db('system')->where('id',1)->field('prize_num')->find();
			$new_num=$sys['prize_num']-$find;
			return json(['code'=>200,'msg'=>'获取成功','data'=>$new_num]);
		}

		//我的奖品
		function my_prize(){
			$uid=input('uid');
			if(empty($uid)){
				return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
			}
			$where['uid']=$uid;
			$time=input('time');
			if(!empty($time)){
				$where['time']=$time;
			}
			$res=db('user_price')->where($where)->field('log,dateline')->select();
			foreach($res as $key=>$value){
				$res[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
			}
			if($res){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
			}else{
				return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
			}
		}
}