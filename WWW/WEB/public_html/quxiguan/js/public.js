$(function(){
    if($(".menu_fenlei").length>0){
        var detailmenuh=$(".menu_fenlei").height()+40;
       $('.header-tab').on('tap', 'a', function() {
        	var i = $(this).index();
        	console.log($($(this).attr("href")).offset().top-detailmenuh);
            $('html,body').animate({
                scrollTop: ($($(this).attr("href")).offset().top-detailmenuh)
            }, 500);
            return false;
        })
        var obj = document.getElementById("menu_fenlei"),eq=0;
        var top = getTop(obj);
        var navTar = $(".swiper-wrapper");
		$("body,html").scroll(function(){
            var bodyScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
            navTar.find("a").removeClass("active");
            $(".list_content").each(function(i){
                var scrolltop=$(this).offset().top;
                if( scrolltop+$(this).height()-detailmenuh>0){
                    eq=i;
                    return false;
                }
            });
            navTar.find("a:eq("+eq+")").addClass("active")   ;
        });
    }
});
function getTop(e){
	e = e||window.event;
    var offset = e.offsetTop;
    if(e.offsetParent != null) offset += getTop(e.offsetParent);
    return offset;
}

function aa(){
	var navTar = $(".swiper-wrapper");
	var detailmenuh=$(".menu_fenlei").height();
	//console.log(detailmenuh+'detailmenuh');
    var bodyScrollTop = document.documentElement.scrollTop || document.body.scrollTop;
   // console.log(bodyScrollTop+' bodyScrollTop');
    navTar.find("a").removeClass("active");
    $(".list_content").each(function(i){
        var scrolltop=$(this).offset().top;
        //console.log(scrolltop+' scrolltop');
        // console.log($(this).height()+' $(this).height()');
        if(scrolltop+$(this).height()-detailmenuh>0){
            eq=i;
          // console.log(scrolltop+' scrolltop');
          // console.log($(this).height())
            return false;
        }else{
        	//eq=parseInt(eq)+1;   
        	//navTar.find("a:eq("+eq+")").addClass("active");
        }
    });
    navTar.find("a:eq("+eq+")").addClass("active");
}
