<?php
/**
 * @Author: Marte
 * @Date:   2018-01-31 08:34:22
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-10 10:44:39
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
     * @param strind $cid 分类id
     */
}