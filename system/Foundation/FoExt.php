<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 插件入口基类 - FoExt
// -+----------------------------+-

namespace LyApi\Foundation;

use LyApi\Support\Cache\FileCache;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class FoExt
{

    public const EXTEND_NAME = "FoExt";
    public const EXTEND_VERSION = "V1.0";
    public const EXTEND_DESCRIBLE = "拓展程序基类 FoExt";
    public const EXTEND_TYPE = "功能管理";

    public const EXTEND_AUTHOR = "mr小卓X";
    public const EXTEND_WEBSITE = "http://blog.wwsg18.com/";

    public $_reg_route = true;

    final public static function render_template($file, $data = [])
    {
        $ext = explode("\\",get_called_class());
        $ext = $ext[length($ext) - 2];
        $path = ROOT_PATH . '/extend/' . $ext . '/template/';
        $viewLoader = new FilesystemLoader($path);

        $viewTwig = new Environment($viewLoader, [
            'cache' => ROOT_PATH . '/runtime/template',
            'auto_reload' => true
        ]);

        $template = $viewTwig->load($file);
        $res = $template->render($data);
        return $res;
    }

    final public static function ext_filecache(){
        return new FileCache('_' . str_replace("\\", '.', get_called_class()));
    }

    public function event_route_register()
    {
        $this->_reg_route = false;
    }

    public function event_helper_register()
    {
        return false;
    }

    public function event_trigger_register()
    {
        return false;
    }
}
