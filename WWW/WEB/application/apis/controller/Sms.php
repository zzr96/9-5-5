<?php
namespace app\apis\controller;

use think\Controller;

/**
 * swagger: 商品相关
 */
class Sms extends Controller
{
	function sms(){
		$rand='123456';
		$target = "http://cf.51welink.com/submitdata/Service.asmx/g_Submit";
		//替换成自己的测试账号,参数顺序和wenservice对应
		$content='您的注册验证码为：'.$rand.'。验证码有效期为5分钟，请尽快填写！【养生活】'; 
		$post_data = "sname=dlycwl01&spwd=ycwl123456&scorpid=&sprdid=1012818&sdst=13673068561&smsg=".rawurlencode($content);
		//$binarydata = pack("A", $post_data);
		echo $gets = $this->post($post_data, $target);
		//请自己解析$gets字符串并实现自己的逻辑
		//<State>0</State>表示成功,其它的参考文档
	}
	function post($data, $target){
	    $url_info = parse_url($target);
	    $httpheader = "POST " . $url_info['path'] . " HTTP/1.0\r\n";
	    $httpheader .= "Host:" . $url_info['host'] . "\r\n";
	    $httpheader .= "Content-Type:application/x-www-form-urlencoded\r\n";
	    $httpheader .= "Content-Length:" . strlen($data) . "\r\n";
	    $httpheader .= "Connection:close\r\n\r\n";
	    //$httpheader .= "Connection:Keep-Alive\r\n\r\n";
	    $httpheader .= $data;

	    $fd = fsockopen($url_info['host'], 80);
	    fwrite($fd, $httpheader);
	    $gets = "";
	    while(!feof($fd)) {
	        $gets .= fread($fd, 128);
	    }
	    fclose($fd);
	    if($gets != ''){
	        $start = strpos($gets, '<?xml');
	        if($start > 0) {
	            $gets = substr($gets, $start);
	        }        
	    }
	    return $gets;
	}
}