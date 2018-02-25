<?php
namespace Common\Controller;
/**
 * @Author: Marte
 * @Date:   2018-02-14 09:09:28
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-25 15:51:05
 */
/**
 * 前台基类controller
 */
class HomeBaseController extends BaseController{
    /**
     * 初始化方法
     */
    public function _initialize(){
        parent::_initialize();

        // 组合置顶推荐where
        $recommend_map=array(
            'is_show'=>1,
            'is_delete'=>0,
            'is_top'=>1
            );
        // 获取置顶推荐文章
        $recommend=M('Article')
            ->field('aid,title')
            ->where($recommend_map)
            ->order('aid desc')
            ->select();

        // 判断是否显示友情链接(仅仅在首页显示友情链接)
        $show_link=MODULE_NAME.'/'.CONTROLLER_NAME.'/'.ACTION_NAME=='Home/Index/index' ? 1 : 0;

        // 分配常用数据
        $assign=array(
            'categorys'=>D('Category')->getAllData(),
            'tags'=>D('Tag')->getAllData(),
            'recommend'=>$recommend,
            'show_link'=>$show_link,
            'links'=>D('Link')->getDataByState(0,1),
            );
        // echo "<pre>";
        // print_r($assign);die;
        $this->assign($assign);

    }
}