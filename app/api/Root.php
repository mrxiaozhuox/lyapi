<?php

/**
 * 本Class默认渲染主页面，可自行更改。
 */

namespace APP\api;

use APP\DI;
use LyApi\core\classify\VIEW;
use LyApi\core\error\ClientException;
use LyApi\core\request\Request;
use LyApi\LyApi;
use LyApi\tools\Launch;
use LyApi\tools\Template;

class Root extends VIEW
{

    // 主页面渲染：处理程序
    public function Index()
    {
        $ModeNow = Request::Get('Mode');

        $ChangeTo = '随机数据_' . rand(1000,9999);
        $ChangeNow = Request::Get('Test');
        if($ChangeNow == ''){
            $ChangeNow = '随机数据_XXXX';
        }

        $launchData =  Launch::LaunchApi('APP\api\Demo','Hello');

        if($ModeNow == 'Test'){
            return Template::RenderTemplate(file_get_contents(LyApi . '/app/view/html/test.html'),[
                'LyApi_Version' => LyApi::$version,
                'Launch_Mode' => self::GetMethod(),
                'Change_Now' => $ChangeNow,
                'Change_To' => $ChangeTo,
                'Launch_Path' => Template::RenderJson(self::GetParam()),
                'Launch_Data' => Template::RenderJson($launchData)
            ]);
        }else{
            return Template::RenderTemplate(file_get_contents(LyApi . '/app/view/html/index.html'),[
                'LyApi_Version' => LyApi::$version,
            ]);
        }
    }

    // 资源文件渲染：程序需要的静态资源
    // PS: 已废弃，程序更新了新的解决方案（ 位于Ecore程序中 ）
    public function Resource()
    {
        $type = Request::Get('type');
        $location = Request::Get('location');

        if($location == 'bootstrap'){
            if($type == 'js'){
                return file_get_contents(LyApi . '/app/view/static/bootstrap/js/bootstrap.min.js');
            }elseif($type == 'css'){
                return file_get_contents(LyApi . '/app/view/static/bootstrap/css/bootstrap.min.css');
            }else{
                return '';
            }
        }elseif($location == 'jquery'){
            return file_get_contents(LyApi . '/app/view/static/jquery-3.4.1.min.js');
        }else{
            return '';
        }

    }

}