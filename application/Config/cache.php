<?php

/**
 * 缓存系统配置文件
 */

return [
    'cache_system' => "file",
    "file" => [
        "save_path" => ROOT_PATH . '/runtime/cache/',
        "default_group" => "default",
    ],
    "redis" => [
        "host" => '127.0.0.1',
        "port" => 6379,
        "database" => 0
    ]
];
