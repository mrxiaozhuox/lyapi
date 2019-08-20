<?php

namespace LyApi\core\classify;

class API
{

    //储存函数数据
    public $API_Function_Data = [];

    //设置函数数据
    public function SetFuncData($data, $funcname = 'all')
    {
        if (strtolower($funcname) == 'all') {
            $this->API_Function_Data['all'] = $data;
        } else {
            $this->API_Function_Data[$funcname] = $data;
        }
    }

    //读取函数数据
    public function GetFuncData($funcname = 'all')
    {
        if (strtolower($funcname) == 'all') {
            return $this->API_Function_Data['all'];
        } else {
            return $this->API_Function_Data[$funcname];
        }
    }

    //设置隐藏数据
    public function HiddenKeys($keys, $funcname = 'all')
    {

        $Func_Config = require LyApi . '/config/func.php';
        if (array_key_exists('FUNCITON_SET_DATA', $Func_Config)) {
            if (array_key_exists('FUNCITON_SET_DATA', $Func_Config)) {
                $Func_Setting = $Func_Config['FUNCITON_SET_DATA'];
                if (array_key_exists('CUSTON_SUCCESS_HIDDEN_KEYS', $Func_Setting)) {

                    if (strtolower($funcname) == 'all') {
                        $Hidden_List = $this->API_Function_Data['all'][$Func_Setting['CUSTON_SUCCESS_HIDDEN_KEYS']];
                    } else {
                        $Hidden_List = $this->API_Function_Data[$funcname][$Func_Setting['CUSTON_SUCCESS_HIDDEN_KEYS']];
                    }

                    if (!is_array($Hidden_List)) {
                        if (is_array($keys)) {
                            $Hidden_List = $keys;
                        } else {
                            $Hidden_List = [$keys];
                        }
                    } else {
                        if (is_array($keys)) {
                            $Hidden_List + $keys;
                        } else {
                            array_push($Hidden_List, $keys);
                        }
                    }

                    $this->API_Function_Data[$funcname][$Func_Setting['CUSTON_SUCCESS_HIDDEN_KEYS']] = $Hidden_List;

                }
            }
        }
    }
}
