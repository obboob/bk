<?php
include dirname(__DIR__) . '/include/inc.php';
$action =isset($_REQUEST['action'])?$_REQUEST['action']:'';
if ($action=='operate_log') {
	$ids=isset($_POST['blog'])?$_POST['blog']:array();
	$operate=isset($_POST['operate'])?$_POST['operate']:'';
	switch ($operate) {
		case 'del':
			//处理提交过来的文章id
		$ids=implode(',', array_map('intval', $ids));
		$sql='DELETE FROM `article` WHERE `id` IN ('.$ids.')';
			//执行sql
		if (!$db->query($sql)) {
			die($db->error);
		}
			//判断是否有响应函数
		if ($db->affectde_rows>0) {
			echo "删除成功";
		}else{
			echo "删除失败";
		}
		break;
	}
}else{
	$sql='SELECT * FROM `article` WHERE 1';
	if(!$result = $db->query($sql)){
		die($db->error);
	}
	$rows=$result->fetch_all();//查询所有
	include 'views/admin_log.html';
}




