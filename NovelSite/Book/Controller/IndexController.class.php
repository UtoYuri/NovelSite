<?php
namespace Book\Controller;
use Think\Controller;
class IndexController extends Controller {

    /** 
     * 默认控制器默认动作
     * 展示网站首页
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

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
        }

    	// 尚未登录则跳转注册页面
    	if ($err_msg){
    		$this->redirect('/User/Reg');
    	}

        // 获取账户余额
        $pocket = $user_model->get_user_pocket($user_id);

        // 获取用户类型
        $user_type = $user_model->get_user_type($user_id);

        // 创建站内信模型
        $message_model = D('Message/Message');
    	// 获取消息列表
    	$message_list = $message_model->get_unread_message_list($user_id);

    	// 绑定消息并展示主页
    	$this->assign('message_list', $message_list);
        $this->assign('pocket', $pocket);
    	$this->assign('user_type', $user_type);
        $this->show();
    }
}