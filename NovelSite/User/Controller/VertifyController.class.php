<?php
namespace User\Controller;
use Think\Controller;
class VertifyController extends Controller {

    /** 
     * 验证控制器默认动作
     * 重定向到网站首页
     */  
    public function index(){
        $this->redirect('/Book/index');
    }


	/** 
	 * 邮箱验证码获取API
	 * @param string $mail 邮箱地址
	 * @return json 获取结果
	 */  
    public function get_mail_vertify(){
    	$mail = I('post.mail', '');

    	// 如果post数据不完整
    	// 返回错误提示json
    	if (strlen($mail) == 0){
    		$this->ajaxReturn(array(
    				'success' => false, 
    				'msg' => '邮箱不能为空', 
    				'data' => array(
    						'redirect' => U('/User/Reg/index'), 
    					), 
				), 'json');
    	}

        // 创建验证模型
        $vertify_model = D('Vertify');

        // 查看该邮箱是否已经注册过账号
        // 已经注册过则返回错误提示
        if ($vertify_model->is_mail_bind_user($mail)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '该邮箱已被使用', 
                    'data' => array(
                            'redirect' => U('/User/Reg/index'), 
                        ), 
                ), 'json');
        }

        // 检查邮件发送频率
        // 如果频率过高则返回错误提示
        if (!$vertify_model->check_mail_freq($mail)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '验证码发送频率过高', 
                    'data' => array(
                            'redirect' => U('/User/Reg/index'), 
                        ), 
                ), 'json');
        }

    	// 发送邮箱验证码并存储至数据库
    	// 操作失败则返回错误提示
        $vertify = rand_code();
        if (!send_mail($mail, 'T宅极客-邮箱验证码', '邮箱验证码为 '.$vertify.'<br>验证码30分钟内有效，请勿泄露。') 
            || !$vertify_model->update_mail_vertify($mail, $vertify)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '发送失败', 
                    'data' => array(
                            'redirect' => U('/User/Reg/index'), 
                        ), 
                ), 'json');
        }

        // 邮箱验证码发送成功
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '发送成功', 
                'data' => array(
                        'redirect' => U('/User/Reg/index'), 
                    ), 
            ), 'json');
    }
}