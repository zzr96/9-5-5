<?php
namespace app\client\model;

use think\Model;
class Category extends Model
{
    public function getCate(){
        $list=db('category')->where('pid=0')->order('weigh asc,id asc')->field('id,name,image')->select();
        return $list;
    }
}
