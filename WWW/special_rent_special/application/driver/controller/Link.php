<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/27
 * Time: 14:27
 */

namespace app\driver\controller;


use think\Controller;
use app\driver\model\Link as LinkModel;
use app\driver\model\About;

class Link extends Controller
{
    /**
     * desc 联系客服
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 14:31
     */
    public function getLink(){
        $data = LinkModel::all();
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
    }
    /**
     * desc 关于我们
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 14:31
     */
    public function getAbout(){
        $data = About::get(1);
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
    }
}