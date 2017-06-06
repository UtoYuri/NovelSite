
/*
 * 显示淘宝推广浮窗
*/
function tbPanelShow(){
	$('.hover-content.tb').show();
}

/*
 * 隐藏淘宝推广浮窗
*/
function tbPanelHide(){
	$('.hover-content.tb').hide();
}

/*
 * 显示赞助我们浮窗
*/
function zfbPanelShow(){
	$('.hover-content.zfb').show();
}

/*
 * 隐藏赞助我们浮窗
*/
function zfbPanelHide(){
	$('.hover-content.zfb').hide();
}

/*
 * 显示登录表单
*/
function logOptShow(obj){
	$(obj).removeClass('unchoosen').siblings().addClass('unchoosen');
	$('.form-reg').hide();
	$('.form-login').show();
}

/*
 * 显示注册表单
*/
function regOptShow(obj){
	$(obj).removeClass('unchoosen').siblings().addClass('unchoosen');
	$('.form-login').hide();
	$('.form-reg').show();
}

/*
 * 获取邮箱验证码
*/
function ajaxGetVertify(){
	var mail = $('#mail').val();
	if (mail.length == 0){
		$('#mail').css("border","1px solid red");
		return;
	}
	$.ajax({
		url : INDEX + "/User/Vertify/get_mail_vertify",
		type : "POST",
		data : {
			"mail" : mail,
		},
		dataType : "json",
		success : function(result) {
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
		error:function(msg){
			console.log(msg);
		}
	});
}

/*
 * 注册
*/
function ajaxReg(){
	var mail 		 = $('#mail').val();
	var vertify 	 = $('#vertify').val();
	var password 	 = $('#pwd').val();
	if (mail.length == 0){
		$('#mail').css("border","1px solid red");
		return;
	}
	if (vertify.length == 0){
		$('#vertify').css("border","1px solid red");
		return;
	}
	if (password.length == 0){
		$('#pwd').css("border","1px solid red");
		return;
	}
	$.ajax({
		url : INDEX + "/User/Reg/reg",
		type : "POST",
		data : {
			"mail" : mail,
			"pwd" : password,
			"vertify_code" : vertify,
		},
		dataType : "json",
		success : function(result) {
			if (result.success){
				location.href = result.data.redirect;
			}else{
				$('.form-reg .tip').text(result.msg);
			}
		},
		error:function(msg){
			console.log(msg);
		}
	});
}

/*
 * 登录
*/
function ajaxLogin(){
	var mail 		 = $('#user').val();
	var password 	 = $('#password').val();
	if (mail.length == 0){
		$('#mail').css("border","1px solid red");
		return;
	}
	if (password.length == 0){
		$('#pwd').css("border","1px solid red");
		return;
	}
	$.ajax({
		url : INDEX + "/User/Login/login",
		type : "POST",
		data : {
			"mail" : mail,
			"pwd" : password,
		},
		dataType : "json",
		success : function(result) {
			if (result.success){
				location.href = result.data.redirect;
			}else{
				$('.form-login .tip').text(result.msg);
			}
		},
		error:function(msg){
			console.log(msg);
		}
	});
}


/*
 * 登录
*/
function ajaxLogout(){
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
}


/*
 * 获取站内信
*/
function ajaxGetMessagaList(){
	// $.ajax({
	// 	url : INDEX + "/Message/Index/getlist",
	// 	type : "POST",
	// 	data : {
	// 		"page" : 1,
	// 		"num" : 20,
	// 	},
	// 	dataType : "json",
	// 	success : function(result) {
	// 		if (result.success){
	// 			location.href = result.data.redirect;
	// 		}else{
	// 			console.log(result);
	// 		}
	// 	},
	// 	error:function(msg){
	// 		console.log(msg);
	// 	}
	// });
}
