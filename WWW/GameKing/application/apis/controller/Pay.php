<?php

namespace app\apis\controller;

use Alipay\AlipayTradeAppPayRequest;
use Alipay\AlipayTradeWapPayRequest;
use Alipay\AopClient;
use fast\Random;
use think\Controller;
use think\Db;
use Wxpay\Wxpay;

/**
 * swagger: 支付相关
 */
class Pay extends Controller
{
    private $alipayapp_id = '2019032263659101';
    private $wxapp_id = 'wx69a98000459e47bd';
    private $wxmch_id = '1530636181';
    private $alipayrsakey = 'MIIEowIBAAKCAQEAq5C9mmrII8A1G77BtJRUiBG8n3OLs/WffNcGoxMVjUyxXQ5PLJ7zkuTvvZbCmIWI9EdXXv+joKwPyE4KkDkbKTzs7sIQpbaLGEwgoDvhHdPyNaUEloiilhSg9UO3S+ypfJ1R0FTUx4waFIqk/KO1w+W5a2O7evKPvniaOzZtbnp16p8DU/kkwDngnuFWsSloek8uIdNWlU7KUxOBzeQ802hGynL8RCBCavIZLbdSgeDqyAZyFcD6B9SR1X7EZNHkwoPe4RVwhHGFz3j6bzEg1i3gxUrJX/T9NZ2yDPnN3aLplBhX/F5sJdSLGDwFFbIEOKikP2dRTNAGNKDRmkB5LwIDAQABAoIBAQCYTE+TwVDTM6+xZn/LzpTPiXc40LhmUxd2GedhYfKS0U7tPWOZR60PbD1JvogWHww5VQ9BXIujeLo15NlJt/fdfqERPaLLiCUnt4SK2Tz4Xoo3hrpw6u/xbHSJ5MfVFUxLO9hBqNyOG1eXfpABWU5kjQwxQYJPHP3vZl37TFCObaEklrIYsRIyXI+i0qVoZ1D6oh1qjCSF4mVNYCiBIChEAuk36cJHL/RoJvOb2tSOsMKm0XohQVgRidr4Y0T0R/nIMVzqblpZuIC+BZehUvWBsfblBqleGdxMETDxb+QFSA2ZNTgjM7tXxC7yoPnYqcCGTH8+shSbazG8ZcdsNmOZAoGBANIbeIwmp45YEW2Xe18hWShr2guEzjDMqIZsCs/M0ugMQm+LCcoPcJzbBoKnyuseZ4fuK8+tvehQEtcxoR615iHNPJrr719g7WFaN0Nd4gjUqTWm2hv6ZmUBG8SZbWqllcwXUi1O59JExnJF6Gw4lj95FlbUb/aU8nVRgwng5IWbAoGBANEKIQlwZIk2cZnbJIGWePH/pmEk3r7MK40siAL4OXX0t5s0m64/d0sFEvEFmj0KpIIZjruU+0v3R983AQ9Q5g7zoKrMp8XmpqcF+XMPcwzF+G49apBBDzPGofdv+v0ctQC4l+v4dZLK1vdPKkwwXpm3iU0gZo8dlc78pj2cH739AoGAHJFtp6jN2ZXx8LPLK5kuNUfM4woQuyPoujZQlgakmraoBeCi2R2WU02YqgVEmwmMTDg/LsLR8peIqEfwP2u+OdRdLDduWcqlHDqXDwrh5A9+XnQsbedzKsKm0umFrh2Lq690FgMHlFiTnxAQVT6wdzrwpafG/YEtInSN27b/e4cCgYBKCbhhy3kQUEQ0JjXr4Au+Srn3NkPeeBoEynzx8FEJwj81rZBmSvxJieaHj2BttlNF8R9ouP+4jEDcgnLRpemV6hxB4nKZ2XO1PJx5cENjhsrS4NXZefGUeQnsxbCz7XL+amMEpN+fulAhD9z/VzJqZ2Mt+gfZMLVatol60M09UQKBgHwbZZS1kcsggd8pDTtqzvsLu43tZ/XPxhijxObpgfNgg0BAo9oBUPRQ5lKWsZHDJoeJ09jjsqnBa3CkpNWmoM2CbDS3SWhVp3rtNTKy91jWh+oQiYYQaZ8vYK5t6SX9e1E0W8pHJXUSOJqem2JvX/czmATIUJ1rqt4pO+102jyI';


