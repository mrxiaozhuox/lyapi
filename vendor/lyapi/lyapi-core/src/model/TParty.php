<?php

namespace LyApi\model;

use Medoo\Medoo;

class TParty
{
    public static function Medoo($config_num=0)
    {
        $config = require LyApi . "/config/model/medoo.php";
        $Medoo_config = $config[$config_num];
        return new Medoo($Medoo_config);
    }
}