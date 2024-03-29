<?php

/**
 * LyApi Framework 插件开发
 */

namespace Extend\LyDev\library;

use LyApi\Support\CuteDB;

class Storage
{
    public static $db = null;

    public static function init()
    {
        self::$db = new CuteDB();
        self::$db->open(ROOT_PATH . "/extend/LyDev/data/storage");
    
        if(self::$db->get("extdev-pwd") == null){
            self::$db->set("extdev-pwd","123456");
        }
    }
}
