<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开121`发版本
// 结果处理程序 - Response
// -+----------------------------+-

namespace LyApi\Core;

use LyApi\Support\Config;
use LyApi\Support\Event;
use LyApi\Support\Log;

class Response
{
    public static function httpCodeSet($http_code)
    {
        $http = array(
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );

        if (array_key_exists($http_code, $http)) {
            header($http[$http_code]);
        } else {
            header($http[500]);
        }
    }

    public static function abort($http_code, $exception = null)
    {
        self::httpCodeSet($http_code);

        $setting = Config::getConfig("app");

        // 查找错误处理程序路径
        if (array_key_exists("error_controller", $setting)) {
            $controller = $setting["error_controller"];
        } else {
            $controller = "Exception.*";
        }

        $cont_arr = explode(".", $controller);
        $func = $cont_arr[sizeof($cont_arr) - 1];
        array_pop($cont_arr);

        if ($func == "*") {
            $controller = join(".", $cont_arr) . "._" . $http_code;
        }

        $display = [
            "controller" => $controller,
            "default" => "_default",
            "abort" => true,
            "parms" => ["HTTP_CODE" => $http_code, "EXCEPTION" => $exception]
        ];

        $dislist = Event::trigger("Abort_Trigger", $display);
        if ($dislist != [] && $dislist != "") {
            foreach ($dislist as $key => $value) {
                $display = $value;
            }
        }

        $result = App::dispense($display);

        Log::bflush();
        exit($result);
    }

    public static function redirect($url)
    {
        Log::bflush();
        header("Location: $url");
        exit();
    }

    public static function downloadFile($file_path, $save_name = null)
    {
        $path = '';
        if (is_file($file_path)) {
            $path = $file_path;
        }
        if (is_file(ROOT_PATH . $file_path)) {
            $path = $file_path;
        }

        if ($path != "") {

            $info = pathinfo($path);
            if ($save_name == null) {
                $save_name = $info['basename'];
            }

            Header("Content-type: application/octet-stream");
            Header("Accept-Ranges: bytes");
            Header("Accept-Length: " . filesize($path));
            Header("Content-Disposition: attachment; filename=" . $save_name);

            $file = fopen($path, "rb");
            echo fread($file, filesize($path));
            fclose($file);

            exit();
        } else {
            return false;
        }
    }

    // 允许跨域请求
    public static function corsAccessor($domain = '*', $type = '*')
    {
        if (is_array($domain)) {

            $origin = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

            if (in_array($origin, $domain)) {
                $domain = $origin;
            } else {
                $domain = "*";
            }
        }

        if (is_array($type)) {
            $tlist = $type;
            $type = '';
            foreach ($tlist as $key => $value) {
                $type .= $value . ',';
            }
            $type = substr($type, 0, strlen($type) - 1);
        }

        header('Access-Control-Allow-Origin:' . $domain);
        header('Access-Control-Allow-Methods:' . $type);
    }
}
