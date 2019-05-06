<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:87:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\system\index.html";i:1553582338;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
        <input name="id" type="hidden" value="<?php echo $view['id']; ?>"/>
        <div class="item">
            <label>LOGO：</label>

            <div class="i_bd">
                <div class="chanpin_pic_list">
                    <div class="iitem">
                        <input name="logo" id="picval_img" type="hidden" value='<?php echo $view['logo']; ?>'/>

                        <div class="pic" id="img">
                            <a onclick="addpic();" class="upload_pic" title="上传图片">
                                <img id='pic_yl_img' src="/public_html<?php echo !empty($view['logo'])?$view['logo']: '/static/manage/images/addpic.gif'; ?>" width="100" height="100"/></a>
                        </div>
                    </div>
                    <br>&nbsp;&nbsp;<span>(推荐大小100px*100px)</span>
                </div>
            </div>
        </div>
        <div class="item">
            <label>网站名字：</label>

            <div class="i_bd">
                <input class="edit_input" name="name" value="<?php echo $view['name']; ?>" style="width:30%;" type="text"/>
            </div>
        </div>
        <div class="item">
                <label>步数兑换：</label>
    
                <div class="i_bd">
                    <input class="edit_input" name="step_money" value="<?php echo $view['step_money']; ?>" style="width:30%;" type="number"/>(如1000步1元)
                </div>
        </div>
        <div class="item">
            <label>App版本号：</label>

            <div class="i_bd">
                <input class="edit_input" name="version" value="<?php echo $view['version']; ?>" style="width:30%;"
                       type="text"/>
            </div>
        </div>
        <div class="item">
            <label>公司名称：</label>

            <div class="i_bd">
                <input class="edit_input" name="gs_name" value="<?php echo $view['gs_name']; ?>" style="width:30%;"
                       type="text"/>
            </div>
        </div>
        <div class="item">
            <label></label>

            <div class="i_bd">
                <a href="javascript:;" id="form_btn" class="btn btn-success">提交</a>
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

<script type="text/javascript" src="/static/manage/editor/kindeditor.js"></script>>
<script>
KindEditor.ready(function (K) {
        window.editor = K.create('#editor_id', {
            afterBlur: function () {
                this.sync();
            }
        });
    });
    $(function () {
        $("#form_btn").click(function () {
            $("#form").ajaxSubmit(function (txt) {
                if (txt.code == 1) {
                    tishi('success', txt.msg, window.location.href)
                } else {
                    tishi('error', txt.msg)
                }
            });
        })


    });
   function addpic() {
        var html = '<iframe frameborder="0" marginheight="0" marginwidth="0" height="100" width="100%" src="<?php echo url("/admin/upload/img"); ?>" ></iframe>';
        tanchuang("apppic_img", 700, '上传图片', html)
    }
    function addpic_do(img, pid) {
        re_tanchuang('apppic_img');
        $("#pic_yl_img").attr('src', '/public_html'+img);
        $("#picval_img").val(img)
    }
</script>
