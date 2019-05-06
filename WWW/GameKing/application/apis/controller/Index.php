<?php
namespace app\apis\controller;

use think\Controller;
class Index extends Controller{
   //分类主分类
    function index_cate(){
        $res=db('shop_cat')->where('fid',0)->field('id,name,img')->select();
        foreach ($res as $key => $value) {
            $res[$key]['img']=$value['img'];
        }
        if($res){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$res]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
        }
    }
    //分类二次分类
    function second_cate(){
        $cate_id=input('cate_id');
        if(empty($cate_id)){
            return json(['code'=>400,'msg'=>'缺少参数cate_id','data'=>'']);
        }
        $res=db('shop_cat')->where('fid',$cate_id)->field('id,name,img')->select();
        foreach ($res as $key => $value) {
            $res[$key]['img']=$value['img'];
        }
        $find=db('shop_cat')->where('id',$cate_id)->field('name')->find();
        if($res){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'name'=>$find['name']]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
        }
    }
    //首页搜索商品
    function index_good(){
        $uid=input('uid');
        $name=input('name');
        $type=input('type');  //1.综合2.销量高到低 3.销量低到高4.价格高到底5.价格低到高
        if(empty($name)){
            return json(['code'=>400,'msg'=>'请输入商品名称关键词','data'=>'']);
        }
        if(!empty($uid)){
            $aa['uid']=$uid;
            $aa['name']=$name;
            db('sg')->insert($aa);
        }
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
        $count=db('goods')->where('goods_name','like','%'.$name.'%')->count();
        $p=input('p')?input('p'):1;
        $limit=15;
        $count_page = ceil($count/$limit);
        $data=db('goods')->where('goods_name','like','%'.$name.'%')->field('id,img1,goods_name,sales,price,shop_id')->order($order)->page($p,$limit)->select();
        foreach ($data as $key => $value) {
            $arr=db('shop')->where('id',$value['shop_id'])->find();
            $data[$key]['shop_name']=$arr['shop_name'];
            $data[$key]['shop_logo']=$arr['shop_logo'];
        }
        if($data){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$data,'page'=>$count_page]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
        }
    }
    //获取搜索历史纪录
    function get_sg(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
        }
        $shop_id=input('shop_id');
        if(empty($shop_id)){
            $data=db('sg')->where("uid=$uid && shop_id=0")->field('name')->select();
        }else{
            $data=db('sg')->where("uid=$uid && shop_id=$shop_id")->field('name')->select();
        }
        /*$arr=[];
        foreach ($data as $key => $value) {
            $arr[]=$value['name'];
        }*/
        $data=array_unique($data, SORT_REGULAR);
//        $data=object_array($data);
//        $data=json_decode(json_encode($data),true);
        if($data){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$data]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
        }

    }
    //删除搜索记录
    function del_sg(){
        $uid=input('uid');
        if(empty($uid)){
             return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $shop_id=input('shop_id');
        if(empty($shop_id)){
            $res=db('sg')->where("uid=$uid && shop_id=0")->delete();
        }else{
            $res=db('sg')->where("uid=$uid && shop_id=$shop_id")->delete();
        }
        if($res){
             return json(['code'=>200,'msg'=>'成功']);
        }else{
             return json(['code'=>200,'msg'=>'失败']);
        }
    }
    //首页获取推荐产品
    function recommend(){
        $p=input('p')?input('p'):1;
        $limit=3;
        $count=db('goods')->where('is_hot=2')->field('id,shop_name,shop_id,img1,goods_name,price,sales')->count();
        $count_page = ceil($count/$limit);
        $arr=db('goods')->where('is_hot=2')->field('id,shop_name,shop_id,img1,goods_name,price,sales')->page($p,$limit)->order('sales desc')->select();
        foreach ($arr as $key => $value) {
            $arr[$key]['img1']=$value['img1'];
            $shops=db('shop')->where('id',$value['shop_id'])->find();
            $arr[$key]['shop_logo']=$shops['shop_logo'];
        }
        if($arr){
             return json(['code'=>200,'msg'=>'获取成功','data'=>$arr,'page'=>$count_page]);
         }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>'']);
         }
    }
}
