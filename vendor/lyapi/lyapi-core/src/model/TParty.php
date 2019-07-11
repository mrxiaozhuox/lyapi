<?php

namespace LyApi\model;

use Medoo\Medoo;

class TParty
{
    public static function Medoo($config=1)
    {
        $config = require LyApi . "/config/model/medoo.php";
        $Medoo_config = $config[$config];
        return new Medoo($config);
    }
}