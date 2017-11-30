<?php

namespace lib;
use \lib\Config;
use \lib\Db;

/**
* 模型
*/
class Model {
	
	protected $db = null;
	protected $model = null;

	public function __construct($model_str = ''){
		$db_host = Config::get('db_host');
		$db_name = Config::get('db_name');
		$db_user = Config::get('db_user');
		$db_password = Config::get('db_password');
		$db_port = Config::get('db_port');
		$this->db = Db::connect($db_host, $db_port, $db_user, $db_password, $db_name);
	}
}