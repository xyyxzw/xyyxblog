<?php
/**
 * 安装向导
 */
header('Content-type:text/html;charset=utf-8');
// 检测是否安装过
if (file_exists('./install.lock')) {
    echo '你已经安装过该系统，重新安装需要先删除./Public/install/install.lock 文件';
    die;
}
// 同意协议页面
if(@!isset($_GET['c']) || @$_GET['c']=='agreement'){
    require './agreement.html';
}
// 检测环境页面
if(@$_GET['c']=='test'){
    require './test.html';
}
// 创建数据库页面
if(@$_GET['c']=='create'){
    require './create.html';
}
// 安装成功页面
if(@$_GET['c']=='success'){
    // 判断是否为post
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $data=$_POST;
        // 连接数据库
        $link=@new mysqli("{$data['DB_HOST']}:{$data['DB_PORT']}",$data['DB_USER'],$data['DB_PWD']);
        // 获取错误信息
        $error=$link->connect_error;
        if (!is_null($error)) {
            // 转义防止和alert中的引号冲突
            $error=addslashes($error);
            die("<script>alert('数据库链接失败:$error');history.go(-1)</script>");
        }
        // 设置字符集
        $link->query("SET NAMES 'utf8'");
        $link->server_info>5.0 or die("<script>alert('请将您的mysql升级到5.0以上');history.go(-1)</script>");
        // 创建数据库并选中
        if(!$link->select_db($data['DB_NAME'])){
            $create_sql='CREATE DATABASE IF NOT EXISTS '.$data['DB_NAME'].' DEFAULT CHARACTER SET utf8;';
            $link->query($create_sql) or die('创建数据库失败');
            $link->select_db($data['DB_NAME']);
        }
        // 导入sql数据并创建表
        $bjyblog_str=file_get_contents('./xyyxblog.sql');
        $sql_array=preg_split("/;[\r\n]+/", str_replace('bjy_',$data['DB_PREFIX'],$bjyblog_str));
        foreach ($sql_array as $k => $v) {
            if (!empty($v)) {
                $link->query($v);
            }
        }
        $link->close();
        $db_str=<<<php
<?php
return array(

//*************************************数据库设置*************************************
    'DB_TYPE'               =>  'mysqli',                 // 数据库类型
    'DB_HOST'               =>  '{$data['DB_HOST']}',     // 服务器地址
    'DB_NAME'               =>  '{$data['DB_NAME']}',     // 数据库名
    'DB_USER'               =>  '{$data['DB_USER']}',     // 用户名
    'DB_PWD'                =>  '{$data['DB_PWD']}',      // 密码
    'DB_PORT'               =>  '{$data['DB_PORT']}',     // 端口
    'DB_PREFIX'             =>  '{$data['DB_PREFIX']}',   // 数据库表前缀
);
php;
        // 创建数据库链接配置文件
        file_put_contents('../../Application/Common/Conf/db.php', $db_str);
        @touch('./install.lock');
        require './success.html';
    }

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





