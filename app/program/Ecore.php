<?php

namespace APP\program;

/**
 * 框架内部直接调用的函数，可以根据需求自行修改
 * PS：需先打开func配置下的 USING_ECORE 选项
 */


class Ecore
{
    public function FinalExamine($respnse_structure = [],$respnse_data = [])
    {
        // 这个函数会在数据正常输出前把结构和内容交给你做最后一次检查，你可以在这里进行处理。
        $respnse_structure = [];
        return [
            'structure' => $respnse_structure,
            'data' => $respnse_data
        ];
    }
}
