<?php
namespace Common\Model;
use Common\Model\BaseModel;
class CategoryModel extends BaseModel{
	//自动验证
	protected $_validate=array(
		array('cname','require','分类名不能为空'),
	            array('sort','number','排序必须为整数'),
	            );
	//添加数据
	public function addData(){
	    $data=I('post.');
	    if($this->create($data)){
	        return $this->add();
	    }
	}
	/*引用自定义的类 将类文件放到ThinkPHP/Library/Org/目录下并创建一个文件夹Xyyx，Xyyx文件夹内加入自定义的类文件，开头必须命名空间namespa Org\Xyyx,使用的时候直接实例化类 new \Org\Xyyx\类名*/
	public function getAllData($field='all',$tree=true){
		if($field=='all'){
			$data=$this->order('sort')->select();
			if($tree){
				return \Org\Xyyx\Data::tree($data,'cname');
			}else{
				return $data;
			}
		}else{
			return $this->getField("cid,$field");
		}
	}
	 //传递cid和field获取对应的数据
	public function getDataByCid($cid,$field='all'){
		if($field=='all'){
			return $this->where(array('cid'=>$cid))->find();
		}else{
			return $this->where(array('cid'=>$cid))->getField($field);
		}
	}
	// 传递cid获得所有子栏目
	public function getChildData($cid){
		$data=$this->getAllData('all',false);//获得非tree处理的数据
		$child=\Org\Xyyx\Data::channelList($data,$cid);//用地址栏传递的分类id当做父ID求该分类下的所有子分类
		foreach($child as $k=>$v){
			$childs[]=$v['cid'];//然后将该分类下的所有子分类ID赋给数组childs并返回
		}
		return $childs;
	}
	//修改数据
	public function editData(){
		$data=I('post.');
		if($this->create($data)){
			return $this->where(array('cid'=>$data['cid']))->save($data);
		}
	}

	//删除数据
	public function deleteData($cid=null){
		$cid=is_null($cid)?I('get.cid'):$cid;
		$child=$this->getChildData($cid);
		if(!empty($child)){
			$this->error='请先删除子分类';
			return false;
		}
		 $articleData=D('Article')->getDataByCid($cid);
        if(!empty($articleData)){
            $this->error='请先删除此分类下的文章';
            return false;
        }
		if($this->where(array('cid'=>$cid))->delete()){
	                         return true;
		}else{
			return false;
		}

	}

}