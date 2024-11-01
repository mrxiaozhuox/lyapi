<?php

/**
 * LyApi Framework 插件开发
 */

namespace Extend\DevPanel\Controller;

use Error;
use Exception;
use Extend\DevPanel\Library\Common;
use Extend\DevPanel\Library\Panel;
use Extend\DevPanel\Library\Storage;
use Extend\DevPanel\Main;
use ExtFunc;
use LyApi\Core\Request;
use LyApi\Core\Response;
use LyApi\Core\Route;
use LyApi\Core\View;
use LyApi\Support\Extend;
use LyApi\Support\Storage\Session;
use ZipArchive;

class Dev
{
    private const EXT_PATH = ROOT_PATH . '/extend/LyDev/';

    public static function index(Request $req, Response $resp)
    {
        $exts = Extend::list();

        $rots = Route::list()['routes'];

        foreach ($rots as $key => $value) {

            if (substr($key, 0, 5) == "/@dev") {
                unset($rots[$key]);
                continue;
            }

            if (is_object($value['controller'])) {
                $rots[$key]['controller'] = "@Closure";
            }
            if (is_array($value['method'])) {
                $rots[$key]['method'] = "MULTI";
            }
        }

        foreach ($exts as $key => $value) {
            $exts[$key]['EXTDEV_STD'] = Panel::standard($value['EXTEND_NAME']);
        }

        return Main::render_template("index.swig", [
            "extList" => $exts,
            "routeli" => $rots,
            "page" => "info"
        ]);
    }

    public static function manager(Request $req, Response $resp)
    {
        return Main::render_template("manager.swig", [
            'page' => 'manager',
            "checker" => "@dev" . md5(date("Ymd") . "@DEVCHECKER")
        ]);
    }


    public static function panel(Request $req, Response $resp)
    {
        if ($req->variable[0] != null) {
            $ext = $req->variable[0];

            $loader = $req::Get("loader");
            $redirect = "";

            $root_path = ROOT_PATH . '/extend/' . $ext . '/';

            if ($loader == "") {

                if (is_file($root_path . '/PanelConfig/setting.json')) {
                    $setting = file_get_contents($root_path . '/PanelConfig/setting.json');
                    $setting = json_decode($setting, true);

                    if (isset($setting['panel-setting']['default-file'])) {
                        $loader = $setting['panel-setting']['default-file'];
                    }

                    if (isset($setting['panel-setting']['panel-url'])) {
                        $redirect = $setting['panel-setting']['panel-url'];
                    }
                }

                $loader = $loader == "" ? "panel.xml" : $loader;
            }

            if ($loader == "panel.xml") {
                $forms = Panel::parse($ext);

                $body = null;

                if ($forms) {
                    foreach ($forms['struct'] as $value) {
                        if ($value['_lab'] == "page") {
                            $body = $value;
                        }
                    }
                } else {
                    $forms = ['struct' => [], 'event' => []];
                }

                if ($redirect == "") {
                    $redirect = null;
                }

                return Main::render_template("panel.swig", [
                    "body" => $body,
                    "forms" => $forms['struct'],
                    "extname" => $ext,
                    "redirect" => $redirect
                ]);
            } else {

                $path = ROOT_PATH . '/extend/' . $ext . '/PanelConfig/';
                if (is_file($path . $loader)) {
                    return file_get_contents($path . $loader);
                } else {
                    return Main::render_template("panel.swig", [
                        "body" => [],
                        "forms" => [],
                        "extname" => $ext
                    ]);
                }
            }
        } else {
            abort(404);
        }
    }

