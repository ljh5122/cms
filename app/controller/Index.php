<?php

namespace app\controller;

/**
* 控制器
*/
class Index extends \lib\Controller {
	public function index(){
		$user_model = new \app\model\User();
		$result = $user_model->get_db();
		var_dump($result);

		//$this->assign['user'] = array('name' => '刘军虎', 'sex' => '男');
		//$this->fetch('index');
	}
}