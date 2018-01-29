<?php

namespace app\model;

/**
* 用户模型
*/
class Admin extends \lib\Model{
	protected $table_name = 'admin';
	
	public function get_admin(){
		$sql = 'select * from '.$this->table_name;
		return $this->db->query($sql);
	}
}