<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 核心代码程序 - App
// -+----------------------------+-

namespace LyApi\Core;

use Exception;
use LyApi\Support\Cache\Cache;
use LyApi\Support\Config;
use LyApi\Support\Log;
use LyApi\Support\Storage\Session;

class App
{

    public static $startTimer = 0;

    /**
     * 框架运行主程序
     */
    public function run($debug = false)
    {
        if ($debug) {
            $this->processor();
        } else {
            try {
                $this->processor();
            } catch (Exception $e) {
                Response::abort(HTTP_INTERNAL_SERVER_ERROR);
            }
        }
    }

    public function relyInit()
    {
        Session::__loader();
        Cache::__loader();
    }

    private function processor()
    {
        $events = Config::getConfig("event");
        $routes = Config::getConfig("route");

        $regexs =    $routes["regexs"];
        $resources = $routes['resources'];
        $routes =    $routes["routes"];

        $accessUri = $_SERVER['REQUEST_URI'];

        if (strrpos($accessUri, "?") != false) {
            $accessUri = substr($accessUri, 0, strrpos($accessUri, "?"));
        }

        $accessArray = explode("/", $accessUri);
        $accessArray = array_filter($accessArray);

        // 静态文件路径检测
        foreach ($resources as $key => $value) {

            if (substr($key, -1) != '/') $key .= '/';
            $pattern = "/^" . preg_quote($key, "/") . "(.*)$/i";

            if (preg_match($pattern, $accessUri, $tester)) {
                if (is_file($value . '/' . $tester[1])) {

                    $suffix = pathinfo($tester[1], PATHINFO_EXTENSION);

                    $headers = array(
                        "ico" => "Content-type: image/jpg",
                        "jpg" => "Content-type: image/jpg",
                        "png" => "Content-type: image/jpg",
                        "jpeg" => "Content-type: image/jpg",
                        "js" => "Content-type: text/javascript",
                        "css" => "Content-type: text/css",
                        "json" => "Content-type: application/json",
                        "zip" => "Content-Type: application/zip",
                        "rar" => "Content-Type: application/zip",
                        "pdf" => "Content-Type: application/pdf"
                    );

                    if (array_key_exists($suffix, $headers)) {
                        header($headers[$suffix]);
                    }

                    echo file_get_contents($value . '/' . $tester[1]);

                    Log::bflush();
                    exit();
                }
            }
        }

        $controller = null;

        foreach ($routes as $key => $value) {

            $pattern = "/^" . preg_quote($key, "/") . "(\/?)$/i";


            foreach ($regexs as $sign => $patt) {
                $pattern = str_replace("\{" . $sign . "\}", "(" . $patt . ")", $pattern);
            }

            // var_dump($pattern);

            if (!($value['method'] == 'ANY' || $_SERVER['REQUEST_METHOD'] == $value['method'])) {
                continue;
            }

            if (preg_match($pattern, $accessUri, $tester)) {

                if ($value['filter'] != []) {
                    if (!$value['filter']['value']) {
                        if ($value['filter']['abort'] == null) {
                            break;
                        } else if (is_integer($value['filter']['abort'])) {
                            abort($value['filter']['abort']);
                        } else {
                            redirect($value['filter']['abort']);
                        }
                    }
                }

                $controller = $value;

                if ($tester == null) $tester = [""];
                array_shift($tester);

                if (is_string($controller['controller'])) {
                    foreach ($tester as $key => $value) {
                        $key += 1;
                        $cont = $controller['controller'];
                        $controller['controller'] = str_replace("{" . $key . "}", $value, $cont);
                    }
                }

                if ($controller['after'] != null) {
                    $func = $controller['after'];
                    $controller['controller'] = $func($controller["controller"]);
                }

                break;
            }
        }

        if ($controller != null) {
            $result = self::dispense([
                "controller" => $controller['controller'],
                "parms" => [
                    'VARIABLE' => $tester
                ]
            ]);

            if (is_string($result) || is_numeric($result)) {
                echo $result; // 输出程序结果
            } else if ($result != null) {
                echo View::api($result);
            }
        } else {
            Response::abort(404);
        }
    }

    public static function dispense($options = [])
    {

        $controller = null;
        $default    = null;
        $abort      = false;
        $parms      = [
            "REQUEST_METHOD" => $_SERVER["REQUEST_METHOD"]
        ];

        if (array_key_exists("controller", $options)) {
            $controller = $options["controller"];
        }

        if (array_key_exists("default", $options)) $default = $options['default'];
        if (array_key_exists("abort", $options)) $abort = $options['abort'];
        if (array_key_exists("parms", $options)) $parms = array_merge($options['parms'], $parms);

        if (is_object($controller)) {
            return $controller(new Request($parms), new Response);
        }

        $target_path = explode(".", $controller);
        $function = $target_path[sizeof($target_path) - 1];
        array_pop($target_path);

        $target_path = "Application\\Controller\\" . join("\\", $target_path);

        if (class_exists($target_path)) {
            $object = new $target_path;

            $base = get_parent_class($object);
            $base = new $base;

            $methods = get_class_methods($target_path);
            if (in_array($function, $methods)) {
                $function = $function;
            } else if (in_array($default, $methods) || $default == null) {
                if ($default != null) {
                    $function = $default;
                } else {
                    if (!$abort) abort(404);
                }
            } else {
                $function = null;
            }

            if ($function != null) {
                $ret = $object->$function(new Request($parms), new Response);
                return $object->_export($ret, $parms);
            }
        }

        if (!$abort) Response::abort(404);
    }
}
