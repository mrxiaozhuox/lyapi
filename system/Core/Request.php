<?php

// -+----------------------------+-
// Reauest handler - Request
// -+----------------------------+-

namespace LyApi\Core;

class Request
{
    public $options = [];
    public $variable = [];

    /*
     * this object will be generate when program got a new request
     */
    public function __construct($options)
    {
        if (is_array($options)) {

            $this->options = $options;

            if (array_key_exists("VARIABLE", $this->options)) {
                $this->variable = $options['VARIABLE'];
            }
        }
    }

    /*
     * data by query
     */
    public function args($name)
    {
        if (array_key_exists($name, $_GET)) {
            return $_GET[$name];
        }
    }

    /*
     * data by form
     */
    public function form(string $name)
    {
        if (array_key_exists($name, $_POST)) {
            return $_POST[$name];
        }
    }

    public function file(string $name)
    {
        if(array_key_exists($name, $_FILES)) {
          return $_FILES[$name];
        }
    }

    public function __get(string $name)
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
