<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 连接错误 - ConnException
// -+----------------------------+-

namespace LyApi\Support\Database;

use Exception;
use Throwable;

class ConnException extends Exception
{
    public function __construct($message = "", $code = 0, Throwable $previous = null)
    {

        if ($message == "") {
            $message = "database connect field.";
        }

        parent::__construct($message, $code, $previous);
    }
}
