var api = "http://yungeche.niurenjianzhan.com/api/";
var iapi = "http://yungeche.niurenjianzhan.com/"

function check_login() {
	var userId = plus.storage.getItem("userId");
	console.log(userId)
	if(!userId) {
		mui.openWindow({
			url: "/public/login.html",
			id: "/public/login.html",
			waiting: {
				autoShow: false, //自动显示等待框，默认为true
			}
		})
		return false;
	}
	return true;
}

function youke_login() {
	var userId = plus.storage.getItem("userId");
	if(!userId) {
		mui.openWindow({
			url: "/public/login.html",
			id: "/public/login.html",
			waiting: {
				autoShow: false, //自动显示等待框，默认为true
			}
		})
		return;
	}
	
	return true;
}
var parseQueryString= function (url) {    //获取参数链接转为json
		reg_para = /([^&=]+)=([\w\W]*?)(&|$|#)/g,
		ret = {};
		var str_para = url, result;
			while ((result = reg_para.exec(str_para)) != null) {
		    ret[result[1]] = result[2];
		}
	return ret;
}

mui.plusReady(function() {
	
	//全局点击事件
	$(".newact").on("tap", function() {
		
		var url = $(this).attr("url");
		if(!url) {
			return;
		}
		var data_parm = $(this).attr("data-parm")
		var parm = parseQueryString(data_parm)
		var web = plus.webview.getWebviewById(url)
		if(web) {
			plus.webview.show(web, "fade-in", 300);
			return
		}
		if(!parm){
			return;
		}
		mui.openWindow({
			url: url,
			id: url,
			extras: {
				parm:parm
			},
			waiting: {
				autoShow: false, //自动显示等待框，默认为true
			},
			
		})
		
	})

	//全局点击事件
	$(".newactlogin").on("tap", function() {
		if(!check_login()) {
			return;
		}
		var url = $(this).attr("url");
		var data_parm = $(this).attr("data-parm")
		var parm = parseQueryString(data_parm)
//		var parm = data_parm.toString()
		if(!url) {
			return;
		}
		var web = plus.webview.getWebviewById(url)
		if(web) {
			plus.webview.show(web, "fade-in", 300);
			return
		}
		if(!parm){
			return;
		}
		mui.openWindow({
			url: url,
			id: url,
			extras: {
				parm:parm
			},
			waiting: {
				autoShow: false, //自动显示等待框，默认为true
			}
		})
	})

	document.addEventListener("netchange", wainshow, false);
})

function wainshow() {
	if(plus.networkinfo.getCurrentType() == plus.networkinfo.CONNECTION_NONE) {
		mui.toast("网络异常，请检查网络设置！");
		mui.openWindow({
			url: '/page/nointnet.html',
			id: '/page/nointnet.html',
			waiting: {
				autoShow: false, //自动显示等待框，默认为true
			}
		})

	} else {

	}
}

//数组去重	PS:取消注释后个人中心中的选择滑动将确定不了	mui.poppicker.js:117
Array.prototype.unique3 = function() {
	var res = [];
	var json = {};
	for(var i = 0; i < this.length; i++) {
		if(!json[this[i]]) {
			res.push(this[i]);
			json[this[i]] = 1;
		}
	}
	return res;
}

function wDate(s, t) {
	da = new Date(parseInt(s));
	var year = da.getFullYear();
	var month = da.getMonth() + 1;
	var date = da.getDate();

	var h = da.getHours();
	var i = da.getMinutes();
	var ss = da.getSeconds();
	if(t) {
		var o = h + ":" + i + ":" + ss
		return [year, month, date].join('-') + " " + o;
	}
	return [year, month, date].join('-');
}

function getDate(nS) {
	return new Date(parseInt(nS) * 1000).toLocaleString().replace(/年|月/g, "-").replace(/日/g, " ");
}

Array.prototype.indexOf = function(val) {
	for(var i = 0; i < this.length; i++) {
		if(this[i] == val) return i;
	}
	return -1;
};

Array.prototype.remove = function(val) {
	var index = this.indexOf(val);
	if(index > -1) {
		this.splice(index, 1);
	}
};
String.prototype.trim = function() {　　
	return this.replace(/(^\s*)|(\s*$)/g, "");
}

//存储json对象
function saveJ(key, value) {
	value = JSON.stringify(value);
	res = plus.storage.setItem(key, value);
	return res;
}
//读取json对象
function readJ(key) {
	res = plus.storage.getItem(key);
	res = JSON.parse(res);
	return res;
}