<?php if (!defined('THINK_PATH')) exit(); /*a:4:{s:94:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\administrator\index.html";i:1537873324;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\head.html";i:1551318104;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\menu.html";i:1537404906;s:71:"D:\phpStudy\PHPTutorial\WWW\WEB\application\admin\view\common\foot.html";i:1541491608;}*/ ?>
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
	<table width="100%" border="0" cellpadding="0" cellspacing="0" class="table">
			  <tr class="h">
				<td width="60" valign="middle"><input name="" class="chk_all" type="checkbox" value="" />全选</td>
				<td>会员名</td>
				<td>角色</td>
				<td>状态</td>
				<td>操作</td>
			  </tr>
			  <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
			  <tr>
				<td><input name="chk_list" type="checkbox" value="<?php echo $vo['id']; ?>" /></td>
				<td><a href="<?php echo url("edit?id=$vo[id]"); ?>"><b><?php echo $vo['name']; ?></b></a>  注册于<?php echo date("Y-m-d H:i:s",$vo['regdate']); ?> </span></td>
				<td><a href="<?php echo url("index?cat=$vo[cat]"); ?>"><?php echo $vo['cats']['name']; ?></a></td>
				<td><a href="<?php echo url("index?status=$vo[status]"); ?>" class="btn btn-default">
					<?php
						if($vo['status']==1){
							echo '正常';
						}else{
							echo '禁用';
						}
					?>
				</a></td>
				<td><a href="<?php echo url("edit?id=$vo[id]&nav=编辑管理员"); ?>" class="btn btn-success">编辑</a> </td>
			  </tr>
			  <?php endforeach; endif; else: echo "" ;endif; ?>
			  <tr class="f">
				<td><input name="" class="chk_all" type="checkbox" value="" />全选 </td>
			    <td>
					选中项 :
					<a href="javascript:;" class="btn btn-danger" onclick="del();" >删除</a>
					<a href="javascript:;" class="btn btn-primary" onclick="move();">移动</a>
					<a href="javascript:;" class="btn btn-primary" onclick="qiyong();" >启用</a>
					<a href="javascript:;" class="btn btn-danger" onclick="jinyong();" >禁用</a>	
				</td>
			    <td colspan="3"><?php echo $page; ?></td>
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
$(".chk_all").click(function(){
	if(!$(this).attr("checked")){
		$("input[name='chk_list']").removeAttr("checked");
	}else{
     $("input[name='chk_list']").attr("checked",$(this).attr("checked"));
	 }
});
function del(){
if(!confirm("确定要删除选中项吗？")){
	return false;
}
var arrChk=$("input[name='chk_list']:checked");
var val='';
    for (var i=0;i<arrChk.length;i++)
    {
         val=val+","+arrChk[i].value;
    }
	if(val==''){
		alert("请选择需要操作的信息")
		return false;
	}
	var url="<?php echo url("del"); ?>";
		$.ajax({
				 url:url,
				 data:"id="+val,
				 type:'post',
					success:function(txt){
						tishi('success',txt.msg,window.location.href)
					 }

		})
}
function jinyong(){
	if(!confirm("确定要选中项禁用吗？")){
	return false;
}
var arrChk=$("input[name='chk_list']:checked");
var val='';
    for (var i=0;i<arrChk.length;i++)
    {
         val=val+","+arrChk[i].value;
    }
	if(val==''){
		alert("请选择需要操作的信息")
		return false;
	}
	var url="<?php echo url("jinyong"); ?>";
		$.ajax({
				 url:url,
				 data:"id="+val,
				 type:'post',
					success:function(txt){
						tishi('success',txt.msg,window.location.href)

					 }

		})
}
function qiyong(){
	if(!confirm("确定要选中项启用吗？")){
	return false;
}
var arrChk=$("input[name='chk_list']:checked");
var val='';
    for (var i=0;i<arrChk.length;i++)
    {
         val=val+","+arrChk[i].value;
    }
	if(val==''){
		alert("请选择需要操作的信息")
		return false;
	}
	var url="<?php echo url("qiyong"); ?>";
		$.ajax({
				 url:url,
				 data:"id="+val,
				 type:'post',
					success:function(txt){
						tishi('success',txt.msg,window.location.href)

					 }

		})
}
function move(){
	var arrChk=$("input[name='chk_list']:checked");
	var val='';
    for (var i=0;i<arrChk.length;i++){
         val=val+","+arrChk[i].value;
    }
	if(val==''){
		alert("请选择需要操作的信息")
		return false;
	}
	$html='选择要移入的类别:<select name="cat" id="cat" ><option value="">选择要移入的类别</option><?php if(is_array($cat) || $cat instanceof \think\Collection || $cat instanceof \think\Paginator): $i = 0; $__LIST__ = $cat;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?><option value="<?php echo $vo['id']; ?>"><?php echo $vo['name']; ?></option><?php if(is_array($vo['one']) || $vo['one'] instanceof \think\Collection || $vo['one'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo['one'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo2): $mod = ($i % 2 );++$i;?><option value="<?php echo $vo2['id']; ?>">┠<?php echo $vo2['name']; ?></option><?php if(is_array($vo2['two']) || $vo2['two'] instanceof \think\Collection || $vo2['two'] instanceof \think\Paginator): $i = 0; $__LIST__ = $vo2['two'];if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo3): $mod = ($i % 2 );++$i;?><option value="<?php echo $vo3['id']; ?>">&nbsp;&nbsp;┠<?php echo $vo3['name']; ?></option>	<?php endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; endforeach; endif; else: echo "" ;endif; ?></select>';
	$html+='<div style="padding:20px 0px 0px 100px;"><input name="" class="i_btn" onclick="move_do();" value="提交" type="button" /></div>';
	tanchuang("move",'400','信息移动',$html)
}
function move_do(){
	if(!confirm("确定要移动选中项吗？")){
		return false;
	}
	var cid=$("#cat").val();
	if(cid==''){
		alert("请选择要移入的类别");
		return false;
	}
	var arrChk=$("input[name='chk_list']:checked");
	var val='';
    for (var i=0;i<arrChk.length;i++){
         val=val+","+arrChk[i].value;
    }
	var url="<?php echo url("move"); ?>";
		$.ajax({
				 url:url,
				 data:"id="+val+"&cat="+cid,
				 type:'post',
					success:function(txt){
						tishi('success',txt.msg,window.location.href)
					 }

		})
}
</script>