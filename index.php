<?php

/**
 * 应用入口文件
 */

// 项目根目录
define('ROOT', __DIR__);

// 定义应用目录
define('APP_PATH', ROOT.'/app');

// 应用引导文件
require_once './lib/Init.php';

// 运行应用程序
Init::start();