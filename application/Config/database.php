<?php

/**
 * 数据库配置文件
 */

return [
    // 给连接添加一个名称，方便使用
    "mydb" => [
        'server' => '127.0.0.1',
        'username' => 'root',
        'password' => 'root',
        'database_name' => 'mysql',
        'charset' => 'utf8',
        'database_type' => 'mysql'
    ],
    "dorea" => [
        "server" => "127.0.0.1",
        "port" => 3451,
        "tls" => true,
        "password" => "DOREA@SERVICE",
        "default_db" => "default"
    ]
];
