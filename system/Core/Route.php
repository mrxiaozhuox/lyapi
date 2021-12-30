<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 路由处理程序 - Route
// -+----------------------------+-

namespace LyApi\Core;

class Route
{

    private static $routes = [];
    private static $regexs = [];
    private static $resources = [];
    private static $lastset = '';

    /**
     * 注册路由函数
     */
    public static function rule($path, $controller, $method = "any")
    {
        self::$routes[$path] = array(
            "controller" => $controller,
            "method" => strtoupper($method),
            "filter" => [],
            "after" => null
        );

        self::$lastset = $path;

        return new self();
    }

    /**
     * 处理器表达式注册函数
     */
    public static function regex($sign, $pattern, $info = "i")
    {
        self::$regexs[$sign] = $pattern;
        return new self();
    }

    /**
     * 静态访问路径注册函数
     */
    public static function resource($path, $dir)
    {
        self::$resources[$path] = $dir;
        return new self();
    }

    public static function list()
    {
        return array(
            "routes" => self::$routes,
            "regexs" => self::$regexs,
            "resources" => self::$resources
        );
    }

    // 辅助函数程序

    public static function filter($val = true, $abort = null)
    {
        if (self::$lastset != '') {

            if (is_object($val)) {
                $val = $val();
            }

            self::$routes[self::$lastset]['filter'] = [
                "value" => $val,
                "abort" => $abort
            ];
        }
        return new self();
    }

    public static function afterDefine($function)
    {
        if (self::$lastset != '') {
            if (is_object($function)) {
                self::$routes[self::$lastset]['after'] = $function;
            }
        }
        return new self();
    }
}
