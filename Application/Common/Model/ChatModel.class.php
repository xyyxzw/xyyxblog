<?php
/**
 * @Author: Marte
 * @Date:   2018-02-24 14:32:50
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-24 15:52:04
 */
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 闲来一笔model
 */
class ChatModel extends BaseModel{
    //定义自动验证规则
    protected $_validate=array(
         array('content','require','内容必填'),
        );
    //添加数据
    public function addData(){
        $data=I('post.');
        if($this->create($data)){
            $data['date']=time();
            $data['is_delete']=0;
            $chid=$this->add($data);
            return $chid;
        }else{
            return false;
        }
    }

    // 传递is_delete和is_show获取对应数据
    public function getDataByState($is_delete='all',$is_show='all'){
        $is_delete=$is_delete==='all' ? '' : "is_delete=$is_delete";
        $is_show=$is_show==='all' ? '' : "is_show=$is_show";
        $where=trim(trim($is_delete.' and '.$is_show,' '),'and');
        // $where=$is_delete.' and '.$is_show;
        //  echo $where;die;
        return $this->where($where)->order('date desc')->select();
        //
        //
        // $map['is_delete']=$is_delete;
        // $map['is_show']=$is_show;
        // return $this->where($map)->order('date desc')->select();
    }


    // 修改数据
    public function editData(){
        $data=I('post.');
        if($this->create($data)){
            $this->where(array('chid'=>$data['chid']))->save($data);
            return true;
        }else{
            return false;
        }
    }


    // 传递chid获取单条数据
    public function getDataByLid($chid){
        return $this->where(array('chid'=>$chid))->find();
    }



}