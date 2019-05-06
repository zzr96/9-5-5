<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 16:19
 */

namespace app\client\validate;

class Login extends Base
{
    protected $rule = [
        'mobile|手机号' => 'require|isMobile',
        'password|密码' => 'require',
    ];
}