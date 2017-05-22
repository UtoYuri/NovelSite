<?php

namespace User\Model;
use Think\Model;
class ProposeModel extends Model {
    protected $tableName = 'propose'; 

	/** 
	 * 发布留言
     * @param int $user_id 用户ID
	 * @param string $message 留言
	 * @return bool 操作结果
	 */  
    public function post_propose($user_id, $message, $tag){
    	$data = array(
    			'user_id' => $user_id, 
    			'tag' => $tag, 
    			'message' => $message, 
    		);

    	// 存储至数据库
		$result = $this->add($data);
    	return $result != false;
    }

    /** 
     * 分页获取留言列表
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 留言内容
     */  
    public function get_propose($page = 1, $num = 20){
        // 获取留言内容
        $result = $this->order('post_time DESC')->page($page, $num)->select();
        return $result;
    }
}