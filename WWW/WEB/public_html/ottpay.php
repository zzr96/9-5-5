<?php


header("Content-type: text/html; charset=utf-8");

function sendRequest($data, $url)
{
    $resp_data = array();
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', 'Content-length:' . strlen($data)));
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLINFO_HEADER_OUT, true);
    $resp = curl_exec($ch);
    $resp_data = urldecode($resp);
    curl_close($ch);
    return $resp_data;
}

;


$data_array = array();
$data_array['amount'] = '1';
$data_array['biz_type'] = 'ALIPAY';
$data_array['operator_id'] = 'ON00003765'; //using your 10-digital operator number provided by OTTPAY;
$data_array['order_id'] = "ON" . date("YmdHis"); //'TEST20171130175453';
$data_array['call_back_url'] = "http://www.ottpay.com/index.php?option=com_j2store&view=callback&method=payment_ottpay"; //using your call back url;
$temp_data_array = $data_array;
ksort($temp_data_array);
$data_str = implode(array_values($temp_data_array));
$data_md5 = strtoupper(md5($data_str));
$user_key = '10000000004695'; //using your Sign Key provided by OTTPAY;
$aesKeyStr = strtoupper(substr(md5($data_md5 . $user_key), 8, 16));
$data_json = json_encode($data_array);

$encrypted_data = Security::encrypt($data_json, $aesKeyStr);


$params_array = array();
$params_array['action'] = 'ACTIVEPAY';
$params_array['version'] = '1.0';
$params_array['merchant_id'] = 'ON00003765'; //using your Merchant ID provided by OTTPAY;
$params_array['data'] = $encrypted_data;
$params_array['md5'] = $data_md5;
$params_json = json_encode($params_array, JSON_UNESCAPED_UNICODE);
$resp_data = sendRequest($params_json, 'https://frontapi.ottpay.com:443/process');

$resp_arr = (array)json_decode($resp_data, true);
$aesKeyStr = strtoupper(substr(md5($resp_arr['md5'] . $user_key), 8, 16));
$decrypted_data = Security::decrypt($resp_arr['data'], $aesKeyStr);

$return_data_arr = (array)json_decode($decrypted_data, true);
$qrCode_url = $return_data_arr['code_url'];

echo 'response qrCode_url = ' . $qrCode_url;


class Security
{
    public static function encrypt($input, $key)
    {
        $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
        $input = Security::pkcs5_pad($input, $size);
        $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
        $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
        mcrypt_generic_init($td, $key, $iv);
        $data = mcrypt_generic($td, $input);
        mcrypt_generic_deinit($td);
        mcrypt_module_close($td);
        $data = utf8_encode(base64_encode($data));
        return $data;
    }


    private static function pkcs5_pad($text, $blocksize)
    {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }

    public static function decrypt($sStr, $sKey)
    {
        $decrypted = mcrypt_decrypt(MCRYPT_RIJNDAEL_128, $sKey, base64_decode(str_replace(" ", "+", $sStr)), MCRYPT_MODE_ECB);
        $dec_s = strlen($decrypted);
        $padding = ord($decrypted[$dec_s - 1]);
        $decrypted = substr($decrypted, 0, -$padding);
        return $decrypted;
    }
}
