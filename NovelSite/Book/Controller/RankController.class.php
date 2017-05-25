<?php
namespace Book\Controller;
use Think\Controller;
class RankController extends Controller {

    /** 
     * 排行控制器默认动作
     * @param string $category 分类
     * @param int $page 页码
     * @param int $num 页面容量
     * 展示图书排行页面
     */  
    public function index($category = '', $page = 1, $num = 20){
        // 创建小说模型
        $book_model = D('Book');

        // 获取小说列表
        $book_list = $book_model->get_rank_list($category, $page, $num);

        // 模板赋值并展示
        $this->assign('book_list', $book_list);
        $this->show();
    }
}