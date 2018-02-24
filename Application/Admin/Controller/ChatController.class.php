<?php
/**
 * @Author: Marte
 * @Date:   2018-02-24 14:26:52
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-24 15:48:53
 */
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
/**
 * 闲来一笔管理
 */
class ChatController extends AdminBaseController{
    //定义数据
    private $db;
    public function __construct(){
        parent::__construct();
        $this->db=D('Chat');
    }

    //添加
    public function add(){
        if(IS_POST){
            if($this->db->addData()){
                $this->success('闲来一笔发表成功',U('Admin/Chat/add'));
            }else{
                $this->error($this->db->getError());
            }
        }else{
            $this->display();
        }
    }

    //列表
    public function index(){
        $data=$this->db->getDataByState(0,'all');
        // echo $this->db->getLastSql();
        $this->assign('data',$data);
        $this->display();
    }


    // 修改
    public function edit(){
        if(IS_POST){
            if($this->db->editData()){
                $this->success('修改成功');
            }else{
                $this->error($this->db->getError());
            }
        }else{
            $chid=I('get.chid');
            $data=$this->db->getDataByLid($chid);
            $this->assign('data',$data);
            $this->display();
        }
    }
}