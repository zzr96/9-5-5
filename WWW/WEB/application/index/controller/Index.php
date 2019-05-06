<?php
namespace app\index\controller;

use think\Controller;

class Index extends Controller
{

    public function index()
    {
        return date('Y年m月d日 H时i分m秒');
    }

    //注册
    public function register()
    {
        $pid = input('pid');
        $this->assign('pid',$pid);
        return view();
    }
}
