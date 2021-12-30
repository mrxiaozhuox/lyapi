<?php

/**
 * 大家好：这是我的一个新项目： Dorea-DB
 * 它是一款轻量级的Key-Value数据库（使用 Rust 开发）
 * 如果您正在做某些 娱乐、练习 项目的话，希望您可以尝试一下我的这个数据库系统！
 * 它与 LyApi 框架是高度集成的！您能非常方便的在 LyApi 中引入这套数据库系统！
 * Website: https://dorea.mrxzx.info/
 * 如果您喜欢，请在 GitHub 上帮我点一个 Star 吧！
 * YuKun Liu <mrxzx.info@gmail.com>
 */

namespace Extend\Dorea\library;

use LyApi\Support\Database\ConnException;
use WpOrg\Requests\Exception;
use WpOrg\Requests\Exception\Http;
use WpOrg\Requests\Requests;

class Database
{

    private $url = "";
    private $password = "";
    private $token = "";

    // 默认数据库
    private $group_name = "default";

    /**
     * @throws ConnException
     */
    public function __construct($url, $password, $default_db = "default") {

        $this->url = $url;
        if (substr($this->url, length($this->url) - 1, 1) == "/") {
            $this->url = substr($this->url, 0, length($this->url) - 1);
        }


        $this->password = $password;
        $this->group_name = $default_db;

        try {
            $response = Requests::post($url);
        } catch (Exception $e) {
            throw new ConnException("[DoreaDB] 服务器无法通讯！");
        }
        if ($response->status_code != 200) {
            throw new ConnException("[DoreaDB] 服务器无法通讯！");
        }

        if (!$this->updateToken()) {
            throw new ConnException("[DoreaDB] 服务器验证失败！");
        }
    }

    private function updateToken(): bool
    {
        $authPath = $this->url . "/auth";
        $response = Requests::post($authPath, [], [
            "password" => $this->password,
        ]);
        if ($response->status_code == 200) {
            $data = json_decode($response->body, true);
            $token = $data["data"]["token"];
            $this->token = $token;
            return true;
        }
        return false;
    }

    public function execute($command, $options = []): ?array
    {
        $style = "json";
        if (key_exists("style", $options) && strtoupper($options["style"]) == "DOSON") {
            $style = "doson";
        }

        $execPath = $this->url . "/@" . $this->group_name . "/execute";
        $response = Requests::post($execPath, [
            "authorization" => "Bearer " . $this->token,
        ], [
            "query" => $command,
            "style" => $style,
        ]);

        if ($response->status_code == 401) {
            if ($this->updateToken()) {
                return $this->execute($command);
            } else {
                return null;
            }
        }

        $meta_data = json_decode($response->body, true);
        return [
            "status" => $meta_data["alpha"] == "OK",
            "data" => $meta_data["data"],
            "message" => $meta_data["message"],
        ];
    }

    public function get($key, $useObject = true) {
        $res = $this->execute("get " . $key);
        if ($res["status"]) {
            $meta = $res["data"]["reply"];
            return Value::parser($meta, $useObject);
        }
        return null;
    }

    public function set($key, $value): bool {

        $stringify = Value::stringify($value);
        if ($stringify == "") {
            return false;
        }

        $fin = base64_encode($stringify);
        $res = $this->execute("set " . $key . " b:" . $fin . ":");

        return $res["status"];
    }

    public function delete($key): bool {
        $res = $this->execute("delete " . $key);
        return $res["status"];
    }

    public function clean(): bool {
        $res = $this->execute("clean");
        return $res["status"];
    }

    public function select($db_name) {
        $this->group_name = $db_name;
    }
}