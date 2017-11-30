<?php

namespace lib;

/**
* 配置管理类
*/
class Config {
	private static $config = array();

	private function __construct(){}

	/**
	 * [load 加载所有配置文件信息]
	 * @param  string $path [配置文件目录]
	 * @return [type]        [无]
	 */
	public  static function load($path = ''){
		$config_file = scandir($path);
		foreach ($config_file as $file) {
			if ($file != '.' && $file != '..') {
				$config = require_once $path.$file;
				self::$config = array_merge(self::$config, $config);
			}
		}
	}

	/**
	 * [get 获取配置信息]
	 * @param  string $key [配置项]
	 * @return [type]      [配置项的值]
	 */
	public static function get($key = '') {
		return $key ? self::$config[$key] : self::$config;
	}
}