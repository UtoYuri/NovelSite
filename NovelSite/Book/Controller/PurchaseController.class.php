<?php
namespace Book\Controller;
use Think\Controller;
class PurchaseController extends Controller {

    /** 
     * 购买控制器默认动作
     * 跳转至图书首页
     */  
    public function index(){
        $this->redirect('/Book/Index');
    }

    /** 
     * 购买API
     * @param string $guid 图书guid
     * @return json 书架结果
     */  
    public function buy(){
        $guid           = I('post.guid', '');
        $session_key    = I('session.session_key', '');

        if (!strlen($guid)){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '书籍编号不可以为空', 
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

        // 创建小说模型
        $shelf_model = D('Shelf');

        // 获取订单详情
        $order = $shelf_model->purchase($user_id, $guid);

        if (!$order){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => '购买失败', 
                    'data' => array(
                            'redirect' => U('/Book/Index'), 
                        ), 
                ), 'json');
        }

        // 返回订单详情
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '购买成功', 
                'data' => $order,
            ), 'json');
    }

    /** 
     * 订单详情API
     * @param int $page 页码
     * @param int $num 页面容量
     * @return json 订单详情
     */  
    public function order(){
        $page           = I('post.page/d', 1);
        $num            = I('post.num/d', 20);
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

        // 创建小说模型
        $shelf_model = D('Shelf');

        // 获取订单详情
        $order = $shelf_model->get_order_list($user_id, $page, $num);

        // 返回订单详情
        $this->ajaxReturn(array(
                'success' => true, 
                'msg' => '获取成功', 
                'data' => $order,
            ), 'json');
    }
}