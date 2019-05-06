var shares=null;
var sweixin=null;
var buttons=[
  {title:'我的好友',extra:{scene:'WXSceneSession'}},
  {title:'朋友圈',extra:{scene:'WXSceneTimeline'}}
  // {title:'我的收藏',extra:{scene:'WXSceneFavorite'}}
];
// H5 plus事件处理
function plusReady(){
	updateSerivces();
}
if(window.plus){
	plusReady();
}else{
	document.addEventListener('plusready', plusReady, false);
}
/**
 * 更新分享服务
 */
function updateSerivces(){
	plus.share.getServices(function(s){
		shares={};
		for(var i in s){
			var t=s[i];
			shares[t.id]=t;
		}
    sweixin=shares['weixin'];
	}, function(e){
		outSet('获取分享服务列表失败：'+e.message);
	});
}
// 分享网页
function shareWxCircle() {
	var msg={
		type:'web',
		thumbs:['_www/logo.png'],
		href: 'http://www.dcloud.io/',
		title: 'DCloud-做最好的HTML5开发工具',
		content: '我正在使用HBuilder+HTML5开发移动应用，赶紧跟我一起来体验！'
	};
	share(sweixin, msg, {title:'朋友圈',extra:{scene:'WXSceneTimeline'}})
}

function shareWx() {
	var msg={
		type:'web',
		thumbs:['_www/logo.png'],
		href: 'http://www.dcloud.io/',
		title: 'DCloud-做最好的HTML5开发工具',
		content: '我正在使用HBuilder+HTML5开发移动应用，赶紧跟我一起来体验！'
	};
	share(sweixin, msg, {title:'我的好友',extra:{scene:'WXSceneSession'}});
}
// 分享
function share(srv, msg, button){
	console.log('分享操作：');
  if(!srv){
    console.log('无效的分享服务！');
    return;
  }
  button&&(msg.extra=button.extra);
	// 发送分享
	if(srv.authenticated){
		console.log('---已授权---');
		doShare(srv, msg);
	}else{
		console.log('---未授权---');
		srv.authorize(function(){
			doShare(srv, msg);
		}, function(e){
			console.log('认证授权失败：'+JSON.stringify(e));
		});
	}  
}
// 发送分享
function doShare(srv, msg){
	console.log(JSON.stringify(msg));
	srv.send(msg, function(){
		console.log('分享到"'+srv.description+'"成功！');
	}, function(e){
		console.log('分享到"'+srv.description+'"失败: '+JSON.stringify(e));
	});
}
// 解除授权
function cancelAuth(){
	console.log('解除授权：');
  if(sweixin){
    if(sweixin.authenticated){
    	console.log('取消"'+sweixin.description+'"');
    }
    sweixin.forbid();
  }else{
    console.log('当前环境不支持微信分享操作!');
  }
}