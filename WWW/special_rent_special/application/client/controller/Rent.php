<?php
namespace app\client\controller;
use think\Controller;
use app\client\model\RentCate;
use app\client\model\Parts;
use app\client\model\RentCar;
use app\client\model\RentEvaluate;
use app\client\model\Collect;
/**
 * 租车接口
 */
class Rent extends Controller
{

    /**
     * 获取车型分类
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getCate()
    {
        $list=new RentCate();
        $arr=$list->getCate();
        if($arr){
            return json(['code'=>200,'msg'=>'获取车型分类成功','data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>'获取车型分类失败']);
        }
    }
    /**
     * 获取车型
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getCar(){
        $type=input('id');//车型分类的id
         $p=input('p');//页码
        $list=new RentCar();
        $arr=$list->getCar($type,$p);
        if($arr){
            return json(['code'=>200,'msg'=>'获取车型成功','data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>'获取车型失败']);
        }
    }
    /**
     * 车型详情
     * @param string $msg   提示信息
     * @param mixed $arr  $rent_evaluate要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function carDetail(){
        $id=input('id');
        if(empty($id)){
            return json(['code'=>400,'msg'=>'缺少车型id']);
        }
        //获取车型详情
        $list= new RentCar();
        $arr=$list->carDetail($id);
        $uid=input('uid');
        if($uid){
            $collect=new Collect();
            $is_collect=$collect->isCollect($uid,$id,$type=1);
            $arr['is_collect']=$is_collect;
        }else{
            $arr['is_collect']=2;
        }
        //获取详情里一条评价
        $evaluate=new RentEvaluate();
        $rent_evaluate=$evaluate->rentEvaluate($id);
        if($arr && $rent_evaluate){
            return json(['code'=>200,'msg'=>'获取车型详情成功','data'=>$arr,'evaluate'=>$rent_evaluate]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 获取配件详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function partsDetail(){
        $id=input('id');
        if(empty($id)){
            return json(['code'=>400,'msg'=>"缺少id"]);
        }
        $list=new Parts();
        $arr=$list->partsDetail($id);
        if($arr){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }
    /**
     * 获取配件
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getParts(){
        $id=input('id');//车型id
        if(empty($id)){
             return json(['code'=>400,'msg'=>"缺少车型id"]);
        }
        $list=new RentCar();
        $arr=$list->getParts($id);
        return json(['code'=>200,'msg'=>'获取成功','data'=>$arr]);
    }
    /**
     * 运费规则
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function freightRules(){
        $arr=db('about')->where('id=1')->find();
        $crisis=$arr['freight'];
        return json(['code'=>200,'msg'=>'获取成功','crisis'=>$crisis]);
    }
    /**
     * 收藏和取消收藏车型
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function collect(){
        $uid=input('uid');//用户id
        if(empty($uid)){
            return json(['code'=>400,'msg'=>"缺少uid"]);
        }
        $id=input('id');//车型id
        if(empty($id)){
             return json(['code'=>400,'msg'=>"缺少车型id"]);
        }
        $list=new Collect();
        return $arr=$list->collect($uid,$id,$type=1);
    }
    /**
     * 获取改车型的所有评价
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getEvaluate(){
        $id=input('id');//车型id
        $p=input('p');//页码
        $list= new RentEvaluate();
        return $arr=$list->getEvaluate($id,$p);
    }
}
