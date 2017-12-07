<?php

namespace app\controller;

/**
* 控制器
*/
class Index extends \lib\Controller {
	public function index(){
		$admin_model = new \app\model\Admin();
		$result = $admin_model->get_admin();
		$this->assign['admin'] = $result;
		$this->fetch('index');
	}
}