<?php 
include dirname(__DIR__).'/include/inc.php';
$login=new LoginAuth;
if (!$login->is_login()) {
    $Login->Login();
}