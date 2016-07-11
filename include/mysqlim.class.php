<?php  
//命名空间
namespace lib;
$a=include '../admin/config.php';
var_dump($a);
/**
* mysqli 数据库操作类
*/
class Mysqlim{
/**
* [$db 私有属性，数据库连接]
* @var[type]
*/
private $db;
/**
*[__construct 构建函数]
*/
private static $stmt;
//静态stmt属性
public function __construct($db_host=DB_HOST,$db_user=DB_USER,$db_pass=DB_PASS,$db_name=DB_NAME,$db_port=DB_PORT){
//判断系统是否存在mysqli类
	class_exists('Mysqli',false)||die('该php空间不支持mysqli类');

//实例化数据库连接
//使用命名空间，如果需要调用系统自带的class或者function,需要在前面加上[\]，表示顶层
	$this->db=new \Mysqli($db_host,$db_user,$db_pass,$db_name,$db_port);
//判断数据库是否连接正常
	if ($this->db->connect_errno) {
		die($this->db->connect_errno);
	}
//设置数据库编码
	$this->db->set_charset('utf8');
}
public function bind_query($query_string,$param){
//初始化stmt
	self::$stmt=$this->db->stmt_init();
	$map=array(
'%d'=>'i',//整型
'%f'=>'d',//浮点
'%s'=>'s',//字符串
'%b'=>'b',//二进制
);
//生成正则表达式
	$expr='/('.implode('|', array_keys($map)).')/';
//匹配需要绑定的类型
	if (preg_match_all($expr, $query_string, $matches)) {
//拼接匹配到的类型
		if (count($matches['0'])!=count($params)) {
			die('传入参数不符合');
		}
		$types=implode('',$matches['0']);
//转换为绑定参数
		$types=strtr($types,$map);
//生成sql语句
		$query=preg_replace($expr, '?', $query_string);
		if (!self::$stmt->prepare($query)) {
			return false;
		}
		//把绑定类型添加到数组第一个
		array_unshift($parmas, $types);
		//处理绑定参数是需要引用的问题
		$array=array();
		foreach ($params as $key => $value) {
			$array[$key] = &$params[$key];
		}
		//使用回调函数的形式动态传参
		if (!call_user_func_array(array(self::$stmt,'bind_param'),$array)) {
			return false;
		}//执行sql
		return self::$stmt->execute();//返回执行结果
	}else{
		//如果没有任何需要绑定的参数之间预处理sql语句
		if (!self::$stmt->prepare($query_string)) {
			return false;
		}
		//执行sql
		return self::$stmt->execute();//返回执行结果
	}
}
}
$a=new Mysqlim();
$a->bind_query('SELECT * FROM `article` WHERE 1',array(1,'admin'));
// var_dump($a);
