<?php
namespace app\apis\controller;

use think\Controller;

class Cart extends Com
{
	//添加购物车
	function add_cart(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$goods_size_id=input('goods_size_id');
		if(empty($goods_size_id)){
			return json(['code'=>101,'msg'=>'缺少参数goods_size_id','data'=>'']);
		}
		$num=input('num');
		if(empty($num)){
			return json(['code'=>101,'msg'=>'缺少参数num','data'=>'']);
		}
		$find=db('shopping_cart')->where('goods_size_id',$goods_size_id)->field('num')->find();
		if($find){
			$new_num=$find['num']+$num;
			$res=db('shopping_cart')->where('goods_size_id',$goods_size_id)->update(['num'=>$new_num]);
		}else{
			// $shop_id=input('shop_id');
			$shop=db('goods_size')->where('id',$goods_size_id)->field('goods_id')->find();
			$god=db('goods')->where('id',$shop['goods_id'])->field('shop_id')->find();
			$shop_id=$god['shop_id'];		
			$data=[];
			$data=[
				'uid'=>$uid,
				'goods_size_id'=>$goods_size_id,
				'num'=>$num,
				'shop_id'=>$shop_id,
			];
			$goods_size=db('goods_size')->where('id',$goods_size_id)->field('goods_id,color,spec,fprice,total')->find();
			$data['spec']=$goods_size['spec'];
			$data['color']=$goods_size['color'];
			if($goods_size){
				$goods=db('goods')->where('id',$goods_size['goods_id'])->field('img1,goods_name,yh_price')->find();
				if($goods_size['fprice']==0){
					$data['price']=$goods['yh_price'];
				}else{
					$data['price']=$goods_size['fprice'];
				}
				$data['goods_name']=$goods['goods_name'];
				$data['goods_img']=$goods['img1'];
			}
			$data['dateline']=date('Y-m-d H:i:s',time());
			$res=db('shopping_cart')->insert($data);
		}
		if($res){
			return json(['code'=>200,'msg'=>'购物车添加成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'购物车添加失败','data'=>'']);
		}
	}

	//我的购物车
	function my_cart(){
		$uid=input('uid');
		$res=db('shopping_cart')->where('uid',$uid)->field('shop_id')->select();
		$res=array_unique($res, SORT_REGULAR);
		foreach ($res as $key => $value){
			$find=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
			$res[$key]['shop_name']=$find['shop_name'];
			$goods=db('shopping_cart')->where('shop_id',$value['shop_id'])
			->field('id,goods_size_id,goods_name,goods_img,spec,color,num,price')->select();
			foreach ($goods as $k => $v){
				if(!empty($v['color'])){
					$goods[$k]['size_name']=$v['color'];
				}
				if(!empty($v['spec'])){
					$goods[$k]['size_name']=$goods[$k]['size_name'].' '.$v['spec'];
				}
			}
			$res[$key]['goods']=$goods;
		}
		$res=array_values($res);
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//购物车商品数量更改
	function change_num(){
		$id=input('cart_id');
		if(empty($id)){
			return json(['code'=>101,'msg'=>'缺少参数id','data'=>'']);
		}
		$type=input('type');	//1.加2.减
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$cart=db('shopping_cart')->where('id',$id)->field('num')->find();
		if($type==1){
			$new_num=$cart['num']+1;
		}else{
			$new_num=$cart['num']-1;
		}
		if($new_num<0){
			return json(['code'=>101,'msg'=>'遇到未知错误','data'=>'']);
		}
		$res=db('shopping_cart')->where('id',$id)->update(['num'=>$new_num]);
		if($res){
			return json(['code'=>200,'msg'=>'操作成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'操作失败','data'=>'']);
		}
	}

	//删除购物车
	function del_cart(){
		$ids=input('ids');
		$res=db('shopping_cart')->where('id','in',$ids)->delete();
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
	}
}	