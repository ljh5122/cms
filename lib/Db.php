<?php

namespace lib;
use \lib\Config;

class Db{

    private static $pdo; //pdo 实例
    protected $lastSql = ''; //最后一条直行的 sql

    private function __construct(){}

    //创建 PDO 实例
    public static function connect($host = '', $port = '', $user = '', $password = '', $db_name = '', $charset = 'utf8'){
        if (self::$pdo == null) {
            $host     = $host ?: Config::get('db_host');
            $db_name  = $db_name ?: Config::get('db_name');
            $user     = $user ?: Config::get('db_user');
            $password = $password ?: Config::get('db_password');
            $port     = $port ?: Config::get('db_port');

            //初始化一个PDO对象
            try {
                self::$pdo = new \PDO('mysql:host='.$host.';dbname='.$db_name, $user, $password);
            } catch (PDOException $e) {
                die ("Error!: " . $e->getMessage() . "<br/>");
            }
        }

        return self::$pdo;
    }

   /**
    * 关闭连接
    */
    public function closeConnection(){
        self::$pdo = null;
    }

    /**
     * 执行
     * @param string $query
     * @param string $parameters
     * @throws PDOException
     */
    protected function execute($query, $parameters = ""){
        try {
            $sQuery = @self::$pdo->prepare($query);
            $this->bindMore($parameters);
            if (!empty($this->parameters)) {
                foreach ($this->parameters as $param) {
                    $parameters = explode("\x7F", $param);
                    $sQuery->bindParam($parameters[0], $parameters[1]);
                }
            }
            $this->success = $sQuery->execute();
        } catch (PDOException $e) {
            // 服务端断开时重连一次
            if ($e->errorInfo[1] == 2006 || $e->errorInfo[1] == 2013) {
                $this->closeConnection();
                $this->connect();

                try {
                    $sQuery = self::$pdo->prepare($query);
                    $this->bindMore($parameters);
                    if (!empty($this->parameters)) {
                        foreach ($this->parameters as $param) {
                            $parameters = explode("\x7F", $param);
                            $sQuery->bindParam($parameters[0], $parameters[1]);
                        }
                    }
                    $this->success = $sQuery->execute();
                } catch (PDOException $ex) {
                    $this->rollBackTrans();
                    throw $ex;
                }
            } else {
                $this->rollBackTrans();
                $msg = $e->getMessage();
                $err_msg = "SQL:".$this->lastSQL()." ".$msg;
                $exception = new \PDOException($err_msg, (int)$e->getCode());
                throw $exception;
            }
        }

        $this->parameters = array();
    }

    /**
     * 执行 SQL
     * @param string $query
     * @param array  $params
     * @param int    $fetchmode
     * @return mixed
     */
    public function query($query = '', $params = null, $fetchmode = PDO::FETCH_ASSOC){

    }

    /**
     * 返回一列
     * @param  string $query
     * @param  array  $params
     * @return array
     */
    public function column($query = '', $params = null){

    }

    /**
     * 返回一行
     * @param  string $query
     * @param  array  $params
     * @param  int    $fetchmode
     * @return array
     */
    public function row($query = '', $params = null, $fetchmode = PDO::FETCH_ASSOC){

    }

    /**
     * 返回 lastInsertId
     * @return string
     */
    public function lastInsertId(){
        return self::$pdo->lastInsertId();
    }

    /**
     * 返回最后一条执行的 sql
     * @return  string
     */
    public function lastSQL(){
        return $this->lastSql;
    }

    /**
     * 开始事务
     */
    public function beginTrans(){
        try {
            return self::$pdo->beginTransaction();
        } catch (PDOException $e) {
            // 服务端断开时重连一次
            if ($e->errorInfo[1] == 2006 || $e->errorInfo[1] == 2013) {
                $this->closeConnection();
                $this->connect();
                return self::$pdo->beginTransaction();
            } else {
                throw $e;
            }
        }
    }

    /**
     * 提交事务
     */
    public function commitTrans(){
        return self::$pdo->commit();
    }

    /**
     * 事务回滚
     */
    public function rollBackTrans(){
        if (self::$pdo->inTransaction()) {
            return self::$pdo->rollBack();
        }
        return true;
    }
}