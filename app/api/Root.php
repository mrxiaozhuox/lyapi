<?php


namespace APP\api;

use APP\DI;
use LyApi\core\classify\VIEW;
use LyApi\core\error\ClientException;
use LyApi\core\request\Request;
use LyApi\LyApi;
use LyApi\tools\Launch;

class Root extends VIEW
{
    
    public function Index()
    {
        $ModeNow = Request::Get('Mode');

        $ChangeTo = '随机数据_' . rand(1000,9999);
        $ChangeNow = Request::Get('Test');
        if($ChangeNow == ''){
            $ChangeNow = '随机数据_XXXX';
        }

        $launchData =  Launch::LaunchApi('APP\api\Demo','Hello');

        if($ModeNow == 'Test'){
            return "
            <html>
            <head>
                <title>LyApi - Welcome</title>
            </head>
            <body>
                <style>
                    .buttons { /* 按钮美化 */
                        width: 100px; /* 宽度 */
                        height: 26px; /* 高度 */
                        border-width: 0px; /* 边框宽度 */
                        border-radius: 3px; /* 边框半径 */
                        background: #1E90FF; /* 背景颜色 */
                        cursor: pointer; /* 鼠标移入按钮范围时出现手势 */
                        outline: none; /* 不显示轮廓线 */
                        font-family: Microsoft YaHei; /* 设置字体 */
                        color: white; /* 字体颜色 */
                        font-size: 17px; /* 字体大小 */
                    }
                    .buttons:hover { /* 鼠标移入按钮范围时改变颜色 */
                        background: #5599FF;
                    }
                    .container {
                        width: 60%;
                        margin: 10% auto 0;
                        background-color: #f0f0f0;
                        padding: 2% 5%;
                        border-radius: 10px
                    }
                </style>

                <center class='container'>
                    <h1>LyApi " . LyApi::$version . " 功能测试</h1>

                    <h3>当前启动模式：" . self::GetMethod() . "</h3>                
                    <h3>参数传递测试：" . $ChangeNow . "&nbsp <a href='?Mode=Test&Test=" . $ChangeTo . "'>切换</a> </h3>
                    <h3>运行地址获取：" . json_encode(self::GetParam()) . "</h3>
                    <h3>接口交互测试：" . json_encode($launchData) . " </h3>
                    <h3>AJAX请求测试： <button onclick='ajaxTest()' class='buttons'>获取数据</button> </h3>

                    <h3><a href='?Mode=Index'>返回主页</a></h3>
                    <p style='color:#a2a2a2;'>测试框架的部分功能，稳定性测试...</p>
                </center>
            </bdoy>

            <script>
                function ajaxTest(){
                    xmlHttp=new XMLHttpRequest();
                    xmlHttp.open('GET','Demo/Hello',true);
                    xmlHttp.send(null);

                    xmlHttp.onreadystatechange=function () {
                        if (xmlHttp.readyState ==4 && xmlHttp.status ==200){
                            alert(xmlHttp.responseText);
                        }
                    }

                }
            </script>
            </html>
            ";
        }else{
            return "
            <html>
                <head>
                    <title>LyApi - Welcome</title>
                </head>
                <style>
                    .container {
                        width: 60%;
                        margin: 10% auto 0;
                        background-color: #f0f0f0;
                        padding: 2% 5%;
                        border-radius: 10px
                    }
                </style>
                <body>
                    <center class='container'>
                        <h1>Welcome LyApi " . LyApi::$version . " ...<h1>
                        <h2>官方站点：<a href='http://lyapi.org'>LyApi.org</a> &nbsp; OR &nbsp; 博客教程：<a href='http://blog.wwsg18.com'>WWBlog</a></h2>
        
                        <h3><a href='?Mode=Test'>前往功能测试页面</a></h3>
                        <p style='color:#a2a2a2;'>当你看到这个页面时，LyApi已经安装成功！</p>
                    </center>
                </bdoy>
            </html>
            ";
        }
    }
}