<?php
namespace app\apis\controller;

use think\Controller;

class Goods extends Com
{
	 //商品列表
	function index(){
		$zcate_id=input('zcate_id');
		$type=input('type');  //1.综合2.销量3.价格高到底4.价格低到高5.店铺
		$p=input('p')?input('p'):1;
		$limit=15;
		if($type==5){
			$count=db('shop')->where('status',2)->count();
			$count_page = ceil($count/$limit);
			$res=db('shop')->where('status',2)->field('id,shop_name,shop_logo')->page($p,$limit)->select();
			// foreach ($res as $key => $value) {
			// 	$id=$value['id'];
			// 	$gds=db('goods')->where('shop_id',$id)->field('img1,yh_price')->limit(3)->select();
			// 	if(empty($gds)){
			// 		unset($res[$key]);
			// 	}else{
			// 		$res[$key]['goods']=$gds;
			// 	}
			// }
			foreach($res as $key=>$value){
				$hj=db('goods_evaluate')->where('shop_id',$value['id'])->count();
				$hj_sum=db('goods_evaluate')->where('shop_id',$value['id'])->sum('star');
				if($hj==0){
					$res[$key]['good_hp']='100%';
				}else{
					$hjs=$hj_sum/($hj*5);
					$hjs_n=sprintf("%.2f",$hjs);
					$hjs_nm=$hjs_n*100;
					$res[$key]['good_hp']=$hjs_nm.'%';
				}
			}
		}else{
			if(empty($zcate_id)){
				return json(['code'=>101,'msg'=>'缺少参数zcate_id','data'=>'']);
			}
			$order=[];
			if($type==1){
				$order=['dateline asc'];
			}else if($type==2){
				$order=['sales asc'];
			}else if($type==3){
				$order=['price asc'];
			}else if($type==4){
				$order=['price desc'];
			}
			$where['zcate_id']=$zcate_id;
			$count=db('goods')->where($where)->count();
			$count_page = ceil($count/$limit);			
			$res=db('goods')->where($where)
			->field('id,shop_name,shop_id,img1,goods_name,price,sales')->order($order)->page($p,$limit)->select();
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//搜索列表--店铺内搜索
	function indexs(){
		$shop_id=input('shop_id');
		$name=input('name');
		if(empty($name)){
			return json(['code'=>101,'msg'=>'缺少参数name','data'=>'']);
		}
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=15;
		if($shop_id!=''){
			$wheres['shop_id']=$shop_id;
		}
		$wheres['goods_name']=['like','%'.$name.'%'];
		$find_name=db('history')->where(['uid'=>$uid,'content'=>$name])->find();
		if(!$find_name){
			db('history')->insert(['uid'=>$uid,'content'=>$name]);
		}
		$count=db('goods')->where($wheres)->count();
		$count_page = ceil($count/$limit);
		$res=db('goods')->where($wheres)
		->field('id,shop_name,shop_id,img1,goods_name,yh_price,price,sales')->page($p,$limit)->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}

	}

	//商品详情
	function goods_detail(){
		$uid=input('uid');
		$goods_id=input('goods_id');
		$p=input('p')?input('p'):1;
		if(empty($goods_id)){
			return json(['code'=>101,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$res=db('goods')->where('id',$goods_id)
		->field('shop_id,img1,img2,img3,img4,price,yh_price,goods_name,content,sales,is_activity,activity_title,postage')->find();
		if(!empty($res['img1'])){
			$data['img'][]=$res['img1'];
		}
		if(!empty($res['img2'])){
			$data['img'][]=$res['img2'];
		}
		if(!empty($res['img3'])){
			$data['img'][]=$res['img3'];
		}
		if(!empty($res['img4'])){
			$data['img'][]=$res['img4'];
		}
		$data['price']=$res['price'];
		$data['yh_price']=$res['yh_price'];
		$data['goods_name']=$res['goods_name'];
		$content=$res['content'];
		$content=str_replace('src="','src="http://quxiguan.tainongnongzi.com',$content);
		// $data['content']=$res['content'];
		$data['content']=$content;
		$total_count=db('goods_size')->where('goods_id',$goods_id)->sum('total');
		$data['sales']=$res['sales'];
		$data['total_count']=$total_count;
		$data['is_activity']=$res['is_activity'];
		if($res['postage']==0){
			$data['postage']='免运费';
		}else{
			$data['postage']='运费'.$res['postage'].'元';
		}
		$hj=db('goods_evaluate')->where('shop_id',$res['shop_id'])->count();
		$hj_sum=db('goods_evaluate')->where('shop_id',$res['shop_id'])->sum('star');
		if($hj==0){
			$data['good_hp']='100%';
		}else{
			$hjs=$hj_sum/($hj*5);
			$hjs_n=sprintf("%.2f",$hjs);
			$hjs_nm=$hjs_n*100;
			$data['good_hp']=$hjs_nm.'%';
		}
		$data['activity_title']=$res['activity_title'];
		$find=db('goods_evaluate')->where('goods_id',$goods_id)->field('id,u_logo,u_name,content,dateline,star')->order('dateline desc')->limit(1)->select();
		$shops=db('shop')->where('id',$res['shop_id'])->field('shop_name,shop_logo')->find();
		$collect=db('collection')
		->where(['uid'=>$uid,'type'=>1,'collect'=>$goods_id])->find();
		if($collect){
			//收藏
			$data['is_collect']=1;
		}else{
			//未收藏
			$data['is_collect']=2;
		}
		$data['shop_name']=$shops['shop_name'];
		$data['shop_logo']=$shops['shop_logo'];
		$data['pj']=[];
		if(!empty($find)){
			foreach ($find as $key => $value) {
				$finds=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
				$find[$key]['pj_img']=$finds;
			}
			$data['pj']=$find;
			// if($find){
			// 	$finds=db('goods_evimg')->where('ev_id',$find['id'])->field('img')->select();
			// 	$data['pj']['pj_img']=$finds;
			// }
		}
		$pj_count=db('goods_evaluate')->where('goods_id',$goods_id)->count();
		$data['pj_num']=$pj_count;
		$limit=15;
		$data['tj_goods']=[];
		$count_page=0;
		if($res){
			$count=db('goods')->where('goods_name','like',$res['goods_name'])->count();
			$count_page = ceil($count/$limit);
			$tj_goods=db('goods')->where(['goods_name'=>['like',$res['goods_name']],'id'=>['<>',$goods_id]])->field('id,goods_name,price,img1')->page($p,$limit)->limit(10)->select();
			$data['tj_goods']=$tj_goods;
		}
		$data['yh']=[];
		$yhs=db('coupon')->where('shop_id',$res['shop_id'])->field('id,name,mz_money,yh_money,start_time,end_time')->select();
		if($yhs){
			//已使用
			$user_arr=[];
			//未使用
			$nuser_arr=[];
			//已删除
			$del_arr=[];
			$del_yh=db('coupon_del')->where('uid',$uid)->field('coupon_id')->select();
			if($del_yh){
				foreach($del_yh as $key => $value){
					$del_arr[]=$value['coupon_id'];
				}
			}
			$u_yh=db('user_coupon')->where(['uid'=>$uid,'shop_id'=>$res['shop_id']])->field('coupon_id,is_use')->select();
			if($u_yh){
				foreach($u_yh as $key => $value){
					if($value['is_use']==2){
						//已使用
						$user_arr[]=$value['coupon_id'];
					}else{
						//未使用
						$nuser_arr[]=$value['coupon_id'];
					}
				}
			}
			foreach($yhs as $key => $value){
				if(in_array($value['id'],$user_arr)){
					unset($yhs[$key]);
				}elseif(in_array($value['id'],$del_arr)){
					unset($yhs[$key]);
				}else{
					if(in_array($value['id'],$nuser_arr)){
						$yhs[$key]['is_use']=1;//已领取未使用
					}else{
						$yhs[$key]['is_use']=2;//未领取
					}
				}
			}
			$data['yh']=$yhs;
			$data['shop_id']=$res['shop_id'];
		}
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data,'page'=>$count_page]);
	}

	//商品规格
	function goods_size(){
		$id=input('id');
		if(empty($id)){
			return json(['code'=>101,'msg'=>'缺少参数id','data'=>'']);
		}
		$finds=db('goods_size')->where('goods_id',$id)->field('color,spec')->select();
		$color=[];
		$spec=[];
		foreach ($finds as $key => $value){
			$color[]=$value['color'];
			if(!empty($value['spec'])){
				$spec[]=$value['spec'];
			}
		}
		$color=array_unique($color, SORT_REGULAR);
		$spec=array_unique($spec, SORT_REGULAR);
		if(!empty($color)){
			$data['color']=$color;
		}
		if(!empty($spec)){
			$data['spec']=$spec;
		}
		$data['goods_id']=$id;
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
	}

	//根据规格获取价格，库存
	function get_size(){
		$uid=input('uid');
		$color=input('color');
		$types =input('types');	//1不是2是
		$data=[];
		if(!empty($color)){
			$data['color']=$color;
		}
		$spec=input('spec');
		if(!empty($spec)){
			$data['spec']=$spec;
		}
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>101,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$data['goods_id']=$goods_id;
		$finds=db('goods_size')->where($data)->field('id,fprice,total,goods_id')->find();
		if($finds['fprice']==0.00){
			$find=db('goods')->where('id',$finds['goods_id'])->field('yh_price')->find();
			$prcie=$find['yh_price'];
		}else{
			$prcie=$finds['fprice'];
		}
		if($types==2){
			$energy_mart=db('energy_mart')->where('goods_id',$goods_id)->field('energy_than')->find();
			$energy_b=$prcie*($energy_mart['energy_than']/100);
			$user=db('user')->where('id',$uid)->field('energy_coin')->find();
			if($energy_b>$user['energy_coin']){
				return json(['code'=>101,'msg'=>'您的能量不足','data'=>'']);
			}
		}
		$datas['fprice']=$prcie;
		$datas['total']=$finds['total'];
		$datas['goods_size_id']=$finds['id'];
		$datas['size']=$color.' '.$spec;
		if($finds){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$datas]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败','data'=>'']);
		}
	}

	//商品收藏
	function collection(){
		$uid=input('uid');
		$goods_id=input('goods_id');
		$goods=db('goods')->where('id',$goods_id)->field('img1,goods_name,yh_price,collect_num')->find();
		$find=db('collection')->where(['uid'=>$uid,'collect'=>$goods_id,'type'=>1])->field('id')->find();
		if($find){
			$new_nums=$goods['collect_num']-1;
			if($new_nums<0){
				return json(['code'=>101,'msg'=>'遇到未知错误','data'=>'']);
			}
			db('collection')->where('id',$find['id'])->delete();
			db('goods')->where('id',$goods_id)->update(['collect_num'=>$new_nums]);
			return json(['code'=>200,'msg'=>'取消收藏成功','data'=>0]);
		}
		$data=[
			'uid'=>$uid,
			'type'=>1,
			'collect'=>$goods_id,
			'logo'=>$goods['img1'],
			'title'=>$goods['goods_name'],
			'price'=>$goods['yh_price'],
			'dateline'=>time()
		];
		$res=db('collection')->insert($data);
		if($res){
			$new_num=$goods['collect_num']+1;
			db('goods')->where('id',$goods_id)->update(['collect_num'=>$new_num]);
			return json(['code'=>200,'msg'=>'收藏成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}

	//历史记录
	function history(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('history')->where('uid',$uid)->field('content')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//清空历史记录
	function del_history(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('history')->where('uid',$uid)->delete();
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'删除失败','data'=>'']);
		}
	}

	//商品评价
	function evaluate(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
		}
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>101,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$star=input('star');
		if(empty($star)){
			return json(['code'=>101,'msg'=>'缺少参数star','data'=>'']);
		}
		$img=input('img/a');
		$user=db('user')->where('id',$uid)->field('logo,nickname')->find();
		$goods=db('goods')->where('id',$goods_id)->field('shop_id,img1,price,goods_name')->find();
		$data=[
			'uid'=>$uid,
			'goods_id'=>$goods_id,
			'shop_id'=>$goods['shop_id'],
			'u_logo'=>$user['logo'],
			'u_name'=>$user['nickname'],
			'g_logo'=>$goods['img1'],
			'g_name'=>$goods['goods_name'],
			'g_price'=>$goods['price'],
			'dateline'=>time(),
			'star'=>$star
		];
		$content=input('content');
		if(!empty($content)){
			$data['content']=$content;
		}
		$res=db('goods_evaluate')->insertGetId($data);
		foreach($img as $key=>$value){
			db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
		}
		if($res){
			return json(['code'=>200,'msg'=>'评价成功','data'=>'']);
		}else{
			return json(['code'=>100,'msg'=>'评价失败','data'=>'']);
		}
	}

	//能量商城分类
	function energy_goods_type(){
		$find=db('energy_mart')->field('cate_id')->select();
		$find=array_unique($find, SORT_REGULAR);
		$find=array_merge($find);
		foreach($find as $key=>$value){
			$cate=db('shop_cat')->where('id',$value['cate_id'])->field('name')->find();
			$find[$key]['name']=$cate['name'];
		}
		if($find){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$find]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}		
	}

	//根据分类ID获取能量商城商品
	function get_energy_goods(){
		$cate_id=input('cate_id');
		$p=input('p')?input('p'):1;
		$limit=15;
		$count=db('energy_mart')->where('cate_id',$cate_id)->count();
		$count_page = ceil($count/$limit);
		$res=db('energy_mart')->where('cate_id',$cate_id)->page($p,$limit)->select();
		foreach($res as $key=>$value){
			$goods=db('goods')->where('id',$value['goods_id'])->field('id,goods_name,yh_price,img1')->find();
			$res[$key]['goods_name']=$goods['goods_name'];
			$res[$key]['yh_price']=$goods['yh_price'];
			$res[$key]['img']=$goods['img1'];
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}

	}
}	