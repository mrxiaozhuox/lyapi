# LyAPI
[![Travis](https://img.shields.io/badge/Language-PHP-blue.svg)](http://php.net)
[![Travis](https://img.shields.io/badge/License-MIT-brightgreen.svg)](https://mit-license.org)
[![Travis](https://img.shields.io/badge/Version-V1.2.4-orange.svg)](http://lyapi.wwsg18.com)
[![Travis](https://img.shields.io/badge/Core_Version-V1.5.6-blue.svg)](http://lyapi.wwsg18.com)

## 安装方法

使用Composer构建LyApi项目:

    $ composer create-project mrxzx/lyapi

## 简单Demo

    // ./app/api/Demo.php
    <?php

    namespace APP\api;

    use LyApi\core\API;

    class Demo extends API{
        public function User(){
            return array(
                'username' => 'mr小卓X',
                'password' => '12345678'
            );
        }
    }

#### 运行结果:

    {
        "code":"200",
        "data":{
            "username":"mr小卓X",
            "password":"12345678"
        },
        "msg":""
    }

## 图片演示

![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI1.png)
![avatar](http://wwsg-img.bj.bcebos.com/project%2Flyapi%2Freadme%2FLyAPI2.png)

## 在线体验

不想下载？你可以使用[在线体验][1]功能！

## 在线文档

想深入了解LyAPI？快来看看[在线文档][4]吧！

## 最近更新

- Core V1.5.5：增加单独删除缓存的函数、优先输出参数
- Core V1.5.3：增加视图类、init、after函数
- Core V1.5.2：修复Linux命名空间区分大小写问题

- 框架更新请前往update.txt查看更新

## 拓展类库

LyAPI将会不断的更新拓展类库：
- array2xml 数组转XML数据
- visit-stats 接口访问数量统计
- ladoc 在线生成接口文档
- [更多拓展请前往packagist查看][2]

#### 类库安装

    composer require lyapi\ExtendName

#### 类库使用
所有类库都是放在命名空间: LyApi\extend 下的

## 参与贡献

1. Fork代码到你的仓库
2. 增加功能并自行测试
3. 发起Pull Requests
4. 等待管理员审查

## 开源协议

LyAPI使用MIT协议，更多信息请查看[MIT协议官网][3]

## 联系作者

作者:mr小卓X

Q Q:1373962439

交流群:769094015(加群提问)

GitHub: https://github.com/xiaozhuox/LyApi

## 已知问题

问题1：Composer创建的项目报错：

解决方法：删除vendor文件夹，重新install即可

问题2：FileCahce和Log无法正常使用：

解决方法：在根目录新建data文件夹，里面再建cahce和log文件夹


> 如发现更多问题请 发布Issue 或 加群反馈 

[1]: http://lyapi.wwsg18.com/trial.html
[2]: https://packagist.org/users/wwsg18/
[3]: https://mit-license.org
[4]: https://wwsg18.gitee.io/lyapi-docs/#/
