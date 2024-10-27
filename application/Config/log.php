<?php

/**
 * 日志系统配置信息
 */

namespace Application\Config;

return array(
    "log_system" => "file",
    "file" => [

        // 文件日志保存根目录
        "save_path" => ROOT_PATH . '/runtime/log/',

        // 缓冲区最大值（超过则自动冲刷）
        "buffer_max" => 50,

        // 是否开启文件隔离（不同类型日志存放不同文件）
        "save_isolation" => true
    ]
);
