<?php

/**
 * LyApi Framework 插件开发
 */

namespace Extend\Dorea;

use Extend\Dorea\library\Controller;
use Extend\Dorea\library\Database;
use Extend\LyDev\controller\Dev;
use LyApi\Core\Route;
use LyApi\Foundation\FoExt;
use LyApi\Support\Config;
use LyApi\Support\Event;

class Main extends FoExt
{

    // LyDev 标准化注册 - 使用 LyDev 必须开启！！
    public const EXTDEV_STD = true;

    public const EXTEND_NAME = "Dorea";
    public const EXTEND_VERSION = "V1.10";
    public const EXTEND_DESCRIBLE = "Dorea 数据库驱动";
    public const EXTEND_TYPE = "数据库连接";

    public const EXTEND_AUTHOR = "Dorea-Development";
    public const EXTEND_WEBSITE = "http://dorea.mrxzx.info/";


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
    public function event_helper_register(): array
    {
        return [
            "dorea_connect" => function ($url, $password, $default_group = "default") {
                return new Database($url, $password, $default_group);
            }
        ];
    }

    /**
     * 插件注册事件
     */
    public function event_route_register()
    {
        Route::rule("/@dorea/api/{letter}", function($req, $resp) {
            return Controller::Api($req, $resp);
        },"any");
    }

    /**
     * 事件注册器
     */
    public function event_trigger_register(): bool
    {
        return false;
    }
}
