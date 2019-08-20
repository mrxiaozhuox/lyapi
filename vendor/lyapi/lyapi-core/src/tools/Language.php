<?php

namespace LyApi\tools;

class Language
{

    //获取语言翻译信息
    public static function Translation($text, $language, $value = null)
    {
        $str = $text;
        $file = LyApi . '/language/' . $language . '.json';
        if (is_file($file)) {
            $lang_config = json_decode(file_get_contents($file), true);
            if (array_key_exists($text, $lang_config)) {
                $str = $lang_config[$text];
                if (is_array($value)) {
                    foreach ($value as $key => $val) {
                        $str = str_replace('$' . $key, $val, $str);
                    }
                }
            }
        }
        return $str;
    }

    //设置（或创建）一个语言配置文件
    public static function SetLanguage($lang, $value, $increase = false)
    {
        $file = LyApi . '/language/' . $lang . '.json';
        if ($increase) {
            $ary = this::GetLanguage($lang);
            $returns = array_merge($ary, $value);
        } else {
            $returns = $value;
        }
        file_put_contents($file, $returns);
    }

    //获取一个语言配置文件
    public static function GetLanguage($lang)
    {
        $file = LyApi . '/language/' . $lang . '.json';
        if (is_file($file)) {
            $data = json_decode(file_get_contents($file), true);
            return $data;
        } else {
            return array();
        }
    }
}
