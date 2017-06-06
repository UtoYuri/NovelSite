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
    public function purchase(){
        $guid           = I('post.guid', '');
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

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/User/Reg/reg'), 
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

        // 用户验证出错
        if ($err_msg){
            $this->ajaxReturn(array(
                    'success' => false, 
                    'msg' => $err_msg, 
                    'data' => array(
                            'redirect' => U('/User/Reg/reg'), 
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