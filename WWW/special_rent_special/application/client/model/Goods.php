<?php
namespace app\client\model;

use think\Model;
class Goods extends Model
{
     /**
     * 获取商品详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $cate_id  分类id
     * @param int   $p  页码
     * @param int   $type  排序
     */
    public function getGoods($cate_id,$type,$p){
        $order=[];
        if($type){
            if($type==1){
            $order=['dateline asc'];
            }else if($type==2){
                $order=['sales asc'];
            }else if($type==3){
                $order=['sales desc'];
            }else if($type==4){
                $order=['amount asc'];
            }else if($type==5){
                $order=['amount desc'];
            }
        }else{
            $order=['id desc'];
        }
        $p=$p?$p:1;
        $limit=15;
        if($cate_id){
            $where['cate_id']=$cate_id;
            $where['status']=1;
            $count=db('goods')->where($where)->count();
        }else{
             $where['status']=1;
             $count=db('goods')->where($where)->count();
        }
        $count_page = ceil($count/$limit);
        $res=db('goods')->where($where)
        ->order($order)->field('goods_name,id,sales,amount,img1')->page($p,$limit)->select();
        if($res){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 搜索商品
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $cate_id  分类id
     * @param int   $p  页码
     * @param int   $type  排序
     * @param vachar  $goods_name  商品名称关键词
     */
    public function serGoods($cate_id,$p,$type,$goods_name){
        $order=[];
        if($type){
            if($type==1){
            $order=['dateline asc'];
            }else if($type==2){
                $order=['sales asc'];
            }else if($type==3){
                $order=['sales desc'];
            }else if($type==4){
                $order=['amount asc'];
            }else if($type==5){
                $order=['amount desc'];
            }
        }else{
            $order=['id desc'];
        };
        $p=$p?$p:1;
        $limit=15;
        if($cate_id){
            $where['cate_id']=$cate_id;
            $where['status']=1;
            $where['goods_name']=array('like','%'.$goods_name.'%');
            $count=db('goods')->where($where)->count();
        }else{
             $where['status']=1;
             $where['goods_name']=array('like','%'.$goods_name.'%');
             $count=db('goods')->where($where)->count();
        }
        $count_page = ceil($count/$limit);
        $res=db('goods')->where($where)
        ->order($order)->field('goods_name,id,sales,amount,img1')->page($p,$limit)->select();
        if($res){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 获取商品详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $goods_id  商品id
     */
    public function goodsDetail($goods_id){
        $res=db('goods')->where('id',$goods_id)->field('id,goods_name,amount,img1,content,sales')->find();
        return $res;
    }
}
