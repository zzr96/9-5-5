<?php
namespace app\apis\controller;

use think\Controller;
use think\Db;
class Goods extends Com
{
	 //商品列表
	function index(){
		$zcate_id=input('zcate_id');
		$cate_id=input('cate_id');
		$type=input('type');  //1.综合2.销量高到低 3.销量低到高4.价格高到底5.价格低到高
		$p=input('p')?input('p'):1;
		$limit=15;
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
		if($zcate_id){
			$where['zcate_id']=$zcate_id;
		}
		if($cate_id){
			$where['cate_id']=$cate_id;
		}
		$count=db('goods')->where($where)->count();
		$count_page = ceil($count/$limit);
		$res=db('goods')->where($where)
		->field('id,shop_name,shop_id,img1,goods_name,price,sales')->order($order)->page($p,$limit)->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}
	//商品详情
	function goods_detail(){
		$token=input('token');
		if($token){
			return json(['code'=>400,'msg'=>'请先下载app','data'=>'']);
		}
		$uid=input('uid');
		$goods_id=input('goods_id');
		if($uid){
			$view['uid']=$uid;
			$view['goods_id']=$goods_id;
			$view['createtime']=date("Y-m-d H:i:s");
			db('view_history')->insert($view);
		}
		$p=input('p')?input('p'):1;
		if(empty($goods_id)){
			return json(['code'=>400,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$res=db('goods')->where('id',$goods_id)
		->field('shop_id,img1,img2,img3,img4,price,goods_name,content,sales,postage,collect_num,dz,is_xn')->find();
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
		$data['is_zan'] = Db::name('praise_goods')->where(['tid'=>$goods_id,'uid'=>$uid])->count();
		$data['is_xn']=$res['is_xn'];
		$data['collect_num']=$res['collect_num'];
		$data['price']=$res['price'];
		$data['goods_name']=$res['goods_name'];
		$content=$res['content'];
		$data['dz']=$res['dz'];
		$shop_id=$res['shop_id'];
		$data['shop_id']=$res['shop_id'];
		if($uid){
			$aa=db('fav_merchant')->where("uid=$uid && itemid=$shop_id")->find();
			if($aa){
				$data['is_gz']=1;
			}else{
				$data['is_gz']=2;
			}
		}else{
			$data['is_gz']=2;
		}

		//$content=str_replace('src="','src="http://quxiguan.tainongnongzi.com',$content);
		// $data['content']=$res['content'];
		$data['content']=$content;
		$total_count=db('goods_size')->where('good_id',$goods_id)->sum('total');
		$data['sales']=$res['sales'];
		$data['total_count']=$total_count;
		if($res['postage']==0){
			$data['postage']='免运费';
		}else{
			$data['postage']='运费'.$res['postage'].'元';
		}
		$hj=db('good_evaluate')->where('shop_id',$res['shop_id'])->count();
		$hj_sum=db('good_evaluate')->where('shop_id',$res['shop_id'])->sum('star');
		if($hj==0){
			$data['good_hp']='100%';
		}else{
			$hjs=$hj_sum/($hj*5);
			$hjs_n=sprintf("%.2f",$hjs);
			$hjs_nm=$hjs_n*100;
			$data['good_hp']=$hjs_nm.'%';
		}
		//评价
		//$pj_count=db('good_evaluate')->where('good_id',$goods_id)->count();
		//$data['pj_num']=$pj_count;
		//$p=input('p')?input('p'):1;
		//$limit=5;
		//$count_page = ceil($pj_count/$limit);
		$find=db('good_evaluate')->where('good_id',$goods_id)->field('id,u_logo,u_name,content,date,star,goods_size')->order('date desc')->limit(1)->select();
		$data['pj_num']=db('good_evaluate')->where('good_id',$goods_id)->count();
		$shops=db('shop')->where('id',$res['shop_id'])->field('shop_name,shop_logo')->find();
		if($uid){
			$collect=db('goods_collect')->where(['uid'=>$uid,'itemid'=>$goods_id])->find();
			if($collect){
				//收藏
				$data['is_collect']=1;
			}else{
				//未收藏
				$data['is_collect']=2;
			}
		}else{
			$data['is_collect']=2;
		}
		$data['shop_name']=$shops['shop_name'];
		$data['shop_logo']=$shops['shop_logo'];
		$data['pj']=[];
		if(!empty($find)){
			foreach ($find as $key => $value) {
				$find[$key]['goods_num']=substr($value['goods_size'], -1);
				$find[$key]['goods_size']=preg_replace("/\\d+/",'', $value['goods_size']);
				$finds=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
				$bb=db('shop_evaluate')->where('pid',$value['id'])->find();
				if($bb){
					$find[$key]['shop_ev']=$bb['content'];
				}else{
					$find[$key]['shop_ev']='';
				}
				$find[$key]['pj_img']=$finds;
			}
			$data['pj']=$find;
			// if($find){
			// 	$finds=db('goods_evimg')->where('ev_id',$find['id'])->field('img')->select();
			// 	$data['pj']['pj_img']=$finds;
			// }
		}
		$data['ev_img']=array();
		$data['ev_num']=0;
		$arr=db('good_evaluate')->where("good_id=$goods_id")->field('id')->select();
		foreach ($arr as $key => $value) {
			$data['ev_img']=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->limit(4)->select();
			$data['ev_num']+=db('goods_evimg')->where('ev_id',$value['id'])->count();
		}
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
	}
	//商品点赞
	function dz(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>400,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$type = input('type/d',0);
		if(!$type){
            return json(['code'=>400,'msg'=>'缺少参数type']);
        }
        if($type == 2){
            try{
                Db::startTrans();
                Db::name('praise_goods')->where(['tid' => $goods_id, 'uid' => $uid])->delete();
                Db::name('goods')->where('id',$goods_id)->setDec('dz',1);
                Db::commit();
                return json(['code' => 200, 'msg' => '取消成功']);
            }catch (\Exception $e){
                Db::rollback();
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }

        }
        if(Db::name('praise_goods')->where(['tid' => $goods_id, 'uid' => $uid])->find()){
            return json(['code'=>400,'msg'=>'不能重复点赞哦']);
        }
        try{
            Db::startTrans();
            Db::name('praise_goods')->insertGetId(['tid' => $goods_id, 'uid' => $uid, 'createtime' => time()]);
            Db::name('goods')->where('id',$goods_id)->setInc('dz',1);
            Db::commit();
            return json(['code'=>200,'msg'=>'点赞成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
	}
	//商品规格
	function goods_size(){
		$is_xn=input('is_xn');
		$id=input('id');
		if(empty($id)){
			return json(['code'=>400,'msg'=>'缺少参数id','data'=>'']);
		}
		if($is_xn==2){
			$arr=db('goods_size')->where('good_id',$id)->field('id')->find();
			$data['goods_size_id']=$arr['id'];
			$data['color']="";
			$data['spec']="";
		}else{
			$finds=db('goods_size')->where('good_id',$id)->field('color,spec')->select();
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
		}
		return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
	}

	//根据规格获取价格，库存
	function get_size(){
		//$uid=input('uid');
		$color=input('color');
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
			return json(['code'=>400,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$data['good_id']=$goods_id;
		$finds=db('goods_size')->where($data)->field('id,fprice,total,good_id')->find();
		if($finds['fprice']==0.00){
			$find=db('goods')->where('id',$finds['good_id'])->field('price')->find();
			$prcie=$find['price'];
		}else{
			$prcie=$finds['fprice'];
		}
		$datas['fprice']=$prcie;
		$datas['total']=$finds['total'];
		$datas['goods_size_id']=$finds['id'];
		$datas['size']=$color.' '.$spec;
		if($finds){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$datas]);
		}else{
			return json(['code'=>400,'msg'=>'获取失败','data'=>'']);
		}
	}

	//商品收藏
	function collection(){
		$uid=input('uid');
		$goods_id=input('goods_id');
		$goods=db('goods')->where('id',$goods_id)->field('img1,goods_name,yh_price,collect_num')->find();
		$find=db('goods_collect')->where(['uid'=>$uid,'itemid'=>$goods_id])->field('id')->find();
		if($find){
			$new_nums=$goods['collect_num']-1;
			if($new_nums<0){
				return json(['code'=>400,'msg'=>'遇到未知错误','data'=>'']);
			}
			db("goods_collect")->where('id',$find['id'])->delete();
			db('goods')->where('id',$goods_id)->update(['collect_num'=>$new_nums]);
			return json(['code'=>200,'msg'=>'取消收藏成功','data'=>0]);
		}
		$data=[
			'uid'=>$uid,
			'itemid'=>$goods_id,
			//'logo'=>$goods['img1'],
			//'title'=>$goods['goods_name'],
			//'price'=>$goods['yh_price'],
			'createtime'=>date("Y-m-d H:i:s")
		];
		$res=db('goods_collect')->insert($data);
		if($res){
			$new_num=$goods['collect_num']+1;
			db('goods')->where('id',$goods_id)->update(['collect_num'=>$new_num]);
			return json(['code'=>200,'msg'=>'收藏成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'网络不稳定，请稍后再试','data'=>'']);
		}
	}

	//历史记录
	function history(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('history')->where('uid',$uid)->field('content')->select();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}

	//清空历史记录
	function del_history(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$res=db('history')->where('uid',$uid)->delete();
		if($res){
			return json(['code'=>200,'msg'=>'删除成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'删除失败','data'=>'']);
		}
	}
	//分享商品获取链接
	public function getgoods()
    {
        $goods_id = input('goods_id');
        $uid=input('uid');
        if (empty($uid)) {
            return json(['code' => 400, 'msg' => "缺少参数uid"]);
        }
        if (empty($goods_id)) {
            return json(['code' => 400, 'msg' => "缺少参数goods_id"]);
        }
        $arr = db('goods')->where('id', $goods_id)->find();
        if (!$arr) {
            return json(['code' => 400, 'msg' => '商品不存在']);
        }
        $url = $_SERVER['HTTP_HOST'] . url('apis/Goods/goods_detail',['goods_id'=>$goods_id,'uid'=>$uid,'token'=>$uid.$goods_id]);
        return json(['code' => 200, 'msg' => 'SUCCESS', 'url' =>$url
        ]);
    }

	//商品评价
	function evaluate(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
		}
		$goods_size=input('goods_size');//商品规格加商品数量
		if(empty($goods_size)){
			return json(['code'=>400,'msg'=>'缺少参数goods_size','data'=>'']);
		}
		$goods_size_id=input('goods_size_id');
		if(empty($goods_id)){
			return json(['code'=>400,'msg'=>'缺少参数goods_size_id','data'=>'']);
		}
		$star=input('star');
		if(empty($star)){
			return json(['code'=>400,'msg'=>'缺少参数star','data'=>'']);
		}
		$img=input('img');
		$user=db('member')->where('uid',$uid)->field('avatar,nickname')->find();
		$goods=db('goods')->where('id',$goods_id)->field('shop_id,img1,price,goods_name')->find();
		$data=[
			'uid'=>$uid,
			'good_id'=>$goods_id,
			'shop_id'=>$goods['shop_id'],
			'u_logo'=>$user['avatar'],
			'u_name'=>$user['nickname'],
			'g_logo'=>$goods['img1'],
			'g_name'=>$goods['goods_name'],
			'g_price'=>$goods['price'],
			'date'=>date("Y-m-d H:i:s"),
			'star'=>$star,
			'goods_size'=>$goods_size
		];
		$content=input('content');
		if(!empty($content)){
			$data['content']=$content;
		}
		$res=db('good_evaluate')->insertGetId($data);
		if(is_array($img)){
			foreach($img as $key=>$value){
			db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
			}
		}else{
			db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$img]);
		}
		/*foreach($img as $key=>$value){
			db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
		}*/
		if($res){
			return json(['code'=>200,'msg'=>'评价成功','data'=>'']);
		}else{
			return json(['code'=>400,'msg'=>'评价失败','data'=>'']);
		}
	}
	//买家相册
	function user_photo(){
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>400,'msg'=>'缺少参数goods_id']);
		}
		$arr = Db::name('good_evaluate')->where("good_id=$goods_id")->field('id,uid,u_name,u_logo,content')->paginate(4)->each(function ($item,$K){
            $item['img'] = Db::name('goods_evimg')->where('ev_id',$item['id'])->field('img')->select();
            return $item;
        });
//		$arr=db('good_evaluate')->where("good_id=$goods_id")->field('id,uid,u_name,u_logo,content')->select();
//		foreach ($arr as $key => $value) {
//			$finds=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
//			$arr[$key]['img']=$finds;
//		}
		return json(['code'=>200,'msg'=>'SUCCESS','data'=>$arr]);
		//$imgs=db('goods_evimg')->where('ev_id','in',"$ids")->field('id,img')->select();
		//$data['img']=
	}
}