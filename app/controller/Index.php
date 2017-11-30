<?php

namespace app\controller;

/**
* 控制器
*/
class Index{
	private static $instance = null;

	private function __construct(){}

	public static function get_instance(){
		if (self::$instance == null) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function index(){
		$user_model = \app\model\User::get_instance();
		var_dump($user_model->getAll());
	}
}