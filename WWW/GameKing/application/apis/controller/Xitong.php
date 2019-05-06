<?php

namespace app\apis\controller;

use think\Db;
use think\Controller;

/**
 * swagger: 系统基本功能
 */
class Xitong extends Controller
{

    /**
     * get: 关于我们
     * path: about
     * method: about
     */
    public function aboutUs()
    {
        $res = Db::name('about')->select();
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $res]);
    }

    /**
     * get: 联系我们
     * path: link
     * method: link
     */
    public function link()
    {
        $res = Db::name('system_link')->select();
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $res]);
    }


    /**
     * get: 获取推广规则
     * path: link
     * method: link
     */
    public function getRule()
    {
        $res = Db::name('tuiguang')->find(1);
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $res]);
    }

}
