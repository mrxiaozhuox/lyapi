<?php

/**
 * LyDev System
 * http://dev.wwsg18.com
 */

namespace Extend\DevPanel;

use Exception;
use Extend\DevPanel\Controller\Dev;
use Extend\DevPanel\Library\Storage;
use ExtFunc;
use LyApi\Core\Route;
use LyApi\Foundation\FoExt;
use LyApi\Support\Config;
use LyApi\Support\Storage\Session;

class Main extends FoExt
{
    public const EXTDEV_STD = true;

    public const EXTEND_NAME = "DevPanel";
    public const EXTEND_VERSION = "V0.2";
    public const EXTEND_DESCRIBLE = "定义属于你的 LyApi 框架!";
    public const EXTEND_TYPE = "功能管理";

    public const EXTEND_AUTHOR = "LyApi-Development";
    public const EXTEND_WEBSITE = "http://dev.wwsg18.com/";


    /**
     * 路由注册开关
     * 不需要路由注册功能请关闭，节省系统资源。
     */
    public $_reg_route = true;


    /**
     * 公用函数注册
     * 注册后使用格式：ExtFunc::FUNCTION_NAME();
     * ExtFunc兼容性强，当插件被卸载后调用的也不会报错（返回Null）
     */
    public function event_helper_register()
    {
        return [
            "is_debug" => function () {
                return Config::dotConfig("app.open_debug");
            },
            "devlogin" => function () {
                return Session::get("extdev-login", false);
            },
            "_child_dir" => function ($path) {

                try {
                    $list = scandir($path);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }

                $res = [];

                foreach ($list as $f) {
                    if (is_dir($path . $f) && $f != "." && $f != "..") {
                        array_push($res, $f);
                    }
                }

                return $res;
            }
        ];
    }

    /**
     * 插件注册事件
     */
    public function event_route_register()
    {
        Storage::init();

        Route::regex("ctrl", "[0-9a-zA-Z\_\-\.]+");

        // 使用闭包函数方法实现路由，非常方便！
        Route::rule("/@dev", function ($req, $resp) {
            return Dev::index($req, $resp);
        }, "GET")::filter(ExtFunc::devlogin(), "/@dev/login");

        Route::rule("/@dev/manager", function ($req, $resp) {
            return Dev::manager($req, $resp);
        }, "GET")::filter(ExtFunc::devlogin(), "/@dev/login");

        Route::rule("/@dev/panel/@{ctrl}", function ($req, $resp) {
            return Dev::panel($req, $resp);
        }, "GET")::filter(ExtFunc::devlogin(), "/@dev/login");

        Route::rule("/@dev/application/@{ctrl}/{ctrl}", function ($req, $resp) {
            return Dev::application($req, $resp);
        }, "ANY");

        Route::rule("/@dev/extres/@{ctrl}.js", function ($req, $resp) {
            return Dev::extres($req, $resp);
        }, "GET");

        Route::rule("/@dev/api/{letter}", function ($req, $resp) {
            return Dev::api($req, $resp);
        }, "ANY");

        Route::rule("/@dev/login", function ($req, $resp) {
            return Dev::login($req, $resp);
        });

        Route::resource("/@dev/static/", ROOT_PATH . "/extend/DevPanel/Template/static/");

    }



    public function event_trigger_register()
    {
        return false;
    }
}
