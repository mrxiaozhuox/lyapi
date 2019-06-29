<?php

// 程序运行出现问题请先运行check.php文件！检测框架是否完整！！！

require 'init.php';

$config = require LyApi . "/config/api.php";

$priority_output = $config["PRIORITY_OUTPUT"];
$http_status_set = $config["HTTP_STATUS_SET"];

// ------可以在这里进行一些前置操作------//

// print("Hello World");

//------可以在这里进行一些前置操作------//

\LyApi\LyApi::output($priority_output,$http_status_set);