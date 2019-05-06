<?php
namespace app\admin\controller;

use think\Db;

class Favorable extends Base
{	
	protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign("title", "优惠管理");
    }

    //优惠券展示
    function index(){
        $search = input('search');
        $map = [];
        if($search){
            $map['name'] = ['like','%'.$search.'%'];
        }
		$data=db('coupon')->where($map)->paginate(20, false, ['query' => input()]);
        $page = $data->render();
        $items = $data->items();
		$this->assign('list', $items);
        $this->assign('page', $page);
		return view('index');
    }

    //添加优惠券列表
    function add(){
        $shoplist=db('shop')->where('status',2)->field('id,shop_name')->select();
        $this->assign('shoplist',$shoplist);
        return view('add');
    }

    //优惠券添加方法
    function add_do(){   
        $data=[
            'shop_id'=>input('shop_id'),
            'name'=>input('name'),
            'mz_money'=>input('mz_money'),
            'yh_money'=>input('yh_money'),
            'start_time'=>input('start_time'),
            'end_time'=>input('end_time'),
            'dateline'=>date('Y-m-d H:i:s',time())
        ];
        $res=db('coupon')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'创建成功!']);
        }else{
            return json(['code'=>0,'msg'=>'创建失败!']);
        }
    }

    //删除优惠券
    function del(){
        $id=input('id');
        $res=db('coupon')->where('id',$id)->delete();
        if($res){
           return json(['code'=>1,'msg'=>'删除成功!']);
        }else{
           return json(['code'=>0,'msg'=>'删除失败!']);
        }
    }
    
    //修改优惠券
    function edit(){
        $res=db('coupon')->where('id',input('id'))->find();
        $shoplist=db('shop')->where('status',2)->field('id,shop_name')->select();
        $this->assign('shoplist',$shoplist);
        $this->assign('res',$res);
        return view('edit');
    }

    //修改优惠券操作
    function edit_do(){       
        $data=[
            'shop_id'=>input('shop_id'),
            'name'=>input('name'),
            'mz_money'=>input('mz_money'),
            'yh_money'=>input('yh_money'),
            'start_time'=>input('start_time'),
            'end_time'=>input('end_time'),
            'dateline'=>date('Y-m-d H:i:s',time())
        ];
        $res=db('coupon')->where('id',input('id'))->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改成功!']);
        }else{
            return json(['code'=>0,'msg'=>'修改失败!']);
        }
    }

    //活动列表
    function activity(){
        $search = input('search');
        $map = [];
        if($search){
            $map['name'] = ['like','%'.$search.'%'];
        }
		$data=db('activity')->where($map)->paginate(20, false, ['query' => input()]);
        $page = $data->render();
        $items = $data->items();
		$this->assign('list', $items);
        $this->assign('page', $page);
        return view();
    }

    //删除活动
    function activel_del(){
        $id=input('id');
        $res=db('activity')->where('id',$id)->delete();
        if($res){
           return json(['code'=>1,'msg'=>'删除成功!']);
        }else{
           return json(['code'=>0,'msg'=>'删除失败!']);
        }
    }

    //添加活动
    function activity_add(){
        $shoplist=db('shop')->where('status',2)->field('id,shop_name')->select();
        $this->assign('shoplist',$shoplist);
        return view();
    }

    //添加活动
    function activity_add_do(){
        $data=input("");
        $shop=db('shop')->where('id',$data['shop_id'])->field('shop_name')->find();
        $data['shop_name']=$shop['shop_name'];
        $res=db('activity')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'创建成功!']);
        }else{
            return json(['code'=>0,'msg'=>'创建失败!']);
        }
    }

    //修改活动页面
    function activity_edit(){
        $id=input('id');
        $find=db('activity')->where('id',$id)->find();
        $shoplist=db('shop')->where('status',2)->field('id,shop_name')->select();
        $this->assign('shoplist',$shoplist);
        $this->assign('find',$find);
        return view();
    }

}