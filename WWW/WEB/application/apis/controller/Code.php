<?php
namespace app\apis\controller;

use think\Controller;
use QRcode\QRcode;

class Code extends Com
{
	public function make_qrcode($url){
        // import("phpqrcode.phpqrcode", EXTEND_PATH);  //加载Extend文件夹内的   生成二维码类
        //二维码 保存路径
        $savePath = './uploads/' . 'qrcode' . '/' . date("Ymd") . "/";
        //保存文件名（唯一）
        $saveName = uniqid() . '.png';
        //若没有文件夹，则最高权限创建
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        //二维码路径
        $qrcode_path=$savePath.$saveName;
        //生成动作
        \QRcode::png($url,$qrcode_path,1,6);
        //返回 生成的二维码 路径/uploads/qrcode/20190321/5c9366b4c8c11.png
		return str_replace('.','',$savePath).$saveName;
	}
	
	 //合并 两张图片，返回保存后的 web路径；

	 function merge_img($path_1,$path_2){  //原图和 二维码图 路径参数
		// $path_2='http://quxiguan.tainongnongzi.com/public_html/uploads/qrcode/20190321/5c9366b4c8c11.png';
		// $path_1='http://quxiguan.tainongnongzi.com/public_html/upload/1.png';
        //根据图片类型，使用相应的 方法；
        $path_1_img=getimagesize($path_1);
        if($path_1_img['mime']=="image/jpg" || $path_1_img['mime']=="image/jpeg"){
            $image_1 = imagecreatefromjpeg($path_1);
        }
        if($path_1_img['mime']=="image/png"){
            $image_1 = imagecreatefrompng($path_1);
        }
        $image_2 = imagecreatefrompng($path_2);
        // 创建真彩画布
        $image_3 = imageCreatetruecolor(imagesx($image_1),imagesy($image_1));
        // 为真彩画布创建白色背景
        $color = imagecolorallocate($image_3, 255, 255, 255);
        imagefill($image_3, 0, 0, $color);
        // 设置透明
        // imageColorTransparent($image_3, $color);
        // 复制图片一到真彩画布中（重新取样-获取透明图片）
        imagecopyresampled($image_3, $image_1, 0, 0, 0, 0, imagesx($image_1), imagesy($image_1), imagesx($image_1), imagesy($image_1));
        // 与图片二合成
        $image_1_size=getimagesize($path_1); //原图大小
        $x=$image_1_size[0]/2-imagesx($image_2);  //二维码 X坐标起始位置
        $y=$image_1_size[1]*26/30-imagesy($image_2);      //二维码 y坐标起始位置
        imagecopymerge($image_3, $image_2, $x,$y,0,0, imagesx($image_2), imagesy($image_2), 100);
        // 保存路径
        $savePath = './merge_img' . '/' . date("Ymd") . "/";
        //保存文件名（唯一）
        $saveName = uniqid() . '.png';
        //若没有文件夹，则最高权限创建
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $result_path=$savePath.$saveName;
        //保存 合成图片
        imagepng($image_3,$result_path);
		return str_replace('.','',$savePath).$saveName;
	}
	
	//获取分享图片
	function get_img(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>101,'msg'=>'您尚未登陆','data'=>'']);
        }
		$find=db('user')->where('id',$uid)->field('yq_img')->find();
		if($find){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$find['yq_img']]);
		}else{
			return json(['code'=>100,'msg'=>'网络不稳定请稍后再试','data'=>'']);
		}
    }
    
    //获取分享二维码
    function gets_code(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>101,'msg'=>'您尚未登陆','data'=>'']);
        }
        $user=db('user')->where('id',$uid)->field('yq_img')->find();
        if($user['yq_img']==null){
            $url='http://quxiguan.tainongnongzi.com/public_html/quxiguan/register.html?uid='.$uid;
            $path_1='http://quxiguan.tainongnongzi.com/public_html/upload/1.png';
            $path_2=$this->make_qrcode($url);
            $path_2='http://quxiguan.tainongnongzi.com/public_html'.$path_2;
            $img=$this->merge_img($path_1,$path_2);
            db('user')->where('id',$uid)->update(['yq_img'=>$img]);
        }
    }

    //分享明细
    function fx_mx(){
        $uid=input('uid');
        if(empty($uid)){
            return json(['code'=>101,'msg'=>'您尚未登陆','data'=>'']);
        }
        $time=input('time');
        $p=input('p')?input('p'):1;
        $where['uid']=$uid;
        if(empty($time)){
            $where['time']=$time;
        }
        $count=db('user_team')->where($where)->count();
        $limit=15;
        $count_page = ceil($count/$limit);
        $res=db('user_team')->where($where)->field('id,yq_id,dateline')->page($p,$limit)->select();
        foreach($res as $key=>$value){
            $res[$key]['dateline']=date('Y-m-d H:i',$value['dateline']);
        }
        if($res){
            foreach($res as $key=>$value){
                $yq=db('user')->where('id',$value['yq_id'])->field('nickname,is_vip')->find();
                $res[$key]['title']='成功推荐了'.$yq['nickname'];
                $res[$key]['is_vip']=$yq['is_vip'];
            }
        }
        if($res){
			return json(['code'=>200,'msg'=>'获取成功','data'=>$res,'page'=>$count_page]);
		}else{
			return json(['code'=>100,'msg'=>'暂未数据','data'=>'']);
		}
    }
}