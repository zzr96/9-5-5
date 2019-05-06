<?php
namespace app\admin\controller;
use think\Db;
class Kd extends Base
{
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign("title", "物流管理");
    }

    function index(){
    	$res = db('kd')->paginate(20,false,['query'=>input()]);
        $list = $res->items();
        $page = $res->render();
        $total['all'] = $res->count();
        $this->assign("list", $list);
        $this->assign("page", $page);
        $this->assign('total', $total);
    	return view();
    }

    //物流添加页面
    public function add()
    {
        return view('add');
    }

    //删除物流信息
    public function  del()
    {
        $id = input('post.id','','trim');
        $Where = array();
        $Where['id'] = $id;
        $find=db('order')->where('kd_id',$id)->find();
        if($find){
        	$this->result('', 0, '此物流在使用中不可删除');
        }
        db('kd')->where($Where)->delete();
        $this->result('', 1, '删除成功');
    }

    //物流添加操作
    function addsave(){
    	$data = input('post.', '', 'trim');
    	$find=db('kd')->where($data)->find();
    	if($find){
    		$this->result('', 0, '该数据已存在');
    	}
    	$res = db('kd')->insert($data);
        if ($res) {
            $this->result('', 1, '新增成功');
        } else {
            $this->result('', 0, '新增失败');
        }
    }

    //修改操作
    public function edit()
    {
        $data = db('kd')->where('id=' . input('id'))->find();
        $this->assign("data", $data);
        return view();
    }

    public function editsave()
    {
        $data = input('post.', '', 'trim');
        $id = $data['id'];
        unset($data['id']);
        $find=db('kd')->where($data)->find();
        if($find){
        	$this->result('', 0, '该数据已存在');
        }
        $res = db('kd')->where('id=' . $id)->update($data);
        if ($res) {
            $this->result('', 1, '修改成功');
        } else {
            $this->result('', 0, '修改失败');
        }
    }


}
