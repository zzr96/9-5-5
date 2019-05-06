<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


function jifen($uid, $e, $reason)
{
    $data['uid'] = $uid;
    $data['jine'] = $e;
    $data['reason'] = $reason;
    $data['date'] = time();
    $do = db("user_jifen")->insert($data);
    if ($do) {
        $user = db("user")->where("id=" . $uid)->find();
        $da['id'] = $uid;
        $da['jifen'] = $user['jifen'] + $e;
        return db("user")->update($da);
    }
}

function sql()
{
    return db()->getLastSql();
}

function count_size($bit)
{
    $type = array('Bytes', 'KB', 'MB', 'GB', 'TB');
    for ($i = 0; $bit >= 1024; $i++)//单位每增大1024，则单位数组向后移动一位表示相应的单位
    {
        $bit /= 1024;
    }
    return (floor($bit * 100) / 100) . $type[$i];//floor是取整函数，为了防止出现一串的小数，这里取了两位小数
}

function wstr($str, $start = 0, $length, $charset = "utf-8", $suffix = false)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
    } else {
        $re ['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re ['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re ['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re ['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re [$charset], $str, $match);
        $slice = join("", array_slice($match [0], $start, $length));
    }
    return $suffix ? $slice . '...' : $slice;
}

function getDes($content, $num)
{
    if ($num == 0) {
        return $content;
    }

    $content = strip_tags($content);
    $order = array(
        "'\r\n'",
        "'\n'",
        "'\r'",
        "'\s+'",
        "'&nbsp;'"
    );
    $replace = array(
        '',
        '',
        '',
        ' '
    );
    $content = preg_replace($order, $replace, $content);
    $content = wstr($content, 0, $num, 'utf-8');
    $content = str_replace('"', "'", $content);
    return $content;
}

function wdate($the_time)
{
    $now_time = date("Y-m-d H:i:s", time());
    $now_time = strtotime($now_time);
    $show_time = $the_time;
    $dur = $now_time - $show_time;
    if ($dur < 0) {
        return $the_time;
    } else {
        if ($dur < 60) {
            return $dur . '秒前';
        } else {
            if ($dur < 3600) {
                return floor($dur / 60) . '分钟前';
            } else {
                if ($dur < 86400) {
                    return floor($dur / 3600) . '小时前';
                } else {
                    if ($dur < 259200) { // 3天内
                        return floor($dur / 86400) . '天前';
                    } else {
                        return date("m月d日", $the_time);
                    }
                }
            }
        }
    }
}

/**
 * 字符串截取，支持中文和其他编码
 * @static
 * @access public
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * @return string
 */
function delhtml($str)
{   //HTMLǩ
    $st = -1; //ʼ
    $et = -1; //
    $stmp = array();
    $stmp[] = "&nbsp;";
    $len = strlen($str);
    for ($i = 0; $i < $len; $i++) {
        $ss = substr($str, $i, 1);
        if (ord($ss) == 60) { //ord("<")==60
            $st = $i;
        }
        if (ord($ss) == 62) { //ord(">")==62
            $et = $i;
            if ($st != -1) {
                $stmp[] = substr($str, $st, $et - $st + 1);
            }
        }
    }
    $str = str_replace($stmp, "", $str);
    $str = str_replace(chr(10), "", $str);
    $str = str_replace(chr(13), "", $str);
    return $str;
}


/**
 * +----------------------------------------------------------
 * 字符串截取，支持中文和其他编码
 * +----------------------------------------------------------
 * @static
 * @access public
 * +----------------------------------------------------------
 * @param string $str 需要转换的字符串
 * @param string $start 开始位置
 * @param string $length 截取长度
 * @param string $charset 编码格式
 * @param string $suffix 截断显示字符
 * +----------------------------------------------------------
 * @return string
 * +----------------------------------------------------------
 */
function msubstr($str, $start = 0, $length, $charset = "utf-8", $suffix = true)
{
    if (function_exists("mb_substr"))
        $slice = mb_substr($str, $start, $length, $charset);
    elseif (function_exists('iconv_substr')) {
        $slice = iconv_substr($str, $start, $length, $charset);
        if (false === $slice) {
            $slice = '';
        }
    } else {
        $re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        preg_match_all($re[$charset], $str, $match);
        $slice = join("", array_slice($match[0], $start, $length));
    }
    return $suffix ? $slice : $slice;
}

