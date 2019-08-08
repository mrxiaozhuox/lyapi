<?php

namespace LyApi\Logger;

class Logger{
    public static function info($msg){
        self::add('INFO',$msg);
    }
    public static function warn($msg){
        self::add('WARN',$msg);
    }
    public static function error($msg){
        self::add('ERROR',$msg);
    }
    public static function debug($msg){
        self::add('DEBUG',$msg);
    }
    public static function log($type,$msg){
        self::add($type,$msg);
    }

    private static function add($type,$msg){
        $dir = LyApi . '/data/log/' . date('Ym');
        if(! is_dir($dir)){
            mkdir($dir,0777,true);
        }
        $file = $dir . '/' . date('Ymd') . '.log';
        $log = sprintf("%s[%s]%s\n",date('Y-m-d H:i:s'),$type,$msg);
        if(! is_file($file)){
            file_put_contents($file,$log);
        }else{
            file_put_contents($file,$log,FILE_APPEND);
        }
    }
}