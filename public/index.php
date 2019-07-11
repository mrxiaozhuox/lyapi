<?php
use LyApi\LyApi;

// 程序运行出现问题请先运行check.php文件！检测框架是否完整！！！

require 'init.php';

// ------------- 框架信息 ------------- //

$LyAPI_Version      = "1.2.6";
$LyAPI_Core_Verison = LyApi::$version;

// ------------- 框架信息 ------------- //

$config = require LyApi . "/config/api.php";

$priority_output = $config["PRIORITY_OUTPUT"];
$http_status_set = $config["HTTP_STATUS_SET"];
$custom_data =     $config["CUSTOM_DATA"];

// ------可以在这里进行一些前置操作------//

// print("Hello World");

//------可以在这里进行一些前置操作------//

\LyApi\LyApi::output($custom_data,$priority_output,$http_status_set);