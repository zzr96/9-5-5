<?php
namespace app\client\model;
use think\Model;
class RentCar extends Model
{
    /**
     * 获取车型
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $type   排序
     * @param int   $p      页码
     */
    public function getCar($type,$p){
            $aa='';
            $p=$p?$p:1;
            $limit=15;
            if($type){
                 $arr=db('rentcar')->where('cate_id',$type)->field('name,img,id,sale,amount,parts_id')->select();
                 $count=db('rentcar')->where('cate_id',$type)->count();
             }else{
                $arr=db('rentcar')->field('name,img,id,sale,amount,parts_id')->select();
                 $count=db('rentcar')->count();
             }
             //获取车型下的配件
            foreach ($arr as $key => &$value) {
                $value['amount']=($value['amount']/100);
                $parts_id=$value['parts_id'];
                $parts_id = explode(",", $parts_id);
                foreach ($parts_id as $k => $v) {
                    $parts=db('parts')->where('id',$v)->find();
                    $aa.=$parts['name']."|";
                }
                $aa=substr($aa,0,strlen($aa)-1);
                $arr[$key]['parts']=$aa;
            }
        return $arr;
    }
    /**
     * 车型详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function carDetail($id){
        $arr=db('rentcar')->where('id',$id)->find();
        return $arr;
    }
    /**
     * 获取车配
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getParts($id){
        $res=db('rentcar')->where('id',$id)->find();
        $arr=$res['parts_id'];
        if($arr){
            $data=explode(",", $arr);
            $arr=array();
            $list='';
            foreach ($data as $key => $value) {
                    $list=db('parts')->where('id',$value)->field('id,name,img,amount')->find();
                    $list['amount']=$list['amount']/100;
                    $arr[]=$list;
            }
        }else{
            $arr='';
        }

        return $arr;
    }

}
