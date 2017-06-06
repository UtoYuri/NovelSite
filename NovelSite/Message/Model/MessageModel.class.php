<?php

namespace Message\Model;
use Think\Model;
class MessageModel extends Model {
    protected $tableName = 'message'; 

	/** 
     * 发送站内信
     * @param int $sender_id 发件人ID
     * @param int $receiver_id 收件人ID
     * @param string $message 留言
     * @return bool 操作结果
     */  
    public function post_message($sender_id, $receiver_id, $message){
        $data = array(
                'user_sender_id' => $sender_id, 
                'user_receiver_id' => $receiver_id, 
                'message' => $message, 
            );

        // 存储至数据库
        $result = $this->add($data);
        return $result != false;
    }

    /** 
     * 检查用户对信件的访问权限
     * 只有发件人和收件人有访问权限
     * @param int $message_id 站内信ID
     * @param int $user_id 用户ID
     * @return bool 是否有权访问
     */  
    public function check_message_authority($message_id, $user_id){
        $condition = array(
                array(
                    'uid' => $message_id, 
                    'user_sender_id' => $user_id, 
                ),
                array(
                    'uid' => $message_id, 
                    'user_receiver_id' => $user_id, 
                ),
            );
        // 获取站内信内容
        $result = (int)$this->where($condition)->count();
        return $result != 0;
    }

    /** 
     * 更新站内信状态（new readed deleted）
     * 只有收件人可以成功更新站内信
     * @param int $user_id 操作用户ID
     * @param int $message_id 站内信ID
     * @return array 站内信内容
     */  
    public function update_status($user_id, $status = 'readed', $message_id = 0){
        $condition = array(
                'user_receiver_id' => $user_id, 
            );
        if ($message_id != 0){
            $condition['uid'] = $message_id;
        }

        $data = array(
                'status' => $status, 
            );

        // 刷新信件状态
        $result = $this->where($condition)->save($data);
        return $result != false;
    }

    /** 
     * 获取站内信内容详情
     * @param int $message_id 站内信ID
     * @return array 站内信内容
     */  
    public function get_message($message_id){
        $condition = array(
                'uid' => $message_id, 
            );
        // 获取站内信内容
        $result = $this->where($condition)->select();
        return $result;
    }

    /** 
     * 分页获取站内信列表
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 站内信内容
     */  
    public function get_message_list($user_id, $page = 1, $num = 20){
        $condition = array(
                'user_receiver_id' => $user_id, 
            );
        // 获取站内信内容
        $result = $this->where($condition)->order('post_time DESC')->page($page, $num)->select();
        return $result;
    }

    
    /** 
     * 获取所有未读站内信
     * @param int $user_id 操作用户ID
     * @return array 站内信内容
     */  
    public function get_unread_message_list($user_id){
        $condition = array(
                'user_receiver_id' => $user_id, 
                'status' => 'new', 
            );
        // 获取站内信内容
        $result = $this->where($condition)->select();
        return $result;
    }
}