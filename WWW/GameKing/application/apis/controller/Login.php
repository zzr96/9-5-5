<?php

namespace app\apis\controller;

use think\Controller;
use think\Cookie;
use think\Db;
use think\Exception;
use think\Request;

/**
 * swagger: 登录注册
 */
class Login extends Controller
{
    /**
     * post: 绑定手机号
     * path: bindUserMobile
     * method: bindUserMobile
     * param: phone - {string} 手机号
     * param: code - {int} 手机号
     */
    public function bindUserMobile()
    {
        $phone = input('phone');
        $code = input('code');
        $ud_id = input('ud_id');
        if (!$phone) {
            return json(['code' => 400, 'msg' => '缺少参数phone']);
        }
        if (!$code) {
            return json(['code' => 400, 'msg' => '缺少参数code']);
        }
        if (!$ud_id) {
            return json(['code' => 400, 'msg' => '缺少参数ud_id']);
        }
        $smsCheck = self::checkSmsCode($phone, $code);
        $smsCheck = $smsCheck->getData();
        if ($smsCheck['code'] != 200) {
            return json($smsCheck);
        }
        $re = Db::name('union_login')->where(['id' => $ud_id])->find();
        if(empty($re)){
            return json(['code'=>400,'msg'=>'网络异常，请稍后再试']);
        }
        $result = Db::name('member')->where(['phone' => $phone])->find();
        if ($result) {
            return json(['code' => 400, 'msg' => '手机号已被使用']);
        }
        Db::startTrans();
        try{
            $res = Db::name('member')->insertGetId(['phone'=>$phone,'nickname'=>$re['nickname'],'avatar'=>$re['headimg_url'],'invite_code'=>create_invite_code(),'createtime'=>date('Y-m-d H:i:s')]);
            Db::name('union_login')->where(['id'=>$ud_id])->update(['uid'=>$res]);
            Db::commit();
            $token = md5($res . md5($phone));
            cookie('token', $token, 3600 * 24 * 30 * 12);
            cookie('userId', $res, 3600 * 24 * 30 * 12);
            return json(['code'=>200,'msg'=>'绑定成功','uid'=>$res]);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>'网络异常，请稍后再试']);
        }
    }


    /**
     * post: 用户登录
     * path: userLogin
     * method: userLogin
     * param: phone - {string} 手机号
     * param: password - {string} 登录密码
     */
    public function userLogin()
    {
        $params = $this->request->param();
        if(!isset($params['phone'])){
            return json(['code'=>400,'msg'=>'请输入手机号']);
        }
        if(!isset($params['password'])){
            return json(['code'=>400,'msg'=>'请输入密码']);
        }
        //$data = Db::name('member')->field('uid,password,unread_num')->where(['phone' => $params['phone'],'password' => md5($params['password'])])->find();
        $data = Db::name('member')->field('password,alipay_pwd,balance',true)->where(['phone' => $params['phone'],'password' => md5($params['password'])])->find();
        if (empty($data)) {
            return json(['code' => 400, 'msg' => '账户不存在或密码错误']);
        }
        //更新绑定的UID
        $ud_id = session('ud_id');
        if ($ud_id) {
            $res = Db::name('union_login')->where(['id' => $ud_id])->update(['uid' => $data['uid']]);
            if($res === false){
                return json(['code' => 400, 'msg' => '网络异常，请稍后再试']);
            }
        }
        $token = md5($data['uid'] . md5($data['phone']));
        cookie('token', $token, 3600 * 24 * 30 * 12);
        cookie('userId', $data['uid'], 3600 * 24 * 30 * 12);
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $data, 'token' => $token]);
    }





    /**
     * post:联合登录
     * path:unionLogin
     * param:
     */
    public function unionLogin()
    {
        $type = input('type');
        $openid = input('openid');
        $headimgurl = input('headimgurl');
        $nickname = input('nickname');
        if(empty($type)){
            return json(['code'=>400,'msg'=>'缺少参数type']);
        }
        if(empty($openid)){
            return json(['code'=>400,'msg'=>'缺少参数openid']);
        }
        if(empty($headimgurl)){
            return json(['code'=>400,'msg'=>'缺少参数headimgurl']);
        }
        if(empty($nickname)){
            return json(['code'=>400,'msg'=>'缺少参数nickname']);
        }
        $hasBind = Db::name('union_login')->where(['type' => $type, 'openid' => $openid])->find();
        if (empty($hasBind)) {//写入第三方用户信息
            $unionData = [
                'type' => $type,
                'openid' => $openid,
                'headimg_url' => $headimgurl,
                'nickname' => $nickname
            ];
            $re = Db::name('union_login')->insertGetId($unionData);
            session('ud_id', $re);
            return json(['code' => 400, 'msg' => 'SUCCESS', 'ud_id' => $re]);
        } else {
            if ($hasBind['uid']) {//已绑定，直接登录
                $member = Db::name('member')->where(['uid' => $hasBind['uid']])->find();
                $token = md5($hasBind['uid'] . md5($member['phone']));
                cookie('token', $token, 3600 * 24 * 30 * 12);
                cookie('userId', $hasBind['uid'], 3600 * 24 * 30 * 12);
                return json(['code' => 200, 'msg' => 'SUCCESS', 'uid' => $hasBind['uid']]);
            } else {
                session('ud_id', $hasBind['id']);
                return json(['code' => 400, 'msg' => 'SUCCESS', 'ud_id' => $hasBind['id']]);
            }
        }

    }

    /**
     * get: 发送短信验证码
     * path: sendSmsCode
     * method: sendSmsCode
     * param: phone - {string} 手机号
     */
    public function sendSmsCode()
    {
        $phone = input('phone');
        $code = mt_rand(1000, 9999);
        if(empty($phone)){
            return json(['code'=>400,'msg'=>'请输入手机号']);
        }

//        $content = '您的验证码是：' . $code . '。请不要把验证码泄露给其他人。';
//        $url = "http://106.ihuyi.com/webservice/sms.php?method=Submit&account=" . config('sms.appid') . "&password=" . config('sms.appkey') . "&mobile=" . $phone . "&content=" . $content;
//        $result = file_get_contents($url);
//        simplexml_load_string($result);
        $content='您的注册验证码为：'.$code.'。验证码有效期为5分钟，请尽快填写！';
        $url="http://106.ihuyi.com/webservice/sms.php?method=Submit&account=cf_huke&password=wyx037798&mobile=".$phone."&content=".$content;
        $result = file_get_contents($url);
        $xml = simplexml_load_string($result);
        $tt=$xml->msg;
        $data['phone'] = $phone;
        $data['code'] = $code;
        $data['addtime'] = time();
        $re = Db::name('sms_code')->insertGetId($data);
        if ($re) {
            return ['code' => 200, 'msg' => '短信发送成功'];
        }
        return ['code' => 400, 'msg' => '发送失败'];
    }





    /**
     * get: 验证短信验证码
     * path: checkSmsCode
     * method: checkSmsCode
     * param: phone - {string} 手机号
     * param: code - {int} 验证码
     */
    public function checkSmsCode($phone, $code)
    {
        $smsRe = Db::name('sms_code')
            ->where(['phone' => $phone, 'code' => $code])
            ->find();
        if (empty($smsRe)) {
            return json(['code' => 400, 'msg' => '验证码错误']);
        }

        $now = time();
        if (($now - $smsRe['addtime']) >= 5 * 60) {
            return json(['code' => 400, 'msg' => '验证码已过期']);
        }
        return json(['code' => 200, 'msg' => '验证码正确']);
    }


    /**
     * post: 用户注册
     * path: userRegister
     * method: userRegister
     * param: password - {string} 密码
     * param: phone - {string} 手机号
     * param: code - {string} 短信验证码
     * param: invite_code - {string} 邀请码
     */
    public function userRegister()
    {
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求方式不正确']);
        }
        $phone = input('phone');
        $code = input('code');
        $invite_code = input('invite_code');
        $password = input('password');
        if(!isset($phone) || !$this->isMobile($phone)){
            return json(['code'=>400,'msg'=>'请填写有效的手机号']);
        }
        if(!isset($code)){
            return json(['code'=>400,'msg'=>'请填写短信验证码']);
        }
        if(!isset($password)){
            return json(['code'=>400,'msg'=>'请设置密码']);
        }
        $smsCheck = self::checkSmsCode($phone, $code);
        $smsCheck = $smsCheck->getData();
        if ($smsCheck['code'] != 200) {
            return json($smsCheck);
        }
        $data = Db::name('member')->where(['phone' => $phone])->find();
        if ($data) {
            return json(['code' => 400, 'msg' => '该手机号已注册']);
        }
        $r = 0;
        if(!empty($invite_code)){
            $r = Db::name('member')->where(['invite_code'=>$invite_code])->value('uid');
            if(!$r){
               return json(['code'=>400,'msg'=>'填写的邀请码不存在']);
            }
        }
        $time = time();
        $invite = create_invite_code();
        $member = [
            'phone' => $phone,
            'password' => md5($password),
            'nickname' => $phone,
            'invite_code' => $invite,
            'qr_code' => create_qrcode($invite),
            'avatar' => '/uploads/avatar/' . mt_rand(1, 10) . '.png',
            'createtime' => date('Y-m-d H:i:s',$time),
        ];
        Db::startTrans();
        try {
            $uid = Db::name('member')->insertGetId($member);
            Db::name('user_setting')->insert(['uid' => $uid]);
            if($r){
                Db::name('spread')->insert(['uid'=>$uid,'other_id'=>$r,'invite_code'=>$invite_code,'addtime'=>$time]);
            }
            $token = md5($uid . md5($member['phone']));
            //更新绑定的UID
            $ud_id = session('ud_id');
            if ($ud_id) {
                Db::name('union_login')->where(['id' => $ud_id])->update(['uid' => $uid]);
            }
            cookie('token', $token, 3600 * 24 * 30 * 12);
            cookie('userId', $uid, 3600 * 24 * 30 * 12);
            Db::commit();
            return json(['code' => 200, 'msg' => '注册成功', 'data' => ['uid' => $uid]]);
        } catch (\Exception $e) {
            Db::rollback();
            $msg = $e->getMessage();
            return json(['code' => 400, 'msg' => $msg]);
        }
    }


    //手机号的验证规则
    public function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * post: 用户退出
     * path: logout
     * method: logout
     */
    public function logout()
    {
        Cookie::delete('userId');
        Cookie::delete('token');
        return json(['code' => 200, 'msg' => 'SUCCESS']);
    }

    /**
     * post: 重置密码-个人
     * path: resetPasswordPer
     * method: resetPasswordPer
     * param: phone - {int} 手机号
     * param: code - {int} 验证码
     * param: password - {string} 密码
     */
    public function resetPasswordPer()
    {
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求方式不正确']);
        }
        $params = Request::instance()->param();
        if(!isset($params['phone'])){
            return json(['code'=>400,'msg'=>'请输入手机号']);
        }
        if(!isset($params['code'])){
            return json(['code'=>400,'msg'=>'请输入短信验证码']);
        }
        if(!isset($params['password'])){
            return json(['code'=>400,'msg'=>'请输入密码']);
        }
        $smsCheck = self::checkSmsCode($params['phone'], $params['code']);
        $smsCheck = $smsCheck->getData();

        if ($smsCheck['code'] != 200) {
            return json($smsCheck);
        }

        $re = Db::name('member')->where(['phone' => $params['phone']])->update(['password' => md5($params['password'])]);
        if($re === false){
            return json(['code' => 400, 'msg' => '密码修改失败']);
        }
        $uid = Db::name('member')->where(['phone' => $params['phone']])->value('uid');
        $token = md5($uid . md5($params['phone']));
        cookie('token', $token, 3600 * 24 * 30 * 12);
        cookie('userId', $uid, 3600 * 24 * 30 * 12);
        return json(['code' => 200, 'msg' => '密码修改成功', 'data' => ['uid' => $uid]]);
    }


    /**
     * post: 获取省市区
     * path: getCity
     * method: getCity
     * param: name - {string} 省市区
     */
    public function getCity()
    {
        if(Request::instance()->isPost()){
            $name = input('name');
            $code = $this->getCityCode($name);
            $c = Db::name('area')->where(['parentid'=>$code])->select();
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$c]);
        }
        $p = Db::name('area')->where(['level'=>1])->select();
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$p]);
    }


    /**
     * get: 获取城市code
     * path: getCityCode
     * method: getCityCode
     * param: cityName - {string} 城市名称
     */
    public function getCityCode($name)
    {
        $code = Db::name('area')->where('areaname', $name)->value('code');
        return $code;
    }
    /**
     * get: 获取邮箱验证码
     * path: sendEmailCode
     * method: sendEmailCode
     * param: email - {string} 邮箱
     */
    public function sendEmailCode(){
        $email = input('email');
        if(empty($email) || !is_email($email)){
            return json(['code'=>400,'请填写有效邮箱地址']);
        }
        $subject='游戏大王邮箱验证码';
        $code = mt_rand(100000,999999);
        $body = '您的验证码是：<h2>' . $code . '</h2>';
        $toemail = $email;
        $name="游戏大王APP";
        $r=send_mail($toemail,$name,$subject,$body,$attachment = null);
        $data['email'] = $email;
        $data['code'] = $code;
        $data['addtime'] = time();
        //记录邮件验证码
        Db::name('email_code')->insert($data);
        if($r){
            return json(['code'=>200,'msg'=>'发送成功']);
        }else{
            return json(['code'=>400,'msg'=>'发送失败']);
        }
    }


    /**
     * post: 验证邮箱验证码
     * path: checkEmailCode
     * method: checkEmailCode
     * param: email - {string} 邮箱
     * param: code - {int} 验证码
     */
    public function checkEmailCode($email,$code){
        $email = Db::name('email_code')->where(['email'=>$email,'code'=>$code,'status'=>0])->order('id desc')->find();
        if(empty($email)){
            return ['code'=>400,'msg'=>'验证码错误'];
        }
        if($email['addtime'] + 60 * 15 <= time()){
            return ['code'=>400,'msg'=>'验证码已过期'];
        }
        if(Db::name('email_code')->where(['id'=>$email['id']])->update(['status'=>1])){
            return ['code'=>200,'msg'=>''];
        }
        return ['code'=>400,'msg'=>'验证码错误'];
    }
    //测试
    public function ceshi(){
        echo input('invite_code');
    }
}
