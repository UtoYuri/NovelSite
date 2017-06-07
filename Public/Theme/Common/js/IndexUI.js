var IndexUI = (function () {

	var _instance = null;

	// 账户管理类
	var _accountManager = AccountManager();
	// 主窗体进度条
	var _progress = null;
	// 商品获取类
	var _ajaxGetter = AjaxItemsGetter();
	// 播放器
	var _player = null;

	// 创建账户管理类
	function _indexUI(){

	}

	_indexUI.prototype = {

		/*
		 * 初始化AjaxGetter
		*/
		initAjaxGetter: function(){
			_ajaxGetter.init(INDEX + "/Book/Search/page", 1, 20, false);
		},

		/*
		 * 设置进度条
		*/
		setProgress: function(obj){
			_progress = obj;
		},

		/*
		 * 初始化分类信息
		*/
		getCateItems: function(){
			// 获取分类信息
			_ajaxGetter.init(INDEX + "/Book/Search/cate", 1, 20, false)
			.getItems({
				"successCb" : function(result){
									$('.category-main .cate').html(result);
								},
				"failedCb" : function(msg){
									console.log(msg);
								}
			});
		},

		getGoodsItems: function(args){
			args = args || {};
			// 获取Items
			_ajaxGetter.getItems($.extend({
				"beforeFn" : function(){
					_progress.start().set(20).autoIncrease(10, 500);
				},
				"successCb" : function(result){
					if (result.indexOf('没有更多') != -1){
						_ajaxGetter.prepareNextRequest(true);
					}
					$('.movie-list').append(result);
					_progress.set(100).end();
					_ajaxGetter.prepareNextRequest();
				},
				"failedCb" : function(msg){
					_progress.set(100).end();
					console.log(msg);
				}
			}, args));
		},

		/*
		 * 更新检索规则
		*/
		changeMovieGettingRule: function (obj){
			$('.category-main ul li a').removeClass('choosen')
			$this = $(obj);
			$this.addClass('choosen');

			// 更改检索规则
			extraFormData = {}
			var rule = $this.attr('rank-type');
			if (rule === 'all'){
				_ajaxGetter.init(INDEX + "/Book/Search/page", 1, 20, false);
			}else if (rule === 'record'){
				_ajaxGetter.init(INDEX + "/Book/Shelf/page", 1, 20, false);
			}else{
				_ajaxGetter.init(INDEX + "/Book/Search/rank", 1, 20, false);
				extraFormData = {
					'category' : $this.attr('rank-name'),
				}
			}
			// 清屏
			$('.movie-list').html("");
			// 检索
			this.getGoodsItems({
				"extraFormData": extraFormData,
			});
		},


		/*
		 * 搜索模式
		*/
		setSearchRule: function (){
			$('.category-main ul li a').removeClass('choosen');
			$('.default').addClass('choosen');

			_ajaxGetter.init(INDEX + "/Book/Search/search", 1, 20, false);
			extraFormData = {
				'key' : $('#key').val(),
			}
			// 清屏
			$('.movie-list').html("");
			// 检索
			this.getGoodsItems({
				"extraFormData": extraFormData,
			});
		},

		/*
		 * 关闭播放器层
		*/
		closePlayer: function (){
			$('.player-panel').hide();
			if (_player){
				_player.pause();
			}
		},

		/*
		 * 开始播放
		*/
		play: function (src, cover){
			$('.player-panel').show();
			if (_player === null){
				_player = new DPlayer({
			        element: document.getElementById('player'),
			        autoplay: false,
			        theme: '#FADFA3',
			        loop: false,
			        lang: 'en',
			        preload: 'auto', 
			        screenshot: true,
			        hotkey: true,
			        video: {
			            url: src,
			            pic: cover,
			            type: 'auto',
			        },
			    });
			    return;
			}
			_player.switchVideo({
	            url: src,
	            pic: cover,
	            type: 'auto',
	        });
		},

	};

	return function(){
		if (!_instance){
			_instance = new _indexUI();
		}
		return _instance;
	};
})();
