<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019/4/23
 * Time: 16:19
 */

namespace app\driver\validate;

class Login extends Base
{
    /**
     * desc: 登录验证规则
     * time 2019-04-24
     */
    protected $rule = [
        'mobile|手机号' => 'require|isMobile',
        'password|密码' => 'require',
    ];
}