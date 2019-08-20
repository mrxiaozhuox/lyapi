<?php

namespace LyApi\Logger;

use LyApi\tools\Config;

class Logger
{

    private $LoggerSetting = [];
    private $LoggerDir = '';

    public function __construct($Setting = null)
    {
        if ($Setting != null) {
            $this->LoggerSetting = $Setting;
        } else {
            $this->LoggerSetting = Config::getConfig('logger', '');
        }
    }

    //设置一个数据
    public function SetLogger($data)
    {

        $dir = LyApi . '/data/log/' . date('Ym');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . '/' . date('Ymd') . '.log';

        // $log = sprintf("%s[%s]%s\n",date('Y-m-d H:i:s'),$type,$msg);

        $log = $this->LoggerSetting['LOGGER_TEMPLATE'];
        foreach ($this->LoggerSetting['TEMPLATE_ALIAS'] as $key => $val) {
            if (array_key_exists($key, $data)) {
                $to = $data[$key];
            } else {
                $to = $this->LoggerSetting['TEMPLATE_DEFAULT'][$key];
            }
            $log = str_replace($val, $to, $log);
        }

        if (!is_file($file)) {
            file_put_contents($file, $log . PHP_EOL);
        } else {
            file_put_contents($file, $log . PHP_EOL, FILE_APPEND);
        }
    }

    public function GetLastLogger()
    {

        $dir = LyApi . '/data/log/' . date('Ym');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }
        $file = $dir . '/' . date('Ymd') . '.log';

        $loggers = explode(PHP_EOL, file_get_contents($file));
        return $loggers[count($loggers) - 1];
    }

    public function SearchDateLogger($Date = null)
    {

        $dir = LyApi . '/data/log/' . date('Ym');
        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
        }

        if ($Date == null) {
            $Date = date('Ymd');
        }

        $file = $dir . '/' . $Date . '.log';

        if (is_file($file)) {
            $loggerList = file_get_contents($file);
            return explode(PHP_EOL, $loggerList);
        } else {
            return null;
        }
    }
}
