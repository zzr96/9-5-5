<?php
namespace app\apis\controller;

use think\Controller;

class Evaluate extends Com
{
	 //我的评价
	 function my_evaluate(){
	 	$uid=input('uid');
	 	if(empty($uid)){
	 		return json(['code'=>400,'msg'=>'缺少参数uid','data'=>'']);
	 	}
	 	$p=input('p')?input('p'):1;
	 	$limit=15;
	 	$count=db('good_evaluate')->where('uid',$uid)->count();
	 	$count_page = ceil($count/$limit);
	 	$res=db('good_evaluate')->where('uid',$uid)->page($p,$limit)->select();
	 	foreach ($res as $key => $value) {
	 		$res[$key]['date']=date('Y-m-d H:i');
	 		$img=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
	 		$res[$key]['img']=$img;
	 		$bb=db('shop_evaluate')->where('pid',$value['id'])->find();
			if($bb){
				$res[$key]['shop_ev']=$bb['content'];
			}else{
				$res[$key]['shop_ev']='';
			}
	 	}
	 	if($res){
	 		return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
	 	}else{
	 		return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
	 	}
	 }

	//删除
	function del(){
		$id = input("id");
		if(empty($id)){
			return json(['code'=>400,'msg'=>'缺少参数id！','data'=>'']);
		}
        $map['id'] = array("in", $id);
        $res = db('good_evaluate')->where($map)->delete();
        $maps['ev_id'] = array("in", $id);
        db('goods_evimg')->where($maps)->delete();
        if($res){
           return json(['code'=>200,'msg'=>'操作成功','data'=>'']);
        }else{
           return json(['code'=>400,'msg'=>'网络不稳定请稍后再试','data'=>'']);
        }
	}

	//商品评价
	function goods_evaluate(){
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>400,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=15;
		$count=db('good_evaluate')->where('good_id',$goods_id)->count();
		$count_page = ceil($count/$limit);
		$res=db('good_evaluate')->where('good_id',$goods_id)
		->field('id,u_name,u_logo,star,date,content,goods_size')->page($p,$limit)->select();
		if($res){
			foreach ($res as $key => $value) {
				$res[$key]['goods_num']=substr($value['goods_size'], -1);
				$res[$key]['goods_size']=preg_replace("/\\d+/",'', $value['goods_size']);
				$res[$key]['date']=$value['date'];
				$find=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
				$res[$key]['img']=$find;
				$bb=db('shop_evaluate')->where('pid',$value['id'])->find();
				if($bb){
					$res[$key]['shop_ev']=$bb['content'];
				}else{
					$res[$key]['shop_ev']='';
				}
			}
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
		}
	}
}