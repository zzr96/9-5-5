<?php
namespace app\client\validate;

class Address extends Base
{
    protected $rule = [
        'id|地址id' => 'require|number',
        'name|姓名' => 'require|isNotEmpty',
        'mobile|手机号' => 'require|isMobile',
        'province|省份' => 'require|isNotEmpty',
        'city|市' => 'require|isNotEmpty',
        'area|区' => 'require|isNotEmpty',
        'street|街道' => 'require|isNotEmpty',
        'address|详细地址' => 'require|isNotEmpty',
        'is_default|是否默认' => 'require|number|between:0,1',
    ];

    protected $scene = [
      'add' => ['name','mobile','province','city','area','street','address','is_default']
    ];
}