<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/*
 *文章管理
 */
class ArticleController extends AdminBaseController{
    //定义数据表
    private $db;
    private $viewDb;

    //构造函数 实例化ArticleModel表
    public function __construct(){
        parent::__construct();
        $this->db=D('Article');
    }

    //文章列表
    public function index(){
        $data=$this->db->getPageData('all','all','all',0,15);
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display();
    }
}