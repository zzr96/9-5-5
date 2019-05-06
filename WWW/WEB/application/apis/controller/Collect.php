<?php
namespace app\apis\controller;

use think\Controller;

class Collect extends Com
{
	 //我的收藏
	function index(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'您尚未登录','data'=>'']);
		}
		$type=input('type'); //商品 2、店铺
		if(empty($type)){
			return json(['code'=>101,'msg'=>'缺少参数type','data'=>'']);
		}
		$p=input('p')?input('p'):1;
		$limit=5;
		$count=db('collection')->where(['uid'=>$uid,'type'=>$type])->count();
		$count_page = ceil($count/$limit);
		if($type==1){
			$res=db('collection')->where(['uid'=>$uid,'type'=>$type])
			->field('id,collect,logo,title,price')->page($p,$limit)->select();
			foreach ($res as $key => $value){
				$count=db('goods')->where('id',$value['collect'])->field('collect_num')->find();
				if(empty($count)){
					$count=0;
				}
				$res[$key]['collect_num']=$count;
			}
		}else{
			$res=db('collection')->where(['uid'=>$uid,'type'=>$type])
			->field('id,collect,logo,title')->page($p,$limit)->select();
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
        $res = db('collection')->where($map)->delete();
        if($res){
           return json(['code'=>200,'msg'=>'操作成功','data'=>'']);
        }else{
           return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
        }
	}
}	