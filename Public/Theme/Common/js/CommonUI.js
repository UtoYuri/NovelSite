var CommonUI = (function () {

	// 账户管理类
	var _accountManager = AccountManager();

	// 创建账户管理类
	function _commonUI(){

		if (!(this instanceof CommonUI)){
			return new _commonUI();
		}

	}

	_commonUI.prototype = {

		/*
		 * 显示淘宝推广浮窗
		*/
		tbPanelShow: function (){
			$('.hover-content.tb').show();
		},


		/*
		 * 隐藏淘宝推广浮窗
		*/
		tbPanelHide: function (){
			$('.hover-content.tb').hide();
		},

		/*
		 * 显示赞助我们浮窗
		*/
		zfbPanelShow: function (){
			$('.hover-content.zfb').show();
		},

		/*
		 * 隐藏赞助我们浮窗
		*/
		zfbPanelHide: function (){
			$('.hover-content.zfb').hide();
		},

		/*
		 * 显示登录表单
		*/
		logOptShow: function (obj){
			$(obj).removeClass('unchoosen').siblings().addClass('unchoosen');
			$('.form-reg').hide();
			$('.form-login').show();
		},

		/*
		 * 显示注册表单
		*/
		regOptShow: function (obj){
			$(obj).removeClass('unchoosen').siblings().addClass('unchoosen');
			$('.form-login').hide();
			$('.form-reg').show();
		},

		/*
		 * 获取邮箱验证码
		*/
		getVertify: function (){
			var mail = $('#mail').val();
			_accountManager.getVertify({
				"url": INDEX + "/User/Vertify/get_mail_vertify",
				"extraFormDate": {
					"mail" : mail,
				},
				"successCb": function(result){
					if (result.success){
						$('#user').css("border","1px solid green");
						$('.form-reg .tip').text(result.msg);
						$('#get_vertify').attr("disabled", true); 
						var timeout = 60;
						var timer = setInterval(function(){
							--timeout;
							$('#get_vertify').val(timeout + "秒后可重新发送");
							if (timeout < 0){
								$('#get_vertify').val('发送验证码');
								$('#get_vertify').attr("disabled", false); 
								clearInterval(timer);
							}
						}, 1000);
					}else{
						$('.form-reg .tip').text(result.msg);
					}
				},
				"failedCb": function(msg){
					console.log(msg);
				}
			});
		}, 

		/*
		 * 注册
		*/
		reg: function (){
			var mail 		 = $('#mail').val();
			var vertify 	 = $('#vertify').val();
			var password 	 = $('#pwd').val();
			
			_accountManager.getVertify({
				"url": INDEX + "/User/Reg/reg",
				"extraFormDate": {
					"mail" : mail,
					"pwd" : password,
					"vertify_code" : vertify,
				},
				"successCb": function(result){
					if (result.success){
						location.href = result.data.redirect;
					}else{
						$('.form-reg .tip').text(result.msg);
					}
				},
				"failedCb": function(msg){
					$('.form-reg .tip').text(msg);
				}
			});
		},

		/*
		 * 登录
		*/
		login: function (){
			var mail 		 = $('#user').val();
			var password 	 = $('#password').val();
			_accountManager.getVertify({
				"url": INDEX + "/User/Login/login",
				"extraFormDate": {
					"mail" : mail,
					"pwd" : password,
				},
				"successCb": function(result){
					if (result.success){
						location.href = result.data.redirect;
					}else{
						$('.form-login .tip').text(result.msg);
					}
				},
				"failedCb": function(msg){
					$('.form-login .tip').text(msg);
				}
			});
		},


		/*
		 * 注销
		*/
		logout: function (){
			$.ajax({
				url : INDEX + "/User/Login/logout",
				type : "POST",
				dataType : "json",
				success : function(result) {
					if (result.success){
						location.href = result.data.redirect;
					}else{
						console.log(msg);
					}
				},
				error:function(msg){
					console.log(msg);
				}
			});
		},

	};

	return _commonUI;
})();
