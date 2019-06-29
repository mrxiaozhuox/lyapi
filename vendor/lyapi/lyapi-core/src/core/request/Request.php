<?php

namespace LyApi\core\request;

class Request{

    public static function Get($key){
        if(isset($_GET[$key])){
            return $_GET[$key];
        }else{
            return '';
        }
    }

    public static function Post($key){
        if(isset($_POST[$key])){
            return $_POST[$key];
        }else{
            return '';
        }
    }

    public static function Request($key){
        if(isset($_REQUEST[$key])){
            return $_REQUEST[$key];
        }else{
            return '';
        }
    }

    public static function Files($key){
        if(isset($_FILES[$key])){
            return $_FILES[$key];
        }else{
            return '';
        }
    }

    public static function Env($key){
        if(isset($_ENV[$key])){
            return $_ENV[$key];
        }else{
            return '';
        }
    }

    public static function Globals($key){
        if(isset($GLOBALS[$key])){
            return $GLOBALS[$key];
        }else{
            return '';
        }
    }

    public static function Cookie($key){
        if(isset($_COOKIE[$key])){
            return $_COOKIE[$key];
        }else{
            return '';
        }
    }

    public static function Session($key){
        if(isset($_SESSION[$key])){
            return $_SESSION[$key];
        }else{
            return '';
        }
    }

    public static function Server($key){
        if(isset($_SERVER[$key])){
            return $_SERVER[$key];
        }else{
            return '';
        }
    }
}