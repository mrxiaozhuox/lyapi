<?php

/**
 * LyApi 错误页面处理程序
 * 错误处理程序定义函数名：_[HTTP_CODE]
 * 例：404 - 函数名( _404 )
 */

namespace Application\Controller;

use Application\Foundation\ViewCon;

class Exception extends ViewCon
{
    /**
     * 当错误处理器无法查找到相关函数，则会默认调用 _default 函数
     */

    public function _default($req, $resp)
    {
        $http_code = $req->options['HTTP_CODE'];
        $exception = $req->options['EXCEPTION'];


        return "<h1>" . $http_code . " Error!</h1>";
    }

    // public function _404($req, $resp)
    // {
    //     // 当你不返回任何值时，浏览器会渲染默认的 404 页面
    //     // return "404 Not Found";
    // }

    // public function _500($req, $resp)
    // {
    // }
}
