// JavaScript Document

function img_init(){
	var url='/Plugin?id='+siteId;
		$.ajax({
			url:url,
			success: function(txt){
				$("#img_con").html(txt);
			}
		})
}

function insert(obj){
var img=imgs.join(",");
imgs=[];
var url='/Plugin/local?id='+siteId;
$("#img_con").html("加载中，请勿关闭浏览器");
		$.ajax({
			url:url,
			data:'img='+img+"&alt="+ alt,
			success: function(txt){
				
				obj.insertHtml(txt).hideDialog().focus();
			}
	})

	
}

KindEditor.plugin('imgsearch',function(K){
			var self = this, name = 'imgsearch';	
			var html='';
			
			self.clickToolbar(name, function(){
				img_init();							 
				dialog = self.createDialog({
					name : name,
					width : 700,
					height : 630,
					title : "图片搜索",		
					body : '<div><div id="img_con"></div></div>',
					yesBtn : {
							name:"插入",
							click : function(e) {
								insert(self)
								
							},
						}//yesBtn
										   
				})//dialog			 
											 
	    });//clickToolbar	
		
})