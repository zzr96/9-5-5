<?php
namespace app\admin\controller;
use think\Controller;
class Rank extends Base
{
     /* 订单管理开始 */
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '排行榜管理');
    }
    //首页
    public function index(){
        //$arr=db('gg')->alias('a')->join('shop b','a.shop_id=b.id','left')->select();
        $arr=db('gg')->select();
        foreach ($arr as $key => $value) {
            $data=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
            $arr[$key]['shop_name']=$data['shop_name'];
        }
        $this->assign('arr',$arr);
        return view();
        //print_r($data);
    }
    //删除排行榜
    public function delete(){
        $id=input('id');
        $res=db('gg')->where('id',$id)->delete();
        if($res){
            return json(['code'=>0,'msg'=>'操作成功！']);
        }else{
             return json(['code'=>1,'msg'=>'操作失败！']);
        }
    }
    //添加
    public function add(){
        return view('add');
    }
    //添加操作
    public function addsave(){
        $data = input('post.', '', 'trim');
        $list=db('gg')->where('gg_wz',$data['gg_wz'])->find();
        if($list){
            $this->result('', 0, '此广告位已有位置');
        }
        $res = db('gg')->insert($data);
        if ($res) {
            $this->result('', 1, '新增成功');
        } else {
            $this->result('', 0, '新增失败');
        }
    }
    //查看竞价用户
    public function look(){
        $id=input('id');
        $find=db('gg')->where('id',$id)->find();
        if(empty($find['shop_id'])){
            $this->assign('ids',0);
        }else{
            $this->assign('ids',$find['shop_id']);
        }
        $arr=db('compete')->where('gg_id',$id)->select();
        foreach ($arr as $key => $value) {
            $data=db('shop')->where('id',$value['shop_id'])->field('shop_name')->find();
            $arr[$key]['shop_name']=$data['shop_name'];
        }
        $this->assign('arr',$arr);
        return view();
    }
    //确认中标用户
    public function zhongbiao(){
        $id=input('id');
        $shop_id=input('shop_id');
        if(empty($id)){
            return json(['code'=>0,'msg'=>'id值为空！']);
        }
        if(empty($shop_id)){
            return json(['code'=>0,'msg'=>'shop_id值为空！']);
        }
        $data['shop_id']=$shop_id;
        $res=db('gg')->where('id',$id)->update($data);
        $list=db("gg")->where('id',$id)->find();
        $map['sort']=$list['gg_wz'];
        db("shop")->where('id',$shop_id)->update($map);
        if($res){
            return json(['code'=>1,'msg'=>'选择成功！']);
        }else{
            return json(['code'=>1,'msg'=>'选择失败！']);
        }
    }
    //排行榜规则
    public function gz(){
        $arr=db('rule')->where("id=1")->find();
        $this->assign('arr',$arr);
       return view();
    }
    //添加排行榜规则
    public function add_do(){
        $content=input('content');
        $data['content']=$content;
        $res=db('rule')->where('id=1')->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功！']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败！']);
        }
    }
}