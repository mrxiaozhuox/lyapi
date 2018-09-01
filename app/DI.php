<?php

namespace APP;

use LyApi\cache\FileCache;
use LyApi\cache\RedisCache;
use LyApi\Logger\Logger;
use LyApi\model\TParty;
use LyApi\tools\CurlUtils;
use Unirest\Request;

class DI{

    /*        DI默认函数        */

    //获取Medoo连接
    public static function Medoo(){
        return TParty::Medoo();
    }

    //获取文件缓存
    public static function FileCache($group=null){
        return new FileCache($group);
    }

    //获取Redis操作
    public static function RedisCache($config){
        return new RedisCache($config);
    }

    //获取日志操作类
    public static function Logger(){
        return new Logger();
    }

    //Curl工具类
    public static function CurlUtils($url,$responseHeader = 0){
        return new CurlUtils($url,$responseHeader);
    }

    //Unirest
    public static function Unirest(){
        return new Request();
    }

    /*        DI自定义函数        */

}