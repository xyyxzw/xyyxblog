<?php
return array(
//'配置项'=>'配置值'
'SHOW_PAGE_TRACE'=>false,//关闭trace信息
'TAGLIB_BUILD_IN' =>  'Cx,Common\Tag\My',//加载自定义标签（模板页面标签 标签函数 将my标签库合并成内置标签)
'LOAD_EXT_CONFIG'=>'db,webconfig,oauth',//加载网站额外设置文件
'TMPL_PARSE_STRING'=>array(
	//定义常用路径
	  /*./Tempate/default/  TMPL_PATH（index.php) */
	///Template/default/Home/Public/css
	'__HOME_CSS__'=>__ROOT__.trim(TMPL_PATH,'.').'Home/Public/css',
	'__HOME_JS__'=>__ROOT__.trim(TMPL_PATH,'.').'Home/Public/js',
	'__HOME_IMAGE__'=>__ROOT__.trim(TMPL_PATH,'.').'Home/Public/image',
	'__ADMIN_CSS__'=>__ROOT__.trim(TMPL_PATH,'.').'Admin/Public/css',
	'__ADMIN_JS__'=>__ROOT__.trim(TMPL_PATH,'.').'Admin/Public/js',
	'__ADMIN_IMAGE__'=>__ROOT__.trim(TMPL_PATH,'.').'Admin/Public/image',
	),
//允许访问的模块
'MODULE_ALLOW_LIST'=>array('Home','Admin','Api'),
//系统错误页面模板
//如果开启了调试则用系统自带大的出错页面否则用自定义的出错页面
'TMPL_EXCEPTION_FILE'=>APP_DEBUG?THINK_PATH.'Tpl/think_exception.tpl':'./Templlate/default/Home/Public/404/html',
// 'TMPL_EXCEPTION_FILE'   =>'./Template/default/Home/Public/404.html',                                    //404设置
//session设置
// 假设我们实现了一个Db类型的session驱动，那么只需要在配置文件中使用：
// 'SESSION_TYPE'=>'Db'  // 或者  'SESSION_OPTIONS'=>array(  'type'=>'Db', )系统在初始化Session的时候会自动处理，采用Db机制来处理session。
'SESSION_OPTIONS'=>array(
          'name'=>'XYYXSESSION',//设置session名
          'expire'=>24*3600*15,//session保存15天
          'use_trans_sid'=>1,//跨页传递
          'use_only_cookies'=>0,//是否只开启基于cookies的session的会话方法
	),
//'SESSION_PREFIX'        =>  'xyyx',
//URL设置
'URL_MODEL'=>1,//为了兼容性更好而设置成1 如果确认服务器开启了mod_rewrite 请设置为 2
'URL_CASE_INSENSITIVE'=>false,//区分url大小写
//当 URL_CASE_INSENSITIVE 设置为true的时候表示URL地址不区分大小写，这个也是框架在部署模式下面的默认设置。
// 当开启调试模式的情况下，这个参数是false，因此你会发现在调试模式下面URL区分大小写的情况。
//本系统版本号
'THINK_INFORMATION'=>'1.1',
);