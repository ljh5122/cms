<?php

namespace app\model;

/**
* 用户模型
*/
class Admin extends \lib\Model{
	protected $table_name = 'admin';

	public function table_name(){
		return $this->table_prefix.$this->table_name;
	}

	public function get_admin(){
		return $this->db->query('select * from sy_admin');
	}
}