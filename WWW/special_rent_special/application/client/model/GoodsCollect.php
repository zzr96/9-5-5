<?php
namespace app\client\model;

use think\Model;
class GoodsCollect extends Model
{
    /**
     * 判断是否收藏
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $uid    用户id
     * @param int   $goods_id    商品id
     */
    public function isCollect($uid,$goods_id){
        $res=db('goods_collect')->where("uid=$uid && itemid=$goods_id")->find();
        if($res){
           return 1;
        }else{
           return 2;
        }
    }
}
