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
	 * @return json 注册结果
	 */  
    public function reg(){
    	$mail			 = I('post.mail', '');
    	$pwd			 = I('post.pwd', '');
    	$vertify_code	 = I('post.vertify_code', '');

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

        // 验证邮箱是否获取过验证码
    	$mail_id = $vertify_model->get_mail_id($mail);
    	if (!$mail_id){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '该邮箱尚未获取验证码', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

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

    	// 创建用户模型
    	$user_model = D('User');

    	// 创建账户
    	$session = create_session_key($mail_id);
    	if (!$user_model->create_user($mail_id, $pwd, $session)){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '注册失败', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

    	// 保存session
    	session(null);
    	session(array(
    			'umail_id' => $mail_id, 
    			'session_key' => $session, 
    		));

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