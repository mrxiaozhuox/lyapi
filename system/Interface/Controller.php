<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 控制器基类 - Bcontrol
// -+----------------------------+-

namespace LyApi\Interface;

use LyApi\Support\Cache\FileCache;
use LyApi\Support\Event;

class Controller
{
    private static $__cacheInfo = false;

    /**
     * 将页面 HTML 代码进行缓存
     */
    final public static function pageCache($options = [])
    {

        $info = [
            "FileCache" => new FileCache("PageCache"),
            "Refresh" => 24 * 60 * 1000
        ];

        if ($options == "CACHEINFO") {
            return self::$__cacheInfo;
        }

        if (is_numeric($options)) {
            $info['Refresh'] = $options;
        }

        self::$__cacheInfo = $info;
    }

    public function _export($data)
    {
        $b = explode("\\", get_parent_class(get_called_class()))[2];
        $res = Event::trigger("Foundation_Export", $data, $b);
        if ($res != null && $res != []) {
            foreach ($res as $value) {
                return $value;
            }
        } else {
            return $data;
        }
    }
}
