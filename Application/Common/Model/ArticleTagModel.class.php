<?php
/**
 * @Author: Marte
 * @Date:   2018-02-08 16:12:44
 * @Last Modified by:   Marte
 * @Last Modified time: 2018-02-08 16:18:17
 */
namespace Common\Model;
use Common\Model\BaseModel;
/**
 * 文章标签关联表model
 */
class ArticleTagModel extends BaseModel{
    /**
     * 添加数据
     * @param string  $aid  文章ID
     * @param array $tids 标签id
     */
    public function addData($aid,$tids){
        foreach ($tids as $k => $v) {
            $tag_data=array(
                'aid'=>$aid,
                'tid'=>$v,
                );
            $this->add($tag_data);
        }
        return true;
    }
}