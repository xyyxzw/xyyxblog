<?php
namespace Org\Xyyx;
final class Data
{
// /*简单点的无限极分类*/
// static public function tree($data,$pid=0,$level=0){
//     static $ret=array();
//     foreach ($data as $k => $v) {
//                  if($v['pid']==$pid){
//                     $v['level']=$level;
//                     $ret[]=$v;
//                     self::tree($data,$v['cid'],$level+1);
//                  }
//     }
//     return $ret;
//  }
// }


/**
     * 获得所有子栏目
     * @param $data 栏目数据
     * @param int $pid 操作的栏目
     * @param string $html 栏目名前字符
     * @param string $fieldPri 表主键
     * @param string $fieldPid 父id
     * @param int $level 等级
     * @return array
     */
 static public function channelList($data,$pid=0,$html="&nbsp;",$fieldPri='cid',$fieldPid='pid',$level=1){
    $data=self::_channelList($data,$pid,$html,$fieldPri,$fieldPid,$level);
    if(empty($data))
        return $data;
    foreach($data as $n=>$m){
        if($m['_level']==1)
            continue;//如果是顶级分类就不需要执行后面的_first _end
        $data[$n]['_first']=false;
        $data[$n]['_end']=false;
        //每组平级子分类的第一个添加_first=true
        if(!isset($data[$n-1]) || $data[$n-1]['_level']!=$m['_level']){
            $data[$n]['_first']=true;
        }
        //每组平级子分类的最后一个添加_end=true
        if(isset($data[$n+1]) && $data[$n]['_level']>$data[$n+1]['_level']){
            $data[$n]['_end']=true;
        }
        //如果夹在中间的都是false
    }
    //更新key为栏目主键
    $category=array();
    foreach($data as $d){
        $category[$d[$fieldPri]]=$d;
      }
      return $category;
    }


    //只供channelList方法使用
    //典型的无限极分类只是递归时不能用$this要有self因为是静态方法
    static private function _channelList($data,$pid=0,$html="&nbsp;",$fieldPri='cid',$fieldPid='pid',$level=1){
        if(empty($data))
            return array();
        $arr=array();
        foreach($data as $v){
            $id=$v[$fieldPri];
            if($v[$fieldPid]==$pid){
                $v['_level']=$level;
                $v['_html']=str_repeat($html, $level-1);
                array_push($arr,$v);
                $tmp=self::_channelList($data,$id,$html,$fieldPri,$fieldPid,$level+1);
                $arr=array_merge($arr,$tmp);
            }
        }
        return $arr;
    }
 

/**
     * 获得树状数据
     * @param $data 数据
     * @param $title 字段名
     * @param string $fieldPri 主键id
     * @param string $fieldPid 父id
     * @return array
     */
static public function tree($data,$title,$fieldPri='cid',$fieldPid='pid'){
    if(!is_array($data) || empty($data))
        return array();
    // $arr=Data::channelList($data,0,'',$fieldPri,$fieldPid);
    $arr=self::channelList($data,0,'',$fieldPri,$fieldPid);
    //顶级分类不需要对分类名进行处理
    //从第三级子分类开始添加level深度的字符前缀
    foreach($arr as $k=>$v){
        $str="";
        if($v['_level']>2){
           for($i=1;$i<$v['_level']-1;$i++){
            $str.="| &nbsp;&nbsp;&nbsp;&nbsp;";
           }
        }
        if($v['_level']!=1){
            $t=$title?$v[$title]:"";
            if(isset($arr[$k+1])&& $arr[$k+1]['_level']>=$arr[$k]['_level']){
                $arr[$k]['_name']=$str."├─ ".$v['_html'].$t;//平级子分类用T
            }else{
                $arr[$k]['_name']=$str."└─".$v['_html'].$t;//下级子分类用L
            }
        }else{
                $arr[$k]['_name']=$v[$title];//顶级分类不需要处理
            }
        }
        //设置主键为$fieldPri
        $data=array();
        foreach($arr as $d){
            $data[$d[$fieldPri]]=$d;
        }
        return $data;
    }
 }


