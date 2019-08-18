<?php

namespace Plugin\Core\tools;

class Save{

    private $Save_Path = '';
    private $Save_Name = '';

    //传入所有插件数据，以便区分插件
    public function __construct($name){
        $this->Save_Name = $name;
        $this->Save_Path = LyApi . '/data/plugin/' . $name . '/';
    }

    //储存特定数据
    public function SaveData($title,$data,$group = "index",$postfix = "json",$add = false){

        if($group != 'index'){
            $path = $this->Save_Path . $group . '/';
        }else{
            $path = $this->Save_Path;
        }

        $file = $path . $title . '.' . $postfix;

        if(! is_dir($path)){
            mkdir($path,0777,true);
        }

        if($postfix == 'json'){
            $data = json_encode($data);
        }

        if($add){
            $ret = file_put_contents($file,$data,FILE_APPEND);
        }else{
            $ret = file_put_contents($file,$data);            
        }

        return ($ret > 0) ? true : false;

    }

    //读取特定数据
    public function ReadData($title,$group="index",$postfix = "json"){

        if($group != 'index'){
            $path = $this->Save_Path . $group . '/';
        }else{
            $path = $this->Save_Path;
        }

        $file = $path . $title . '.' . $postfix;

        if(! is_dir($path)){
            mkdir($path,0777,true);
        }

        if($postfix == 'json'){
            return json_decode(file_get_contents($file));
        }else{
            return file_get_contents($file);
        }
    }

    //返回文件地址，可自行处理
    public function FilePath($title,$group="index",$postfix = "json"){
        if($group != 'index'){
            $path = $this->Save_Path . $group . '/';
        }else{
            $path = $this->Save_Path;
        }

        return $path . $title . '.' . $postfix;
    }
}