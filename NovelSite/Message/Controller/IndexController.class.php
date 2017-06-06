<?php
namespace Message\Controller;
use Think\Controller;
class IndexController extends Controller {

	/** 
	 * 默认控制器默认动作
	 * 显示站内信页面
	 */  
    public function index(){
        $this->show();
    }

	/** 
	 * 发送站内信API
	 * @param int $receiver_id 收信人ID
	 * @param string $pwd 登录密码
	 * @return json 登录结果
	 */  
	public function post(){

        $receiver_id    = I('post.receiver_id/d', 0);
        $message        = I('post.message', '');
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

    	// 如果post数据不完整
    	// 返回错误提示json
    	if (strlen($message) == 0){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '留言不能为空', 
    				'data' => array(
    						'redirect' => U('/Message/Index/index'), 
    					), 
				), 'json');
    	}

    	// 如果收件人不存在
    	// 返回错误提示json
    	if (!$receiver_id){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '收件人不存在', 
    				'data' => array(
    						'redirect' => U('/Message/Index/index'), 
    					), 
				), 'json');
    	}

        // 检测自发自收
        if ($sender_id == $receiver_id){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '收件人不可以是自己', 
                    'data' => array(
    						'redirect' => U('/Message/Index/index'), 
                        ), 
                ), 'json');
        }

        // 创建站内信模型
        $message_model = D('Message');

        // 发送失败则返回错误提示
        if (!$message_model->post_message($sender_id, $receiver_id, $message)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '发送失败', 
                    'data' => array(
    						'redirect' => U('/Message/Index/index'), 
                        ), 
                ), 'json');
        }

    	// 发送成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '发送成功', 
				'data' => array(
						'redirect' => U('/Message/Index/index'), 
					), 
			), 'json');
	}

	/** 
	 * 获取消息列表API
	 * @return json 消息列表结果
	 */  
	public function getlist(){
        $page    		= I('post.page/d', 1);
        $num    		= I('post.num/d', 20);
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

        // 创建站内信模型
        $message_model = D('Message');

    	// 获取成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '获取成功', 
				'data' => $message_model->get_message_list($user_id, $page, $num), 
			), 'json');
	}


	/** 
	 * 获取站内信详细API
	 * @param $message_id 信件ID
	 * @return json 指定信件详情结果
	 */  
	public function get(){
        $message_id    	= I('post.message_id/d', 0);
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

        // 创建站内信模型
        $message_model = D('Message');

    	// 检查用户权限
    	// 无权访问信件则返回错误提示
    	if (!$message_model->check_message_authority($message_id, $user_id)){
    		$this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '无权访问该信件', 
                    'data' => array(
                            'redirect' => U('/Message/Index/index'), 
                        ), 
                ), 'json');
    	}

    	// 获取信件内容
    	$data = $message_model->get_message($message_id);

    	// 更新状态为已读
    	$message_model->update_status($user_id, 'readed', $message_id);

    	// 获取成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '获取成功', 
				'data' => $data, 
			), 'json');
	}

	/** 
	 * 个人所有站内信标记已读
	 * @return json 操作结果
	 */  
	public function clear(){
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

        // 创建站内信模型
        $message_model = D('Message');

    	// 更新状态为已读
    	$message_model->update_status($user_id, 'readed');

    	// 标记成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '标记成功', 
                    'data' => array(
                            'redirect' => U('/Message/Index/index'), 
                        ), 
			), 'json');
	}
}