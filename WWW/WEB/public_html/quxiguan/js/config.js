//公共地址
	var config = {
		base_url: "http://quxiguan.tainongnongzi.com/public_html/index.php",
		img_url: "http://quxiguan.tainongnongzi.com/public_html"
	};

	//获取url地址的各个参数(单独获取)
	function GetQueryString(name) {
		var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)");
		var r = window.location.search.substr(1).match(reg);
		if (r != null) return decodeURI(r[2]);
		return null;
	}

	/*在手机端当用户调出手机键盘的时候,让本页面的那个大的按钮隐藏*/
	var windheight = $(window).height();
	$(document).ready(function() {
		$(window).resize(function() {
			var docheight = $(window).height();
			if (docheight < windheight) {
				$(".HideElement").hide();
			} else {
				$(".HideElement").show();
			}
		});
	});

	function uniq(array) {
		var temp = []; //一个新的临时数组
		for (var i = 0; i < array.length; i++) {
			if (temp.indexOf(array[i]) == -1) {
				temp.push(array[i]);
			}
		}
		return temp;
	}
	
	function openWebview(params) {
		mui.openWindow({
			url: params.url,
			id: params.id,
			waiting:{
				autoShow:false
			}
		})
	}
	
	(function($, window) {
    //显示加载框
    $.showLoading = function(message,type) {
        if ($.os.plus && type !== 'div') {
            $.plusReady(function() {
                plus.nativeUI.showWaiting(message);
            });
        } else {
            var html = '';
            html += '<i class="mui-spinner mui-spinner-white"></i>';
            html += '<p class="text">' + (message || "数据加载中") + '</p>';

            //遮罩层
            var mask=document.getElementsByClassName("mui-show-loading-mask");
            if(mask.length==0){
                mask = document.createElement('div');
                mask.classList.add("mui-show-loading-mask");
                document.body.appendChild(mask);
                mask.addEventListener("touchmove", function(e){e.stopPropagation();e.preventDefault();});
            }else{
                mask[0].classList.remove("mui-show-loading-mask-hidden");
            }
            //加载框
            var toast=document.getElementsByClassName("mui-show-loading");
            if(toast.length==0){
                toast = document.createElement('div');
                toast.classList.add("mui-show-loading");
                toast.classList.add('loading-visible');
                document.body.appendChild(toast);
                toast.innerHTML = html;
                toast.addEventListener("touchmove", function(e){e.stopPropagation();e.preventDefault();});
            }else{
                toast[0].innerHTML = html;
                toast[0].classList.add("loading-visible");
            }
        }   
    };

    //隐藏加载框
      $.hideLoading = function(callback) {
        if ($.os.plus) {
            $.plusReady(function() {
                plus.nativeUI.closeWaiting();
            });
        } 
        var mask=document.getElementsByClassName("mui-show-loading-mask");
        var toast=document.getElementsByClassName("mui-show-loading");
        if(mask.length>0){
            mask[0].classList.add("mui-show-loading-mask-hidden");
        }
        if(toast.length>0){
            toast[0].classList.remove("loading-visible");
            callback && callback();
        }
      }
})(mui, window);
