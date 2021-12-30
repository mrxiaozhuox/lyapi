<?php


namespace Common;

class Encryption
{
    /**
     * Password 模块（集成）
     * 将两个函数集成为一个函数，方便快速调用。
     */
    public static function password($value, $hash = null)
    {
        if ($hash == null) {
            return password_hash($value, PASSWORD_DEFAULT);
        } else {
            return password_verify($value, $hash);
        }
    }

    /**
     * MD5 加密模块（加强版）
     * 本 MD5 会在用户未自动设置盐值时自动生成对应的盐。
     * 使用本函数生成的MD5结果将无法轻易被暴力破解。
     * 当输入结果相同时，自动生成的盐值也会相同。
     */
    public static function md5(string $value, $salt = null)
    {
        if ($salt == null) {

            $len = strlen($value);
            if ($len % 2 == 0) {
                $res = substr($value, ($len / 2), $len);
            } else {
                $res = substr($value, 0, $len / 2);
            }

            $salt = base64_encode($res . "%" . ($len % 2));
        }

        $data = "$" . $salt . "$." . $value;
        return md5($data);
    }

    /**
     * UUID 生成函数
     * 本函数会自动生成一个UUID值（唯一）
     */
    public static function uuid($prefix = "")
    {
        $chars = md5(uniqid(mt_rand(), true));
        
        $uuid = substr($chars, 0, 8) . '-'
            . substr($chars, 8, 4) . '-'
            . substr($chars, 12, 4) . '-'
            . substr($chars, 16, 4) . '-'
            . substr($chars, 20, 12);
        return $prefix . $uuid;
    }
}
