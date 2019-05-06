<?php
namespace app\apis\controller;

use think\Controller;

class Evaluate extends Com
{
	 //我的评价
	 function my_evaluate(){
	 	$uid=input('uid');
	 	if(empty('uid')){
	 		return json(['code'=>101,'msg'=>'缺少参数uid','data'=>'']);
	 	}
	 	$p=input('p')?input('p'):1;
	 	$limit=15;
	 	$count=db('goods_evaluate')->where('uid',$uid)->count();
	 	$count_page = ceil($count/$limit);
	 	$res=db('goods_evaluate')->where('uid',$uid)->page($p,$limit)->select();
	 	foreach ($res as $key => $value) {
	 		$res[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
	 		$img=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
	 		$res[$key]['img']=$img;
	 	}
	 	if($res){
	 		return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
	 	}else{
	 		return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
	 	}
	 }
	
	//删除
	function del(){
		$id = input("id");
		if(empty($id)){
			return json(['code'=>101,'msg'=>'缺少参数id！','data'=>'']);
		}
        $map['id'] = array("in", $id);
        $res = db('goods_evaluate')->where($map)->delete();
        $maps['ev_id'] = array("in", $id);
        db('goods_evimg')->where($maps)->delete();
        if($res){
           return json(['code'=>200,'msg'=>'操作成功','data'=>'']);
        }else{
           return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
        }
	}

	//商品评价
	function goods_evaluate(){
		$goods_id=input('goods_id');
		if(empty($goods_id)){
			return json(['code'=>101,'msg'=>'缺少参数goods_id','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=1;
		$count=db('goods_evaluate')->where('goods_id',$goods_id)->count();
		$count_page = ceil($count/$limit);
		$res=db('goods_evaluate')->where('goods_id',$goods_id)
		->field('id,u_name,u_logo,star,dateline,content')->page($p,$limit)->select();
		if($res){
			foreach ($res as $key => $value) {
				$res[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
				$find=db('goods_evimg')->where('ev_id',$value['id'])->field('img')->select();
				$res[$key]['img']=$find;
			}
		}
		if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'暂无数据','data'=>'']);
		}
	}
}	