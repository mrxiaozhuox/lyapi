<?php

namespace Extend\Dorea\library;

use Common\Api;
use LyApi\Core\Request;
use LyApi\Core\Response;
use LyApi\Support\Database\ConnException;

class Controller
{
    public static function Api(Request $request,Response $response): array
    {

        $server = $request->form("server");
        $password = $request->form("password");

        $query = $request->form("query");

        if ($server == null || $password == null || $query == null ) {
            return Api::error(400, "参数不足");
        }

        if (!\ExtFunc::devlogin()) {
            return Api::error(401, "账号未登陆");
        }


        $url = $server;
        if (substr($server,0,4) != "http") {
            $url = "http://" . $server . "/";
        }
        if (substr($url,length($url) - 1,1) != "/") {
            $url .= "/";
        }

        try {
            $db = new Database($url, $password);
        } catch (ConnException $e) {
            return Api::error(400, $e->getMessage());
        }

        $res = $db->execute($query, [
            "style" => "doson",
        ]);
        if ($res["status"]) {
            return Api::simple($res["data"]["reply"]);
        } else {
            return Api::error(400,"Dorea: " . $res["message"]);
        }
    }
}