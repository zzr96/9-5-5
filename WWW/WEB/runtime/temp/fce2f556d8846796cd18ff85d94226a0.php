<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\goods\index.html";i:1551341318;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
    <div class="v_bd_tag">
        <a href="<?php echo url('index'); ?>" status="all" class="<?php echo input('status')==''&&input('top')==''?'focus':''; ?> status">
            全部商品<span>(<?php echo $total['all']; ?>)</span></a>|
        <a href="<?php echo url('index'); ?>?status=1" class="<?php echo input('status')==1?'focus':''; ?> status">已上架<span>(<?php echo $total['on']; ?>)</span></a>|
        <a href="<?php echo url('index'); ?>?status=2" class="<?php echo input('status')==2?'focus':''; ?> status">已下架<span>(<?php echo $total['off']; ?>)</span></a>|
        <a href="<?php echo url('index'); ?>?top=2" class="<?php echo input('top')==2?'focus':''; ?> status">推荐商品<span>(<?php echo $total['top']; ?>)</span></a>|
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;        
        商品搜索：
        <input id="search" class="edit_input" value="" type="text"/>
        <a href="javascript:;" onClick="searchs();" class="btn btn-success">提交</a><i class="huise">(请输入商品名称)</i>
<!--         <p style='float:right;margin-top:4px;'>
            <a href="<?php echo url('index_list'); ?>" class="btn btn-success">切换查看方式</a>
        </p> -->
        <p style='float:right;margin-top:4px;'>
            <a href="<?php echo url('add'); ?>" class="btn btn-success">添加商品</a>
        </p>
    </div>
    <style>
        .table tr:hover {
            background: #ffffff;
        }
    </style>
    <table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
        <tr>
            <td colspan="5">
                <div class="pic_list">
                    <ul>
                        <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                        <li style="height: 300px;">

                            <a href="javascript:;" class="pic" title="<?php echo $vo['id']; ?>" onclick="opennew(this)"
                               data-url="<?php echo url('edit'); ?>?id=<?php echo $vo['id']; ?>&nav=编辑信息&url=">
                                <img src="/public_html<?php echo $vo['img1']; ?>" align="absmiddle" alt="<?php echo $vo['goods_name']; ?>"/>
                            </a>
                            <p class="pro-name">
                                <a href="javascript:;" onclick="opennew(this)"
                                   data-url="<?php echo url('edit'); ?>?id=<?php echo $vo['id']; ?>&nav=编辑信息&url=">
                                    <?php if($vo['status'] == 2): ?>
                                    [<b>已下架</b>]
                                    <?php endif; ?><?php echo $vo['goods_name']; ?></a>
                            </p>
                            <p style='margin-bottom:-24px;height:50px;'>
                                <input type="checkbox" name="chk_list" value="<?php echo $vo['id']; ?>"/> ID：<?php echo $vo['id']; ?>
                            </p>
                            <p>
                                <span>
                                    所属商城：<?php echo $vo['shop_name']; ?>
                                </span>
                            </p>
                            <p><span>销量：<?php echo $vo['sales']; ?></span></p>
                        </li>
                        <?php endforeach; endif; else: echo "" ;endif; ?>
                    </ul>
                    <div class="clear"></div>
                </div>
            </td>
        </tr>
        <tr class="f">
            <td><input name="" class="chk_all" type="checkbox" value=""/>全选</td>
            <td>选中项 :
                <a href="javascript:;" class="btn btn-danger" onclick="del();">删除</a>
                <a href="javascript:;" class="btn btn-primary" onclick="upper_shelf();">上架</a>
                <a href="javascript:;" class="btn btn-danger" onclick="lower_shelf();">下架</a>
                <a href="javascript:;" class="btn btn-success" onclick="topon();">推荐</a>
                <a href="javascript:;" class="btn btn-danger" onclick="topoff();">取消推荐</a>
            </td>
            <td align="right"><?php echo $page; ?></td>
        </tr>
    </table>
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
    function change(th, key) {
        window.location.href = "<?php echo url('index'); ?>?" + key + "=" + th.value;
    }

    function opennew(th) {
        window.location.href = th.getAttribute('data-url') + window.location.href;
    }

    $(".chk_all").click(function () {
        if (!$(this).attr("checked")) {
            $("input[name='chk_list']").removeAttr("checked");
        } else {
            $("input[name='chk_list']").attr("checked", $(this).attr("checked"));
        }
    });

    function del() {
        var arrChk = $("input[name='chk_list']:checked");
        var url = "<?php echo url('del'); ?>";
        var val = '';
        for (var i = 0; i < arrChk.length; i++) {
            val = val + "," + arrChk[i].value;
        }
        if (val == '') {
            alert("没有选中任何商品");
            return false;
        }
        if (!confirm("确定要删除选中项吗？")) {
            return false;
        }
        $.ajax({
            url: url,
            data: "id=" + val,
            type: 'post',
            success: function (txt) {
                tishi(txt.code == 1 ? 'success' : 'error', txt.msg, window.location.href)
            }
        });
    }

    //下架
    function lower_shelf() {
        var arrChk = $("input[name='chk_list']:checked");
        var url = "<?php echo url('lower_shelf'); ?>";
        var val = '';
        for (var i = 0; i < arrChk.length; i++) {
            val = val + "," + arrChk[i].value;
        }
        if (val == '') {
            alert("没有选中任何商品");
            return false;
        }
        if (!confirm("确定要选中项下架吗？")) {
            return false;
        }
        $.ajax({
            url: url,
            data: "id=" + val,
            type: 'post',
            success: function (txt) {
                tishi(txt.code == 1 ? 'success' : 'error', txt.msg, window.location.href)
            }
        })
    }
    //上架
    function upper_shelf() {
        var arrChk = $("input[name='chk_list']:checked");
        var url = "<?php echo url('upper_shelf'); ?>";
        var val = '';
        for (var i = 0; i < arrChk.length; i++) {
            val = val + "," + arrChk[i].value;
        }
        if (val == '') {
            alert("没有选中任何商品");
            return false;
        }
        if (!confirm("确定要选中项上架吗？")) {
            return false;
        }
        $.ajax({
            url: url,
            data: "id=" + val,
            type: 'post',
            success: function (txt) {
                tishi('success', txt.msg, window.location.href)
            }
        })
    }

    function topon() {
        var arrChk = $("input[name='chk_list']:checked");
        var url = "<?php echo url('topon'); ?>";
        var val = '';
        for (var i = 0; i < arrChk.length; i++) {
            val = val + "," + arrChk[i].value;
        }
        if (val == '') {
            alert("没有选中任何商品");
            return false;
        }
        $.ajax({
            url: url,
            data: "id=" + val,
            type: 'post',
            success: function (txt) {
                tishi('success', txt.msg, window.location.href)
            }
        })
    }

    function topoff() {
        var arrChk = $("input[name='chk_list']:checked");
        var url = "<?php echo url('topoff'); ?>";
        var val = '';
        for (var i = 0; i < arrChk.length; i++) {
            val = val + "," + arrChk[i].value;
        }
        if (val == '') {
            alert("没有选中任何商品");
            return false;
        }
        $.ajax({
            url: url,
            data: "id=" + val,
            type: 'post',
            success: function (txt) {
                tishi('success', txt.msg, window.location.href)
            }
        })
    }
    function look_cat(id) {
        var $val = $(id).val();
        var url = '<?php echo url("index"); ?>?scat=' + $val;
        window.location.href = url;
    }

    function look_order(id) {
        var $val = $(id).val();
        var url = '<?php echo url("index"); ?>?order=' + $val;
        window.location.href = url;
    }

    function searchs() {
        var $val = $('#search').val();
        var url = '<?php echo url("index"); ?>?search=' + $val;
        window.location.href = url;
    }
</script>