<?php
namespace app\apis\controller;

use think\Controller;

class Order extends Com
{
	//下单
	function sub_order(){
		header("Content-Type: text/html;charset=utf-8");
		$type=input('type');	//1直接下单 2：购物车下单
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$addr_id=input('addr_id');
        if(empty($addr_id)){
            return json(['code'=>101,'msg'=>'缺少参数addr_id','data'=>'']);
        }
        $uid=input('uid');
        if(empty($uid)){
        	return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
        }
        $user=db('user')->where('id',$uid)->field('money')->find();
        $find=db('user_addr')->where('id',$addr_id)->field('uid,name,mobile,province,city,area,address')->find();
        $pay_status=input('pay_status');    //支付方式  1:余额2：支付宝3：微信
        if($type==1){
        	//直接下单
        	$coupons_id=input('coupons_id');    //优惠券
	        $content=input('content');  //留言
	        $goods_size_id=input('goods_size_id');
	        if(empty($goods_size_id)){
	        	return json(['code'=>101,'msg'=>'缺少参数goods_size_id','data'=>'']);
	        }
	        //规格表
	        $goods_sizes=db('goods_size')->where('id',$goods_size_id)->field('color,spec,fprice,goods_id')->find();
	        if(empty($goods_sizes)){
	        	return json(['code'=>101,'msg'=>'发生未知错误','data'=>'']);
	        }
	        //商品表
	        $goods=db('goods')->where('id',$goods_sizes['goods_id'])->field('yh_price,postage,is_activity,activity_id,shop_id,shop_name,goods_name,img1')->find();
	        $num=input('num');
	        if(empty($num)){
	        	return json(['code'=>101,'msg'=>'缺少参数num','data'=>'']);
			}
			$order_sn='QXG'.time().rand('100','999');
	        $data=[
	        	'uid'=>$uid,
	        	'u_name'=>$find['name'],
	        	'u_mobile'=>$find['mobile'],
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
	        if($goods_sizes['fprice']==0.00){
	        	//商品金额
	        	$sp_price=$goods['yh_price'];
	        }else{
	        	$sp_price=$goods_sizes['fprice'];
	        }
	        //商品总金额
	        $price=$sp_price*$num;
	        $new_price=sprintf("%.2f",$price);
	        if(empty($coupon_id)){
	        	//无优惠券
	        	if($goods['is_activity']==2){
	        		//满减
	        		$activity=db('activity')->where('id',$goods['activity_id'])->field('max_price,min_price')->find();
	        		if($new_price>$activity['max_price']){
	        			$new_price=$new_price-$activity['min_price'];
	        			$new_price=sprintf("%.2f",$new_price);
	        		}
	        	}
	        	if($goods['is_activity']==3){
	        		//折扣
	        		$activity=db('activity')->where('id',$goods['activity_id'])->field('max_price')->find();
	        		$new_price=$new_price*($activity['max_price']/10);
	        		$new_price=sprintf("%.2f",$new_price);
	        	}
	        }else{
	        	$coupons=db('coupon')->where('id',$coupon_id)->field('mz_money,yh_money')->find();
	        	if($new_price<$coupons['mz_money']){
	        		return json(['code'=>101,'msg'=>'此优惠券不可用','data'=>'']);
	        	}
	        	$new_price=$new_price-$coupons['yh_money'];
	        	$new_price=sprintf("%.2f",$new_price);
	        }
			$data['price']=$new_price;
			$pt=2;
			if($pay_status==1){
				if($user['money']>$new_price || $user['money']==$new_price){
					$data['status']=2;
					$pt=2;
					if($pay_status==1){
						$data['pay_status']=$pay_status;
					}
				}else{
					$pt=1;
					$data['status']=1;
				}
			}
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
	        	];
	        	db('order_goods')->insert($wheregoods);
	        	if($pt==1){
	        		if($res){
			        	return json(['code'=>200,'msg'=>'余额不足','data'=>$order_sn]);
			        }else{
			        	return json(['code'=>100,'msg'=>'下单失败','data'=>'']);
			        }
	        	}else{
	        		if($res){
			        	return json(['code'=>200,'msg'=>'下单成功','data'=>$order_sn]);
			        }else{
			        	return json(['code'=>100,'msg'=>'下单失败','data'=>'']);
			        }
	        	}
	        }
        }else{
        	//购物车下单购物车ID
			$ids=input('ids');
			//优惠券
			$content=input('content/a');
			$my_yhq=[];
			foreach($content as $key=>$value){
				$r=json_decode($value);
				$my_yhq['shop_id']=$r->shop_id;
				$my_yhq['coupon_id']=$r->coupon_id;
				$my_yhq['content']=$r->content;
				$yhqs[]=$my_yhq;
			}
			//yhqs  二维数组包含商店ID 优惠券ID 留言 
			$data=[];
        	if(empty($ids)){
        		return json(['code'=>101,'msg'=>'缺少参数ids','data'=>'']);
        	}
			$ids_arr=explode(',',$ids);
			//店铺ID
			$shop_ids=[];
			//需支付金额
        	$new_price=0;
        	foreach ($ids_arr as $key => $value){
        		$ids_good=db('shopping_cart')->where('id',$value)->find();
        		$shop_ids[]=$ids_good['shop_id'];
        		$new_price+=$ids_good['price'];
        		$new_price=sprintf("%.2f",$new_price);
        	}
        	$shop_ids=array_unique($shop_ids);
			$order_sn='QXG'.time().rand('100','999');
			$pt=2;
			if($pay_status==1){
				if($user['money']>$new_price || $user['money']==$new_price){
					$status=2;
				}else{
					$status=1;
					$pt=1;
				}
			}
        	foreach ($shop_ids as $key => $value){
        		$shop=db('shop')->where('id',$value)->field('shop_name')->find();
        		$data=[
        			'uid'=>$uid,
        			'shop_id'=>$value,
        			'shop_name'=>$shop['shop_name'],
        			'u_name'=>$find['name'],
        			'u_mobile'=>$find['mobile'],
					'u_addr'=>$find['province'].$find['city'].$find['area'].$find['address'],
					'status'=>$status,
					'order_sn'=>$order_sn,
					'pay_status'=>$pay_status,
        			'dateline'=>time()
				];
				foreach($yhqs as $k=>$v){
					if($v['shop_id']==$value){
						$price_y=db('shopping_cart')->where('shop_id',$v['shop_id'])->select();						
						$price=0;
						foreach($price_y as $val){
							if(in_array($val['id'],$ids_arr)){
								$prc=$val['price']*$val['num'];
								$price+=sprintf("%.2f",$prc);
							}
						}
						if($v['coupon_id']!=''){
							//存在优惠券获取该优惠券信息
							$hq_yhq=db('user_coupon')->where('coupon_id',$v['coupon_id'])->field('endtime')->find();
							if(empty($hq_yhq)){
								return json(['code'=>101,'msg'=>'该优惠券不存在','data'=>'']);
							}
							if($hq_yhq['endtime']<time()){
								return json(['code'=>101,'msg'=>'所用优惠券已过期','data'=>'']);
							}
							$jm_je=db('coupon')->where('id',$v['coupon_id'])->field('mz_money,yh_money')->find();
							//订单写入金额
							$data['price']==sprintf("%.2f",($price-$jm_je['yh_price']));
							$data['coupon_id']=$v['coupon_id'];
						}else{							
							$data['price']=$price;
						}
						$data['content']=$v['content'];
					}
				}
				$res=db('order')->insertGetId($data);
				$goods_size_ids=db('shopping_cart')->where('shop_id',$value)->select();
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
						];
						db('order_goods')->insert($wheregoods);
					}
				}
			}
			if($pt==1){
				if($res){
					return json(['code'=>200,'msg'=>'余额不足','data'=>$order_sn]);
				}else{
					return json(['code'=>100,'msg'=>'下单失败','data'=>'']);
				}
			}else{
				if($res){
					return json(['code'=>200,'msg'=>'下单成功','data'=>$order_sn]);
				}else{
					return json(['code'=>100,'msg'=>'下单失败','data'=>'']);
				}
			}
        }        
	}

	//订单展示
	function show_order(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=15;
		$where['uid']=$uid;
		$type=input('type');//1.待付款、2.待收货、4.待评价、8\9退款/售后
		if($type==1){
			$where['status']=1;
		}
		if($type==2){
			$where['status']=2;
		}
		if($type==3){
			$where['status']=4;
		}
		if($type==4){
			$where['status']=['in','8,9'];
		}
		$where['is_delete']=1;
		$count=db('order')->where($where)->count();
		$count_page = ceil($count/$limit);
		$res=db('order')->where($where)->field('id,order_sn,price,status')->order('dateline desc')->page($p,$limit)->select();
		foreach ($res as $key => $value){			
				$goods=db('order_goods')->where('order_id',$value['id'])->select();
				$num=0;
				foreach($goods as $k=>$v){
					$goods[$k]['size']=$v['color'].' '.$v['spec'];
					$num+=$v['goods_num'];
				}
				$res[$key]['goods']=$goods;
				$res[$key]['num']=$num;
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//查看物流
	function logistics(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'请选择要查询的订单','data'=>'']);
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
		 	return json(['code'=>100,'msg'=>'暂无数据！','data'=>'']);
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
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$type=input('type');	//1.直接下单 2.购物车下单
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$user=db('user')->where('id',$uid)->field('money')->find();
		if($type==1){
			$goods_size_id=input('goods_size_id');
			if(empty($goods_size_id)){
				return json(['code'=>101,'msg'=>'缺少参数goods_size_id','data'=>'']);
			}
			$goods_sizes=db('goods_size')->where('id',$goods_size_id)->field('goods_id,color,fprice,spec,total')->find();
			$goods=db('goods')->where('id',$goods_sizes['goods_id'])
			->field('yh_price,img1,goods_name,shop_name,shop_id,is_activity,activity_id,postage')->find();
			//规格未设置价格
			if($goods_sizes['fprice']==0.00){
				$price=$goods['yh_price'];
			}else{
				$price=$goods_sizes['fprice'];
			}
			if($goods['is_activity']!=1){
				$activity=db('activity')->where('id',$goods['activity_id'])->field('max_price,min_price')->find();
				$data['max_price']=$activity['max_price'];
				$data['min_price']=$activity['min_price'];
			}
			$data['shop_name']=$goods['shop_name'];
			$data['shop_id']=$goods['shop_id'];
			$data['price']=$price;
			$data['is_activity']=$goods['is_activity'];
			$data['img']=$goods['img1'];
			$data['size']=$goods_sizes['color'].' '.$goods_sizes['spec'];
			$data['postage']=$goods['postage'];
			$data['goods_name']=$goods['goods_name'];
			$data['money']=$user['money'];
			if($goods_sizes){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);	
			}else{
				return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);	
			}
		}else{
			$cart_id=input('cart_id');//多个以逗号隔开
			if(empty($cart_id)){
				return json(['code'=>101,'msg'=>'缺少参数cart_id','data'=>'']);
			}
			$cart_id=explode(',',$cart_id);
			$shop_ids=[];
			$shop_id=db('shopping_cart')->where('id','in',$cart_id)->field('shop_id')->select();
			$shop_id=array_unique($shop_id, SORT_REGULAR);
			if($shop_id){
				foreach($shop_id as $key=>$value){
					$shop=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
					$activity_all=db('activity')->where('shop_id',$value['shop_id'])->find();
					$shop_id[$key]['shop_name']=$shop['shop_name'];
					if($activity_all['is_all']==1){
						$shop_id[$key]['type']=$activity_all['type'];
						$shop_id[$key]['max_price']=$activity_all['max_price'];
						$shop_id[$key]['min_price']=$activity_all['min_price'];
					}
					$shop_id[$key]['is_all']=$activity_all['is_all'];	//1.全场 2.不是
					$goods=db('shopping_cart')->where(['shop_id'=>$value['shop_id'],'id'=>['in',$cart_id]])
					->field('goods_size_id,num,color,price,goods_img,goods_name,spec')->select();
					foreach($goods as $k=>$v){
						$goods_size=db('goods_size')->alias('a')->join('goods b','b.id=a.goods_id')
						->field('b.postage,b.is_activity,b.activity_id')->find();
						$goods[$k]['goods_name']=$v['goods_name'];
						$goods[$k]['price']=$v['price'];
						$goods[$k]['size']=$v['color'].' '.$v['spec'];
						$goods[$k]['num']=$v['num'];
						$goods[$k]['goods_img']=$v['goods_img'];
						$goods[$k]['postage']=$goods_size['postage'];
						if($goods_size['is_activity']!=1 && $activity_all['is_all']!=1){
							$activity=db('activity')->where('id',$goods_size['activity_id'])->field('max_price,min_price')->find();
							$goods[$k]['max_price']=$activity['max_price'];
							$goods[$k]['min_price']=$activity['min_price'];
						}
						if($activity_all['is_all']!=1){
							$goods[$k]['is_activity']=$goods_size['is_activity'];
						}
					}
					$shop_id[$key]['goods']=$goods;
				}
			}
			if($shop_id){
				return json(['code'=>200,'msg'=>'获取成功','data'=>$shop_id,'money'=>$user['money']]);
			}else{
				return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
			}
		}

	}

	//显示可使用的优惠券
	function use_yhq(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>101,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$money=input('money');
		if(empty($money)){
			return json(['code'=>101,'msg'=>'缺少参数money','data'=>'']);
		}
		$res=db('user_coupon')->where(['uid'=>$uid,'shop_id'=>$shop_id,'is_use'=>1,'endtime'=>['>',time()]])->field('coupon_id')->select();
		$my_yhq=[];
		foreach($res as $key=>$value){
			$my_yhq[]=$value['coupon_id'];
		}
		$find=db('coupon')->where(['shop_id'=>$shop_id,'mz_money'=>['<=',$money],'end_time'=>['>',time()]])->field('id,mz_money,yh_money')->select();
		foreach($find as $key=>$value){
			if(!in_array($value['id'],$my_yhq)){
				unset($find[$key]);
			}else{
				$find[$key]['title']='省'.$value['yh_money'].'元:满'.$value['mz_money'].'减'.$value['yh_money'];
			}
		}
		$activity=db('activity')->where('shop_id',$shop_id)->field('is_all,type,max_price,min_price')->select();
		if($activity){
			foreach($activity as $key=>$value){
				if($value['type']==2){
					$activity[$key]['title']='本店活动满'.$value['max_price'].'减'.$value['min_price'];
				}else{
					$activity[$key]['title']='本店活动'.$value['max_price'].'折';
				}
			}
		}
		if($find){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$find,'datas'=>$activity]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//取消订单
	function cancel(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->delete();
		if($res){
			db('order_goods')->where('order_id',$order_id)->delete();
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
	}

	//确认收货
	function verify(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->update(['status'=>4]);
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
	}

	//删除订单
	function del_order(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->update(['is_delete'=>2]);
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'删除失败','data'=>'']);
		}
	}

	//订单详情
	function order_details(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order')->where('id',$order_id)->field('u_name,u_mobile,u_addr,price,status,pay_status,freight,coupon_id,order_sn,dateline')->find();
		$res['dateline']=date('Y-m-d',time());
		$goods=db('order_goods')->where('order_id',$order_id)->field('goods_size_id,goods_img,goods_name,goods_num,price,color,spec')->select();
		$goods_all=0;
		foreach($goods as $key=>$value){
			$goods[$key]['size']=$value['color'].' '.$value['spec'];
			$pc=$value['goods_num']*$value['price'];
			$goods_all+=$pc;
		}
		$res['zq']=0;
		$res['goods_all']=$goods_all;
		$res['goods']=$goods;
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂未数据','data'=>'']);
		}		
	}

	//根据订单获取订单
	function get_sale_ym(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$res=db('order_goods')->where('order_id',$order_id)->field('goods_size_id,goods_img,goods_name,goods_num')->select();
		foreach($res as $key=>$value){
			$goods_size=db('goods_size')->where('id',$value['goods_size_id'])->field('goods_id')->find();
			$res[$key]['goods_id']=$goods_size['goods_id'];
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂未数据','data'=>'']);
		}
	}

	//申请售后
	function after_sale(){
		$order_id=input('order_id');
		if(empty($order_id)){
			return json(['code'=>101,'msg'=>'缺少参数order_id','data'=>'']);
		}
		$type=input('type');	//1.换货2.退货
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$content=input('content');
		$img=input('img');	//逗号隔开
		$img=explode(',',$img);
		$where=[];
		$where['order_id']=$order_id;
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
			return json(['code'=>100,'msg'=>'提交失败','data'=>'']);
		}
	}

	//商家评价
	function goods_evaluate(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
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
			$res=db('goods_evaluate')->insertGetId($where);
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