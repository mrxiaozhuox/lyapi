# LyAPI
[![Travis](https://img.shields.io/badge/Language-PHP-blue.svg)](http://php.net)
[![Travis](https://img.shields.io/badge/License-MIT-brightgreen.svg)](https://mit-license.org)
[![Travis](https://img.shields.io/badge/Version-V1.0-orange.svg)](http://lyapi.wwsg18.com)
[![Travis](https://img.shields.io/badge/Core_Version-V1.5.2-blue.svg)](http://lyapi.wwsg18.com)

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

## 在线体验

不想下载？你可以使用[在线体验][1]功能！

## 在线文档

想深入了解LyAPI？快来看看[在线文档][4]吧！

## 拓展类库

LyAPI将会不断的更新拓展类库：
- array2xml 数组转XML数据
- visit-stats 接口访问数量统计
- [更多拓展请前往packagist查看][2] 

#### 类库安装

    composer require lyapi\ExtendName

#### 类库使用
所有类库都是放在命名空间: LyApi\extend 下的

## 参与贡献

LyAPI不属于任何人，它属于我们所有人！
1. Fork代码到你的仓库
2. 增加功能并自行测试
3. 发起Pull Requests
4. 等待管理员审查

## 开源协议

LyAPI使用MIT协议，更多信息请查看[MIT协议官网][3]

[1]: http://lyapi.wwsg18.com/trial.html
[2]: https://packagist.org/users/wwsg18/
[3]: https://mit-license.org
[4]: https://wwsg18.gitee.io/lyapi-docs/#/