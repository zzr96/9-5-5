<?php
namespace app\apis\controller;

use think\Config;
use think\Db;
use think\Controller;

use Alipay\Alipaynotify;
use Wxpay\Wxpay;
use think\Cache;
use Alipay\AopClient;
use Alipay\AlipayTradeAppPayRequest;


/**
 * swagger: 支付相关
 */
class Punch extends Controller
{   
    //支付接口
    function pay(){
        $pay_type=input('pay_type');    //1.支付宝 2.微信   3.余额
        $uid=input('uid');
        $type=input('type');
        $price=input('price');
        if($pay_type==1){
            $data=$this->pay_alipay($price,$uid);
            return json(['code'=>200,'msg'=>'支付成功','data'=>'']);
        }elseif($pay_type==2){
            $data=$this->wxpay($price,$uid);
            return json(['code'=>200,'msg'=>'支付成功','data'=>'']);
        }else{
            $user=db('user')->where('id',$uid)->field('money')->find();
            //is_vip 1不是2：是
            if($user['money']<$price){
                return json(['code'=>101,'msg'=>'余额不足','data'=>'']);
            }
            $where=[
                'uid'=>$uid,
                'addtime'=>time(),
                'type'=>$type,
                'pay_money'=>$price,
            ];
            $res=db('user_clock_pay')->insert($where);
            return json(['code'=>200,'msg'=>'支付成功','data'=>'']);
            // $year=strtotime("+1 year");
            // $zj=strtotime("+1 years 1months",$year); 
        }
    }

    /**
     * get: 支付宝支付
     * path: pay_alipay
     * method: pay_alipay
     * param: order_id - {int} 订单号 必须
     * param: money - {int} 订单金额 必须
     */
    //支付宝支付
    function  pay_alipay($uid,$price)
    {
        require_once EXTEND_PATH . 'Alipay/AopEncrypt.php';
        //创建订单
        $price = 0.01;
        //发起支付
        $c = new AopClient;
        $request = new AlipayTradeAppPayRequest();
        //私钥
        $rsaPrivateKey = 'MIIEowIBAAKCAQEAzduMjzi1ro7qcuy/gwUJEJ5nYQ96f6ARqxT7hKA2fKlxb5JxqWiJxXhcd6GIQfnNPIuT2G/Wys/3xS9F3CpzC/Z4siU18zNuAK6xjzvJPZd9fBv3VX6rp0p5fVu/F8EcPA/sCipU0SmC33VS3JiAOWw0/qnF6xsd9FMHFsQWIyNytXhTeciqzgEpfrgR5kZmBHtdHuDdgaQ98eQaCwm6A5VGIkIW6A5cm357Yh3owOqKTaWvaPI1cYMEuyLPhM5thoLvqBacVhzH9+pWvT9Jb6s82RcEcjWXlw16JD7+JXJmy8bo7KlaoZq5EoQxUMhXPrRmchMnKh6na9V+FvAMTwIDAQABAoIBAEP9KA5Bb5focbxlPtc5+YV4m7It14qBRnSXqH5kH7rKh8GOv2VxNld26itEuWj45PUDMGBCh9FSIIYKl+sgRgEEdVZ4/bmGGeW1zMT+vPdPqk0sO2MMRAsriLaDb7ibugSISbANzReLu0KsUCK1Z1wPxez6C3kb6qfAKE43kwwlWFo//Ke/izl/+5QwBzP7Y2Sl1F2U5BRHP+T0XMbVPBIp+qsBxvNtFmTlo9Vjrnoae7I8mazNIs0YeYTGW54ifKxqoTHLgXKtdYM81Yj5wNntdl5OHYNzEZKiWc3rTPfZelba9MIcqHY0i/gJrzSId4Z/nzOmz/ZlPqheoHfHRXECgYEA6dAH30uA1W/zNYkmnNy7LpBojH+Tl54zv7t+mYt4i8HtwsuBqL8atFLGg62OEMYJq3cUWjEWUY/hUCVCvJ9ihp+/C+h1oSt4TCW8tNiqWYjvRWWqDUDYrBtwnW6ZUzzGN7jUV7lvU3QrE+Os+BChm3vTSQqcXnm+gndl1o6PNskCgYEA4WRphjJe1nR5+E9FOeaumWTlhDy+HtU+LZJ34nX2maSQkfsrFq76MCe+c7yoTc4i85MXB1rbn3JGc4JZmwiMDPNhmSk/12MYSyHIehi5fDkeb7yDGLFQj2ZTHUJgvW2zJdtPD4CJRoCCpkanKrNXJDs+PvFhhe8In1LfgZwh/lcCgYATcn0lIneNySj2e/jdNTLQdaxsezQAeWUefm7SQJp/LVskR576NSL4eqYOT8IwPPiS7W2g7tJHaGs6Kk85txwPzHusduJJzH7N/pmKhTbuCYi1QfZ58bnT3thoD8nq9XQbMGFhBohu7YujtN0vKNr4Pr1dJufIp+GjomCzDtp0mQKBgQDEecyG/9euKCC+tRNKcu2Wp95vFKhCpm6qOTiqP1x0+IBR0Nnxzxwm6C5cI4OvN1c+buUvMyOp7Wq+fE1yGNRE4dAdPFj7f9WIgO2KiYz/XadLjc/VsZPhPTiMk/VSi5MUGAXmJI0F2TaKjaJhVa5L1hzfr3js0L76a7qL+DelxwKBgBq9jHNY53nAqOBBtXPsr3hhYl3Ai+7zTrOTCcPp13FuQEYz82WOb7nARSpviqlFU1VPqTLFKS19KQmeFJo9W9ucUc7kZRVI955G/hC/Vd6r7CSpqn7EnzRZSwMR/KC97WIqaL0Z8Cd2RHIQiy6xhDco4wQxKwlmSLBT3v0h0icY';
        /**业务参数**/
        $content['body'] = "趣习惯APP支付"; //对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。非必填参数
        $content['subject'] = "趣习惯APP支付";//商品的标题/交易标题/订单标题/订单关键字等。
        $content['out_trade_no'] = $uid;//商户网站唯一订单号
        $content['timeout_express'] = "30m";//该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。注：若为空，则默认为15d。
        $content['product_code'] = "QUICK_MSECURITY_PAY";//    销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
        $content['total_amount'] = "$price";//    订单总金额，单位为元，精确到小数点后两位，取值范围[0.01,100000000]，
        // $content['goods_type'] = "0";//    商品主类型：0—虚拟类商品，1—实物类商品注：虚拟类商品不支持使用花呗渠道  非必填参数
        $con = json_encode($content);//$content是biz_content的值,将之转化成字符串
        /**业务参数**/
        /**公共参数**/
        $param = array();
        $param['app_id'] = '2019022263298301';//支付宝分配给开发者的应用ID
        $param['biz_content'] = $con;//业务请求参数的集合,长度不限,json格式
        $param['charset'] = 'utf-8';//请求使用的编码格式
        $param['format '] = 'json';
        $param['method'] = 'alipay.trade.app.pay';//接口名称
        $param['notify_url'] = 'http://liumai.niurenjianzhan.com/apis/pay/alipay_notify_url';//支付宝服务器主动通知地址
        $param['sign_type'] = 'RSA2';//商户生成签名字符串所使用的签名算法类型
        $param['timestamp'] = date("Y-m-d H:i:s");//发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
        $param['version'] = '1.0';//调用的接口版本，固定为：1.0
        /**公共参数**/
        //生成签名
        $paramStr = $c->getSignContent($param);
        $sign = $c->alonersaSign($paramStr, $rsaPrivateKey, 'RSA2');
        $param['sign'] = $sign;
        $str = $c->getSignContentUrlencode($param);//返回给客户端
        return $str;
        // $this->result($str);
    }

