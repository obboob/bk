<?php 
session_start();
include 'function.base.php';//引入公共函数
include 'config.php';//引入数据库配置文件
//实例化数据库连接
	$db = @new Mysqli( DB_HOST,DB_USER,DB_PASS,DB_NAME,DB_PORT);
	//获取连接数据库时的错误信息，输出错误信息
	if ($db->connect_errno) {
		die($db->connect_error);
	}

//设置连接数据库编码方式
if (! $db -> set_charset ( "utf8" )) {
     printf ( "Error loading character set utf8: %s\n" ,  $db -> error );
} 




 ?>