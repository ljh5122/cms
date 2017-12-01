<?php

namespace lib;
use \lib\Db;

/**
* 模型
*/
class Model {
	
	protected $db = null;
	protected $model = null;

	public function __construct($model_str = ''){
		//$this->db = new \lib\Db($db_host, $db_port, $db_user, $db_password, $db_name);
		$this->db = Db::connect();
	}
}