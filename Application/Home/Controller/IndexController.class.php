<?php
namespace Home\Controller;
use Common\Controller\HomeBaseController;
class IndexController extends HomeBaseController {
   /**
    * 网站首页
    */
   //首页
   public function index(){
    // $articles=D('Article')->getPageData('all','all','1','0',1);
    $articles=D('Article')->getPageData();
    // $articles=D('Article')->getDataByCid(2);
    // var_dump($articles);die;
    $assign=array(
         'articles'=>$articles['data'],
         'page'=>$articles['page'],
         'cid'=>'index',
        );
    $this->assign($assign);
    $this->display();
    // var_dump($assign);
    // echo "<pre>";
    // print_r($assign);
   }

   // 分类
   public function category(){
       $cid=I('get.cid',0,'intval');
       // 获取分类数据
       $category=D('Category')->getDataByCid($cid);
        // var_dump($category);die;
       // 如果分类不存在；则返回404页面
       if (empty($category)) {
           header("HTTP/1.0  404  Not Found");
           $this->display('./Template/default/Home/Public/404.html');
           exit(0);
       }
       // 获取分类下的文章数据
       $articles=D('Article')->getPageData($cid);
       // var_dump($articles);die;
       // 未优化 如果该分类下没有文章那么导航栏不显示改分类 或者 提示文字“该分类下没有文章” 而不用404页面
       if (empty($articles['data'])) {
           header("HTTP/1.0  404  Not Found");
           $this->display('./Template/default/Home/Public/404.html');
           exit(0);
       }
       $assign=array(
           'category'=>$category,
           'articles'=>$articles['data'],
           'page'=>$articles['page'],
           'cid'=>$cid
           );
       $this->assign($assign);
       $this->display();
   }
   //标签
   public function tag(){
       $tid=I('get.tid',0,'intval');
       // 获取标签名
       $tname=D('Tag')->getFieldByTid($tid,'tname');
       // 如果标签不存在；则返回404页面
       if (empty($tname)) {
           header("HTTP/1.0  404  Not Found");
           $this->display('./Template/default/Home/Public/404.html');
           exit(0);
       }
       // 获取文章数据
       $articles=D('Article')->getPageData('all',$tid);
       $assign=array(
           'articles'=>$articles['data'],
           'page'=>$articles['page'],
           'title'=>$tname,
           'title_word'=>'拥有<span class="b-highlight">'.$tname.'</span>标签的文章',
           'cid'=>'index'
           );
       $this->assign($assign);
       $this->display();
   }

   // 文章内容
   public function article(){
       $aid=I('get.aid',0,'intval');
       $cid=intval(cookie('cid'));
       $tid=intval(cookie('tid'));
       $search_word=cookie('search_word');
       $search_word=empty($search_word) ? 0 : $search_word;
      // echo $cid,$tid;die;0 0

       //浏览数开始
       $read=cookie('read');
       // 判断$read这个数组cookie里面是否有$aid(文章ID）是否已经记录过aid
       if (array_key_exists($aid, $read)) {
           // 判断点击本篇文章的时间是否已经超过一天
           if ($read[$aid]-time()>=3600) {
               $read[$aid]=time();
               // 文章点击量+1
               M('Article')->where(array('aid'=>$aid))->setInc('click',1);
           }
       }else{
           $read[$aid]=time();
           // 文章点击量+1
           M('Article')->where(array('aid'=>$aid))->setInc('click',1);
       }
       cookie('read',$read,3600);
       //浏览数开始结束
       // print_r($read);die;
       // print_r(cookie());die;//Array ( [cid] => 0 [tid] => 0 [search_word] => 0 [XYYXSESSION] => ggd7kfe367uv9in4f3hq466o67 [read] => think:{"3":"1519450523","2":"1519450851","1":"1519450870"} )


       switch(true){
           case $cid==0 && $tid==0 && $search_word==(string)0:
               $map=array();
               break;
           case $cid!=0:
               $map=array('cid'=>$cid);
               break;
           case $tid!=0:
               $map=array('tid'=>$tid);
               break;
           case $search_word!==0:
               $map=array('title'=>$search_word);
               break;
       }
       // print_r($map);die;
       // 获取文章数据
       $article=D('Article')->getDataByAid($aid,$map);
       // 如果文章不存在；则返回404页面
       if (empty($article['current']['aid'])) {
           header("HTTP/1.0  404  Not Found");
           $this->display('./Template/default/Home/Public/404.html');
           exit(0);
       }
       // 获取评论数据
       // $comment=D('Comment')->getChildData($aid);
       $assign=array(
           'article'=>$article,
           // 'comment'=>$comment,
           'cid'=>$article['current']['cid']
           );
       // if (!empty($_SESSION['user']['id'])) {
       //     $assign['user_email']=M('Oauth_user')->getFieldById($_SESSION['user']['id'],'email');
       // }
       // var_dump($article);die;
       // print_r($article);die;
       $this->assign($assign);
       $this->display();
   }


  //搜索
  public function search(){
    $search_word=I('get.search_word');
    // $search_word=I('post.search_word');
    $articles=D('Article')->getDataByTitle($search_word);
    $assign=array(
       'articles'=>$articles['data'],
       'page'=>$articles['page'],
       'titlekey'=>$search_word,
       'title_word'=>'搜索到的与<span class="b-highlight">'.$search_word.'</span>相关的文章',
       'cid'=>'index'
      );
    // var_dump($articles);die;
    $this->assign($assign);
    $this->display('tag');
  }

   //闲来一笔
   public function chat(){
     $assign=array(
        'data'=>D('Chat')->getDataByState(0,1),
        'cid'=>'chat'
      );
     $this->assign($assign);
     $this->display();
   }

}