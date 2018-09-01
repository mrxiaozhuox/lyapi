<?php

namespace LyApi;

use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\ServerException;

class LyApi{
    public static function output(){
        $Api_Config = require LyApi . '/config/api.php';

        $SERVCIE = $Api_Config['DEFAULT_SERVCIE'];
        $RESPONSE = $Api_Config['DEFAULT_RESPONSE'];

        if(isset($_GET[$SERVCIE])){
            $service = $_GET[$SERVCIE];

            $nsps = explode('.',$service);
            $func =  $nsps[sizeof($nsps) - 1];
            array_pop($nsps);

            $namespace = "APP\\Api\\".join('\\',$nsps);

            if(class_exists($namespace)){
                $class = new $namespace;

                if(is_subclass_of($class,'LyApi\core\API')){
                    $RS = array(
                        'code' => '200',
                        'data' => null,
                        'msg' => ''
                    );
                     try{
                         $methods = get_class_methods($namespace);
                         if(in_array($func,$methods)){
                             $RS['data'] = $class->$func();
                         }else{
                             echo self::CreateRs($RESPONSE,array(
                                 'code' => '400',
                                 'data' => array(),
                                 'msg' => 'Invalid Request: Function does not exist'
                             ));
                             return;
                         }
                     }catch(ClientException $e){
                         $RS['code'] = $e->ErrorCode();
                         $RS['msg'] = $e->ErrorMsg();
                         $RS['data'] = array();
                     }catch(ServerException $e){
                         $RS['code'] = $e->ErrorCode();
                         $RS['msg'] = $e->ErrorMsg();
                         $RS['data'] = array();
                     }catch(CustomException $e){
                         echo $e->getMessage();
                         return;
                     }
                    echo self::CreateRs($RESPONSE,$RS);
                     return;
                }else{
                    echo self::CreateRs($RESPONSE,array(
                        'code' => '400',
                        'data' => array(),
                        'msg' => 'Invalid Request: Class does not extend API'
                    ));
                    return;
                }
            }else{
                echo self::CreateRs($RESPONSE,array(
                    'code' => '400',
                    'data' => array(),
                    'msg' => 'Invalid Request: Class does not exist'
                ));
                return;
            }
        }else{
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
}