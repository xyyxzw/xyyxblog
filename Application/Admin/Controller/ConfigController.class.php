<?php
/**
 * @Author: Marte
 * @Date:   2018-02-01 15:16:55
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-07 12:45:58
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 网站设置
 */
class ConfigController extends AdminBaseController{
    //定义数据表
    private $db;
    //构造函数 实例化configmodel
    public function __construct(){
        parent::__construct();
        $this->db=D('Config');
    }
    //网站配置首页
    public function  index(){
        if(IS_POST){
            if($this->db->editData()){
                $this->success('修改成功',U('Admin/Config/index'));
            }else{
                $this->error('修改失败');
            }
        }else{
            $data=$this->db->getAllData();
            $this->assign('data',$data);
            $this->display();
        }
    }

    //修改密码
    public function change_password(){
        if(IS_POST){
            if($this->db->changePassword()){
                //$this->success('修改成功');
                // redirect(U('Admin/Login/login'),3,'修改成功');
                session('admin',null);
                echo "<script >alert('修改成功!');parent.location.href='".U('Admin/Login/login')."'</script>";
        }else{
            $this->error($this->db->getError());
        }
    }else{
        $this->display();
    }
 }
}
