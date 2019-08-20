<?php

// 程序运行出现问题请先运行check.php文件！检测框架是否完整！！！

require 'init.php';

use LyApi\LyApi;
use LyApi\tools\Config;

// ------------- 框架信息 ------------- //

$LyAPI_Version      = "1.4.0";
$LyAPI_Core_Verison = LyApi::$version;

// ------------- 框架信息 ------------- //

$config = Config::getConfig('api', '');

$priority_output = $config["PRIORITY_OUTPUT"];
$http_status_set = $config["HTTP_STATUS_SET"];
$custom_data =     $config["CUSTOM_DATA"];

// ------可以在这里进行一些前置操作------//

// 千万不要输出数据！防止header设置出现错误！

// do some thing ...

//------可以在这里进行一些前置操作------//


// return_http_code 返回的是本次程序运行最终的HTTP状态码
$return_http_code =  LyApi::output($custom_data, $priority_output, $http_status_set);

// ------可以在这里进行一些后置操作------//

// if($return_http_code != 200){
//     return '程序运行出现错误...';
// }

//------可以在这里进行一些后置操作------//
