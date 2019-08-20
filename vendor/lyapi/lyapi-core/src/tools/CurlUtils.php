<?php

namespace LyApi\tools;

class CurlUtils
{

    private $ch;

    public function __construct($url, $responseHeader = 0)
    {
        $this->ch = curl_init($url);
        curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($this->ch, CURLOPT_HEADER, $responseHeader);
    }

    public function __destruct()
    {
        $this->close();
    }

    public function addHeader($value)
    {
        curl_setopt($this->ch, CURLOPT_HTTPHEADER, $value);
    }

    private function exec()
    {
        return curl_exec($this->ch);
    }

    public function get()
    {
        return $this->exec();
    }

    public function post($value, $https = true)
    {
        if ($https) {
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYPEER, FALSE);
            curl_setopt($this->ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        }
        curl_setopt($this->ch, CURLOPT_POST, 1);
        curl_setopt($this->ch, CURLOPT_POSTFIELDS, $value);
        return $this->exec();
    }

    private function close()
    {
        curl_close($this->ch);
    }
}
