<?php

include "init.php";

/**
 * 快速检查和修复程序问题
 */

echo '<h1>项目目录结构检查</h1>';


echo '<h2>数据文件夹:';
if(is_dir('../data')){
    echo ' 已存在</h2>';
}else{
    mkdir('../data');
    echo ' 已修复</h2>';
}

echo '<h2>缓存文件夹:';
if(is_dir('../data/cache')){
    echo ' 已存在</h2>';
}else{
    mkdir('../data/cache');
    echo ' 已修复</h2>';
}

echo '<h2>日志文件夹:';
if(is_dir('../data/log')){
    echo ' 已存在</h2>';
}else{
    mkdir('../data/log');
    echo ' 已修复</h2>';
}

echo '<h2>核心代码:';
if(class_exists('\LyApi\LyApi')){
    echo ' 已引入</h2>';
}else{
    echo ' 请修复</h2>';
}
echo '<h4>核心修复方法:重新使用composer安装拓展</h4>';