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
        $user_id        = I('session.user_id/d', 0);        
        $session_key    = I('session.session_key', '');        

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
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
        $user_id        = I('session.user_id/d', 0);        
        $session_key    = I('session.session_key', '');        

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
        }

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/User/Reg/reg'), 
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