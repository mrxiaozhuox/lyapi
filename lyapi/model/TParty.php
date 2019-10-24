<?php

namespace LyApi\model;

use Medoo\Medoo;
use NotORM;
use PDO;

//快速获取数据库连接

class TParty
{

    // 获取Medoo连接
    public static function Medoo($ConfigSelect = 0)
    {
        $config = require LyApi . "/config/model/medoo.php";
        $Medoo_config = $config[$ConfigSelect];
        return new Medoo($Medoo_config);
    }

    // 获取NotORM连接
    public static function NotORM($AutoLoad = null, $PdoObject = null)
    {
        if ($AutoLoad != null) {
            $config = require LyApi . "/config/model/pdo.php";
            $pdo = new PDO($config[$AutoLoad]);
            return new NotORM($pdo);
        } else if ($PdoObject != null) {
            return new NotORM($PdoObject);
        } else {
            return null;
        }
    }

    // 获取PDO连接
    public static function PDO($AutoLoad = null, $DSN = null)
    {
        if ($AutoLoad != null) {
            $config = require LyApi . "/config/model/pdo.php";
            return new PDO($config[$AutoLoad]);
        } else if ($PdoObject != null) {
            return new PDO($DSN);
        } else {
            return null;
        }
    }
}
