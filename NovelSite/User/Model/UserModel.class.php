<?php

namespace User\Model;
use Think\Model;
class UserModel extends Model {
    protected $tableName = 'user'; 

    /** 
     * 获取用户总览信息
     * @return array 操作结果
     */  
    public function user_dashboard_map(){
        $total = (int)$this->count();
        $reg_tswk = (int)$this->where('DATE_SUB(CURDATE(), INTERVAL 7 DAY) <= reg_time')->count();
        return array(
                'total' => $total, 
                'reg_tswk' => $reg_tswk, 
            );
    }

	/** 
	 * 创建用户
     * @param int $mail_id 邮箱编号
     * @param string $password 密码
	 * @param bool $is_admin 是否注册为管理员
	 * @return bool 操作结果
	 */  
    public function create_user($mail_id, $password, $is_admin = false){
    	$condition = array(
    			'umail_id' => $mail_id, 
    		);
    	$data = array(
    			'umail_id' => $mail_id, 
    			'password' => md5($password), 
    			'is_admin' => $is_admin, 
    		);

    	// 查找用户是否已经存在
    	// 已经存在返回错误
        // 不存在则插入新纪录
    	if ((int)$this->where($condition)->count() == 0){
    		$result = $this->add($data);
    	}else{
    		$result = false;
    	}
    	return $result != false;
    }

    /** 
     * 获取账户ID
     * @param int $mail_id 邮箱编号
     * @return int 账户对应ID
     */  
    public function get_user_id_by_mail($mail_id){
        $condition = array(
                'umail_id' => $mail_id, 
            );

        // 获取用户密码
        $id = (int)$this->where($condition)->getField('uid');
        return $id;
    }


    /** 
     * 获取账户ID
     * @param string $session_key 会话ID
     * @return int 账户对应ID
     */  
    public function get_user_id_by_session($session_key){
        $condition = array(
                'session_key' => $session_key, 
            );

        // 获取用户密码
        $id = (int)$this->where($condition)->getField('uid');
        return $id;
    }

    /** 
     * 获取账户密码
     * @param int $user_id 用户编号
     * @return string 账户对应密码
     */  
    public function get_user_pwd($user_id){
        $condition = array(
                'uid' => $user_id, 
            );

        // 获取用户密码
        $password = $this->where($condition)->getField('password');
        return $password;
    }

    /** 
     * 刷新用户会话ID
     * @param int $user_id 用户编号
     * @param string $session_key 会话ID
     * @return bool 操作结果
     */  
    public function update_session_key($user_id, $session_key){
        $condition = array(
                'uid' => $user_id, 
            );
        $data = array(
                'session_key' => $session_key, 
            );

        // 刷新会话ID
        $result = $this->where($condition)->save($data);
        return $result != false;
    }

    /** 
     * 修改用户密码
     * @param int $user_id 用户编号
     * @param string $new_pwd 新密码
     * @return bool 操作结果
     */  
    public function update_password($user_id, $new_pwd){
        $condition = array(
                'uid' => $user_id, 
            );
        $data = array(
                'password' => md5($new_pwd), 
            );

        // 刷新会话ID
        $result = $this->where($condition)->save($data);
        return $result != false;
    }

    /** 
     * 获取余额
     * @param int $user_id 用户编号
     * @return float 余额
     */  
    public function get_user_pocket($user_id){
        $condition = array(
                'uid' => $user_id, 
            );

        // 获取余额
        $pocket = (float)$this->where($condition)->getField('pocket');
        return $pocket;
    }

    /** 
     * 用户类型
     * @param int $user_id 用户编号
     * @return string 用户类型
     */  
    public function get_user_type($user_id){
        $condition = array(
                'uid' => $user_id, 
            );

        // 获取用户类型
        $is_admin = (float)$this->where($condition)->getField('is_admin');
        return $is_admin ? 'admin' : 'user';
    }


    /** 
     * 分页获取用户列表
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 用户
     */  
    public function get_user($page = 1, $num = 20){
        // 获取留言内容
        $result = $this->join('LEFT JOIN t_mail ON t_mail.uid = t_user.umail_id')
                    ->field('t_mail.umail, t_user.*')
                    ->order('uid ASC')->page($page, $num)->select();
        return $result;
    }
}