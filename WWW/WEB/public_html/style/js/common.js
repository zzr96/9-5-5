    var apis = 'http://shengxian.niurenjianzhan.com/apis/';
    var iapi = 'http://shengxian.niurenjianzhan.com/';
    var apis = 'http://127.0.0.1/apis/';
    var iapi = 'http://127.0.0.1/';
    var userId;
    var api_init;
    var UILoading;
    var mask;
    mui.init({
        gestureConfig: {
            tap: true, //默认为true
            doubletap: true, //默认为false
            longtap: true, //默认为false
            swipe: true, //默认为true
            drag: true, //默认为true
            hold: true, //默认为false，不监听
            release: true //默认为false，不监听
        }
    });
    var apiready = function() {
        var sType = api.systemType;
        var sVer = api.systemVersion;
        UILoading = api.require('UILoading');
        mask = mui.createMask();
        // if (sType == 'android') {
        //     if (sVer > '6.0') {
        //         $('header').css('padding-top', api.safeArea.top + 'px')
        //         $('body').addClass('header_and')
        //     }
        // }

        // $(".newact").on("click", function() { //不用判断登录class
        //     var url = $(this).attr("url");
        //     if (!url) {
        //         return;
        //     }
        //     api.openWin({
        //         name: url,
        //         url: url,
        //         opaque: false
        //     });
        // })

        //全局点击事件
        $(".newactlogin").on("touchstart", function() { //判断登录class   比如:'<div class="newactlogin" url="你要跳转的路径">'
            if (!checkLogin()) {
                return;
            }
            var url = $(this).attr("url");
            if (!url) {
                return;
            }
            api.openWin({
                name: url,
                url: url
            });
        })

        $(".event_back").on('touchstart', function() { //公共返回事件
            api.closeWin();
        });

        $('.mui-pull-left').on('tap', function() {
            api.closeWin();
        });

        api_init();
    }

    function loadingStart() {
        mask.show();
        UILoading.flower();

    }

    function loadingClose() {
        mask.close();
        UILoading.closeFlower();

    }

    function checkLogin() {
        userId = $api.getStorage('userId');
        if (userId) {
            $.ajax({
                url: apis + 'user/checkLogin',
                data: {
                    uid: userId
                },
                success: function(ret) {
                    console.log(JSON.stringify(ret));
                }
            });
        } else {
            api.openWin({
                name: 'login',
                url: '../guest/login.html',
                pageParam: {
                    name: 'login'
                }
            });
        }
    }

    function GetDateDiff(datetime) {
        var date = new Date(datetime.replace(/\-/g, "/"));
        var nowTime = new Date().getTime();
        var res = Math.abs(parseInt((date.getTime() - nowTime) / 1000));
        if (res < 60) {
            return ' 刚刚 ';
        }
        if (res < 60 * 60) {
            return Math.floor(res / 60) + '分钟前';
        }
        if (res < 60 * 60 * 24) {
            return Math.floor(res / 60 / 60) + '小时前';
        }
        if (res < 60 * 60 * 24 * 30) {
            return Math.floor(res / 60 / 60 / 24) + '天前';
        }
        return datetime.split(' ')[0];
    }

    function twoDate(a, b) {
        var a = new Date(a.replace(/\-/g, "/"));
        var b = new Date(b.replace(/\-/g, "/"));
        var res = Math.abs(parseInt((a.getTime() - b.getTime()) / 1000));
        if (res > 600) {
            return true;
        }
        return false;
    }

    function randomCode(n) {
        var min = 1;
        var max = 10;
        for (var i = 1; i < n; i++) {
            min *= 10;
            max *= 10;
        }
        max--;
        return Math.floor(Math.random() * (max - min + 1) + min);
    }


    function shareQQ(nid) {
        var qq = api.require('QQPlus');
        qq.shareNews({
            url: iapi + 'index/news?nid=' + nid,
            title: '微在线',
            description: '这一刻，你我同在！',
            imgUrl: iapi + 'uploads/default/logo.png'
        }, function(ret, err) {
            api.toast({
                msg: ret.status ? '分享成功' : '分享已取消',
                duration: 2000,
                location: 'middle'
            });
        });
        qq.installed(function(ret, err) {
            if (ret.status) {

            } else {
                api.alert({
                    msg: "当前设备未安装QQ客户端"
                });
            }
        });
    }


    function shareWx(nid, scene) {
        var weiXin = api.require('weiXin');
        weiXin.registerApp(
            function(ret, err) {
                if (ret.status) {
                    weiXin.sendRequest({
                        contentType: 'web_page',
                        scene: scene,
                        title: '微在线',
                        description: '这一刻，你我同在！',
                        thumbUrl: iapi + 'uploads/default/logo.png',
                        contentUrl: iapi + 'index/news?nid=' + nid
                    }, function(ret, err) {
                        api.toast({
                            msg: ret.status ? '分享成功' : '分享已取消',
                            duration: 2000,
                            location: 'middle'
                        });
                    });
                } else {
                    api.alert({
                        msg: err.msg
                    });
                }
            }
        );
        return;

        // wx.isInstalled(function(ret, err) {
        //     if (ret.installed) {
        //
        //     } else {
        //         alert('当前设备未安装微信客户端');
        //     }
        // });
    }

    var emotion = ﻿ [{
        "name": "Expression_1",
        "text": "[微笑]"
    }, {
        "name": "Expression_2",
        "text": "[撇嘴]"
    }, {
        "name": "Expression_3",
        "text": "[色]"
    }, {
        "name": "Expression_4",
        "text": "[发呆]"
    }, {
        "name": "Expression_5",
        "text": "[得意]"
    }, {
        "name": "Expression_6",
        "text": "[流泪]"
    }, {
        "name": "Expression_7",
        "text": "[害羞]"
    }, {
        "name": "Expression_8",
        "text": "[闭嘴]"
    }, {
        "name": "Expression_9",
        "text": "[睡]"
    }, {
        "name": "Expression_10",
        "text": "[大哭]"
    }, {
        "name": "Expression_11",
        "text": "[尴尬]"
    }, {
        "name": "Expression_12",
        "text": "[发怒]"
    }, {
        "name": "Expression_13",
        "text": "[调皮]"
    }, {
        "name": "Expression_14",
        "text": "[呲牙]"
    }, {
        "name": "Expression_15",
        "text": "[惊讶]"
    }, {
        "name": "Expression_16",
        "text": "[难过]"
    }, {
        "name": "Expression_17",
        "text": "[酷]"
    }, {
        "name": "Expression_18",
        "text": "[冷汗]"
    }, {
        "name": "Expression_19",
        "text": "[抓狂]"
    }, {
        "name": "Expression_20",
        "text": "[吐]"
    }, {
        "name": "Expression_21",
        "text": "[偷笑]"
    }, {
        "name": "Expression_22",
        "text": "[愉快]"
    }, {
        "name": "Expression_23",
        "text": "[白眼]"
    }, {
        "name": "Expression_24",
        "text": "[傲慢]"
    }, {
        "name": "Expression_25",
        "text": "[饥饿]"
    }, {
        "name": "Expression_26",
        "text": "[困]"
    }, {
        "name": "Expression_27",
        "text": "[恐惧]"
    }, {
        "name": "Expression_28",
        "text": "[流汗]"
    }, {
        "name": "Expression_29",
        "text": "[憨笑]"
    }, {
        "name": "Expression_30",
        "text": "[悠闲]"
    }, {
        "name": "Expression_31",
        "text": "[奋斗]"
    }, {
        "name": "Expression_32",
        "text": "[咒骂]"
    }, {
        "name": "Expression_33",
        "text": "[疑问]"
    }, {
        "name": "Expression_34",
        "text": "[嘘]"
    }, {
        "name": "Expression_35",
        "text": "[晕]"
    }, {
        "name": "Expression_36",
        "text": "[疯了]"
    }, {
        "name": "Expression_37",
        "text": "[衰]"
    }, {
        "name": "Expression_38",
        "text": "[骷髅]"
    }, {
        "name": "Expression_39",
        "text": "[敲打]"
    }, {
        "name": "Expression_40",
        "text": "[再见]"
    }, {
        "name": "Expression_41",
        "text": "[擦汗]"
    }, {
        "name": "Expression_42",
        "text": "[抠鼻]"
    }, {
        "name": "Expression_43",
        "text": "[鼓掌]"
    }, {
        "name": "Expression_44",
        "text": "[糗大了]"
    }, {
        "name": "Expression_45",
        "text": "[坏笑]"
    }, {
        "name": "Expression_46",
        "text": "[左哼哼]"
    }, {
        "name": "Expression_47",
        "text": "[右哼哼]"
    }, {
        "name": "Expression_48",
        "text": "[哈欠]"
    }, {
        "name": "Expression_49",
        "text": "[鄙视]"
    }, {
        "name": "Expression_50",
        "text": "[委屈]"
    }, {
        "name": "Expression_51",
        "text": "[快哭了]"
    }, {
        "name": "Expression_52",
        "text": "[阴险]"
    }, {
        "name": "Expression_53",
        "text": "[亲亲]"
    }, {
        "name": "Expression_54",
        "text": "[吓]"
    }, {
        "name": "Expression_55",
        "text": "[可怜]"
    }, {
        "name": "Expression_56",
        "text": "[菜刀]"
    }, {
        "name": "Expression_57",
        "text": "[西瓜]"
    }, {
        "name": "Expression_58",
        "text": "[啤酒]"
    }, {
        "name": "Expression_59",
        "text": "[篮球]"
    }, {
        "name": "Expression_60",
        "text": "[乒乓]"
    }, {
        "name": "Expression_61",
        "text": "[咖啡]"
    }, {
        "name": "Expression_62",
        "text": "[饭]"
    }, {
        "name": "Expression_63",
        "text": "[猪头]"
    }, {
        "name": "Expression_64",
        "text": "[玫瑰]"
    }, {
        "name": "Expression_65",
        "text": "[凋谢]"
    }, {
        "name": "Expression_66",
        "text": "[嘴唇]"
    }, {
        "name": "Expression_67",
        "text": "[爱心]"
    }, {
        "name": "Expression_68",
        "text": "[心碎]"
    }, {
        "name": "Expression_69",
        "text": "[蛋糕]"
    }, {
        "name": "Expression_70",
        "text": "[闪电]"
    }, {
        "name": "Expression_71",
        "text": "[炸弹]"
    }, {
        "name": "Expression_72",
        "text": "[刀]"
    }, {
        "name": "Expression_73",
        "text": "[足球]"
    }, {
        "name": "Expression_74",
        "text": "[瓢虫]"
    }, {
        "name": "Expression_75",
        "text": "[便便]"
    }, {
        "name": "Expression_76",
        "text": "[月亮]"
    }, {
        "name": "Expression_77",
        "text": "[太阳]"
    }, {
        "name": "Expression_78",
        "text": "[礼物]"
    }, {
        "name": "Expression_79",
        "text": "[拥抱]"
    }, {
        "name": "Expression_80",
        "text": "[强]"
    }, {
        "name": "Expression_81",
        "text": "[弱]"
    }, {
        "name": "Expression_82",
        "text": "[握手]"
    }, {
        "name": "Expression_83",
        "text": "[胜利]"
    }, {
        "name": "Expression_84",
        "text": "[抱拳]"
    }, {
        "name": "Expression_85",
        "text": "[勾引]"
    }, {
        "name": "Expression_86",
        "text": "[拳头]"
    }, {
        "name": "Expression_87",
        "text": "[差劲]"
    }, {
        "name": "Expression_88",
        "text": "[爱你]"
    }, {
        "name": "Expression_89",
        "text": "[NO]"
    }, {
        "name": "Expression_90",
        "text": "[OK]"
    }, {
        "name": "Expression_91",
        "text": "[爱情]"
    }, {
        "name": "Expression_92",
        "text": "[飞吻]"
    }, {
        "name": "Expression_93",
        "text": "[跳跳]"
    }, {
        "name": "Expression_94",
        "text": "[发抖]"
    }, {
        "name": "Expression_95",
        "text": "[怄火]"
    }, {
        "name": "Expression_96",
        "text": "[转圈]"
    }, {
        "name": "Expression_97",
        "text": "[磕头]"
    }, {
        "name": "Expression_98",
        "text": "[回头]"
    }, {
        "name": "Expression_99",
        "text": "[跳绳]"
    }, {
        "name": "Expression_100",
        "text": "[投降]"
    }, {
        "name": "Expression_101",
        "text": "[激动]"
    }, {
        "name": "Expression_102",
        "text": "[街舞]"
    }, {
        "name": "Expression_103",
        "text": "[献吻]"
    }, {
        "name": "Expression_104",
        "text": "[左太极]"
    }, {
        "name": "Expression_105",
        "text": "[右太极]"
    }, {
        "name": "Expression_106",
        "text": "[哈哈]"
    }];

    var es = ["[害羞]", "[闭嘴]", "[不说话]", "[开心]", "[怪脸]", "[受伤]", "[惊恐]", "[委屈]", "[吐舌头]", "[哼小曲]",
        "[炸弹]", "[惊吓]", "[糗大了]", "[汗颜]", "[疑问]", "[酷]", "[开心到飞]", "[想一下]", "[生病]", "[难受]",
        "[傻瓜]", "[拍死你]", "[委屈]", "[咒骂]", "[哦]", "[色]", "[洗澡]", "[想哭]", "[困]", "[奔跑]",
        "[勾引]", "[脸黑]", "[伤心]", "[生气]", "[委屈不像说]", "[魔鬼]", "[斜眼]", "[嘘]", "[惊讶]", "[撇嘴]",
        "[惊悚]", "[坏笑]", "[晕]", "[啊]", "[哈哈]", "[泪奔]", "[卖萌]", "[可怜]", "[想吃]", "[吐]"
    ];

    //表情匹配
    function ei(con) {
        var res = con.match(/\[.+?\]/g);
        if (res)
            $.each(res, function(k, v) {
                i = es.indexOf(v);
                if (i > -1) {
                    con = con.replace(v, '<img height="20px" src="../img/wbimg/' + i + '.gif"/>');
                }
            });
        var res = con.match(/\/qn\d{1,3}/g);
        if (res)
            $.each(res, function(k, v) {
                i = v.replace('/qn', '');
                if (i < 1 && i > 106) return true;
                con = con.replace(v, '<img height="20px" src="../img/emotion/Expression_' + i + '.png"/>');
            });
        var res = con.match(/\/al\d{1,3}/g);
        if (res)
            $.each(res, function(k, v) {
                i = v.replace('/al', '');
                if (i < 6 && i > 104) return true;
                con = con.replace(v, '<img height="20px" src="../img/alimg/' + i + '.gif"/>');
            });
        var res = con.match(/\/qq\d{1,3}/g);
        if (res)
            $.each(res, function(k, v) {
                i = v.replace('/qq', '');
                if (i > 132) return true;
                con = con.replace(v, '<img height="20px" src="../img/qqimg/' + i + '.gif"/>');
            });
        var res = con.match(/\/wb\d{1,2}/g);
        if (res)
            $.each(res, function(k, v) {
                i = v.replace('/wb', '');
                if (i > 49) return true;
                con = con.replace(v, '<img height="20px"  src="../img/wbimg/' + i + '.gif"/>');
            });
        return con;
    }