function getImg($str, $num)
{
    $preg_img = "#<img.*src=\"(.*)\".*/>#iUs";
    preg_match_all($preg_img, $str, $arr_img);

    foreach ($arr_img[1] as $k => $v) {
        if ($k >= $num) {
            unset($arr_img[1][$k]);
        }
    }

    return $arr_img[1];
}

/**
 * zouhao619@gmail.com    zouhao
 * 一些验证方法
 */
/**
 * 是否是手机号码
 *
 * @param string $phone 手机号码
 * @return boolean
 */
function is_phone($phone)
{
    if (strlen($phone) != 11 || !preg_match('/^1[3|4|5|8][0-9]\d{4,8}$/', $phone)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 验证字符串是否为数字,字母,中文和下划线构成
 * @param string $username
 * @return bool
 */
function is_check_string($str)
{
    if (preg_match('/^[\x{4e00}-\x{9fa5}\w_]+$/u', $str)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为一个合法的email
 * @param sting $email
 * @return boolean
 */
function is_email($email)
{
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为一个合法的url
 * @param string $url
 * @return boolean
 */
function is_url($url)
{
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为一个合法的ip地址
 * @param string $ip
 * @return boolean
 */
function is_ip($ip)
{
    if (ip2long($ip)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为整数
 * @param int $number
 * @return boolean
 */
function is_number($number)
{
    if (preg_match('/^[-\+]?\d+$/', $number)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为正整数
 * @param int $number
 * @return boolean
 */
function is_positive_number($number)
{
    if (ctype_digit($number)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为小数
 * @param float $number
 * @return boolean
 */
function is_decimal($number)
{
    if (preg_match('/^[-\+]?\d+(\.\d+)?$/', $number)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为正小数
 * @param float $number
 * @return boolean
 */
function is_positive_decimal($number)
{
    if (preg_match('/^\d+(\.\d+)?$/', $number)) {
        return true;
    } else {
        return false;
    }
}

/**
 * 是否为英文
 * @param string $str
 * @return boolean
 */
function is_english($str)
{
    if (ctype_alpha($str))
        return true;
    else
        return false;
}

/**
 * 是否为中文
 * @param string $str
 * @return boolean
 */
function is_chinese($str)
{
    if (preg_match('/^[\x{4e00}-\x{9fa5}]+$/u', $str))
        return true;
    else
        return false;
}

/**
 * 判断是否为图片
 * @param string $file 图片文件路径
 * @return boolean
 */
function is_image($file)
{
    if (file_exists($file) && getimagesize($file === false)) {
        return false;
    } else {
        return true;
    }
}

/**
 * 是否为合法的身份证(支持15位和18位)
 * @param string $card
 * @return boolean
 */
function is_card($card)
{
    if (preg_match('/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/', $card) || preg_match('/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{4}$/', $card))
        return true;
    else
        return false;
}

/**
 * 验证日期格式是否正确
 * @param string $date
 * @param string $format
 * @return boolean
 */
function is_date($date, $format = 'Y-m-d')
{
    $t = date_parse_from_format($format, $date);
    if (empty($t['errors'])) {
        return true;
    } else {
        return false;
    }
}


function getitem($content, $startstr, $endstr)
{
    if ($content == '') return '';
    $startstr = regexencode($startstr);
    $endstr = regexencode($endstr);
    $regexstr = "/" . $startstr . "([\s\S]*?)" . $endstr . "/";
    $regexnum = preg_match_all($regexstr, $content, $matches);
    //	dump($matches);
    return $matches[1];
}

function getcontent($content, $star, $end)
{
    $content = getmidfield($content, $star, $end);
    //$content=strip_tags($content,"<a></a><br/><p></p><img><label><div><li>");
    return $content;
}

function getmidfield($content, $startstr, $endstr)
{
    if ($content == '') return '';
    $startstr = regexencode($startstr);
    $endstr = regexencode($endstr);
    $regexstr = "/" . $startstr . "([\s\S]*?)" . $endstr . "/";
    $regexnum = preg_match($regexstr, $content, $matches);
    $v = $matches[1];
    $v = trim($v);
    return $v;
}

function regexencode($str)
{
    $str = str_replace("\\", "\\\\", $str);
    $str = str_replace(".", "\.", $str);
    $str = str_replace("[", "\[", $str);
    $str = str_replace("]", "\]", $str);
    $str = str_replace("(", "\(", $str);
    $str = str_replace(")", "\)", $str);
    $str = str_replace("?", "\?", $str);
    $str = str_replace("+", "\+", $str);
    $str = str_replace("*", "\*", $str);
    $str = str_replace("^", "\^", $str);
    $str = str_replace("{", "\{", $str);
    $str = str_replace("}", "\}", $str);
    $str = str_replace("$", "\$", $str);
    $str = str_replace("|", "\|", $str);
    $str = str_replace("/", "\/", $str);
    $str = str_replace("\(\*\)", "[\s\S]*?", $str);
    return $str;
}

function Post($url, $post = null)
{
    $context = array();
    if (is_array($post)) {
        ksort($post);

        $context['http'] = array(
            'timeout' => 60,
            'method' => 'POST',
            'content' => http_build_query($post, '', '&'),
        );
    }

    return file_get_contents($url, false, stream_context_create($context));
}

function wrequest($url, $params = array(), $method = 'POST', $multi = false, $extheaders = array())
{
    if (!function_exists('curl_init')) exit('Need to open the curl extension');
    $method = strtoupper($method);
    $ci = curl_init();
    curl_setopt($ci, CURLOPT_USERAGENT, 'PHP-SDK OAuth2.0');
    //  curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, 3);
    curl_setopt($ci, CURLOPT_TIMEOUT, 60);
    curl_setopt($ci, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ci, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ci, CURLOPT_HEADER, false);
    $headers = (array)$extheaders;
    switch ($method) {
        case 'POST':
            curl_setopt($ci, CURLOPT_POST, TRUE);
            if (!empty($params)) {
                if ($multi) {
                    foreach ($multi as $key => $file) {
                        $params[$key] = '@' . $file;
                    }
                    curl_setopt($ci, CURLOPT_POSTFIELDS, $params);
                    $headers[] = 'Expect: ';
                } else {
                    curl_setopt($ci, CURLOPT_POSTFIELDS, http_build_query($params));
                }
            }
            break;
        case 'DELETE':
        case 'GET':
            $method == 'DELETE' && curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
            if (!empty($params)) {
                $url = $url . (strpos($url, '?') ? '&' : '?')
                    . (is_array($params) ? http_build_query($params) : $params);
            }
            break;
    }
    curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE);
    curl_setopt($ci, CURLOPT_URL, $url);
    $headers = array("content-type: application/x-www-form-urlencoded; charset=UTF-8");
    if ($headers) {
        curl_setopt($ci, CURLOPT_HTTPHEADER, $headers);
    }

    $response = curl_exec($ci);
    curl_close($ci);
    return $response;
}

/**
 * 通用化API接口数据输出
 * @param int $status 业务状态码
 * @param string $message 信息提示
 * @param [] $data  数据
 * @param int $httpCode http状态码
 * @return array
 */
function show($status, $message, $data = [], $httpCode = 200)
{

    $data = [
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ];

    return json($data, $httpCode);
}

/**
 * 自写自用日志记录
 * @param $data
 */
function mylog($data)
{
    $data = json_encode($data, JSON_UNESCAPED_UNICODE);

    $data = str_replace(",", "\r\n", $data);

    $data = str_replace("}", "}\r\n", $data);

    file_put_contents("../mylog/mylog.txt", date('Y-m-d H:i:s', time()) . "\r\n" . $data . "\r\n" . '-----------------------------------------' . "\r\n", FILE_APPEND);


    // $data=json_encode($data);
    // file_put_contents("../mylog/mylog.txt", date('Y-m-d H:i:s',time())."\r\n".$data."\r\n".'-----------------------------------------'."\r\n", FILE_APPEND);
}

/**
 * 二维数组按照子数组值进行排序
 * @param $arr：需排序数组 $order:按照排序的值
 * @param $order
 * @return mixed
 */
function listorder($arr, $order)
{
    array_multisort(array_column($arr, $order), SORT_DESC, $arr);
    return $arr;

}

/**
 * 自动下架过期项目
 */
function closeXiangmu()
{
    $time = time();
    $expiredXiangmu = think\Db::name('xiangmu')->where('gq_time', '<', $time)->select();
    foreach ($expiredXiangmu as $xm) {
        think\Db::name('xiangmu')->where(['id' => $xm['id']])->update(['status' => 5]);
        think\Db::name('index_show')->where(['module' => 1, 'itemid' => $xm['id']])->delete();
    }
}

/**
 * 薪酬支付7小时
 */
function payXinchou()
{
    $time = time();
    $expiredXiangmu = think\Db::name('xiangmu')->where(['status' => 2, 'xc_paytime' => 0])->select();
    foreach ($expiredXiangmu as $xm) {
        if (($xm['pp_time'] + 7 * 3600) < $time) {
            think\Db::name('xiangmu')->where(['id' => $xm['id']])->update(['status' => 5]);
            //TODO 判断用户是否支付押金，已经支付的话归还押金，写入余额变动记录表
            if ($xm['yj_paytime']) {
                $yajin = think\Db::name('pay_log')->where(['uid' => $xm['sqr_uid'], 'xmid' => $xm['id'], 'status' => 1])->find();
                db('member')->where(['uid' => $xm['sqr_uid']])->setInc('balance', $yajin['money']);
                db('balance_log')->insert([
                    'uid' => $xm['sqr_uid'],
                    'amount' => $yajin['money'],
                    'mark' => '项目超时押金返还',
                    'type' => 1,
                    'order_no' => $yajin['order_no'],
                    'createtime' => $time,
                ]);
            }
        }
    }
}

/**
 * 押金支付7小时
 */
function payYajin()
{
    $time = time();
    $expiredXiangmu = think\Db::name('xiangmu')->where(['status' => 2, 'yj_paytime' => 0])->select();
    foreach ($expiredXiangmu as $xm) {
        if (($xm['pp_time'] + 7 * 3600) < $time) {
            think\Db::name('xiangmu')->where(['id' => $xm['id']])->update(['status' => 0]);
            //TODO 判断发布者是否支付薪酬，已经支付的话归还薪酬，写入余额变动记录表
            if ($xm['xc_paytime']) {
                $xinchou = think\Db::name('pay_log')->where(['uid' => $xm['uid'], 'xmid' => $xm['id'], 'status' => 1])->find();
                db('member')->where(['uid' => $xm['uid']])->setInc('balance', $xinchou['money']);
                db('balance_log')->insert([
                    'uid' => $xm['uid'],
                    'amount' => $xinchou['money'],
                    'mark' => '项目超时薪酬返还',
                    'type' => 1,
                    'order_no' => $xinchou['order_no'],
                    'createtime' => $time,
                ]);
            }
        }
    }
}

function createOrderNo()
{
    $no = date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    return $no;
}

//发送生日祝福
function sendBirthdayMsg()
{
    $day = date('Y-m-d');
    $user = think\Db::name('member')->where(['birthday' => $day, 'vip' => 1])->select();
    foreach ($user as $v) {
        \app\apis\controller\SendSms::sendSms();
    }
}

//自动判断会员到期
function vipEnd()
{
    $dayStart = strtotime(date('Y-m-d'));
    $user = think\Db::name('member')->where(['vip'=>['>',0],'vip_end'=>['<=', $dayStart]])->select();
    foreach ($user as $v) {
        db('member')->where('uid', $v['uid'])->update(['vip' => 0]);
    }
}

function hisDate($time)
{
    if ($time > 0) {
        $h = floor($time / 3600);
        $i = floor(($time - $h * 3600) / 60);
        $s = $time - $h * 3600 - $i * 60;
        return $h . ':' . $i . ':' . $s;
    } else {
        return 0;
    }

}

//单个设备下发通知消息
/**
 * @param $uid 用户UID
 * @param $title 消息标题
 * @param $content 消息内容
 * @param $custom
 * @return array|mixed
 */
function xingePushSingleDeviceNotification($uid, $title, $content,$custom = [])
{
    require_once __DIR__ . '/../extend/Xinge/XingeApp.php';
    $token = db('member')->where('uid', $uid)->value('xinge_token');
    $setting = db('user_setting')->where('uid', $uid)->find();
    if (!$setting['message_notice']) {
        return;
    }
    $push = new \XingeApp(config('xinge.accessId'), config('xinge.secretKey'));
    $mess = new \Message();
    $mess->setType(\Message::TYPE_NOTIFICATION);
    $mess->setTitle($title);
    $mess->setContent($content);
    $mess->setExpireTime(86400);
    //$style = new Style(0);
    #含义：样式编号0，响铃，震动，不可从通知栏清除，不影响先前通知
    $style = new \Style(0, $setting['sound'], $setting['shock'], 0, 0);
    //$action = new \ClickAction();
    //$action->setActionType(ClickAction::TYPE_URL);
    //$action->setUrl("http://xg.qq.com");
    #打开url需要用户确认
    //$action->setComfirmOnUrl(1);
    $mess->setStyle($style);
    //$mess->setAction($action);
    $mess->setCustom($custom);
    $startArr = explode(':',$setting['start']);
    $startH = isset($startArr[0]) ? $startArr[0] : 0;
    $startM = isset($startArr[1]) ? $startArr[1] : 0;
    $endArr = explode(':',$setting['end']);
    $endH = isset($endArr[0]) ? $endArr[0] : 23;
    $endM = isset($endArr[1]) ? $endArr[1] : 59;
    $acceptTime1 = new \TimeInterval(intval($startH), intval($startM), intval($endH), intval($endM));
    $mess->addAcceptTime($acceptTime1);
    $ret = $push->PushSingleDevice($token, $mess);
    return ($ret);
}
/**
 * method: 给数组中图片地址添加域名前缀
 * param: $arr - {array}要添加域名前缀的图片地址数组
 * param: $prefix - {string} 域名前缀
 */
function add_prefix($arr,$prefix){
    if(empty($arr)){
        return '';
    }
    if(!is_array($arr)){
        return $prefix . $arr ;
    }
    foreach ($arr as $k=>&$v){
        $v = $prefix . $v;
    }
    return $arr;
}




    /**
     * 发送邮件
     * @param  string $address 需要发送的邮箱地址 发送给多个地址需要写成数组形式
     * @param  string $subject 标题
     * @param  string $content 内容
     * @return boolean       是否成功
     */
function send_mail($toemail, $name, $subject = '', $body = '',$attachment = null) {
    $mail = new \PHPMailer\PHPMailer();           //实例化PHPMailer对象
    $mail->CharSet = 'UTF-8';           //设定邮件编码，默认ISO-8859-1，如果发中文此项必须设置，否则乱码
    $mail->IsSMTP();                    // 设定使用SMTP服务
    $mail->SMTPDebug = 0;               // SMTP调试功能 0=关闭 1 = 错误和消息 2 = 消息
    $mail->SMTPAuth = true;             // 启用 SMTP 验证功能
    $mail->SMTPSecure = 'ssl';          // 使用安全协议
    $mail->Host = config('site.smtEmail')['Host']; // SMTP 服务器
    $mail->Port = config('site.smtEmail')['Port'];                  // SMTP服务器的端口号
    $mail->Username = config('site.smtEmail')['Username'];    // SMTP服务器用户名
    $mail->Password = config('site.smtEmail')['Password'];     // SMTP服务器密码//这里的密码可以是邮箱登录密码也可以是SMTP服务器密码
    $mail->SetFrom(config('site.smtEmail')['Username'], '游戏大王APP');
    $replyEmail = '';                   //留空则为发件人EMAIL
    $replyName = '';                    //回复名称（留空则为发件人名称）
    $mail->AddReplyTo($replyEmail, $replyName);
    $mail->Subject = $subject;
    $mail->MsgHTML($body);
    $mail->AddAddress($toemail, $name);
    if (is_array($attachment)) { // 添加附件
        foreach ($attachment as $file) {
            is_file($file) && $mail->AddAttachment($file);
        }
    }
    return $mail->Send() ? true : $mail->ErrorInfo;
}
function object_array($array) {
    if(is_object($array)) {
        $array = (array)$array;
    }
    if(is_array($array)) {
        foreach($array as $key=>$value) {
            $array[$key] = object_array($value);
        }
    }
    return $array;
}

