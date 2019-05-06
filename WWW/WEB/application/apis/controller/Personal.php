<?php
namespace app\apis\controller;

use think\Controller;
use QRcode\QRcode;

class Personal extends Com
{
	//个人信息
	function personal_data(){
		$uid=input('uid');
		if(empty($uid)){
			return json(['code'=>101,'msg'=>'您尚未登录!','data'=>'']);
		}
		$res=db('user')->where('id',$uid)->field('name')->find();
		if($res){
			return json(['code'=>200,'msg'=>'获取成功!','data'=>$res]);
		}else{
			return json(['code'=>100,'msg'=>'获取失败!','data'=>'']);
		}
	}

	//测试生成带有二维码的图片
	function code(){
		$name='邀请码';
		$code='123456789';
		$code_name=$name.$code;
		$QRCode = new QRcode();
		$ds = DIRECTORY_SEPARATOR;
    	$root = dirname(dirname(dirname(__DIR__)));
    	$img['img']='http://www.fengchao.com/uploads/image/20181124/20181124015048_90400.png';
    	$img_dir = './uploads/public_html/' . date("Ymd") . "/";
    	if(!is_dir($img_dir)){
    		mkdir($img_dir, 0777, true);
    	}
    	$qr_name = $this->createImgName('qrcode', $code);
    	$url = $_SERVER['SERVER_NAME'].'/public_html';
    	//二维码内容
    	$qr_goods_url = "www.baidu.com";
    	//二维码容错率
    	$qr_error_level = 'M';
    	//二维码大小
    	$qr_size = 4;
    	//二维码边距
    	$qr_margin = 3;
    	//生成二维码
    	$QRCode::png($qr_goods_url, $img_dir.$qr_name, $qr_error_level, $qr_size, $qr_margin);
    	//二维码图片信息
    	$qr_base_info = getimagesize($img_dir.$qr_name);
    	$qr_w = $qr_base_info[0];	//宽
    	$qr_h = $qr_base_info[1];	//高
    	//二维码图片画布
		$qr_obj = imagecreatefrompng($img_dir.$qr_name);
		var_dump($qr_base_info);exit;
    	$goods_base_info = getimagesize($img['img']);
		$goods_ext_info = pathinfo($img['img']);
    	$goods_w = $goods_base_info[0];
    	$goods_h = $goods_base_info[1];
    	//创建商品原图片画布
    	switch($goods_base_info[2]){
    		case 1 : $goods_obj = imagecreatefromgif($img['img']); break;
    		case 2 : $goods_obj = imagecreatefromjpeg($img['img']); break;
    		case 3 : $goods_obj = imagecreatefrompng($img['img']); break;
    	}
    	$share_name = $this->createImgName('share', 'png');
    	while(file_exists($img_dir.$share_name)){
    		$share_name = $this->createImgName('share', 'png');
    	}
    	//创建画布
    	$img_w = 375;	//新画布宽度
    	$img_h = 600;	//新画布高度
    	$new_goods_w = 375; 	//新画布中商品图片宽度
    	$new_goods_h = 400;		//新画布中商品图片高度
    	$new_name_w = 200; 		//新画布中商品名称宽度
    	$new_qr_w = 125;	//新画布中二维码图片宽度
    	$new_qr_h = 125;	//新画布中二维码图片高度

    	$img_obj = imagecreatetruecolor($img_w, $img_h);
    	//填充背景
    	$gray = imagecolorallocate($img_obj, 245, 245, 245);
    	imagefill($img_obj, 0, 0, $gray);
    	//放入商品图片
    	imagecopyresampled($img_obj, $goods_obj, 0, 0, 0, 0, $new_goods_w, $new_goods_h, $goods_w, $goods_h);
    	//放入二维码图片
    	imagecopyresampled($img_obj, $qr_obj, 240, 420, 0, 0, $new_qr_w, $new_qr_h, $qr_w, $qr_h);
    	//加入商品名称
    	$name_size = 20;
    	$price_size = 15;
    	$font = $root.$ds.'extend'.$ds.'QRcode'.$ds.'simfang.ttf';
    	$black = imagecolorallocate($img_obj, 0, 0, 0);
    	$red = imagecolorallocate($img_obj, 217, 16, 20);
    	$sub_name_1 = $code_name;
    	$sub_name_2 = '';
    	if(strlen($code_name) > 20){
    		$sub_name_1 = mb_strimwidth($code_name, 0, 18, '','utf-8');
    		$sub_name_2 = mb_strimwidth($code_name, iconv_strlen($sub_name_1, 'utf-8'), 18, '...','utf-8');
    		$name_size = 18;
    	}
    	imagettftext($img_obj, $name_size, 0, 20, 440, $black, $font, $sub_name_1);
    	imagettftext($img_obj, $name_size, 0, 20, 470, $black, $font, $sub_name_2);
    	$ext = 'jpg';
    	imagepng($img_obj, $img_dir.$share_name);
    	//转base64
    	$img_base64 = 'data:image/'.$ext.';base64,';
    	if($fp = fopen($img_dir.$share_name, 'rb', 0)){
    		$fp_info = fread($fp, filesize($img_dir.$share_name));
    		fclose($fp);
	    	$img_base64 .= chunk_split(base64_encode($fp_info));
    	}

    	imagedestroy($qr_obj);
    	imagedestroy($goods_obj);
    	imagedestroy($img_obj);

    	return ['code' => '1', 'data' => ['img' => 'http://'.$url.'/goodsShare/'.$share_name, 'img_64' => $img_base64]];

	}

	function createImgName($type, $ext = ''){
    	//二维码图片
    	if($type == 'qrcode'){
    		$name = 'QR_'.$ext.'.png';
    	}elseif($type == 'share'){
    		$name = 'FX_'.time().'.'.$ext;
    		// $name = 'FX_'.time().'.png';
    	}
    	return $name;
    }
}