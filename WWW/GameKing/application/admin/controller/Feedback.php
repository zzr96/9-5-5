<?php

namespace app\admin\controller;

use think\Db;
class Feedback extends Base
{
    protected $paginate = 10;
    protected $beforeActionList = [
        'first',
    ];

    public function first()
    {
        $this->assign('title', '意见反馈管理');
    }
    //反馈列表
    public function index()
    {
        $data = Db::name('feedback')->field('f.*,m.phone,m.nickname,m.avatar')->alias('f')->join('member m','f.uid = m.uid','left')->paginate($this->paginate);
        $list = $data->items();
        $page = $data->render();
        $this->assign('list', $list);
        $this->assign('page', $page);
        return view();
    }
    //反馈详情
    public function detail()
    {
        $id = input('id');
        $data = Db::name('feedback')->find($id);
        $data['nickname'] = Db::name('member')->where('uid',$data['uid'])->value('nickname');
        $this->assign('data', $data);
        return view();
    }
    //删除反馈
    public function del(){
        $id = input('id');
        $re = Db::name('feedback')->where(['id'=>['in',$id]])->delete();
        if($re){
            return json(['code'=>200,'msg'=>'SUCCESS']);
        }
        return json(['code'=>400,'msg'=>'FAIL']);
    }

}
