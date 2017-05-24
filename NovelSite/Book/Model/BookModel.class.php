<?php

namespace Book\Model;
use Think\Model;
class BookModel extends Model {
    protected $tableName = 'novel_info'; 

    /** 
     * 分页图书检索列表
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 图书列表
     */  
    public function get_book_list($page = 1, $num = 20){
        // 获取图书信息
        $result = $this->order('update_time DESC')->page($page, $num)->select();
        return $result;
    }

    /** 
     * 获取小说详情
     * @param string $guid 小说统一标识符
     * @return array 小说详情
     */  
    public function get_book_info($guid){
        $condition = array(
                'GUID' => $guid, 
                't_novel_info.is_active' => true, 
            );

        // 获取图书信息
        $result = $this->where($condition)
                        ->field('GUID, title, author, cover, t_novel_cate.uname AS category, price, discount, description, meta_key, meta_desc, update_time')
                        ->join('LEFT JOIN t_novel_cate ON (t_novel_info.ucate_id = t_novel_cate.uid)')
                        ->select();
        return $result;
    }

    /** 
     * 搜索小说
     * @param string $key 关键字
     * @param string $field 检索字段
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 小说详情
     */  
    public function search_book($key = '', $field = '', $page = 1, $num = 20){
        $condition = array(
                't_novel_info.is_active' => true, 
            );

        if ($field == 'author'){
            $condition['author'] = array('LIKE', '%'.$key.'%');
        }elseif ($field == 'title') {
            $condition['title'] = array('LIKE', '%'.$key.'%');
        }else{
            $condition['_complex'] = array(
                    'title' => array('LIKE', '%'.$key.'%'),
                    'author' => array('LIKE', '%'.$key.'%'),
                    '_logic' => 'OR',
                );
        }

        // 获取图书信息
        $result = $this->where($condition)
                        ->field('GUID, title, author, cover, t_novel_cate.uname AS category, price, discount, description, meta_key, meta_desc, update_time')
                        ->join('LEFT JOIN t_novel_cate ON (t_novel_info.ucate_id = t_novel_cate.uid)')
                        ->order('update_time DESC')
                        ->page($page, $num)
                        ->select();
        return $result;
    }


    /** 
     * 获取小说章节
     * @param string $guid 小说统一标识符
     * @return array 小说章节信息
     */  
    public function get_book_chapters($guid){
        $condition = array(
                'GUID' => array('LIKE', $guid.'%'), 
                'is_active' => true, 
            );
        
        // 获取图书信息
        $result = $this->table('t_novel_chapter')->where($condition)->field('GUID, title, update_time')->select();
        return $result;
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
            $chapters_id[] = $value[0];
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
        $result = $this->table('t_notes')->where($condition)->select();
        return $result;
    }
}