<?php

/**
 * LyApi Framework 插件开发
 */

namespace Extend\DevPanel\Library;

use Error;

class Panel
{
    public static function standard($ext)
    {
        $path = ROOT_PATH . '/extend/' . $ext . '/';

        if (is_file($path . 'Main.php')) {
            $tar = "Extend\\" . $ext . "\\Main";
            $obj = new $tar();

            try {
                $obj::EXTDEV_STD;
            } catch (Error) {
                return false;
            }

            if (is_dir($path . 'PanelConfig')) {
                return true;
            }

        }

        return false;
    }

    public static function parse($ext)
    {
        $path = ROOT_PATH . '/extend/' . $ext;

        if (file_exists($path . '/PanelConfig/panel.xml')) {

            $xml = file_get_contents($path . '/PanelConfig/panel.xml');
            $xml = simplexml_load_string($xml);
            $arx = json_decode(json_encode($xml), true);

            if (isset($arx['struct']) && isset($arx['event'])) {
                $arx = self::format($arx);

                return $arx;
            } else {
                return false;
            }
        }

        return false;
    }


    public static function format($arx)
    {

        $struct = [];
        $event  = [];

        if (!(isset($arx['struct']) && isset($arx['event']))) {
            return null;
        }

        $struct = $arx['struct'];
        $event  = $arx['event'];

        $res = [
            "struct" => [],
            "event"  => []
        ];

        foreach ($struct as $key => $value) {

            $data = [];
            $rand = md5(uniqid(microtime(true), true));

            // var_dump($value);
            // echo "<br>" . $key . '<br><br>';

            if (isset($value['@attributes'])) {
                $data = self::_rec($key, $value, $rand);
                array_push($res['struct'], $data);
            } else {
                if (is_array($value)) {
                    foreach ($value as $iv) {
                        $data = self::_rec($key, $iv, $rand);
                        array_push($res['struct'], $data);
                        $rand = md5(uniqid(microtime(true), true));
                    }
                }
            }
        }

        foreach ($event as $key => $value) {
            if (isset($value['@attributes'])) {
                $data = self::_eve($key, $value, $rand);
                array_push($res['event'], $data);
            } else {
                if (is_array($value)) {
                    foreach ($value as $iv) {
                        $data = self::_eve($key, $iv, $rand);
                        array_push($res['event'], $data);
                        $rand = md5(uniqid(microtime(true), true));
                    }
                }
            }
        }

        return $res;
    }

