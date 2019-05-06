<?php

namespace app\api\controller;

use app\common\controller\Api;

/**
 * 首页接口
 */
class Index extends Api
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];

    /**
     * 首页
     *
     */

    //-1未支付订单0已支付订单未被司机接单1订单已被分配2已接单(待出发)3待施工4待完工5已完成
    public function index()
    {
        $this->success('请求成功');
    }
}
