# LyApi

> 轻量级API开发框架：高效、稳定、强大

## 安装

使用Composer:

> composer create-project mrxzx/lyapi

手动安装:

> 暂不支持...

## 开始

接口入口:
> http://localhost/path/public

    {
        "code":"400",
        "data":[],
        "msg":"Service does not exist"
        }

测试接口:
> http://localhost/path/public?service=Demo.User

    {
        "code":"200",
        "data":{
            "username":"mr小卓X",
            "password":"12345678"
        },
        "msg":""
    }
    
## 文档
LyApi开发文档:编写中

## 项目
快速开发框架:[Lolly][1]

## 问题
如发现问题，请提交[Issue][2]。

[1]: http://lolly.wwg18.com
[2]: https://gitee.com/wwsg18/LyApi-Core/issues