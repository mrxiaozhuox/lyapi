<?php

// -+----------------------------+-
// View Management - View
// -+----------------------------+-

namespace LyApi\Core;

use LyApi\Foundation\Bcontrol;
use LyApi\Support\Cache\FileCache;

use const Application\Config\API_STRUCTURE_INFO;
use const Application\Config\HTTP_INTERNAL_SERVER_ERROR;

class View
{
    private static $viewLoader = null;
    private static $viewTwig = null;
    private static $isLoading = false;
    public static $filePath = null;

    private static function __loader($path = ROOT_PATH . '/resource/view')
    {
        self::$viewLoader = new \Twig\Loader\FilesystemLoader($path);
        self::$filePath =  $path;

        self::$viewTwig = new \Twig\Environment(self::$viewLoader, [
            'cache' => ROOT_PATH . '/runtime/template',
            'auto_reload' => true
        ]);

        self::$isLoading = true;
    }

    public static function changePath($path)
    {
        self::__loader($path);
    }

    public static function render($path, $data, $type = "html")
    {
        $path = str_replace(".", '/', $path);

        $file = ROOT_PATH . '/resource/view/' . $path . '.' . $type;

        $pch = new FileCache("PageCache");
        if ($pch->has($file)) {
            return $pch->get($file);
        }

        if (!self::$isLoading) {
            self::__loader();
        }

        if (is_file($file)) {
            if (is_array($data)) {
                $template = self::$viewTwig->load($path . '.' . $type);
                $res = $template->render($data);
            } else {
                $res = file_get_contents($file);
            }

            $cinf = Bcontrol::pageCache("CACHEINFO");
            if ($cinf) {
                $cache = $cinf['FileCache'];
                $cache->set($file, $res, $cinf["Refresh"]);
            }

            return $res;
        } else {
            Response::abort(404);
        }
    }

    public static function label($name, $data, $options = [])
    {

        $addition = $options;
        if (is_array($options)) {
            $addition = "";
            foreach ($options as $key => $value) {
                $addition .= "$key = '$value' ";
            }
        }

        return "<$name $addition>$data</$name>";
    }

    public static function json($data)
    {
        header("content-type:application/json");
        return json_encode($data, JSON_PRETTY_PRINT);
    }

    public static function api($data, $structure = API_STRUCTURE_INFO, $options = [])
    {

        $result = $structure['structure'];

        if (is_string($data)) {
            $result[$structure['info_item']] = $data;
        } elseif (is_array($data)) {

            // ~ add new field
            if (array_key_exists($structure['struct_symbol'], $data)) {
                $val = $data[$structure['struct_symbol']];
                foreach ($val as $key => $value) {
                    $result[$key] = $value;
                }
                unset($data[$structure['struct_symbol']]);
            }

            if ($result[$structure['info_item']] == []) {
                $result[$structure['info_item']] = $data;
            }

            // ^ delete field
            if (array_key_exists($structure['deltem_symbol'], $data)) {
                $val = $data[$structure['deltem_symbol']];
                if (is_array($val)) {
                    foreach ($val as $key => $value) {
                        unset($result[$value]);
                    }
                } elseif ($val == "*") {
                    $result = [];
                } elseif (is_string($val)) {
                    unset($result[$val]);
                }

                unset($result[$structure['info_item']][$structure['deltem_symbol']]);
            }
        }

        if (array_key_exists("httpCode", $options)) {
            Response::httpCodeSet($options['httpCode']);
        } else {
            if (array_key_exists($structure['http_code'], $result)) {
                Response::httpCodeSet($result[$structure['http_code']]);
            } else {
                Response::httpCodeSet(HTTP_INTERNAL_SERVER_ERROR);
            }
        }

        header("content-type:application/json");

        if (array_key_exists("toArray", $options) && $options['toArray']) {
            $res = $result;
        } else {
            $res = json_encode($result, JSON_PRETTY_PRINT);

            // handle broken char
            $res = preg_replace_callback(
                "#\\\u([0-9a-f]{4})#i",
                function ($r) {
                    return iconv('UCS-2BE', 'UTF-8', pack('H4', $r[1]));
                },
                $res
            );
        }


        return $res;
    }
}
