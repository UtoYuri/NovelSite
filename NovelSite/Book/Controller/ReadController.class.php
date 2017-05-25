<?php
namespace Book\Controller;
use Think\Controller;
class ReadController extends Controller {

    /** 
     * 阅读控制器默认动作
     * 跳转至图书首页
     */  
    public function index(){
        $this->redirect('/Book/Index');
    }

     /** 
     * 在线阅读
     * @param string $token 阅读令牌
     * @param int $guid 章节GUID
     * 展示阅读界面
     */  
    public function view($token = '', $guid = ''){
        $session_key    = I('session.session_key', '');

        // 验证post数据完整性
        if (strlen($token) == 0
            || strlen($guid) == 0){
            $err_msg = $err_msg ? $err_msg : '阅读令牌/章节GUID为空';
        }

        // 验证登录状态
        if (strlen($session_key) == 0){
            $err_msg = $err_msg ? $err_msg : '请先登录';
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $err_msg = $err_msg ? $err_msg : '登陆状态已失效';
        }

        // 创建书架模型
        $shelf_model = D('Shelf');

        // 检验令牌有效
        // 根据有效令牌获取小说
        $novel_id = $shelf_model->check_token($user_id, $token);

        if (!$novel_id){
            $err_msg = $err_msg ? $err_msg : '错误的Token';
        }

        // 创建图书模型
        $book_model = D('Read');
        $chapter_info = $book_model->get_chapter_info($guid);

        // 添加阅读记录
        if (!count($chapter_info)){
            $err_msg = $err_msg ? $err_msg : '找不到对应章节';
        }else{
            $book_model->post_read_record($user_id, $guid);
        }

        // 绑定数据并显示
        $this->assign('err_msg', $err_msg);
        $this->assign('chapter_info', $chapter_info);
        $this->show();
    }

    /** 
     * 获取用户阅读等级API
     * @return json 等级详情
     */  
    public function level(){
        $session_key    = I('session.session_key', '');

        // 验证登录状态
        if (strlen($session_key) == 0){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '请先登录', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '登陆状态已失效', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建图书模型
        $book_model = D('Read');
        $words = $book_model->get_readed_words($user_id);
        $level_name = $book_model->get_user_level($user_id);

        // 返回等级信息
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => array(
                        'words' => $words,
                        'level_name' => $level_name,
                    ),
            ), 'json');
    }

    /** 
     * 保存阅读笔记API
     * @param string $guid 图书GUID
     * @param string $notes 阅读笔记
     * @return json 订单详情
     */  
    public function note(){
        $guid           = I('post.guid', '');
        $note           = I('post.note', '');
        $session_key    = I('session.session_key', '');

        // 验证post数据完整性
        if (strlen($guid) == 0
            || strlen($note) == 0){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '图书GUID/阅读笔记不可以为空', 
                    'data' => array(
                            'redirect' => U('/Book/Index/index'), 
                        ), 
                ), 'json');
        }

        // 验证登录状态
        if (strlen($session_key) == 0){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '请先登录', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建用户模型
        $user_model = D('User/User');

        // 获取账户ID
        $user_id = $user_model->get_user_id_by_session($session_key);

        // 检测异地登录导致的session失效
        // 登录状态失效则返回错误提示
        if (!$user_id){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '登陆状态已失效', 
                    'data' => array(
                            'redirect' => U('/User/Login/index'), 
                        ), 
                ), 'json');
        }

        // 创建阅读笔记模型
        $note_model = D('Note');

        // 保存阅读笔记
        $result = $note_model->post_note($user_id, $guid, $note);

        if (!$result){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '保存失败', 
                    'data' => array(
                            'redirect' => U('/Book/Index/index'), 
                        ), 
                ), 'json');
        }

        // 返回保存结果
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '保存成功', 
                'data' => array(),
            ), 'json');
    }
}