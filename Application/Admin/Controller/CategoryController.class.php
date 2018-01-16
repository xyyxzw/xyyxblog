<?php
namespace Admin\Controller;
use Common\Controller\AdminBaseController;
class CategoryController extends AdminBaseController{
	//定义数据表 这样不需要每次执行一个方法都要重新实例化模型*/
	private $db;
	//定义category表数
	private $categoryData;
	// 构造函数 实例化CategoryModel 并获得整张表的数据
	// public function __construct(){
	public function _initialize(){
		// parent::__construct();
		parent::_initialize();
		//初始化时实例化category model
		$this->db=D('Category');
		//获取category数据并赋值给$categoryData,目的是在添加分类时表单的下拉框的所属分类取得数据*/
	             $this->categoryData=$this->db->getAllData();
	              // dump($this->categoryData);die;
	}
	public function index(){
		$this->assign('data',$this->categoryData);
		$this->display();
	}
	 public function add(){
	 	if(IS_POST){
	 		if($this->db->addData()){
	 			$this->success('分类添加成功');
	 		}else{
	 			$this->error($this->db->getError());
	 		}
	 	}else{
	 	/*I函数 不存在id则用0并且用强制转化整型过滤*/
	 	/*这是在分类列表下点击分类中添加子分类时候(地址栏）传递的该分类ID，并传送给add.html，如果该ID与select中option的id一致则被选中*/
	             $cid=I('get.cid',0,'intval');
	             if($cid){
	             	$this->assign('cid',$cid);
	             }
	 	$this->assign('data',$this->categoryData);
	 	$this->display();
	          }
	 }
	 //修改分类
	 public function edit(){
	 	if(IS_POST){
	 		if($this->db->editData()){
	 			$this->success('修改成功');
	 		}else{
	 			$this->error($this->db->getError());
	 		}
	 	}else{
	 		$cid=I('get.cid',0,'intval');
	 		$onedata=$this->db->getDataByCid($cid);
	 		//获得该分类ID的分类数据
	 		//dump($onedata);die;
	 		$data=$this->categoryData;
	 		$childs=$this->db->getChildData($cid);
	 		//获得顶级分类或者子分类下的子分类ID（数组）
	 		//array (size=6)
	 		    // 0 => string '30' (length=2)
	 		    // 1 => string '31' (length=2)
	 		    // 2 => string '32' (length=2)
	 		    // 3 => string '34' (length=2)
	 		    // 4 => string '36' (length=2)
	 		    // 5 => string '37' (length=2)
	 		// dump($childs);die;
	 		//如果修改分类 该分类下的子分类不能再被选择为分类进行选择
	 		foreach($data as $k=>$v){
	 			if(in_array($v['cid'],$childs)){
	 				$data[$k]['_html']=" disabled='disabled' style='background:#F0F0F0'";
	 			}else{
	 				$data[$k]['_html']="";
	 			}
	 		}
	 		$this->assign('data',$data);
	 		$this->assign('onedata',$onedata);
	 		$this->display();
	 	}
	 }

	 //删除分类
	 public function delete(){
	 	if($this->db->deleteData()){
	 		$this->success('删除成功');
	 	}else{
	 		$this->error($this->db->getError());
	 	}
	 }
	 //排序
	 public function sort(){
	 	$data=I('post.');
	 	if(!empty($data)){
	 		foreach($data as $k=>$v){
	 			if(!is_numeric($v)){
	 				$this->error('排序必须为整数',U('Admin/Category/index'));
	 			}
	 			$result=$this->db->where(array('cid'=>$k))->save(array('sort'=>$v));
	 			if($result<0){
	 				$this->error('排序修改失败',U('Admin/Category/index'));
	 			}
	 		}
	 	   }
	 	$this->success('排序修改成功',U('Admin/Category/index'));
	 }

}