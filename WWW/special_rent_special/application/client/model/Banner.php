<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 17:55
 */

namespace app\client\model;


use think\Model;

class Banner extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';
    //获取banner
    public static function getBanner(){
        $banner = self::where(['status'=>1])->order('create_time desc')->select();
        return$banner;
    }
}