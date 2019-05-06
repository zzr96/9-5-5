<?php

namespace app\apis\controller;

use think\config\driver\Json;
use think\Db;
use think\Controller;
use think\Exception;
use think\Request;
use think\Validate;
use think\Image;

/**
 * swagger: 用户相关接口
 */
class Member extends Controller
{

    /**
     * post: 上传图片
     * path: upload
     * method: upload
     * param: image - {file} 图片文件
     * param: avatar - {int} 是否更新头像
     */
    public function upload()
    {
        $avatar = input('avatar');
        $uid = input('uid');
        $file = $this->request->file('image');
        $info = $file->move(ROOT_PATH . 'public_html' . DS . 'uploads' . DS . 'image');

        if ($info) {
            $url = 'uploads' . DS . 'image' . DS . $info->getSaveName();
            $image = \think\Image::open($url);

            if ($avatar) {
                if(empty($uid)){
                    return json(['code' => 400, 'msg' => '缺少用户uid']);
                }
                $image->thumb(100, 100)->save($url);
                Db::name('member')->where('uid', $uid)->setField('avatar', $url);
            } else {
                $image->thumb(1024, 1024)->save($url);
            }
            return json(['code' => 200, 'msg' => '上传成功', 'url' => $url]);
        } else {
            //上传失败获取错误信息
            return json(['code' => 400, 'msg' => $file->getError()]);
        }
    }


