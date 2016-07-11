<?php 
include dirname(__DIR__) . '\include/inc.php';
//判断请求类型
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST'){
	if (!empty($_SESSION['code']) && strtoupper($_SESSION['code']) === strtoupper($_POST['code'])) {
	$m_user = isset($_POST['user']) ? addslashes($_POST['user']) : '';
	$m_pass = isset($_POST['pw']) ? addslashes($_POST['pw']) : '';
	//初始化STMT
	$stmt = $db->stmt_init();      	//mysqli_stmt_init
	$sql = 'SELECT * FROM `user` WHERE `username`=? AND `userpass` = ?';//问号是占位符
	//预处理sql语句
	if (!$stmt->prepare($sql)) {   	//mysqli_stmt::prepare
		var_dump($stmt->error);
	}
	//绑定值到sql中
	if (!$stmt->bind_param('ss',$m_user,$m_pass)) {//mysqli_stmt::bind_param
		var_dump($stmt->error);   //第一个参数，对应sql语句中？号数量的参数，第二个参数起，依次对?问号值进行值绑定
	}
	//执行sql 
	if (!$stmt->execute()) {   		//mysqli_stmt::execute
		var_dump($stmt->error);
	}//执行sql语句
	 //取出查询到的数据
	$stmt->store_result();		//mysqli_stmt::store_result
	//绑定取出数据的变量名
	if (!$stmt->bind_result($id,$a,$b)) {	 //mysqli_stmt::bind_result
		var_dump($stmt->error);			//参数根据数据查询的字段数量来填写
	}
	if ($stmt->fetch()) {		//mysqli_stmt::fetch
		// var_dump($stmt->error);	//把取出的数据，赋值给绑定的变量
	}
	if ($stmt->num_rows>0) {
		$msg = '登录成功';
		header('location:write_log.php');
	}else{
		$msg = '用户名或者密码错误';
	}
}else{ 
	$msg= '验证码错误'; 
	
	}
}

include "views/login.html";

