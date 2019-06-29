<?php

namespace LyApi\model;

use Medoo\Medoo;

class TParty
{
    public static function Medoo()
    {
        $config = require LyApi . "/config/model/medoo.php";
        return new Medoo($config);
    }
}