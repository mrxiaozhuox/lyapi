<?php

namespace LyApi\tools;

use Mustache_Engine;

class Template
{
    /**
     * @About: 直接渲染模版（目前仅支持 Mustache 模板引擎）
     */
    public static function RenderTemplate($template, $context = array(), $parameter = array(), $engine = 'mustache')
    {
        if ($engine == 'mustache') {
            $TplObj = new Mustache_Engine($parameter);
            return $TplObj->render($template, $context);
        } else {
            return $template;
        }
    }

    /**
     * @About: 生成JSON代码
     */
    public static function RenderJson($context)
    {
        // 待优化
        return json_encode($context);
    }
    
    /**
     * @About: 生成XML代码
     */
    public static function RenderXML($context)
    {
        if (is_array($context)) {
            $xml = "<xml>";
            foreach ($context as $key => $val) {
                if (is_numeric($val)) {
                    $xml .= "<$key>$val</$key>";
                } else
                    $xml .= "<$key><![CDATA[$val]]></$key>";
            }
            $xml .= "</xml>";
            return $xml;
        } else {
            return false;
        }
    }
}
