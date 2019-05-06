<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:85:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\order\look.html";i:1553580986;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
    <form action="<?php echo url('fh'); ?>" method="post" id="form">
        <div class="item">
            <label>订单编号：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                    <?php echo $res['order_sn']; ?>
            </div>
        </div>
        <div class="item">
            <label>商品信息：</label>
             <?php if(is_array($goods) || $goods instanceof \think\Collection || $goods instanceof \think\Paginator): $i = 0; $__LIST__ = $goods;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <div class="i_bd">
                 <img src="/public_html<?php echo $vo['goods_img']; ?>" style="width:100px;">
                 <br>商品名字：<?php echo $vo['goods_name']; ?>单价：<?php echo $vo['price']; ?>购买数量：<?php echo $vo['goods_num']; ?>      
            </div>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </div>
        <div class="item">
            <label>发货信息：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                <?php echo $res['u_name']; ?>&nbsp;&nbsp;&nbsp;<?php echo $res['u_mobile']; ?>
                <br />
                <?php echo $res['u_addr']; ?>
            </div>
        </div>
        <div class="item">
            <label>留言：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                <?php if($res['content'] == null): ?>
                暂无留言
                <?php else: ?>
                <?php echo $res['content']; endif; ?>
            </div>
        </div>
        <div class="item">
            <label>支付金额：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                <?php echo $res['price']; ?>
            </div>
        </div>
        <div class="item">
            <label>下单时间：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                <?php echo $res['dateline']; ?>
            </div>
        </div>
        <?php if($u_kd['name'] != null): ?>
        <div class="item">
            <label>发货信息：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                物流名称：<?php echo $u_kd['name']; ?>
                <br/>
                物流单号：<?php echo $res['kd_num']; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="item">
            <label>订单状态：</label>

            <div class="i_bd" style="border:1px solid;padding:5px;width:30%">
                <?php if($res['status'] == 1): ?>
                待付款
                <?php elseif($res['status'] == 2): ?>
                待发货
                <?php elseif($res['status'] == 3): ?>
                待收货
                <?php elseif($res['status'] == 4): ?>
                已完成
                <?php elseif($res['status'] == 5): ?>
                退款审核中
                <?php elseif($res['status'] == 6): ?>
                退款审核通过
                <?php elseif($res['status'] == 7): ?>
                已退款
                <?php endif; ?>
            </div>
        </div>
        <?php if($res['status'] == 2): ?>
        <div class="item">
            <label>物流选择：</label>

            <div class="i_bd">
               <select name="kd_id" style='width:200px;height:28.8px;'>
                    <?php if(is_array($kd) || $kd instanceof \think\Collection || $kd instanceof \think\Paginator): $i = 0; $__LIST__ = $kd;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
                    <option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option>
                    <?php endforeach; endif; else: echo "" ;endif; ?>
               </select>
            </div>
        </div>
        <div class="item">
            <label>物流单号：</label>

            <div class="i_bd">
                   <input type="text" name="kd_num" style="height:28.8px;width:230px;">
            </div>
        </div>
        <input type="hidden" name="id" value="<?php echo $res['id']; ?>">
        <div class="item">
            <label></label>
            <div class="i_bd">
                <input name="" type="button" class="btn btn-success" id="form_btn" value="发货"/>
            </div>
        </div>
        <?php endif; ?>
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
            $("#form").ajaxSubmit(function (txt) {
                if (txt.code == 1){
                    tishi('success', txt.msg, '<?php echo url("index"); ?>');

                } else {
                    tishi('error', txt.msg);
                }
            });
        })
    });
</script>