<?php
namespace app\admin\controller;

use think\Controller;


class Upload extends Base
{
    //上传图片窗口
    public function img()
    {
        return view();
    }

    // 执行上传图片
    public function up_do()
    {
        $path = input('path', 'image');
        $image = \think\Image::open(request()->file('file'));
//        $image->thumb(600, 600, \think\Image::THUMB_FILLED);
        $savePath = './uploads/' . $path . '/' . date("Ymd") . "/";
        $saveName = uniqid() . '.jpg';
        if (!is_dir($savePath)) {
            mkdir($savePath, 0777, true);
        }
        $image->save($savePath . $saveName);
        $url = $savePath . $saveName;
        $ret['pic'] = str_replace("./", "/", $url);
        $ret['code'] = 0;
        return $ret;
    }
}