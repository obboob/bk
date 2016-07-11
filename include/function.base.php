<?php 
/**
 * [is_post 判断是否POST请求]
 */
function is_post(){
	return (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST');
}


 ?>