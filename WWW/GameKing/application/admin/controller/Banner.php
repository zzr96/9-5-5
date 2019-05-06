<?php

namespace app\admin\controller;

use think\Request;
use think\Db;

class Banner extends Base
{
    protected $beforeActionList = [
        'first',
    ];
    public function first()
    {
        $this->assign('title', '轮播图管理');
    }


    // 轮播图管理首页
    public function index()
    {
        $adminid = cookie("adminid");
        if($adminid!=1){
            $shop_id=$this->get_shop_id();
            $map['shop_id']=$shop_id;
        }else{
            $map['shop_id']=0;
        }
        $data = Db::name('banner')->where($map)->paginate(10);
        $list = $data->items();
        $page = $data->render();
        $total['all'] = $data->count();
        $this->assign('list', $list);
        $this->assign('page', $page);
        $this->assign('total', $total);
        return view();
    }
    //修改状态 0：禁用 1：启用
     public function status()
    {

        $id = input("id",'','trim');
        $status = input("status");
        if (!$id) {
            $ret['code'] = 1;
            $ret['msg'] = '参数错误！';
            return json($ret);
        }
        $do = db('banner')->where("id=" . $id)->setField("status", $status);
        if ($do) {
            $ret['code'] = 0;
            $ret['msg'] = '修改成功！';
        } else {
            $ret['code'] = 1;
            $ret['msg'] = '修改失败！';
        }
        return json($ret);
    }
    //添加页面展示
    public function add()
    {
        return view('add');
    }

     //添加操作
     public function addsave()
    {
        $data = input('post.', '', 'trim');
        if (!$data['img']) {
            $this->result('', 0, '图片路径不能为空');
        }
        $data['status'] = 1;
        $data['dateline']=date('Y-m-d H:i:s',time());
        $adminid = cookie("adminid");
        if($adminid!=1){
            $shop_id=$this->get_shop_id();
            $data['shop_id']=$shop_id;
        }else{
            $data['shop_id']=0;
        }
        $res = db('banner')->insert($data);
        if ($res) {
            $this->result('code', 1, '新增成功');
        } else {
            $this->result('code', 0, '新增失败');
        }

    }
    ////编辑页面
    public function edit()
    {
        $data = db('banner')->where('id=' . input('id'))->find();
        $this->assign("data", $data);
        return view();
    }
    //编辑操作
     public function editsave()
    {
        $data = input('post.', '', 'trim');
        if (!$data['img']) {
            $this->result('', 0, '图片路径不能为空');
        }
        $data['status'] = 1;

        $id = $data['id'];
        unset($data['id']);

        $res = db('banner')->where('id=' . $id)->update($data);
        if ($res) {
            $this->result('', 1, '修改成功');
        } else {
            $this->result('', 0, '修改失败');
        }
    }
    //删除操作
    public function  del()
    {
        $id = input('post.id','','trim');
        $Where = array();
        $Where['id'] = $id;
        db('banner')->where($Where)->delete();
        $this->result('', 1, '删除成功');
    }
}