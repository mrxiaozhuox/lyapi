<?php

namespace LyApi\cache;

class FileCache{
    private $dir;
    public function __construct($group=null){
        if(is_null($group)){
            $group = 'defualt';
        }
        $dir = LyApi . '/data/cache/' . $group;
        if(! is_dir($dir)){
            if(! mkdir($dir,0777,true)){
                return false;
            }
        }
        $this->dir = $dir;
    }

    public function set($key,$data,$expire = 0){
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if($expire != 0){
            $duetime = time() + $expire;
            $datas = array(
                'data' => $data,
                'duetime' => $duetime
            );
        }else{
            $datas = array(
                'data' => $data
            );
        }

        $datas = json_encode($datas,JSON_UNESCAPED_UNICODE);
        $datas = base64_encode($datas);
        if(file_put_contents($filename,$datas)){
            return true;
        }else{
            return false;
        }
    }

    public function get($key){
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if(is_file($filename)){
            $datas = file_get_contents($filename);
            $datas = base64_decode($datas);
            $datas = json_decode($datas,true);
            if(array_key_exists('duetime',$datas)){
                if($datas['duetime'] > time()){
                    return $datas['data'];
                }else{
                    @unlink($filename);
                    return '';
                }
            }else{
                return $datas['data'];
            }
        }
    }

    public function has($key){
        if($this->get($key) == ''){
            return false;
        }else{
            return true;
        }
    }

    public function delete($key){
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        return @unlink($filename);
    }

    public function clean(){
        $dirs = scandir($this->dir);
        foreach($dirs as $dir){
            if($dir != '.' && $dir != '..'){
                @unlink($this->dir . '/' . $dir);
            }
        }
    }
}