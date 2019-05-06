<?php
namespace app\client\model;
use think\Model;
class RentEvaluate extends Model
{
     /**
     * 获取车型详情里一条评论
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function rentEvaluate($id){
        $arr=db('rent_evaluate')->where("rent_id=$id")->field('id,u_name,u_logo,star,content,img1,img2,img3,date')->limit(1)->find();
        $arr['count']=db('rent_evaluate')->where("rent_id=$id")->count();
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
     * 获取改车型的所有评价
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function  getEvaluate($id,$p){
         $p=$p?$p:1;
         $limit=15;
         $count=db('rent_evaluate')->where("rent_id=$id")->count();
         $count_page = ceil($count/$limit);
         $arr=db('rent_evaluate')->where("rent_id=$id")->field('id,u_name,u_logo,star,content,img1,img2,img3,date')->page($p,$limit)->select();
         foreach ($arr as $key => &$value) {
            if(empty($value['img1'])){
                unset($arr[$key]['img1']);
            }
            if(empty($value['img2'])){
                unset($arr[$key]['img2']);
            }
            if(empty($value['img3'])){
                unset($arr[$key]['img3']);
            }
            $arr[$key]['date']=date('Y-m-d',$value['date']);
        }
       if($arr){
              return json(['code'=>200,'msg'=>'获取成功','data'=>$arr,'page'=>$count_page]);
       }else{
            return json(['code'=>400,'msg'=>'获取失败']);
       }
    }
}
