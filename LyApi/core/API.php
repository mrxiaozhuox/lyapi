<?php

namespace LyApi\core;

class API{

    //获取各种数据
    public static function GET($type,$key){
        $t = '_' . $type;
        if(array_key_exists($t,$GLOBALS)){
            if(array_key_exists($key,$GLOBALS[$t])){
                return $GLOBALS[$t][$key];
            }else{
                return null;
            }
        }else{
            return null;
        }
    }
}