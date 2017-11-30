<?php

/**
* 应用引导类
*/
class Config {
	private static $_map = array(); //注册树

	private function __construct(){}

	/**
	 * [start 应用初始化]
	 * @return [type] [无]
	 */
	public static function start(){
		header('content-type:text/html; charset=utf-8');
		spl_autoload_register('self::autoload');
		self::run();
	}

	/**
	 * [autoload 自动加载类]
	 * @param  string $class [类名]
	 * @return [type]        [无]
	 */
	private static function autoload($class = 'Index'){
		$class_path = $class.'.php';
		if (is_file($class_path)) {
			require_once $class_path;
		}
	}

	/**
	 * [run 运行应用]
	 * @return [type] [无]
	 */
	private static function run(){
		$_SERVER['PATH_INFO'] = !empty($_SERVER['PATH_INFO']) ?: 'Index/index';
		$path_info 	= explode('/', $_SERVER['PATH_INFO']);
		$action     = empty($path_info[2]) ? 'index' : $path_info[2];
		$controller = '\\app\\controller\\'.$path_info[1];
		$controller::get_instance()->$action();
	}
}