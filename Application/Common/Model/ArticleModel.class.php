<?php
/**
 * @Author: Marte
 * @Date:   2018-01-31 08:34:22
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-13 15:20:13
 */
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 文章model
 */
class ArticleModel extends BaseModel{

    //自动验证
    protected $_validate=array(
        array('tid','require','必须选择栏目'),
        array('title','require','文章标题必填'),
        array('author','require','作者必填'),
        array('content','require','文章内容必填'),
        );
    //自动完成
    protected $_auto=array(
        array('click',0),
        array('is_delete',0),
        array('addtime','time',1,'function'),//用PHP函数time插入
        array('description','getDescription',3,'callback'),
        array('keywords','comma2coa',3,'callback'),
        );
    //获得描述
    protected function getDescription($description){
        if(empty($description)){
            return $description;
        }else{
            $data=I('post.description');
            $des=htmlspecialchars_decode($data);
            $des=re_substr(strip_tags($des),0,100,true);
            return $des;
        }
    }
    //顿号转换为英文逗号
    protected function comma2coa($keywords){
        return str_replace('、',',',$keywords);
    }
    //添加数据
    public function addData(){
        //获取post数据
        $data=I('post.');
        $data['content']=htmlspecialchars_decode($data['content']);
        //判断是否修改文章中图片的默认的alt 和title
        $image_title_alt_word=C('IMAGE_TITLE_ALT_WORD');
        if(empty($image_title_alt_word)){
            //修改图片默认的title和alt
            //解释：(?<=(")) 表示 匹配以(")开头的字符串，并且捕获(存储)到分组中(?=(")) 表示 匹配以(")结尾的字符串，并且捕获(存储)到分组中
            $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="徐逸以轩博客"',$data['content']);
            $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="徐逸以轩博客"',$data['content']);
        }else{
            $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="'.$image_title_alt_word.'"',$data['content']);
            $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="'.$image_title_alt_word.'"',$data['content']);
        }
        //将绝对路径转换为相当路径
        $data['content']=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="/Upload/image/ueditor',$data['content']);
        //转义
        $data['content']=htmlspecialchars($data['content']);
        if($this->create($data)){
            //获取文章内容图片
            // $image_path=get_ueditor_image_path($data['content']);
            $image_path=get_ueditor_image_path($data['content']);
            if($aid=$this->add()){
                if(isset($data['tids'])){
                    D('ArticleTag')->addData($aid,$data['tids']);
                }
                if(!empty($image_path)){
                    //添加水印
                    if(C('WATER_TYPE')!=0){
                        foreach ($image_path as $k=>$v){
                            add_water('.'.$v);
                        }
                    }
                    //传递图片插入数据库
                    D('ArticlePic')->addData($aid,$image_path);
                }
                return $aid;
            }else{
                return false;
            }
        }else{
            return false;
        }

    }
    /**
     * 获得文章分页数据
     * @param  string  $cid       分类id all为全部分类
     * @param  string  $tid       标签id all为全部标签
     * @param  string  $is_show   是否显示 1为显示 0为不显示
     * @param  string  $is_delete 状态 1为删除 0为正常
     * @param  integer $limit     分页条数
     * @return array $data  分页样式 和 分页数据
     */
  public function getPageData($cid='all',$tid='all',$is_show='1',$is_delete='0',$limit=10){
    if($cid=='all' && $tid=='all'){
        //获取全部分类 全部标签下的文章
        if($is_show=='all'){
            $where=array('is_delete'=>$is_delete);
        }else{
            $where=array(
                'is_delete'=>$is_delete,
                'is_show'=>$is_show
                );
        }
        $count=$this->where($where)->count();
        $page=new \Org\Xyyx\Page($count,$limit);
        $page->setConfig('prev','<<');
        $page->setConfig('next','>>');
        $list=$this->where($where)->order('addtime desc')->limit($page->firstRow.','.$page->listRows)->select();
        $extend=array(
            'type'=>'index',
            'id'=>0
            );
    }
    $show=$page->show();
    foreach($list as $k=>$v){
        $list[$k]['addtime']=word_time($v['addtime']);
        $list[$k]['tag']=D('ArticleTag')->getDataByAid($v['aid'],'all');
        $list[$k]['pic_path']=D('ArticlePic')->getDataByAid($v['aid']);
        //current() 函数返回数组中的当前元素的值
        $list[$k]['category']=current(D('Category')->getDataByCid($v['cid'],'cid,cid,cname'));
        // $list[$k]['category']=D('Category')->getDataByCid($v['cid'],'cid,cname');
        //如果传入了2个以上的字段名，则返回一个二维数组（类似select方法的返回值，区别在于索引是二维数组的键名是第一个字段的值）
        $v['content']=preg_ueditor_image_path($v['content']);
        $list[$k]['content']=htmlspecialchars($v['content']);
        $list[$k]['url']=U('Home/Index/article/',array('aid'=>$v['aid']));
        $list[$k]['extend']=$extend;
    }
    $data=array(
        'page'=>$show,
        'data'=>$list,
        );
    return $data;
  }

