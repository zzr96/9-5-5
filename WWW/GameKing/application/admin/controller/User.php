<?php
namespace app\admin\controller;

class User extends Base
{
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '用户管理');
    }

    // 会员管理首页
    public function index()
    {
        $key = input('key');
        $qiyong = input('qiyong');
        //
        $map = [];
        if ($key) {
            $map['phone'] = ['like','%'.$key.'%'];
        }
        if ($qiyong) {
            $map['qiyong'] = $qiyong;
        }
        $data = db('admin_user')->where($map)->order("id desc")->paginate(20);
        $num = $data->count();
        $page = $data->render();
        $items = $data->items();
        //

        $this->assign('list', $items);
        $this->assign('page', $page);
        $this->assign('num', $num);
        return view();
    }
    // 弹出添加图片
    public function yinsi_upimg(){

        return view();
    }
    //删除
    public function xinxi_del()
    {
        $id=input('id');
        $id=explode(',', $id);
        $user=db('admin_user');
        foreach ($id as $k => $v) {
            if($v){
             $user->where('id',$v)->delete();
            }
        }
        $ret['ret']=1;
        $ret['msg']='删除成功';
        return json($ret);
    }
    public function yinsi_imgup_do()
    {
        $image = \think\Image::open(request()->file('file'));
        $savePath =  './uploads/image/'. date("Ymd")."/";
        $saveName = uniqid() . '.jpg';

        if (!is_dir($savePath)){
            mkdir($savePath,0777,true);
        }
        $image->save( $savePath . $saveName);
        $url=   $savePath.  $saveName;


        $ret['pic']=str_replace("./","/",$url);
        $ret['code']=0;

        return $ret;
    }
    // 修改会员启用/禁用状态
    public function status()
    {

        $id = input("id");
        $status = input("status");
        if (!$id) {
            $ret['code'] = 1;
            $ret['msg'] = '参数错误！';
            return json($ret);
        }
        $do = db('admin_user')->where("id=" . $id)->setField("qiyong", $status);
        if ($do) {
            // alog('修改会员状态'.$id."|".$status);
            $ret['code'] = 0;
            $ret['msg'] = '修改成功！';
        } else {
            $ret['code'] = 1;
            $ret['msg'] = '修改失败！';
        }
        return json($ret);
    }

    // 弹出充值窗口
    public function chongzhi()
    {
        return view();
    }

    // 修改会员信息
    public function edit()
    {

        $count = db("admin_user")->count();
        $this->assign("count", $count);

        $uid = input("uid");
        $user = db("admin_user")->where("id='" . $uid . "'")->find();
        $this->assign("users", $user);

        return view('edit');
    }

    // 执行修改会员信息
    public function edit_do()
    {

        $id = input("uid");
        $name = input("nicheng");
        $phone = input("phone");
        $headimg = input("headimg");;
        $password = input("password");

        if ($name == "") {
            $ret['code'] = 1;
            $ret['msg'] = "用户名不能为空！";
            return json($ret);
        }

        if ($phone == "") {
            $ret['code'] = 1;
            $ret['msg'] = "电话不能为空！";
            return json($ret);
        }

        /*if (strlen($phone) != 11 || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', $phone)) {
            $ret['code'] = 1;
            $ret['msg'] = "电话不正确！";
            return json($ret);
        }*/

        $re = db("admin_user")->where("nicheng='" . $name . "' and id<>" . $id)->find();
        if ($re) {
            $ret['code'] = 1;
            $ret['msg'] = "账户已存在";
            return json($ret);
        }

        $retel = db("admin_user")->where("phone='" . $phone . "' and id<>" . $id)->find();
        if ($retel) {
            $ret['code'] = 1;
            $ret['msg'] = "电话已存在";
            return json($ret);
        }


        $data['id'] = $id;
        $data['nicheng'] = $name;
        $data['phone'] = $phone;
        $data['headimg'] = input('headimg');
//        $data['age'] = input("age");
        $data['sex'] = input("sex");

        if ($password) {
            $data['password'] = md5($password);
        }
        $do = db("admin_user")->update($data);
        if ($do) {
            // alog('修改会员'.$name);
            $ret['code'] = 0;
            $ret['msg'] = "修改成功！";
        } else {
            $ret['code'] = 1;
            $ret['msg'] = "修改失败！";
        }
        return json($ret);

    }

    // 添加会员页
    public function add()
    {
        $count = db("admin_user")->count();
        $this->assign("count", $count);
        return view('add');
    }

    // 执行添加会员
    public function add_do()
    {

        $name = input("name");
        $password = input("password");
         $nicheng = input("nicheng");
        if ($nicheng == "") {
            $ret['code'] = 1;
            $ret['msg'] = "昵称不能为空！";
            return json($ret);
        }
         if ($name == "") {
            $ret['code'] = 1;
            $ret['msg'] = "用户名不能为空！";
            return json($ret);
        }
        if ($password == '') {
            $ret['code'] = 1;
            $ret['msg'] = "密码不能为空！";
            return json($ret);
        }

        $re = db("admin_user")->where("phone='" . $name . "'")->find();
        if ($re) {
            $ret['code'] = 1;
            $ret['msg'] = "手机号已注册";
            return json($ret);
        }
        $data['nicheng'] = $nicheng;
        $data['phone'] = $name;
        $data['datetime'] = date('Y-m-d H:i:s');
        $data['password'] = md5($password);
        $data['headimg'] = input('headimg');
        $data['yue'] = input('yue');
        $data['address'] = input('address');
        $do = db("admin_user")->insert($data);
        if ($do) {
            $ret['code'] = 0;
            $ret['msg'] = "添加成功！";

        } else {
            $ret['code'] = 1;
            $ret['msg'] = "添加失败！";
        }
        return json($ret);
    }

    function bonUserId()
    {
        $uid = rand(10000000, 99999999);
        $find = db('admin_user')->where('id=' . $uid)->find();
        if ($find) {
            return $this->bonUserId();
        }
        return $uid;
    }

    //意见反馈管理首页
    public function feedback()
    {
        //条件搜索
        $key = input('key');
        $map = [];

        if ($key) {
            $map['u.name'] = $key;
        }

        //获取意见反馈数据
        $order = db('feedback')->alias('o')->join('user u', 'o.uid=u.id', 'left')
            ->where($map)->order('o.id desc')
            ->field('o.*,u.name')->paginate(10);
        $list = $order->items();

        $page = $order->render();
        $total['all'] = $order->total();
        //赋值
        $this->assign("list", $list);
        $this->assign('page', $page);
        $this->assign("total", $total);
        return view();
    }







    //余额保存
    public function updata_yue()
    {
       $data=$this->request->param();
       $do=db('admin_user')->update($data);
       if(!$do){
        $ret['code']=0;
        $ret['msg']='修改失败或并无修改';
        return json($ret);
       }
        $ret['code']=1;
        $ret['msg']='修改成功';
        return json($ret);

    }
    //售后服务
    public function service()
    {

        $list=db('order_service')->alias('o')->field('o.*,u.nicheng')->order('o.createtime desc')->join('ke_user u','u.id=o.uid','left')->paginate(10);

        $page=$list->render();

        $list = $list->items();
        //halt($list);
        $this->assign('page',$page);
        $this->assign('list',$list);
        $num=db('order_service')->count();
        $this->assign('num',$num);

        return view();
    }
    //售后通过或拒绝
    public function shenhe_do()
    {
        $id=input('id');
        $status=input('status');
        $re=db('order_service')->where('id',$id)->setField('status',$status);
        $uid=db('order_service')->where('id',$id)->value('uid');
        if($re){

                $ret['code']=1;
              if($status==1){
                 $ret['msg']='已通过';
                $data=[
                'uid'=>$uid,
                'title'=>'售后服务',
                'content'=>'您的售后申请已通过！',
                'createtime'=>date('Y-m-d H:i:s',time())
                ];
              }else{
                 $ret['msg']='已拒绝';
                  $data=[
                'uid'=>$uid,
                'title'=>'售后服务',
                'content'=>'您的售后申请已被拒绝！',
                'createtime'=>date('Y-m-d H:i:s',time())
                ];
              }

            db('message')->insert($data);

        }else{
             $ret['code']=0;
             $ret['msg']='操作未成功';
        }
        return $ret;

    }
    //体现申请显示
    public function tixian()
    {

        $list=db('tixian')->alias('o')->field('o.*,u.nicheng')->order('o.createtime desc')->join('ke_user u','u.id=o.uid','left')->paginate(10);

        foreach ($list as $k => &$v) {
            if($v['createtime']){
                $v['createtime']= date("Y-m-d H:i:s",$v['createtime']);

            }
               $list[$k]=$v;

        }
        $page=$list->render();
        $this->assign('page',$page);
        $this->assign('list',$list);
        $num=db('order_service')->count();
        $this->assign('num',$num);
        return view();
    }
    //申请体现通过或拒绝
    public function tixian_do()
    {
        $id=input('id');
        $status=input('status');
        $re=db('tixian')->where('id',$id)->setField('status',$status);
        $uid=db('tixian')->where('id',$id)->value('uid');
        $money=db('tixian')->where('id',$id)->value('money');
        if($re){

            $ret['code']=1;
              if($status==1){
                 $ret['msg']='已通过';
                  $data=[
                'uid'=>$uid,
                'title'=>'提现服务',
                'content'=>'您的提现申请已通过！',
                'createtime'=>date('Y-m-d H:i:s',time())
                ];
                //减少余额
                db('admin_user')->where('id', 'eq', $uid)->setDec('yue',$money);
                //余额明细
                $datas = [
                    'y_type' => 3,
                    'y_source' => '支付宝提现',
                    'y_time' => date('Y-m-d H:i:s',time()),
                    'y_price' => $money,
                    'y_uid' => $uid
                ];
                db('yue')->insert($datas);
              }else{
                 $ret['msg']='已拒绝';
                  $data=[
                'uid'=>$uid,
                'title'=>'提现服务',
                'content'=>'您的提现申请已被拒绝！',
                'createtime'=>date('Y-m-d H:i:s',time())
                ];
              }
               db('message')->insert($data);

        }else{
             $ret['code']=0;
             $ret['msg']='操作未成功';
        }
        return $ret;

    }
    //红包管理
    public function hongbao()
    {
        $xitong=db('xitong')->find();
        $hongbao= $xitong['hongbao'];
        $this->assign('hongbao',$hongbao);
        return view();
    }
    public function hongbao_do()
    {
        $hongbao=input('hongbao');
        $xitong=db('xitong')->find();
        $re=db('xitong')->where('id',$xitong['id'])->setField('hongbao',$hongbao);
        if($re){
             $data=[
            'code'=>1,
            'msg'=>'修改成功'
        ];
    }else{
        $data=[
            'code'=>0,
            'msg'=>'修改失败'
        ];
    }

        return  $data;
    }
}