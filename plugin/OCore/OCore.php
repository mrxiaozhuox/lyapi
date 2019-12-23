<?php

namespace Plugin\OCore;

use LyApi\core\error\CustomException;
use Plugin\Core\Core;
use Plugin\PConfig\PConfig;

/**
 * Name: Core.Template
 * Author: LyAPI
 * ModifyTime: 2019/07/29
 * Purpose: 插件开发模板
 */

class OCore extends Core{

    private $Plugin_Config;

    //设置插件信息（请严格按照本模板编写代码）
    public function __construct(){
        $this->Plugin_Name = 'OCore';
        $this->Plugin_Version = 'V1.0.1';
        $this->Plugin_Author = 'mrxiaozhuox';
        $this->Plugin_About = '使用本插件接管框架ECore系统可获得更多功能（ 当前支持ECore for V1.6.7 ）';
        $this->Plugin_Examine = '';

        $this->Plugin_Config = new PConfig($this->Plugin_Name);

        // 处理头文件
        $this->Plugin_Config->InitConfig("headers",array(
            "jpg" => "Content-type: image/jpg",
            "png" => "Content-type: image/jpg",
            "jpeg" => "Content-type: image/jpg",
            "js" => "Content-type: text/javascript",
            "css" => "Content-type: text/css",
            "json" => "Content-type: application/json",
            "zip" => "Content-Type: application/zip",
            "rar" => "Content-Type: application/zip",
            "pdf" => "Content-Type: application/pdf"
        ));
    }

    // 接管函数：插件返回数据
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


        // 如果继续使用内部生成系统，返回NULL即可
        return null;
    }

    // 接管函数：替换使用函数
    public function TargetFinding($using_namespace = '', $using_function = '')
    {
        // 这个函数会在调用函数使用前执行，你可以替换使用的函数

        // ---------- 这边给出Demo以供参考 ---------- //

        // PS: 当你不需要本功能，删除下方所有程序，提升接口运行效率

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

                        $suffix = pathinfo($file_path, PATHINFO_EXTENSION);
                        $headers = $this->Plugin_Config->ReadConfig("headers");

                        // 对一些特殊文件进行处理
                        if(array_key_exists($suffix,$headers)){
                            header($headers[$suffix]);
                        }

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

    // 接管函数：处理最终输出
    public function FinalExamine($respnse_structure = [], $respnse_data = [])
    {
        // 这个函数会在数据正常输出前把结构和内容交给你做最后一次检查，你可以在这里进行处理。

        // $respnse_structure = [];

        return [
            'structure' => $respnse_structure,
            'data' => $respnse_data
        ];
    }

    // 接管函数：插件初始化
    public function InitPlugin($plugin_name = '', $plugin_version = '')
    {
        // 这个函数会在所有插件被初始化时调用，你可以在这里进行前置操作
        
        // 返回的数据将存入对象的 Tmp_Data 数据下
        return [];
    }

}