    //支付宝支付通知
    public function alipay_notify_url()
    {
        //回调返回订单号
        $out_trade_no = $_POST['out_trade_no'];
        //交易状态
        $trade_status = $_POST['trade_status'];
        //交易金额
        $total_fee = $_POST['total_amount'];
        if ($_POST['trade_status'] == 'TRADE_FINISHED') {
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
            //注意：
            //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
        } else if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
            $order = db('order')->where('order_num',$out_trade_no)->find();
        }
        echo "success"; //请不要修改或删除
    }

    /**
     * get: 微信支付
     * path: wxpay
     * method: wxpay
     * param: order_id - {int} 订单号 必须
     */
    public function wxpay($uid,$price)
    {   
        $id='1528609441'.$uid;
        $price = 0.01;
        //发起支付
        $body = '订单支付';  //商品详情
        //$wxpay_config = Config::get('wxpay_config');
        //预支付，构造要请求的参数数组，无需改动
        $parameter = array(
            'appid' => 'wx22d986cd10e4d041',//微信开放平台审核通过的应用APPID
            'mch_id' => '1528609441',//微信支付分配的商户号
            'nonce_str' => $this->getNonceStr(),//随机字符串，不长于32位。推荐随机数生成算法
            'body' => $body,//商品描述交易字段格式根据不同的应用场景按照以下格式：APP——需传入应用市场上的APP名字-实际商品名称，天天爱消除-游戏充值
            'out_trade_no' => $id,//String(32)商户系统内部的订单号,32个字符内
            'total_fee' => (int)($price * 100),//订单总金额，单位为分
            'spbill_create_ip' => $this->get_client_ip(),//用户端实际ip
            'notify_url' => 'http://quxiguan.tainongnongzi.com/public_html/index.php/apis/pay/weixin_notify_url',//String(256)  接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
            'trade_type' => 'APP'//支付类型
        );
        $wxpay = new Wxpay($parameter);
        $parameter['sign'] = $wxpay->SetSign();//设置sign;第一次延签
        $xml = $wxpay->arrayToXml($parameter);
        $url = 'https://api.mch.weixin.qq.com/pay/unifiedorder';
        $response = $wxpay->postXmlCurl($xml, $url);//预支付返回信息，curl抛送请求微信预支付api接口，php的curl模拟提交函数
        $response_array = $wxpay->FromXml($response);//微信服务器返回的xml转化过的数组
        //调起支付接口，构造要请求的参数数组，无需改
        $data = array(
            'appid' => 'wx22d986cd10e4d041',
            'noncestr' => $response_array['nonce_str'],
            'package' => 'Sign=WXPay',//扩展字段,暂填写固定值Sign=WXPay
            'partnerid' => '1528609441',
            'prepayid' => $response_array['prepay_id'],//String(32) 预支付交易会话ID
            'timestamp' => time(),
        );
        $wxpay = new Wxpay($data);
        $data['sign'] = $wxpay->SetSign();
        $data = json_encode($data);
        return $data;
    }

    public function weixin_notify_url()
    {
        $xml = file_get_contents('php://input');
        $xmlArray = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //file_put_contents("a.txt",json_encode($arr),FILE_APPEND);
        //支付成功
        if($xmlArray['result_code'] == "SUCCESS"){
            $out_trade_no = $xmlArray['out_trade_no'];//订单号：业务逻辑区域
            $total_fee = $xmlArray['total_fee'] / 100;//订单号：业务逻辑区域
            // $order = db('order')->where('order_num',$out_trade_no)->find();
            $return = ['return_code' => 'SUCCESS', 'return_msg' => 'OK'];
            $xml = '<xml>';
            foreach ($return as $k => $v) {
                $xml .= '<' . $k . '><![CDATA[' . $v . ']]></' . $k . '>';
            }
            $xml .= '</xml>';
            echo $xml;
            exit;
        } else {
            echo "FAIL";
            exit;
        }
    }

    /**
     *
     * 产生随机字符串，不长于32位
     * @param int $length
     * @return 产生的随机字符串
     */
    public function getNonceStr($length = 32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    /*
    获取当前服务器的IP
    */
    public function get_client_ip()
    {
        if ($_SERVER['REMOTE_ADDR']) {
            $cip = $_SERVER['REMOTE_ADDR'];
        } elseif (getenv("REMOTE_ADDR")) {
            $cip = getenv("REMOTE_ADDR");
        } elseif (getenv("HTTP_CLIENT_IP")) {
            $cip = getenv("HTTP_CLIENT_IP");
        } else {
            $cip = "unknown";
        }
        return $cip;
    }

    public function getSignContent($params)
    {
        ksort($params);
        $stringToBeSigned = "";
        $i = 0;
        foreach ($params as $k => $v) {
            if (false === $this->checkEmpty($v) && "@" != substr($v, 0, 1)) {

                // 转换成目标字符集
                $v = $this->characet($v, $this->postCharset);

                if ($i == 0) {
                    $stringToBeSigned .= "$k" . "=" . "$v";
                } else {
                    $stringToBeSigned .= "&" . "$k" . "=" . "$v";
                }
                $i++;
            }
        }

        unset ($k, $v);
        return $stringToBeSigned;
    }


    //支付宝签名
    public function rsaSign($params, $signType = "RSA")
    {
        return $this->sign($this->getSignContent($params), $signType);
    }

    function sign($data, $signType = "RSA")
    {
        if ($this->checkEmpty($this->rsaPrivateKeyFilePath)) {
            $priKey = $this->rsaPrivateKey;
            $res = "-----BEGIN RSA PRIVATE KEY-----\n" .
                wordwrap($priKey, 64, "\n", true) .
                "\n-----END RSA PRIVATE KEY-----";
        } else {
            $priKey = file_get_contents($this->rsaPrivateKeyFilePath);
            $res = openssl_get_privatekey($priKey);
        }
        ($res) or die('您使用的私钥格式错误，请检查RSA私钥配置');
        if ("RSA2" == $signType) {
            openssl_sign($data, $sign, $res, OPENSSL_ALGO_SHA256);
        } else {
            openssl_sign($data, $sign, $res);
        }
        if (!$this->checkEmpty($this->rsaPrivateKeyFilePath)) {
            openssl_free_key($res);
        }
        $sign = base64_encode($sign);
        return $sign;
    }
}