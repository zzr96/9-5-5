<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:84:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\shop\edit.html";i:1552372468;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title><?php echo $title; ?></title>
    <script type="text/javascript" src="/static/manage/js/jquery-1.8.1.min.js"></script>
    <script type="text/javascript" src="/static/manage/js/jquery.form.js"></script>
    <script type="text/javascript" src="/static/manage/js/common.js"></script>
    <link rel="stylesheet" type="text/css" href="/static/manage/css/style.css" />
    <!--<link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" />-->
    <script type="text/javascript" src="/static/manage/layui/layer/layer.js"></script>
    <link rel="stylesheet" type="text/css" href="//at.alicdn.com/t/font_514924_p9y50h8mweg919k9.css" />
    <link rel="stylesheet" type="text/css" href="/static/manage/layui/css/layui.css" /> 
    <script type="text/javascript" src="/static/manage/layui/layui.js"></script>
    <link rel="stylesheet" type="text/css" href="http://at.alicdn.com/t/font_624393_mvshhlyudzz4obt9.css"/>
</head>
<body>
<div class="head_container">
    <div class="inner-head">
        <div class="logo"><img src="/static/manage/images/logo.png"/><a href="javascript:;" id="edit_menu">☰</a></div>
        <div class="login"><a href="<?php echo url('index/index'); ?>" class="name">你好,<?php echo $admin['name']; ?></a> |
            <?php
		if(isset($xiaoqu)){
	?>
            <?php echo $xiaoqu['name']; ?> 小区管理 |
            <?php } 
		if(isset($dianpu)){
			echo $dianpu['name']."管理";
		}
	?>
            <a href="<?php echo url('login/out'); ?>" target="_blank">退出登录</a>
        </div>
    </div>
</div>
<script>

</script>
<div class="col">
    <ul class="s_menu" id="s_menu">
			<li class="center"><i class="iconfont icon-homepage"></i>管理中心</li>
			<?php if(is_array($menu) || $menu instanceof \think\Collection || $menu instanceof \think\Paginator): $i = 0; $__LIST__ = $menu;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
				<li><a href="<?php echo url($vo['m']."/".$vo['a']); ?>" <?php if($m==$vo['m']){echo 'class="focus"';} ?>><i class="iconfont <?php echo $vo['icon']; ?>"></i><?php echo $vo['name']; ?></a></li>
			<?php endforeach; endif; else: echo "" ;endif; ?>
			
</ul>
<script>
$("#edit_menu").click(function(){
	var ml=$(".s_menu").css("margin-left")
	if(ml=='0px'){
		$(".s_menu").animate({marginLeft:'-200px'},'fast')
		$(".v_col").animate({marginLeft:'0px'},'fast')
	}else{
		$(".s_menu").animate({marginLeft:'0px'},'fast')
		$(".v_col").animate({marginLeft:'200px'},'fast')
	}
})
$(document).ready(function(){
	layout_init();
	$(window).resize(function() {
 		 layout_init();
	});
});
function layout_init(){
	$("#s_menu").css("height",$(window).height()-62);
	$(".v_col").css("height",$(window).height()-82);
}
</script>
    <div class="v_col"><!--v_col-->
        <div class="v_hd"><!--v_hd-->
            <div class="v_nav"><!--v_nav-->
                <?php if(is_array($nav) || $nav instanceof \think\Collection || $nav instanceof \think\Paginator): $i = 0; $__LIST__ = $nav;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                <span <?php if($a==$vo['a']){echo 'class="focus"';}elseif($vo['a']=='javascript:;'){echo 'class="focus"';} ?>
                ><a href="<?php echo url($vo['a']); ?>"><?php echo $vo['name']; ?></a></span>
                <?php endforeach; endif; else: echo "" ;endif; ?>
            </div><!--v_nav-->
        </div> <!--v_hd-->
