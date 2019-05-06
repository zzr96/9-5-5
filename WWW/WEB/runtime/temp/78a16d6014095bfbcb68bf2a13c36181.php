<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\shop\index.html";i:1552372368;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
    <div class='bd'>
        <div class="v_tishi">
            <div style='float:left;'>您可通过操作查看按钮了解申请店铺详情并进行相应的操作!</div>
            <div style='clear:both;'></div>
        </div>
         <?php
            if(!$list){
        ?>
        <div class="none_list">暂无店铺申请记录</div>
        <?php
            }else{
        ?>
        <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
            <tr class="h">
                <td valign="middle">ID</td>
                <td>姓名</td>
                <td>类型</td>
                <td>店铺名称</td>
                <td>电话</td>
                <td>创建时间</td>
                <td>状态</td>
                <td>操作</td>
            </tr>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><?php echo $vo['id']; ?></td>
                <td><b><?php echo $vo['name']; ?></b></td>
                <td><?php echo $vo['cat_name']; ?></td>
                <td><?php echo $vo['shop_name']; ?></td>
                <td><?php echo $vo['mobile']; ?></td>
                <td><?php echo $vo['dateline']; ?></td>
                <td><?php if($vo['status'] == 1): ?>未审核<?php elseif($vo['status'] == 2): ?>审核通过<?php else: ?>拒绝<?php endif; ?></td>
                <td>
                    <a href="<?php echo url('edit'); ?>?id=<?php echo $vo['id']; ?>&nav=查看" class="btn btn-success">查看</a>
                    <a href="javascript:;" class="btn btn-danger" onclick="del('<?php echo $vo['id']; ?>');">删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
            <div class="page"><?php echo $page; ?></div>
        <?php
            }
        ?>
    </div>
</div>
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
	
<script type="text/javascript">
    function del(id){
        if (!confirm('此操作不能撤销，确定要删除该申请记录?')) {
            return false;
        }
        $.ajax({
            url: "<?php echo url('del'); ?>",
            data: "id=" + id,
            type: 'post',
            success: function (ret) {
                if (ret.code == 1) {
                    tishi('success', ret.msg, window.location.href)
                }else{
                    tishi('error',ret.msg);
                }
            }
        })
    }
</script>