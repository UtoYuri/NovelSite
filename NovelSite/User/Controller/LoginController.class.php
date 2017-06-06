<?php
namespace User\Controller;
use Think\Controller;
class LoginController extends Controller {

	/** 
	 * 登录控制器默认动作
	 * 显示注册页面
	 */  
    public function index(){
    	$this->show();
    }

    /** 
     * 登录控制器忘记密码动作
     * 显示忘记密码页面
     */  
    public function forgot(){
        $this->display('forgot');
    }


	/** 
	 * 登录API
	 * @param string $mail 用户名
	 * @param string $pwd 登录密码
	 * @return json 登录结果
	 */  
    public function login(){
    	$mail      = I('post.mail', '');
    	$pwd       = I('post.pwd', '');

    	// 如果post数据不完整
    	// 返回错误提示json
    	if (strlen($mail) == 0 
    		|| strlen($pwd) == 0){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '用户名/密码不能为空', 
    				'data' => array(
    						'redirect' => U('/User/Login/index'), 
    					), 
				), 'json');
    	}

        // 创建验证模型
        $vertify_model = D('Vertify');
        // 创建用户模型
        $user_model = D('User');

        // 获取用户ID
    	$mail_id = $vertify_model->get_mail_id($mail);
        $user_id = $user_model->get_user_id_by_mail($mail_id);

        // 验证用户是否存在
    	if (!$user_id){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '用户不存在', 
    				'data' => array(
    						'redirect' => U('/User/Login/index'), 
    					), 
				), 'json');
    	}


    	// 获取密码
        $password = $user_model->get_user_pwd($user_id);

        // 验证密码是否正确
        // 不正确则返回错误提示
        if (md5($pwd) != $password){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '密码错误', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

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

    	// 登录成功
    	$this->ajaxReturn(array(
				'success' => true, 
				'msg' => '登录成功', 
				'data' => array(
						'redirect' => U('/Book/index'), 
					), 
			), 'json');
    }

    /** 
     * 注销API
     * @return json 注销结果
     */  
    public function logout(){

        // 清除session
        session(null);

        // 注销成功
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '注销成功', 
                'data' => array(
                        'redirect' => U('/User/Login/index'), 
                    ), 
            ), 'json');
    }

    /** 
     * 修改密码API
     * @param string $mail 用户名
     * @param string $pwd 登录密码
     * @return json 登录结果
     */  
    public function change_pwd(){
        $mail          = I('post.mail', '');
        $old_pwd       = I('post.old_pwd', '');
        $new_pwd       = I('post.new_pwd', '');

        // 如果post数据不完整
        // 返回错误提示json
        if (strlen($mail) == 0 
            || strlen($old_pwd) == 0
            || strlen($new_pwd) == 0){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '用户名/旧密码/新密码不能为空', 
                    'data' => array(
                            'redirect' => U('/User/Login/forgot'), 
                        ), 
                ), 'json');
        }

        // 创建验证模型
        $vertify_model = D('Vertify');
        // 创建用户模型
        $user_model = D('User');

        // 获取用户ID
        $mail_id = $vertify_model->get_mail_id($mail);
        $user_id = $user_model->get_user_id_by_mail($mail_id);

        // 验证用户是否存在
        if (!$user_id){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '用户不存在', 
                    'data' => array(
                            'redirect' => U('/User/Login/forgot'), 
                        ), 
                ), 'json');
        }


        // 获取密码
        $password = $user_model->get_user_pwd($user_id);

        // 验证密码是否正确
        // 不正确则返回错误提示
        if (md5($old_pwd) != $password){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '密码错误', 
                    'data' => array(
                            'redirect' => U('/User/Login/forgot'), 
                        ), 
                ), 'json');
        }

        // 修改用户密码
        // 如果修改失败则返回错误提示
        if (!$user_model->update_password($user_id, $new_pwd)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '修改密码失败', 
                    'data' => array(
                            'redirect' => U('/User/Login/forgot'), 
                        ), 
                ), 'json');
        }

        // 保存session
        session(null);

        // 修改成功
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '修改成功，需重新登录', 
                'data' => array(
                        'redirect' => U('/User/Login/index'), 
                    ), 
            ), 'json');
    }
}