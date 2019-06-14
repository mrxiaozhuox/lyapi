<?php

namespace LyApi\core\request;

class Cookie{
    private $path;
    private $domain;
    private $secure;
    private $httponly;

    public function __construct($path=null,$domain=null,$secure=false,$httponly=false){
        $this->path = $path;
        $this->domain = $domain;
        $this->secure = $secure;
        $this->httponly = $httponly;
    }

    public function Set($name,$data="",$expire=0){
        return setcookie(
            $name,
            $data,
            $expire,
            $this->path,
            $this->domain,
            $this->secure,
            $this->httponly
        );
    }

    public function Del($name){
        return $this->Set($name,'',time() - 1);
    }

    public function Get($name){
        return Request::Cookie($name);
    }

    public function EncryptSet($name,$data="",$expire=0){
        $data = base64_encode($data);
        return self::Set($name,$data,$expire);
    }

    public function EncryptGet($name){
        $data = self::Get($name);
        return base64_decode($data);
    }
}