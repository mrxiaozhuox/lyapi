<?php

namespace LyApi\core\design;

class Register
{

    protected static $Mounts = [];

    //存放对象到注册树
    public static function Set($name, $obj)
    {
        self::$Mounts[$name] = $obj;
    }

    //在注册树中读取对象
    public static function Get($name)
    {
        if (array_key_exists($name, self::$Mounts)) {
            return self::$Mounts[$name];
        } else {
            return '';
        }
    }

    //删除注册树中的一个对象
    public static function Delete($name)
    {
        if (array_key_exists($name, self::$Mounts)) {
            unset(self::$Mounts[$name]);
            return true;
        } else {
            return false;
        }
    }

    //清空注册树中的所有对象
    public static function Clean()
    {
        self::$Mounts = [];
    }

    //获取注册树中的对象数量
    public static function Count()
    {
        return sizeof(self::$Mounts);
    }

    //获取所有存放在注册树中的对象对应名
    public static function Lists()
    {
        return array_keys(self::$Mounts);
    }
}
