<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 拓展包操作类 - Extend、
//
// 本对象下以 __ 或 _ 开头的函数请勿随意在程序中调用。
// -+----------------------------+-

namespace LyApi\Support;

use Error;
use Exception;
use ExtFunc;

class Extend
{

    private static $_exts    = [];
    private static $_extinfo = [
        "_route" => [],
        "_all" => []
    ];

    public static function list($need = ["NAME", "VERSION", "TYPE", "DESCRIBLE", "AUTHOR", "WEBSITE"])
    {
        if ($need == [] || $need == ["NAME"] || $need == null) {
            return Extend::$_extinfo['all'];
        } else {
            $result = [];
            foreach (Extend::$_exts as $key => $value) {

                $infos = [];
                foreach ($need as $info) {
                    $info = "EXTEND_" . strtoupper($info);

                    try {
                        $infos[$info] = eval('return $value::' . $info . ';');
                        // $infos[$info] = $value::$$info;
                    } catch (Error $e) {
                        $infos[$info] = null;
                    }
                }

                $result[] = $infos;
            }
            return $result;
        }
    }

    // 插件加载器
    public static function __loader()
    {
        $root_path = ROOT_PATH . '/extend/';

        $list = array();
        $data = scandir($root_path);
        foreach ($data as $value) {
            if ($value != '.' && $value != '..' && is_dir($root_path . '/' . $value)) {
                $list[] = $value;
            }
        }
        Extend::$_extinfo['all'] = $list;

        foreach ($list as $ext) {
            $path = $root_path . '/' . $ext;
            if (file_exists($path . '/Main.php')) {
                require_once($path . '/Main.php');
                $tar = "Extend\\" . $ext . "\\Main";
                if (class_exists($tar)) {
                    Extend::$_exts[$ext] = new $tar;

                    try {
                        Extend::$_exts[$ext]->event_trigger_register();
                    } catch (Error $e) {
                    }

                    if (Extend::$_exts[$ext]->_reg_route) {
                        Extend::$_extinfo["_route"][] = $ext;
                    }
                }
            }
        }
    }

    public static function _router($forbid = [])
    {
        foreach (Extend::$_extinfo['_route'] as $val) {
            if (!in_array($val, $forbid)) {
                $methods = get_class_methods("Extend\\" . $val . "\\Main");
                if (in_array("event_route_register", $methods)) {

                    // try {
                        Extend::$_exts[$val]->event_route_register();
                    // } catch (Exception $e) {
                        // echo $e;
                        // Log::bwrite("")
                    // }
                }
            }
        }
    }

    public static function _helper()
    {
        foreach (Extend::$_exts as $val) {
            $methods = get_class_methods($val);
            if (in_array("event_helper_register", $methods)) {
                try {
                    $res = $val->event_helper_register();
                } catch (Exception $e) {
                    $res = [];
                }
                ExtFunc::__loader($res, "5e6e68d1ada5d72c0096f8776e76060d");
            }
        }
    }
}
