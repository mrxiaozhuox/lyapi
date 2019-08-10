<?php

/*
 * 日志系统配置文件
 */

return array(
    'TEMPLATE_ALIAS' => [                       //模板变量别名
        "time" => "{time}",
        "type" => "{type}",
        "message" => "{msg}"
    ],
    'TEMPLATE_DEFAULT' => [                       //模板变量默认值
        "time" => date('Y-m-d H:i:s'),
        "type" => "default"
    ],
    'LOGGER_TEMPLATE' => "{time}[{type}] :  {msg}",    //日志写入模板
);