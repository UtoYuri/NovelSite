<?php

namespace User\Model;
use Think\Model;
class VertifyModel extends Model {
    protected $tableName = 'mail'; 

	/** 
	 * 获取邮箱编号
	 * @param string $mail 邮箱地址
	 * @return int 邮箱ID
	 */  
    public function get_mail_id($mail){
    	$condition = array(
    			'umail' => $mail, 
    		);

    	// 查找邮箱编号
    	$mail_id = $this->where($condition)->getField('uid');
    	if ($mail_id == null){
    		return false;
        }
    	return $mail_id;
    }

	/** 
	 * 刷新数据库验证码和获取时间
	 * @param string $mail 邮箱地址
	 * @param string $vertify 验证码
	 * @return bool 操作结果
	 */  
    public function update_mail_vertify($mail, $vertify){
    	$condition = array(
    			'umail' => $mail, 
    		);
    	$data = array(
    			'umail' => $mail, 
    			'vertify_code' => $vertify, 
    			'last_request_time' => date('Y-m-d H:i:s'), 
    		);
    	// 查找邮箱是否已经存在
    	// 已经存在则直接更新
    	// 不存在则插入新纪录
    	if ((int)$this->where($condition)->count() == 0){
    		$result = $this->add($data);
    	}else{
    		$result = $this->where($condition)->save($data);
    	}
    	return $result != false;
    }

	/** 
	 * 验证验证码
	 * @param string $mail 邮箱地址
	 * @param string $vertify 验证码
	 * @return bool 验证结果
	 */  
    public function check_mail_vertify($mail, $vertify){
    	$condition = array(
    			'umail' => $mail, 
    		);

    	$vertify_code = $this->where($condition)->getField('vertify_code');
    	$last_request_time = $this->where($condition)->getField('last_request_time');

    	// 验证验证码正确性
    	// 验证码有效期为30分钟
    	if ($vertify_code != $vertify 
    		|| strtotime(date('Y-m-d H:i:s')) - strtotime($last_request_time) > 60 * 30){
    		return false;
    	}
    	return true;
    }

    /** 
	 * 验证码请求时间间隔控制
	 * @param string $mail 邮箱地址
	 * @return bool 验证结果
	 */  
    public function check_mail_freq($mail){
    	$condition = array(
    			'umail' => $mail, 
    		);
    	$last_request_time = $this->where($condition)->getField('last_request_time');

    	// 两次验证码发送间隔不可以超过60秒
    	if (strtotime(date('Y-m-d H:i:s')) - strtotime($last_request_time) < 60){
    		return false;
    	}
    	return true;
    }

    /** 
	 * 验证邮箱是否关联账号
	 * @param string $mail 邮箱地址
	 * @return bool 结果
	 */  
    public function is_mail_bind_user($mail, $vertify){
    	$condition = array(
    			'umail' => $mail, 
    		);
    	if ((int)$this->join('t_user on t_mail.uid = t_user.umail_id')->where($condition)->count() == 0){
    		return false;
    	}
    	return true;
    }

}