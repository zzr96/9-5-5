<?php

namespace app\client\controller;
use think\Controller;
use app\client\model\Category;
use app\client\model\Goods as goodss;
use app\client\model\Collect;
use app\client\model\GoodsEvaluate;
use app\client\model\GoodsSize;
/**
 * 商城接口
 */
class Goods extends Controller
{
    protected $noNeedLogin = ['*'];
    protected $noNeedRight = ['*'];
    /**
     * 获取商品分类
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
   public function showCate(){
        $list=new category();
        $arr=$list->getCate();
        if($arr){
            return json(['code'=>200,'msg'=>'获取商品分类成功','data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>'获取商品分类失败']);
        }
    }
    /**
     * 显示商品
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $cate_id  分类id
     */
    public function showGoods(){
        //分类id
        $cate_id=input('cate_id');
        //商品帅选条件//1.综合2.销量高到低 3.销量低到高4.价格高到底5.价格低到高
        $type=input('type');
        $p=input('p');
        $list=new goodss();
        return $arr=$list->getGoods($cate_id,$type,$p);
    }
    /**
     * 搜索商品
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $cate_id  分类id
     * @param int   $p  页码
     * @param int   $type  排序
     * @param int   $goods_name  商品名称
     */
    //搜索商品
    public function serGoods(){
         //分类id
        $cate_id=input('cate_id');
        //商品帅选条件//1.综合2.销量高到低 3.销量低到高4.价格高到底5.价格低到高
        $type=input('type');
        $p=input('p');
        $goods_name=input('goods_name');
        if(empty($goods_name)){
             return json(['code'=>400,'msg'=>'请输入商品名称']);
        }
        $list=new goodss();
        return $arr=$list->serGoods($cate_id,$type,$p,$goods_name);
    }
    /**
     * 商品详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $goods_id  商品id
     */
    //商品详情
    public function goodsDetail(){
        $goods_id=input('goods_id');
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>'请输入商品id']);
        }
        $list=new goodss();
        //获取商品详情
        $arr=$list->goodsDetail($goods_id);
        //获取该用户是否收藏
        $uid=input('uid');
        if($uid){
            $collect=new Collect();
            $is_collect=$collect->isCollect($uid,$goods_id,$type=2);
            $arr['is_collect']=$is_collect;
        }else{
            $arr['is_collect']=2;//未收藏
        }
        //获取评价
        $evaluate=new GoodsEvaluate();
        $goods_evaluate=$evaluate->goodsEvaluate($goods_id,$type=1);
        if($arr && $goods_evaluate){
            return json(['code'=>200,'msg'=>'获取商品详情成功','data'=>$arr,'evaluate'=>$goods_evaluate]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 获取商品规格
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $goods_id  商品id
     */
    public function goods_size(){
        $goods_id=input('goods_id');
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>'请传商品id']);
        }
        $list=new GoodsSize();
        $arr=$list->getSize($goods_id);
        if($arr){
             return json(['code'=>200,'msg'=>'获取规格成功','data'=>$arr]);
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
     * @return 1,2
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
        return $arr=$list->collect($uid,$goods_id,$type=2);
    }
     /**
     * 获取商品的全部评价
     * @param int $goods_id 商品id
     * @param int $type 类型  1:商城商品 2:积分商品
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function showEvaluate(){
        $goods_id=input('goods_id');
        $p=input('p');
        if(empty($goods_id)){
             return json(['code'=>400,'msg'=>"缺少商品id"]);
        }
        $list=new GoodsEvaluate();
        $arr=$list->showEvaluate($goods_id,$type=1,$p);
        return $arr;
    }
    /**
     * 根据规格获取商品的价格和库存
     * @param int $color 第一个尺寸值
     * @param int $color 第二个尺寸值
     * @param int $goods_id 商品id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.26
     * @editor
     * @updated
     * @return arr
     */
    public function getSize(){
        $color=input('color');//第一个规格的值
        $spec=input('spec');//第二个规格的值
        $goods_id=input('goods_id');//商品id
        if(!empty($color)){
            $data['color']=$color;
        }
        if(!empty($spec)){
            $data['spec']=$spec;
        }
        $data['goods_id']=$goods_id;
        $list=new GoodsSize();
        $arr=$list->goodsSize($data);
        return $arr;
    }
     /**
     * 添加商品评价页面
     * 根据商品订单id获取该订单商品信息
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $order_id  商品id
     */
    function get_sale_ym(){
        $order_id=input('order_id');
        if(empty($order_id)){
            return json(['code'=>400,'msg'=>'缺少参数order_id','data'=>'']);
        }
        $res=db('order_goods')->where('order_id',$order_id)->field('id,goods_size_id,goods_img,goods_name,goods_num,amount')->select();
        $num_price=0;
        foreach($res as $key=>$value){
            $num_price+=$value['amount']*$value['goods_num'];
            $goods_size=db('goods_size')->where('id',$value['goods_size_id'])->field('goods_id,spec,color,spec,color')->find();
            $res[$key]['goods_id']=$goods_size['goods_id'];
            $res[$key]['size']=$goods_size['spec']." ".$goods_size['color'];
        }
        if($res){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'num_price'=>$num_price]);
        }else{
            return json(['code'=>400,'msg'=>'暂未数据','data'=>'']);
        }
    }
     /**
     * 提交商品评价
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $order_id  商品id
     */
    function order_evaluate(){
        $order_id=input('order_id');
        $content=input('content');
        $img=input('img');
        $type=input('type');
        $star=input('star');
        $uid=input('uid');
        $user=db('user')->where('uid',$uid)->field('avatar,nickname')->find();
        if($type==1){
            $logo="匿名";
        }else{
            $logo=$user['avatar'];
        }
        $arr=db('order_goods')->where('order_id',$order_id)->select();
        foreach ($arr as $key => $value) {
            $goods_size=db('goods_size')->where('id',$value['goods_size_id'])->find();
            $goods=db('goods')->where('id',$goods_size['good_id'])->find();
            //$data['uid']=$uid;
            //$data['star']=$star;
            //$data['u_logo']=$user['avatar'];
            //$data['u_name']=$user['nickname'];
            //$data['good_id']=$goods_size['good_id'];

            $data['g_name']=$goods['goods_name'];
            $data['g_logo']=$goods['img1'];
            $data['content']=$content;
            $data['g_price']=$goods['price'];
            $data['date']=date("Y-m-d H:i:s");
            $res=db('good_evaluate')->insertGetId($data);
            if($img){
                if(is_array($img)){
                foreach($img as $key=>$value){
                    db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$value]);
                    }
                }else{
                    db('goods_evimg')->insert(['ev_id'=>$res,'img'=>$img]);
                }
            }
        }
        if($arr){
            db('order')->where('id',$order_id)->update(['status'=>5]);
            return json(['code'=>200,'msg'=>'评价成功']);
        }else{
            return json(['code'=>400,'msg'=>'评价失败']);
        }
    }
}