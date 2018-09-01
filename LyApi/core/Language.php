<?php

namespace LyApi\core;

class Language{
    public static function translation($text,$language,$value=null){
        $str = $text;
        $file = LyApi . '/language/' . $language . '.php';
        if(is_file($file)){
            $lang_config = require($file);
            if(array_key_exists($text,$lang_config)){
                $str = $lang_config[$text];
                if(is_array($value)){
                    foreach($value as $key => $val){
                        $str = str_replace('$' . $key,$val,$str);
                    }
                }
            }
        }
        return $str;
    }
}