    /**
     * get/post: 获取/修改用户个人资料
     * path: profile
     * method: profile
     * param: avatar - {string} 头像[选填]
     * param: nickname - {string} 昵称[选填]
     * param: sex - {int} 性别 1男2女
     */
   /* public function profile()
    {
        if (Request::instance()->isPost()) {
            $params = Request::instance()->param();

            //获取头像地址
            if (isset($params['avatar'])) {
                $update = ['avatar' => $params['avatar']];
            }
            //验证昵称
            if (isset($params['nickname'])) {
                $len = mb_strlen($params['nickname'], 'utf8');
                if ($len < 3 || $len > 8) {
                    return json(['code' => 400, 'msg' => '昵称长度只能是3-8位']);
                }
                $data = Db::name('member')->where('nickname', $params['nickname'])->find();
                if (!empty($data)) {
                    return json(['code' => 400, 'msg' => '该昵称已存在']);
                }
                $update['nickname'] = $params['nickname'];
            }
            //验证性别
            if (isset($params['sex'])) {
                if (!in_array($params['sex'], [1, 2])) {
                    return json(['code' => 400, 'msg' => '性别错误']);
                }
                $update = ['sex' => $params['sex']];
            }
            $update['updatetime'] = date('Y-m-d H:i:s');
            $re = Db::name('member')->where('uid', $this->uid)->update($update);
            if($re === false){
                return json(['code' => 400, 'msg' => '资料修改失败']);
            }
            return json(['code' => 200, 'msg' => '资料修改成功']);
        }
        if (Request::instance()->isGet()) {
            $profile = Db::name('member')->field('uid,avatar,nickname,phone,sex,email')->where('uid', $this->uid)->find();
            return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $profile]);
        }


    }*/
    public function get_profile(){
        Auth::checkAuth();
        $uid=input('uid');
        if(empty($uid)){
             return json(['code' => 400, 'msg' => '缺少用户id']);
        }
        $arr=db('member')->field('uid,avatar,nickname,sex,email,phone')->where('uid',$uid)->find();
        if($arr){
            return json(['code' => 200, 'msg' => '获取成功', 'data' => $arr]);
        }else{
            return json(['code' => 400, 'msg' => '获取失败']);
        }
    }
    public function post_profile(){
        Auth::checkAuth();
        $uid=input('uid');
        $avatar=input('avatar');
        $nickname=input('nickname');
        $sex=input('sex');
        if(empty($uid)){
            return json(['code' => 400, 'msg' => '缺少用户id']);
        }
        $data=[];
        if(!empty($avatar)){
            $data['avatar']=$avatar;
        }
        if(!empty($nickname)){
            $data['nickname']=$nickname;
        }
        if(!empty($sex)){
            $data['sex']=$sex;
        }
        $re = Db::name('member')->where('uid', $uid)->update($data);
        if($re){
            return json(['code' => 200, 'msg' => '修改成功']);
        }else{
            return json(['code' => 400, 'msg' => '修改失败']);
        }
    }
    /**
     * post: 修改密码
     * path: changePassword
     * method: changePassword
     * param: phone - {string} 手机号
     * param: code - {string} 短信验证码
     * param: password - {string} 新密码
     */
    public function changePassword()
    {
            Auth::checkAuth();
        if (Request::instance()->isPost()) {
            $uid = input('uid');
            if(empty($uid)){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $user = Db::name('member')->where(['uid'=>$uid])->find();
            if(empty($user)){
                return json(['code'=>400,'用户不存在']);
            }
            $params = Request::instance()->param();
            if(!isset($params['phone'])){
                return json(['code'=>400,'msg'=>'请填写手机号']);
            }
            if(!isset($params['code'])){
                return json(['code'=>400,'msg'=>'请填写短信验证码']);
            }
            if(!isset($params['password'])){
                return json(['code'=>400,'msg'=>'请填输入密码']);
            }
            $login = new Login();
            $re = $login->checkSmsCode($params['phone'], $params['code']);
            $re = $re->getData();
            if ($re['code'] != 200) {
                return json($re);
            }
            $res = Db::name('member')->where('uid', $this->uid)->update(['password' => md5($params['password'])]);
            if($res === false){
                return json(['code' => 400, 'msg' => '密码修改失败']);
            }

            return json(['code' => 200, 'msg' => '密码修改成功']);
        }
    }

    /**
     * post: 修改手机号
     * path: changeMobile
     * method: changeMobile
     * param: newPhone - {int} 新手机号
     * param: code - {int} 短信验证码
     */
    public function changeMobile()
    {
        Auth::checkAuth();
        $newPhone = input('newPhone');
        $code = input('code');
        $uid = input('uid');
        if(!$uid){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        if(!$newPhone){
            return json(['code'=>400,'msg'=>'请输入手机号']);
        }
        if(!$code){
            return json(['code'=>400,'msg'=>'请输入短信验证码']);
        }
        $login = new Login();
        $re = $login->checkSmsCode($newPhone, $code);
        $re = $re->getData();
        if ($re['code'] != 200) {
            return json($re);
        }
        $data = Db::name('member')->where('phone', $newPhone)->find();
        if (!empty($data)) {
            return json(['code' => 400, 'msg' => '手机号已存在']);
        }
        $re = Db::name('member')->where('uid', $uid)->update(['phone' => $newPhone]);
        if ($re) {
            $token = md5($uid . md5($newPhone));
            cookie('token', $token,3600 * 24 * 30 * 12);
            return json(['code' => 200, 'msg' => '手机号修改成功','newPhone'=>$newPhone]);
        }
        return json(['code' => 400, 'msg' => '手机号修改失败']);
    }


    /**
     * post: 验证邮箱验证码
     * path: checkEmailCode
     * method: checkEmailCode
     * param: email - {string} 邮箱
     * param: code - {int} 验证码
     */
    public function bindEmail(){
        $uid = input('uid');
        $email = input('email');
        $code = input('code');
        if(!$uid){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        if(!$email){
            return json(['code'=>400,'msg'=>'请输入邮箱']);
        }
        if(!$code){
            return json(['code'=>400,'msg'=>'请输入邮箱验证码']);
        }
        $login = new Login();
        $res = $login->checkEmailCode($email,$code);
        if($res['code'] == 200){
            if(Db::name('member')->where(['uid'=>$uid])->update(['email'=>$email])){
                return json(['code'=>200,'msg'=>'绑定成功']);
            }
            return json(['code'=>400,'msg'=>'绑定失败']);
        }
        return json($res);
    }





    /**
     * post: 更新信鸽
     * path: xingeBind
     * method: xingeBind
     * param: token - {string} token
     */
    public function xingeBind()
    {
        $token = input('token');
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        if(!$token){
            return json(['code'=>400,'msg'=>'请输入token']);
        }
        Db::name('member')->where('uid', $uid)->setField('xinge_token', $token);
        return json(['code' => 200, 'msg' => 'SUCCESS']);
    }


    /**
     * get:收藏帖子
     * path: collect
     * method: collect
     * table:collect，praise
     * param: itemid - {int} 贴子ID
     */
    public function collect()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $itemid = input('itemid', 0);
        if (!$itemid) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        $square = Db::name('square')->where(['id' => $itemid])->find();
        if(!$square){
            return json(['code' => 400, 'msg' => '帖子不存在']);
        }
        $has = Db::name('collect')->where(['itemid' => $itemid, 'uid' => $uid])->find();
        if ($has) {
            return json(['code' => 400, 'msg' => '不能重复收藏']);
        }
        // 启动事务
        Db::startTrans();
        try{
            Db::name('collect')->insertGetId(['itemid' => $itemid, 'uid' => $uid, 'createtime' => date('Y-m-d:H:i:s')]);
            Db::name('square')->where('id', $itemid)->setInc('collects', 1);
            // 提交事务
            Db::commit();
            return json(['code' => 200, 'msg' => '收藏成功']);
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(['code' => 400, 'msg' =>$e->getMessage()]);
        }
    }



    /**
     * get: 取消收藏帖子
     * path: unCollect
     * method: unCollect
     * table: collect、square
     * param: itemid - {int} 帖子ID
     */
    public function unCollect()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $itemid = input('itemid', 0);
        if (!$itemid) {
            return json(['code' => 400, 'msg' => '帖子ID不存在']);
        }
        // 启动事务
        Db::startTrans();
        try{
            Db::name('collect')->where(['itemid' => $itemid,'uid' => $uid])->delete();
            Db::name('square')->where('id', $itemid)->setDec('collects', 1);
            Db::commit();
            return json(['code' => 200, 'msg' => '取消成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code' => 400, 'msg' =>$e->getMessage()]);
        }
    }


    /**
     * get: 关注用户
     * path: favorite
     * method: favorite
     * table:favorite
     * param: itemid - {int} 用户ID
     */
    public function favorite()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $itemid = input('itemid', 0);
        if (!$itemid) {
            return json(['code' => 400, 'msg' => '缺少需要关注用户的必要参数']);
        }
        $mem = Db::name('member')->where(['uid' => $itemid])->find();
        if(!$mem){
            return json(['code' => 400, 'msg' => '被关注的用户不存在']);
        }
        $has = Db::name('favorite')->where(['itemid' => $itemid, 'uid' => $uid])->find();
        if ($has) {
            return json(['code' => 400, 'msg' => '你已关注该用户']);
        }
        $re = Db::name('favorite')->insertGetId(['itemid' => $itemid,'uid' => $uid, 'createtime' => date('Y-m-d H:i:s')]);
        if ($re) {
            return json(['code' => 200, 'msg' => '关注成功']);
        }
    }

