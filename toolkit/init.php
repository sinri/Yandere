<?php
/*
 * All Hail Sinri Edogawa
 * =========================================
 * Weishop 的初始化文件
 * 反正就是初始化，搞搞数据库啊日志啊啥的
 * 绝命：2015年4月8日
 * =========================================
 * 针对新项目的修正处以【TODO】标记
 */


require_once(__DIR__."/../toolkit/lib_log.php");

date_default_timezone_set("Asia/Shanghai");

require_once(__DIR__."/../toolkit/config.php");

// Set up the logger.
// URL: such as http://XXX.com/weishop/toolkit/log/ws_20150408.log
QLog::setLogDir($log_file_path);


require(__DIR__."/../toolkit/SinriPDO.php");

global $db;
$db = new SinriPDO($deploy_level,$username,$password,$host,$port,$database,$charset);

function getRequest($name,$default=null){
	if(isset($_REQUEST[$name])){
		return $_REQUEST[$name];
	}else{
		return $default;
	}
}

function maskValue($mono,$default=''){
	if($isset($mono)){
		return $mono;
	}else{
		return $default;
	}
}