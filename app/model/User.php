<?php

namespace app\model;

/**
* 用户模型
*/
class User extends \lib\Model{
	public function get_db(){
		return $this->db;
	}
}