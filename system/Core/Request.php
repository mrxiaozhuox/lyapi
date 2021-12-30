<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 请求处理程序 - Request
// -+----------------------------+-

namespace LyApi\Core;


class Request
{

    public $options = [];
    public $variable = [];

    // 对象构造函数，将在程序生成 Request 对象时用到
    public function __construct($options)
    {
        if (is_array($options)) {

            $this->options = $options;

            if (array_key_exists("VARIABLE", $this->options)) {
                $this->variable = $options['VARIABLE'];
            }
        }
    }

    public function args($name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
    }

    public function form($name)
    {
        if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        }
    }

    public function __get($name)
    {
        if ($name == "uri") {
            return $_SERVER["REQUEST_URI"];
        }
    }

    
    /**
     * LyApi 1.X Request 函数兼容
     */

    public static function Get($key)
    {
        if (isset($_GET[$key])) {
            return $_GET[$key];
        } else {
            return '';
        }
    }

    public static function Post($key)
    {
        if (isset($_POST[$key])) {
            return $_POST[$key];
        } else {
            return '';
        }
    }

    public static function Request($key)
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        } else {
            return '';
        }
    }

    public static function Files($key)
    {
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        } else {
            return '';
        }
    }

    public static function Env($key)
    {
        if (isset($_ENV[$key])) {
            return $_ENV[$key];
        } else {
            return '';
        }
    }

    public static function Globals($key)
    {
        if (isset($GLOBALS[$key])) {
            return $GLOBALS[$key];
        } else {
            return '';
        }
    }

    public static function Cookie($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        } else {
            return '';
        }
    }

    public static function Session($key)
    {
        if (isset($_SESSION[$key])) {
            return $_SESSION[$key];
        } else {
            return '';
        }
    }

    public static function Server($key)
    {
        if (isset($_SERVER[$key])) {
            return $_SERVER[$key];
        } else {
            return '';
        }
    }
}
