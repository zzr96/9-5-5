<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 13:12
 */

namespace app\client\controller;
use app\client\validate\Register;
use think\Controller;
use think\Request;
use app\client\model\User;
use think\Validate;

class Login extends Controller
{
    /**
     * 用户注册
     *
     * @param string mobile 用户名
     * @param int code 密码
     * @param string password 密码
     */
    public function userRegister()
    {
        if(Request::instance()->isPost()){
            $data = Request::instance()->param();
            $validate = new Register();
            if(!$validate->check($data)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $res = checkSmsCode($data['mobile'],$data['code']);
            if($res['code'] != 200){
                return json($res);
            }
            $data['password'] = md5($data['password']);
            try {
                $user = User::create($data, true);
                $uid = $user->id;
                //设置Token
                $_token = md5($uid . $data['password']);
                cookie('token', $_token, 3600 * 24 * 30 * 12);
                cookie('uid', $uid, 3600 * 24 * 30 * 12);
            } catch (\Exception $e) {
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
            return json(['code'=>200,'msg'=>'注册成功','data'=>['uid'=>$uid]]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);

    }


    /**
     * 用户登录
     *
     * @param string $mobile  手机号
     * @param string $password 密码
     */
    public function userLogin()
    {
        if(Request::instance()->isPost()){
            $data = Request::instance()->param();
            $validate = new \app\client\validate\Login();
            if(!$validate->check($data)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $data['password'] = md5($data['password']);
            $user = User::get(['mobile'=>$data['mobile'],'password'=>$data['password']]);
            if(!$user){
                return json(['code'=>400,'msg'=>'账号或密码错误']);
            }
            $token = md5($user->id . $user->password);
            cookie('token', $token, 3600 * 24 * 30 * 12);
            cookie('uid', $user->id, 3600 * 24 * 30 * 12);
            return json(['code' => 200, 'msg' => 'SUCCESS', 'data' =>['uid'=>$user->id,'token'=>$token]]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }

    /**
     * 用户忘记密码
     *
     * @param string $mobile  手机号
     * string code  短信验证码
     * @param string $password 密码
     */
    public function forgetPwd()
    {
        if(Request::instance()->isPost()){
            $data = Request::instance()->param();
            $rule = [
                'mobile|手机号'=>'require',
                'code|短信验证码'=>'require|number|min:4|max:4',
                'password|密码' => 'require|min:6|max:12'
            ];
            $validate = new Validate($rule);
            if(!$validate->check($data)){
                return json(['code'=>400,'msg'=>$validate->getError()]);
            }
            $res = checkSmsCode($data['mobile'],$data['code']);
            if($res['code'] != 200){
                return json($res);
            }
            $user = User::get(['mobile'=>$data['mobile']]);
            if(!$user){
                return json(['code'=>400,'msg'=>'手机号不存在']);
            }
            try {
                $user->password = md5($data['password']);
                $user->save();
                $uid = $user->id;
                //设置Token
                $_token = md5($uid . md5($data['password']));
                cookie('token', $_token, 3600 * 24 * 30 * 12);
                cookie('uid', $uid, 3600 * 24 * 30 * 12);
            } catch (\Exception $e) {
                return json(['code'=>400,'msg'=>$e->getMessage()]);
            }
            return json(['code'=>200,'msg'=>'重置成功','data'=>['uid'=>$uid]]);
        }
        return json(['code'=>400,'msg'=>'请求类型错误']);
    }
}