
progress = null;

defaultCurlUrl = "/Book/Search/page";
defaultCurlFormData = {
	'page' : 1,
	'num' : 20
};
noMoreMovie = false;

/*
 * 获取分类信息
*/
function ajaxCateItem(){
	$.ajax({
		url : INDEX + "/Book/Search/cate",
		type : "POST",
		data : {},
		dataType : "html",
		success : function(result) {
			$('.category-main .cate').html(result);
		},
		error:function(msg){
			console.log(msg);
		}
	});
}

/*
 * 获取检索结果
*/
function ajaxMovieItem(){
	if (progress == null){
		progress = progressJs('.waterfall-main');
	}
	progress.start().set(20).autoIncrease(10, 500);
	$.ajax({
		url : INDEX + defaultCurlUrl,
		type : "POST",
		data : defaultCurlFormData,
		dataType : "html",
		success : function(result) {
			if (result.indexOf('没有更多') != -1){
				noMoreMovie = true;
			}
			$('.movie-list').append(result);
			// 下一页formdata
			++defaultCurlFormData['page'];
			progress.set(100).end();
		},
		error:function(msg){
			console.log(msg);
			progress.set(100).end();
		}
	});
}

/*
 * 更新检索规则
*/
function changeMovieGettingRule(obj){
	$('.category-main ul li a').removeClass('choosen')
	$this = $(obj);
	$this.addClass('choosen');

	// 更改检索规则
	var rule = $this.attr('rank-type');
	if (rule === 'all'){
		defaultCurlUrl = "/Book/Search/page";
		defaultCurlFormData = {
			'page' : 1,
			'num' : 20
		};
	}else if (rule === 'record'){
		defaultCurlUrl = "/Book/Shelf/page";
		defaultCurlFormData = {
			'page' : 1,
			'num' : 20
		};
	}else{
		defaultCurlUrl = "/Book/Search/rank";
		defaultCurlFormData = {
			'category' : $this.attr('rank-name'),
			'page' : 1,
			'num' : 20
		};
	}

	// 重置是否没有更多
	noMoreMovie = false;
	// 清屏
	$('.movie-list').html("");
	// 检索
	ajaxMovieItem();
}


/*
 * 搜索模式
*/
function setSearchRule(){
	$('.category-main ul li a').removeClass('choosen');
	$('.default').addClass('choosen');

	// 搜索参数
	defaultCurlUrl = "/Book/Search/search";
	defaultCurlFormData = {
		'key' : $('#key').val(),
		'page' : 1,
		'num' : 20
	};

	// 重置是否没有更多
	noMoreMovie = false;
	// 清屏
	$('.movie-list').html("");
	// 检索
	ajaxMovieItem();
}
