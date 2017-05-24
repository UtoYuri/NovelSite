<?php
namespace Book\Controller;
use Think\Controller;
class SearchController extends Controller {

    /** 
     * 默认控制器默认动作
     * @param int $page 页码
     * @param int $num 页面容量
     * 展示网站首页
     */  
    public function index($page = 1, $num = 20){
        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->get_book_list($page, $num);

        // 模板赋值并展示
        $this->assign('book_list', $book_list);
        $this->show();
    }

    
    /** 
     * 小说详情页面
     * @param string $guid 小说全局统一标识符
     * 展示小说详情页面
     */  
    public function info($guid = ''){
        $session_key    = I('session.session_key', '');

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = '请登录后查看';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $err_msg = '登录状态已过期，请重新登录';
        }

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说基本信息
        $book_info = $book_model->get_book_info($guid);
        // 获取小说章节信息
        $book_chapters = $book_model->get_book_chapters($guid);
        // 如果登录则获取用户对该书的阅读笔记
        if (!$err_msg){
            $notes = $book_model->get_notes($user_id, $guid);
            $log_status = true;
        }else{
            $notes = $err_msg;
            $log_status = false;
        }

        // 模板赋值并展示
        $this->assign('book_info', $book_info);
        $this->assign('book_chapters', $book_chapters);
        $this->assign('log_status', $log_status);
        $this->assign('notes', $notes);
        $this->show();
    }

    /** 
     * 图书分页检索API
     * @param int $page 页码
     * @param int $num 页面容量
     * @return 检索结果API
     */  
    public function page(){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->get_book_list($page, $num);

        // 返回检索结果
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
    }

    /** 
     * 小说检索API
     * @param int $page 页码
     * @param int $num 页面容量
     * @return 检索结果API
     */  
    public function search(){
        $key        = I('post.key', '');
        $field      = I('post.field', '');
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->search_book($key, $field, $page, $num);

        // 返回检索结果
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
    }
}