<?php
namespace App\libs;
final class Mycat
{
    private static $mycat;
    private $dbms='mysql';     //数据库类型
    private $host='mycat:8066'; //数据库主机名
    private $dbName='mariadb';    //使用的数据库
    private $user='root';      //数据库连接用户名
    private $pass='000000';          //对应的密码
    private $db;
    private $info;
    private $result;
//    单例模式数据库会自动断开，保活
    private $date;

    protected function __construct()
    {
        $this->connect();
    }

    public function get_date(){
        return $this->date;
    }

    public function pdo_ping(){
        try{
            $this->db->getAttribute(\PDO::ATTR_SERVER_INFO);
        } catch (PDOException $e) {
            if(!strstr($e->getMessage(), 'MySQL server has gone away')){
                $this->db=null;
                $this->connect();
                return false;
            }
        }
        $this->date = time();
        return true;
    }

    protected function connect(){
        try {
            $this->db = new \PDO("$this->dbms:host=$this->host;dbname=$this->dbName", $this->user, $this->pass,[\PDO::ATTR_PERSISTENT => true]);
            echo "连接成功";
            $this->date = time();
            $this->info = '连接成功';
        } catch (PDOException $e) {
            echo ("Error!: " . $e->getMessage());
            $this->info = '连接失败';
        }
    }

    public function query($sql){
        if (time()-$this->date>600){
            $this->db=null;
            $this->connect();
            $this->date = time();
            return $this->query($sql);
        }
        if ($re = $this->db->query($sql)){
            $this->result = $re;
            $this->date = time();
        }else{
            $this->result = false;
        }
        return $this->result;
    }

    public function insert($sql){
        return $this->query($sql);
    }

    public function selectone($sql){
        $re = $this->query($sql);
        if ($re){
            $rows = $re->fetchAll();
            return $rows[0];
        }
        return false;
    }

    public function selectall($sql){
        $re = $this->query($sql);
        if ($re){
            $rows = $re->fetchAll();
            return $rows;
        }
        return false;
    }

    public static function get_mycat(){
        if (self::$mycat == null){
            self::$mycat = new Mycat();
        }
        return self::$mycat;
    }

    protected function __clone()
    {

    }
}
