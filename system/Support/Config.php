<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 配置文件操作类 - Config
// -+----------------------------+-

namespace LyApi\Support;


class Config
{

    /**
     * 读取配置文件
     */
    public static function getConfig($name)
    {
        $path = ROOT_PATH . "/application/Config/" . $name . ".php";

        if (is_file($path)) {
            return require $path;
        } else {
            return null;
        }
    }

    /**
     * 通过 Dot 方式访问配置
     */
    public static function dotConfig($path)
    {
        $pathArr = explode(".", $path);
        $pathStr = "";
        $info = [];

        foreach ($pathArr as $key => $value) {
            $pathStr .= $value;
            $info = self::getConfig($pathStr);
            array_shift($pathArr);

            if ($info != null) break;
            else $pathStr .= '/';
        }


        foreach ($pathArr as $key => $value) {
            if (is_array($info)) {
                if (array_key_exists($value, $info)) {
                    $info = $info[$value];
                } else {
                    break;
                }
            } else {
                break;
            }
        }

        return $info;
    }
}
