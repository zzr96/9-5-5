<?php
namespace app\admin\controller;

use think\Db;

class System extends Base
{
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "系统设置");
    }

    function index(){
    	$view = db("system")->where("id=1")->find();
        $this->assign("view", $view);
    	return view();
    }

    function edit_do(){
    	$data = input();
        $do = db("system")->where("id=1")->update($data);
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

    //添加页面
    function link_add(){
        return view();
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

    //消息页面
    function news(){
        $res=db('system_news')->paginate(20,false,['query'=>input()]);
        $list = $res->items();
        $page = $res->render();
        $this->assign('list',$list);
        $this->assign("page", $page);
        return view();
    }

    //消息添加页面
    function news_add(){
        return view();
    }

    //消息添加方法
    function news_add_do(){
        $data=input('');
        $data['dateline']=time();
        $res=db('system_news')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }

    //修改页面
    function news_edit(){
        $id=input('id');
        $find=db('system_news')->where('id',$id)->find();
        $this->assign('find',$find);
        return view();
    }

    //店铺修改操作
    function news_edit_do(){
        $data=input("");
        $id=$data['id'];
        unset($data['id']);
        $res=db('system_news')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改完成']);
        }else{
            return json(['code'=>0,'msg'=>'您尚未做出修改']);
        }
    }
    //联系我们
    function bank(){
        $find=db('bank')->select();
        $this->assign('find',$find);
        return view();
    }
    //添加银行卡
    function bank_add(){
        return view();
    }

    //添加银行卡方法
    function bank_save(){
        $data=input();
        $find=db('bank')->where($data)->find();
        if($find){
            return json(['code'=>0,'msg'=>'添加内容已存在']);
        }
        $res=db('bank')->insert($data);
        if($res){
            return json(['code'=>1,'msg'=>'添加成功']);
        }else{
            return json(['code'=>0,'msg'=>'添加失败']);
        }
    }

    //修改银行卡页面
    function bank_edit(){
        $id=input('id');
        $find=db('bank')->where('id',$id)->find();
        $this->assign('find',$find);
        return view();
    }
    //修改银行卡方式方法
    function bank_edit_save(){
        $data=input();
        $id=$data['id'];
        unset($data['id']);
        $find=db('bank')->where($data)->find();
        if($find){
            if($id!=$find['id']){
                return json(['code'=>0,'msg'=>'当前内容已存在']);
            }
        }
        $res=db('bank')->where('id',$id)->update($data);
        if($res){
            return json(['code'=>1,'msg'=>'修改完成']);
        }else{
            return json(['code'=>0,'msg'=>'您尚未做出修改']);
        }
    }

     //删除联系方式
     function bank_del(){
        $id=input('id');
        $res=db('bank')->where('id',$id)->delete();
        if($res){
            return json(['code'=>1,'msg'=>'删除成功']);
        }else{
            return json(['code'=>0,'msg'=>'删除失败']);
        }
    }


}