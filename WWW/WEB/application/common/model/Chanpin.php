<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/19
 * Time: 8:57
 */

namespace app\common\model;


use think\Model;

class Chanpin extends Model
{
    //未知原因获取器添加不存在字段不能用
//    protected function getCatNameqAttr($value){
//        $name=db('deal_category')->where('id',$value)->value('name');
//        return $name;
//
//    }
    public function getById($id)
    {
        $res = $this->where('id', $id)
            ->find()
            ->toArray();
        $name = db('deal_category')->where('id', $res['cat'])->value('name');
        $res['cat_name'] = $name;
        halt($res);
        return $res;
    }

}