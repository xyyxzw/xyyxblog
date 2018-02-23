<?php
namespace Common\Controller;
/**
 * @Author: Marte
 * @Date:   2018-02-14 09:09:28
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-22 13:16:42
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
            );
        $this->assign($assign);

    }
}