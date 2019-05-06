<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 13:58
 */

namespace app\driver\validate;

class Register extends Base
{
    /**
     * desc: 注册验证规则
     * time 2019-04-24
     */
    protected $rule = [
        'mobile|手机号' => 'require|isMobile|unique:driver',
        'code|短信验证码' => 'require|min:4|max:4|number',
        'password|密码' => 'require|min:6|max:12',
    ];
}