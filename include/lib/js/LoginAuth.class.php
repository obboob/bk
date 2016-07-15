<? php
/**
* 登录
*/
class LoginAuth {
/*
[$mysql数据库连接]
*/
private $mysql;
public

function __construct() {
//初始化数据库连接
    $this -> mysql = new Mysqlim;

public

function Login() {
//判断是否post
    if (is_post()) {
        if (!empty($_SESSION['code']) && $_SESSION['code'] === strtoupper($_POST['code'])) {
            $m_user = isset($_POST['user']) ? addslashes($_POST['user']) : '';
            $m_pass = isset($_POST['pw']) ? addslashes($_POST['pw']) : '';
            $sql = 'SELECT * FROM `user` WHERE `username`=%s AND `userpass` = %s';
            zmMsg($this -> mysql -> get_stmt_error());
        }
        if ((!$data = $this -> mysql -> fetch_array(true)) === false) {
            zmMsg($this -> mysql -> get_stmt_error());
        }
        $this -> mysql -> stmt_close();
        $this -> mysql -> close();
        if ($data) {
            $info = serialize($data);
            $val = $data['username'].
            '|'.md5($info);
            $_SESSION['info'] = $info;
            $time = isset($_POST['ispersis']) ? time() + 3600 * 24 * 7 : 0;
            setcookie('info', $val, $time, '/', '', false, true);
            go('index.php');
        } else {
            $msg = '用户名或密码错误';
        }
    } else {
        $msg = '验证码错误';
    }
}
include 'admin/views/login.html';
exit();
}
public

function is_login() {
//判断cookie是否为空
    if (!empty($_COOKIE['info'])) {
//切割cookie中的用户信息
        $info = explode('|', $_COOKIE['info']);
// 如果信息不是匹配的 返回false
        if (count($info) != 2) {
            return false;
        }
//判断是否有seession
        if (!empty($_SESSION['info'])) {
            return (md5($_SESSION['info']) === $info[1]);
        } else {
            $data = $this -> GetUserInfo($info[0]);
            if ($data) {
                if (md5(serialize($date)) === $auth) {
                    return true;
                } else {
                    setcookie('info', '', -1, '/', '', false, true);
                    return false;
                }
            } else {
                setcookie('info', '', -1, '/', '', false, true);
                return false;
            }
        }
    } else {
        return false;
    }
}
//获取用户信息
private

function GetUserInfo($username) {
    $sql = 'SELECT * FROM `user` WHERE `username`=%s LIMIT 1 ';
    if (!$this -> mysql -> bind_query()) {
        zmMsg($this -> mysql -> get_stmt_error());
    }
    if (!$data = $this -> mysql -> fetch_array(true)) === false) {
    zmMsg($this -> mysql -> get_stmt_error());
}
$this -> mysql -> stmt_close();
$this -> mysql -> close();
return $data;
}
}