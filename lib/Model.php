<?php

namespace lib;
use \lib\Config;
use \lib\Db;

/**
* 模型
*/
class Model {
	
	protected $db = null;
	protected $table_prefix = ''; //表前缀
	protected $table_name = null; //表名
	protected $last_sql = ''; //最后一条 sql

	public function __construct($model_str = ''){
		$host      = Config::get('db_host');
        $db_name   = Config::get('db_name');
        $user      = Config::get('db_user');
        $password  = Config::get('db_password');
        $port      = Config::get('db_port');
        $charset   = Config::get('db_charset');

		$this->db = new Db($host, $port, $user, $password, $db_name, $charset);
		$this->table_prefix = Config::get('db_prefix');
	}
}