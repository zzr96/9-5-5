<?php

namespace app\client\controller;
use think\Controller;
use app\client\model\Cart as Car;
class Cart extends Controller
{

    /**
     * 我的购物车
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function myCart(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户id']);
        }
        $list=new Car();
        $arr=$list->myCart($uid);
        return $arr;
    }
    /**
     * 删除购物车
     * @param int $ids  购物车id  12,13,14
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function delCar(){
         $ids=input('ids');//获取购物车id  12,13,14
         if(empty($ids)){
            return json(['code'=>400,'msg'=>'缺少id']);
         }
         $list=new car();
         $arr=$list->delCar($ids);
         return $arr;
    }
    /**
     * 修改购物车数量
     * @param int $cart_id  购物车id
     * @param int $type     1.加 2.减
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function change_num(){
        $cart_id=input('cart_id');//获取购物车id
        if(empty($cart_id)){
            return json(['code'=>400,'msg'=>'cart_id']);
        }
        $type=input('type');    //1.加2.减
        if(empty($type)){
            return json(['code'=>101,'msg'=>'缺少参数type']);
        }
        $list=new Car();
        $arr=$list->change_num($cart_id,$type);
        return $arr;
    }
    /**
     * 添加到购物车
     * @param int $uid  用户id
     * @param int $good_size_id    尺寸id
     * @param int $num   商品数量
     * @param int $goods_id  商品id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function addCart(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $goods_size_id=input('goods_size_id');
        if(empty($goods_size_id)){
            return json(['code'=>400,'msg'=>'缺少参数goods_size_id']);
        }
        $num=input('num');
        //print_r($num);die;
        if(empty($num)){
            return json(['code'=>400,'msg'=>'缺少参数num']);
        }
        $goods_id=input('goods_id');
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>'缺少参数goods_id']);
        }
        $list=new Car();
        $arr=$list->addCart($uid,$num,$goods_size_id,$goods_id);
        return $arr;
    }
}