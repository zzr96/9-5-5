<?php
/**
 * Created by PhpStorm.
 * User: 7howe
 * Date: 2018/8/25
 * Time: 11:01
 */

namespace app\apis\controller;


use think\Controller;
use think\Db;

class Paynotify extends Controller
{
    public function _empty()
    {
        return 'empty';
    }

    //支付宝支付通知
    public function alipay_notify_url()
    {
        //回调返回订单号
        $out_trade_no = $_POST['out_trade_no'];
        $file='./log.txt';
        $content="到回调2";
        file_put_contents($file,$content,FILE_APPEND);
        if ($_POST['trade_status'] == 'TRADE_FINISHED'){
            $file='./log.txt';
            $content="到回调了1";
            file_put_contents($file,$content,FILE_APPEND);
            //判断该笔订单是否在商户网站中已经做过处理
            //如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
            //如果有做过处理，不执行商户的业务程序
            //注意：
            //退款日期超过可退款期限后（如三个月可退款），支付宝系统发送该交易状态通知
            //请务必判断请求时的total_fee、seller_id与通知时获取的total_fee、seller_id为一致的
        } else if ($_POST['trade_status'] == 'TRADE_SUCCESS'){
            //成功回调
            $file='./log.txt';
            $content="到回调了";
            file_put_contents($file,$content,FILE_APPEND);
            $payInfo = Db::name('balance_log')->field('uid,amount')->where(['order_no'=>$out_trade_no])->find();
            Db::name('balance_log')->where(['order_no'=>$out_trade_no])->update(['type'=>3,'mark'=>'余额充值']);
            //根据支付异步通知
            Db::name('member')->where(['uid'=>$payInfo['uid']])->setInc('balance',$payInfo['amount']);
        }
        echo "success"; //请不要修改或删除
    }

    //微信支付成功
    public function weixin_notify_url()
    {
        $xml = file_get_contents('php://input');
        $xmlArray = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
        //支付成功

        if ($xmlArray['result_code'] == "SUCCESS") {
            $order_no = $xmlArray['out_trade_no'];//订单号：业务逻辑区域
            $total_fee = $xmlArray['total_fee'];//订单号：业务逻辑区域
            //根据支付异步通知
            //成功回调
            $payInfo = Db::name("balance_log")->field('uid,amount')->where(["order_no"=>$order_no])->find();
            Db::name('balance_log')->where(['order_no'=>$order_no])->update(['type'=>3,'mark'=>'余额充值']);
            //根据支付异步通知
            Db::name('member')->where(['uid'=>$payInfo['uid']])->setInc('balance',$payInfo['amount']);
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
}