    /**
     * get: 取消关注用户
     * path: unFavorite
     * method: unFavorite
     * table:favorite
     * param: itemid - {int} 用户ID
     */
    public function unFavorite()
    {
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少参数uid']);
        }
        $itemid = input('itemid', 0);
        if (!$itemid) {
            return json(['code' => 400, 'msg' => '缺少用户id']);
        }
        $re = Db::name('favorite')->where(['itemid' => $itemid,'uid' => $uid])->delete();
        if ($re) {
            return json(['code' => 200, 'msg' => '取消成功']);
        }
        return json(['code' => 400, 'msg' => '取消失败']);
    }

    /**
     * post: 用户绑定支付宝账号
     * path: bindAlipay
     * method: bindAlipay
     * table:member
     * param: alipay_name - {string} 姓名
     * param: alipay_account - {string} 支付宝账户
     */
    function bindAlipay(){
        Auth::checkAuth();
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $name=input('alipay_name');
        if(empty($name)){
            return json(['code'=>400,'msg'=>'请填写姓名']);
        }
        $alipay=input('alipay_account');
        if(empty($alipay)){
            return json(['code'=>400,'msg'=>'请填写支付宝账户']);
        }
        $data=[
            'alipay_name'=>$name,
            'alipay_account'=>$alipay
        ];
        $res=Db::name('member')->where(['uid'=>$uid])->update($data);
        if($res){
            return json(['code'=>200,'msg'=>'绑定成功']);
        }else{
            return json(['code'=>400,'msg'=>'绑定失败']);
        }
    }

    /**
     * post: 用户修改支付宝账号
     * path: updateAlipay
     * method: updateAlipay
     * table:member
     * param: alipay_name - {string} 姓名
     * param: alipay_account - {string} 支付宝账户
     */
    function updateAlipay(){
        Auth::checkAuth();
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $name=input('alipay_name');
        if(empty($name)){
            return json(['code'=>400,'msg'=>'请填写姓名']);
        }
        $alipay=input('alipay_account');
        if(empty($alipay)){
            return json(['code'=>400,'msg'=>'请填写支付宝账户']);
        }
        $data=[
            'alipay_name'=>$name,
            'alipay_account'=>$alipay
        ];
        $res=Db::name('member')->where(['uid'=>$uid])->update($data);
        if($res){
            return json(['code'=>200,'msg'=>'修改成功']);
        }else{
            return json(['code'=>400,'msg'=>'修改失败']);
        }
    }

    /**
     * post: 设置支付密码
     * path: setAlipayPwd
     * method: setAlipayPwd
     * param: mobile - {string} 手机号
     * param: code - {int} 短信验证码
     * param: password - {int} 支付密码
     * param: re_password - {int} 支付密码
     */
    public function setAlipayPwd()
    {
        Auth::checkAuth();
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $mobile = input('mobile');
        $code = input('code');
        $password = input('password');
        $re_password = input('re_password');
        $login = new Login();
        $re = $login->checkSmsCode($mobile, $code);
        $re = $re->getData();
        if ($re['code'] != 200) {
            return json($re);
        }
        if($password != $re_password){
            return json(['code'=>400,'msg'=>'支付密码不一致']);
        }
        if (preg_match("/\d{6}/i", $password)) {
            $re = Db::name('member')->where(['uid'=>$uid])->update(['alipay_pwd' => sha1(md5($password))]);
            if($re !== false){
                return json(['code' => 200, 'msg' => '支付密码设置成功']);
            }
            return json(['code' => 400, 'msg' => '设置失败']);
        }
        return json(['code' => 400, 'msg' => '支付密码必须是6位数字']);
    }

    /**
     * get: 验证用户是否设置支付密码
     * path: checkUserIssetPwd
     * method: checkUserIssetPwd
     */
    function checkUserIssetPwd(){
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $res=Db::name('member')->where(['uid'=>$uid])->value('alipay_pwd');
        if($res){
            return json(['code'=>200,'msg'=>'已设置']);
        }
        return json(['code'=>400,'msg'=>'尚未设置']);
    }

    /**
     * post: 用户验证支付密码
     * path: checkAlipayPwd
     * method: checkAlipayPwd
     * table:member
     * param: password - {string} 支付密码
     */
    function checkAlipayPwd(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $alipay_password=input('password');
        if(empty($alipay_password)){
            return json(['code'=>400,'msg'=>'请输入支付密码']);
        }
        $pwd=Db::name('member')->where(['uid'=>$uid])->value('alipay_pwd');
        if(sha1(md5($alipay_password)) == $pwd){
            return json(['code'=>200,'msg'=>'支付密码验证成功']);
        }
        return json(['code'=>400,'msg'=>'支付密码错误']);
    }

    /**
     * get: 获取用户余额
     * path: getUserBalance
     * method: getUserBalance
     * table:member
     */
    function getUserBalance(){
        Auth::checkAuth();
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $balance = Db::name('member')->where(['uid'=>$uid])->value('balance');
        return json(['code'=>200,'msg'=>'SUCCESS','balance'=>($balance/100)]);
    }

    /**
     * get: 获取用户支付宝账号
     * path: getUserAlipay
     * method: getUserAlipay
     * table:member
     */
    function getUserAlipay(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $user=Db::name('member')->where(['uid'=>$uid])->field('alipay_name,alipay_account')->find();
        if($user){
            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$user]);
        }
        return json(['code'=>400,'msg'=>'用户暂未绑定支付宝']);
    }


    /**
     * post: 用户提现
     * path: userDeposit
     * method: userDeposit
     * table:member
     * param: money - {int} 申请提现金额
     */
    function userDeposit(){
        Auth::checkAuth();
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求方式不正确']);
        }
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $data = [];
        $data['uid'] = $uid;
        $user = Db::name('member')->where(['uid'=>$uid])->field('alipay_name,alipay_account,balance')->find();
        if(empty($user['alipay_account']) || empty($user['alipay_name'])){
            return json(['code'=>400,'msg'=>'请完善支付宝信息']);
        }
        $data['alipay_account']=$user['alipay_account'];
        $money = input('money');
        if(empty($money)){
            return json(['code'=>400,'msg'=>'缺少参数money']);
        }
        if($user['balance'] < ($money * 100)){
            return json(['code'=>400,'msg'=>'余额不足']);
        }
        $data['money'] = ($money * 100);
        $data['dateline']=date('Y-m-d H:i:s',time());
        $order_no = createOrderNo();
        $data['order_no'] = $order_no;
        Db::startTrans();
        try{
            Db::name('user_deposit')->insert($data);
//            Db::name('balance_log')->insert(['uid'=>$uid,'amount'=>'-' . $money,'mark'=>'提现到支付宝','type'=>2,'order_no'=>$order_no,'createtime'=>date('Y-m-d H:i:s')]);
            Db::name('member')->where(['uid'=>$uid])->setDec('balance',($money * 100));
            Db::commit();
            return json(['code'=>200,'msg'=>'提现申请成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }


    /**
     * get: 用户近30天 余额明细
     * path: getUserbalanceLog
     * method: getUserbalanceLog
     * table:member
     * param p - {int} 页码
     * param limit - {每页条数}
     */
    function getUserbalanceLog(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $p = input('p') ? input('p') : 1;
        $limit = input('limit') ? input('limit') : 15;
        $where['uid'] = $uid;
        $where['type'] = ['between',[1,4]];
        $now  = time();
        $start = $now - 60 * 60 * 24 * 30;
        $where['createtime'] = ['between',[date('Y-m-d H:i:s',$start),date('Y-m-d H:i:s',$now)]];
        $count=Db::name('balance_log')->where($where)->field('amount,mark,createtime')->count();
        $count_page = ceil($count/$limit);
        $user=Db::name('balance_log')->where($where)->field('amount,mark,createtime')->page($p,$limit)->select();
        foreach ($user as &$v){
            if($v['mark']=="购物消费"){
                $v['amount'] = "-".($v['amount']/100);
            }else{
                 $v['amount'] = "+".($v['amount']/100);
            }
        }
        if($user){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$user,'page'=>$count_page]);
        }else{
            return json(['code'=>400,'msg'=>'暂无记录']);
        }
    }

    /**
     * get: 用户零钱明细
     * path: getUserAllbalanceLog
     * method: getUserAllbalanceLog
     * table:member
     * param p - {int} 页码
     * param limit - {每页条数}
     */
    function getUserAllbalanceLog(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $p = input('p') ? input('p') : 1;
        $limit = input('limit') ? input('limit') : 15;
        $where['uid'] = $uid;
        $where['type'] = ['between',[1,4]];
        $count=db('balance_log')->where($where)->field('amount,mark,createtime')->count();
        $count_page = ceil($count/$limit);
        $user=db('balance_log')->where($where)->field('amount,mark,createtime')->page($p,$limit)->select();
        foreach ($user as $k=>&$v){
            if($v['mark']=="购物消费"){
                $v['amount'] = "-".($v['amount']/100);
            }else{
                 $v['amount'] = "+".($v['amount']/100);
            }
        }
        if($user){
            return json(['code'=>200,'msg'=>'获取成功','data'=>$user,'page'=>$count_page]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据','data'=>[]]);
        }
    }


    /**
     * post: 申请售后
     * path: customerService
     * method: customerService
     * table:order
     * param order_goods_id - {sting} 商品订单号
     * param type - {类型} //1.换货2.退款3.退货退款
     * param content - {string} 原因
     * param img - {string} 图片
     */
    function customerService(){
        if(!Request::instance()->isPost()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $order_id = input('order_goods_id');
        if(empty($order_id)){
            return json(['code'=>400,'msg'=>'缺少商品订单号']);
        }
        $type=input('type');	//类型1退货2退款
        if(empty($type)){
            return json(['code'=>400,'msg'=>'缺少申请类型']);
        }
        $reason=input('reason');	//类型1退货2退款
        if(empty($reason)){
            return json(['code'=>400,'msg'=>'缺少退款原因']);
        }
        $cargo_status=input('cargo_status');	//类型1退货2退款
        if(empty($cargo_status)){
            return json(['code'=>400,'msg'=>'缺少货物状态']);
        }
        $refund_amount = input('refund_amount');
        if(empty($refund_amount)){
            return json(['code'=>400,'msg'=>'缺少退款金额']);
        }
        $content=input('content');	//类型1退货2退款
        if(empty($content)){
            return json(['code'=>400,'msg'=>'缺少售后原因']);
        }
        $img=input('img');	//类型1退货2退款
        if(empty($img)){
            return json(['code'=>400,'msg'=>'请上传图片']);
        }
        $order_goods = Db::name('order_goods')->where(['id'=>$order_id])->find();
        if(empty($order_goods)){
            return json(['code'=>400,'msg'=>'未查询到该商品订单信息']);
        }
        $order = Db::name('order')->where(['id'=>$order_goods['order_id']])->find();
        if(empty($order)){
            return json(['code'=>400,'msg'=>'未查询到该订单信息']);
        }
        if($order['status'] <= 1){
            return json(['code'=>400,'msg'=>'订单状态异常']);
        }
        if($type == 2){
            if(!empty($order['updatetime'])){
                if(time() > ($order['updatetime'] + 60 * 60 * 24 * 15)){
                    return json(['code'=>400,'msg'=>'该订单已超出退款时间']);
                }
            }
        }
        $img=explode(',',$img);
        $data=[];
        $data['uid'] = $uid;
        $data['order_id'] = $order_id;
        $data['type'] = $type;
        $data['content'] = $content;
        $data['reason'] = $reason;
        $data['cargo_status'] = $cargo_status;
        $data['refund_amount'] = $refund_amount;
        $data['dateline'] = time();
        $data['order_status'] = $order['status'];
        $status = 6;
        Db::startTrans();
        try{
            $res = Db::name('order_back')->insertGetId($data);
            Db::name('oback_img')->insert(['back_id'=>$res,'img'=>json_encode($img)]);
            Db::name('order')->where(['id'=>$order['id']])->update(['status'=>$status]);
            Db::commit();
            return json(['code'=>200,'msg'=>'申请成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }
    /**
     * post: 退货快递信息填写
     * path: ExpressInformation
     * method: ExpressInformation
     * table:express
     * param back_id - {sting} 退货id
     * param express_name - {string} 快递名
     * param express_num - {string} 快递单号
     */
    function ExpressInformation(){
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $params = Request::instance()->param();
        if(!isset($params['back_id'])){
            return json(['code'=>400,'msg'=>'缺少退货单ID']);
        }
        if(!isset($params['express_name'])){
            return json(['code'=>400,'msg'=>'请填写快递名称']);
        }
        if(!isset($params['express_num'])){
            return json(['code'=>400,'msg'=>'请填写快递单号']);
        }
        $params['uid'] = $uid;
        $params['addtime'] = time();
        $res = Db::name('express')->insert($params);
        if($res){
            return json(['code'=>200,'msg'=>'提交成功']);
        }
        return json(['code'=>400,'msg'=>'提交失败']);
    }



    /**
     * post: 撤销申请
     * path: rveokeSale
     * method: rveokeSale
     * table:order
     * param id - {sting} 申请id
     */
    function rveokeSale(){
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $id = input('id',0);
        $back = Db::name('order_back')->where(['id'=>$id,'uid'=>$uid])->find();
        if(empty($back)){
            return json(['code'=>400,'msg'=>'订单不存在']);
        }
        $order_id = Db::name('order_goods')->where(['id'=>$back['order_id']])->value('order_id');
        Db::startTrans();
        try{
            Db::name('order_back')->where(['id'=>$id,'uid'=>$uid])->delete();
            Db::name('oback_img')->where(['back_id'=>$id])->delete();
            Db::name('order')->where(['id'=>$order_id])->update(['status'=>$back['order_status']]);
            Db::commit();
            return json(['code'=>200,'msg'=>'撤销成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }

    /**
     * get: 我的售后订单列表
     * path: myCustomerOrder
     * method: myCustomerOrder
     * table:order_back
     * param type - {int}状态1待同意2待确认3待退款4用户拒绝退款5商家拒绝退款6申请客服介入7客服已介入8客服处理完成9退款已完成
     *
     */
    function myCustomerOrder(){
//        $uid = $this->uid;
        if(!Request::instance()->isGet()){
            return json(['code'=>400,'msg'=>'请求类型错误']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $params = Request::instance()->param();
        $params['pageSize'] = isset($params['pageSize']) ? $params['pageSize'] : 10;
        $where = ['uid'=>$uid];
        $status = input('type');
        switch ($status) {
            case '1':
                $where['status'] = 1;
                break;
            case '2':
                $where['status'] = 2;
                break;
            case '3':
                $where['status'] = 3;
                break;
            case '4':
                $where['status'] = ['in',[4,5,6,7,8,9]];
                break;
        }
        $lists = Db::name('order_back')->field('id,order_id,order_status,status')->where($where)->paginate($params['pageSize'])->each(function ($item, $key)use($uid){
            $item['order_goods_info'] = Db::name('order_goods')->field('order_id,goods_size_id,goods_img,goods_name,goods_num,color,spec,price')->where(['id'=>$item['order_id']])->find();
            $item['order_goods_info']['shop_name'] = Db::name('order')->where(['id'=>$item['order_goods_info']['order_id']])->value('shop_name');
            $item['order_goods_info']['freight'] = Db::name('order')->where(['id'=>$item['order_goods_info']['order_id']])->value('freight');
            $item['order_goods_info']['goods_id'] = Db::name('goods_size')->where(['id'=>$item['order_goods_info']['goods_size_id']])->value('good_id');
            return $item;
        });
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$lists]);
    }

    /**
     * get: 我的售后订单详情
     * path: myCustomerOrderDetails
     * method: myCustomerOrder
     * table:order_back
     * param id - {int} 售后单id
     *
     */
    function myCustomerOrderDetails(){
//        $uid = $this->uid;
        $id = input('id');
        if(!$id){
            return json(['code'=>400,'msg'=>'缺少ID']);
        }
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $lists = [];
        $order_back = Db::name('order_back')->where(['id'=>$id])->find();
        if(empty($order_back)){
            return json(['code'=>400,'msg'=>'售后订单不存在']);
        }
        $order_back['dateline'] = date('Y-m-d H:i:s',$order_back['dateline']);
        $order_back['updatetime'] = date('Y-m-d H:i:s',$order_back['updatetime']);
        $order_back['refund_time'] = date('Y-m-d H:i:s',$order_back['refund_time']);
        $order_goods = Db::name('order_goods')->where(['id'=>$order_back['order_id']])->find();
        $order = [];
        if($order_goods){
            $order = Db::name('order')->where(['id'=>$order_goods['order_id']])->find();
        }
        if($order){
            $order['dateline'] = date('Y-m-d H:i:s',$order['dateline']);
        }
        $back_img = Db::name('oback_img')->where(['back_id'=>$order_back['id']])->find();
        if($back_img){
            $back_img['img'] = json_decode($back_img['img'],true);
        }
        $customer = Db::name('customer')->where(['back_id'=>$order_back['id']])->select();
        $lists['order_back_info'] = $order_back;
        $lists['order_goods_info'] = $order_goods;
        $lists['order_info'] = $order;
        $lists['back_img_info'] = $back_img;
        $lists['customer_info'] = $customer;
        foreach ($lists['customer_info'] as $kk=>&$vv){
            $vv['userinfo'] = Db::name('member')->field('avatar,nickname')->where(['uid'=>$vv['uid']])->find();
        }
        return json(['code'=>200,'msg'=>'SUCCESS','data'=>$lists]);
    }




    /**
     * get: 用户同意退款
     * path: userAgreeRefund
     * method: userAgreeRefund
     * table:order_back
     * param: back_id - {int} 退款号id
     * param: status - {int} 状态1待同意2待确认3待退款4用户拒绝退款5商家拒绝退款6申请客服介入7客服已介入8客服处理完成9退款已完成
     *
     */
    function userAgreeRefund(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $back_id = input('back_id');
        $back = Db::name('order_back')->where(['id'=>$back_id,'uid'=>$uid])->find();
        if(empty($back)){
            return json(['code'=>400,'msg'=>'未查询到退货单']);
        }
        $data['status'] = 3;
        $res = Db::name('order_back')->where(['id'=>$back_id,'uid'=>$uid])->update($data);
        if($res){
            return json(['code'=>200,'msg'=>'操作成功']);
        }
        return json(['code'=>400,'msg'=>'操作失败']);
    }


    /**
     * get: 用户拒绝退款
     * path: userRefuseRefund
     * method: userRefuseRefund
     * table:order_back
     * param: back_id - {int} 退款号id
     * param: status - {int} 状态1待同意2待确认3待退款4用户拒绝退款5商家拒绝退款6申请客服介入7客服已介入8客服处理完成9退款已完成
     *
     */
    function userRefuseRefund(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $back_id = input('back_id');
        $back = Db::name('order_back')->where(['id'=>$back_id,'uid'=>$uid])->find();
        if(empty($back)){
            return json(['code'=>400,'msg'=>'未查询到退货单']);
        }
        $data['status'] = 4;
        $res = Db::name('order_back')->where(['id'=>$back_id,'uid'=>$uid])->update($data);
        if($res){
            return json(['code'=>200,'msg'=>'操作成功']);
        }
        return json(['code'=>400,'msg'=>'操作失败']);
    }


    /**
     * post: 客服介入提交资料
     * path: CustomerIntervention
     * method: CustomerIntervention
     * table:customer
     * param back_id - {int} 售后id
     * param desc - {string} 描述
     * param img - {sting} 图片
     */
    function CustomerIntervention(){
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $params = Request::instance()->param();
        $validate = new Validate([
            ['back_id', 'require', '缺少售后订单ID'],
            ['desc', 'require', '请输入具体内容'],
            ['img', 'require', '请添加图片凭证'],
        ]);
        if (!$validate->check($params)) {
            return json(['code' => 400, 'msg' => $validate->getError()]);
        }
        $res = Db::name('order_back')->where(['id'=>$params['back_id'],'uid'=>$uid])->find();
        if(empty($res)){
            return json(['code'=>400,'msg'=>'未查询到该订单']);
        }
        //状态1待同意2待确认3待退款4用户拒绝退款5商家拒绝退款6申请客服介入7客服已介入8客服处理完成9退款已完成
        $data['status'] = 6;
        Db::startTrans();
        try{
            Db::name('customer')->insert(['back_id'=>$params['back_id'],'desc'=>$params['desc'],'img'=>json_encode(explode(',',$params['img']))]);
            Db::name('order_back')->where(['id'=>$params['back_id'],'uid'=>$uid])->update($data);
            Db::commit();
            return json(['code'=>200,'msg'=>'提交成功']);
        }catch (\Exception $e){
            Db::rollback();
            return json(['code'=>400,'msg'=>$e->getMessage()]);
        }
    }






    /**
     * post: 商铺认证
     * path: shopCertification
     * method: shopCertification
     * param: company_name - {string} 公司姓名
     * param: contact - {string} 联系电话
     * param: legal_person - {string} 法人姓名
     * param: address - {string} 公司地址
     * param: qq_email - {string} QQ邮箱
     * param: supply_address - {string} 供应地址
     * param: supply_specialty - {string} 供应专业
     * param: supply_type - {string} 供应类别
     * param: brand - {string} 品牌
     * param: idcard_front - {string} 身份证正面照
     * param: idcard_back - {string} 身份证反面照
     * param: license - {string} 营业执照
     */
    public function shopCertification ()
    {
        if (Request::instance()->isPost()) {
//            $uid = $this->uid;
            $uid = input('uid');
            if(empty($uid)){
                return json(['code'=>400,'msg'=>'缺少用户uid']);
            }
            $user = Db::name('member')->where(['uid'=>$uid])->find();
            if(empty($user)){
                return json(['code'=>400,'msg'=>'用户不存在']);
            }
            if ($user['renzhwng'] == 1) {
                return json(['code' => 400, 'msg' => '您已通过商家认证']);
            }
            $params = Request::instance()->param();
            $validate = new Validate([
                ['name', 'require', '请填写姓名'],
                ['id_card', 'require', '请填写身份证号'],
                ['mobile', 'require', '请填写联系电话'],
                ['pro_code', 'require', '请选择个人所在区域'],
                ['city_code', 'require', '请选择个人所在区域'],
                ['area_code', 'require', '请选择个人所在区域'],
                ['u_addr', 'require', '请填写详细地址'],
                ['idcard_front', 'require', '请上传身份证正面照片'],
                ['fid_card', 'require', '请上传身份证反面照片'],
                ['shop_name', 'require', '请填写店铺名称'],
                ['cate_id', 'require', '请选择所属分类'],
                ['pro', 'require', '请选择店铺地址所在区域'],
                ['city', 'require', '请选择店铺地址所在区域'],
                ['area', 'require', '请选择店铺地址所在区域'],
                ['s_addr', 'require', '请填写店铺详细地址'],
                ['bankname', 'require', '请填写银行名称'],
                ['bankcard', 'require', '请填写银行账号'],
                ['license', 'require', '请上传营业执照'],
                ['shop_logo', 'require', '请上传店铺logo'],
            ]);
            if (!$validate->check($params)) {
                return json(['code' => 400, 'msg' => $validate->getError()]);
            }
            $params['uid'] = $uid;
            $params['dateline'] = date('Y-m-d H:i:s');
            if(Db::name('shop')->insert($params)){
                return json(['code' => 200, 'msg' => '上传成功,请等待审核']);
            }
            return json(['code' => 400, 'msg' => '上传失败']);
        }
    }

    /**
     * get: 我的收藏
     * path: myCollect
     * method: myCollect
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function myCollect()
    {
        $pageSize = input('pageSize', 10);
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $where = ['uid' => $uid];
        $lists = Db::name('collect')->where($where)->paginate($pageSize)->each(function ($item, $key) {
            $item['squareinfo'] = Db::name('square')->where(['id'=>$item['itemid']])->find();
            $item['squareinfo']['res_url'] = json_decode($item['squareinfo']['res_url'],true);
            $item['userinfo'] = Db::name('member')->where(['uid'=>$item['squareinfo']['uid']])->field('sex,avatar,nickname')->find();
            return $item;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);
    }
    //我的商品关注
    public function goods_collect(){
        $uid=input("uid");
        $p=input('p')?input('p'):1;
        $limit=15;
        $count=db('goods_collect')->where('uid',$uid)->count();
        $count_page = ceil($count/$limit);
        $arr=db('goods_collect')->where('uid',$uid)->field('itemid')->page($p,$limit)->select();
        foreach ($arr as $key => $value) {
            $data=db('goods')->where('id',$value['itemid'])->find();
            $arr[$key]['goods_id']=$data['id'];
            $arr[$key]['img']=$data['img1'];
            $arr[$key]['goods_name']=$data['goods_name'];
            $arr[$key]['price']=$data['price'];
            unset( $arr[$key]['itemid']);
        }
        if($arr){
            return json(['code' => 200, 'msg' => '获取成功','data'=>$arr,'page'=>$count_page]);
        }else{
            return json(['code' => 400, 'msg' => '获取失败']);
        }
    }
    //我的店铺关注
    public function shop_collect(){
        $uid=input('uid');
        $p=input('p')?input('p'):1;
        $limit=15;
        $count=db('fav_merchant')->where('uid',$uid)->count();
        $count_page = ceil($count/$limit);
        $arr=db('fav_merchant')->where('uid',$uid)->field('itemid')->page($p,$limit)->select();
        foreach ($arr as $key => $value) {
            $data=db('shop')->where('id',$value['itemid'])->find();
            $arr[$key]['shop_logo']=$data['shop_logo'];
            $arr[$key]['shop_name']=$data['shop_name'];
            $arr[$key]['shop_id']=$data['id'];
            //好评率
            $hj=db('good_evaluate')->where('shop_id',$value['itemid'])->count();
            $hj_sum=db('good_evaluate')->where('shop_id',$value['itemid'])->sum('star');
            //如果评价表里没有该店铺的评价  好评率为100%;其他的好评率的规则来
            if($hj==0){
                $arr[$key]['good_hp']='100%';
            }else{
                $hjs=$hj_sum/($hj*5);
                $hjs_n=sprintf("%.2f",$hjs);
                $hjs_nm=$hjs_n*100;
                $arr[$key]['good_hp']=$hjs_nm.'%';
            }
            unset($arr[$key]['itemid']);
        }
        if($arr){
             return json(['code' => 200, 'msg' => '获取成功','data'=>$arr,'page'=>$count_page]);
         }else{
            return json(['code' => 400, 'msg' => '获取失败']);
         }
    }
    /**
     * get: 我的关注
     * path: myFavorite
     * method: myFavorite
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function myFavorite()
    {
        $pageSize = input('pageSize', 10);
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $where = ['uid' => $uid];
        $lists = Db::name('favorite')->where($where)->paginate($pageSize)->each(function ($item, $key) {
            $item['userinfo'] = Db::name('member')->where(['uid'=>$item['itemid']])->field('sex,avatar,nickname')->find();
            return $item;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists]);
    }



    /**
     * get: 历史记录
     * path: history
     * method: history
     */
    public function myHistory()
    {
//        $uid = $this->uid;
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,"msg"=>'缺少用户uid']);
        }
        $p=input('p')?input('p'):1;
        $limit=15;
        $lists = [];
        $count=Db::name('view_history')->where(['uid'=>$uid])->group('goods_id')->count();
        $count_page = ceil($count/$limit);
        $lists = Db::name('view_history')->where(['uid'=>$uid])->group('goods_id')->page($p,$limit)->select();
//        $lists=array_unique($lists, SORT_REGULAR);
        //print_r($lists);
        foreach ($lists as $k => $v){
            $arr = Db::name('goods')->field('img1,goods_name,sales,price')->where(['id'=>$v['goods_id']])->find();
            $lists[$k]['img']=$arr['img1'];
            $lists[$k]['goods_name']=$arr['goods_name'];
            $lists[$k]['sales']=$arr['sales'];
            $lists[$k]['price']=$arr['price'];
            //好评率
            $hj=Db::name('good_evaluate')->where(['good_id'=>$v['goods_id'],'uid'=>$uid])->count();
            $hj_sum=Db::name('good_evaluate')->where(['good_id'=>$v['goods_id'],'uid'=>$uid])->sum('star');
            if($hj==0){
                $lists[$k]['goods_hp']='100%';
            }else{
                $hjs=$hj_sum/($hj*5);
                $hjs_n=sprintf("%.2f",$hjs);
                $hjs_nm=$hjs_n*100;
                $lists[$k]['goods_hp']=$hjs_nm.'%';
            }
        }
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $lists,'page'=>$count_page]);
    }

    /**
     * get: 清空历史记录
     * path: delHistory
     * method: delHistory
     */
    public function delHistory()
    {
//        $uid = $this->uid;

        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        $res = Db::name('view_history')->where(['uid'=>$uid])->delete();
        if($res){
            return json(['code' => 200, 'msg' => '清空成功']);
        }
        return json(['code' => 400, 'msg' => '清空失败']);
    }



    /**
     * get: 我的广场发布
     * path: mySquare
     * method: mySquare
     * param: pageSize - {int} 每页显示数量[选填]，默认10
     * param: page - {int} 分页页码[选填]
     */
    public function mySquare()
    {
        $pageSize = input('pageSize', 10);
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'msg'=>'用户不存在']);
        }
        $list = Db::name('square')->where(['uid' => $uid])->paginate($pageSize)->each(function ($item, $key) {
            $item['createtime'] = date('m-d', strtotime($item['createtime']));
            $item['res_url'] = json_decode($item['res_url'], true);
            $item['userInfo'] = Db::name('member')->field('avatar,nickname,sex')->where(['uid'=>$item['uid']])->find();
            return $item;
        });
        return json(['code' => 200, 'msg' => 'SUCCESS', 'data' => $list]);
    }


    /**
     * post: 意见反馈
     * path: fankui
     * method: fankui
     * param: content - {text} 反馈内容
     */
    public function fankui()
    {
        $content = input('content');
        $uid = input('uid');
        if(empty($uid)){
            return json(['code'=>400,'msg'=>'缺少用户uid']);
        }
        $user = Db::name('member')->where(['uid'=>$uid])->find();
        if(empty($user)){
            return json(['code'=>400,'用户不存在']);
        }
        if(!$content){
            return json(['code'=>400,'msg'=>'请输入反馈内容']);
        }
        $data = [
            'uid' => $uid,
            'content' => $content,
            'createtime' => date('Y-m-d H:i:s',time())
        ];
        if(Db::name('feedback')->insert($data)){
            return json(['code' => 200, 'msg' => '反馈成功']);

        }
        return json(['code' => 400, 'msg' => '反馈失败']);

    }

    /**
     * post: 推广记录
     * path: myExtension
     * method: myExtension
     * param: uid - {int} 用户uid
     */

    public function myExtension(){
        if(Request::instance()->isGet()){
            $pageSize = input('pageSize', 15);
            $uid = input('uid',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
            $data = Db::name('spread')->where(['other_id'=>$uid])->paginate($pageSize)->each(function ($item,$key){
                $item['otherUserInfo'] = Db::name('member')->field('avatar,nickname,phone,createtime')->where(['uid'=>$item['uid']])->find();
                    return $item;
            });

            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
//            dump($data);die;
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * post: 我的推广信息
     * path: myExtInfo
     * method: myExtInfo
     * param: uid - {int} 用户uid
     */

    public function myExtInfo(){
        if(Request::instance()->isGet()){
            $uid = input('uid',0);
            if(!$uid){
                return json(['code'=>400,'msg'=>'缺少参数uid']);
            }
                $data = Db::name('member')->field('uid,qr_code,invite_code')->where(['uid'=>$uid])->find();

            return json(['code'=>200,'msg'=>'SUCCESS','data'=>$data]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }


}