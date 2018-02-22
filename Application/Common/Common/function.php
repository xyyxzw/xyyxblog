<?php
//设置验证码
header("Content-type:text/html;charset=utf-8");
//调试数据易于阅读
function p($data){
    $str='<pre style="display: block;padding: 9.5px;margin: 44px 0 0 0;font-size: 25px;line-height: 1.42857;color: blue;word-break: break-all;word-wrap: break-word;background-color: #F5F5F5;border: 1px solid #CCC;border-radius: 4px;">';
    //如果是boolean或者null直接显示文字，否则print
    if(is_bool($data)){
        $show_data=$data?'true':'false';
    }elseif(is_null($data)){
        $show_data='null';
    }else{
        $show_data=print_r($data,true);// 第二个参数 为true时 返回值 而不显示
    }
    $str.=$show_data;
    $str.='</pre>';
    echo $str;
}

function show_verify($config=''){
	if($config==''){
		$config=array(
                                'codeSet'=>'13456789abcdefghjkmnpqrstuvwxyABCDEFGHJKMNPQRSTUVWXY',
                                'fontSize'=>30,
                                //是否使用混淆曲线 默认为true
                                'useCurve'=>false,
                                'imageH'=>60,
                                'imageW'=>240,
                                'length'=>4,
                                'fontttf'=>'4.ttf',
			);
	}
	$verify=new \Think\Verify($config);
	return $verify->entry();
}
//检测验证码
function check_verify($code){
	$verify=new \Think\Verify();
	return $verify->check($code);
}
/**
 * 字符串截取 支持中文和其他编码
 * @param  string  $str     需要转换的字符串
 * @param  integer $start   开始位置
 * @param  string  $length  截取长度
 * @param  boolean $suffix  截断显示字符
 * @param  string  $charset 编码格式
 * @return string
 */
/*以上代码用到了二个函数iconv_substr和mb_substr，均可以在当前字符下进行字符串截取，以达到中文字符截取的不乱码。

应该如何选择呢？

1、iconv库在某些操作系统上可能运行不正确，需要安装GNU扩展库以保证它的正常运行。mb_substr函数的兼容性更好。

2、iconv函数会先将当前字符串转换为相应的编码再进行截取，而mb函数则是直接根据指定的编码进行截取(提供安全的多字节截取)，所以mb函数的截取效率更高。

因此，mb_substr函数进行中文字符串的截取为最合适的选择。
输入的描述大于规定的长度才加...，否则不截取也不加...
*/
function re_substr($str,$start=0,$length,$charset="utf-8"){
    if(function_exists('mb_substr')){
        if(mb_strlen($str)>$length){
        $slice=mb_substr($str,$start,$length,$charset).'...';
       }else{
        $slice=$str;
       }
    }elseif(function_exists('iconv_substr')){
        if(iconv_strlen($str)>$length){
        $slice=iconv_substr($str,$start,$length,$charset).'...';
       }else{
        $slice=$str;
       }
    }else{
        //UTF8
// [\x01-\x7f]|[\xc0-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}
        $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
        $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
        $re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
        $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
        //$match多维数组，作为输出参数输出所有匹配结果
        preg_match_all($re[$charset],$str,$match);
        //join函数 是 implode() 函数的别名 把数组元素组合为一个字符串
        //array_slice() 函数返回数组中的选定部分。
        $slice=join("",array_slice($match[0],$start,$length));

    }
    return $slice;

}
//未优化版
// function re_substr($str,$start=0,$length,$suffix=true,$charset="utf-8"){
//     if(function_exists('mb_substr')){
//         $slice=mb_substr($str,$start,$length,$charset);
//     }elseif(function_exists('iconv_substr')){
//         $slice=iconv_substr($str,$start,$length,$charset);
//     }else{
//         //UTF8
// // [\x01-\x7f]|[\xc0-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}
//         $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
//         $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
//         $re['gbk']  = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
//         $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
//         //$match多维数组，作为输出参数输出所有匹配结果
//         preg_match_all($re[$charset],$str,$match);
//         //join函数 是 implode() 函数的别名 把数组元素组合为一个字符串
//         //array_slice() 函数返回数组中的选定部分。
//         $slice=join("",array_slice($match[0],$start,$length));

//     }
//     return $suffix?$slice.'...':$slice;

// }
/**
 * 传递ueditor生成的内容获取其中图片的路径
 * @param  string $str 含有图片链接的字符串
 * @return array      匹配的图片数组
 */
// function get_ueditor_image_path($str){
//     $preg='/\/Upload\/image\/ueditor\/\d*\/\d*\.[jpg|jpeg|png|bmp|gif]*/i';
//     preg_match_all($preg,$str,$data);
//     return current($data);//输出数组中的当前元素的值

// }
function get_ueditor_image_path($str){
    $preg='/\/Upload\/image\/ueditor\/\d*\/\d*\.[jpg|jpeg|png|bmp|gif]*/i';
    preg_match_all($preg, $str,$data);
    return current($data);
}
/**
 * 传递图片路径根据配置添加水印
 * @param string $path 图片路径
 */
