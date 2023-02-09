<?php
namespace App\libs;

class Mycat_short{
    private $dbms='mysql';     //数据库类型
    private $host='mycat:8066'; //数据库主机名
    private $dbName='mariadb';    //使用的数据库
    private $user='root';      //数据库连接用户名
    private $pass='000000';          //对应的密码
    private $db;

    public function __construct()
    {
        $this->connect();
    }

    public function __destruct()
    {
        $this->db = null;
    }

    public function connect(){
        try {
            $this->db = new \PDO("$this->dbms:host=$this->host;dbname=$this->dbName", $this->user, $this->pass);
            echo "连接成功";
            $this->info = '连接成功';
        } catch (PDOException $e) {
            echo ("Error!: " . $e->getMessage());
            $this->info = '连接失败';
        }
    }

    public function query($sql){
        if ($re = $this->db->query($sql)){
            $this->result = $re;
        }else{
            $this->result = false;
        }
        return $this->result;
    }

    public function selectall($sql){
        $re = $this->query($sql);
        if ($re){
            $rows = $re->fetchAll();
            return $rows;
        }
        return false;
    }
}
