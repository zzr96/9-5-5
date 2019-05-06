<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:86:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\order\index.html";i:1553013700;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
<div class="v_bd">
    <div class="v_bd_tag">
        <a href="<?php echo url('index'); ?>"><span class="focus">全部订单(<?php echo $num; ?>)</span></a>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span>分状态查看：
            <div class="select_style">
                <select id="status" name="status" onChange="lookCate(this)" style='height:28.5px;line-height:28.5px;'>
                    <option value="">全部用户</option>
                    <?php if(input('status') == 1): ?>
                    <option value="1" selected="selected">待付款</option>
                    <?php else: ?>
                    <option value="1">待付款</option>
                    <?php endif; if(input('status') == 2): ?>
                    <option value="2" selected="selected">待发货</option>
                    <?php else: ?>
                    <option value="2">待发货</option>
                    <?php endif; if(input('status') == 3): ?>
                    <option value="3" selected="selected">待收货</option>
                    <?php else: ?>
                    <option value="3">待收货</option>
                    <?php endif; if(input('status') == 4): ?>
                    <option value="4" selected="selected">已完成</option>
                    <?php else: ?>
                    <option value="4">已完成</option>
                    <?php endif; if(input('status') == 5): ?>
                    <option value="5" selected="selected">退款审核中</option>
                    <?php else: ?>
                    <option value="5">退款审核中</option>
                    <?php endif; if(input('status') == 6): ?>
                    <option value="6" selected="selected">退款审核通过</option>
                    <?php else: ?>
                    <option value="6">退款审核通过</option>
                    <?php endif; if(input('status') == 7): ?>
                    <option value="7" selected="selected">已退款</option>
                    <?php else: ?>
                    <option value="7">已退款</option>
                    <?php endif; ?>
                </select>
            </div>
        </span>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
        <span>订单搜索：
            <input name="email" id="name" class="edit_input" value="<?php echo input('key'); ?>" type="text"/>
            <a href="javascript:;" onClick="searchs();" class="btn btn-success">提交</a>
            <i class="huise"> (请输入订单号)</i>
        </span>
    </div>
    <br/>

    <div class="bd">
        <?php
            if(!$list){
        ?>
        <div class="none_list">暂无订单</div>
        <?php
            }else{
        ?>
        <table width="100%" border="0" cellpadding="0" class="table" cellspacing="0">
            <tr class="h">
                <td>ID</td>
                <td>订单编号</td>
                <td>姓名</td>
                <td>电话</td>
                <td>地址</td>
                <td>支付金额</td>
                <td>下单时间</td>
                <td>支付方式</td>
                <td>订单状态</td>
                <td>操作</td>
            </tr>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr id="item<?php echo $vo['id']; ?>">
                <td><?php echo $vo['id']; ?></td>
                <td><?php echo $vo['order_sn']; ?></td>
                <td><?php echo $vo['u_name']; ?></td>
                <td><?php echo $vo['u_mobile']; ?></td>
                <td><?php echo $vo['u_addr']; ?></td>
                <td><?php echo $vo['price']; ?></td>
                <td><?php echo $vo['dateline']; ?></td>
                <td><?php if($vo['pay_status'] == 1): ?>余额<?php elseif($vo['pay_status'] == 2): ?>微信<?php else: ?>支付宝<?php endif; ?></td>
                <td>
                    <?php if($vo['status'] == 1): ?>待付款
                    <?php elseif($vo['status'] == 2): ?>待发货
                    <?php elseif($vo['status'] == 3): ?>待收货
                    <?php elseif($vo['status'] == 4): ?>已完成
                    <?php elseif($vo['status'] == 5): ?>退款审核中
                    <?php elseif($vo['status'] == 6): ?>退款审核通过
                    <?php elseif($vo['status'] == 7): ?>已退款
                    <?php endif; ?>
                </td>
                <td>
                    <a href="javascript:;" onClick="del(<?php echo $vo['id']; ?>)" class="btn btn-danger">删除</a>
                    <a href="<?php echo url('order/look'); ?>?id=<?php echo $vo['id']; ?>&nav=订单详情" class="btn btn-success">订单详情</a>
                    <?php if(($vo['status'] == 5) OR ($vo['status']==6) OR ($vo['status']==7)): ?> 
                    <a href="<?php echo url('order/tk_xq'); ?>?id=<?php echo $vo['id']; ?>&nav=退款详情" class="btn btn-success">退款详情</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
        </table>
        <div class="page"><?php echo $page; ?></div>
        <?php } ?>
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

<script>
    function lookCate(obj) {
        var val = obj.value;
        window.location.href = '<?php echo url("index"); ?>?key=<?php echo input("key"); ?>&status=' + val;
    }

    function del(id){
        var r = confirm("您确定要删除该用户吗？");
        if (!r) {
            return false;
        }
        var url = '<?php echo url("delete"); ?>?id=' + id;
        $.ajax({
            url: url,
            success: function (txt){
                if (txt.code == 0){
                   tishi('success', txt.msg, '<?php echo url("index"); ?>');
                } else {
                   tishi('error', txt.msg);
                }
            }
        })
    }
    function status(id, status){
        var data = 'id=' + id + '&status=' + status;
        var url = '<?php echo url("status"); ?>';
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function (data) {
                if (data.code == 0) {
                   tishi('success', txt.msg, '<?php echo url("index"); ?>');
                } else {
                    tishi('error', txt.msg);
                }
            }
        });
    }
    function searchs() {
        var val = $("#name").val();
        if(!val){
            return alert("请输入订单号");
        }
        window.location.href = '<?php echo url("index"); ?>?key=' + val + '&status=<?php echo input("status"); ?>';
    }
</script>
