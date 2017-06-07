var AccountManager = (function () {

	var _instance = null;

	// 检测url
	function _isValidUrl(url){
		return url.length > 0 ? null : "请求url错误";
	}
	// 检测邮箱
	function _isValidMail(mail){
		return mail.length > 0;
	}
	// 检测密码
	function _isValidPWD(pwd){
		return pwd.length > 0;
	}
	// 检测验证码
	function _isValidVertify(vertify){
		return vertify.length > 0;
	}
	// 检测所有
	function _isValidRequest(args){
		if (!_isValidMail(args['mail'])){
			return "邮箱格式错误";
		}
		if (args['pwd'] && !_isValidPWD(args['pwd'])){
			return "密码不符合规则";
		}
		if (args['vertify_code'] && !_isValidVertify(args['vertify_code'])){
			return "验证码格式错误";
		}
		return null;
	}

	// ajax请求
	function ajax(args){
		args = args || {};
		// 忽略无效请求
		if ((errMsg = _isValidRequest(args['extraFormDate'] || {})) || (errMsg = _isValidUrl(args['url']))){
			args['failedCb'] && args['failedCb'](errMsg);
			return;
		}
		// 请求前执行函数
		args['beforeFN'] && args['beforeFN']();
		// 开始请求
		$.ajax({
			url : args['url'],
			type : "POST",
			data : args['extraFormDate'] || {},
			dataType : args['formate'] || "json",
			success : function(result) {
				args['successCb'] && args['successCb'](result);
			},
			error:function(msg){
				args['failedCb'] && args['failedCb'](msg);
			}
		});
		// 请求后执行函数
		args['afterFn'] && args['afterFn']();
	}

	// 创建账户管理类
	function _accountManager(){

	}

	_accountManager.prototype = {

		getVertify : function(args){
			ajax(args);
		},

		reg : function(args){
			ajax(args);
		},

		login : function(args){
			ajax(args);
		},

		logout : function(args){
			ajax(args);
		}
	};

	return function(){
		if (!_instance){
			_instance = new _accountManager();
		}
		return _instance;
	};
})();