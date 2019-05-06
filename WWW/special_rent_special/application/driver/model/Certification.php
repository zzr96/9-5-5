<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 19:45
 */

namespace app\driver\model;


use think\Model;

class Certification extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';
    //隐藏时间
    protected $hidden = ['create_time','update_time'];


    /**
     * desc 驾驶证转json
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:03
     */
    protected function setDriverImgAttr($val){
        return json_encode(explode(',',$val)) ;
    }

    /**
     * desc 行驶证转json
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:03
     */
    protected function setTravelImgAttr($val){
        return json_encode(explode(',',$val)) ;
    }

    /**
     * desc 驾驶证转数组
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:03
     */
    protected function getDriverImgAttr($val){
        return json_decode($val,true) ;
    }

    /**
     * desc 行驶证转数组
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:03
     */
    protected function getTravelImgAttr($val){
        return json_decode($val,true) ;
    }
}