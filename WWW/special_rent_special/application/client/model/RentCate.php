<?php
namespace app\client\model;
use think\Model;
class RentCate extends Model
{
     /**
     * 获取车型分类
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function getCate(){
        $arr=db('rentcate')->field('id,name')->select();
        return $arr;
    }
}
