<?php

namespace APP\api;

use LyApi\core\API;
use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;

class Demo extends API{

    //正常的数据返回
    public function User(){
        return array(
            'username' => 'mr小卓X',
            'password' => '12345678'
        );
    }

    //出现客户端错误
    public function ClientError(){
        throw new ClientException('Error Message',1);
    }

    //出现服务端错误
    public function ServerError(){
        throw new ServerException('Error Message',1);
    }

    //自定义返回内容
    public function Custom(){
        throw new CustomException('This is a Custom msg');
    }
}