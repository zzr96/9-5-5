<?php
/**
 * Created by PhpStorm.
 * User: 7howe
 * Date: 2018/8/17
 * Time: 17:59
 */

function notify($uid, $title, $content)
{
    $data = [
        'uid' => $uid,
        'title' => $title,
        'content' => $content,
        'createtime' => time()
    ];
    $rs = db('message')->insert($data);
    return $rs;
}

function makeOrder($type, $money)
{
    if (!cookie('?userId')) {
        return false;
    }
    $om = date('YmdHis').rand(1000,9999);
    $data = [
        'uid' => cookie('userId'),
        'type' => $type,
        'order_num' => $om,
        'money' => $money,
        'createtime' => time()
    ];
    $rs = db('user_order')->insert($data);
    if ($rs) {
        return $om;
    }
    makeOrder($type, $money);
}

/**
 * method: 将时间戳转换为具体多久前
 * param: $the_time - {int} 需转换时间戳
 */
function time_tran($the_time)
{
    $now_time = time();
    $dur = $now_time - $the_time;
    switch ($dur)
    {
        case $dur < 0 :
            $dur = $the_time;
            break;
        case 0< $dur && $dur < 60:
            $dur = $dur . '秒前';
            break;
        case 60< $dur && $dur < 3600:
            $dur = floor($dur / 60) . '分钟前';
            break;
        case 3600< $dur && $dur < 86400:
            $dur = floor($dur / 3600) . '小时前';
            break;
        case 86400< $dur && $dur < 2592000:
            $dur = floor($dur / 86400) . '天前';
            break;
        case 2592000 < $dur:
            $dur = floor($dur / 2592000) . '月前';
            break;
    }
    return $dur;
}

//生成唯一邀请码
function create_invite_code() {
    $code = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $rand = $code[mt_rand(0,25)]
        .strtoupper(dechex(date('m')))
        .date('d')
        .substr(time(),-5)
        .substr(microtime(),2,5)
        .sprintf('%02d',mt_rand(0,99));
    for(
        $a = md5( $rand, true ),
        $s = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ',
        $d = '',
        $f = 0;
        $f < 8;
        $g = ord( $a[ $f ] ),
        $d .= $s[ ( $g ^ ord( $a[ $f + 8 ] ) ) - $g & 0x1F ],
        $f++
    );
    return $d;
}


//推广二维码

function create_qrcode($invite_code)
{
    vendor("phpqrcode.phpqrcode");
    $data = 'http://yingtao.tainongnongzi.com/apis/Login/ceshi?invite_code=' . $invite_code;
    $path = ROOT_PATH . "public_html";
    $outfile = "/uploads/qrcode/" . mt_rand().time() . '.jpg';
    $level = 'L';
    $size = 4;
    $QRcode = new \QRcode();
    ob_start();
    $QRcode->png($data, $path . $outfile, $level, $size, 2);
    ob_end_clean();
    return $outfile;
}
