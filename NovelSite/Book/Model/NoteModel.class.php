<?php

namespace Book\Model;
use Think\Model;
class NoteModel extends Model {
    protected $tableName = 'notes'; 

    /** 
     * 上传阅读笔记
     * @param int $user_id 用户ID
     * @param string $guid 章节GUID
     * @param string $note 阅读笔记
     * @return bool 操作结果
     */  
    public function post_note($user_id, $guid, $note){
        $condition = array(
                'GUID' => $guid,
            );
        $chapter = $this->table('t_novel_chapter')->where($condition)->field('uid')->select();
        if (!count($chapter)){
            return false;
        }
        $data = array(
                'chapter_id' => $chapter[0]['uid'],
                'user_id' => $user_id,
                'notes' => $note,
            );
        $result = $this->add($data);
        return $result != false;
    }


    /** 
     * 获取小说阅读笔记
     * @param int $user_id 用户ID
     * @param string $guid 小说统一标识符
     * @return array 该用户的该小说的阅读笔记
     */  
    public function get_notes($user_id, $guid){
        $condition = array(
                'GUID' => array('LIKE', substr($guid, 0, 10).'%'), 
                'is_active' => true, 
            );

        // 获取章节ID
        $chapters = $this->table('t_novel_chapter')->where($condition)->field('uid')->select();

        $chapters_id = array();
        foreach ($chapters as $key => $value) {
            $chapters_id[] = $value['uid'];
        }

        // 如果找不到对应章节 就直接返回空数组
        if (!count($chapters_id)){
            return array();
        }

        $condition = array(
                'user_id' => $user_id, 
                'chapter_id' => array('IN', $chapters_id), 
            );
        // 阅读笔记
        $result = $this->where($condition)->select();
        return $result;
    }
}