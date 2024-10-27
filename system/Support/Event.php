<?php

// -+----------------------------+-
// LyApi [ V2.0 ] - 全新开发版本
// 事件处理类 - Event
// -+----------------------------+-

namespace LyApi\Support;

use LyApi\Support\DataStruct\Stack;

class Event
{
    private static $events = [];
    private static $interrupt = false;

    public static function on($name, $closure, $sign = null)
    {
        if (!isset(self::$events[$name])) {
            self::$events[$name] = Stack::create();
        }

        if (is_object($closure)) {
            self::$events[$name]->push([$closure, $sign]);
        }
    }

    public static function trigger($name, ...$args)
    {
        self::$interrupt = false;

        $values = [];

        if (isset(self::$events[$name])) {
            while (!self::$events[$name]->empty()) {

                if (self::$interrupt) {
                    self::$interrupt = false;
                    break;
                }

                $closure = self::$events[$name]->top();

                $sign = $closure[1];
                $closure = $closure[0];

                if (is_object($closure)) {
                    $ret = @$closure(...$args);

                    if ($sign == null || $sign == "") {
                        $sign = substr(md5(rand(11111, 99999) . uniqid()), 3, 8);
                    }
                    $values[$sign] = $ret;
                }
                self::$events[$name]->pop();
            }
        }

        return $values;
    }


    public static function interrupt()
    {
        self::$interrupt = true;
    }
}
