<?php

/**
* 应用引导类
*/
class Init {
	private function __construct(){}

	/**
	 * [start 应用初始化]
	 * @return [type] [无]
	 */
	public static function start(){
		//设置自动加载方法
		spl_autoload_register('self::autoload');

		//设置错误级别
		//error_reporting('E_ERROR');

		//设置时区
		date_default_timezone_set('PRC');

		// 加载应用配置
		\lib\Config::load(APP_PATH.'/config/');

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
		if (\lib\Config::get('path_info') && empty($_GET['c'])) {
			$path_info = empty($_SERVER['REDIRECT_PATH_INFO']) ? 'Index/index' : $_SERVER['REDIRECT_PATH_INFO'];
			$request = explode('/', $path_info);
			$item = count($request);
			for ($i=2; $i < $item; $i++) { 
				$_GET[$request[$i]] = $request[++$i];
			}

			$_GET['c'] = $request[0];
			$_GET['a'] = $request[1];
			unset($request);
		}

		$controller = $_GET['c'] ?: 'Index';
		$action = $_GET['a'] ?: 'Index';
		
		define('CONTROLLER_NAME', $controller);
		define('ACTION_NAME', $action);

		//运行控制器方法
		$controller = '\\app\\controller\\'.$controller;
		$controller_obj = new $controller();
		$controller_obj->$action();
	}
}