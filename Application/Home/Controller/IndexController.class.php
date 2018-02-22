<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class IndexController extends HomeBaseController {
   /**
    * 网站首页
    */
   //首页
   public function index(){
    $articles=D('Article')->getPageData();
    $assign=array(
         'articles'=>$articles['data'],
         'page'=>$articles['page'],
         'cid'=>'index',
        );
    $this->assign($assign);
    $this->display();
    // var_dump($assign);
    // echo "<pre>";
    // print_r($assign);
   }

}