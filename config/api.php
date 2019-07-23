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
    'DEFAULT_SERVICE' => 'service',         //服务参数名
    'PRIORITY_OUTPUT' => '',                //优先输出内容（可为HTML代码）
    'HTTP_STATUS_SET' => true,              //接口是否使用Header返回状态码
    'CUSTOM_DATA' =>     array(             //其他数据（用于抓RESONSE）
        //usi => "Hello World"              //自定义数据内容
    ),
    'ERROR_MESSAGE' => array(               //自定义错误信息
        'service_not_find' => array(            //service找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Service does not exist'
        ),
        'class_not_extend' => array(            //class未继承时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Class does not extend API'
        ),
        'class_not_find' => array(              //class找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Class does not exist'
        ),
        'function_not_find' => array(           //function找不到时的返回数据
            'code' => '400',
            'data' => array(),
            'msg' => 'Invalid Request: Function does not exist'
        )
    )
);