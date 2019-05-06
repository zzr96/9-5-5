<?php
namespace app\client\model;

use think\Model;
class RentCollect extends Model
{
     /**
     * 判断是否收藏
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function isCollect($uid,$id){
        $res=db('rent_collect')->where("uid=$uid && itemid=$id")->find();
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
    public function collect($uid,$id){
        $arr=db('rent_collect')->where("uid=$uid && itemid=$id")->find();
        if($arr){
            $res=db('rent_collect')->where("uid=$uid && itemid=$id")->delete();
            if($res){
                return json(['code'=>200,'msg'=>'取消收藏成功']);
            }else{
                return json(['code'=>400,'msg'=>'取消收藏失败']);
            }
        }else{
            $data['uid']=$uid;
            $data['itemid']=$id;
            $data['createtime']= date('Y-m-d h:i:s');
            $res=db('rent_collect')->insert($data);
            if($res){
                 return json(['code'=>200,'msg'=>'收藏成功']);
            }else{
                return json(['code'=>400,'msg'=>'取消收藏失败']);
            }
        }
    }
}
