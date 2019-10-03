<?php

namespace LyApi;

use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;
use LyApi\core\error\OtherException;
use LyApi\tools\Config;

class LyApi
{

    //LyAPI信息：
    public static $version = "1.6.5";

    //输出接口程序最终的数据
    private static function output($other_data = array(), $priority_output = "", $http_status_set = true)
    {

        //优先输出 用于在接口数据返回前输出一些数据 (慎用)
        if ($priority_output != '') {
            // echo $priority_output;
        }

        $Api_Config = require LyApi . '/config/api.php';


        $SERVICE = $Api_Config['GET_METHOD_SETTING']['DEFAULT_SERVICE'];
        $RESPONSE = $Api_Config['DEFAULT_RESPONSE'];
        $METHODS = $Api_Config['ACCESS_METHODS'];

        if (isset($_REQUEST[$SERVICE]) || $METHODS == 'URL') {

            $nsps = array();

            if ($METHODS != 'URL') {
                $service = $_REQUEST[$SERVICE];
                $nsps = explode($Api_Config['GET_METHOD_SETTING']['SERVICE_SEGMENTATION'], $service);
            } else {
                //处理路由，解析URL

                $AccessUri =  $_SERVER['REQUEST_URI'];
                
                if(strrpos($AccessUri,"?") != false){
                    $AccessUri = substr($AccessUri,0,strrpos($AccessUri,"?"));
                }
                $AccessArray = explode("/",$AccessUri);
                $AccessArray = array_filter($AccessArray);
                $DelNum = $Api_Config['URL_METHOD_SETTING']['EFFECTIVE_POSITION'];
                array_splice($AccessArray,0,$DelNum);
                if(sizeof($AccessArray) == 0){
                   array_push($nsps,$Api_Config['URL_METHOD_SETTING']['DEFAULT_CLASS'],$Api_Config['URL_METHOD_SETTING']['INDEX_FUNCTION']);
                }elseif(sizeof($AccessArray) == 1){
                    array_push($nsps,$Api_Config['URL_METHOD_SETTING']['DEFAULT_CLASS'],$AccessArray[0]);
                }else{
                    $nsps = $AccessArray;
                }
                // var_dump($nsps);
            }

            $func =  $nsps[sizeof($nsps) - 1];
            array_pop($nsps);

            $namespace = "APP\\api\\" . join('\\', $nsps);
            if (class_exists($namespace)) {
                $class = new $namespace;

                if (is_subclass_of($class, 'LyApi\core\classify\API')) {
                    self::httpStatus(200, $http_status_set);
                    $RS = array(
                        'code' => '200',
                        'data' => null,
                        'msg' => ''
                    );
                    $methods = get_class_methods($namespace);
                    $Func_Config = require LyApi . '/config/func.php';

                    //调用初始函数
                    if (in_array($Func_Config['INIT_FUNC'], $methods)) {
                        @$class->$Func_Config['INIT_FUNC']($func);
                    }

                    try {
                        if (in_array($func, $methods)) {

                            @$Func_Return = $class->$func('API', $_REQUEST);
                            if (true) {

                                $Cust_Code = array_search('$code', $RESPONSE);
                                $Cust_Message = array_search('$msg', $RESPONSE);

                                //处理自定义函数数据
                                if (array_key_exists('FUNCITON_SET_DATA', $Func_Config)) {

                                    $Func_SetData = $Func_Config['FUNCITON_SET_DATA'];

                                    $Func_SetCode = $Func_SetData['CUSTON_SUCCESS_CODE'];
                                    $Func_SetDatas = $Func_SetData['CUSTON_SUCCESS_DATA'];
                                    $Func_SetMessage = $Func_SetData['CUSTON_SUCCESS_MESSAGE'];
                                    $Func_SetCustKey = $Func_SetData['CUSTON_SUCCESS_CUSTOM_KEYS'];
                                    $Func_HiddenKey = $Func_SetData['CUSTON_SUCCESS_HIDDEN_KEYS'];

                                    //通过各种数据来设置code 和 msg 优先级 (2)
                                    if (array_key_exists($func, $class->API_Function_Data)) {
                                        if (is_array($class->API_Function_Data[$func])) {
                                            if (array_key_exists($Func_SetCode, $class->API_Function_Data[$func])) {
                                                $RS['code'] = $class->API_Function_Data[$func][$Func_SetCode];
                                            }

                                            if (array_key_exists($Func_SetMessage, $class->API_Function_Data[$func])) {
                                                $RS['msg'] = $class->API_Function_Data[$func][$Func_SetMessage];
                                            }

                                            if (array_key_exists($Func_SetDatas, $class->API_Function_Data[$func])) {
                                                $RS['data'] = $class->API_Function_Data[$func][$Func_SetDatas];
                                            }

                                            if (array_key_exists($Func_SetCustKey, $class->API_Function_Data[$func])) {
                                                $Func_CustKeys = $class->API_Function_Data[$func][$Func_SetCustKey];
                                                if (is_array($Func_CustKeys)) {
                                                    foreach ($Func_CustKeys as $key => $value) {
                                                        $RS[array_search('$' . $key, $RESPONSE)] = $value;
                                                    }
                                                }
                                            }

                                            // 将被隐藏的Key删除
                                            if (array_key_exists($Func_HiddenKey, $class->API_Function_Data[$func])) {
                                                $Func_Hidden = $class->API_Function_Data[$func][$Func_HiddenKey];
                                                if (is_array($Func_Hidden)) {
                                                    foreach ($Func_Hidden as $key => $value) {
                                                        unset($RESPONSE[array_search('$' . $value, $RESPONSE)]);
                                                    }
                                                }
                                            }
                                        }
                                    } elseif (array_key_exists('all', $class->API_Function_Data)) {
                                        // 处理全局函数数据
                                        if (is_array($class->API_Function_Data['all'])) {
                                            if (array_key_exists($Func_SetCode, $class->API_Function_Data['all'])) {
                                                $RS['code'] = $class->API_Function_Data['all'][$Func_SetCode];
                                            }

                                            if (array_key_exists($Func_SetMessage, $class->API_Function_Data['all'])) {
                                                $RS['msg'] = $class->API_Function_Data['all'][$Func_SetMessage];
                                            }

                                            if (array_key_exists($Func_SetDatas, $class->API_Function_Data['all'])) {
                                                $RS['data'] = $class->API_Function_Data['all'][$Func_SetDatas];
                                            }

                                            if (array_key_exists($Func_SetCustKey, $class->API_Function_Data['all'])) {
                                                $Func_CustKeys = $class->API_Function_Data['all'][$Func_SetCustKey];
                                                if (is_array($Func_CustKeys)) {
                                                    foreach ($Func_CustKeys as $key => $value) {
                                                        $RS[array_search('$' . $key, $RESPONSE)] = $value;
                                                    }
                                                }
                                            }

                                            // 将被隐藏的Key删除
                                            if (array_key_exists($Func_HiddenKey, $class->API_Function_Data['all'])) {
                                                $Func_Hidden = $class->API_Function_Data['all'][$Func_HiddenKey];
                                                if (is_array($Func_Hidden)) {
                                                    foreach ($Func_Hidden as $key => $value) {
                                                        unset($RESPONSE[array_search('$' . $value, $RESPONSE)]);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }

                                // 处理返回数组的一些特别信息 优先级（1）
                                if (is_array($Func_Return)) {

                                    if (array_key_exists('#' . $Cust_Code, $Func_Return)) {
                                        $RS['code'] = $Func_Return['#' . $Cust_Code];
                                        unset($Func_Return['#' . $Cust_Code]);
                                    }

                                    if (array_key_exists('#' . $Cust_Message, $Func_Return)) {
                                        $RS['msg'] = $Func_Return['#' . $Cust_Message];
                                        unset($Func_Return['#' . $Cust_Message]);
                                    }

                                    foreach ($Func_Return as $Return_Key => $Return_Val) {
                                        if (substr($Return_Key, 0, 1) == '#') {
                                            $Custom_DValue = '$' . substr($Return_Key, 1);
                                            if (in_array($Custom_DValue, $RESPONSE)) {
                                                $Custom_DataOne = array_search($Custom_DValue, $RESPONSE);
                                                $RS[$Custom_DataOne] = $Return_Val;
                                                unset($Func_Return[$Return_Key]);
                                            }
                                        }
                                    }
                                }
                            }

                            //处理返回值为NULL的情况 优先级 (0)
                            if ($Func_Return != null) {
                                $RS['data'] = $Func_Return;
                            }
                        } else {

                            $function_not_find = $Api_Config['ERROR_MESSAGE']['function_not_find'];

                            self::httpStatus($function_not_find['code'], $http_status_set);
                            echo self::CreateRs($RESPONSE, $function_not_find, $other_data);
                            return $function_not_find['code'];
                        }
                        //捕获异常
                    } catch (ClientException $e) {
                        self::httpStatus($e->ErrorCode(), $http_status_set);
                        $RS['code'] = $e->ErrorCode();
                        $RS['msg'] = $e->ErrorMsg();
                        $RS['data'] = array();
                    } catch (ServerException $e) {
                        self::httpStatus($e->ErrorCode(), $http_status_set);
                        $RS['code'] = $e->ErrorCode();
                        $RS['msg'] = $e->ErrorMsg();
                        $RS['data'] = array();
                    } catch (OtherException $e) {
                        self::httpStatus($e->ErrorCode(), $http_status_set);
                        $RS['code'] = $e->ErrorCode();
                        $RS['msg'] = $e->ErrorMsg();
                        $RS['data'] = array();
                    } catch (CustomException $e) {
                        self::httpStatus(200, $http_status_set);
                        echo $e->getMessage();
                        return 200;
                    }

                    self::httpStatus($RS['code'], $http_status_set);
                    echo self::CreateRs($RESPONSE, $RS, $other_data);

                    //调用结束函数
                    if (in_array($Func_Config['AFTER_FUNC'], $methods)) {
                        @$class->$Func_Config['AFTER_FUNC']();
                    }

                    return $RS['code'];
                } elseif (is_subclass_of($class, 'LyApi\core\classify\VIEW')) {
                    // 处理VIEW视图渲染

                    $methods = get_class_methods($namespace);
                    $Func_Config = require LyApi . '/config/func.php';

                    //调用初始函数
                    if (in_array($Func_Config['INIT_FUNC'], $methods)) {
                        @$class->$Func_Config['INIT_FUNC']($func);
                    }

                    if (in_array($func, $methods)) {
                        try{
                            echo $class->$func('API', $_REQUEST);
                        }catch(ClientException $e){
                            echo self::ShowError($e->ErrorCode());
                        }catch(ServerException $e){
                            echo self::ShowError($e->ErrorCode());
                        }catch(OtherException $e){
                            echo self::ShowError($e->ErrorCode());
                        }catch(CustomException $e){
                            echo json_encode([
                                'code' => 200,
                                'data' => $e->ErrorMsg(),
                                'msg' => ''
                            ]);
                        }
                    }else{
                        echo self::ShowError(404);
                    }

                    //调用结束函数
                    if (in_array($Func_Config['AFTER_FUNC'], $methods)) {
                        @$class->$Func_Config['AFTER_FUNC']($func);
                    }

                    return 200;
                } else {

                    $class_not_extend = $Api_Config['ERROR_MESSAGE']['class_not_extend'];

                    self::httpStatus($class_not_extend['code'], $http_status_set);
                    echo self::CreateRs($RESPONSE, $class_not_extend, $other_data);
                    return $class_not_extend['code'];
                }
            } else {

                $class_not_find = $Api_Config['ERROR_MESSAGE']['class_not_find'];

                self::httpStatus($class_not_find['code'], $http_status_set);
                echo self::CreateRs($RESPONSE, $class_not_find, $other_data);
                return $class_not_find['code'];
            }
        } else {

            $service_not_find = $Api_Config['ERROR_MESSAGE']['service_not_find'];

            self::httpStatus($service_not_find['code'], $http_status_set);
            echo self::CreateRs($RESPONSE, $service_not_find, $other_data);
            return $service_not_find['code'];
        }
    }

    private static function CreateRs($response, $value, $other = array())
    {
        // var_dump($value); 
        foreach ($response as $key => $val) {
            if ($val == '$data') {
                $response[$key] = $value['data'];
            } else if ($val == '$code') {
                $response[$key] = $value['code'];
            } else if ($val == '$msg') {
                $response[$key] = $value['msg'];
            } else {
                if (array_key_exists($key, $value)) {
                    $response[$key] = $value[$key];
                } elseif (array_key_exists(substr($val, 1), $other)) {
                    $response[$key] = $other[substr($val, 1)];
                } else {
                    $response[$key] = $val;
                }
            }
        }
        return json_encode($response, JSON_UNESCAPED_UNICODE);
    }

    //处理接口返回的状态码
    private static function httpStatus($num, $use_header = true)
    {

        if (!$use_header) {
            return;
        }

        static $http = array(
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
        if (array_key_exists($num, $http)) {
            header($http[$num]);
        } else {
            header("HTTP/1.1 " . (string) $num . " Undefined");
        }
        return;
    }

    private static function ShowError($code = 404){
        $DirPath = LyApi . '/app/view/error/';
        if(is_file($DirPath . $code . '.html')){
            return file_get_contents($DirPath . $code . '.html');
        }else{
            return file_get_contents($DirPath . 'default.html');
        }
        
    }

    // 普通对象函数
    private $APP_Config = [];
    private $Response_Code = 400;

    public function __construct($Config = [])
    {
        // 对配置进行处理
        if (!array_key_exists("Priority_Output", $Config)) {
            $Config['Priority_Output'] = '';
        }
        if (!array_key_exists("Http_Status_Set", $Config)) {
            $Config['Http_Status_Set'] = true;
        }
        if (!array_key_exists("Other_Data", $Config)) {
            $Config['Other_Data'] = [];
        }

        $this->APP_Config = $Config;
    }

    // 运行接口程序
    public function Run()
    {
        $Config = $this->APP_Config;
        $RespCode = self::output($Config['Other_Data'], $Config['Priority_Output'], $Config['Http_Status_Set']);
        $this->Response_Code = $RespCode;
        return $RespCode;
    }
}