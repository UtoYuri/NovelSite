<?php
namespace Book\Controller;
use Think\Controller;
class IndexController extends Controller {

    /** 
     * 默认控制器默认动作
     * 展示网站首页
     */  
    public function index(){
        $this->show();
    }
}