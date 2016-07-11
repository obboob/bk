<?php 
include dirname(__DIR__) . '/include/inc.php';
$action = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';
if ($action == 'edit') {
	//获取文字id
	$id=isset($_REQUEST['id']) ? intval($_REQUEST['id']) :0;
	//判断是否post
	if (is_post()) {
		$title=isset($_POST['title'])?$_POST['title']:'';
		$content=isset($_POST['content'])?$_POST['content']:'';
		if ($title!='') {
			$sql='UPDATE `article` SET `title`=?,`content`=? WHERE`id`=? LIMIT 1';
			//初始化stmt
			$stmt=$db->stmt_init();
			//预处理sql
			if (!$stmt->prepare($sql)) {
				die($stmt->error);
			}
			if (!$stmt->bind_param('ssi',$title,$content,$id)) {
				die($stmt->error);
			}
			if (!$stmt->execute()) {
				die($stmt->error);
			}
			// $stmt->close();//关闭stmt线程
			 // $sql->close();//关闭数据库
			 go('admin_log.php');
			}
		}
		$sql='SELECT * FROM `article` WHERE `ID`=' .$id. '  LIMIT 1 ';
		$row=$db->query($sql)->fetch_row();
		include 'views/edit_log.html';
	}else{
		if(is_post()){
			$title=isset($_POST['title']) ? $_POST['title'] :'';
			$content = isset($_POST['content']) ? $_POST['content'] : '';
			if ($title!='') {
				$sql='INSERT INTO `article` (`title`,`content`) VALUES (?,?)';
			//初始化stmt
				$stmt = $db->stmt_init();
			//预处理sql
				if (!$stmt->prepare($sql)) {
					die($stmt->error);
				}
				if (!$stmt->bind_param('ss',$title,$content)) {
					die($stmt->error);
				}
				if (!$stmt->execute()) {
					die($stmt->error);
				}
		}$stmt->close();//关闭stmt线程
 		  $sql->close();//关闭数据库
 		  go('admin_log.php');
 		}
 		include 'views/write_log.html';
 	}

