<?php

namespace LyApi\core\error;

use Exception;

class CustomException extends Exception
{
    public function ErrorMsg()
    {
        return $this->getMessage();
    }
}
