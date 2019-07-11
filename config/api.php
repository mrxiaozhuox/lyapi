<?php

/*
 * 接口基础配置文件
 */

return array(
    'DEFAULT_RESPONSE' => array(            //响应数据结构(Key可修改，Value请勿随便修改)
        'code' => '$code',
        'data' => '$data',
        'msg' => '$msg'
        // 'using' => '$usi'                // 目前支持自定义数据（数据放在CUSTOM_DATA处）
    ),
    'DEFAULT_SERVCIE' => 'service',         //服务参数名
    'PRIORITY_OUTPUT' => '',                //优先输出内容（可为HTML代码）
    'HTTP_STATUS_SET' => true,              //接口是否使用Header返回状态码
    'CUSTOM_DATA' =>     array(             //其他数据（用于抓RESONSE）
        //usi => "Hello World"              //自定义数据内容
    )               
);