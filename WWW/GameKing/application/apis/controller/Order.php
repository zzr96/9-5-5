<?php
namespace app\apis\controller;

use think\Controller;
use app\apis\controller\Porder;

class Order extends Com
{
	//虚拟物品下单
	function  xn_order(){
		header("Content-Type: text/html;charset=utf-8");
		$type=input('type');//1：直接下单  2：购物车下单
		if(empty($type)){
			return json(['code'=>400,'msg'=>'缺少参数type','data'=>'']);
		}
		$uid=input('uid');
        if(empty($uid)){
        	return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
        }
        $user=db('member')->where('uid',$uid)->field('balance,phone,nickname')->find();//查找余额
        $content=input('content');  //留言
        $pay_status=input('pay_status');    //支付方式  1:余额2：支付宝3：微信
        if($type==1){
        	//直接下单虚拟物品
        	$game_character=input("character");//游戏角色
        	$game_zone=input('zone');//游戏区服
        	$game_account=input('account');//游戏账号
        	$goods_size_id=input('goods_size_id');//商品规格值
	        if(empty($goods_size_id)){
	        	return json(['code'=>400,'msg'=>'缺少参数goods_size_id','data'=>'']);
	        }
	        $goods_sizes=db('goods_size')->where('id',$goods_size_id)->field('color,spec,fprice,good_id')->find();
	        if(empty($goods_sizes)){
	        	return json(['code'=>400,'msg'=>'发生未知错误','data'=>'']);
	        }
	         //商品表
	        $goods=db('goods')->where('id',$goods_sizes['good_id'])->field('price,postage,shop_id,shop_name,goods_name,img1')->find();
	        $num=input('num');//商品数量
	        if(empty($num)){
	        	return json(['code'=>400,'msg'=>'缺少参数num','data'=>'']);
			}
			$order_sn='YXDW'.time().rand('100','999');
		        $data=[
		        	'uid'=>$uid,
		        	'u_name'=>$user['nickname'],
		        	'u_mobile'=>$user['phone'],
		        	'order_sn'=>$order_sn,
		        	'dateline'=>time(),
				];
	        if(empty($content)){
	        	$data['content']='暂无留言';
	        }else{
	        	$data['content']=$content;
	        }
	        if($goods){
	        	$data['shop_id']=$goods['shop_id'];
	        	$data['shop_name']=$goods['shop_name'];

	        }
	        if($goods_sizes['fprice']==0){
	        	//商品金额
	        	$sp_price=$goods['price'];
	        }else{
	        	$sp_price=$goods_sizes['fprice'];
	        }
	        //商品总金额
	        $price=$sp_price*$num;
	        $new_price=sprintf("%.2f",$price);
			$data['price']=$new_price;
			$data['status']=1;
	        $res=db('order')->insertGetId($data);
	        if($res){
	        	$wheregoods=[
	        		'order_id'=>$res,
	        		'goods_size_id'=>$goods_size_id,
	        		'goods_img'=>$goods['img1'],
	        		'goods_name'=>$goods['goods_name'],
	        		'goods_num'=>$num,
	        		'color'=>$goods_sizes['color'],
	        		'spec'=>$goods_sizes['spec'],
	        		'price'=>$sp_price,
	        		'order_sn'=>$order_sn,
	        		'game_character'=>$game_character,
	        		'game_zone'=>$game_zone,
	        		'game_account'=>$game_account
	        	];
	        	db('order_goods')->insert($wheregoods);
	        	$aa=controller('Porder');
				$new_price=(int)($new_price*100);
	        	return $aa->pay($pay_status,$order_sn,$new_price);
	        }
        }else{
        	//购物车下单购物车ID
			$ids=input('ids');
			if(empty($ids)){
        		return json(['code'=>400,'msg'=>'缺少参数ids','data'=>'']);
        	}
        	$find=db('cart')->where('id',$ids)->find();
        	$aa=db("shop")->where('id',$find['shop_id'])->field('shop_name')->find();
        	$new_price=$find['num']*$find['price'];
        	$new_price=sprintf("%.2f",$new_price);
        	$order_sn='YXDW'.time().rand('100','999');
        	$game_character=input("character");//游戏角色
        	$game_zone=input('zone');//游戏区服
        	$game_account=input('account');//游戏账号
	        $data=[
        			'uid'=>$uid,
        			'shop_id'=>$find['shop_id'],
        			'shop_name'=>$aa['shop_name'],
        			'u_name'=>$user['nickname'],
        			'u_mobile'=>$user['phone'],
					'status'=>1,
					'order_sn'=>$order_sn,
					'pay_status'=>$pay_status,
        			'dateline'=>time(),
        			'price'=>$new_price,
        			'pay_time'=>date("Y-m-d H:i:s"),
				];
			if(empty($content)){
	        	$data['content']='暂无留言';
	        }else{
	        	$data['content']=$content;
	        }
			$res=db('order')->insertGetId($data);
			if($res){
	        	$wheregoods=[
	        		'order_id'=>$res,
	        		'goods_size_id'=>$find['goods_size_id'],
	        		'goods_img'=>$find['goods_img'],
	        		'goods_name'=>$find['goods_name'],
	        		'goods_num'=>$find['num'],
	        		'color'=>$find['color'],
	        		'spec'=>$find['spec'],
	        		'price'=>$find['price'],
	        		'order_sn'=>$order_sn,
	        		'game_character'=>$game_character,
	        		'game_zone'=>$game_zone,
	        		'game_account'=>$game_account
	        	];
	        	db('order_goods')->insert($wheregoods);
	        }
	        db('cart')->where("id","in",$ids)->delete();
	        $aa=controller('Porder');
			$new_price=(int)($new_price*100);
	        return $aa->pay($pay_status,$order_sn,$new_price);
        }
	}
	//下单
	function sub_order(){
		header("Content-Type: text/html;charset=utf-8");
		$type=input('type');	//1直接下单 2：购物车下单
		if(empty($type)){
			return json(['code'=>400,'msg'=>'缺少参数type','data'=>'']);
		}
		$addr_id=input('addr_id');
        $uid=input('uid');
        if(empty($uid)){
        	return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
        }
        $user=db('member')->where('uid',$uid)->field('balance')->find();//查找余
       	$find=db('address')->where('id',$addr_id)->field('uid,uname,tel,province,city,area,address')->find();
        $pay_status=input('pay_status');    //支付方式  1:余额2：支付宝3：微信
        if($type==1){
        	//直接下单
	        $content=input('content');  //留言
	        $goods_size_id=input('goods_size_id');
	        if(empty($goods_size_id)){
	        	return json(['code'=>400,'msg'=>'缺少参数goods_size_id','data'=>'']);
	        }
	        //规格表
	        //查找商品的规格
	        $goods_sizes=db('goods_size')->where('id',$goods_size_id)->field('color,spec,fprice,good_id')->find();
	        if(empty($goods_sizes)){
	        	return json(['code'=>400,'msg'=>'发生未知错误','data'=>'']);
	        }
	        //商品表
	        $goods=db('goods')->where('id',$goods_sizes['good_id'])->field('price,postage,shop_id,shop_name,goods_name,img1')->find();
	        $num=input('num');//商品数量
	        if(empty($num)){
	        	return json(['code'=>400,'msg'=>'缺少参数num','data'=>'']);
			}
			$order_sn='YXDW'.time().rand('100','999');
		        $data=[
		        	'uid'=>$uid,
		        	'u_name'=>$find['uname'],
		        	'u_mobile'=>$find['tel'],
		        	'u_addr'=>$find['province'].$find['city'].$find['area'].$find['address'],
		        	'order_sn'=>$order_sn,
		        	'dateline'=>time()
				];
	        if(empty($content)){
	        	$data['content']='暂无留言';
	        }else{
	        	$data['content']=$content;
	        }
	        if($goods){
	        	$data['shop_id']=$goods['shop_id'];
	        	$data['shop_name']=$goods['shop_name'];

	        }
	        if($goods_sizes['fprice']==0){
	        	//商品金额
	        	$sp_price=$goods['price'];
	        }else{
	        	$sp_price=$goods_sizes['fprice'];
	        }
	        //商品总金额
	        $price=$sp_price*$num;
	        $new_price=sprintf("%.2f",$price);
			$data['price']=$new_price;
            $data['pay_status'] = $pay_status;
			$data['status']=1;
	        $res=db('order')->insertGetId($data);
	        if($res){
	        	$wheregoods=[
	        		'order_id'=>$res,
	        		'goods_size_id'=>$goods_size_id,
	        		'goods_img'=>$goods['img1'],
	        		'goods_name'=>$goods['goods_name'],
	        		'goods_num'=>$num,
	        		'color'=>$goods_sizes['color'],
	        		'spec'=>$goods_sizes['spec'],
	        		'price'=>$sp_price,
	        		'order_sn'=>$order_sn,
	        	];
	        	db('order_goods')->insert($wheregoods);
	        	$aa=controller('Porder');
	        	$new_price=(int)($new_price*100);
	        	return $aa->pay($pay_status,$order_sn,$new_price);
	        }
        }else{
        	//购物车下单购物车ID
			$ids=input('ids');
			//$list='[{"shop_id":6,"name":"name1"},{"shop_id":5,"name":"name2"}]';
			$aa = $_POST['list'];
			//$aa=json_decode($list,true);
			//print_r($aa);die;
			$data=[];
        	if(empty($ids)){
        		return json(['code'=>400,'msg'=>'缺少参数ids','data'=>'']);
        	}
			$ids_arr=explode(',',$ids);
			//店铺ID
			$shop_ids=[];
			//需支付金额
        	$new_price=0;
        	foreach ($ids_arr as $key => $value){
        		$ids_good=db('cart')->where('id',$value)->find();
        		$shop_ids[]=$ids_good['shop_id'];
        		$new_price+=$ids_good['price']*$ids_good['num'];
        		$new_price=sprintf("%.2f",$new_price);
        	}
        	$shop_ids=array_unique($shop_ids);
			$order_sn='YXDW'.time().rand('100','999');
			//print_r($shop_ids);
        	foreach ($shop_ids as $key => $value){

        		$shop=db('shop')->where('id',$value)->field('shop_name')->find();
        		$data=[
        			'uid'=>$uid,
        			'shop_id'=>$value,
        			'shop_name'=>$shop['shop_name'],
        			'u_name'=>$find['uname'],
        			'u_mobile'=>$find['tel'],
					'u_addr'=>$find['province'].$find['city'].$find['area'].$find['address'],
					'status'=>1,
					'order_sn'=>$order_sn,
					'pay_status'=>$pay_status,
        			'dateline'=>time(),
        			'price'=>$new_price,
        			'pay_time'=>date("Y-m-d H:i:s"),
				];
				foreach ($aa as $a => $b) {
        			if($b['shop_id']==$value){
        				$data['content']=$b['name'];
        			}
        		}
				$res=db('order')->insertGetId($data);
				$goods_size_ids=db('cart')->where('shop_id',$value)->select();
				foreach($goods_size_ids as $k=>$v){
					if(in_array($v['id'],$ids_arr)){
						$wheregoods=[
							'order_id'=>$res,
							'goods_size_id'=>$v['goods_size_id'],
							'goods_img'=>$v['goods_img'],
							'goods_name'=>$v['goods_name'],
							'goods_num'=>$v['num'],
							'color'=>$v['color'],
							'spec'=>$v['spec'],
							'price'=>$v['price'],
							'order_sn'=>$order_sn,
						];
						db('order_goods')->insert($wheregoods);
					}
				}
			}
			db('cart')->where("id","in",$ids)->delete();
			$aa=controller('Porder');
			$new_price=(int)($new_price*100);
	        return $aa->pay($pay_status,$order_sn,$new_price);
        }
	}
	//我的订单展示
	function show_order(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=15;
		$where['uid']=$uid;
		$type=input('type');//1.待付款、2.待发货、3.待收获、4.待评价
		if($type==1){
			$where['status']=1;
		}
		if($type==2){
			$where['status']=2;
		}
		if($type==3){
			$where['status']=3;
		}
		if($type==4){
			$where['status']=4;
		}
		$where['is_delete']=1;
		$count=db('order')->where($where)->count();
		$count_page = ceil($count/$limit);
		/*$res=db('order')->where($where)->field('order_sn,status')->order('dateline desc')->page($p,$limit)->group('order_sn')->select();
		$num=0;
		$money=0;
		foreach ($res as $key => $value){
				$shops=db('order')->where('order_sn',$value['order_sn'])->field('id,shop_id,shop_name')->select();
				$res[$key]['shops']=$shops;
				foreach ($shops as $k => $v) {
					$num+=db('order_goods')->where('order_id',$v['id'])->field('goods_size_id,goods_name')->count();
					$aa=db('order_goods')->where('order_id',$v['id'])->select();
					$num=db('order_goods')->where('order_id',$v['id'])->count();
					foreach ($aa as $b => $c) {
						if($c['color']=='无'){
							unset($aa[$b]['color']);
							unset($aa[$b]['spec']);
						}else{
							unset($aa[$b]['game_character']);
							unset($aa[$b]['game_zone']);
							unset($aa[$b]['game_account']);
						}
						$money+=$c['goods_num']*$c['price'];
					}
					$res[$key]['shops'][$k]['goods']=$aa;
				}
		$res[$key]['num']=$num;
		$res[$key]['money']=$money;
		}*/
		$num=0;
		$res=db('order')->where($where)->field('id,status,shop_name,freight')->order('dateline desc')->where('is_delete=1 && status < 6')->page($p,$limit)->select();
		foreach ($res as $key => $value) {
			if($value['status']==1){
				$order_sn='YXDW'.time().rand('100','999');
				db('order')->where('id',$value['id'])->update(['order_sn'=>$order_sn]);
				$sn=db('order_goods')->where('order_id',$value['id'])->select();
				foreach ($sn as $s => $n) {
					db('order_goods')->where('id',$n['id'])->update(['order_sn'=>$order_sn]);
				}
			}
			$money=0;
			$res[$key]['num']=db('order_goods')->where('order_id',$value['id'])->count();
			$aa=db('order_goods')->where('order_id',$value['id'])->select();
			foreach ($aa as $k => $v) {
				$list=db('goods_size')->where('id',$v['goods_size_id'])->find();
				$aa[$k]['goods_id']=$list['good_id'];
				$bb=db('goods')->where('id',$list['good_id'])->find();
				$res[$key]['is_xn']=$bb['is_xn'];
				$money+=$v['price']*$v['goods_num'];
				if($v['color']=='无'){
					unset($aa[$k]['color']);
					unset($aa[$k]['spec']);
					unset($res[$key]['freight']);
				}else{
					unset($aa[$k]['game_character']);
					unset($aa[$k]['game_account']);
					unset($aa[$k]['game_zone']);
				}
				unset($aa[$k]['order_sn']);
			}
			$res[$key]['money']=$money;
			$res[$key]['goods']=$aa;
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//查看物流
	function logistics(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'请选择要查询的订单','data'=>'']);
		}
		$kd = db('order')->where('id',$order_id)->field('kd_name,kd_num,order_sn')->find();
		$kds=db('kd')->where('b_name',$kd['kd_name'])->field('name,logo')->find();
		$url="http://www.kuaidi100.com/query?type=".$kd['kd_name']."&postid=".$kd['kd_num'];
		$data=$this->curl_get($url);
        $data=json_decode($data);
       	$data=$data->data;
       	foreach ($data as $key => $value){
       		$data[$key]=(array)$value;
       	}
       	$datas['kd_num']=$kd['kd_num'];
		$datas['name']=$kds['name'];
		$datas['logo']=$kds['logo'];
		$datas['order_sn']=$kd['order_sn'];
		if($data){
		 	return json(['code'=>200,'msg'=>'获取成功！','data'=>$data,'datas'=>$datas]);
		}else{
		 	return json(['code'=>400,'msg'=>'暂无数据！','data'=>'']);
		}
	}

	function curl_get($url){
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content=curl_exec($ch);
        curl_close($ch);
        return($content);
	}
	//提交订单展示页面
	function show_sub_order(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$type=input('type');	//1.直接下单 2.购物车下单
		if(empty($type)){
			return json(['code'=>400,'msg'=>'缺少参数type','data'=>'']);
		}
		$user=db('member')->where('uid',$uid)->field('balance')->find();
		if($type==1){
			$num=input('num');
			if(empty($num)){
				return json(['code'=>400,'msg'=>'缺少参数num','data'=>'']);
			}
			$goods_size_id=input('goods_size_id');
			if(empty($goods_size_id)){
				return json(['code'=>400,'msg'=>'缺少参数goods_size_id','data'=>'']);
			}
			$goods_sizes=db('goods_size')->where('id',$goods_size_id)->field('good_id,color,fprice,spec,total')->find();
			if($goods_sizes['color']=='无'){
				$addr='';
				$goods=db('goods')->where('id',$goods_sizes['good_id'])
				->field('price,img1,goods_name,shop_name,shop_id,is_xn')->find();
				//规格未设置价格
				if($goods_sizes['fprice']==0){
					$price=$goods['price'];
				}else{
					$price=$goods_sizes['fprice'];
				}
				$data['sum_price']=$price*$num;
				$data['num']=$num;
				$data['shop_name']=$goods['shop_name'];
				$data['shop_id']=$goods['shop_id'];
				$data['price']=$price;
				$data['img']=$goods['img1'];
				$data['goods_name']=$goods['goods_name'];
				$data['balance']=$user['balance'];
				$data['is_xn']=$goods['is_xn'];
			}else{
				$addr=db('address')->where("uid=$uid && is_default")->field('uname,id,tel,province,city,area,address')->find();
				$goods=db('goods')->where('id',$goods_sizes['good_id'])
				->field('price,img1,goods_name,shop_name,shop_id,postage,is_xn')->find();
				//规格未设置价格
				if($goods_sizes['fprice']==0){
					$price=$goods['price'];
				}else{
					$price=$goods_sizes['fprice'];
				}
				$data['sum_price']=$price*$num;
				$data['num']=$num;
				$data['shop_name']=$goods['shop_name'];
				$data['shop_id']=$goods['shop_id'];
				$data['price']=$price;
				$data['img']=$goods['img1'];
				$data['size']=$goods_sizes['color'].' '.$goods_sizes['spec'];
				$data['postage']=$goods['postage'];
				$data['goods_name']=$goods['goods_name'];
				$data['is_xn']=$goods['is_xn'];
			}
			if($goods_sizes){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$data,'addr'=>$addr]);
			}else{
				return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
			}
		}else{
			$cart_id=input('cart_id');//多个以逗号隔开
			if(empty($cart_id)){
				return json(['code'=>400,'msg'=>'缺少参数cart_id','data'=>'']);
			}
			$cart_id=explode(',',$cart_id);
			$shop_ids=[];
			$shop_id=db('cart')->where('id','in',$cart_id)->field('shop_id,is_xn')->select();
			$shop_id=array_unique($shop_id, SORT_REGULAR);
			if($shop_id){
				$all_price=0;
				$postage=0;
				foreach($shop_id as $key=>$value){
					if($value['is_xn']==2){
						$xn=2;
						$new_price=0;
						$shop=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
						$shop_id[$key]['shop_name']=$shop['shop_name'];
						$goods=db('cart')->where(['shop_id'=>$value['shop_id'],'id'=>['in',$cart_id]])
						->field('goods_size_id,num,goods_img,goods_name,price')->select();
						foreach($goods as $k=>$v){
							$goods[$k]['goods_name']=$v['goods_name'];
							$goods[$k]['price']=$v['price'];
							$goods[$k]['num']=$v['num'];
							$goods[$k]['goods_img']=$v['goods_img'];
							$new_price+=$v['price']*$v['num'];;
	        				$new_price=sprintf("%.2f",$new_price);
						}
						$shop_id[$key]['goods']=$goods;
						$shop_id[$key]['sum_price']=$new_price;
						$all_price+=$new_price;
						$addr='';
					}else{
						$new_price=0;
						$xn=1;
						$addr=db('address')->where("uid=$uid && is_default")->field('uname,id,tel,province,city,area,address')->find();
						$shop=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
						$shop_id[$key]['shop_name']=$shop['shop_name'];
						$goods=db('cart')->where(['shop_id'=>$value['shop_id'],'id'=>['in',$cart_id]])
						->field('goods_size_id,num,color,price,goods_img,goods_name,spec')->select();
						foreach($goods as $k=>$v){
							$goods_size=db('goods_size')->alias('a')->join('goods b','b.id=a.good_id')
							->field('b.postage,b.is_xn')->find();
							$goods[$k]['goods_name']=$v['goods_name'];
							$goods[$k]['price']=$v['price'];
							$goods[$k]['size']=$v['color'].' '.$v['spec'];
							$goods[$k]['num']=$v['num'];
							$goods[$k]['goods_img']=$v['goods_img'];
							$postage=$goods_size['postage'];
							$new_price+=$v['price']*$v['num'];;
	        				$new_price=sprintf("%.2f",$new_price);
						}
						$shop_id[$key]['goods']=$goods;
						$shop_id[$key]['sum_price']=$new_price;
						$all_price+=$new_price;
						$postage+=$postage;
					}

				}
				//print_r($shop_id);die;
			}
			if($shop_id){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$shop_id,'all_price'=>$all_price,'is_xn'=>$xn,'postage'=>$postage,'addr'=>$addr]);
			}else{
				return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
			}
		}

	}
	//取消订单
	function cancel(){
		$order_id=input('order_id');
		$reason=input('reason');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->update(['is_delete'=>2,'reason'=>"$reason"]);
		//$res=db('order')->where('id',$order_id)->delete();
		if($res){
			//db('order_goods')->where('order_id',$order_id)->delete();
			return json(['code'=>200,'msg'=>'取消成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
	}

	//确认收货
	function verify(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->update(['status'=>4,'updatetime'=>time()]);
		if($res){
			return json(['code'=>200,'msg'=>'收货成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
	}

	//删除订单
	function del_order(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->delete();
		if($res){
			db('order_goods')->where('order_id',$order_id)->delete();
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'删除失败','data'=>'']);
		}
	}

	//订单详情
	function order_details(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->field('u_name,u_mobile,u_addr,price,status,pay_status,freight,order_sn,dateline,content,shop_name,shop_id,dateline,updatetime')->find();
		$res['dateline']=date('Y-m-d H:i:s',$res['dateline']);
		$res['updatetime']=date('Y-m-d H:i:s',$res['updatetime']);
		$sid=db('shop')->where('id',$res['shop_id'])->find();
		$res['sid']=$sid['uid'];
		$goods=db('order_goods')->where('order_id',$order_id)->field('goods_size_id,goods_img,goods_name,goods_num,price,color,spec,game_account,game_character,game_zone,id')->select();
		$money=0;
		foreach($goods as $key=>$value){
			$aa=db('goods_size')->where('id',$value['goods_size_id'])->find();
			$bb=db('goods')->where('id',$aa['good_id'])->find();
			$res['is_xn']=$bb['is_xn'];
			$goods[$key]['id']=$value['id'];
			if($value['color']=="无"){
				unset($goods[$key]['color']);
				unset($goods[$key]['spec']);
			}else{
				unset($goods[$key]['game_account']);
				unset($goods[$key]['game_zone']);
				unset($goods[$key]['game_character']);
			}
			$money+=$value['goods_num']*$value['price'];
			$arr=db('goods_size')->alias('a')->join('goods b','b.id=a.good_id')->where('a.id',$value['goods_size_id'])->field('b.shop_name,b.shop_id')->find();
			/*$aa=db('goods_size')->where('id',$value['goods_size_id'])->find();
			$bb=db('goods')->where('id',$aa['good_id'])->find();
			$goods[$key]['shop_name']=$bb['shop_name'];
			$goods[$key]['shop_id']=$bb['shop_id'];*/
		}
		//$res['zq']=0;
		$res['momey']=$money;
		$res['goods']=$goods;
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>400,'msg'=>'暂未数据','data'=>'']);
		}
	}

	//根据订单号获取订单
	function get_sale_ym(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order_goods')->where('order_id',$order_id)->field('id,goods_size_id,goods_img,goods_name,goods_num,price')->select();
		$num_price=0;
		foreach($res as $key=>$value){
			$num_price+=$value['price']*$value['goods_num'];
			$goods_size=db('goods_size')->where('id',$value['goods_size_id'])->field('good_id,spec,color')->find();
			$is_xn=db('goods')->where('id',$goods_size['good_id'])->find();
			$res[$key]['is_xn']=$is_xn['is_xn'];
			$res[$key]['goods_id']=$goods_size['good_id'];
			$res[$key]['size']=$goods_size['spec']." ".$goods_size['color'];
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'num_price'=>$num_price]);
		}else{
			return json(['code'=>400,'msg'=>'暂未数据','data'=>'']);
		}
	}
	//申请售后
	function after_sale(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$type=input('type');	//1.未发货2.已发货
		if(empty($type)){
			return json(['code'=>400,'msg'=>'缺少参数type','data'=>'']);
		}
		$content=input('content');
		$img=input('img');	//逗号隔开
		$img=explode(',',$img);
		$where=[];
		$where['order_id']=$order_id;
		$where['uid']=$uid;
		$where['type']=$type;
		if($content!=''){
			$where['content']=$content;
		}
		$where['dateline']=time();
		$res=db('order_back')->insertGetId($where);
		db('order')->where('id',$order_id)->update(['status'=>6]);
		if($res){
			if(!empty($img)){
				foreach($img as $key=>$value){
					db('oback_img')->insert(['back_id'=>$res,'img'=>$value]);
				}
			}
			return json(['code'=>200,'msg'=>'提交成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'提交失败','data'=>'']);
		}
	}
	//订单评价
	function order_evaluate(){
		$order_id=input('order_id');
		$content=input('content');
		$img=input('img');
		$type=input('type');
		$star=input('star');
		$uid=input('uid');
		$user=db('member')->where('uid',$uid)->field('avatar,nickname')->find();
		if($type==1){
			$logo="匿名";
		}else{
			$logo=$user['avatar'];
		}
		$arr=db('order_goods')->where('order_id',$order_id)->select();
		foreach ($arr as $key => $value) {
			if($value['color']=="无"){
				$data['goods_size']=$value['game_character'].$value['game_zone'].$value['game_account']."".$value['goods_num'];
			}else{
				$data['goods_size']=$value['color'].$value['spec']."".$value['goods_num'];
			}
			$goods_size=db('goods_size')->where('id',$value['goods_size_id'])->find();
			$goods=db('goods')->where('id',$goods_size['good_id'])->find();
			$data['uid']=$uid;
			$data['star']=$star;
			$data['u_logo']=$user['avatar'];
			$data['u_name']=$user['nickname'];
			$data['good_id']=$goods_size['good_id'];
			$data['shop_id']=$goods['shop_id'];
			$data['g_name']=$goods['goods_name'];
			$data['g_logo']=$goods['img1'];
			$data['content']=$content;
			$data['g_price']=$goods['price'];
			$data['date']=date("Y-m-d H:i:s");
			$res=db('good_evaluate')->insertGetId($data);
			if($img){
				if(is_array($img)){
				foreach($img as $key=>$value){
					db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
					}
				}else{
					db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$img]);
				}
			}
		}
		if($arr){
			db('order')->where('id',$order_id)->update(['status'=>5]);
			return json(['code'=>200,'msg'=>'评价成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'评价失败','data'=>'']);
		}
	}
	//商家评价
	function goods_evaluate(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$order_id=input('order_id');
		$user=db('user')->where('id',$uid)->field('nickname,logo')->find();
		$list=input('list');
		$list=json_decode($list);
		// var_dump($list);exit;
		$my_data=[];
		foreach($list as $key=>$value){
			$r=$value;
			$my_data['goods_id']=$r->goods_id;
			$my_data['star']=$r->star;
			$my_data['is_name']=$r->is_name;
			$my_data['content']=$r->content;
			$my_data['img']=$r->img;
			$datas[]=$my_data;
		}
		//datas  二维数组包含商店ID 优惠券ID 留言
		foreach($datas as $key=>$value){
			$img=$value['img'];	//逗号隔开
			$img=explode(',',$img);
			$is_name=$value['is_name'];	//1：匿名 2：不是匿名
			$goods_id=$value['goods_id'];
			$goods=db('goods')->where('id',$goods_id)->field('goods_name,yh_price,img1,shop_id')->find();
			$star=$value['star'];	//0-5
			$content=$value['content'];
			$where=[];
			if($is_name=='ture'){
				$where['u_name']='匿名';
			}else{
				$where['u_name']=$user['nickname'];
			}
			$where=[
				'uid'=>$uid,
				'goods_id'=>$goods_id,
				'shop_id'=>$goods['shop_id'],
				'u_logo'=>$user['logo'],
				'u_name'=>$user['nickname'],
				'g_logo'=>$goods['img1'],
				'g_name'=>$goods['goods_name'],
				'g_price'=>$goods['yh_price'],
				'star'=>$star,
				'content'=>$content,
				'dateline'=>time()
			];
			$res=db('good_evaluate')->insertGetId($where);
			if($res){
				if(!empty($img)){
					foreach($img as $key=>$value){
						db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
					}
				}
			}
		}
		$rec=db('order')->where('id',$order_id)->update(['status'=>5]);
		return json(['code'=>200,'msg'=>'提交成功','data'=>'']);
	}
}