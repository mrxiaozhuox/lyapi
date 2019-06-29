<?php

/*
 * 接口基础配置文件
 */

return array(
    'DEFAULT_RESPONSE' => array(            //响应数据结构
        'code' => '$code',
        'data' => '$data',
        'msg' => '$msg'
    ),
    'DEFAULT_SERVCIE' => 'service',         //服务参数名
    'PRIORITY_OUTPUT' => '',                //优先输出内容（可为HTML代码）
    'HTTP_STATUS_SET' => true               //接口是否使用Header返回状态码
);