    //支付接口
    function pay(){
        $uid=input('uid');
        cookie('userId',$uid);
        $pay_type=input('pay_type');    //1.支付宝 2.微信
        if(empty($pay_type)){
            return json(['code'=>400,'msg'=>'缺少支付类型']);
        }
        $money = input('price');
        if(empty($money)){
            return json(['code'=>400,'msg'=>'缺少支付金额']);
        }
        $oid = createOrderNo();
        if($pay_type==1){
            $data=$this->alipayPay($money * 100,$oid,'http://yingtao.tainongnongzi.com/apis/paynotify/alipay_notify_url');
            return $data;
//            return json(['code'=>200,'msg'=>'充值成功','data'=>$data]);
        }else{
            $data=$this->wxpay($money,$oid,'http://yingtao.tainongnongzi.com/apis/paynotify/weixin_notify_url');
            return $data;
//            return json(['code'=>200,'msg'=>'充值成功','data'=>$data]);
        }
    }


    //支付宝支付
    public function alipayPay($money,$order_id,$notify_url)
    {
        require_once EXTEND_PATH . 'Alipay/AopClient.php';
        //发起支付
        $c = new \Alipay\AopClient();

        $rsaPrivateKey = $this->alipayrsakey;
        $money = (float)($money/100);
        /**业务参数**/
        $content['body'] = "游戏大王APP支付"; //对一笔交易的具体描述信息。如果是多种商品，请将商品描述字符串累加传给body。非必填参数
        $content['subject'] = "游戏大王APP支付"; //商品的标题/交易标题/订单标题/订单关键字等。
        $content['out_trade_no'] = $order_id; //商户网站唯一订单号
        $content['timeout_express'] = "30m"; //该笔订单允许的最晚付款时间，逾期将关闭交易。取值范围：1m～15d。m-分钟，h-小时，d-天，1c-当天（1c-当天的情况下，无论交易何时创建，都在0点关闭）。 该参数数值不接受小数点， 如 1.5h，可转换为 90m。注：若为空，则默认为15d。
        $content['product_code'] = "QUICK_MSECURITY_PAY"; //    销售产品码，商家和支付宝签约的产品码，为固定值QUICK_MSECURITY_PAY
        $content['total_amount'] = sprintf("%.2f", $money);
        $con = json_encode($content); //$content是biz_content的值,将之转化成字符串
        /**业务参数**/
        /**公共参数**/
        $param = array();

        $param['app_id'] = $this->alipayapp_id; //支付宝分配给开发者的应用ID

        $param['biz_content'] = $con; //业务请求参数的集合,长度不限,json格式
        $param['charset'] = 'utf-8'; //请求使用的编码格式
        $param['format '] = 'json';
        $param['method'] = 'alipay.trade.app.pay'; //接口名称
        $param['notify_url'] = $notify_url; //支付宝服务器主动通知地址
        $param['sign_type'] = 'RSA2'; //商户生成签名字符串所使用的签名算法类型
        $param['timestamp'] = date("Y-m-d H:i:s"); //发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
        $param['version'] = '1.0'; //调用的接口版本，固定为：1.0
        /**公共参数**/
        //生成签名
        $uid = cookie('userId');
        Db::name('balance_log')->insert(['uid'=>$uid,'type'=>'-1','order_no'=>$order_id,'amount'=>$money * 100,'createtime'=>date('Y-m-d H:i:s')]);
        $paramStr = $c->getSignContent($param);
        $sign = $c->alonersaSign($paramStr, $rsaPrivateKey, 'RSA2');
        $param['sign'] = $sign;
        $str = $c->getSignContentUrlencode($param); //返回给客户端
        return json(['data'=>$str,"code"=>200,"msg"=>"支付成功",'money'=>$money]);    }

