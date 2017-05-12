<?php
namespace Book\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
    	// echo U('Book/Index/index/', array('id' => 1, ));
    	// $this->ajaxReturn(array('id' => 1, ), 'json');
    	// $this->redirect('Index/index', array('cate_id' => 2), 1, '页面跳转中...');
        // $this->success('登录成功', U('/User/index'), 1);
        // $test = D('Test');
        // echo $test->query("select * from t_user");
        // echo $test->query("select * from user");
        $this->assign('data', array('a' => 'asdasd', ));
        $this->show();
    }
}