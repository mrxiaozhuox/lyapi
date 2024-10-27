<?php


namespace Common;

use const Application\Config\API_STRUCTURE_INFO;

class Api
{

    public static $_struct = API_STRUCTURE_INFO;


    public const CLEAN_ALL_STRUCT = 233;

    public static function simple($data, $code = 200, $message = "")
    {

        $struct = self::$_struct;

        $res = [
            $struct['struct_symbol'] => [
                $struct['http_code'] => $code,
                $struct['info_item'] => $data,
                $struct['error_msg'] => $message
            ]
        ];

        return $res;
    }

    public static function error($code, $message = "Error")
    {

        $struct = self::$_struct;

        $res = [
            $struct['struct_symbol'] => [
                $struct['http_code'] => $code,
                $struct['error_msg'] => $message
            ]
        ];

        return $res;
    }

    public static function custom($values, $httpCode = 200, $remove = self::CLEAN_ALL_STRUCT)
    {
        $struct = self::$_struct;

        if ($remove == self::CLEAN_ALL_STRUCT) {
            $remove = [
                $struct['http_code'],
                $struct['info_item'],
                $struct['error_msg']
            ];
        }

        $res = [
            $struct['struct_symbol'] => $values,
            $struct['deltem_symbol'] => $remove,
            "_opt" => [
                "httpCode" => $httpCode
            ],
        ];

        return $res;
    }
}
