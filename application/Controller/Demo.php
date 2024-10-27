<?php

/**
 * LyApi Framework 接口演示
 * 
 * 关于：2.X 版本在优化了用户体验同时也在改进接口开发。
 * 下方是我为各位开发者整理的接口Demo（都是接口程序）
 * 
 * ⚠ 本文件的访问路径为：http://domain.com/Demo/[函数名]
 */

namespace Application\Controller;

use Application\Foundation\ApiCon;
use Common\Api;
use ErrorException;
use Extend\Dorea\library\Database;
use Extend\Dorea\library\Tuple;

class Demo extends ApiCon
{
    // 要开发一个接口，最重要的就是继承 ApiCon 这个对象
    // 当然你可以再 ViewCon 下调用 view::api 来实现接口生成
    // 但 ApiCon 下有很多特有的函数，方便接口相关的操作


    // 程序员写下的第一个代码便是这 Hello World 了，嘻嘻！
    public function hello(): string
    {
        return "LyApi 2.X 全新启航！";
    }

    // 程序出错了，来个 404 Not Found？
    public function error()
    {
        try {
            file_get_contents("/这是一个不存在的文件.txt");
        } catch (ErrorException) {
            // Simple 函数是 Api 提供的结构生成函数
            // 正常来说，访问数据需要复杂的数组组合，而本函数仅用几个简单参数就可以完成
            return Api::simple(null, 404, "File Not Found");
        }
        return "不可能到这里哦！";
    }

    // 一个接口需要遵循其他规则？Custom 函数可以完全自定义结构哦！
    public function custom(): array
    {
        // Custom 需要三个参数：
        // 参数1：接口结构（默认不包含 code data msg）
        // 参数2：HTTP状态码，由于缺少了 code 则需要手动设置状态码
        // 参数3：需要删除的键，默认删除 code data msg，可以是数组和字符串
        return Api::custom([
            "username" => "mrxiaozhuox",
            "password" => "hello_lyapi"
        ], 200);
    }

    // 请允许我夹带点私货：https://dorea.mrxzx.info/
    public function dorea() {
        $db = new Database("http://127.0.0.1:3451", "DOREA@SERVICE");
        $db->set("foo", ["FOO","BAR","DOREA",Tuple::build("PI",3.14)]);
        return $db->get("foo", false);
    }

}
