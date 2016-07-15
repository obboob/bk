<?php 
/**
 * 文章模型
 */
class ArticleModel{
    private $mysql;
    /*
    [__construct 构建函数自动加载]
     */
    public function __construct(){
        $this->mysql=new Mysqlim;
    }
/*
  添加文章模型方法
 */
    public function ArticleAdd($title ='',$content=''){
        $sql ='INSERT INTO `article`(`title`,`content`) VALUES(%s,%s)';
        if (!$this->mysql->bind_query($sql,array($title,$content))) {
            zmMsg($this->mysql->get_stmt_error());
        }
        $status=$this->mysql->get_affected_rows();//获取响应行数
        $this->mysql->stmt_close();//关闭stmt线程
        $this->mysql->colse();//关闭数据库
        return ($status>0);
    }
/*
更新数据
 */
    public function ArticleUpdate(){
        $sql='UPDATE `article` SET `title`=%s,`content`=%s WHERE `id`=d% LIMIT 1';
        if (!$status=$this->mysql->bind_query($sql,array($title,$content,$id))) {
            zmMsg($this->mysql->get_stmt_errot());
        }
        $this->mysql->stmt_close();//关闭stmt线程
        $this->mysql->colse();//关闭数据库
        return $status;
    }
/*
 查看一篇文章
 */


public function ArticleOne($id=0){
    $sql='SELECT * FROM `article` WHERE `id`=%d LIMIT 1 ';
    if (!$this->mysql->bind_query($sql,array($id))) {
        zmMsg($this->mysql->get_stmt_error());
    }
    //判断取出数据是否异常
    if (($date=$this->mysql->fetch_array(ture))===false) {
        zmMsg($this->mysql->get_stmt_error());
    }
       $this->mysql->stmt_close();//关闭stmt线程
        $this->mysql->colse();//关闭数据库
        return $data;
}



/**[ArticleALL]获取所有文章数据
  */
public function ArticleALL(){
    $sql='SELECT * FROM `article` WHERE 1';
    if (!$this->mysql->bind_query($sql)) {
        zmMsg($this->mysql->get_stmt_error());
    }
    if (($data=$this->mysql->fetch_array())===false) {
        zmMsg($this->mysql->get_stmt_error());
    }
    $this->mysql->stmt_close();//关闭STMT线程
    $this->mysql->close();//关闭数据库
    return $date;
}
}