    private static function _rec($key, $value, $rand)
    {

        $data['_lab'] = $key;
        $data['_ckr'] = md5($rand);

        if ($key == "page") {
            $data['_evr'] = [];
            foreach ($value as $k => $v) {

                if ($k == "event") {
                    if (isset($v['@attributes'])) {
                        $v = [$v];
                    }

                    foreach ($v as $ev) {
                        $evr = [];
                        if (isset($ev['@attributes'])) {
                            $attr = $ev['@attributes'];

                            if (array_key_exists("trigger", $attr)) {
                                $evr['trigger'] = $attr['trigger'];
                            } else {
                                $evr['trigger'] = null;
                            }

                            if (array_key_exists("function", $attr)) {
                                $evr['function'] = $attr['function'] . '()';
                            } else {
                                $evr['function'] = null;
                            }

                            if (array_key_exists("target", $attr)) {
                                $evr['target'] = $attr['target'];
                            } else {
                                $evr['target'] = null;
                            }
                        }

                        array_push($data['_evr'], $evr);
                    }
                }
            }
        } elseif ($key == "input") {
            if (isset($value['@attributes'])) {
                $attr = $value['@attributes'];
                if (array_key_exists("hint", $attr)) {
                    $data['hint'] = $attr['hint'];
                } else {
                    $data['hint'] = "";
                }


                if (array_key_exists("id", $attr)) {
                    $data['id'] = $attr['id'];
                } else {
                    $data['id'] = $rand;
                }

                if (array_key_exists("value", $attr)) {
                    $data['value'] = $attr['value'];
                } else {
                    $data['value'] = "";
                }

                if (array_key_exists("type", $attr)) {
                    $data['type'] = $attr['type'];
                } else {
                    $data['type'] = "text";
                }
            }

            foreach ($value as $k => $v) {
                if ($k == "label" && is_string($v)) {
                    $data['label'] = $v;
                }
                if ($k == "note" && is_string($v)) {
                    $data['note'] = $v;
                }
            }
        } elseif ($key == 'select') {

            $data['options'] = [];

            // var_dump($value);

            if (isset($value['@attributes'])) {
                $attr = $value['@attributes'];

                if (array_key_exists("id", $attr)) {
                    $data['id'] = $attr['id'];
                } else {
                    $data['id'] = $rand;
                }
            }

            foreach ($value as $k => $v) {
                if ($k == "label" && is_string($v)) {
                    $data['label'] = $v;
                }

                if ($k == "option" && is_array($v)) {
                    foreach ($v as $opt) {
                        array_push($data['options'], $opt);
                    }
                }
            }
        } elseif ($key == "button") {
            if (isset($value['@attributes'])) {
                $attr = $value['@attributes'];

                if (array_key_exists("type", $attr)) {
                    $data['type'] = $attr['type'];
                } else {
                    $data['type'] = "button";
                }

                if (array_key_exists("text", $attr)) {
                    $data['text'] = $attr['text'];
                } else {
                    $data['text'] = "Dev 按钮";
                }
            }

            $data['class'] = $data['type'] . '_';

            foreach ($value as $k => $v) {
                if ($k == "text" && is_string($v)) {
                    $data['text'] = $v;
                }
            }
        }

        $data['_evr'] = [];
        foreach ($value as $k => $v) {
            if ($k == "event") {
                if (isset($v['@attributes'])) {
                    $v = [$v];
                }

                foreach ($v as $ev) {
                    $evr = [];
                    if (isset($ev['@attributes'])) {
                        $attr = $ev['@attributes'];

                        if (array_key_exists("trigger", $attr)) {
                            $evr['trigger'] = $attr['trigger'];
                        } else {
                            $evr['trigger'] = null;
                        }

                        if (array_key_exists("function", $attr)) {
                            $evr['function'] = $attr['function'] . '()';
                        } else {
                            $evr['function'] = null;
                        }

                        if (array_key_exists("target", $attr)) {
                            $evr['target'] = $attr['target'];
                        } else {
                            $evr['target'] = null;
                        }
                    }

                    array_push($data['_evr'], $evr);
                }
            }
        }

        return $data;
    }

    private static function _eve($key, $value, $rand)
    {
        $data['_name'] = $key;

        if (isset($value['@attributes'])) {
            $attr = $value['@attributes'];
            $data['type'] = $attr['type'];

            if ($attr['type'] == "script") {
                if (array_key_exists("env", $attr)) {
                    $data['env'] = $attr['env'];
                } else {
                    $data['env'] = "javascript";
                }
            } elseif ($attr['type'] == "submit") {
                if (array_key_exists("target", $attr)) {
                    $data['target'] = $attr['target'];
                } else {
                    $data['target'] = "Manager.Index";
                }
            }
        }

        foreach ($value as $k => $v) {
            if ($k == "script" && is_string($v)) {
                $data['script'] = $v;
            }

            if ($k == "argument") {
                $args = [];
                if (is_array($v)) {
                    if (isset($v['@attributes'])) {
                        array_push($args, $v['@attributes']);
                    } else {
                        foreach ($v as $arg) {
                            if (isset($arg['@attributes'])) {
                                array_push($args, $arg['@attributes']);
                            }
                        }
                    }
                }

                $data['argument'] = $args;
            }
        }

        return $data;
    }

    public static function compre($code)
    {
        $code = trim($code);
        return $code;
    }
}
