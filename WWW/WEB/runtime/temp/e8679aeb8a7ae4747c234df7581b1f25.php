<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:91:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\system\news_edit.html";i:1553562354;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
<style>
    .v_col .v_bd .item .size{margin-top: 5px;}
</style>
<div class="v_bd"><!--v_bd-->
    <form action="<?php echo url('news_edit_do'); ?>" method="post" id="form">
        <input type="hidden" name="id" value="<?php echo $find['id']; ?>" />
        <div class="item">
            <label>标题：</label>
            <div class="i_bd">
                <input type="text" id="title" name="title" class="edit_input"  value="<?php echo $find['title']; ?>" size="60"/> <span>(*必填)</span>
            </div>
        </div>
        <div class="item">
            <label>图片：</label>

            <div class="i_bd">
                <div class="chanpin_pic_list" style='height:140px;'>

                    <div class="iitem">
                        <input name="img" id="picval_5" type="hidden" value="<?php echo $find['img']; ?>"/>

                        <div class="pic" id="pic_5">
                            <a onclick="addpic(5);" title="上传图片">
                                <?php if($find['img']): ?>
                                <img id="pic_yl_5" width="100" height="100"
                                     src="/public_html<?php echo $find['img']; ?>"/></a>
                                <?php else: ?>
                                <img id="pic_yl_5" width="100" height="100"
                                     src="/static/manage/images/add_fpic.gif"/>
                                <?php endif; ?>
                            </a>
                        </div>
                        <div id='pic_btn_5' style='margin-top:10px;text-align:center;display:none;'>
                            <a onclick='del_pic(5)' class="btn btn-success">删除</a>
                        </div>
                    </div>

                </div>
                <div style='margin-top:10px;color:red;'>请上传图片尺寸为 750px x 430px 的图片!</div>
            </div>
        </div>
        <div class="item">
            <label>内容：</label>

            <div class="i_bd">
                <textarea id="content" name="content" style="width:700px;height:300px;" cols="" rows="" ><?php echo $find['content']; ?></textarea>
            </div>
        </div>
        <div class="item">
            <label></label>
            <div class="i_bd">
                <input name="" type="button" id="form_btn" class="btn btn-success" value="提交"/>
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
    $(function () {
        $("#form_btn").click(function () {
            //$("#form").submit();
            $("#form").ajaxSubmit(function (txt) {
                if (txt.code == 1) {
                    tishi('success', txt.msg, '<?php echo url("news"); ?>');

                } else {
                    tishi('error', txt.msg);
                }
            });
        })
    });
    function addpic(id) {
        var html = '<iframe frameborder="0" marginheight="0" marginwidth="0" height="100" width="100%" src="<?php echo url("/admin/upload/img"); ?>?pid=' + id + '" ></iframe>';
        tanchuang("apppic", 700, '上传图片', html)
    }

    function addpic_do(pic, pid) {
        re_tanchuang('apppic');
        $("#pic_yl_" + pid).attr('src', '/public_html'+pic);
        $("#picval_" + pid).val(pic);
        $('#pic_btn_' + pid).show();
    }

    function del_pic(id) {
        $('#pic_yl_' + id).attr('src', '/static/manage/images/add_fpic.gif');
        $('#picval_' + id).val('');
        $('#pic_btn_' + id).hide();
    }
</script>