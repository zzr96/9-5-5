<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 17:54
 */

namespace app\client\controller;

use app\client\model\Banner;
use app\client\model\Rental;
use think\Controller;
use think\Request;

class Index extends Controller
{

    /**
     * desc: 获取首页轮播图
     * path: banner
     * method: get
     * time: 2019-04-24
     */
    public function banner(){
        if(Request::instance()->isGet()){
            $banner = Banner::getBanner();
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$banner]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);

    }

    /**
     * desc: 获取租车指南
     * path: guide
     * method: get
     * time: 2019-04-24
     */
    public function guide(){
        if(Request::instance()->isGet()){
            $rental = Rental::getRental();
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$rental]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);

    }


    /**
     * desc: 获取租车指南
     * path: guide
     * method: get
     * time: 2019-04-24
     * param id  -int 租车指南记录id
     */
    public function guideDetail(){
        $id = input('id',0);
        $data = Rental::get($id);
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
    }
}