<?php
namespace Common\Controller;
/**
 * 后台基类Controller
 */
class AdminBaseController extends BaseController{
     /**
     * 初始化方法
     */
    //构造方法
    // public function __construct(){
    //  //构造父类
    //  parent::__construct();
    //  echo '你好，世界';
    // }
     //ThinkPHP提供
	public function _initialize(){
		 parent::_initialize();
         if(session('admin')!='is_login'){
            //判断第三方登录账号中是否有admin
            $oauth_has_admin=M('Oauth_user')->where(array('is_admin'=>1))->count();
            //如果有第三方账号admin则要求用第三方账号登录否则用常规登录
            if($oauth_has_admin){
                die('请在前台页面通过第三方账号登录');
            }else{
                redirect(U('Admin/Login/login'));
            }
         }

}

}