<?php

namespace Book\Model;
use Think\Model;
class ShelfModel extends Model {
    protected $tableName = 'purchase_token'; 

    /** 
     * 商品购买
     * @param int $user_id 用户ID
     * @param string $guid 图书GUID
     * @return bool/array 操作结果
     */  
    public function purchase($user_id, $guid){
        $condition = array(
                'GUID' => $guid,
                'is_active' => true,
            );

        // 获取商品价格信息
        $novel = $this->table('t_novel_info')->where($condition)->field('uid, price, discount')->select();

        // dump($novel);
        if (!count($novel)){
            return false;
        }

        // 判断是否已经购买
        // 没有购买则开始购买
        $condition = array(
                'novel_id' => $novel[0]['uid'],
                'user_id' => $user_id,
            );

        if ((int)$this->where($condition)->field('token')->count() == 0){
            $cost = $novel[0]['price'] - $novel[0]['discount'];
            $data = array(
                    'uorder' => create_order(),
                    'novel_id' => $novel[0]['uid'],
                    'user_id' => $user_id,
                    'price' => $cost,
                    'token' => uniqid(),
                );
            $this->add($data);

            // 修改余额
            $condition = array(
                    'uid' => $user_id,
                );
            $pocket = $this->table('t_user')->where($condition)->field('pocket')->select()[0]['pocket'] - $cost;
            $this->execute("UPDATE t_user SET pocket = ".$pocket." WHERE uid = '".$user_id."'");
        }
        $result = $this->table('t_purchase_token')->where($condition)->field('uorder, token')->select();

        return $result[0];
    }

    /** 
     * 购买订单
     * @param int $user_id 用户ID
     * @param int $page 页码
     * @param int $num 页面容量
     * @return array 购买记录
     */  
    public function get_order_list($user_id, $page = 1, $num = 20){
        $condition = array(
                'user_id' => $user_id,
            );

        // 获取购买信息
        $result = $this->where($condition)
                        ->join('LEFT JOIN t_novel_info ON (t_novel_info.uid = t_purchase_token.novel_id)')
                        ->field('t_purchase_token.uorder, t_novel_info.title, t_novel_info.cover, t_purchase_token.price, t_purchase_token.token, t_purchase_token.purchase_time')
                        ->order('t_purchase_token.purchase_time DESC')
                        ->page($page, $num)
                        ->select();
        return $result;
    }

    /** 
     * 找到对应图书
     * @param int $user_id 用户ID
     * @param string $token 令牌
     * @return bool/array 结果
     */  
    public function check_token($user_id, $token){
        $condition = array(
                'user_id' => $user_id,
                'token' => $token,
            );
        
        $result = $this->where($condition)->field('novel_id')->select();
        
        if (!count($result)){
            return false;
        }

        return $result[0]['novel_id'];
    }
}