<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 后台首页
 */
class IndexController extends AdminBaseController{
	public function index(){
		$this->display();
	}
	public function welcome(){
		$data=array(
                               'all_article'=>M('Article')->count(),
                               'delete_article'=>M('Article')->where(array('is_delete'=>1))->count(),
                               'hide_article'=>M('Article')->where(array('is_show'=>0))->count(),
                               'all_chat'=>M('Chat')->count(),
                               'delete_chat'=>M('Chat')->where(array('is_delete'=>1))->count(),
                               'hide_chat'=>M('Chat')->where(array('is_show'=>0))->count(),
                               'all_comment'=>M('Comment')->count(),
			);
		// dump($data);die;
		$this->assign('data',$data);
		$this->display();
	}
}