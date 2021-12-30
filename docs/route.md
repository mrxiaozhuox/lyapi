# 路由系统

> 路由是框架中最重要的功能，它根据访问路径来分配不同的控制器。

路由配置位于：Application/Config/route.php 文件。

## 🚚路由基础

这是 LyApi 在 Gitee 上的路径：
```
https://gitee.com/mrxzx/LyApi
```

它由几部分组成：

- 协议：HTTPS
- 域名：gitee.com
- 路径：/mrxzx/LyApi

而路由系统则会根据路径的不同，去调用不同的控制器完成数据处理。

!> 路由系统支持的前提是添加了伪静态配置，否则路径会去调用不同目录下的不同文件。

在 LyApi 中，你可以很轻松的添加一个路由：

```php
Route::rule("/", "Main.index","any");
```

上方的 **Route::rule** 有三个参数：

- $path       : 绑定的路径
- $controller : 绑定的控制器
- $method     : 访问方式

这行代码将根路径交给了 Main.index 控制器去处理，并且它支持所有的访问方式。

也就是说，访问 http://domain.com/ 时，Main.index 会处理这条请求。

```php
Route::rule("/test", "Main.test","any");
```

而这行代码则会绑定到 http://domain.com/test 路径上。

## 🚜路由变量

上方的路由设置仅在确定路径的情况下使用，但当我们要实现类似 Gitee 的路径时，会遇到一些问题。

要实现上方 Demo 中的项目路径的话，难道我要把所有项目路由都绑定一次吗？？

```php
Route::rule("/mrxzx/LyApi", "Main.mrxzx.LyApi","any");
Route::rule("/mrxzx/LyMaster", "Main.mrxzx.LyMaster","any");
Route::rule("/mrxzx/LyPage", "Main.mrxzx.LyPage","any");
```

这样一添加新的数据就得加个路径和控制器？岂不是得不偿失？？

然而此时我们可以使用 “正则表达式” 来实现动态的匹配！

使用 **Route::regex** 函数绑定一个匹配方案：

```php
Route::regex("letter", "[a-zA-Z]+");
```

第一个参数为匹配方案的名称，第二个参数是一个正则表达式。

提交匹配方案后，便可以在路由注册中使用了：

```php
Route::rule("/{letter}", "Main.{1}", 'any');
```

上方代码可以实现动态的路由匹配和控制器动态调用。

比如说你访问的路径为：http://domain.com/test ，控制器则会调用到：Main.test

同时，在控制器处也可接收到动态路由匹配的参数。如实现项目路径功能：

```php
Route::rule("/{letter}/{letter}","Main.project"); 
// OR
Route::rule("/{letter}/{letter}","Main.{1}.{2}"); 
```

并在控制器取得两个变量的值，根据值的不同生成不同的页面即可。


## 🚌自定处理

系统自带的路由系统始终有局限性，所有干脆让开发者自己定义一个闭包函数来处理数据：

```php
Route::rule("/{any}", "{1}")::afterDefine(function ($c) {
    $c = str_replace("/", ".", $c);
    return $c;
});
```

上方的代码实现了 LyApi 1.X 的自动指向控制器功能，如：

- 访问：http://domain.com/Main/index 会自动指向 Main.index 控制器。
- 访问：http://domain.com/Main/Hello/world 会自动指向 Main.Hello.world 控制器。

这是非常方便的，即你可以根据自己的需求来自定义控制器结果。

!> 请注意闭包函数一定要返回最终结果，否则会出现无法匹配的问题。


## 🚠路由过滤

部分情况下，一些路径必须在一些状态下才能访问，这时则可以使用路由过滤了。

比如说：一个页面仅在用户登录后才能访问，否则报404了。

```php
Route::rule("/message", "User.message")::filter(islogin(), HTTP_NOT_FOUND);
```

上方代码会在 **islogin()** 返回 True 时允许路由访问，否则报出 HTTP_NOT_FOUND 的错误（即404）

第一个参数可以直接给 Boolean 值，也可以是一个闭包函数。

第二个参数则有很多可能性：

- 留空则跳过本路由，继续去匹配后面的路由配置。
- 为数字则报出相应的 HTTP CODE 异常。
- 为字符串则重定向到相应的路径。