  // 传递aid获取单条全部数据
  public function getDataByAid($aid){
    $data=$this->where(array('aid'=>$aid))->find();
    $data['tids']=D('ArticleTag')->getDataByAid($aid);
    $data['tag']=D('ArticleTag')->getDataByAid($aid,'all');
    $data['category']=current(D('Category')->getDataByCid($data['cid'],'cid,cid,cname,keywords'));
    // 获取相对路径的图片地址
    $data['content']=preg_ueditor_image_path($data['content']);
    return $data;
  }

  // 修改数据
  public function editData(){
      // 获取post数据
      $data=I('post.');
      // 反转义为下文的 preg_replace使用
      $data['content']=htmlspecialchars_decode($data['content']);
      // 判断是否修改文章中图片的默认的alt 和title
      $image_title_alt_word=C('IMAGE_TITLE_ALT_WORD');
      if(empty($image_title_alt_word)){
          //修改图片默认的title和alt
          //解释：(?<=(")) 表示 匹配以(")开头的字符串，并且捕获(存储)到分组中(?=(")) 表示 匹配以(")结尾的字符串，并且捕获(存储)到分组中
          $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="徐逸以轩博客"',$data['content']);
          $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="徐逸以轩博客"',$data['content']);
      }else{
          $data['content']=preg_replace('/title=\"(?<=").*?(?=")\"/','title="'.$image_title_alt_word.'"',$data['content']);
          $data['content']=preg_replace('/alt=\"(?<=").*?(?=")\"/','alt="'.$image_title_alt_word.'"',$data['content']);
      }
      // 将绝对路径转换为相对路径
      $data['content']=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="/Upload/image/ueditor',$data['content']);
      $data['content']=htmlspecialchars($data['content']);
      if($this->create($data)){
          $aid=$data['aid'];
          $this->where(array('aid'=>$aid))->save();
          $image_path=get_ueditor_image_path($data['content']);
          D('ArticleTag')->deleteData($aid);
          if(isset($data['tids'])){
              D('ArticleTag')->addData($aid,$data['tids']);
          }
          // 删除图片路径
          D('ArticlePic')->deleteData($aid,$data['content']);
          if(!empty($image_path)){
              if(C('WATER_TYPE')!=0){
                  foreach ($image_path as $k => $v) {
                      add_water('.'.$v);
                  }
              }
              // 添加新图片路径
              D('ArticlePic')->addData($aid,$image_path);
          }
          return true;
      }else{
          return false;
      }
  }

  // 彻底删除
  public function deleteData(){
      $aid=I('get.aid',0,'intval');
      D('ArticlePic')->deleteDataAll($aid);
      D('ArticleTag')->deleteData($aid);
      $this->where(array('aid'=>$aid))->delete();
      return true;
  }
}