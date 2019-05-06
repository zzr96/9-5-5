<?php
namespace app\client\controller;
use think\Controller;
class Com extends Controller
{
     /**
     * 图片上传
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param vachar   $file  文件
     */
    public function upload()
    {
        $file = $this->request->file('file');
        $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads' . DS . 'image');
        if ($info) {
            $url = 'uploads' . DS . 'image' . DS . $info->getSaveName();
            return json(['code' => 200, 'msg' => '上传成功', 'url' => $url]);
        } else {
            //上传失败获取错误信息
            return json(['code' => 400, 'msg' => $file->getError()]);
        }
    }
    /**
     * 发送短信验证码
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $phone  手机号
     */
    public function sendSmsCode()
    {
        $phone = input('mobile');
        $code = mt_rand(1000, 9999);
        if(empty($phone)){
            return json(['code'=>400,'msg'=>'请输入手机号']);
        }
        //        $content = '您的验证码是：' . $code . '。请不要把验证码泄露给其他人。';
        //        $url = "http://106.ihuyi.com/webservice/sms.php?method=Submit&account=" . config('sms.appid') . "&password=" . config('sms.appkey') . "&mobile=" . $phone . "&content=" . $content;
        //        $result = file_get_contents($url);
        //        simplexml_load_string($result);
        $content='您的注册验证码为：'.$code.'。验证码有效期为5分钟，请尽快填写！';
        $url="http://106.ihuyi.com/webservice/sms.php?method=Submit&account=cf_huke&password=wyx037798&mobile=".$phone."&content=".$content;
        $result = file_get_contents($url);
        $xml = simplexml_load_string($result);
        $tt=$xml->msg;
        $data['mobile'] = $phone;
        $data['code'] = $code;
        $data['addtime'] = time();
        $re = db('sms')->insertGetId($data);
        if ($re) {
            return json(['code' => 200, 'msg' => '短信发送成功','code'=>$code]);
        }else{
            return json(['code' => 400, 'msg' => '发送失败']);
        }
    }
     /**
     * 发送短信验证码
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $kd_num 物流单号
     * @param int   $kd_name 快递名称
     */
    public function logistics(){
        /*$order_id=input('order_id');
        if(empty($order_id)){
            return json(['code'=>400,'msg'=>'请选择要查询的订单','data'=>'']);
        }
        $kd = db('order')->where('id',$order_id)->field('kd_name,kd_num,order_sn')->find();
        $kds=db('kd')->where('b_name',$kd['kd_name'])->field('name,logo')->find();*/
        $kd_name=input('kd_name');
        $kd_num=input('kd_num');
        //$url="http://www.kuaidi100.com/query?type=".$kd['kd_name']."&postid=".$kd['kd_num'];
        $url="http://www.kuaidi100.com/query?type=".$kd_name."&postid=".$kd_num;
        $data=$this->curl_get($url);
        $data=json_decode($data);
        $data=$data->data;
        foreach ($data as $key => $value){
            $data[$key]=(array)$value;
        }
        print_r($data);
        /*$datas['kd_num']=$kd['kd_num'];
        $datas['name']=$kds['name'];
        $datas['logo']=$kds['logo'];
        $datas['order_sn']=$kd['order_sn'];
        if($data){
            return json(['code'=>200,'msg'=>'获取成功！','data'=>$data,'datas'=>$datas]);
        }else{
            return json(['code'=>400,'msg'=>'暂无数据！','data'=>'']);
        }*/
    }
    public function curl_get($url){
        $ch=curl_init($url);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        $content=curl_exec($ch);
        curl_close($ch);
        return($content);
    }
    /**
     * 关于我们
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     */
    public function about(){
        $arr=db('about')->field('copyright,img')->where('id=1')->find();
        if($arr){
            return json(['code' => 200, 'msg' => '获取成功','data'=>$arr]);
        }else{
             return json(['code' => 400, 'msg' => '获取失败']);
        }
    }
    /**
     * 发送短信验证码
     * @param string $msg   提示信息
     * @param mixed $arr  要返回的数据
     * @param int   $code   错误码，默认为200
     * @param int   $uid   用户id
     * @param txt   $content  留言
     * @param vachar   $img  图片路径
     * @param vachar   mark  反馈类型
     */
    public function feedBack(){
        $data['img']=input('img');
        $data['content']=input('content');
        $data['mark']=input('mark');
        $data['uid']=input('uid');
        $res=db('feedback')->insert($data);
        if($res){
             return json(['code' => 200, 'msg' => '提交成功']);
        }else{
            return json(['code' => 400, 'msg' => '提交失败']);
        }
    }
}