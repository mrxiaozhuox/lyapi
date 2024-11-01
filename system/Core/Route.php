<?php

// -+----------------------------+-
// Routing handler - Route
// -+----------------------------+-

namespace LyApi\Core;

class Route
{
    private static $routes = [];
    private static $regexs = [];
    private static $resources = [];
    private static $lastset = '';

    /**
     * register a new router
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
     * bind a dynamic regex 
     */
    public static function regex($sign, $pattern)
    {
        self::$regexs[$sign] = $pattern;
        return new self();
    }

    /**
     * static resource bind 
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

    /*
     * make a filter for router
     */
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
    
    /*
     * custom router handler
     */
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