    /**
     * get: 微信支付
     * path: wxpay
     * method: wxpay
     * param: order_id - {int} 订单号 必须
     */
    public function wxpay($money,$order_id,$notify_url)
    {
        // $price = 0.01;
        //发起支付
        $body = '游戏大王APP支付';  //商品详情
        //$wxpay_config = Config::get('wxpay_config');
        //预支付，构造要请求的参数数组，无需改动
        $parameter = array(
            'appid' => $this->wxapp_id,//微信开放平台审核通过的应用APPID
            'mch_id' => $this->wxmch_id,//微信支付分配的商户号
            'nonce_str' => $this->getNonceStr(),//随机字符串，不长于32位。推荐随机数生成算法
            'body' => $body,//商品描述交易字段格式根据不同的应用场景按照以下格式：APP——需传入应用市场上的APP名字-实际商品名称，天天爱消除-游戏充值
            'out_trade_no' => $order_id,//String(32)商户系统内部的订单号,32个字符内
            'total_fee' => (int)($money * 100),//订单总金额，单位为分
            'spbill_create_ip' => $this->get_client_ip(),//用户端实际ip
            'notify_url' => $notify_url,//String(256)  接收微信支付异步通知回调地址，通知url必须为直接可访问的url，不能携带参数。
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
            'appid' => $this->wxapp_id,
            'noncestr' => $response_array['nonce_str'],
            'package' => 'Sign=WXPay',//扩展字段,暂填写固定值Sign=WXPay
            'partnerid' => $this->wxmch_id,
            'prepayid' => $response_array['prepay_id'],//String(32) 预支付交易会话ID
            'timestamp' => time(),
        );

        $uid = cookie('userId');
        Db::name('balance_log')->insert(['uid'=>$uid,'type'=>'-1','order_no'=>$order_id,'amount'=>$parameter['total_fee'],'createtime'=>date('Y-m-d H:i:s')]);
        $wxpay = new Wxpay($data);
        $data['sign'] = $wxpay->SetSign();
        $data = json_encode($data);
        return $data;
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

    /**
     * 获取当前服务器IP
     *
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

    /**
     * get: 微信退款
     * path: wxrefund
     * method: wxrefund
     * param: order_id - {int} 订单号 必须
     */
    public function wxRefund($order_id){
        /**请求参数**/
        $parameter = array(
            'appid' => $this->wxapp_id,//微信开放平台审核通过的应用APPID
            'mch_id' => $this->wxmch_id,//微信支付分配的商户号
            'nonce_str' => $this->getNonceStr(),//随机字符串，不长于32位。推荐随机数生成算法
            'out_trade_no' => $order_id,//String(32)商户系统内部的订单号,32个字符内
        );
        /**调用退款接口**/
        $wxpay = new Wxpay($parameter);
        $parameter['sign'] = $wxpay->SetSign();//设置sign;第一次延签
        $xml = $wxpay->arrayToXml($parameter);
        $url = 'https://api.mch.weixin.qq.com/pay/refundquery';
        $response = $wxpay->postXmlCurl($xml, $url);//预支付返回信息，curl抛送请求微信预支付api接口，php的curl模拟提交函数
        $response_array = $wxpay->FromXml($response);//微信服务器返回的xml转化过的数组
        /**返回数据处理**/
        if(isset($response_array['result_code']) && $response_array['result_code'] == 'SUCCESS'){
            return '1';
        }else{
            return '0';
        }
    }


    /**
     * get: 支付宝退款
     * path: alirefund
     * method: alirefund
     * param: order_id - {int} 订单号 必须
     * param: money - {int} 退款金额 必须
     */
    public function aliRefund($order_id,$money){
        require_once EXTEND_PATH . 'Alipay/AopClient.php';
        $c = new \Alipay\AopClient();

        $rsaPrivateKey = $this->alipayrsakey;
        /**业务参数**/
        $content['refund_amount'] = (int)($money/100); //退款金额
        $content['out_trade_no'] = $order_id; //商户网站唯一订单号
        $con = json_encode($content); //$content是biz_content的值,将之转化成字符串

        /**公共参数**/
        $param = array();
        $param['app_id'] = $this->alipayapp_id; //支付宝分配给开发者的应用ID
        $param['biz_content'] = $con; //业务请求参数的集合,长度不限,json格式
        $param['charset'] = 'utf-8'; //请求使用的编码格式
        $param['format '] = 'json';
        $param['method'] = 'alipay.trade.refund'; //接口名称
        $param['sign_type'] = 'RSA2'; //商户生成签名字符串所使用的签名算法类型
        $param['timestamp'] = date("Y-m-d H:i:s"); //发送请求的时间，格式"yyyy-MM-dd HH:mm:ss"
        $param['version'] = '1.0'; //调用的接口版本，固定为：1.0

        /**请求退款**/
        $paramStr = $c->getSignContent($param);
        $sign = $c->alonersaSign($paramStr, $rsaPrivateKey, 'RSA2');
        $param['sign'] = $sign;
        $str = $c->getSignContentUrlencode($param); //返回给客户端
       return $str;
    }
}
