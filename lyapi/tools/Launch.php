<?php

namespace LyApi\tools;

use LyApi\core\error\ClientException;
use LyApi\core\error\CustomException;
use LyApi\core\error\OtherException;
use LyApi\core\error\ServerException;

class Launch
{

    public static function LaunchApi($NameSpace, $Function, $ArgsData = [], $LaunchType = 'Function')
    {
        if (class_exists($NameSpace)) {
            $Methods = get_class_methods($NameSpace);
            if (in_array($Function, $Methods)) {
                $Class = new $NameSpace;
                if (!is_subclass_of($Class, 'LyApi\core\classify\API')) {
                    return [];
                }

                $ReturnList = [
                    'code' => 200,
                    'data' => [],
                    'message' => ''
                ];

                try {
                    $ReturnData = $Class->$Function($LaunchType, $ArgsData);

                    $Api_Config = require LyApi . '/config/api.php';
                    $RESPONSE = $Api_Config['DEFAULT_RESPONSE'];

                    $Cust_Code = array_search('$code', $RESPONSE);
                    $Cust_Message = array_search('$msg', $RESPONSE);

                    if (is_array($ReturnData)) {


                        if (array_key_exists('#' . $Cust_Code, $ReturnData)) {
                            $ReturnList['code'] = $ReturnData['#' . $Cust_Code];
                            unset($ReturnData['#' . $Cust_Code]);
                        }

                        if (array_key_exists('#' . $Cust_Message, $ReturnData)) {
                            $ReturnList['message'] = $ReturnData['#' . $Cust_Message];
                            unset($ReturnData['#' . $Cust_Message]);
                        }

                        foreach ($ReturnData as $Return_Key => $Return_Val) {
                            if (substr($Return_Key, 0, 1) == '#') {
                                $Custom_DValue = '$' . substr($Return_Key, 1);
                                if (in_array($Custom_DValue, $RESPONSE)) {
                                    $Custom_DataOne = array_search($Custom_DValue, $RESPONSE);
                                    $ReturnList[$Custom_DataOne] = $Return_Val;
                                    unset($ReturnData[$Return_Key]);
                                }
                            }
                        }
                    }
                } catch (ClientException $e) {
                    $ReturnList['code'] = $e->getCode();
                    $ReturnList['message'] = $e->getCode();
                    return $ReturnList;
                } catch (ServerException $e) {
                    $ReturnList['code'] = $e->getCode();
                    $ReturnList['message'] = $e->getCode();
                    return $ReturnList;
                } catch (OtherException $e) {
                    $ReturnList['code'] = $e->getCode();
                    $ReturnList['message'] = $e->getCode();
                    return $ReturnList;
                } catch (CustomException $e) {
                    return $e->getMessage();
                }

                //接口没收到异常则处理一些数据
                $ReturnList['data'] = $ReturnData;
                return $ReturnList;

            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public static function LaunchShell($Command)
    {
        // exec 函数默认是被禁用的，需要手动开启
        exec($Command, $output);
        return $output;
    }
}