    public static function api(Request $req, Response $resp)
    {
        $enter = $req->variable[0];

        if (!ExtFunc::devlogin()) {
            return View::api([
                "~" => [
                    "code" => 400,
                    "msg" => "接口仅在登录后允许调用"
                ]
            ]);
        }

        if ($enter == "info") {

            $ext = $req::Get("ext") != "" ? $req::Get("ext") : "NOT_FOUND_EXT_INFO";
            $path = ROOT_PATH . '/extend/' . $ext . '/';


            if (!is_file($path . '/Main.php')) {
                return View::api([
                    "~" => [
                        "code" => 400,
                        "msg" => "未知的拓展程序"
                    ]
                ]);
            }

            try {
                $tar = "Extend\\" . $ext . "\\Main";
                $obj = new $tar();
            } catch (Exception $e) {
                return View::api([
                    "~" => [
                        "code" => 500,
                        "msg" => "拓展不符合标准"
                    ]
                ]);
            }

            $item = $req::Get("item") != "" ? $req::Get("item") : "NAME|VERSION|AUTHOR|.STD";
            $item = strtoupper($item);

            $item = explode("|", $item);

            $res = [];
            foreach ($item as $i) {

                if (substr($i, 0, 1) == ".") {
                    $name = "EXTDEV_" . substr($i, 1, strlen($i));
                } else {
                    $name = "EXTEND_" . $i;
                }

                try {
                    $res[$i] = eval('return $obj::' . $name . ';');
                } catch (Error $e) {
                    continue;
                }
            }

            if (in_array(".STD", $item)) {
                $res['.STD'] = Panel::standard($ext) ? "standard" : "original";
            }

            if (in_array(".MD5", $item)) {
                $res['.MD5'] = md5_file($path . '/Main.php');
                $context = file_get_contents($path . '/Main.php');
                $res['.MD5'] = md5(preg_filter("/\s+/", '', $context));
            }

            return View::api($res);
        } elseif ($enter == "repwd") {
            $pwd = $req::Get("pwd");
            $checker = $req::Get("checker");

            if ($checker == "@dev" . md5(date("Ymd") . '@DEVCHECKER')) {
                $res = Storage::$db->set("extdev-pwd", $pwd, true);
                if ($res) {
                    return View::api("OK");
                } else {
                    return View::api([
                        "~" => [
                            "code" => 400,
                            "msg" => "密码更新错误"
                        ]
                    ]);
                }
            } else {
                return View::api([
                    "~" => [
                        "code" => 400,
                        "msg" => "检查码错误"
                    ]
                ]);
            }
        } elseif ($enter == "upload") {

            // 在线拓展上传
            $file = isset($_FILES['file_data']) ? $_FILES['file_data'] : null;
            $name = isset($_POST['file_name']) ? $_POST['file_name'] : null;
            $total = isset($_POST['file_total']) ? $_POST['file_total'] : 0;
            $index = isset($_POST['file_index']) ? $_POST['file_index'] : 0;
            $md5   = isset($_POST['file_md5']) ? $_POST['file_md5'] : 0;
            $size  = isset($_POST['file_size']) ? $_POST['file_size'] : null;
            $chunksize  = isset($_POST['file_chunksize']) ? $_POST['file_chunksize'] : null;
            $suffix  = isset($_POST['file_suffix']) ? $_POST['file_suffix'] : null;

            if (!$md5 || $file['error'] != 0) {
                return View::api([
                    "~" => [
                        "msg" => "没有文件被上传！"
                    ]
                ]);
            }

            if ($suffix != "zip") {
                return View::api([
                    "~" => [
                        "msg" => "未知的文件类型，仅支持 zip 文件！"
                    ]
                ]);
            }

            $path = ROOT_PATH . '/extend/';

            $ext = explode(".", $name)[0];
            if (is_dir($path . $ext)) {
                try {
                    Common::rmdir($path . $ext);
                } catch (Exception $e) {
                    echo $e->getMessage();
                }
                // return View::api([
                //     "~" => [
                //         "msg" => "当前框架已存在同名的拓展程序！"
                //     ]
                // ]);
            }

            if ($index <= $total) {
                if (is_file($file['tmp_name'])) {
                    $content = file_get_contents($file['tmp_name']);
                    if (!file_put_contents($path . $name, $content, FILE_APPEND)) {
                        return View::api([
                            "~" => [
                                "msg" => "文件写入失败，安装自动停止！"
                            ]
                        ]);
                    }
                }

                if ($index == $total) {


                    $old_d = ExtFunc::_child_dir($path);

                    $zip = new ZipArchive();
                    if ($zip->open($path . $name)) {
                        try {
                            $zip->extractTo($path);
                            $zip->close();
                        } catch (Exception $e) {
                            // do some thing ...
                        }
                    } else {
                        return View::api([
                            "~" => [
                                "msg" => "文件解压失败，压缩包可能存在问题！"
                            ]
                        ]);
                    }

                    unlink($path . $name);

                    $new_d = ExtFunc::_child_dir($path);

                    // 当安装前后目录结构一样
                    if ($old_d == $new_d && !in_array($ext, $old_d)) {
                        Common::rmdir($path . $ext);
                        return View::api([
                            "~" => [
                                "msg" => "拓展安装失败！"
                            ]
                        ]);
                    }

                    return View::api([
                        "上传成功",
                        $ext
                    ]);
                } else {
                    return View::api("阶段完成");
                }
            }
        } elseif ($enter == "delete") {

            $ext = $req::Get("ext");
            if ($ext == "") {
                return View::api([
                    "~" => [
                        'code' => 400,
                        "msg" => "未知的拓展程序"
                    ]
                ]);
            }

            if (is_dir(ROOT_PATH . '/extend/' . $ext)) {
                try {
                    Common::rmdir(ROOT_PATH . '/extend/' . $ext);
                } catch (Exception $e) {
                    return View::api([
                        "~" => [
                            'code' => 500,
                            "msg" => "删除拓展失败"
                        ]
                    ]);
                }

                return View::api("OK");
            } else {
                return View::api([
                    "~" => [
                        'code' => 404,
                        "msg" => "不存在的拓展信息"
                    ]
                ]);
            }
        }
    }

