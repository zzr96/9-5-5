<?php
namespace app\admin\controller;

use think\Db;

class Banner extends Base
{	
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "轮播图管理");
    }

	//轮播图展示
	function index(){
		$res = db('banner')->paginate(10,false,['query'=>input()]);
		$list = $res->items();
        $page = $res->render();
        $total['all'] = $res->count();
        $this->assign("list", $list);
        $this->assign("page", $page);
        $this->assign('total', $total);
		 return view('index');
	}

	//添加图片展示
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
        $res = db('banner')->insert($data);
        if ($res) {
            $this->result('', 1, '新增成功');
        } else {
            $this->result('', 0, '新增失败');
        }
    }

    //修改状态
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

    //编辑页面
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