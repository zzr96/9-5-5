<?php
namespace app\admin\controller;
use think\Controller;
class Jrank extends Base
{
     /* 订单管理开始 */
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '排行榜管理');
    }
    public function index(){
        $arr=db('gg')->select();
        foreach ($arr as $key => $value) {
            $data=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
            $arr[$key]['shop_name']=$data['shop_name'];
        }
        $shop_id=$this->get_shop_id();
        $this->assign('shop_id',$shop_id);
        $this->assign('arr',$arr);
        return view();
    }
    //竞价页面
    public function add(){
        $id=input('id');
        $this->assign('id',$id);
        return view();
    }
    public function addsave(){
        $id=input('gg_id');
        $price=input('price');
        if(empty($price)){
            return json(['code'=>0,'msg'=>'价格不能为空！']);
        }
        $shop_id=$this->get_shop_id();
        $res=db('gg')->where('id',$id)->find();
        $time=time();
        $res_time=time($res['j_time']);
        if($time>$res_time){
            return json(['code'=>0,'msg'=>'报价截至时间已过！']);
        }
        $aa=db('compete')->where("gg_id=$id && shop_id=$shop_id")->find();
        if($aa){
            return json(['code'=>0,'msg'=>'你已经报过价！']);
        }
        $data['gg_id']=$id;
        $data['price']=$price;
        $data['shop_id']=$shop_id;
        $data['time']=date("Y-m-d H:i:s");
        $arr = db('compete')->insert($data);
        if($arr){
            return json(['code'=>1,'msg'=>'报价成功！']);
        }else{
            return json(['code'=>0,'msg'=>'报价失败！']);
        }
    }
    //选择商品
    public function select_good(){
        $id=input('id');//排行榜id
        $ids=db('gg')->where('id',$id)->field('goods_id,shop_id')->find();
        if($ids){
            $this->assign('ids',$ids['goods_id']);
            $where['shop_id']=$ids['shop_id'];
            $where['status']=1;
            $list=db("goods")->where($where)->select();
            $this->assign('list',$list);
            $this->assign('id',$id);
        }
        return view();
    }
    //选择商品操作
    public function add_goodsid(){
        $goods_id=input('goods_id');
        $id=input("id");
        $data['goods_id']=$goods_id;
        $res=db('gg')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'选择成功！']);
        }else{
            return json(['code'=>0,'msg'=>'选择失败！']);
        }
    }
}


