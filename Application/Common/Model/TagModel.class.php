<?php
/**
 * @Author: Marte
 * @Date:   2018-01-31 08:40:04
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-01-31 10:34:58
 */
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 标签Model
 */
class TagModel extends BaseModel{
    //添加标签
    public function addData(){
        $str=I('post.tnames');
        if(empty($str)){
            $this->error='标签名不能为空';
            return false;
        }else{
            $str=nl2br(trim($str));//nl2br() 函数在字符串中的每个新行（\n）之前插入 HTML 换行符（<br> 或 <br />）。
            $tnames=explode("<br />",$str);
            foreach($tnames as $k=>$v){
                $v=trim($v);
                if(!empty($v)){
                    $data['tname']=$v;
                    $this->add($data);
                }
            }
            return true;
        }
    }
    //获得全部数据
    public function getAllData(){
        $data=$this->select();
        foreach($data as $k=>$v){
            // $data[$k]['count']=M('Article_tag')->where(array('tid'=>$v['tid']))->count();
        }
        return $data;
    }

    //修改数据
    public function editData(){
        $tid=I('post.tid');
        $data['tname']=I('post.tname');
        if(empty($data['tname'])){
            $this->error='标签名不能为空';
            return false;
        }else{
            return $this->where(array('tid'=>$tid))->save($data);
        }
    }
    //根据tid获取单挑数据
    public function getDataByTid($tid,$field='all'){
        if($field=='all'){
            return $this->where(array('tid'=>$tid))->find();
        }else{
            return $this->getFieldByTid($tid,'tname');
        }
    }
    //删除数据
    public function deleteData(){
        $tid=I('get.tid',0,'intval');
        if($this->where(array('tid'=>$tid))->delete()){
            return true;
        }else{
            return false;
        }
    }
}