<?php
namespace app\client\model;
use think\Model;
/**
 * 车型收藏,商品收藏,积分商品收藏.
 */
class Collect extends Model
{
    /**
     * 判断是否收藏
     * @param int $uid 用户id
     * @param int $id  商品的id（车型id，商城商品id，积分商品id）
     * @param int $type 类型  1:车型 2:商品  3：积分商品
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return 1,2
     */
      public function isCollect($uid,$id,$type){
        $res=db('collect')->where("uid=$uid && itemid=$id && type=$type")->find();
        if($res){
           return 1;
        }else{
           return 2;
        }
    }
    /**
     * 关注和取消收藏
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function collect($uid,$id,$type){
        $arr=db('collect')->where("uid=$uid && itemid=$id && type=$type")->find();
        if($arr){
            $res=db('collect')->where("uid=$uid && itemid=$id && type=$type")->delete();
            if($res){
                return json(['code'=>200,'msg'=>'取消收藏成功']);
            }else{
                return json(['code'=>400,'msg'=>'取消收藏失败']);
            }
        }else{
            $data['uid']=$uid;
            $data['itemid']=$id;
            $data['createtime']= date('Y-m-d h:i:s');
            $data['type']=$type;
            $res=db('collect')->insert($data);
            if($res){
                 return json(['code'=>200,'msg'=>'收藏成功']);
            }else{
                return json(['code'=>400,'msg'=>'收藏失败']);
            }
        }
    }
    /**
     * 我的收藏
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function myCollect($uid){
        $list=db('collect')->where("uid=$uid")->select();
        $arr=array();
        foreach ($list as $key => $value) {
            //获取收藏的车型
            if($value['type']==1){
                $data=db('rentcar')->where('id',$value['itemid'])->field('id,img,name,amount')->find();
                $data['type']=$value['type'];
                $arr[]=$data;
            }
            //获取收藏的商品
            if($value['type']==2){
                 $data=db('goods')->where('id',$value['itemid'])->field('id,img1,goods_name,amount')->find();
                  $data['type']=$value['type'];
                  $data['name']=$data['goods_name'];
                  unset($data['goods_name']);
                  $arr[]=$data;
            }
            //获取收藏的积分商品
            if($value['type']==3){
                  $data=db('integrate_goods')->where('id',$value['itemid'])->field('id,img1,goods_name,amount')->find();
                   $data['type']=$value['type'];
                   $data['name']=$data['goods_name'];
                   unset($data['goods_name']);
                   $arr[]=$data;
            }
        }
        return $arr;
    }
}