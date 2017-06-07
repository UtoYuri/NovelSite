var AjaxItemsGetter = (function () {

	var _instance = null;

	// 请求地址
	var _url = "";
	// 获取页数、数量
	var _page = 1;
	var _num = 20;
	// 没有更多数据
	var _end = false;

	// 上一次请求参数
	var _preArgs = {}

	// 检测url是否为空
	function _isValidUrl(){
		return _url.length > 0;
	}
	// 检测page num是否正确
	function _isValidFormData(){
		return (_page * _num) > 0;
	}
	// 检测是否有更多数据
	function _isEnded(){
		return _end;
	}
	// 检测所有
	function _isValidRequest(){
		if (!_isValidUrl()){
			throw new Error('请求地址为空');
		}
		if (!_isValidFormData()){
			throw new Error('请求参数错误');
		}
		return !_isEnded();
	}

	// 创建Ajax数据获取类
	function _ajaxGetter(url, page, num, end){

		this.init = function(url, page, num, end){
			_url = url || "";
			_page = page || 1;
			_num = num || 20;
			_end = end || false;
			return this;
		}

		this.getItems = function(args){
			// 延续上一次请求参数
			args = args || _preArgs;
			try{
				// 忽略无效请求
				if (!_isValidRequest()){
					return;
				}
			}catch(e){
				console.log(e);
			}
			// 请求前执行函数
			args['beforeFN'] && args['beforeFN']();
			// 开始请求
			$.ajax({
				url : _url,
				type : "POST",
				data : $.extend(
						{
							"page": _page,
							"num": _num,
						}, args['extraFormData'] || {}
					),
				dataType : args['formate'] || "html",
				success : function(result) {
					args['successCb'] && args['successCb'](result);
				},
				error:function(msg){
					args['failedCb'] && args['failedCb'](msg);
				}
			});
			// 请求后执行函数
			args['afterFn'] && args['afterFn']();
			// 保存上一次请求参数
			_preArgs = args || {};
			return this;
		}

		this.prepareNextRequest = function(end){
			_page++;
			_end = end || _end;
		}

		this.init(url, page, num, end);

	}

	_ajaxGetter.prototype = {

	};

	return function(){
		if (!_instance){
			_instance = new _ajaxGetter();
		}
		return _instance;
	};
})();