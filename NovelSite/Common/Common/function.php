<?php

/** 
 * 生成随机6位数字验证码
 * @return int
 */  
function rand_code(){
	return rand(100000,999999);
}

/** 
 * 生成session_key
 * @param int $user_id 用户ID
 * @return string 会话ID
 */  
function create_session_key($user_id){
	return md5($user_id.time());
}

/** 
 * 生成订单号
 * @param int $user_id 用户ID
 * @param int $book_id 目标ID
 * @return string token
 */  
function create_order(){
	return date('YmdHis').rand(100,999);
}

/** 
 * 向指定邮箱发送邮件
 * @param string $toMail 邮件接收人
 * @param string $subject 邮件主题
 * @param string $body 邮件内容
 * @param bool $isHtml 邮件是否为HTML格式
 * @return bool 发送结果
 */  
function send_mail($toMail, $subject, $body, $isHtml = true){
	include_once 'Mail.class.php';
	$config = array(
	     "from" => C('MAIL_USERNAME'),
	     "to" => $toMail,
	     "subject" => $subject,
	     "body" => $body,
	     "username" => C('MAIL_USERNAME'),
	     "password" => C('MAIL_PASSWORD'),
	     "isHTML" => $isHtml
	   );
	$mail = new MySendMail();
	$mail->setServer(C('MAIL_SMTP'));
	$mail->setMailInfo($config);
	if(!$mail->sendMail()) {
	   return false;
	}
	return true;
}