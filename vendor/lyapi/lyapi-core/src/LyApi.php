<?php

namespace LyApi;

use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;

class LyApi{
    
    //输出接口程序最终的数据
    public static function output($priority_output=""){

        //优先输出 用于在接口数据返回前输出一些数据
        if ($priority_output != ''){
            echo $priority_output;
        }

        $Api_Config = require LyApi . '/config/api.php';

        $SERVCIE = $Api_Config['DEFAULT_SERVCIE'];
        $RESPONSE = $Api_Config['DEFAULT_RESPONSE'];

        if(isset($_GET[$SERVCIE])){
            $service = $_GET[$SERVCIE];

            $nsps = explode('.',$service);
            $func =  $nsps[sizeof($nsps) - 1];
            array_pop($nsps);

            $namespace = "APP\\api\\".join('\\',$nsps);

            if(class_exists($namespace)){
                $class = new $namespace;

                if(is_subclass_of($class,'LyApi\core\API')){
                    self::httpStatus(200);
                    $RS = array(
                        'code' => '200',
                        'data' => null,
                        'msg' => ''
                    );
                    $methods = get_class_methods($namespace);
                    $Func_Config = require LyApi . '/config/func.php';

                    //调用初始函数
                    if(in_array($Func_Config['AFTER_FUNC'],$methods)){
                        @$class->$Func_Config['INIT_FUNC']($func);
                    }

                     try{
                         if(in_array($func,$methods)){
                             $RS['data'] = $class->$func();
                         }else{
                             self::httpStatus(400);
                             echo self::CreateRs($RESPONSE,array(
                                 'code' => '400',
                                 'data' => array(),
                                 'msg' => 'Invalid Request: Function does not exist'
                             ));
                             return;
                         }
                     }catch(ClientException $e){
                         self::httpStatus($e->ErrorCode());
                         $RS['code'] = $e->ErrorCode();
                         $RS['msg'] = $e->ErrorMsg();
                         $RS['data'] = array();
                     }catch(ServerException $e){
                         self::httpStatus($e->ErrorCode());
                         $RS['code'] = $e->ErrorCode();
                         $RS['msg'] = $e->ErrorMsg();
                         $RS['data'] = array();
                     }catch(CustomException $e){
                         self::httpStatus(200);
                         echo $e->getMessage();
                         return;
                     }
                     echo self::CreateRs($RESPONSE,$RS);

                    //调用结束函数
                    if(in_array($Func_Config['AFTER_FUNC'],$methods)){
                        @$class->$Func_Config['AFTER_FUNC']();
                    }

                     return;
                }elseif(is_subclass_of($class, 'LyApi\core\VIEW')){
                    $methods = get_class_methods($namespace);
                    $Func_Config = require LyApi . '/config/func.php';

                    //调用初始函数
                    if(in_array($Func_Config['AFTER_FUNC'],$methods)){
                        @$class->$Func_Config['INIT_FUNC']($func);
                    }

                    if(in_array($func,$methods)){
                        echo $class->$func();
                    }

                    //调用结束函数
                    if(in_array($Func_Config['AFTER_FUNC'],$methods)){
                        @$class->$Func_Config['AFTER_FUNC']($func);
                    }

                    return;
                }else{
                    self::httpStatus(400);
                    echo self::CreateRs($RESPONSE,array(
                        'code' => '400',
                        'data' => array(),
                        'msg' => 'Invalid Request: Class does not extend API'
                    ));
                    return;
                }
            }else{
                self::httpStatus(400);
                echo self::CreateRs($RESPONSE,array(
                    'code' => '400',
                    'data' => array(),
                    'msg' => 'Invalid Request: Class does not exist'
                ));
                return;
            }
        }else{
            self::httpStatus(400);
            echo self::CreateRs($RESPONSE,array(
                'code' => '400',
                'data' => array(),
                'msg' => 'Invalid Request: Service does not exist'
            ));
            return;
        }
    }

    private static function CreateRs($response,$value){
        foreach($response as $key => $val){
            if($val == '$data') {
                $response[$key] = $value['data'];
            }else{
                $RS = $val;
                $RS = str_replace('$code',$value['code'],$RS);
                $RS = str_replace('$msg',$value['msg'],$RS);
                $response[$key] = $RS;
            }
        }
        return json_encode($response,JSON_UNESCAPED_UNICODE);
    }

    //处理接口返回的状态码
    private static function httpStatus($num){
        static $http = array (
            100 => "HTTP/1.1 100 Continue",
            101 => "HTTP/1.1 101 Switching Protocols",
            200 => "HTTP/1.1 200 OK",
            201 => "HTTP/1.1 201 Created",
            202 => "HTTP/1.1 202 Accepted",
            203 => "HTTP/1.1 203 Non-Authoritative Information",
            204 => "HTTP/1.1 204 No Content",
            205 => "HTTP/1.1 205 Reset Content",
            206 => "HTTP/1.1 206 Partial Content",
            300 => "HTTP/1.1 300 Multiple Choices",
            301 => "HTTP/1.1 301 Moved Permanently",
            302 => "HTTP/1.1 302 Found",
            303 => "HTTP/1.1 303 See Other",
            304 => "HTTP/1.1 304 Not Modified",
            305 => "HTTP/1.1 305 Use Proxy",
            307 => "HTTP/1.1 307 Temporary Redirect",
            400 => "HTTP/1.1 400 Bad Request",
            401 => "HTTP/1.1 401 Unauthorized",
            402 => "HTTP/1.1 402 Payment Required",
            403 => "HTTP/1.1 403 Forbidden",
            404 => "HTTP/1.1 404 Not Found",
            405 => "HTTP/1.1 405 Method Not Allowed",
            406 => "HTTP/1.1 406 Not Acceptable",
            407 => "HTTP/1.1 407 Proxy Authentication Required",
            408 => "HTTP/1.1 408 Request Time-out",
            409 => "HTTP/1.1 409 Conflict",
            410 => "HTTP/1.1 410 Gone",
            411 => "HTTP/1.1 411 Length Required",
            412 => "HTTP/1.1 412 Precondition Failed",
            413 => "HTTP/1.1 413 Request Entity Too Large",
            414 => "HTTP/1.1 414 Request-URI Too Large",
            415 => "HTTP/1.1 415 Unsupported Media Type",
            416 => "HTTP/1.1 416 Requested range not satisfiable",
            417 => "HTTP/1.1 417 Expectation Failed",
            500 => "HTTP/1.1 500 Internal Server Error",
            501 => "HTTP/1.1 501 Not Implemented",
            502 => "HTTP/1.1 502 Bad Gateway",
            503 => "HTTP/1.1 503 Service Unavailable",
            504 => "HTTP/1.1 504 Gateway Time-out"
        );
        if(array_key_exists($num,$http)){
            header($http[$num]);
        }else{
            header("HTTP/1.1 ". (string)$num ." Undefined");
        }
        return;
    }

}