<?php

namespace Book\Model;
use Think\Model;
class ReadModel extends Model {
    protected $tableName = 'read_record'; 

    /** 
     * 获取章节详情
     * @param string $guid 章节统一标识符
     * @return array 小说详情
     */  
    public function get_chapter_info($guid){
        $condition = array(
                'GUID' => $guid, 
                'is_active' => true, 
            );

        // 获取图书信息
        $result = $this->table('t_novel_chapter')->where($condition)
                        ->select();
        return $result;
    }

    /** 
     * 添加阅读记录
     * @param int $user_id 用户ID
     * @param string $guid 章节统一标识符
     * @return bool 操作结果
     */  
    public function post_read_record($user_id, $guid){
        $condition = array(
                'GUID' => $guid, 
            );

        // 获取章节ID
        $result = $this->table('t_novel_chapter')->where($condition)->field('uid')
                        ->select();
        if (!count($result)){
            return false;
        }

        $data = array(
                'user_id' => $user_id, 
                'chapter_id' => $result[0]['uid'], 
            );
        if ((int)$this->where($condition)->count() == 0){
            $result = $this->add($data);
        }else{
            $result = true;
        }
        return $result != false;
    }

    /** 
     * 获取用户阅读统计和等级
     * @param int $user_id 用户ID
     * @return bool 操作结果
     */  
    public function get_readed_words($user_id){
        // 获取用户总阅读字数
        $condition = array(
                'user_id' => $user_id, 
            );

        $words = (int)$this->join('LEFT JOIN t_novel_chapter ON (t_read_record.chapter_id = t_novel_chapter.uid)')
                        ->where($condition)->sum('words');

        return $words;
    }

    /** 
     * 获取用户阅读统计和等级
     * @param int $user_id 用户ID
     * @return bool 操作结果
     */  
    public function get_user_level($user_id){
        // 获取用户等级
        $condition = array(
                'min_words' => array('ELT', $this->get_readed_words($user_id)), 
                'is_active' => true, 
            );
        $level = $this->table('t_level')
                        ->field('uid, uname')
                        ->where($condition)
                        ->order('min_words DESC')
                        ->limit(1)
                        ->select();
        // 更新用户等级
        $data = array(
                'user_level' => $level[0]['uid'], 
            );
        $this->where($user_condition)->save($data);
        return $level[0]['uname'];
    }


}