<?php
namespace app\admin\controller;

use think\Controller;


class Base extends Controller
{
    public $site;
    public $xiaoqu;
    public $admin;
    public $dianpu;

    public function _initialize()
    {
        $adminid = cookie("adminid");
        if (!$adminid) {
            $this->error("请登录", "login/index");
        }
        $admin = db("admin")->field("id,name,cat,status")->where("id=" . $adminid)->find();
        if (!$admin) {
            cookie("adminid", NULL);
            $this->error("请登录", "login/index");
        }
        if ($admin['status'] == 0) {
            cookie("adminid", NULL);
            $this->error("账号被禁用", "login/index");
        }
        $this->admin = $admin;
//			if($admin['cat'] == 4){
//				$xiaoqu_id=db('admin_xiaoqu')->where('aid='.$admin['id'])->find();
//				if(!$xiaoqu_id){
//					cookie("adminid",NULL);
//					$this->error("请登录","login/index");
//				}
//				$xiaoqu=db('xiaoqu')->where('id='.$xiaoqu_id['xiaoqu_id'])->find();
//				$this->xiaoqu=$xiaoqu;
//				$this->assign('xiaoqu',$xiaoqu);
//			}
//			if($admin['cat'] == 5){
//				$dianpu=db('dianpu')->where('admin_id='.$admin['id'])->find();
//				if($dianpu){
//					$this->dianpu=$dianpu;
//					$this->assign('dianpu',$dianpu);
//				}
//			}

        $this->assign("admin", $admin);
        // $site = db("xitong")->where("id=1")->find();
        // $this->site = $site;
        // $this->assign("site", $site);
        $request = \think\Request::instance();
        $m = $request->controller();
        $a = $request->action();
        $this->assign("m", $m);
        $this->assign("a", $a);
        $auth = db("admin_auth")->where("role_id=" . $admin['cat'])->field("action_id")->select();
        $aids = '';
        foreach ($auth as $k => $v) {
            $aids = $v['action_id'] . "," . $aids;
        }
        $map['id'] = array("in", $aids);
        $map['fid'] = 0;
        $menu = db("admin_action")->where($map)->order("position asc")->select();
        //halt($menu);
        $this->assign("menu", $menu);
        $auth = false;
        $yanzheng = db("admin_action")->select();
        foreach ($yanzheng as $k => $v) {
            if ($v['m'] == $m) {
                $auth = true;
                break;
            }
        }
        //dump($auth);
        //dump($m);
        //dump($a);
        //dump($menu);
        //exit;
//			if(!$auth){
//				$this->error("您没有操作权限！");
//			}
        $map['fid'] = array("EGT", 0);
        $map['m'] = $m;
        $nav = db("admin_action")->where($map)->order("position asc")->select();
        $lnav = input("nav");
        if ($lnav) {
            $nnav = array("name" => $lnav, "a" => 'javascript:;');
            $nav[] = $nnav;
        }
        $this->assign("nav", $nav);
    }

    //获取ID
    function get_shop_id(){
        $adminid = cookie("adminid");
        $shop_name=db('admin')->where('id',$adminid)->field('name')->find();
        $shop=db('shop')->where('shop_name',$shop_name['name'])->field('id')->find();
        return $shop['id'];
    }
}
