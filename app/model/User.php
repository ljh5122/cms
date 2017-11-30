<?php

namespace app\model;

/**
* 用户模型
*/
class User {

	private static $instance = null;

	private function __construct(){}

	public static function get_instance(){
		if (self::$instance == null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function getAll(){
		return array(111,222,333);
	}
}