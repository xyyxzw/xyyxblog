<?php
//设置验证码
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