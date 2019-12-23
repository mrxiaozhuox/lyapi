<?php

namespace Plugin\PConfig;

use Plugin\Core\Core;
use LyApi\tools\Config;
use Whoops\Exception\ErrorException;

/**
 * Name: Core.Config
 * Author: LyAPI
 * ModifyTime: 2019/07/29
 * Purpose: 插件配置文件系统
 */

class PConfig extends Core{

    private $Use_Plugin = '';

    //设置插件信息（请严格按照本模板编写代码）
    public function __construct($Plugin){

        $this->Plugin_Name = 'PConfig';
        $this->Plugin_Version = 'V1.0.0';
        $this->Plugin_Author = 'mrxiaozhuox';
        $this->Plugin_About = '插件配置文件系统';
        $this->Plugin_Examine = '';

        $this->Use_Plugin = $Plugin;

        //判断文件夹是否存在
        $path = LyApi . '/config/' . 'plugin/' . $this->Use_Plugin . '/';
        if(! is_dir($path)){
            mkdir($path,0777,true);
        }
    }

    //设置一个配置文件
    public function SaveConfig($name,$data,$add = false){
        try{
            if($add){
                $ret = Config::setConfig($name,$data,'modify','plugin/' . $this->Use_Plugin);
            }else{
                $ret = Config::setConfig($name,$data,'cover','plugin/' . $this->Use_Plugin);
            }
            return $ret;
        }catch(ErrorException $e){
            return false;
        }
    }

    //配置文件是否存在
    function HasConfig($name){
        return is_file(LyApi . '/config/' . 'plugin/' . $this->Use_Plugin . '/' . $name . '.json');
    }

    //读取一个配置文件
    public function ReadConfig($name){
        try{
            return Config::getConfig($name,'plugin/' . $this->Use_Plugin,'json');
        }catch(ErrorException $e){
            return '';
        }
    }

    //删除一个配置文件
    public function DeleteConfig($name){
        try{
            return unlink(LyApi . '/config/' . 'plugin/' . $this->Use_Plugin . '/' . $name . '.json');
        }catch(ErrorException $e){
            return false;
        }
    }

    //获取配置文件目录
    public function GetDirPath(){
        return LyApi . '/config/' . 'plugin/' . $this->Use_Plugin . '/';
    }

    //获取插件的配置列表
    public function GetDataList(){
        $RetList = array();
        $FileList = scandir(LyApi . '/config/' . 'plugin/' . $this->Use_Plugin . '/');
        // var_dump($FileList);
        for($i = 0; $i < sizeof($FileList); $i ++){
            preg_match('|\.(\w+)$|',$FileList[$i], $ext);
            if($ext != []){
                if($ext[0] == '.json'){
                    preg_match('|(.*)\.[^.]+|',$FileList[$i],$file);
                    array_push($RetList,$file[1]);
                }
            }
        }

        return $RetList;
    }

    //配置文件初始化（文件不存在则创建，存在则不操作）
    public function InitConfig($name,$data){
        if(! $this->HasConfig($name)){
            $this->SaveConfig($name,$data);
        }
    }
}