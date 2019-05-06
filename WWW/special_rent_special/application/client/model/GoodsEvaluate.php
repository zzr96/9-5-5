<?php
namespace app\client\model;

use think\Model;
class GoodsEvaluate extends Model
{
     /**
     * 获取商品详情里一条评论
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function goodsEvaluate($goods_id,$type){
        $arr=db('goods_evaluate')->where("goods_id=$goods_id && type=$type")->field('id,u_name,u_logo,star,content,img1,img2,img3,date')->limit(1)->find();
        $arr['count']=db('goods_evaluate')->where("goods_id=$goods_id && type=$type")->count();
        if(empty($arr['img1'])){
            unset($arr['img1']);
        }
        if(empty($arr['img2'])){
            unset($arr['img2']);
        }
        if(empty($arr['img3'])){
            unset($arr['img3']);
        }
        return $arr;
    }
     /**
     * 获取商品的全部评价
     * @param int $id 商品id
     * @param int $type 类型  1:商城商品 2:积分商品
     * @param int $p   页码
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return 1,2
     */
    public function showEvaluate($id,$type,$p){
        $p=$p?$p:0;
        $limit=15;
        $count=db('goods_evaluate')->where("goods_id=$id && type=$type")->count();
        $count_page = ceil($count/$limit);
        $arr=db('goods_evaluate')->where("goods_id=$id && type=$type")->field('id,u_name,u_logo,star,content,img1,img2,img3,date')->page($p,$limit)->select();
        foreach ($arr as $key => $value) {
            if(empty($value['img1'])){
                unset($arr[$key]['img1']);
            }
            if(empty($value['img2'])){
                unset($arr[$key]['img2']);
            }
            if(empty($value['img3'])){
                unset($arr[$key]['img3']);
            }
        }
        if($arr){
              return json(['code'=>200,'msg'=>'获取成功','data'=>$arr,'page'=>$count_page]);
       }else{
            return json(['code'=>400,'msg'=>'获取失败']);
       }
    }
}