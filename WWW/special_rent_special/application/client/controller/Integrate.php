<?php
namespace app\client\controller;
use think\Controller;
use app\client\model\IntegrateGoods;
use app\client\model\GoodsEvaluate;
use app\client\model\Collect;
/**
 * 积分商城接口
 */
class Integrate extends Controller
{
     /**
     * 获取用户积分和积分商品
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return $arr
     */
    public  function index(){
        $uid=input('uid');//获取用户id
        $p=input('p');//分页
        if(empty($uid)){
             return json(['code'=>400,'msg'=>"缺少用户id"]);
        }
        $list=new IntegrateGoods();
        $arr=$list->index($uid,$p);
        return $arr;
    }
    /**
     * 根据id获取积分商品详情
     * @param int $goods_id 商品id
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return $arr
     */
    public function detail(){
        $goods_id=input('goods_id');
        $uid=input('uid');//用户id
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>"缺少商品id"]);
        }
        $list=new IntegrateGoods();
        $arr=$list->detail($goods_id);
        //获取该用户是否收藏
        if($uid){
            $collect=new Collect();
            $is_collect=$collect->isCollect($uid,$goods_id,$type=3);
            $arr['is_collect']=$is_collect;
        }else{
            $arr['is_collect']=2;//未收藏
        }
        //获取评价 //获取评价
        $evaluate=new GoodsEvaluate();
        $goods_evaluate=$evaluate->goodsEvaluate($goods_id,$type=2);
        if($arr && $goods_evaluate){
            return json(['code'=>200,'msg'=>'获取商品详情成功','data'=>$arr,'evaluate'=>$goods_evaluate]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 添加商品收藏和取消商品收藏
     * @param int $uid 用户id
     * @param int $goods_id  商品的id（车型id，商城商品id，积分商品id）
     * @param int $type 类型  1:车型 2:商品  3：积分商品
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return
     */
    public function collect(){
        $uid=input('uid');//用户id
        if(empty($uid)){
            return json(['code'=>400,'msg'=>"缺少uid"]);
        }
        $goods_id=input('goods_id');//商品id
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>"缺少商品id"]);
        }
        $list=new Collect();
        return $arr=$list->collect($uid,$goods_id,$type=3);
    }
    /**
     * 获取积分商品的全部评价
     * @param int $goods_id 商品id
     * @param int $type 类型  1:商城商品 2:积分商品
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return
     */
    public function showEvaluate(){
        $goods_id=input('goods_id');
        $p=input('p');
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>"缺少商品id"]);
        }
        $list=new GoodsEvaluate();
        $arr=$list->showEvaluate($goods_id,$type=2,$p);
        return $arr;
    }
}