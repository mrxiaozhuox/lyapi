# 插件配置/PConfig

## 插件信息

- 作者：[mr小卓X](http://blog.wwsg18.com)
- 版本：V1.0.0
- 插件核心：V1.0.1
- 框架版本：V1.6.1
  

## 插件介绍

框架自带的插件系统是没有配置功能的，而本插件则为其他插件提供了配置系统。

本插件为大部分插件的前置，建议安装！

## 插件函数

> 函数总数：7

```php
SaveConfig(
	$name,	//储存的配置文件名
  $data,	//需要储存的数据
  $add	//是否追加内容（默认为false）
); //设置一个数据到插件配置文件

HasConfig(
	$name //查找的配置文件名
); //判断一个配置文件是否存在

ReadConfig(
	$name //查找的配置文件名
); //获取配置文件内容

DeleteConfig(
	$name //查找的配置文件名
); //删除一个配置文件

GetDirPath(); //获取插件配置文件所在目录

GetDataList(); //获取插件所有配置文件列表

InitConfig(
	$name,	//储存的配置文件名
  $data		//需要储存的数据
); //在配置文件不存在的情况下，创建配置文件
```

## 安装方法

将PConfig目录放入框架plugin目录下。

## 使用示例

```php
public function PConfig(){
    //动态加载插件类
    $pconfig = DI::PluginClass('PConfig','PConfig','"VisitRecord"');

    //初始化配置文件
    $pconfig->InitConfig('Demo',[
        'Title' => 'PConfig',
        'Message' => 'Hello World'
    ]);

    //返回配置文件内容
    return $pconfig->ReadConfig('Demo');
}
```

> 返回值：{"code":"200","data":{"Title":"PConfig","Message":"Hello World"},"msg":""}

## 前置插件

> 本插件不需要安装任何前置插件

## 注意事项

请确保程序有权限在Config下创建目录（文件夹权限相关）