<?php
namespace Common\Controller;
/**
 * @Author: Marte
 * @Date:   2018-02-14 09:09:28
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-24 16:39:44
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
        // 分配常用数据
        $assign=array(
            'categorys'=>D('Category')->getAllData(),
            'tags'=>D('Tag')->getAllData(),
            );
        // echo "<pre>";
        // print_r($assign);die;
        $this->assign($assign);

    }
}