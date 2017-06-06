<?php
namespace User\Controller;
use Think\Controller;
class RegController extends Controller {

	/** 
	 * 注册控制器默认动作
	 * 显示注册页面
	 */  
    public function index(){
    	$this->show();
    }


	/** 
	 * 注册API
	 * @param string $mail 用户名
	 * @param string $pwd 登录密码
     * @param string $vertify_code 验证码
	 * @param bool $is_admin 是否注册为管理员
	 * @return json 注册结果
	 */  
    public function reg(){
    	$mail			 = I('post.mail', '');
    	$pwd			 = I('post.pwd', '');
    	$vertify_code	 = I('post.vertify_code', '');
        $is_admin        = 0;

    	// 如果post数据不完整
    	// 返回错误提示json
    	if (strlen($mail) == 0 
    		|| strlen($pwd) == 0 
    		|| strlen($vertify_code) == 0){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '用户名/密码/验证码不能为空', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

        // 创建验证模型
        $vertify_model = D('Vertify');

    	// 验证邮箱验证码
    	// 验证失败则返回错误提示
    	if (!$vertify_model->check_mail_vertify($mail, $vertify_code)){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '验证码错误', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

        // 获取邮箱ID
        $mail_id = $vertify_model->get_mail_id($mail);

    	// 创建用户模型
    	$user_model = D('User');

    	// 创建账户
        // 创建失败则返回错误提示
    	if (!$user_model->create_user($mail_id, $pwd, $is_admin)){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '注册失败', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

        // 获取用户ID
        $user_id = $user_model->get_user_id_by_mail($mail_id);

        // 刷新用户会话ID
        // 如果刷新失败则返回错误提示
        $session = create_session_key($user_id);
        if (!$user_model->update_session_key($user_id, $session)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '刷新SESSION出错', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

    	// 保存session
        session(null);
        session('user_id', $user_id);
        session('mail', $mail);
        session('session_key', $session);

    	// 注册成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '注册成功', 
				'data' => array(
						'redirect' => U('/Book/index'), 
					), 
			), 'json');
    }
}