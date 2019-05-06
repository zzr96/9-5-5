<?php
namespace app\apis\controller;

use think\Controller;

class Shop extends Com
{
	/**
     * get: 用户收藏店铺功能
     * path: collect_shop
     * method: collect_shop
     * param: uid - {string} 用户id;shop_id:商家id
     */
	//收藏店铺
	function collect_shop(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'itemid'=>$shop_id,
			'createtime'=>date("Y-m-d H:i:s")
		];
		$finds=db('fav_merchant')
		->where(['uid'=>$uid,'itemid'=>$shop_id])->field('id')->find();
		if($finds){
			db('fav_merchant')->where('id',$finds['id'])->delete();
			return json(['code'=>200,'msg'=>'取消关注成功','data'=>'']);
		}
		$res=db('fav_merchant')->insert($data);
		if($res){
			return json(['code'=>200,'msg'=>'店铺关注成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'店铺关注失败','data'=>'']);
		}
	}
	/**
     * get: 用户收藏店铺功能
     * path: get_shop
     * method: get_shop
     * param: uid - {string} 用户id;shop_id:商家id
     */
	//根据商店ID获取店铺信息
	function get_shop(){
		$uid=input('uid');
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$data=db('shop')->where('id',$shop_id)->field('uid,shop_name,shop_logo')->find();
		/*$where['shop_id']=$shop_id;
		$data['shop_name']=$res['shop_name'];
		$data['shop_logo']=$res['shop_logo'];
		$data['shop_num']=$shop_id.'987987'.$shop_id;
		$data['shop_id']=$shop_id;*/
		//收藏
		$collect_num=db('fav_merchant')->where(['itemid'=>$shop_id])->count();
		$data['collect_num']=$collect_num;
		if($uid){
			$my_collect=db('fav_merchant')->where(['itemid'=>$shop_id,'uid'=>$uid])->find();
			if($my_collect){
				$data['is_collect']=1;	//已收藏
			}else{
				$data['is_collect']=2;	//未收藏
			}
		}else{
			$data['is_collect']=2;	//未收藏
		}
		//好评率
		$hj=db('good_evaluate')->where('shop_id',$shop_id)->count();
		$hj_sum=db('good_evaluate')->where('shop_id',$shop_id)->sum('star');
		if($hj==0){
			$data['good_hp']='100%';
		}else{
			$hjs=$hj_sum/($hj*5);
			$hjs_n=sprintf("%.2f",$hjs);
			$hjs_nm=$hjs_n*100;
			$data['good_hp']=$hjs_nm.'%';
		}
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
		//好评率
		/*$hj=db('goods_evaluate')->where('shop_id',$shop_id)->count();
		$hj_sum=db('goods_evaluate')->where('shop_id',$shop_id)->sum('star');
		if($hj==0){
			$data['good_hp']='100%';
		}else{
			$hjs=$hj_sum/($hj*5);
			$hjs_n=sprintf("%.2f",$hjs);
			$hjs_nm=$hjs_n*100;
			$data['good_hp']=$hjs_nm.'%';
		}*/
		//优惠券
		/*$data['yh']=[];
		$yhs=db('coupon')->where('shop_id',$shop_id)->field('id,name,mz_money,yh_money,start_time,end_time')->select();
		if($yhs && $uid){
			//已使用
			$user_arr=[];
			//未使用
			$nuser_arr=[];
			//删除的
			$del_arr=[];
			$del_yh=db('coupon_del')->where('uid',$uid)->field('coupon_id')->select();
			if($del_yh){
				foreach($del_yh as $key => $value){
					$del_arr[]=$value['coupon_id'];
				}
			}
			$u_yh=db('user_coupon')->where(['uid'=>$uid,'shop_id'=>$shop_id])->field('coupon_id,is_use')->select();
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
			foreach ($yhs as $key => $value){
				if(in_array($value['id'],$user_arr)){
					unset($yhs[$key]);
				}elseif(in_array($value['id'],$del_arr)){
					unset($yhs[$key]);
				}else{
					if(in_array($value['id'],$nuser_arr)){
						$yhs[$key]['is_use']=1;//已领取未使用
					}else{
						$yhs[$key]['is_use']=2;  //未领取
					}
				}
			}
			$data['yh']=$yhs;
		}else{
			$data['yh']=$yhs;
		}
		$titlss=db('activity')->where('shop_id',$shop_id)->field('title')->find();
		if(empty($titlss)){
			$data['title']='本店铺暂未开展任何活动';
		}else{
			$data['title']=$titlss['title'];
		}*/
	}
	//搜索店铺里的商品
	/**
     * post: 搜索商品
     * path: get_shop
     * method: get_shop
     * param: goods_name- 商品名称关键词;shop_id:商家id
     */
	function ser_shop_goods(){
		$type=input('type');	//1.综合2.销量从高到底 3销量从低到高.4.价格高到底5.价格低到高
		$order=[];
		if($type==1){
			$order=['id asc'];
		}else if($type==2){
			$order=['sales asc'];
		}else if($type==3){
			$order=['sales desc'];
		}else if($type==4){
			$order=['price asc'];
		}else if($type==5){
			$order=['price desc'];
		}
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$goods_name=input('goods_name');
		if(empty($goods_name)){
			return json(['code'=>400,'msg'=>'请输入商品名称关键词','data'=>'']);
		}
		$uid=input('uid');
        $aa['uid']=$uid;
        $aa['name']=$goods_name;
       	$aa['shop_id']=$shop_id;
        db('sg')->insert($aa);
		$count=db('goods')->where('goods_name','like','%'.$goods_name.'%')->where('shop_id='.$shop_id)->count();
		$p=input('p')?input('p'):1;
		$limit=15;
		$count_page = ceil($count/$limit);
		$data=db('goods')->where('goods_name','like','%'.$goods_name.'%')->where('shop_id='.$shop_id)->field('id,img1,goods_name,sales,price')->order($order)->page($p,$limit)->select();
		if($data){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$data,'page'=>$count_page]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}
	//获取店铺内商品
	function get_shop_goods(){
		$type=input('type');	//1.综合2.销量从高到底 3销量从低到高.4.价格高到底5.价格低到高
		if(empty($type)){
			return json(['code'=>400,'msg'=>'缺少参数type','data'=>'']);
		}
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$size_id=input('size_id');
		$zsize_id=input('zsize_id');
		$order=[];
		if($type==1){
			$order=['dateline asc'];
		}else if($type==2){
			$order=['sales asc'];
		}else if($type==3){
			$order=['sales desc'];
		}else if($type==4){
			$order=['price asc'];
		}else if($type==5){
			$order=['price desc'];
		}
		$p=input('p')?input('p'):1;
		$limit=15;
		$where['shop_id']=$shop_id;
		$shop=db('shop')->where('id',$shop_id)->field('shop_name')->find();
		if($size_id!=''){
			$where['size_id']=$size_id;
		}
		if($zsize_id!=''){
			$where['zsize_id']=$zsize_id;
		}
		$count=db('goods')->where($where)->count();
		$count_page = ceil($count/$limit);
		$goods=db('goods')->where($where)->field('id,img1,goods_name,sales,price')->order($order)->page($p,$limit)->select();
		foreach($goods as $key=>$value){
			$goods[$key]['shop_name']=$shop['shop_name'];
		}
		if($goods){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$goods,'page'=>$count_page]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}


	//商家入驻
	function enter(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$name=input('name');
		if(empty($name)){
			return json(['code'=>400,'msg'=>'请填写姓名','data'=>'']);
		}
		$id_card=input('id_card');
		if(empty($id_card)){
			return json(['code'=>400,'msg'=>'请填写身份证号','data'=>'']);
		}
		$mobile=input('mobile');
		if(empty($mobile)){
			return json(['code'=>400,'msg'=>'请填写手机号','data'=>'']);
		}
		$u_addr=input('u_addr');
		if(empty($u_addr)){
			return json(['code'=>400,'msg'=>'请填写用户地址','data'=>'']);
		}
		$shop_name=input('shop_name');
		if(empty($shop_name)){
			return json(['code'=>400,'msg'=>'请填写商家名称','data'=>'']);
		}
		$s_addr=input('s_addr');
		if(empty($s_addr)){
			return json(['code'=>400,'msg'=>'请填写商家地址','data'=>'']);
		}
		$cate_id=input('cate_id');
		if(empty($cate_id)){
			return json(['code'=>400,'msg'=>'缺少参数cate_id','data'=>'']);
		}
		//营业执照
		$license=input('license');
		if(empty($license)){
			return json(['code'=>400,'msg'=>'请上传营业执照','data'=>'']);
		}
		$zid_card=input('zid_card');
		if(empty($zid_card)){
			return json(['code'=>400,'msg'=>'请上传正面身份证照片','data'=>'']);
		}
		$fid_card=input('fid_card');
		if(empty($fid_card)){
			return json(['code'=>400,'msg'=>'请上传反面身份证照片','data'=>'']);
		}
		$find=db('shop_cat')->where('id',$cate_id)->field('name')->find();
		$finds=db('shop_cat')->where('uid',$uid)->find();
		if($finds){
			return json(['code'=>400,'msg'=>'审核已提交请耐心等待','data'=>'']);
		}
		$data=[
			'uid'=>$uid,
			'name'=>$name,
			'id_card'=>$id_card,
			'mobile'=>$mobile,
			'u_addr'=>$u_addr,
			'shop_name'=>$shop_name,
			's_addr'=>$s_addr,
			'cate_id'=>$cate_id,
			'cat_name'=>$find['name'],
			'license'=>$license,
			'zid_card'=>$zid_card,
			'fid_card'=>$fid_card,
			'dateline'=>date('Y-m-d H:i:s',time())
		];
		$res=db('shop')->insert($data);
		if($res){
			return json(['code'=>200,'msg'=>'店铺申请成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'网络你稳定，请稍后重试','data'=>'']);
		}
	}

	//商家入驻须知
	function enter_notice(){
		$res=db('system')->where('id',1)->field('enter_notice')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//店铺内分类
	function  shop_cate(){
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$res=db('goods')->where('shop_id',$shop_id)->field('size_id,zsize_id')->select();
		$size_id=[];
		$zsize_id=[];
		foreach ($res as $key => $value){
			$size_id[]=$value['size_id'];
			$zsize_id[]=$value['zsize_id'];
		}
		$size_id=array_unique($size_id);
		$zsize_id=array_unique($zsize_id);
		// $find=db('goods_cate')->where('fid',0)->field('id,name')->select();
		$find=db('good_cate')->where(['fid'=>0,'shop_id'=>$shop_id])->field('id,name,img')->select();
		foreach ($find as $key => $value){
			if(!in_array($value['id'],$size_id)){
				unset($find[$key]);
			}
			// $finds=db('goods_cate')->where('fid',$value['id'])->field('id,name')->select();
			$finds=db('good_cate')->where(['fid'=>$value['id'],'shop_id'=>$shop_id])->field('id,name,img')->select();
			foreach ($finds as $k => $v){
				if(!in_array($v['id'],$zsize_id)){
					unset($finds[$k]);
				}
			}
			$find[$key]['zsize_id']=$finds;
		}
		$find=array_values($find);
		if($find){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$find]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}
	//排行榜规则
	function gz(){
		$arr=db('rule')->where('id=1')->field('content')->find();
		if($arr){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$arr['content']]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}
	//店铺排行榜
	function shop_rank(){
		$p=input('p')?input('p'):1;
		$limit=15;
		$count=db('shop')->where('status',2)->count();
		$count_page = ceil($count/$limit);
		$arr=db('gg')->field('shop_id,gg_wz,goods_id,b_time,l_time')->order('gg_wz asc')->select();
		foreach ($arr as $e => $u) {
			$btime=strtotime($u['b_time']);
			$ltime=strtotime($u['l_time']);
			$nowtime=time();
			if($nowtime<$btime || $nowtime>$ltime){
				unset($arr[$e]);
			}
		}
		$arr_count=count($arr);
		$data=db('shop')->field('id,shop_name,shop_logo')->where('status=2')->page($p,$limit)->select();
		if($arr_count>0){
			foreach ($arr as $key => $value) {
				$bb=db('shop')->where('id',$value['shop_id'])->find();
				$arr[$key]['id']=$value['shop_id'];
				$arr[$key]['shop_name']=$bb['shop_name'];
				$arr[$key]['shop_logo']=$bb['shop_logo'];
				$goods=explode(',',$value['goods_id']);
				foreach ($goods as $a => $b) {
							$arr[$key]['goods'][]=db('goods')->where('id',$b)->field('id,goods_name,img1,price')->find();
				}
				$list[$value['gg_wz']-1]=$arr[$key];
				foreach ($data as $k => $v) {
					if($value['shop_id']==$v['id']){
						unset($data[$k]);
					}else{
						//好评率
						$hj=db('good_evaluate')->where('shop_id',$v['id'])->count();
						$hj_sum=db('good_evaluate')->where('shop_id',$v['id'])->sum('star');
						//如果评价表里没有该店铺的评价  好评率为100%;其他的好评率的规则来
						if($hj==0){
							$data[$k]['good_hp']='100%';
						}else{
							$hjs=$hj_sum/($hj*5);
							$hjs_n=sprintf("%.2f",$hjs);
							$hjs_nm=$hjs_n*100;
							$data[$k]['good_hp']=$hjs_nm.'%';
						}
						$shop_num=db('goods')->where('shop_id='.$v['id'])->count();
						$data[$k]['good_num']=$shop_num;
						$shop_sales=db('goods')->where('shop_id='.$v['id'])->sum('sales');
						$data[$k]['shop_sales']=$shop_sales;
					}
				}
			}
		}else{
			foreach ($data as $k => $v) {
					//好评率
					$hj=db('good_evaluate')->where('shop_id',$v['id'])->count();
					$hj_sum=db('good_evaluate')->where('shop_id',$v['id'])->sum('star');
					//如果评价表里没有该店铺的评价  好评率为100%;其他的好评率的规则来
					if($hj==0){
						$data[$k]['good_hp']='100%';
					}else{
						$hjs=$hj_sum/($hj*5);
						$hjs_n=sprintf("%.2f",$hjs);
						$hjs_nm=$hjs_n*100;
						$data[$k]['good_hp']=$hjs_nm.'%';
					}
					$shop_num=db('goods')->where('shop_id='.$v['id'])->count();
					$data[$k]['good_num']=$shop_num;
					$shop_sales=db('goods')->where('shop_id='.$v['id'])->sum('sales');
					$data[$k]['shop_sales']=$shop_sales;
				}
		}
		$last_names = array_column($data,'shop_sales');
		array_multisort($last_names,SORT_DESC,$data);

		/*foreach ($data as $q => $w) {
			foreach ($list as $z => $x) {
				if($q==$z){
					$data[$q+1]=$x;
				}else{
					$data[$z]=$x;
				}
			}
		}*/
		//$arr1[]=$arr;
		/*$arr[]=$arr[0];
		$arr[]=$arr[1];
		$arr[]=$arr[2];
		array_splice($data,0,0,$arr1);
		array_splice($data,4,0,$arr2);
		array_splice($data,8,0,$arr3);*/
		if($arr_count>0){
			foreach ($arr as $y => $e) {
				$arr1[$y][]=$arr[$y];
				array_splice($data,$e['gg_wz']-1,0,$arr1[$y]);
			}
			foreach ($data as $q => $w) {
				$data[$q]['key']=$q+1;
			}
		}else{
			foreach ($data as $q => $w) {
				$data[$q]['key']=$q+1;
			}
		}
		/*foreach ($data as $key => $value){
			//获取排行榜广告位商家
			$goods_id=db('gg')->where('shop_id',$value['id'])->field('goods_id')->find();
			if($goods_id){
				$arr=explode(',',$goods_id['goods_id']);
				foreach ($arr as $k => $v) {
						$data[$key]['goods'][]=db('goods')->where('id',$v)->field('id,goods_name,img1,price')->find();
				}
				//print_r($goods);die;
				//$data[$key]['goods']=$arr;
			}else{
				//好评率
				$hj=db('good_evaluate')->where('shop_id',$value['id'])->count();
				$hj_sum=db('good_evaluate')->where('shop_id',$value['id'])->sum('star');
				//如果评价表里没有该店铺的评价  好评率为100%;其他的好评率的规则来
				if($hj==0){
					$data[$key]['good_hp']='100%';
				}else{
					$hjs=$hj_sum/($hj*5);
					$hjs_n=sprintf("%.2f",$hjs);
					$hjs_nm=$hjs_n*100;
					$data[$key]['good_hp']=$hjs_nm.'%';
				}
				$shop_num=db('goods')->where('shop_id='.$value['id'])->count();
				$data[$key]['good_num']=$shop_num;
				$shop_sales=db('goods')->where('shop_id='.$value['id'])->sum('sales');
				$data[$key]['shop_sales']=$shop_sales;
			}
			$data[$key]['key']=$key+1;
		}*/
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data,'page'=>$count_page]);

	}
	//获取店铺banner
	function shop_slide(){
		$shop_id=input('shop_id');
		if(empty($shop_id)){
			return json(['code'=>400,'msg'=>'缺少参数shop_id','data'=>'']);
		}
		$data=db('banner')->where("shop_id=$shop_id&&status=1")->field('img,link')->select();
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
	}
	//获取首页banner
	function app_slide(){
		$data=db('banner')->where("shop_id=0&&status=1")->field('img,link')->select();
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
	}
	//与店铺聊天
	function shop_chat(){
		$shop_id=input("shop_id");
		$arr=db("shop")->where('id',$shop_id)->find();
		if($arr){
			return json(['code'=>200,'msg'=>'获取成功','to_uid'=>$arr['uid'],"nickname"=>$arr['shop_name'],'avatar'=>$arr['shop_logo']]);
		}else{
			return json(['code'=>400,'msg'=>'获取失败']);
		}
	}
}