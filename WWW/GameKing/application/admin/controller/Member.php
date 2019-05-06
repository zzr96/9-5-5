<?php

namespace app\admin\controller;

use think\Request;
use think\Db;

class Member extends Base
{
    protected $paginate = 10;
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '用户管理');
    }

    // 用户首页
    public function index()
    {
        $where = [];
        $now = date('Y-m-d H:i:s');
        $date = date('Y-m-d' . ' 00:00:00', strtotime('-7 days'));
        $time = input('time');
        $phone = input('phone');
        if($phone){
            $where['phone'] = ['like','%' . $phone .'%'];
        }
        switch ($time){
            case 1:
                $where['createtime'] = ['between',[date('Y-m-d'.' 00:00:00'),$now]];
                break;
            case 2:
                $where['createtime'] = ['between',[$date,$now]];
                break;
        }
        $data = Db::name('member')->where($where)->paginate($this->paginate);
        $list = $data->items();
        $page = $data->render();
        $this->assign('time', $time);
        $this->assign('phone', $phone);
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }

    //设置用户推荐
    public function tuijian()
    {
        $uid = input('uid');
        $v = Db::name('member')->where('uid', $uid)->value('tuijian');
        $tuijian = $v ? 0 : 1;
        $re = Db::name('member')->where('uid', $uid)->update(['tuijian' => $tuijian]);
        if ($re) {
            return json(['code' => 0, 'msg' => 'SUCCESS']);
        }
    }

    //设为客服
    public function setCustomer(){
        $uid = input('uid');
        $res = Db::name('member')->where(['uid'=>$uid,'is_customer'=>1])->find();
        if($res){
            return json(['code'=>200,'msg'=>'有且只有一位客服哟~']);
        }
        Db::startTrans();
        try{
            Db::name('member')->where(['is_customer'=>1])->update(['is_customer'=>0]);
            Db::name('member')->where(['uid'=>$uid])->update(['is_customer'=>1]);
            Db::commit();
            return json(['code'=>0,'msg'=>'设置成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>200,'msg'=>$e->getMessage()]);
        }
    }

    //用户消费记录
    public function record()
    {

        $data= Db::name('balance_log')->field('uid,sum(amount) as total')->where(['type'=>1])->group('uid')->order('total asc')->paginate(15)->each(function ($item,$k){
            $item['userinfo'] = Db::name('member')->field('uid,nickname,phone')->where(['uid'=>$item['uid']])->find();
            return $item;
        });
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view('record');
    }


    //查看用户消费明细
    public function viewRecord()
    {
        $uid = input('uid');
        $data = Db::name('balance_log')->where(['uid'=>$uid,'type'=>1])->paginate(15);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view('viewRecord');
    }


    //查看用户信息
    public function viewUser()
    {
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            dump($param);die;
        }
        $uid = input('uid');
        $data = Db::name('member')->where('uid', $uid)->find();
        $this->assign('data', $data);
//        dump($data);die;
        return view('viewUser');
    }
    //查看用户信息
    public function saveUser()
    {
        if(Request::instance()->isPost()){
            $data = input('post.');
            if(Db::name('member')->update($data) !== false){
                $this->success('修改成功');
            }else{
                $this->error('修改失败');
            }
        }
    }

    public function userShenhe()
    {
        $uid = input('uid');
        $type = input('type');
        $re = Db::name('renzheng_geren')->where('uid', $uid)->update(['status' => $type]);
        if ($re && $type == 1) {
            Db::name('member')->where('uid', $uid)->update(['renzheng' => 1]);
        }
        return json(['code' => 0, 'msg' => 'SUCCESS']);
    }

}