<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 15:00
 */

namespace app\client\model;


use think\Model;

class User extends Model
{
    // 开启自动写入时间戳字段
    protected $autoWriteTimestamp = 'int';

    // 定义时间戳字段名
    protected $createTime = 'createtime';
    protected $updateTime = 'updatetime';
     /**
     * 查看个人资料
     * @param int $uid 用户id
     * @author 江妙君 [ 861498687@qq.com ]
     * @created 2019.04.25
     * @editor
     * @updated
     * @return arr
     */
    public function showUser($uid){
        $arr=db('user')->where('id',$uid)->field('id,nickname,avatar,mobile,gender,addr,porn')->find();
        if($arr){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$arr]);
        }else{
            return json(['code'=>400,'msg'=>'获取失败']);
        }
    }

    //余额转换为元
    protected function getMoneyAttr($val){
        return $val / 100;
    }
}