<?php
namespace app\apis\validate;

class AddressNew extends BaseValidate
{
    protected $rule = [
        'uname|姓名' => 'require|isNotEmpty',
        'tel|手机号' => 'require|isMobile',
        'province|省份' => 'require|isNotEmpty',
        'city|区' => 'require|isNotEmpty',
        'area|市' => 'require|isNotEmpty',
        'address|详细地址' => 'require|isNotEmpty',
        'is_default|是否默认' => 'require|isNotOne',
    ];
}