<?php
namespace Book\Controller;
use Think\Controller;
class ShelfController extends Controller {

    /** 
     * 书架控制器默认动作
     * @param int $page 页码
     * @param int $num 页面容量
     * 展示我的书架页面
     */  
    public function index($page = 1, $num = 20){
        $session_key    = I('session.session_key', '');

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = '请登录后查看';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $err_msg = '登录状态已过期，请重新登录';
        }

        // 创建小说模型
        $shelf_model = D('Shelf');

        if (!$err_msg){
            $shelf = $shelf_model->get_shelf_list($user_id, $page, $num);
            $log_status = true;
        }else{
            $shelf = $err_msg;
            $log_status = false;
        }

        // 模板赋值并展示
        $this->assign('shelf', $shelf);
        $this->show();
    }

    /** 
     * 书架信息API
     * @param int $page 页码
     * @param int $num 页面容量
     * @return json 书架结果
     */  
    public function page($page = 1, $num = 20){
        $session_key    = I('session.session_key', '');

        // 验证登录状态
        if (strlen($session_key) == 0){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '请先登录', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '登陆状态已失效', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建小说模型
        $shelf_model = D('Shelf');

        // 获取书架信息
        $shelf = $shelf_model->get_shelf_list($user_id, $page, $num);

        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $shelf, 
            ), 'json');
    }
}