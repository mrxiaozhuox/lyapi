<?php

namespace LyApi\Support\Cache;

use LyApi\Exception\Cache\FileCacheException;

class FileCache
{
    private $dir;

    /**
     * 初始化缓存设置.
     */
    public function __construct(string $group = "default")
    {
        $dir = ROOT_PATH . '/runtime/cache/' . $group;

        if (!is_dir($dir)) {
            if (!mkdir($dir, 0777, true)) {
                throw new FileCacheException("Can't read or write the cache directory!");
            }
        }

        $this->dir = $dir;
    }

    /**
     * 设置一个缓存值.
     *
     * @param string $key 键名
     * @param int $add 自增值
     *
     * @return boolean
     */
    public function incrby($key, $add)
    {
        if ($this->has($key)) {
            $data = $this->get($key);
            if (is_numeric($data)) {
                $exp = $this->surexp($key);
                if ($exp >= 0) {
                    return $this->set($key, $data + $add, $exp);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * 设置一个缓存值.
     *
     * @param string $key 键名
     * @param int $expire 过期时间
     * @param string $data 数据
     *
     * @return boolean
     */
    public function setex($key, $expire, $value)
    {
        return $this->set($key, $value, $expire);
    }

    /**
     * 设置一个缓存值.
     *
     * @param string $key 键名
     * @param string $data 数据
     * @param int $expire 过期时间
     *
     * @return boolean
     */
    public function set($key, $value, $expire = null)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';

        if ($expire != null) {
            $duetime = time() + $expire;
            $datas = array(
                'data' => $value,
                'duetime' => $duetime
            );
        } else {
            $datas = array(
                'data' => $value
            );
        }

        $datas = json_encode($datas, JSON_UNESCAPED_UNICODE);
        $datas = base64_encode($datas);
        if (file_put_contents($filename, $datas)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 获取一个缓存值.
     *
     * @param string $key 键名
     *
     * @return string
     */
    public function get($key)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if (is_file($filename)) {
            $datas = file_get_contents($filename);
            $datas = base64_decode($datas);
            $datas = json_decode($datas, true);
            if (array_key_exists('duetime', $datas)) {
                if ($datas['duetime'] > time()) {
                    return $datas['data'];
                } else {
                    @unlink($filename);
                    return '';
                }
            } else {
                return $datas['data'];
            }
        } else {
            return '';
        }
    }

    /**
     * 获取剩余时间值.
     *
     * @param string $key 键名
     *
     * @return int
     */
    public function surexp($key)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        if (is_file($filename)) {
            $datas = file_get_contents($filename);
            $datas = base64_decode($datas);
            $datas = json_decode($datas, true);
            if (array_key_exists('duetime', $datas)) {
                if ($datas['duetime'] > time()) {
                    return $datas['duetime'] - time();
                } else {
                    @unlink($filename);
                    return -1;
                }
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /**
     * 判断一个缓存键是否存在.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function has($key)
    {
        if ($this->get($key) == '') {
            return false;
        } else {
            return true;
        }
    }


    /**
     * 删除一个缓存键.
     *
     * @param string $key 键名
     *
     * @return boolean
     */
    public function delete($key)
    {
        $filename = $this->dir . '/' . md5($key) . '.lyc';
        return @unlink($filename);
    }


    /**
     * 清空所有缓存.
     */
    public function clean()
    {
        $dirs = scandir($this->dir);
        foreach ($dirs as $dir) {
            if ($dir != '.' && $dir != '..') {
                @unlink($this->dir . '/' . $dir);
            }
        }
    }
}
