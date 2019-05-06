<?php
namespace app\apis\controller;

use think\Controller;

class Com 
{
	//验证手机号
    function is_phone($phone){
	    if (strlen ( $phone ) != 11 || ! preg_match ( '/^1[3|4|5|7|8][0-9]\d{4,8}$/', $phone )) {
	      return false;
	    } else {
	      return true;
	    }
  	}

  	//产生随机字符串
	 function randomkeys($length){   
       $key='';
       $pattern = '1234567890abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLOMNOPQRSTUVWXYZ';  
        for($i=0;$i<$length;$i++)   
        {   
            $key .= $pattern{mt_rand(0,35)};  
        }   
        return $key;
    }
}	