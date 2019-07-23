<?php

namespace LyApi\tools;

//配置文件加载类
class Config {
    //获取配置文件（文件名，文件位置，文件类型）
    public static function getConfig($name,$place = 'custom',$type = "php"){
        if($place != ""){
            if($type == 'json'){
                $config_json = file_get_contents(LyApi . '/config/' . $place . '/' . $name . '.json');
                $config = json_decode($config_json,true);
            }else{
                $config = require LyApi . '/config/' . $place . '/' . $name . '.php';
            }
        }else{
            if($type == 'json'){
                $config_json = file_get_contents(LyApi . '/config/' . $place . '/' . $name . '.json');
                $config = json_decode($config_json,true);
            }else{
                $config = require LyApi . '/config/' . $name . '.php';
            }
        }

        return $config;
    }

    //设置配置文件（文件名，数据，类型，文件位置）仅支持Json类型的文件修改
    public static function setConfig($name,$data,$type = "cover",$place = 'custom'){

        if($place != ''){
            $path = LyApi . '/config/' . $place . '/' . $name . '.json';            
        }else{
            $path = LyApi . '/config/' . $name . '.json';
        }

        if($type == 'cover'){
            return file_put_contents($path,json_encode($data));
        }elseif($type == 'modify'){
            if(file_exists($path)){
                $old_conifg_json = file_get_contents($path);
                $old_config = json_decode($old_conifg_json,true);

                $new_config_array = $old_config + $data;
                return file_put_contents($path,json_encode($new_config_array));
            }else{
                return false;
            }
        }
        return false;
    }

}