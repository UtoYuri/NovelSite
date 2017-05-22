<?php
namespace User\Controller;
use Think\Controller;
class IndexController extends Controller {

	/** 
	 * 默认控制器默认动作
	 * 重定向到网站首页
	 */  
    public function index(){
    	$this->redirect('/Book/Index');
    }
}