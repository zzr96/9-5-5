<?php
namespace app\admin\controller;

class Menu extends Base
{

    public $table = 'admin';
    public $table_cat = 'admin_cat';


    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign("title", "菜单管理");
    }

    public function index()
    {
        $list = db("admin_action")->where("fid=0")->order("position asc")->select();
        foreach ($list as $k => $v) {
            $list[$k]['item'] = db("admin_action")->where("fid=" . $v['id'])->order("position asc")->select();
        }
        $this->assign("list", $list);
        return view();
    }


    public function add()
    {
        $list = db("admin_action")->where("fid=0")->order("position asc")->select();
        $this->assign("cat", $list);
        return view();
    }

    public function add_do()
    {


        $data = input();

        if (!$data['name']) {
            $this->error('请填写菜单名字！');
        }
        if (!$data['m']) {
            $this->error('请填写控制器！');
        }
        if (!$data['a']) {
            $this->error('请填写方法！');
        }

        $data['position'] = time();

        $do = db("admin_action")->insert($data);
        if ($do) {
            $this->success("添加成功");
        } else {
            $this->error('添加失败，请稍后重试！');
        }

    }


    public function edit()
    {

        $id = input("id");

        $view = db("admin_action")->where("id=" . $id)->find();
        $this->assign("view", $view);


        $list = db("admin_action")->where("fid=0")->order("position asc")->select();
        $this->assign("cat", $list);

        return view();

    }


    public function edit_do()
    {

        $data = input();
        if (!$data['name']) {
            $this->error('请填写菜单名字！');
        }
        if (!$data['m']) {
            $this->error('请填写控制器！');
        }
        if (!$data['a']) {
            $this->error('请填写方法！');
        }

        $do = db("admin_action")->update($data);
        if ($do) {
            $this->success("修改成功");
        } else {
            $this->error('修改失败，请稍后重试！');
        }

    }

    public function del()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        db("admin_action")->where($map)->delete();
        $this->success("操作成功");
    }

    public function paixu()
    {
        $id = input("id");
        $type = input("type");
        $v = db('admin_action')->where('id=' . $id)->find();
        if ($type == 'up') {
            $d = db('admin_action')->where('position < ' . $v['position'] . " and fid=" . $v['fid'])->order("position desc")->find();
            $data['position'] = $d['position'];

            $da['id'] = $d['id'];
            $da['position'] = $v['position'];
            db("admin_action")->update($da);
        }
        if ($type == 'down') {
            $d = db('admin_action')->where('position > ' . $v['position'] . " and fid=" . $v['fid'])->order("position asc")->find();
            $data['position'] = $d['position'];
            $da['id'] = $d['id'];
            $da['position'] = $v['position'];
            db("admin_action")->update($da);
        }
        $data['id'] = $id;
        $do = db("admin_action")->update($data);
        if ($do) {
            $this->success("修改成功");
        } else {
            $this->error('没有改动！');
        }
    }

    public function icon(){
        return view();
    }


}
