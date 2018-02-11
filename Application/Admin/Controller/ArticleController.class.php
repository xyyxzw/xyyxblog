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

    //添加文章
    public function add(){
        if(IS_POST){
            if($aid=$this->db->addData()){
                // echo $aid;die;
                $this->success('文章添加成功',U('Admin/Article/index'));
            }else{
                // echo '错误';die;
                $this->error($this->db->getError());
            }
        }else{
            $allCategory=D('Category')->getAllData();
            if(empty($allCategory)){
                $this->error('请先添加分类');
            }
            //获取所有标签
            $allTag=D('Tag')->getAllData();
            $this->assign('allCategory',$allCategory);
            $this->assign('allTag',$allTag);
            $this->display();
        }
    }

    //文章列表
    public function index(){
        $data=$this->db->getPageData('all','all','all',0,5);
        // echo "<pre>";
        // print_r($data);die;
        $this->assign('data',$data['data']);
        $this->assign('page',$data['page']);
        $this->display();
    }
}