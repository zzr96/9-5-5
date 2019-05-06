<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/25
 * Time: 14:00
 */

namespace app\driver\controller;

use app\driver\model\Account;
use app\driver\model\Certification;
use app\driver\model\Driver;
use app\driver\model\Feedback;
use app\driver\model\RentEvaluate;
use app\driver\model\Rentorder;
use app\driver\model\Tixian;
use think\Request;
use think\Validate;
use think\Db;

class Member extends Base
{
    /**
     * 个人中心
     * @method: post
     * @param int $uid 用户uid
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function userCenter(){
        if(Request::instance()->isGet()){
            $param = Request::instance()->param();
            $rule = [
              'uid|用户uid' => 'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $userInfo = Driver::getDriverInfo($param['uid']);
            if(!$userInfo){
                return json(['code'=>400,'msg'=>'用户不存在']);
            }
            $userInfo['score'] = RentEvaluate::getDriverScore($param['uid']);
            $userInfo['today_hour'] = Rentorder::getOrderCounts($param['uid'],true);
            $userInfo['all_hour'] = Rentorder::getOrderCounts($param['uid']);
            $userInfo['today_order'] = Rentorder::getOrderCounts($param['uid'],true);
            $userInfo['all_order'] = Rentorder::getOrderCounts($param['uid']);

            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$userInfo]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);

    }
    /**
     * 添加银行卡
     * @method: post
     * @param int $uid 行为id
     * @param string bankname 行为银行名称
     * @param string card 卡号
     * @param string recard 确认卡号
     * @param string name 姓名
     * @param string idcard 身份证号
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function addBankAccount(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
                'bankname|银行名称' => 'require',
                'card|银行卡号'=>'require|confirm:recard',
                'name|姓名' => 'require',
                'idcard|身份证号' => 'require'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Account::get(['uid'=>$param['uid'],'type'=>1]);
            if($acc){
                return json(['code'=>400,'msg'=>'只能添加一张银行卡哟']);
            }
            try{
                Account::create($param,true);
                return json(['code'=>200,'msg'=>'添加成功']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * 添加支付宝账户
     * @method: post
     * @param int $uid  用户uid
     * @param string card 支付宝账户
     * @param string name 姓名
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function addAlipayAccount(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
                'card|支付宝账号'=>'require|confirm:recard',
                'name|姓名' => 'require',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Account::get(['uid'=>$param['uid'],'type'=>2]);
            if($acc){
                return json(['code'=>400,'msg'=>'只能绑定一个支付宝账户哟']);
            }
            try{
                $param['type'] = 2;
                Account::create($param,true);
                return json(['code'=>200,'msg'=>'绑定成功']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 添加微信收款码
     * @method: post
     * @param int $uid 用户uid
     * @param string card 收款码
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function addWechatAccount(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
                'card|收款码'=>'require',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Account::get(['uid'=>$param['uid'],'type'=>3]);
            if($acc){
                return json(['code'=>400,'msg'=>'只能添加一个微信账户哟']);
            }
            try{
                $param['type'] = 3;
                Account::create($param,true);
                return json(['code'=>200,'msg'=>'添加成功']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * 获取提现账户
     * @method: get
     * @param int $uid 行为id
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function getAccount(){
        if(Request::instance()->isGet()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Account::field('id,uid,type,card')->where(['uid'=>$param['uid']])->select();
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$acc]);

        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * 删除账户
     * @method: get
     * @param int $uid 用户uid
     * @param int $id 账户id
     * @author 付鹏 [ fupengdeyouxiang@qq.com ]
     * @created 2019.04.25
     * @editor 付鹏 [ fupengdeyouxiang@qq.com ]
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function delAccount(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
                'id|账户id' => 'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            try{
                Account::destroy(['id'=>$param['id'],'uid'=>$param['uid']]);
                return json(['code'=>200,'msg'=>'删除成功']);
            }catch (\Exception $e){
                return json(['code'=>200,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 司机认证
     * @method: post
     * @param int $uid 行为id
     * @param string avatar 头像
     * @param string realname 真实姓名
     * @param string idcard 身份证号
     * @param string mobile 本人手机
     * @param string panic_mobile 紧急电话
     * @param string type 车型1
     * @param string _type 车型2
     * @param string front_card 手持身份证正面
     * @param string driver_img 驾驶证
     * @param string travel_img 行驶证
     * @param string skills 技能
     * @author 付鹏
     * @created 2019.04.25
     * @editor 付鹏
     * @updated 2019.04.25 18:00
     * @return mixed
     */
    public function setCertification(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid'=>'require|number',
                'avatar|头像' => 'require',
                'realname|真实姓名' => 'require',
                'idcard|身份证号' => 'require',
                'mobile|本人手机' => 'require',
                'panic_mobile|紧急电话' => 'require',
                'front_card|手持身份证正面' => 'require',
                'driver_img|驾驶证' => 'require',
                'travel_img|行驶证' => 'require',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Certification::get(['uid'=>$param['uid']]);
            if($acc && $acc->status == 0){
                return json(['code'=>400,'msg'=>'您的认证信息正在审核中']);
            }
            if($acc && $acc->status){
                return json(['code'=>400,'msg'=>'您的认证信息已通过审核']);
            }
            if(empty($param['type']) && empty($param['_type']) && empty($param['skills'])){
                $param['role'] = 2;
            }elseif((!empty($param['type']) || !empty($param["_type"]))  && empty($param['skills'])){
                $param['role'] = 1;
            }elseif(!empty($param['skills'])){
                $param['role'] = 3;
            }
//            $param['driver_img'] = json_encode(explode(',',$param['driver_img']));
//            $param['travel_img'] = json_encode(explode(',',$param['travel_img']));
            try{
                Certification::create($param);
                return json(['code'=>200,'msg'=>'提交成功，请等待审核']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 获取司机用户信息
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:00
     * param int $uid 用户uid
     */
    public function getCertification(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $info = Certification::get(['uid'=>$uid]);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$info]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * 反馈
     * @method: post
     * @param int $uid 用户uid
     * @param string type 反馈类型
     * @param string content 反馈内容
     * @param string img 图片
     * @author 付鹏
     * @created 2019.04.26
     * @editor 付鹏
     * @updated 2019.04.26 18:00
     * @return mixed
     */
    public function feedBack(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid' => 'require|number',
                'type|反馈类型' => 'require',
                'content|反馈内容' => 'require'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            if(!empty($param['img'])){
                $param['img'] = json_encode(explode(',',$param['img']));
            }
            try{
                Feedback::create($param);
                return json(['code'=>200,'msg'=>'提交成功']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 修改个人资料
     * @method: post
     * @param int $uid 用户uid
     * @param string avatar 头像
     * @param string username 姓名
     * @param string sex 性别
     * @param string mobile 手机号
     * @author 付鹏
     * @created 2019.04.26
     * @editor 付鹏
     * @updated 2019.04.26 18:00
     * @return mixed
     */
    public function updateUserInfo(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid' => 'require|number',
                'avatar|头像' => 'require',
                'sex|性别' => 'require|number',
                'mobile|手机号' => 'require|regex:/^1[345789]\d{9}$/',
                'username|姓名' => 'require|chs',
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $driver = Driver::get(['id'=>$param['uid']]);
            if($param['mobile'] != $driver->mobile){
                $has = Driver::get(['mobile'=>$param['mobile']]);
                if($has){
                    return json(['code'=>400,'手机号已存在']);
                }
            }
            try{
                $driver->avatar = $param['avatar'];
                $driver->sex = $param['sex'];
                $driver->mobile = $param['mobile'];
                $driver->username = $param['username'];
                $driver->save();
                return json(['code'=>200,'msg'=>'修改成功']);
            }catch (\Exception $e){
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * desc 获取个人资料
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:00
     * param int $uid 用户uid
     */
    public function getUserInfo(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $info = Driver::field('id,avatar,mobile,username,sex')->find(['id'=>$uid]);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$info]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * desc 我的账户
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:00
     * param int $uid 用户uid
     */
    public function getBalance(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $info = Driver::field('id,amount')->find(['id'=>$uid]);
            $info['today_hours'] = Rentorder::getHourCounts($uid,true);
            $info['total_hours'] = Rentorder::getHourCounts($uid);
            $info['today_amount'] = Rentorder::getAmountCounts($uid,true);
            $info['total_amount'] = Rentorder::getAmountCounts($uid);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$info]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * desc 工时明细
     * author 付鹏
     * createDay: 2019/4/26
     * createTime: 14:00
     * param int $uid 用户uid
     * param int type 订单类型1租车2司机3维修保养
     */
    public function getWorkDetails(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            $type = input('type/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            if($type == 1){
                //租车订单
                $order = getRentOrder($uid);
            }elseif($type == 2){
                //司机订单
            }elseif($type == 3){
                //维修保养订单
            }else{
                //全部订单
                $account_info = Db::name('rentorder')
                    ->alias('a')
                    ->join('rentorders b','a.uid = b.uid AND ','left')
                    ->join('rentorderc c','b.uid = c.uid','left')
                    ->where(['a.driver_id' => $uid])
                    ->select();

            }

            dump($account_info);die;

            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$order]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


    /**
     * desc 提现
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 14:00
     * param int $uid 用户uid
     * param int type 账户类型 1银行卡2支付宝3微信
     * param int amount 金额
     */
    public function getMoney(){
        if(Request::instance()->isPost()){
            $param = Request::instance()->param();
            $rule = [
                'uid|用户uid' => 'require|number',
                'type|账户类型' => 'require|number',
                'amount|提现金额' => 'require|number'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($param)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $acc = Account::get(['uid'=>$param['uid'],'type'=>$param['uid']]);
            if(!$acc){
                return json(['code'=>400,'msg'=>'请先添加提现账户']);
            }
            $param['amount'] = $param['amount'] * 100 ;
            $amount = Driver::where(['id'=>$param['uid']])->value('amount');
            if($amount < $param['amount']){
                return json(['code'=>400,'msg'=>'账户余额不足']);
            }
            Driver::startTrans();
            try{
                Driver::where(['id'=>$param['uid']])->setDec('amount',$param['amount']);
                Tixian::create($param);
                Driver::commit();
                return json(['code'=>200,'msg'=>'申请成功']);
            }catch (\Exception $e){
                Driver::rollback();
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 13:28
     * desc 提现明细
     * param int $uid 用户uid
     */
    public function getMoneyDetails(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = Tixian::all(['uid'=>$uid]);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 13:28
     * desc 我的评价
     * param int $uid 用户uid
     */
    public function getComment(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = RentEvaluate::getDriverComment($uid);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * author 付鹏
     * createDay: 2019/4/27
     * createTime: 13:28
     * desc 我的评价列表
     * param int $uid 用户uid
     * param int $page 页码
     */
    public function getCommentList(){
        if(Request::instance()->isGet()){
            $uid = input('uid/d',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = RentEvaluate::field('id,u_avatar,u_name,star,date,img1,img2,img3,type')->where(['driver_id'=>$uid])->paginate(15);
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

}