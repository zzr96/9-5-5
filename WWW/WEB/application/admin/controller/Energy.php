<?php
namespace app\admin\controller;

use think\Db;

class Energy extends Base
{
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "能量商城");
    }

    //能量列表
    function index(){
        $order=db('energy_mart')->alias('a')
        ->join('goods c','c.id=a.goods_id')->field('a.id,a.energy_than,a.goods_id,c.goods_name')->paginate(15, false,['query' => input()]);;    
        $list = $order->items();
        $page = $order->render();
        $this->assign('list',$list);
        $this->assign('page',$page);
        return view();
    }

    //能量商城添加
    function add(){
        $find=db('goods')->where('shop_id',1)->field('id,goods_name')->select();
        $cy=db('energy_mart')->field('goods_id')->select();
        $sy=[];
        foreach($cy as $key=>$value){
            $sy[]=$value['goods_id'];
        }
        foreach($find as $key=>$value){
            if(in_array($value['id'],$sy)){
                unset($find[$key]);
            }
        }
        $this->assign('find',$find);
        return view();
    }

    //能量添加方法
    function addsave(){
        $goods_id=input('goods_id');
        $energy_than=input('energy_than');
        $find=db('goods')->where('id',$goods_id)->field('cate_id')->find();
        $data=[
            'cate_id'=>$find['cate_id'],
            'goods_id'=>$goods_id,
            'energy_than'=>$energy_than
        ];
        $res=db('energy_mart')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }

    //能量商城修改
    function edit(){
        $find=db('goods')->where('shop_id',1)->field('id,goods_name')->select();
        $cy=db('energy_mart')->field('goods_id')->select();
        $sy=[];
        foreach($cy as $key=>$value){
            $sy[]=$value['goods_id'];
        }
        foreach($find as $key=>$value){
            if(in_array($value['id'],$sy)){
                unset($find[$key]);
            }
        }
        $finds=db('energy_mart')->where('id=' . input('id'))->find();
        $this->assign('find',$find);
        $this->assign('finds',$finds);
        return view();
    }

    //修改方法
    function editsave(){
        $id=input('id');
        $goods_id=input('goods_id');
        $energy_than=input('energy_than');
        $find=db('goods')->where('id',$goods_id)->field('cate_id')->find();
        $data=[
            'cate_id'=>$find['cate_id'],
            'goods_id'=>$goods_id,
            'energy_than'=>$energy_than
        ];
        $res=db('energy_mart')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改成功']);
        }else{
            return json(['code'=>0,'msg'=>'修改失败']);
        }
    }

    //能量商城删除
    function del(){
        $id=input('id');
        $res=db('energy_mart')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }

}