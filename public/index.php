<?php

// 程序运行出现问题请先运行check.php文件！检测框架是否完整！！！

require 'init.php';

$config = require LyApi . "/config/api.php";

$priority_output = $config["PRIORITY_OUTPUT"];

\LyApi\LyApi::output($priority_output);