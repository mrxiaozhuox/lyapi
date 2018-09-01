<?php

namespace LyApi\core\error;

use Exception;

class ServerException extends Exception{
    public function ErrorMsg(){
        return $this->getMessage();
    }
    public function ErrorCode(){
        $code = $this->getCode();
        if($code >= 100){
            return 500;
        }else{
            return 500 + $code;
        }
    }
}