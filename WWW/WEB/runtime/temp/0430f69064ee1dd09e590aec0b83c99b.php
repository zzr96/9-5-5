<?php if (!defined('THINK_PATH')) exit(); /*a:1:{s:86:"D:\phpStudy\PHPTutorial\WWW\WEB\public_html/../application/admin\view\login\index.html";i:1554285316;}*/ ?>
<!doctype html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>趣习惯后台管理中心</title>
	<meta name="renderer" content="webkit|ie-comp|ie-stand">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport" content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi" />
    <meta http-equiv="Cache-Control" content="no-siteapp" />

    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" type="text/css" href="/static/new/css/font.css" />
    <link rel="stylesheet" type="text/css" href="/static/new/css/xadmin.css" />
  <!--   <script type="text/javascript" src="/static/new/js/xadmin.js"></script> -->
    <script type="text/javascript" src="/static/new/lib/layui/layui.js"></script>
    <script type="text/javascript" src="/static/manage/js/jquery.js"></script>
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>

</head>
<body class="login-bg">
        <div class="login layui-anim layui-anim-up">
        <div class="message">趣习惯后台管理中心</div>
        <div id="darkbannerwrap"></div>
        
        <!-- <form method="post" class="layui-form" > -->
            <input name="name" placeholder="用户名"  type="text" lay-verify="required" class="layui-input"  id="name">
            <hr class="hr15">
            <input name="password" lay-verify="required" placeholder="密码"  type="password" class="layui-input" id="password">
            <hr class="hr15">
            <input class='input' name="captcha"  id="captcha" placeholder="验证码" style="width:50%" type="text"/>&nbsp;&nbsp;&nbsp;&nbsp;
            <img id="verify_img" src="<?php echo captcha_src(); ?>" alt="验证码" onclick="refreshVerify()" style="margin-top:-68px; margin-left:178px;">
            <hr class="hr15">
            <input value="登录" lay-submit lay-filter="login" style="width:100%;" type="submit" onClick="status()">
            <hr class="hr20" >
  <!--       </form> -->
    </div>
    <script>
    function refreshVerify() {
        var ts = Date.parse(new Date()) / 1000;
        var img = document.getElementById('verify_img');
        img.src = "/captcha?id=" + ts;
    }
    function status(){
        var name=$('#name').val();
        if(name==''){
            alert('请填写用户名');
            return false;
        }
        var password=$('#password').val();
        if(password==''){
            alert('请填写密码');
            return false;
        }
        var captcha=$('#captcha').val();
        if(captcha==''){
            alert('请填写验证码');
            return false;
        }
        var data = 'name=' + name + '&password='+password+'&captcha='+captcha;
        var url = '<?php echo url("login_do"); ?>';
        $.ajax({
            url: url,
            data: data,
            type: 'POST',
            success: function (txt){
                if(txt.code == 1){
                     window.location.href = '<?php echo url("/admin/index/index"); ?>'
                }else{
                   alert(txt.msg);
                }
            }
        })
    } 
    </script>

    
    <!-- 底部结束 -->

</body>
</html>