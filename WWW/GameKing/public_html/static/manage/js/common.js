/* admin/view/login/index.html */
$(document).ready(function(){
	layout_init();
	$(window).resize(function() {
 		 layout_init();
	});
});
function layout_init(){
	$("#menu").css("height",$(window).height()-56);
	$("#main").css("height",$(window).height()-56);
	$("#main").css("width",$(window).width()-131);
	$("#login").css("height",$(window).height()-56);
}



function my_img_load(){
	var url="./index.php?g=manage&m=boxedit&a=my_img_load";
		$.ajax({
				 url:url,
					success:function(txt){
						 tanchuang('myimgload',900,'选择图片',txt)
					 }
		})
}

function shengji(){
	var url='/manage/site/shengji';
	$.ajax({
		url:url,
		success: function(txt){
			tanchuang("shengji",500,"网站升级",txt)
		}
	})
}


//////////////////////////////////////////弹出框//////////////////////////////////////////////
function mask(){
	var html='<div class="imask" id="mask" style="width:'+$(window).width()+'px;height:'+$(document).height()+'px;"></div>'
	var vo=$("#mask").attr("class");
	
	if(vo!=null){
		return false;
	}
	$("body").append(html);
	}
function tanchuang(id,w,hd,bd){
	
	var top =$(document).scrollTop()+100 ;
	var left = $(window).width()/2 - w/2;
	var vo=$("#"+id).html();
	if(vo!=null){
		return false;
	}
	
	var html='<div class="mask" id="'+id+'mask" style="width:'+$(window).width()+'px;height:'+$(document).height()+'px;"></div><div class="tanchuang" id="'+id+'"  style="width:'+w+'px;top:'+top+'px;left:'+left+'px;">';
	html+='<div class="hd" id="'+id+'_hd"><a href="javascript:;" class="close"></a>'+hd+'</div>';
	html+='<div class="bd">'+bd+'</div></div>';

	$("body").append(html);
	
	
	Dialogdrag(id)
	$("#"+id+" .close").bind("click",function(){
											
		 $("#"+id).remove();
		 $("#"+id+"mask").remove();
	});
}
function re_tanchuang(id){
	 
	 $("#"+id).remove();
	 $("#"+id+"mask").remove();
}

function Dialogdrag(id){
	var oDrag = $("#"+id);
	var handle = $("#"+id+"_hd");
	
    var T = handle.css('cursor', 'move');
    T.live("selectstart", function(){
        return false;
    });  
	 
	handle.mousedown(function (event)
	{
		var event = event || window.event;
		disX = event.clientX - parseInt(oDrag.css("left"));
		disY = event.clientY - parseInt(oDrag.css("top"));
		$(document).mousemove(function (event)
		{
			var event = event || window.event;
			var iL = event.clientX - disX;
			var iT = event.clientY - disY;
			//var maxL = document.documentElement.clientWidth - oDrag.width();
			//var maxT = document.documentElement.clientHeight - oDrag.height();
			var maxL = document.body.scrollWidth - oDrag.width();
			var maxT = document.body.scrollHeight - oDrag.height();
			iL <= 0 && (iL = 0);
			iT <= 0 && (iT = 0);
			iL >= maxL && (iL = maxL);
			iT >= maxT && (iT = maxT);
			
			oDrag.css("left",iL + "px");
			oDrag.css("top",iT + "px");
			
			return false
		});
		
		$(document).mouseup(function ()
		{
			$(document).unbind("mousemove");
			$(document).unbind("mouseup");
		});
		
		
		return false
	});
	
}
/****************************页面提示***********************/
function tishi(id,bd,url,times){
	times=times==''?4000:times;
	var Top =$(document).scrollTop()+200 ;
	var left = $(window).width()/2-157;
	var vo=$("#"+id).html();
	if(vo!=null){
		$("#"+id).parent().remove();
		//return false;
	}
	var html='<div class="tishi" style="top:'+(Top-20)+'px;left:'+(left)+'px;"><div id="'+id+'">';
	html+=bd+'</div></div>';
	$("body").append(html);
	$(".tishi").css('opacity','0')
	$(".tishi").animate({top:Top+'px',left:left+'px',opacity:'10'},1000,function(){
		if(url){
			window.location.href=url;
		}
	});

	return setTimeout("remove_tishi()",times)

	
}
function remove_tishi(){
	$(".tishi").animate({top:'+=100px',opacity:'0'},1000,function(){$(this).remove()});
}

function menu(obj){
	var menu=$(obj).find(".focus").text();
	$(obj).find("dl").slideDown(100);
	$(obj).find("span a").addClass("focus");
	$(obj).mouseleave(function(){
		$(obj).find("dl").slideUp();
			if(!menu){
				$(obj).find("span a").removeClass("focus");
			}
	})
}

function setTab(menu,name,cursel,n){
  for(var i=1;i<=n;i++){
     var menu2=document.getElementById(menu+i);
	 var con=document.getElementById(name+i);
	 menu2.className=i==cursel?"focus":"";
	 con.style.display=i==cursel?"block":"none";
     }
}

//div resize
//监听div大小变化
(function($, h, c) {
	var a = $([]),
	e = $.resize = $.extend($.resize, {}),
	i,
	k = "setTimeout",
	j = "resize",
	d = j + "-special-event",
	b = "delay",
	f = "throttleWindow";
	e[b] = 250;
	e[f] = true;
	$.event.special[j] = {
		setup: function() {
			if (!e[f] && this[k]) {
				return false;
			}
			var l = $(this);
			a = a.add(l);
			$.data(this, d, {
				w: l.width(),
				h: l.height()
			});
			if (a.length === 1) {
				g();
			}
		},
		teardown: function() {
			if (!e[f] && this[k]) {
				return false;
			}
			var l = $(this);
			a = a.not(l);
			l.removeData(d);
			if (!a.length) {
				clearTimeout(i);
			}
		},
		add: function(l) {
			if (!e[f] && this[k]) {
				return false;
			}
			var n;
			function m(s, o, p) {
				var q = $(this),
				r = $.data(this, d);
				r.w = o !== c ? o: q.width();
				r.h = p !== c ? p: q.height();
				n.apply(this, arguments);
			}
			if ($.isFunction(l)) {
				n = l;
				return m;
			} else {
				n = l.handler;
				l.handler = m;
			}
		}
	};
	function g() {
		i = h[k](function() {
			a.each(function() {
				var n = $(this),
				m = n.width(),
				l = n.height(),
				o = $.data(this, d);
				if (m !== o.w || l !== o.h) {
					n.trigger(j, [o.w = m, o.h = l]);
				}
			});
			g();
		},
		e[b]);
	}
})(jQuery, this);