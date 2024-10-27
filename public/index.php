<?php

/**
 * LyApi 2.X 框架入口
 * 所有请求都需要从本文件进入
 */

use LyApi\Core\App;
use LyApi\Support\Config;
use LyApi\Support\Extend;
use LyApi\Support\Log;

// 框架基本常量定义
define('ROOT_PATH', dirname(dirname(__FILE__)));
define("LYAPI_VERSION", "V2.0");

if (is_file(ROOT_PATH . "/vendor/autoload.php")) {
    require ROOT_PATH . "/vendor/autoload.php";
} else {
    exit("plese execute `composer install` first.");
}


if (Config::dotConfig('app.open_debug')) {
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler());
    $whoops->register();
}

/**
 * ☆ 拓展加载器 ☆
 * 如果不需要拓展功能，请注释本行代码。
 */
Extend::__loader();

require ROOT_PATH . '/public/helper.php';

// 常量定义加载
Config::getConfig("constant");

// 框架应用运行程序
$app = new App();

$app->relyInit([]);
$app->run(Config::dotConfig('app.open_debug'));

// 日志刷新缓冲区（将缓冲区数据写入文件）
Log::bflush();
