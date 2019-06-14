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

    //渲染页面
    public static function Render($data,$vars){
        $str = $data;
        if(is_array($vars)){
            foreach($vars as $key => $val){
                $str = str_replace('$' . $key,$val,$str);
            }
        }
        return $str;
    }

    //输出内容
    public static function FPrint($text){
        echo $text;
    }

}