<div class="v_bd"><!--v_bd-->
    <form action="<?php echo url('edit_do'); ?>" method="post" id="form">
        <input type='hidden' name='id' value='<?php echo $list['id']; ?>'/>
        <div class="item">
            <label>姓名：</label>

            <div class="i_bd">
                <input type="text" name="name" class="edit_input" size="20" value='<?php echo $list['name']; ?>'  disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>手机号码：</label>

            <div class="i_bd">
                <input type="text" name="mobile" class="edit_input" size="20" value='<?php echo $list['mobile']; ?>'  disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>身份证号：</label>

            <div class="i_bd">
                <input type="text" name="id_card" class="edit_input" size="20" value='<?php echo $list['id_card']; ?>' disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>住址：</label>

            <div class="i_bd">
                <input type="text" name="u_addr" class="edit_input" size="20" value='<?php echo $list['u_addr']; ?>' disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>店铺所属分类：</label>

            <div class="i_bd">
                <input type="text" name="cat_name" class="edit_input" size="20" value='<?php echo $list['cat_name']; ?>' disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>店铺名称：</label>

            <div class="i_bd">
                <input type="text" name="shop_name" class="edit_input" size="20" value='<?php echo $list['shop_name']; ?>' disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>身份证正面：</label>
            <div class="i_bd">
                <div class="chanpin_pic_list">
                    <div class="iitem">
                        <input name="zid_card" id="picval_logo" type="hidden" value='<?php echo $list['zid_card']; ?>'/>
                        <div class="pic" id="logo">
                            <a onclick="addpic_logo();" class="upload_pic" title="上传图片"><img id='pic_yl_logo' src="/public_html<?php echo $list['zid_card']; ?>" width="100" height="100" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="item">
            <label>身份证反面：</label>
            <div class="i_bd">
                <div class="chanpin_pic_list">
                    <div class="iitem">
                        <input name="fid_card" id="picval_logo" type="hidden" value='<?php echo $list['fid_card']; ?>'/>
                        <div class="pic" id="logo">
                            <a onclick="addpic_logo();" class="upload_pic" title="上传图片"><img id='pic_yl_logo' src="/public_html<?php echo $list['fid_card']; ?>" width="100" height="100" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <label>营业执照：</label>
            <div class="i_bd">
                <div class="chanpin_pic_list">
                    <div class="iitem">
                        <input name="license" id="picval_logo" type="hidden" value='<?php echo $list['license']; ?>'/>
                        <div class="pic" id="logo">
                            <a onclick="addpic_logo();" class="upload_pic" title="上传图片"><img id='pic_yl_logo' src="/public_html<?php echo $list['license']; ?>" width="100" height="100" /></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
         <div class="item">
            <label>店铺地址：</label>

            <div class="i_bd">
                <input type="text" name="s_addr" class="edit_input" size="20" value='<?php echo $list['s_addr']; ?>' disabled="disabled"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label></label>
            <div class="i_bd">
                <?php if($list['status'] != 2): ?>
                <a href="javascript:;" onClick="status('<?php echo $list['id']; ?>',2)" class="btn btn-success">审核</a>
                <?php else: ?>
                <a href="javascript:;" onClick="status('<?php echo $list['id']; ?>',3)" class="btn btn-danger">禁用</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div><!--v_bd-->
 </div><!--v_col-->
</div><!--col-->

</body>
<script>
	var func = function (){ 
		$.ajax({ 
			type:'POST', 
			url:'/admin/order/checknew', 
			dataType:'json', 
			success:function(ret){ 
				if(ret.code == 1) {
					playSound();
				}
			}
		});
	}
	var playSound = function () {
		var borswer = window.navigator.userAgent.toLowerCase();
		if ( borswer.indexOf( "ie" ) >= 0 ){
			//IE内核浏览器 
			var strEmbed = '<embed name="embedPlay" src="/uploads/default/voice.wav" autostart="true" hidden="true" loop="false"></embed>';
			if ( $( "body" ).find( "embed" ).length <= 0 ){
				$( "body" ).append( strEmbed );
			}
			var embed = document.embedPlay;
			//浏览器不支持 audion，则使用 embed 播放 	
			embed.volume = 100;
			//embed.play();这个不需要
		} else{
			//非IE内核浏览器
			var strAudio = "<audio id='audioPlay' src='/uploads/default/voice.wav' hidden='true'>";
			if($("#audioPlay").length<=0){
	            $( "body" ).append( strAudio );
	        }
	        var audio = document.getElementById( "audioPlay" );
	        //浏览器支持 audio
        	audio.play();
		}
	}
	setInterval("func()", 10000);
</script>
</html>

<script>
    function status(id, status) {
        var data = 'id=' + id + '&status=' + status;
        var url = '<?php echo url("status"); ?>';
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function (data) {
                if (data.code == 1) {
                    tishi('success', data.msg, '<?php echo url("index"); ?>');
                } else {
                    tishi('error', data.msg);
                }
            }
        })
    }
</script>