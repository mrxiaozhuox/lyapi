<?php

/**
 * LyApi Framework 插件开发
 */

namespace Extend\DevPanel\Library;

class Common
{
    public static function rmdir($dir)
    {
        $dh = opendir($dir);

        while ($file = readdir($dh)) {

            if ($file != "." && $file != "..") {

                $fullpath = $dir . "/" . $file;

                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    self::rmdir($fullpath);
                }
            }
        }
    }
}
