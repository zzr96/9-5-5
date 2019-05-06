<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 15:09
 */

namespace app\driver\model;


use think\Model;

class RentEvaluate extends  Model
{
    /**
     * 用户评分
     * @param int $uid 行为id
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public static function getDriverScore($uid){
        $score = self::where(['driver_id'=>$uid])->avg('star');
//        dump($score);die;
        return $score ? round($score,1) : 5;
    }

    /**
     * 用户司机评价
     * @param int $uid 行为id
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public static function getDriverComment($uid){

        $total = self::where(['driver_id'=>$uid])->count();
        $good = self::where(['driver_id'=>$uid,'star'=>5])->count();
        $get = self::where(['driver_id'=>$uid])->sum('star');
        $rate = $get / ($total * 5) * 100 ;
        $data['total'] = $total;
        $data['good'] = $good;
        $data['rate'] = round($rate) . '%';
        return $data ;
    }

    //评价时间转换
    protected function getDateAttr($val){
        return date('Y-m-d',$val);
    }

}