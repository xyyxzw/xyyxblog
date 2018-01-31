<?php
namespace Admin\Controller;
use Common\Controller\PublicBaseController;
/*后台登录*/
class LoginController extends PublicBaseController {
    public function login(){
        //判断第三方登录账号中是否有admin
        $oauth_has_admin=M('Oauth_user')->where(array('is_admin'=>1))->count();
        //如果有第三方账号有admin就用第三方账号登录否则用常规登录
        if($oauth_has_admin){
            die('请在前台页面通过第三方账号登录');
        }
        if(IS_POST){
            $data=I('post.');
            if(check_verify($data['verify'])){
                //动态查询
                //针对某个字段查询并返回某个字段的值，例如 $userId = $User->getFieldByName('liu21st','id');表示根据用户的name获取用户的id值。
                /* $password=M('config')->getByName('ADMIN_PASSWORD');
                 var_dump($password);
                 die;*/
                 // D:\bjyblog\Application\Admin\Controller\LoginController.class.php:22:
                 // array (size=3)
                 //   'id' => string '5' (length=1)
                 //   'name' => string 'ADMIN_PASSWORD' (length=14)
                 //   'value' => string '21232f297a57a5a743894a0e4a801fc3' (length=32)
                $password=M('config')->getFieldByName('ADMIN_PASSWORD','value');
                if(md5($data['ADMIN_PASSWORD'])==$password){
                    session('admin','is_login');
                    session('ADMIN_PASSWORD',null);
                    //dump(session('admin'));die;
                    $this->success('登录成功',U('Admin/Index/index'));
                }else{
                    $this->error('密码输入错误',U('Admin/Login/login'));
                }
            }else{
                session('ADMIN_PASSWORD',$data['ADMIN_PASSWORD']);
                $this->error('验证码输入错误',U('Admin/Login/login'));
            }
        }else{
            $this->display();
        }
    }
            //退出登录
            public function logout(){
              session('admin',null);
              $this->success('退出成功',U('Admin/Login/login'));
            }
         // 生成验证码
            public function showVerify(){
                  show_verify();
            }

}