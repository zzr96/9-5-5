<?php
namespace app\admin\controller;

use think\Db;

class Shop extends Base
{
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "商城管理");
    }

    //商城首页
    function index(){
    	$map=[];
    	$res = db('shop')->where($map)->field('id,name,shop_name,status,mobile,cat_name,dateline')->paginate(10);
        $list = $res->items();
        $page = $res->render();
        $total['all'] = $res->count();
        $this->assign("list", $list);
        $this->assign("page", $page);
        $this->assign('total', $total);
    	return view();
    }

    //查看页面
    function edit(){
    	$id = input('id');
        $list = db('shop')->where('id=' . $id)->find();
        $this->assign('list', $list);
    	return view();
    }

    //审核操作
     public function status(){
        $id = input("id",'','trim');
        $status = input("status");
        if (!$id) {
            $ret['code'] = 1;
            $ret['msg'] = '参数错误！';
            return json($ret);
        }
        $do = db('shop')->where("id",$id)->setField("status", $status);
        if($status==2){
            if ($do) {
                $ret['code'] = 1;
                $ret['msg'] = '审核成功！';
            } else {
                $ret['code'] = 0;
                $ret['msg'] = '审核失败！';
            }
        }else{
            if ($do) {
                $ret['code'] = 1;
                $ret['msg'] = '拒绝成功！';
            } else {
                $ret['code'] = 0;
                $ret['msg'] = '拒绝失败！';
            }
        }
        
        return json($ret);
    }
    //删除
    function del(){
        $id=input('id');
        $res=db('shop')->where('id',$id)->delete();
        if($res){
            $ret['code'] = 1;
            $ret['msg'] = '删除成功';
        }else{
            $ret['code'] = 0;
            $ret['msg'] = '删除失败';
        }
        return json($ret);
    }
    //店铺属性页面
    function cate(){
        $res = db('shop_cat')->where('fid',0)->order('number asc')->paginate(10);
        $list = $res->items();
        $page = $res->render();
        $this->assign("list", $list);
        $this->assign("page", $page);
        return view();
    }

    //店铺属性添加
    function cate_add(){
        return view();
    }

    //店铺属性添加操作
    function cate_add_do(){
        $data=input();
        if($data['name']==''){
            return json(['code'=>0,'msg'=>'请填写名称','data'=>'']);
        }
        if($data['number']<0 || $data['number']==''){
            return json(['code'=>0,'msg'=>'请正确填写排序数字','data'=>'']);
        }
        if($data['img']==''){
            return json(['code'=>0,'msg'=>'请上传图片','data'=>'']);
        }
        $data['dateline']=date('Y-m-d H:i:s',time());
        $find=db('shop_cat')->where('name',$data['name'])->find();
        if($find){
            return json(['code'=>0,'msg'=>'名称不可重复','data'=>'']);
        }
        $res=db('shop_cat')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功','data'=>'']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败','data'=>'']);
        }
    }

    //修改分类
    function cate_edit(){
        $id=input('id');
        $find=db('shop_cat')->where('id',$id)->find();
        $this->assign('find',$find);
        return view();
    }

    //修改分类方法
    function cate_edit_save(){
        $data=input();
        $id=$data['id'];
        unset($data['id']);
        if($data['name']==''){
            return json(['code'=>0,'msg'=>'请填写名称','data'=>'']);
        }
        if($data['number']<0 || $data['number']==''){
            return json(['code'=>0,'msg'=>'请正确填写排序数字','data'=>'']);
        }
        if($data['img']==''){
            return json(['code'=>0,'msg'=>'请上传图片','data'=>'']);
        }
        $data['dateline']=date('Y-m-d H:i:s',time());
        $find=db('shop_cat')->where('name',$data['name'])->field('id')->find();
        if(!empty($find)){
            if($find['id']!=$id){
                return json(['code'=>0,'msg'=>'名称不可重复','data'=>'']);
            }
        }
        $res=db('shop_cat')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改成功','data'=>'']);
        }else{
            return json(['code'=>0,'msg'=>'修改失败','data'=>'']);
        }
    }
    //下级分类
    function next_cate(){
        $id=input('id');
        $res = db('shop_cat')->where('fid',$id)->paginate(10);
        $list = $res->items();
        $page = $res->render();
        $this->assign("list", $list);
        $this->assign("page", $page);
        return view();
    }

    //添加下级分类
    function next_cate_add(){
        $id=input('id');
        $find= db('shop_cat')->where('fid',0)->field('id,name')->select();
        $this->assign('id',$id);
        $this->assign('find',$find);
        return view();
    }

    //添加下级分类方法
    function next_add_do(){
        $data=input();
        if($data['name']==''){
            return json(['code'=>0,'msg'=>'请填写名称','data'=>'']);
        }
        if($data['number']<0 || $data['number']==''){
            return json(['code'=>0,'msg'=>'请正确填写排序数字','data'=>'']);
        }
        if($data['img']==''){
            return json(['code'=>0,'msg'=>'请上传图片','data'=>'']);
        }
        $data['dateline']=date('Y-m-d H:i:s',time());
        $find=db('shop_cat')->where('name',$data['name'])->find();
        if($find){
            return json(['code'=>0,'msg'=>'名称不可重复','data'=>'']);
        }
        $res=db('shop_cat')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功','data'=>'']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败','data'=>'']);
        }
        
    }
    //排序
    public function paixu(){
        $id = input("id");
        $type = input("type");
        $v = db('shop_cat')->where('id=' . $id)->find();
        $rs=db('shop_cat')->where('fid',$v['fid'])->count();
        if($rs<2){
            $this->error('单条数据无需排序');
        }
        if ($type == 'up'){
            $d = db('shop_cat')->where('number < ' . $v['number'] . " and fid=" . $v['fid'])->order("number desc")->find();
            $data['number'] = $d['number'];

            $da['id'] = $d['id'];
            $da['number'] = $v['number'];
            db("shop_cat")->update($da);
        }
        if ($type == 'down') {
            $d = db('shop_cat')->where('number > ' . $v['number'] . " and fid=" . $v['fid'])->order("number asc")->find();
            $data['number'] = $d['number'];

            $da['id'] = $d['id'];
            $da['number'] = $v['number'];
            db("shop_cat")->update($da);
        }
        $data['id'] = $id;
        $do = db("shop_cat")->update($data);
        if($do){
            $this->success("修改成功");
        }else{
            $this->error('没有改动！');
        }
    }

}