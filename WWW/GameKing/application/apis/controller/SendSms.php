<?php

namespace app\apis\controller;

use Aliyun\Api\Sms\Request\V20170525\QuerySendDetailsRequest;
use Aliyun\Api\Sms\Request\V20170525\SendSmsRequest;
use Aliyun\Core\Config;
use Aliyun\Core\DefaultAcsClient;
use Aliyun\Core\Profile\DefaultProfile;
use think\Exception;

class SendSms
{
    static $acsClient = null;

    // 验证码短信
    const SMS_TEMPLATE_VERIFY = '';
    // 商家被评论时，发送通知
    // const SMS_TEMPLATE_BUSINESS_COMMENT_NOTICE = 'SMS_120406189';

    /**
     * 取得AcsClient
     *
     * @return DefaultAcsClient
     */
    public static function getAcsClient()
    {
        //产品名称:云通信流量服务API产品,开发者无需替换
        $product = "Dysmsapi";

        //产品域名,开发者无需替换
        $domain = "dysmsapi.aliyuncs.com";

        $accessKeyId = config('aliyunSms.keyId'); // AccessKeyId

        $accessKeySecret = config('aliyunSms.keySecret'); // AccessKeySecret

        // 暂时不支持多Region
        $region = "cn-hangzhou";

        // 服务结点
        $endPointName = "cn-hangzhou";

        if (static::$acsClient == null) {

            //初始化acsClient,暂不支持region化
            $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);

            // 增加服务结点
            DefaultProfile::addEndpoint($endPointName, $region, $product, $domain);

            // 初始化AcsClient用于发起请求
            static::$acsClient = new DefaultAcsClient($profile);
        }
        return static::$acsClient;
    }

    /**
     * 发送短信
     *
     * @param string $templateCode 短信模板Code，应严格按"模板CODE"填写
     * @param string $phoneNumbers 短信接收号码
     * @param array|null $templateParam 假如模板中存在变量需要替换则为必填项 (e.g. Array("code"=>"12345", "product"=>"阿里通信"))
     * @param string|null $outId [optional] 选填, 发送短信流水号 (e.g. 1234)
     * @param string $signName 短信签名，应严格"签名名称"填写
     * @return stdClass
     */
    public static function sendSms($templateCode, $phoneNumbers, $templateParam = null, $outId = null, $signName = '')
    {
        Config::load();

        // 初始化SendSmsRequest实例用于设置发送短信的参数
        $request = new SendSmsRequest();
        // 必填，设置雉短信接收号码
        $request->setPhoneNumbers($phoneNumbers);
        // 必填，设置签名名称
        $request->setSignName($signName);
        // 必填，设置模板CODE
        $request->setTemplateCode($templateCode);
        // 可选，设置模板参数
        if ($templateParam) {
            $request->setTemplateParam(json_encode($templateParam));
        }

        // 可选，设置流水号
        if ($outId) {
            $request->setOutId($outId);
        }

        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        // 打印请求结果
        try {
            $acsResponse = (array) $acsResponse;
            if (!isset($acsResponse['Code'])) {
                return false;
            }
            if ($acsResponse['Code'] !== 'OK') {
                return false;
            }
            return true;
        } catch (Exception $e) {
            $msg = $e->getMessage();
            return false;
        }
    }

    /**
     * 短信发送记录查询
     *
     * @param string $phoneNumbers 必填, 短信接收号码 (e.g. 12345678901)
     * @param string $sendDate 必填，短信发送日期，格式Ymd，支持近30天记录查询 (e.g. 20170710)
     * @param int $pageSize 必填，分页大小
     * @param int $currentPage 必填，当前页码
     * @param string $bizId 选填，短信发送流水号 (e.g. abc123)
     * @return stdClass
     */
    public static function queryDetails($phoneNumbers, $sendDate, $pageSize = 10, $currentPage = 1, $bizId = null)
    {
        Config::load();

        // 初始化QuerySendDetailsRequest实例用于设置短信查询的参数
        $request = new QuerySendDetailsRequest();
        // 必填，短信接收号码
        $request->setPhoneNumber($phoneNumbers);
        // 选填，短信发送流水号
        $request->setBizId($bizId);
        // 必填，短信发送日期，支持近30天记录查询，格式Ymd
        $request->setSendDate($sendDate);
        // 必填，分页大小
        $request->setPageSize($pageSize);
        // 必填，当前页码
        $request->setCurrentPage($currentPage);
        // 发起访问请求
        $acsResponse = static::getAcsClient()->getAcsResponse($request);
        // 打印请求结果
        // var_dump($acsResponse);
        return $acsResponse;
    }
}
