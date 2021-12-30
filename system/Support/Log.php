<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 日志操作类 - Log
// -+----------------------------+-

namespace LyApi\Support;


class Log
{

    private static $log_path = '';
    private static $buffers = [];

    private static function __loader()
    {

        if (self::$log_path == '') {
            if (strtoupper(Config::dotConfig("log.log_system")) == "FILE") {
                self::$log_path = Config::dotConfig("log.file.save_path");
            }
        }
    }

    // 写入到缓冲区
    public static function bwrite($data, $level = "info")
    {

        self::__loader();

        $maxn = Config::dotConfig("log.file.buffer_max");
        if (sizeof(self::$buffers) >= $maxn) self::bflush(); // 自动冲刷缓冲区

        array_push(self::$buffers, [
            "level" => $level,
            "data" => $data,
            "time" => date("Y-m-d h:i:s")
        ]);
    }

    // 清空缓冲区
    public static function bclean()
    {
        self::__loader();
        self::$buffers = [];
    }

    // 刷新缓冲区
    public static function bflush()
    {
        self::__loader();

        $isolation = Config::dotConfig("log.file.save_isolation");

        if ($isolation) {
            $data = [];
        } else {
            $data = "";
        }

        foreach (self::$buffers as $key => $value) {
            $add = "[" . $value["level"] . "] " . $value["time"] . ": " . $value['data'] . PHP_EOL;

            if ($isolation) {
                if (!array_key_exists($value['level'], $data)) $data[$value['level']] = "";
                $data[$value['level']] .= $add;
            } else {
                $data .= $add;
            }
        }

        $path = self::$log_path;

        if ($isolation) {

            if (self::$buffers != []) {
                if (!is_dir($path . date("Ymd") . '/')) mkdir($path . date("Ymd") . '');
                foreach ($data as $key => $value) {
                    $name = date('Ymd') . "/" . $key . '.log';
                    if (is_file($path . $name)) {
                        file_put_contents($path . $name, $value, FILE_APPEND);
                    } else {
                        file_put_contents($path . $name, $value);
                    }
                }
            }
        } else {
            $name = date("Ymd") . '.log';
            if (is_file($path . $name)) {
                file_put_contents($path . $name, $data, FILE_APPEND);
            } else {
                file_put_contents($path . $name, $data);
            }
        }

        self::bclean();
    }
}
