<?php

// 本代码为示例代码，学习完毕后建议立刻删除！

namespace APP\api;

use APP\DI;
use LyApi\core\API;
use LyApi\core\error\ClientException;
use LyApi\core\request\Cookie;
use LyApi\core\request\Request;

class Demo extends API {

    /**
     * service Demo.Hello
     * introduce 简单的Hello World
     */
    public function Hello(){
        return 'Hello World';
    }


    /**
     * service Demo.Cahce
     * introduce 设置缓存并读取
     */
    public function Cache(){
        $cache = DI::FileCache('Demo');
        //设置缓存
        $cache->set('username','mrxzx');
        $cache->set('password','123456');
        //读取缓存
        $username = $cache->get('username');
        $password = $cache->get('password');
        //清除缓存
        $cache->clean();
        //返回数据
        return array(
            'username' => $username,
            'password' => $password
        );
    }

    /**
     * service Demo.Cookie
     * introduce 设置cookie并读取
     */
    public function Cookie(){
        $cookie = new Cookie('/');
        //设置Cookie
        $cookie->Set('username','mrxzx');
        $cookie->Set('password','123456');
        //获取Cookie
        $username = $cookie->Get('username');
        $password = $cookie->Get('password');
        //返回数据
        return array(
            'username' => $username,
            'password' => $password
        );
    }

    /**
     * service Demo.Register
     * introduce 模拟简单注册
     */
    public function Register(){
        //获取Get数据
        $username = Request::Get('username');
        $password = Request::Get('password');

        if($username != '' && $password != ''){
            //使用缓存来做简单的注册
            $cache = DI::FileCache('Demo');
            $data = $cache->get('user');
            //判断缓存中是否有用户数据
            if($data != null){
                //如果有则加入数据
                $data[$username] = $password;
                $cache->set('user',$data);
            }else{
                //没有则直接存入数据
                $cache->set('user',[$username => $password]);
            }
            return true;
        }else{
            throw new ClientException('参数不完整',0);
        }
    }

    /**
     * service Demo.Login
     * introduce 模拟简单登录
     */
    public function Login(){
        //获取Get数据
        $username = Request::Get('username');
        $password = Request::Get('password');

        if($username != '' && $password != ''){
            //读取缓存
            $cache = DI::FileCache('Demo');
            $data = $cache->get('user');
            if(isset($data[$username]) && $data[$username] == $password){
                return true;
            }else{
                return false;
            }
        }else{
            throw new ClientException('参数不完整',0);
        }
    }

    /**
     * service Demo.Test
     * introduce 自行测试代码
     */
    public function Test(){
        // Write your code ......
    }
}