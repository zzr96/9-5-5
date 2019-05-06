<?php
namespace app\apis\controller;

use think\Controller;

class Coupon extends Com
{
	//我的优惠券
	function my_coupon(){
		$uid=input('uid');
		$res=db('user_coupon')->where('uid',$uid)->field('shop_id')->select();
		$res=array_unique($res, SORT_REGULAR);
		$del_arr=[];
		$finds=db('coupon_del')->where('uid',$uid)->field('coupon_id')->select();
		if($finds){
			foreach ($finds as $key => $value) {
                    $del_arr[]=$value['coupon_id'];
			}
		}
		$del_arr=array_unique($del_arr);
		foreach ($res as $key => $value){
			$shop=db('shop')->where('id',$value['shop_id'])->field('shop_name,shop_logo')->find();
			$res[$key]['shop_name']=$shop['shop_name'];
			$res[$key]['shop_logo']=$shop['shop_logo'];
			$where['shop_id']=$value['shop_id'];
			$where['endtime']=['>',time()];
			$where['is_use']=1;
			$shops=db('user_coupon')->where($where)->field('id,coupon_id')->select();
			foreach ($shops as $k=>$v){
				if(in_array($v['coupon_id'],$del_arr)){
					unset($shops[$k]);
				}else{
					$fis=db('coupon')->where('id',$v['coupon_id'])->field('name,mz_money,yh_money,start_time,end_time')->find();
					$shops[$k]['name']=$fis['name'];
					$shops[$k]['mz_money']=$fis['mz_money'];
					$shops[$k]['yh_money']=$fis['yh_money'];
					$shops[$k]['start_time']=$fis['start_time'];
					$shops[$k]['end_time']=$fis['end_time'];
				}
			}
			$res[$key]['coupons']=$shops;
		}
		$res=array_values($res);
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}


	//删除
	function del(){
		$id = input("id"); //如1,2
		if(empty($id)){
			return json(['code'=>101,'msg'=>'缺少参数id！','data'=>'']);
		}
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$arr=explode(',',$id);
		foreach ($arr as $key => $value){
			$find=db('coupon_del')->where(['uid'=>$uid,'coupon_id'=>$value])->find();
			if($find){
				return json(['code'=>101,'msg'=>'请勿重复删除','data'=>'']);
			}
			db('coupon_del')->insert(['uid'=>$uid,'coupon_id'=>$value]);
		}
        return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
	}

	//优惠券领取
	function receive_yhq(){
		$uid=input('uid');
		$coupon_id=input('coupon_id');
		$coupon=db('coupon')->where('id',$coupon_id)->field('shop_id,end_time')->find();
		$find=db('user_coupon')->where(['uid'=>$uid,'coupon_id'=>$coupon_id])->find();
		if($find){
			return json(['code'=>100,'msg'=>'请勿重复领取','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'coupon_id'=>$coupon_id,
			'shop_id'=>$coupon['shop_id'],
			'endtime'=>strtotime($coupon['end_time'])
		];
		$res=db('user_coupon')->insert($data);
		if($res){
			return json(['code'=>200,'msg'=>'领取成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}

	//优惠券历史记录
	function history(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$type=input('type');	//1.已使用2.已过期
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$where['uid']=$uid;
		if($type==1){
			$where['is_use']=2;
		}else{
			$where['endtime']=['<',time()];
		}
		$limit=15;
		$count=db('user_coupon')->where($where)->count();
		$count_page = ceil($count/$limit);
		$res=db('user_coupon')->where($where)->field('id,coupon_id')->page($p,$limit)->select();
		$del_arr=[];
		$finds=db('coupon_del')->where('uid',$uid)->field('coupon_id')->select();
		if($finds){
			foreach ($finds as $key => $value) {
				 foreach ($value as $v){
                    $del_arr[]=$v;
                }
			}
		}
		foreach ($res as $key => $value){
			if(in_array($value['coupon_id'],$del_arr)){
				unset($res[$k]);
			}else{
				$fis=db('coupon')->where('id',$value['coupon_id'])->field('name,mz_money,yh_money,start_time,end_time')->find();
				$res[$key]['name']=$fis['name'];
				$res[$key]['mz_money']=$fis['mz_money'];
				$res[$key]['yh_money']=$fis['yh_money'];
				$res[$key]['start_time']=$fis['start_time'];
				$res[$key]['end_time']=$fis['end_time'];
			}
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
}	