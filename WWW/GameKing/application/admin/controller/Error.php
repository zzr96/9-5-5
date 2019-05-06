<?php
namespace app\admin\controller;

class Error extends Base
{
    public function _empty()
    {
        $this->assign("title", "未定义页面");
        $request = \think\Request::instance();
        return view("common:tpl");
    }
}
