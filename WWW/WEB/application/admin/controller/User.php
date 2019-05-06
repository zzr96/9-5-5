<?php
namespace app\admin\controller;

use think\Db;

class User extends Base
{
	protected $beforeActionList = [
        'first',
    ];

     public function first()
    {
        $this->assign("title", "用户管理");
    }

    //用户列表显示
    function index(){
    	$key = trim(input("key"));
    	$map = [];
    	if ($key) {
            if(is_numeric($key)){
                if(preg_match('/^1[3|4|5|6|7|8][0-9]\d{4,8}$/', $key)){
                    $map['mobile'] = $key;
                }else{
                    $map['id'] = $key;
                }
            }else{
                $map['nickname'] = $key;
            }
        }
    	$data = db('user')->where($map)->order("id desc")->paginate(10,false,['query'=>input()]);
        $num = db('user')->where($map)->count();
        $page = $data->render();
        $items = $data->items();
        $this->assign('list', $items);
        $this->assign('page', $page);
        $this->assign('num', $num);
    	return view();
    }

    //用户修改页面
    function edit(){
    	$uid = input("uid");
        $user = db("user")->where("id='" . $uid . "'")->find();
        $this->assign("users", $user);
    	return view();
    }

    //用户修改操作方法
    function edit_do(){
        $id = input("uid");
        $nickname = input("nickname");
        $mobile = input("mobile");
        $logo = input("logo");
        $money = input("money");
        $integral = input("integral");
        if ($nickname == "") {
           return json(['code'=>0,'msg'=>'昵称不可为空!']);
       	}
        if ($mobile == "") {
            return json(['code'=>0,'msg'=>'电话不可为空!']);
        }
        if ($money == "") {
            return json(['code'=>0,'msg'=>'余额不能为空!']);
        }
        if ($money < 0) {
            return json(['code'=>0,'msg'=>'余额不能为负!']);
        }
        if ($integral == "") {
            return json(['code'=>0,'msg'=>'积分不能为空!']);
        }
        if ($integral < 0) {
            return json(['code'=>0,'msg'=>'积分不能为负!']);
        }
        $re = db("user")->where("nickname='" . $nickname . "' and id<>" . $id)->find();
        if ($re) {
        	return json(['code'=>0,'msg'=>'账户已存在!']);
        }

        $retel = db("user")->where("mobile='" . $mobile . "' and id<>" . $id)->find();
        if ($retel) {
        	return json(['code'=>0,'msg'=>'电话已存在!']);
        }
        $data['nickname'] = $nickname;
        $data['mobile'] = $mobile;
        $data['logo'] = $logo;
        $data['money'] = $money;
        $data['integral'] = $integral;
        $data['sex'] = input("sex");
        $do = db("user")->where('id',$id)->update($data);
        if($do){
        	return json(['code'=>1,'msg'=>'修改成功！']);
        } else {
        	return json(['code'=>1,'msg'=>'修改失败！']);
        }
    }

    // 弹出添加图片
	function edit_upimg_logo()
    {
        return view();
    }

    // 执行上传图片
    function edit_imgup_do_logo()
    {
        $image = \think\Image::open(request()->file('file'));
        // $image->thumb(80,80, \think\Image::THUMB_CENTER);
        $savePath = './uploads/image/' . date("Ymd") . "/";
        $saveName = uniqid() . '.jpg';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $image->save($savePath . $saveName);
        $url = $savePath . $saveName;

        $ret['pic'] = str_replace("./", "/", $url);
        $ret['code'] = 0;

        return $ret;
    }
}