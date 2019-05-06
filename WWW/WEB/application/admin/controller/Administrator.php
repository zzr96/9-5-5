<?php
namespace app\admin\controller;

class Administrator extends Base
{

    public $table = 'admin';
    public $table_cat = 'admin_cat';


    protected $beforeActionList = [
        'first',
    ];


    public function first()
    {
        $this->assign("title", "管理员管理");
    }

    public function index()
    {

        $map = null;
        $cat = input("cat");
        if ($cat) {
            $map['cat'] = $cat;
        }
        $status = input("status");
        if ($status) {
            $map['status'] = $status;
        }


        $list = db($this->table)->where($map)->paginate(20);
        $items = $list->items();
        foreach ($items as $k => $v){
            $items[$k]['cats'] = db($this->table_cat)->where("id=" . $v['cat'])->field("name")->find();
        }


        $this->assign('list', $items);
        $page = $list->render();
        $this->assign('page', $page);


        $this->cat_get();


        return view('index');
    }

    public function cat_get()
    {
        $cat = db($this->table_cat)->where("fid=0")->field("id,name")->select();
        foreach ($cat as $k => $v) {
            $cat[$k]['one'] = db($this->table_cat)->where("fid=" . $v['id'])->field("id,name")->select();
            foreach ($cat[$k]['one'] as $k2 => $v2) {
                $cat[$k]['one'][$k2]['two'] = db($this->table_cat)->where("fid=" . $v2['id'])->field("id,name")->select();
            }
        }
        $this->assign("cat", $cat);
    }

    public function add()
    {
        $this->cat_get();
        return view();
    }

    public function add_do()
    {

        $data = input();
        //	dump($data);

        if (!$data['name']) {
            $this->error('用户名不能为空！');
        }

        if (!$data['cat']) {
            $this->error('请选择角色！');
        }

        if (!$data['password']) {
            $this->error('密码不能为空！');
        }


        //dump($data);
        if ($data['password'] == $data['repassword']) {
            unset($data['repassword']);
        } else {
            $this->error('密码不一致！');
        }

        $data['pass']=$data['repassword'];
        $data['password'] = md5($data['password']);
        $data['regdate'] = time();

        $do = db($this->table)->insert($data);
        if ($do) {
            $this->success("添加成功");
        } else {
            $this->error('添加失败，请稍后重试！');
        }


    }

    public function edit()
    {
        $this->cat_get();
        $id = input("id");
        $view = db($this->table)->where("id=" . $id)->find();
        $this->assign("view", $view);
        return view();
    }

    public function edit_do()
    {
        $data = input();
        if(!$data['cat']){
            $this->error('请选择角色！');
        }
        if ($data['password']){
            if($data['password'] == $data['repassword']){
                unset($data['repassword']);
                $data['password'] = md5($data['password']);
            }else{
                $this->error('密码不一致！');
            }
        }else{
            unset($data['password']);
            unset($data['repassword']);
        }
        $do = db($this->table)->update($data);
        if($do){
            $this->success("修改成功");
        }else{
            $this->error('没有做出任何修改！');
        }
    }


    public function del()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        db($this->table)->where($map)->delete();
        $this->success("操作成功");
    }

    public function jinyong()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        $data['status'] = 2;
        db($this->table)->where($map)->update($data);
        $this->success('操作成功');
    }

    public function qiyong()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        $data['status'] = 1;
        db($this->table)->where($map)->update($data);
        $this->success($id);
    }

    public function move()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        $data['cat'] = input("cat");
        db($this->table)->where($map)->update($data);
        $this->success('操作成功');
    }

    //角色管理--列表页
    public function cat()
    {
        $list = db($this->table_cat)->paginate(20);
        $items = $list->items();
        $this->assign('list', $items);
        $page = $list->render();
        $this->assign('page', $page);
        return view("cat");
    }

    //角色管理--添加角色
    public function cat_add()
    {
        return view();
    }

    //角色管理--执行添加角色
    public function cat_add_do()
    {
        $name = input('name');
        if(!$name){
            $this->result('',0,'角色名称不能为空');
        }
        $data = [
            'fid' => 0,
            'name' => $name,
            'date' => date('Y-m-d H:i:s')
        ];
        $res = db('admin_cat')->where('name',$name)->find();
        if($res){
            $this->result('',0,'此角色名称已经存在');
        }
        $res = db('admin_cat')->insert($data);
        if($res){
            $this->result('',1,'添加成功');
        }else{
            $this->result('',0,'添加失败');
        }
    }

    //角色管理--编辑角色
    public function cat_edit()
    {   
        $id = input('id');
        $data = db('admin_cat')->where('id',$id)->find();
        $this->assign('data',$data);
        return view();
    }

    //角色管理--执行编辑角色
    public function cat_edit_do()
    {
        $id = input('id');
        $name = input('name');
        // $status = input('status');
        if(!$name){
            $this->result('',0,'角色名称不能为空');
        }
        // if(!$status){
        //     $this->result('',0,'请选择状态');
        // }
        $data = [
            'fid' => 0,
            'name' => $name,
            // 'status' => $status,
            'date' => date('Y-m-d H:i:s')
        ];

        $res = db('admin_cat')->where('name',$name)->where('id','<>',$id)->find();
        if($res){
            $this->result('',0,'此角色名称已经存在');
        }
        $res = db('admin_cat')->where('id',$id)->update($data);
        if($res){
            $this->result('',1,'编辑成功');
        }else{
            $this->result('',0,'编辑失败');
        }
    }

    //角色管理--删除角色
    public function del_cat()
    {
        $id = input("id");
        $map['id'] = array("in", $id);
        $res = db('admin_cat')->where($map)->delete();
        if($res){
            $this->result('',1,'操作成功');
        }else{
            $this->result('',0,'操作失败');
        }
    }

    public function quanxian()
    {
        $id = input("id");
        $view = db("admin_cat")->where("id=" . $id)->find();
        $this->assign("view", $view);
        $list = db("admin_action")->where("fid=0")->order("position asc")->select();
        foreach ($list as $k => $v) {
            $list[$k]['is'] = db("admin_auth")->where("role_id=" . $id . " and action_id=" . $v['id'])->find();
            $list[$k]['item'] = db("admin_action")->where("m='" . $v['m'] . "'")->order("position asc")->select();
            foreach ($list[$k]['item'] as $k2 => $v2) {
                $list[$k]['item'][$k2]['is'] = db("admin_auth")->where("role_id=" . $id . " and action_id=" . $v2['id'])->find();
            }
        }
        $this->assign("list", $list);
        return view();
    }


    public function shouquan()
    {
        $role_id = input("role_id");
        $id = input("ids");
        db("admin_auth")->where("role_id=" . $role_id)->delete();
        $ids = explode(",", $id);
        $ids = array_unique($ids);
        $data['role_id'] = $role_id;
        foreach ($ids as $k => $v) {
            $data['action_id'] = $v;
            db("admin_auth")->insert($data);
        }
        $this->success("授权成功！");
    }


}
