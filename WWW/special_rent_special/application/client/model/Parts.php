<?php
namespace app\client\model;
use think\Model;
class Parts extends Model
{
     /**
     * 获取配件详情
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function partsDetail($id){
        $arr=db('parts')->where('id',$id)->find();
        return $arr;
    }
}
