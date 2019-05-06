var apis = "http://shengxian.niurenjianzhan.com/apis/";
var iapi = "http://shengxian.niurenjianzhan.com";
var api_init;
var apiready = function() {
    //  var sType = api.systemType;
    //  var sVer = api.systemVersion;
    //  console.log(sVer)
    //  if(sType=='android'){   //如果安卓版本 修改状态栏为白色
    //    api.setStatusBarStyle({
    //        style: 'dark',
    //        color: 'rgba(255,255,255,1)'
    //      });
    //     if(sVer > '7.0'){
    //       // $('header').css('padding-top',api.safeArea.top + 'px')
    //       // $('body').addClass('header_and')
    //     }
    //  }else if(sType=='ios'){ //如果ios版本 修改状态栏字体为黑色
    //    api.setStatusBarStyle({
    //        style: 'dark',
    //        color: '#cccccc'
    //      });
    //  }

    // var systemType = api.systemType; // 获取手机类型，比如： ios
    // var header = document.querySelector('header');
    // var statusBarAppearance = api.statusBarAppearance;
    // if (systemType == 'ios') { //兼容ios和安卓
    //     if (statusBarAppearance) {
    //         $api.addCls(header, 'headerIos');
    //     }
    // } else {
    //     if (statusBarAppearance) {
    //         $api.addCls(header, 'headerAnd');
    //     }
    //
    // }
    // api.setStatusBarStyle({
    //   style: 'dark',
    //   color: '#fff'
    // });

    //注册
    $(".newact").on("click", function() { //不用判断登录class
        var url = $(this).attr("url");
        if (!url) {
            return;
        }
        api.openWin({
            name: url,
            url: url
        });
    })

    //
    $(".newactlogin").on("tap", function() { //判断登录class   比如:'<div class="newactlogin" url="你要跳转的路径">'
        if (!checkLogin()) {
            return false;
        }
        var url = $(this).attr("url");
        if (!url) {
            return false;
        }
        api.openWin({
            name: url,
            url: url
        });
    })
    api_init();

}

$(".mui-pull-left").on('touchstart', function() { //公共返回事件
    api.closeWin();
});
// $(".event_back").on('touchstart', function() { //公共返回事件
//     api.closeWin();
// });
//登录检测
function checkLogin() {
    userId = $api.getStorage('userId');
    if (userId) {
        api.ajax({
            url: apis + 'user/checkLogin',
            data: {
                values: {
                    uid: userId
                }
            }
        }, function(ret, err) {
            if (ret) {
                console.log(JSON.stringify(ret));
            } else {
                console.log(JSON.stringify(err));
            }
        });
    } else {
        // api.alert({
        //     title: '请登录',
        //     msg: '你还没有登录',
        // }, function(ret, err) {
            api.openWin({
                name: 'login',
                url: '../public/login.html',
                pageParam: {
                    name: 'login'
                },
                animation: 'none'
            });
        //});
    }
    return userId;
}
/**
 * 获得验证码
 * length 为验证码长度
 */
function getcode(length) {
  if (length == undefined) {
    length = 4;
  }
  var pow = Math.pow(10, length);
  var code = Math.floor(Math.random() * pow + pow / 10).toString();
  return code.substr(0, length);
}
// 显示等待框
function showProgress() {
    api.showProgress({
        style: '0',
        animationType: 'fade',
        title: '',
        text: '',
        modal: true
    });
}
//关闭等待框
function hideProgress(time) {
    if (!time) {
        time = 200;
    }
    setTimeout(function() {
        api.hideProgress();
    }, time);
}

function refresh() {
    api.execScript({
        name: 'root',
        frameName: 'wode',
        script: 'getUserInfo();'
    });
    api.execScript({
        name: 'root',
        frameName: 'wode',
        script: 'getUserCoupon();'
    });
}


function header_top() {
    // var systemType = api.systemType;  // 获取手机类型，比如： ios
    //   var header = document.querySelector('header');
    //   if(systemType=='ios'){//兼容ios和安卓
    //     $api.addCls(header, 'headerIos');
    //   }else{
    //     $api.addCls(header, 'headerAnd');
    //   }
    //

}
