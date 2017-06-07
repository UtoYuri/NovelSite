var ReaderUI = (function () {

	var _instance = null;

	// 主窗体进度条
	var _progress = null;


	// 创建账户管理类
	function _readerUI(){

	}

	_readerUI.prototype = {

		/*
		 * 打开阅读器
		*/
		openReader: function (){
			$('.reader-main .detail').hide();
			$('.reader-main .chapter').show();
			$('.reader-main .chapter').html("<p style='text-align:center; margin-top: 20px;'>加载中...</p>");
		},

		/*
		 * 关闭阅读器
		*/
		closeReader: function (){
			$('.reader-main .detail').show();
			$('.reader-main .chapter').hide();
		},

		/*
		 * 获取章节信息
		*/
		loadChapter: function (token, chapterGUID){
			
			$.ajax({
				url : INDEX + "/Book/Read/view/token/" + token + "/guid/" + chapterGUID,
				type : "GET",
				dataType : "json",
				success : function(result) {
					if (result.success){
						$('.reader-main .chapter').html(result.data);
					}else{
						$('.reader-main .chapter').html(result.msg);
						console.log(result);
					}
				},
				error:function(msg){
					console.log(msg);
				}
			});
		},


		/*
		 * 购买商品
		*/
		purchaseItem: function (obj){
			var guid 	= obj.getAttribute('book-guid');

			$.ajax({
				url : INDEX + "/Book/purchase/purchase",
				type : "POST",
				data : {
					'guid' : guid,
				},
				dataType : "json",
				success : function(result) {
					if (result.success){
						obj.innerHTML = "开始阅读";
						obj.setAttribute("book-order", result.data.uorder);
						obj.setAttribute("book-token", result.data.token);
						obj.id = "read";
						obj.setAttribute("onclick", "ReaderUI().read(this)");
					}else{
						console.log(result);
					}
				},
				error:function(msg){
					console.log(msg);
				}
			});
		},

		read: function(obj){
			var token = $('#read').attr('book-token');
			var chapterGUID = $(obj).attr('chapter-guid');
			$(obj).addClass('choosen').parent().siblings().find('a').removeClass('choosen');

			if (!token){
				alert("请先购买");
				return;
			}

			this.openReader();
			this.loadChapter(token, chapterGUID);
		}


	};

	return function(){
		if (!_instance){
			_instance = new _readerUI();
		}
		return _instance;
	};
})();
