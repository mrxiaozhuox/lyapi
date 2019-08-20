<?php

namespace LyApi\core\error;

use Exception;

class OtherException extends Exception
{
    public function ErrorMsg()
    {
        return $this->getMessage();
    }
    public function ErrorCode()
    {
        $code = $this->getCode();
        return $code;
    }
}
