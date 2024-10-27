<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 数据库连接器 - Connector
// -+----------------------------+-

namespace LyApi\Support\Database;

use Exception;
use Extend\Dorea\library\Database;
use LyApi\Support\Config;
use Medoo\Medoo;

class Connector
{
    private static $conntype = "medoo";

    public function __construct($type = "medoo")
    {
        self::$conntype = $type;
    }

    /**
     * @throws ConnException
     */
    public function __get($name)
    {
        $conf = Config::dotConfig("database");
        if (!array_key_exists($name, $conf)) {
            throw new ConnException("[" . strtoupper($name) . "] 数据库连接失败！");
        }

        if (self::$conntype == "medoo") {
            $temp = new Medoo($conf[$name]);
        } elseif (self::$conntype == "dorea") {

            $config = $conf[$name];

            $url = "";

            if (array_key_exists("url", $config)) {
                $url = $config["url"];
            } else {
                if ($config["tls"]) {
                    $url = "https://";
                } else {
                    $url = "http://";
                }

                $url .= $config["server"];
                if ($config["port"] != 80) {
                    $url .= ":" . (string)$config["port"];
                }

                $url .= "/";

            }

            $temp = \ExtFunc::dorea_connect($url, $config["password"], $config["default_db"]);
            if ($temp == null) {
                throw new ConnException("[" . strtoupper($name) . "] 数据库驱动不存在！");
            }
        }

        return $temp;
    }

    public static function connect($name, $type = "medoo")
    {
        $temp = new self(self::$conntype);
        return $temp->$name;
    }

    public static function dorea($name)
    {
        return self::connect($name, "dorea");
    }

}
