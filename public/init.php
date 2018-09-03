<?php

define('LyApi',dirname(__FILE__) . '/..');

require_once LyApi . '/vendor/autoload.php';


//判读是否打开Whoops

$debug_config = require LyApi . "/config/debug.php";

if($debug_config['OPEN_WHOOPS']){
    $whoops = new \Whoops\Run();
    $whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
    $whoops->register();
}