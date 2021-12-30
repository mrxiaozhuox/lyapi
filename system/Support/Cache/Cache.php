<?php


namespace LyApi\Support\Cache;

use LyApi\Exception\CacheException;
use LyApi\Support\Config;
use Predis\Client;
use Predis\Connection\ConnectionException;

/**
 * 本对象仅用于需要 Redis 缓存的情况下
 * 如若已确定使用文件缓存，请使用 FileCache 对象
 */
class Cache
{
    private static $system = 'FILE';
    private static $object = null;
    private static $group = "";

    public static function __loader()
    {
        $system = strtoupper(Config::dotConfig('cache.cache_system'));
        if ($system != null) self::$system = $system;
        switch (self::$system) {
            case 'REDIS':
                $server = Config::dotConfig('cache.redis');
                self::$group = $server['database'];
                self::$object = new Client($server);
                try {
                    self::$object->ping();
                } catch (ConnectionException $e) {
                    throw new CacheException("Redis 服务器连接失败！$e");
                }
                break;
            case 'FILE':
                self::$group = Config::dotConfig("cache.file.default_group");
                self::$object = new FileCache(self::$group);
                break;
            default:
                throw new CacheException("dbtype error.Please check the cache profile!");
        }
    }

    public static function select($group)
    {
        self::$group = $group;
        if (self::$system == "FILE") {
            self::$object = new FileCache($group);
        } else {
        }
    }

    // 读取缓存数据
    public static function get($key)
    {
        return self::$object->get($key);
    }

    // 设置缓存数据
    public static function set($key, $value, $expire = 0)
    {
        if ($expire == 0) {
            return self::$object->set($key, $value);
        } else {
            return self::$object->setex($key, $expire, $value);
        }
    }

    // 数据自增
    public static function inc($key, $add = 1)
    {
        return self::$object->incrby($key, $add);
    }

    // 检查值是否存在
    public static function has($key)
    {
        switch (self::$system) {
            case 'REDIS':
                return self::$object->exists($key);
            default:
                return self::$object->has($key);
        }
    }

    // 删除缓存数据
    public static function delete($key)
    {
        switch (self::$system) {
            case 'REDIS':
                return self::$object->del($key);
            default:
                return self::$object->delete($key);
        }
    }

    // 清空所有数据信息
    public static function clean()
    {
        switch (self::$system) {
            case 'REDIS':
                return self::$object->flushdb();
            default:
                return self::$object->clean();
        }
    }

    // 切换数据库类型
    public static function system($to)
    {
        $to = strtoupper($to);
        switch ($to) {
            case 'REDIS':
                self::$system = "REDIS";
                break;
            case 'FILE':
                self::$system = "FILE";
                break;
            default:
                throw new CacheException('dbtype error.');
        }
    }
}