    public static function extres(Request $req, Response $resp)
    {
        $script = "/** 本文件为 LyDev 根据 XML 文件全自动生成！仅用于 LyDev 程序内部！！！ */\n";
        $function = "function __NAME__(__ARGS__){ __CODE__ }" . PHP_EOL;

        $extinfo = $req->variable[0];

        $typeinfo = explode("-", $extinfo);
        $ext = $typeinfo[0];
        $typeinfo = $typeinfo[1] . '-' . $typeinfo[2];

        if ($typeinfo == "Dev-Package") {
            $event = Panel::parse($ext)['event'];

            foreach ($event as $eve) {
                $make = $function;
                $make = str_replace("__NAME__", $eve['_name'], $make);


                if ($eve['type'] == "script" && $eve['env'] == "javascript") {

                    $scr = $eve['script'];

                    $scr = Panel::compre($scr);

                    $make = str_replace("__ARGS__", "", $make);
                    $make = str_replace("__CODE__", $scr, $make);
                } elseif ($eve['type'] == 'submit') {

                    $tar = $eve['target'];

                    $args = '{';


                    if (is_array($eve['argument'])) {
                        foreach ($eve['argument'] as $arg) {

                            if (isset($arg['name'])) {
                                $k = $arg['name'];
                            } else {
                                $k = "K_" . rand(1111111, 9999999);
                            }

                            if (isset($arg['value'])) {

                                $v = $arg['value'];

                                if (substr($v, 0, 1) == "@") {
                                    $v = "$('#" . str_replace("@", '', $v) . "').val()";
                                }
                            } else {
                                $v = "V_" . rand(1111111, 9999999);
                            }

                            $args .= '\'' . $k . '\':' . $v . ',';
                        }
                        $args = substr($args, 0, strlen($args) - 1) . '};';
                    }

                    $checker = md5(date("Ymd") . '@' . $ext);

                    $code = "var data=" . $args . ";var checker='" . $checker . "';for(const key in data){if(data[key]==''){layer.msg('请先填写相关信息！');return false}}$.ajax({type:'POST',url:'/@dev/application/@" . $ext . "/" . $tar . "',data:{data:data,checker:checker},dataType:'json',success:function(res){data=res.data;if(typeof data=='object'){if(data[0]=='OK'){if(data.length>1){layer.msg(data[1])}else{layer.msg('数据保存成功！')}}}else if(data=='OK'){layer.msg('数据保存成功！')}else{layer.msg('异常 ['+res.code+']: '+JSON.stringify(data))}},error:function(res){layer.msg('拓展数据返回异常！请联系拓展作者！')}});return false;";

                    $make = str_replace("__ARGS__", "", $make);
                    $make = str_replace("__CODE__", $code, $make);
                } else {
                    $make = str_replace("__ARGS__", "", $make);
                    $make = str_replace("__CODE__", "console.log('未知用途的函数！');", $make);
                }



                $script .= $make;
            }
        }

        // return $event;

        header("Content-type: text/javascript");

        return $script;
    }

    public static function application(Request $req, Response $resp)
    {
        $ext =  $req->variable[0];
        $path = $req->variable[1];

        $root_path = ROOT_PATH . '/extend/' . $ext . '/';

        if (is_dir($root_path)) {
            if (is_file($root_path . '/DevPanel/setting.json')) {
                $setting = file_get_contents($root_path . '/DevPanel/setting.json');
                $setting = json_decode($setting, true);

                $loader = "";
                if (isset($setting['app-loader'])) {
                    $loader = $setting['app-loader'];
                } else {
                    return View::json(["msg" => "相关程序未开放！"]);
                }

                if (is_string($loader)) {
                    $loader = [$loader];
                }

                $objs = [];
                foreach ($loader as $sign => $load) {

                    $load = explode(".", $load);
                    $node = 'Extend\\' . $ext . '\\' . $load[0];

                    array_shift($load);
                    array_push($load, '');

                    foreach ($load as $step) {

                        if (class_exists($node)) {
                            $objs[$sign] = new $node();
                            break;
                        }

                        $node = $node . '\\' . $step;
                    }
                }

                $path = explode(".", $path);

                if (isset($objs[$path[0]])) {
                    $object = $objs[$path[0]];

                    $base = get_parent_class($object);
                    $base = new $base();

                    $function = $path[1];

                    $methods = get_class_methods($object);

                    $res = '';
                    if (in_array($function, $methods)) {
                        $res = $object->$function($req, $resp);
                    }

                    try {
                        return $base->_export($res);
                    } catch (Exception $e) {
                        return $res;
                    }
                }
            } else {
                return View::json(["msg" => "相关程序未开放！"]);
            }
        } else {
            return View::json(["msg" => "拓展程序不存在！"]);
        }
    }

    public static function login(Request $req, Response $resp)
    {
        if ($_SERVER['REQUEST_METHOD'] == "GET") {
            return Main::render_template("login.swig");
        } else {
            $pwd = Request::Post("password");

            if ($pwd == '') {
                return "密码参数不存在！";
            }

            $spw = Storage::$db->get("extdev-pwd");
            if ($spw == $pwd) {
                Session::set("extdev-login", true);
                return "OK";
            } else {
                return "密码输入错误！";
            }
        }
    }
}
