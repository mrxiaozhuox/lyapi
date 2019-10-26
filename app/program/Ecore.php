<?php

namespace APP\program;

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

    public function FinalExamine($respnse_structure = [], $respnse_data = [])
    {
        // 这个函数会在数据正常输出前把结构和内容交给你做最后一次检查，你可以在这里进行处理。

        // $respnse_structure = [];

        return [
            'structure' => $respnse_structure,
            'data' => $respnse_data
        ];
    }

    public function InitPlugin()
    {
        // 这个函数会在所有插件被初始化时调用，你可以在这里进行前置操作

        // 返回的数据将存入对象的 Tmp_Data 数据下
        return [];
    }
}
