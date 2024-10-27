<?php

// -+----------------------------+-
// Session Management - Session
// -+----------------------------+-

namespace LyApi\Support\Storage;

class Session
{
    public static function __loader()
    {
        session_start();
    }


    public static function get($name, $none = null)
    {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        } else {
            return $none;
        }
    }

    public static function set($name, $value)
    {
        $_SESSION[$name] = $value;
    }

    public static function delete($name)
    {
        unset($_SESSION[$name]);
    }

    public static function clear()
    {
        session_unset();
    }
}
