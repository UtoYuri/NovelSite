<?php
namespace Book\Controller;
use Think\Controller;
class SearchController extends Controller {

    /** 
     * 搜索控制器默认动作
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
        $user_id        = I('session.user_id/d', 0);        
        $session_key    = I('session.session_key', '');        

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 验证异地登录
        if (C('CHECK_SESSION_KEY', false)){
            // 获取账户ID
            if ($user_id != $user_model->get_user_id_by_session($session_key)){
                $err_msg = $err_msg ? $err_msg : '您已在其他终端登录，请重新登录';
            }
        }


        if ($err_msg){
            $this->ajaxReturn(array(
                'success' => false, 
                'msg' => $err_msg, 
                'data' => array(), 
            ), 'json');
        }

        // 创建小说模型
        $book_model = D('Book');

        if (strlen($guid) == 0){
            $this->ajaxReturn(array(
                'success' => false, 
                'msg' => '错误的GUID', 
                'data' => array(), 
            ), 'json');
        }


        // 获取小说基本信息
        $book_info = $book_model->get_book_info($guid);
        // 获取小说章节信息
        $book_chapters = $book_model->get_book_chapters($guid);
        // 如果登录则获取用户对该书的阅读笔记
        if (!$err_msg){
            // 创建阅读笔记模型
            // $note_model = D('Note');
            // $notes = $note_model->get_notes($user_id, $guid);get_order
            // 创建阅读模型
            $read_model = D('Read');
            $user_level = $read_model->get_user_level($user_id);
            $readed_words = $read_model->get_readed_words($user_id);
            // 创建书架模型
            $shelf_model = D('Shelf');
            $order = $shelf_model->get_order($user_id, $guid);
            
            $log_status = true;
        }else{
            // $notes = array();
            $log_status = false;
        }

        // 模板赋值并展示
        $this->assign('book_info', $book_info[0]);
        $this->assign('book_chapters', $book_chapters);
        // $this->assign('notes', $notes);
        $this->assign('order', !$order?null:$order);
        $this->assign('user_level', $user_level);
        $this->assign('readed_words', $readed_words);
        $this->show();
    }

    /** 
     * 图书分页检索API
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return 检索结果API
     */  
    public function page($format = 'html'){
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->get_book_list($page, $num);

        // dump($book_list);

        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else{
            $this->assign('book_list', $book_list);
            $this->display('novel-item');
        }
    }

    /** 
     * 小说检索API
     * @param int $page 页码
     * @param int $num 页面容量
     * @param string $format 返回数据格式
     * @return 检索结果API
     */  
    public function search($format = 'html'){
        $key        = I('post.key', '');
        $field      = I('post.field', '');
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->search_book($key, $field, $page, $num);

        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else{
            $this->assign('book_list', $book_list);
            $this->display('novel-item');
        }
    }


    /** 
     * 按分类搜索API
     * @param string $format 返回数据格式
     * @param string $category 分类
     * @param int $page 页码
     * @param int $num 页面容量
     * @return 搜索结果API
     */  
    public function rank($format = 'html'){
        $category   = I('post.category', '');
        $page       = I('post.page/d', 1);
        $num        = I('post.num/d', 20);

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->get_rank_list($category, $page, $num);

        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $book_list, 
            ), 'json');
        }else{
            $this->assign('book_list', $book_list);
            $this->display('novel-item');
        }
    }

    
    /** 
     * 获取分类信息API
     * @param string $format 返回数据格式
     * @return 分类信息
     */  
    public function cate($format = 'html'){

        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $cate_info_list = $book_model->get_cate_info_list($category, $page, $num);

        // 返回检索结果
        if ($format == 'json'){
            $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $cate_info_list, 
            ), 'json');
        }else if($format == 'xml'){
            $this->xmlReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $cate_info_list, 
            ), 'json');
        }else{
            $this->assign('cate_info_list', $cate_info_list);
            $this->display('cate-info-item');
        }
    }
}