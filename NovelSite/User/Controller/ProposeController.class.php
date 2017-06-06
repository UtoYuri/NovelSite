<?php
namespace User\Controller;
use Think\Controller;
class ProposeController extends Controller {

	/** 
	 * 留言控制器默认动作
	 * 显示留言板页面
	 */  
    public function index(){
    	$this->show();
    }

	/** 
	 * 留言API
     * @param string $tag 标签
	 * @param string $message 留言
	 * @return json 留言结果
	 */  
    public function post(){
        $tag            = I('post.tag', '');
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
    	if (strlen($message) == 0
            || strlen($tag) == 0){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '留言/标签不能为空', 
    				'data' => array(
    						'redirect' => U('/User/Propose/index'), 
    					), 
				), 'json');
    	}

        // 创建留言板模型
        $propose_model = D('Propose');

        // 留言失败则返回错误提示
        if (!$propose_model->post_propose($user_id, $tag, $message)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '留言失败', 
                    'data' => array(
                            'redirect' => U('/User/Propose/index'), 
                        ), 
                ), 'json');
        }

    	// 留言成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '留言成功', 
				'data' => array(
						'redirect' => U('/User/Propose/index'), 
					), 
			), 'json');
    }
}