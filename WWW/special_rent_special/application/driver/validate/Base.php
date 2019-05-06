<?php
namespace app\driver\validate;

use think\Validate;

/**
 * Class Base
 * 验证类的基类
 */
class Base extends Validate
{
    /**
     * 是否为正整数
     * time 2019-04-24
     * @param string $value 验证的值
     * @param string $rule 验证的规则
     */
    protected function isPositiveInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) > 0) {
            return true;
        }
        return $field . '必须是正整数';
    }
    /**
     * 是否为空
     * time 2019-04-24
     * @param string $value 验证的值
     * @param string $rule 验证的规则
     */
    protected function isNotEmpty($value, $rule='', $data='', $field='')
    {
        if (empty($value)) {
            return $field . '不允许为空';
        } else {
            return true;
        }
    }

    /**
     * 手机号的验证规则
     * time 2019-04-24
     * @param string $value 验证的值
     * @param string $rule 验证的规则
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }
}