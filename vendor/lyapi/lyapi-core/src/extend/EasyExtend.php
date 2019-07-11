<?php

/**
 * @Author: mrxiaozhuox
 * @Date: 2019-07-11
 * @introduce: This is the superclass for Extend and provides some simple functionality for Extend.
 */

namespace LyApi\tools;

use APP\DI;

class EasyExtend{

    //一些拓展信息，可以在子类直接修改

    public $Extend_Name = "EasyExtend";
    public $Extend_Author =    "LyAPI";
    public $Extend_Version =  "V1.0.0";
    
    //一些对象的储存

    public $FileCache_Objet = NULL;

    // -------------------------- 普通函数 -------------------------- //

    public function __construct(){
        $this->FileCache_Objet = DI::FileCache("Extend_" . $this->Extend_Name);
    }

    public function GetExtendInfo($info){
        $var_name = "Extend_" . $info;
        return $this->$var_name;
    }

    public function GetExtendName($info){
        return $this->Extend_Name;
    }

    
    public function GetExtendAuthor($info){
        return $this->Extend_Author;
    }

    public function GetExtendVersion($info){
        return $this->Extend_Version;
    }

    public function SetData($data_key,$data_value){
        if($data_key != "" && $data_value != ""){
            $this->FileCache_Objet->set($data_key,$data_value);
            return true;
        }
        return false;
    }

    public function GetData($data_key){
        if($data_key != ""){
            return $this->FileCache_Objet->get($data_key);
        }
        return "";
    }

    public function DelData($data_key){
        if($data_key != ""){
            return $this->FileCache_Objet->delete($data_key);
        }
        return false;
    }

    public function SetLog($type,$text){
        DI::Logger()->log($type,$text);
    }

    public function UseDI(){
        return DI;
    }


    // -------------------------- 静态函数 -------------------------- //

    public static function Static_SetData($data_key,$data_value){
        if($data_key != "" && $data_value != ""){
            DI::FileCache("Extend_" . $this->Extend_Name)->set($data_key,$data_value);
            return true;
        }
        return false; 
    }

    public static function Static_GetData($data_key){
        if($data_key != ""){
            return DI::FileCache("Extend_" . $this->Extend_Name)->get($data_key);
        }
        return "";
    }

    public static function Static_DelData($data_key){
        if($data_key != ""){
            return DI::FileCache("Extend_" . $this->Extend_Name)->delete($data_key);
        }
        return false;
    }

    public static function Static_SetLog($type,$text){
        DI::Logger()->log($type,$text);
    }

}