function add_water($path){
    //获取文件后缀
   /* PATHINFO_DIRNAME - 只返回 dirname
    PATHINFO_BASENAME - 只返回 basename
    PATHINFO_EXTENSION - 只返回 extension*/
    $type=strtolower(pathinfo($path,PATHINFO_EXTENSION));
    //不对gif加水印
    if($type==='gif'){
        return true;
    }
    $image=new \Think\Image();
    if(C('WATER_TYPE')==1){
        //添加文字水印
        $image->open($path)->text(C('TEXT_WATER_WORD'),C('TEXT_WATER_TTF_PTH'),C('TEXT_WATER_FONT_SIZE'),C('TEXT_WATER_COLOR'),C('TEXT_WATER_LOCATE'),0,C('TEXT_WATER_ANGLE'))->save($path);
    }elseif(C('WATER_TYPE')==2){
        //添加图片水印
        $image->open($path)->water(C('IMAGE_WATER_PIC_PTAH'),C('IMAGE_WATER_LOCATE'),C('IMAGE_WATER_ALPHA'))->save($path);
    }elseif(C('WATER_TYPE')==3){
        // 添加图片和文字水印
        $image->open($path)->text(C('TEXT_WATER_WORD'),C('TEXT_WATER_TTF_PTH'),C('TEXT_WATER_FONT_SIZE'),C('TEXT_WATER_COLOR'),C('TEXT_WATER_LOCATE'),0,C('TEXT_WATER_ANGLE'))->save($path);
        $image->open($path)->water(C('IMAGE_WATER_PIC_PTAH'),C('IMAGE_WATER_LOCATE'),C('IMAGE_WATER_ALPHA'))->save($path);
    }
}
/**
 * 传入时间戳,计算距离现在的时间
 * @param  number $time 时间戳
 * @return string     返回多少以前
 */
function word_time($time) {
    $time = (int) substr($time, 0, 10);
    $int = time() - $time;
    $str = '';
    if ($int <= 2){
        $str = sprintf('刚刚', $int);
    }elseif ($int < 60){
        $str = sprintf('%d秒前', $int);
    }elseif ($int < 3600){
        $str = sprintf('%d分钟前', floor($int / 60));
    }elseif ($int < 86400){
        $str = sprintf('%d小时前', floor($int / 3600));
    }elseif ($int < 1728000){
        $str = sprintf('%d天前', floor($int / 86400));
    }else{
        $str = date('Y-m-d H:i:s', $time);
    }
    return $str;
}

/**
 * 将ueditor存入数据库的文章中的图片绝对路径转为相对路径
 * @param  string $content 文章内容
 * @return string          转换后的数据
 */
function preg_ueditor_image_path($data){
    // 兼容图片路径
    $root_path=rtrim($_SERVER['SCRIPT_NAME'],'/index.php');
    // 正则替换图片
    $data=htmlspecialchars_decode($data);
    $data=preg_replace('/src=\"^\/.*\/Upload\/image\/ueditor$/','src="'.$root_path.'/Upload/image/ueditor',$data);
    return $data;
}
// 获取访客系统
function getOS()
{
    $os    = '';
    $Agent = $_SERVER['HTTP_USER_AGENT'];
    //return $Agent;
    if (preg_match('/Win/', $Agent) && preg_match('/NT 5.0/', $Agent)) {
        $os = 'Win 2000';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT 6.1/', $Agent)) {
        $os = 'Win 7';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT 5.1/', $Agent)) {
        $os = 'Win XP';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT 6.2/', $Agent)) {
        $os = 'Win 8';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT 6.3/', $Agent)) {
        $os = 'Win 8.1';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT 10/', $Agent)) {
        $os = 'Win 10';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/NT/', $Agent)) {
        $os = 'Win';
    } elseif (preg_match('/Win/', $Agent) && preg_match('/32/', $Agent)) {
        $os = 'Win 32';
    } elseif (preg_match('/Mi/', $Agent)) {
        $os = '小米';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/LG/', $Agent)) {
        $os = 'LG';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/M1/', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/MX4/', $Agent)) {
        $os = '魅族4';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/M3/', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/M4/', $Agent)) {
        $os = '魅族';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/H/', $Agent)) {
        $os = '华为';
    } elseif (preg_match('/Android/', $Agent) && preg_match('/vivo/', $Agent)) {
        $os = 'Vivo';
    } elseif (preg_match('/Android/', $Agent)) {
        $os = 'Android';
    } elseif (preg_match('/Linux/', $Agent)) {
        $os = 'Linux';
    } elseif (preg_match('/Unix/', $Agent)) {
        $os = 'Unix';
    } elseif (preg_match('/iPhone/', $Agent)) {
        $os = 'iPhone';
    } elseif ($os == '') {
        $os = 'Unknown';
    }

    return $os;
}