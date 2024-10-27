<?php

use LyApi\Core\Response;
use LyApi\Core\View;
use LyApi\Support\Cache\Cache;
use LyApi\Support\Config;
use LyApi\Support\Extend;
use LyApi\Support\Storage\Session;
use Particle\Validator\Validator;

/**
 * LyApi 助手函数程序
 * 封装一些助手函数，可快速调用
 */


// 抛出HTTP错误信息
function abort($http_code, $exception = null)
{
    return Response::abort($http_code, $exception);
}

function redirect($url)
{
    return Response::redirect($url);
}

// 快速获取 dotConfig
function config($path)
{
    return Config::dotConfig($path);
}

// 模板渲染输出
function view($path, $data = [], $root = null)
{
    if ($root != null && is_dir($root)) {
        View::changePath($root);
    }

    View::render($path, $data);
}

// 快捷长度获取
function length($var)
{
    if (is_string($var)) {
        return strlen($var);
    } else {
        return sizeof($var);
    }
}

// 缓存处理函数
function cache($key, $val = null, $expire = 0)
{
    if ($val == null) {
        return Cache::get($key);
    } else {
        return Cache::set($key, $val, $expire);
    }
}

function session($name = null, $value = null)
{
    if ($name == null && $value == null) {
        return new Session();
    } elseif ($name != null && $value == null) {
        return Session::get($name);
    } elseif ($name != null && $value != null) {
        return Session::set($name, $value);
    }
    return null;
}

function validator()
{
    return new Validator();
}

class ExtFunc
{
    private static $extfunc = [];

    public static function __callStatic($name, $arguments)
    {
        if (isset(ExtFunc::$extfunc[$name])) {
            return ExtFunc::$extfunc[$name](...$arguments);
        } else {
            return null;
        }
    }

    public static function __loader($exts, $cheker)
    {
        if ($cheker == "5e6e68d1ada5d72c0096f8776e76060d") {
            foreach ($exts as $key => $value) {
                if (!isset(ExtFunc::$extfunc[$key])) {
                    ExtFunc::$extfunc[$key] = $value;
                }
            }
        }
    }
}

Extend::_helper();
