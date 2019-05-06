<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 15:00
 */

namespace app\driver\model;


use think\Model;

class Driver extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'create_time';
    protected $updateTime = 'update_time';

    public static function getDriverInfo($uid){
        $userInfo = self::field('id,amount,avatar,username,is_auth')->find(['id'=>$uid]);
        return $userInfo;
    }

    /**
     * 用户金额转换为元
     * @param int $val 金额
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    protected function getAmountAttr($val){
        return ($val / 100);
    }
}