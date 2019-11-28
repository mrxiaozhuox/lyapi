<?php

namespace APP\program;

use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;

/**
 * 框架内部直接调用的函数，可以根据需求自行修改
 * PS: 需先打开func配置下的 USING_ECORE 选项
 * WARNING: 请勿删除本文件，避免造成不必要的麻烦
 */


class Ecore
{

    public function CreateResult($respnse_structure = [], $respnse_data = [], $respnse_order_data)
    {
        // 这个函数会在每次数据创建时被执行，主要用于最终数据的生成操作

        /**
         * @参数 respnse_structure: 请求的数据结构
         * @参数 respnse_data: 请求的数据内容
         * @参数 respnse_order_data: 请求的备用数据内容
         */

        // ---------- 这边给出官方操作以供参考 ---------- //

        // foreach ($respnse_structure as $key => $val) {
        //     if ($val == '$data') {
        //         $response[$key] = $respnse_data['data'];
        //     } else if ($val == '$code') {
        //         $response[$key] = $respnse_data['code'];
        //     } else if ($val == '$msg') {
        //         $response[$key] = $respnse_data['msg'];
        //     } else {
        //         if (array_key_exists($key, $respnse_data)) {
        //             $response[$key] = $respnse_data[$key];
        //         } elseif (array_key_exists(substr($val, 1), $respnse_order_data)) {
        //             $response[$key] = $respnse_order_data[substr($val, 1)];
        //         } else {
        //             $response[$key] = $val;
        //         }
        //     }
        // }

        // ---------- 这边给出官方操作以供参考 ---------- //


        // 如果继续使用官方生成系统，返回NULL即可
        return null;
    }

    public function TargetFinding($using_namespace = '', $using_function = '')
    {
        // 这个函数会在调用函数所使用前执行，你可以替换使用的函数

        // 这里的 Demo 使用 namespace 和 function 进行判断，你也可以直接解析 URL

        // Demo 实现了静态文件访问功能

        // ---------- 这边给出Demo以供参考 ---------- //


        // Demo首先对URL进行了简单对解析
        $all_path = explode('\\',$using_namespace . '\\' . $using_function);
        $first_path = $all_path[2];

        // 判断当前访问对是不是静态资源页面
        if($using_function == 'Resource' || $first_path == 'Resource'){

            // 返回更新后使用的对象与函数
            return [
                'namespace' => 'APP\api\Root',          // 转到的命名空间（包括对象）
                'function' => 'Index',                  // 转到的函数名
                'rewrite' => function(){                // 重写需要运行的函数

                    // 获取需要访问的文件

                    $uri = $_SERVER['REQUEST_URI'];

                    if (strrpos($uri, "?") != false) {
                        $uri = substr($uri, 0, strrpos($uri, "?"));
                    }

                    $path_list = array_filter(explode('/',$uri));
                    array_shift($path_list);

                    $file_path = implode('/',$path_list);

                    if(is_file(LyApi . '/app/view/static/' . $file_path)){
                        $file = file_get_contents(LyApi . '/app/view/static/' . $file_path);
                        return $file;
                    }else{
                        throw new CustomException('Invalid Request: Resource file not found');
                    }
                }
            ];
        }

        // ---------- 这边给出Demo以供参考 ---------- //

        // 没有被特殊处理就正常运行
        return [
            'namespace' => $using_namespace,
            'function' => $using_function
        ];
    }

    public function FinalExamine($respnse_structure = [], $respnse_data = [])
    {
        // 这个函数会在数据正常输出前把结构和内容交给你做最后一次检查，你可以在这里进行处理。

        // $respnse_structure = [];

        return [
            'structure' => $respnse_structure,
            'data' => $respnse_data
        ];
    }

    public function InitPlugin($plugin_name = '', $plugin_version = '')
    {
        // 这个函数会在所有插件被初始化时调用，你可以在这里进行前置操作

        // 返回的数据将存入对象的 Tmp_Data 数据下
        return [];
    }
}
