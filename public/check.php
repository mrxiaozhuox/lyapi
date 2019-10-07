<?php

use LyApi\LyApi;

include "init.php";

/**
 * 快速检查和修复程序问题
 */


if(is_dir('../data')){
    $data_dir = '已存在';
}else{
    mkdir('../data');
    $data_dir = '已修复';
}

if(is_dir('../data/cache')){
    $cache_dir = '已存在';
}else{
    mkdir('../data/cache');
    $cache_dir = '已修复';
}

if(is_dir('../data/log')){
    $logger_dir = '已存在';
}else{
    mkdir('../data/log');
    $logger_dir = '已修复';
}

if(class_exists('\LyApi\LyApi')){
    $core_code = '已引入';
}else{
    $core_code = '待修复';
}


$html = "
<html lang='en'>

<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <meta http-equiv='X-UA-Compatible' content='ie=edge'>
    <title>LyApi - 框架检查</title>
</head>

<body>
    <style>
        .container {
            width: 60%;
            margin: 10% auto 0;
            background-color: #f0f0f0;
            padding: 2% 5%;
            border-radius: 10px
        }
    </style>
    <center class='container'>
        <h1>LyApi " . LyApi::$version . " - 框架检查<h1>
        <h2>数据文件夹: $data_dir</h2>
        <h2>缓存文件夹: $cache_dir</h2>
        <h2>日志文件夹: $logger_dir</h2>
        <h2>核心代码: $core_code</h2>
    </center>
</body>

</html>
";

echo $html;