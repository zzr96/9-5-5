<?php

namespace app\admin\controller;

use think\Request;

class Xitong extends Base
{
    public function common()
    {
        $this->assign("title", "系统设置");

    }

    public function index()
    {
        $this->common();
        $view = db("about")->where("id=1")->find();
        $this->assign("view", $view);
        return view('index');
    }



    public function edit_do()
    {
        $data = Request::instance()->param();
        $data['updatetime'] = date('Y-m-d H:i:s');
        $do = db("about")->update($data);
        if ($do) {
            $this->success("修改成功！");
        } else {
            $this->error("没有做出任何修改！");
        }
    }


    //联系我们
    function link(){
        $find=db('system_link')->select();
        $this->assign('find',$find);
        return view();
    }
    //修改页面
    function edit_link(){
        $id=input('id');
        $find=db('system_link')->where('id',$id)->find();
        $this->assign('find',$find);
        return view();
    }

    //修改联系方式方法
    function link_edit_save(){
        $data=input();
        $id=$data['id'];
        unset($data['id']);
        $find=db('system_link')->where($data)->find();
        if($find){
            if($id!=$find['id']){
                return json(['code'=>0,'msg'=>'当前内容已存在']);
            }
        }
        $res=db('system_link')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改完成']);
        }else{
            return json(['code'=>0,'msg'=>'您尚未做出修改']);
        }
    }
    //添加联系方式方法
    function link_save(){
        $data=input();
        $find=db('system_link')->where($data)->find();
        if($find){
            return json(['code'=>0,'msg'=>'添加内容已存在']);
        }
        $res=db('system_link')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }
    //添加页面
    function link_add(){
        return view();
    }
    //删除联系方式
    function del(){
        $id=input('id');
        $res=db('system_link')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }


    public function rule()
    {
        if(Request::instance()->isPost()){
            $content = input('content');
            db('tuiguang')->where(['id'=>1])->update(['content'=>$content]);
            return json(['code'=>1,'msg'=>'修改成功']);
        }else{
            $view = db("tuiguang")->where("id=1")->find();
            $this->assign("view", $view);
            return view('rule');
        }
    }
}
