<?php

namespace LyApi\core\error;

use Exception;

class CustomException extends Exception
{
    public function ErrorMsg()
    {
        return $this->getMessage();
    }
    public function ErrorCode()
    {
        $code = $this->getCode();
        if ($code == 0) {
            return 200;
        } else {
            return $code;
        }
    }
}
