<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 13:58
 */

namespace app\client\validate;

class Register extends Base
{
    protected $rule = [
        'mobile|手机号' => 'require|isMobile|unique:user',
        'code|短信验证码' => 'require|min:4|max:4|number',
        'password|密码' => 'require|min:6|max:12',
    ];
}