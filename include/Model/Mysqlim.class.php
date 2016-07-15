<? php
//include dirname(dirname(__DIR__))."/inc.php";
class Mysqlim {
    private $db;
    static private $stmt;
    /*链接数据库*/
    public
    function __construct($db_host = DB_HOST, $db_user = DB_USER, $db_pass = DB_PASS, $db_name = DB_NAME, $db_port = DB_PORT) {
        /*如果需要调用系统自带的方法或类，需要在其前面加“\”;表示顶层空间 */
        $this -> db = @new Mysqli($db_host, $db_user, $db_pass, $db_name, $db_port);
        if ($this -> db -> connect_errno) {
            $this -> error($this -> db -> connect_errno);
        }
        $this -> db -> set_charset("utf8");
    }
    /*绑定参数发送语句*/
    public
    function bind_query($query_string, $param = array()) {
        self::$stmt = $this -> db -> stmt_init();
        $map = array('%b' => "b", '%f' => "d", '%d' => "i", '%s' => "s"); /*  */
        $pattan = "/(".implode('|', array_keys($map)).
        ")/"; /* 定义正则表达式 */
        if (preg_match_all($pattan, $query_string, $matches)) { /* 匹配参数数目和类型 */
            if (count($matches[0]) != count($param)) {
                die("传入参数不对。");
            }
            $str = implode("", $matches[0]); /* 转化要被替换的数据的数据类型 */
            $param1 = strtr($str /* 要转化的 */ , $map /* 转化规则 */ );
            $param2 = preg_replace($pattan, "?", $query_string); /* sql语句 */
            array_unshift($param, $param1); /* 开始绑定参数 */
            //var_dump($param);
            $param3 = "";
            foreach($param as $key => $value) {
                $param3[$key] = & $param[$key]; /*定义传入地址 */
            }
            //var_dump($param3);
            self::$stmt -> prepare($param2) or zmMsg(self::$stmt -> error);
            //var_dump($param3);
            call_user_func_array(array(self::$stmt, "bind_param"), $param3); /* 动态改变传入参数 */ /* 传入引用参数 */

            return self::$stmt -> execute();
            /*	var_dump(self::$stmt->execute()) ;
			var_dump(self::$stmt->store_result());
			echo self::$stmt->affected_rows;*/
        } else { /* 不需要绑定参数 */
            self::$stmt -> prepare($query_string) or zmMsg(self::$stmt -> error);
            return self::$stmt -> execute();
        }

    }
    public
    function fetch_all($status = false) {
        if (!self::$stmt -> store_result()) { /* 储存结果到内存 */
            return false;
        }
        $data = array();
        $varialable = array();
        if (self::$stmt -> num_rows > 0) {
            $meta = self::$stmt -> result_metadata(); /* 取回查询信息种类 */
            while ($field = $meta -> fetch_field()) { /*取回字段名信息  */
                # code...
                $varialable[] = & $data[$field -> name]; /* 定义引用地址 */
            }
            if (!call_user_func_array(array(self::$stmt, "bind_result"), $data)) {
                return false;
            }
            //var_dump($data);
            if ($status != false) { /* 取回一条结果集 */
                self::$stmt -> fetch();
                return $data;

            } else {
                $arr = 0;
                $array = array();
                while (self::$stmt -> fetch()) {
                    foreach($data as $key => $value) { /*处理引用地址问题吧  */
                        $array[$arr][$key] = $value;
                        # code...
                    }
                    $arr += 1;
                }
                return $array;
            }

        } else {
            return null;
        }
    }
    public
    function get_stmt_error() {
        return self::$stmt -> error;
    }
    public
    function close() {
        $this -> db -> close();
    }
    public
    function stmt_close() {
        self::$stmt -> close();
    }

    function get_affected_rows() {
        return self::$stmt -> affected_rows;
    }

    function get_num_rows() {
        return self::$stmt -> num_rows;
    }
    private
    function error($connect_errno) {
        switch ($connect_errno) {
            case 1044:
            case 1045:
                zmMsg("连接数据库失败，数据库用户名或密码错误");
                break;

            case 1049:
                zmMsg("连接数据库失败，未找到您填写的数据库");
                break;

            case 2003:
                zmMsg("连接数据库失败，数据库端口错误");
                break;

            case 2005:
                zmMsg("连接数据库失败，数据库地址错误或者数据库服务器不可用");
                break;

            case 2006:
                zmMsg("连接数据库失败，数据库服务器不可用");
                break;

            default:
                zmMsg("连接数据库失败，请检查数据库信息。错误编号：".$connect_errno);
                break;
        }
    }

}
/*$A=new Mysqlim(); 
$A->bind_query("UPDATE  `article`  SET `title` = %s  , `content` =  %s  WHERE   `id` =  %d",array( 2,'s',' sdfadsf')); 
$aa=$A->get_affected_rows();
var_dump($aa);*/
//$A->bind_query();
/*if(!$re=$A->fetch_all()){
	var_dump($A->get_stmt_error());
}
var_dump($re);*/

?>