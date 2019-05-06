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
use app\apis\controller\Pay;

/**
 * swagger: 支付相关
 */
class Porder extends Controller
{
    //订单下单
    function orderpay(){
        $id=input('order_id');
        $pay_status=input('pay_status');
        $money=input('money');
        $money=$money*100;
        $arr=db('order')->where('id',$id)->field('order_sn')->find();
        $order_sn=$arr['order_sn'];
        return $this->pay($pay_status,$order_sn,$money);
    }
    //支付接口
    function pay($pay_status,$order_sn,$money){
        if($pay_status==2){
            $aa=controller('Pay');
            $url= $_SERVER['SERVER_NAME']."/apis/Porder/alipay_notify_url";
            return $data = $aa->alipayPay($money,$order_sn,$url);
        }elseif($pay_status==3){
            $aa=controller('Pay');
            $url= $_SERVER['SERVER_NAME']."/apis/Porder/weixin_notify_url";
            $data = $aa->wxpay($money/100,$order_sn,$url);
            return json(['data'=>$data,"code"=>200,"msg"=>"支付成功",'money'=>$money/100]);

        }else{
             //存在order_sn
             $find=db('order')->where('order_sn',$order_sn)->field('uid')->find();
             $user=db('member')->where('uid',$find['uid'])->field('balance')->find();
             if($money>$user['balance']){
                return json(['code'=>400,'msg'=>'余额不足','data'=>'']);
            }
            $new_money=$user['balance']-$money;
            $aa=db('member')->where('uid',$find['uid'])->update(['balance'=>$new_money]);
            db('balance_log')->insert(['uid'=>$find['uid'],'type'=>'1','order_no'=>$order_sn,'amount'=>$money,'createtime'=>date('Y-m-d H:i:s'),'mark'=>"购物消费"]);
            db('order')->where('order_sn',$order_sn)->update(['status'=>2,'pay_time'=>date('Y-m-d H:i')]);
            $count=db("order_goods")->where('order_sn',$order_sn)->count();
            if($count>1){
                $arr=db("order_goods")->where("order_sn",$order_sn)->select();
                foreach ($arr as $key => $value) {
                    $res=db("goods_size")->where("id",$value['goods_size_id'])->find();
                    db("goods")->where('id',$res["good_id"])->setInc('sales',1);
                }
            }else{
                $arr=db("order_goods")->where("order_sn",$order_sn)->find();
                $res=db("goods_size")->where("id",$arr['goods_size_id'])->find();
                db("goods")->where('id',$res["good_id"])->setInc('sales',1);
            }
            if($aa){
                return json(['code'=>200,'msg'=>'支付成功','data'=>'']);
            }else{
                return json(['code'=>400,'msg'=>'支付失败','data'=>'']);
            }

        }
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
            $order = db('order')->where('order_sn',$out_trade_no)->update(['status'=>2,'pay_time'=>date('Y-m-d H:i')]);
            $count=db("order_goods")->where('order_sn',$out_trade_no)->count();
            if($count>1){
                $arr=db("order_goods")->where("order_sn",$out_trade_no)->select();
                foreach ($arr as $key => $value) {
                    $res=db("goods_size")->where("id",$value['goods_size_id'])->find();
                    db("goods")->where('id',$res["good_id"])->setInc('sales',1);
                }
            }else{
                $arr=db("order_goods")->where("order_sn",$out_trade_no)->find();
                $res=db("goods_size")->where("id",$arr['goods_size_id'])->find();
                db("goods")->where('id',$res["good_id"])->setInc('sales',1);
            }
            db("balance_log")->where('order_no',$out_trade_no)->update(['type'=>1,'mark'=>"购物消费"]);
        }
        echo "success"; //请不要修改或删除
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
            $order = db('order')->where('order_sn',$out_trade_no)->update(['status'=>2,'pay_time'=>date('Y-m-d H:i')]);
            $count=db("order_goods")->where('order_sn',$out_trade_no)->count();
            if($count>1){
                $arr=db("order_goods")->where("order_sn",$out_trade_no)->select();
                foreach ($arr as $key => $value) {
                    $res=db("goods_size")->where("id",$value['goods_size_id'])->find();
                    db("goods")->where('id',$res["good_id"])->setInc('sales',1);
                }
            }else{
                $arr=db("order_goods")->where("order_sn",$out_trade_no)->find();
                $res=db("goods_size")->where("id",$arr['goods_size_id'])->find();
                db("goods")->where('id',$res["good_id"])->setInc('sales',1);
            }
            db("balance_log")->where('order_no',$out_trade_no)->update(['type'=>1,"mark"=>"购物消费"]);
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
