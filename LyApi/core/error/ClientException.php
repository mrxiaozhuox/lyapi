<?php

namespace LyApi\core\error;

use Exception;

class ClientException extends Exception{
    public function ErrorMsg(){
        return $this->getMessage();
    }
    public function ErrorCode(){
        $code = $this->getCode();
        if($code >= 100){
            return 400;
        }else{
            return 400 + $code;
        }
    }
}