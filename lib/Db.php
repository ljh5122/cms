<?php

namespace lib;

use Exception;
use PDO;
use PDOException;

class Db{

    protected $pdo; //pdo 实例
    protected $lastSql = ''; //最后一条 sql
    protected $sQuery; //PDOStatement 实例

    public function __construct($host = '', $port = '', $user = '', $password = '', $db_name = '', $charset = 'utf8'){
        try {
            $this->pdo = new PDO('mysql:host='.$host.';dbname='.$db_name, $user, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->pdo->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
            $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        } catch (PDOException $e) {
            die ("Error : " . $e->getMessage() . "<br/>");
        }
    }

    /**
     * 执行
     * @param string $query
     * @throws PDOException
     */
    protected function execute($query){
        try {
            $this->sQuery = @$this->pdo->prepare($query);
            $this->sQuery->execute();
        } catch (PDOException $e) {
            $this->rollBackTrans();
            $msg = $e->getMessage();
            $err_msg = "SQL:".$this->lastSQL()." ".$msg;
            $exception = new PDOException($err_msg, (int)$e->getCode());
            throw $exception;
        }
    }

    /**
     * 执行 SQL
     * @param string $query
     * @param int    $fetchmode
     * @return mixed
     */
    public function query($query = '', $fetchmode = PDO::FETCH_ASSOC){
        $this->lastSql = $query;
        $this->execute($query);

        $rawStatement = explode(" ", $query);
        $statement = strtolower(trim($rawStatement[0]));
        if ($statement === 'select' || $statement === 'show') {
            return $this->sQuery->fetchAll($fetchmode);
        } elseif ($statement === 'update' || $statement === 'delete') {
            return $this->sQuery->rowCount();
        } elseif ($statement === 'insert') {
            if ($this->sQuery->rowCount() > 0) {
                return $this->lastInsertId();
            }
        } else {
            return null;
        }

        return null;
    }

    /**
     * 返回一列
     * @param  string $query
     * @return array
     */
    public function column($query = ''){
        $this->lastSql = $query;
        $this->execute($query);
        $columns = $this->sQuery->fetchAll(PDO::FETCH_NUM);
        $column  = null;
        foreach ($columns as $cells) {
            $column[] = $cells[0];
        }

        return $column;
    }

    /**
     * 返回一行
     * @param  string $query
     * @param  int    $fetchmode
     * @return array
     */
    public function row($query = '', $fetchmode = PDO::FETCH_ASSOC){
        $this->lastSql = $query;
        $this->execute($query);
        return $this->sQuery->fetch($fetchmode);
    }

    /**
     * 返回单个值
     *
     * @param  string $query
     * @return string
     */
    public function single($query = ''){
        $this->lastSql = $query;
        $this->execute($query);
        return $this->sQuery->fetchColumn();
    }

    /**
     * 返回 lastInsertId
     * @return string
     */
    public function lastInsertId(){
        return $this->pdo->lastInsertId();
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
            return $this->pdo->beginTransaction();
        } catch (PDOException $e) {
            throw $e;
        }
    }

    /**
     * 提交事务
     */
    public function commitTrans(){
        return $this->pdo->commit();
    }

    /**
     * 事务回滚
     */
    public function rollBackTrans(){
        if ($this->pdo->inTransaction()) {
            return $this->pdo->rollBack();
        }

        return true;
    }
}