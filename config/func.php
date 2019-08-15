<?php

/*
 * 接口函数配置文件
 */

return array(
    'INIT_FUNC' => 'init',                      //初始化函数名
    'AFTER_FUNC' => 'after',                    //结束函数名
    'FUNCITON_SET_DATA' => [                    //自定义函数数据对照名
        'CUSTON_SUCCESS_CODE' => 'code',        //自定义返回code
        'CUSTON_SUCCESS_DATA' => 'data',        //自定义返回data
        'CUSTON_SUCCESS_MESSAGE' => 'msg'       //自定义返回msg
    ]
);