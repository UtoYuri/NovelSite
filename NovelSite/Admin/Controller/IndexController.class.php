<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {

	/** 
	 * 默认控制器默认动作
	 * 管理员首页
	 */  
    public function index(){
        $user_id        = I('session.user_id/d', 0);        
        $session_key    = I('session.session_key', '');        

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 验证管理员权限
        if ($user_model->get_user_type($user_id) != 'admin'){
            $err_msg = $err_msg ? $err_msg : '没有管理权限';
        }

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
        }

        // 用户验证出错 返回首页
        if ($err_msg){
            $this->redirect('/Book/Index');
        }

        $this->show();
    }

	/** 
	 * 总览面板
     * @param string $panel_name 面板名称
	 * @return html 总览
	 */  
	public function panel($panel_name = 'dashboard'){

        $err_msg = $this->check_proprity();

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        if ($panel_name == 'user'){
            $this->display('user');
        }elseif ($panel_name == 'book') {
            $this->display('book');
        }elseif ($panel_name == 'propose') {
            $this->display('propose');
        }elseif ($panel_name == 'order') {
            $this->display('order');
        }else{
            // 获取面板数据
            $user_model = D('User/User');
            $user = $user_model->user_dashboard_map();

            // 创建图书模型
            $book_model = D('Book/Book');
            $book = $book_model->book_dashboard_map();

            // 创建书架模型
            $shelf_model = D('Book/Shelf');
            $order = $shelf_model->order_dashboard_map();

            // 创建留言板模型
            $propose_model = D('User/Propose');
            $propose = $propose_model->propose_dashboard_map();

            // 返回html模板
            $this->assign('err_msg', $err_msg);
            $this->assign('user', $user);
            $this->assign('book', $book);
            $this->assign('order', $order);
            $this->assign('propose', $propose);
            $this->display('dashboard');
        }
	}

    /** 
     * 用户面板
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return html 用户
     */  
    public function user($format = 'html'){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        $err_msg = $this->check_proprity();

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        // 获取面板数据
        $user_model = D('User/User');
        $user = $user_model->get_user($page, $num);

        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $user, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $user, 
            ), 'json');
        }else{
            $this->assign('user', $user);
            $this->display('user-item');
        }
    }

    /** 
     * 图书管理面板
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return html 图书管理
     */  
    public function book($format = 'html'){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        $err_msg = $this->check_proprity();

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        // 获取面板数据
        $book_model = D('Book/Book');
        $book = $book_model->get_book_list($page, $num);
        
        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book, 
            ), 'json');
        }else{
            $this->assign('book', $book);
            $this->display('book-item');
        }
    }

    /** 
     * 留言板管理面板
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return html 留言板管理
     */  
    public function propose($format = 'html'){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        $err_msg = $this->check_proprity();

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        // 获取面板数据
        $propose_model = D('User/Propose');
        $propose = $propose_model->get_propose($page, $num);
        
        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $propose, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $propose, 
            ), 'json');
        }else{
            $this->assign('propose', $propose);
            $this->display('propose-item');
        }
    }


    /** 
     * 订单管理面板
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return html 订单管理
     */  
    public function order($format = 'html'){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        $err_msg = $this->check_proprity();

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        // 获取面板数据
        $shelf_model = D('Book/Shelf');
        $order = $shelf_model->get_order_list($page, $num);
        
        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $order, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $order, 
            ), 'json');
        }else{
            $this->assign('order', $order);
            $this->display('order-item');
        }
    }

    /** 
     * 检查用户权限
     * @return string/null error_msg 错误报告
     */  
    private function check_proprity(){

        $user_id        = I('session.user_id/d', 0);        
        $session_key    = I('session.session_key', '');        

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 验证管理员权限
        if ($user_model->get_user_type($user_id) != 'admin'){
            $err_msg = $err_msg ? $err_msg : '没有管理权限';
        }

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
        }

        return $err_msg;
    }
}