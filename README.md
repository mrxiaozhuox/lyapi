# LyApi 2.X 🍭

> LyApi 2.X 是全新的 LyApi 版本，更新了整体的架构和代码，让框架变得更加灵活！

## 🍑序言

LyApi 1.X 版本已经维护了将近三年了，也是我很重视的一个项目。但是这个项目始终有着很多问题，很多功能并没有很好的去实现。在这三年里我也学到了很多新东西。渐渐也发现了 LyApi 1.X 的局限性，它无法让我去完成很多我想做的事情。包括一些用户给我的提出的意见：路由系统太水，开发不够灵活，仅仅局限于简单的接口开发。于是乎我决定开发了这个 2.X 版本，它是一个从头开始的框架，所有的代码都进行了重写：路由系统、缓存系统、视图系统、控制器系统、模板引擎、以及部分常用的数据结构模板。 

[ 快速前往LyApi 1.X 🛶 ](https://gitee.com/mrxzx/LyApi/tree/V1.X/)

[ LyDev 开发思路 🎊 ](https://blog.wwsg18.com/index.php/archives/58/)


## 🍍特点

- 路由系统：丰富的自定义选项。支持动态参数定义，动态控制器等。
- 日志系统：轻量级日志生成系统，支持类型分类等功能。
- 缓存系统：文件缓存支持简单的增删改查，以及缓存分类等功能。
- 结果类型：根据不同父类可生成不同的返回数据，自带 **JSON** 和 **HTML** 。
- 数据结构：内置 堆、栈、树、字典树等常用数据结构程序，可快速创建与调用。
- 数据访问：内置 Medoo 轻量级库，可快速对各种数据库进行访问以及操作。
- 异常处理：框架对所有异常信息进行分类处理，并可调用不同的自定义控制器。
- 在线管理：内置强大的拓展系统，与自带拓展 LyDev 配合即可完成在线管理功能。

PS: 本框架引入了很多 **PHP** 的新特性，所以需要使用 7.X 以上的版本运行。

## 🥝演示

> 以下提供部分代码演示，可自行查看需要的功能。


### 🌈​ 路由系统

```php
// 基础路由注册（访问根目录则指向 Main.index 控制器）
Route::rule("/", "Main.index"); 

// 动态控制器注册（根据路径不同，注册到不同控制器）
Route::rule("/{letter}", "Main.{1}"); 

// 类似于 LyApi 1.X 的自动指向控制器 的实现
Route::rule("/{any}", "{1}")::afterDefine(function ($c) {
    $c = str_replace("/", ".", $c);
    return $c;
});

// 过滤器，当闭包结果为 False 则会拦截请求并报出相应的异常信息
Route::rule("/debug/{letter}", "Debug.{1}")::filter(function () {
    return Config::dotConfig('app.open_debug');
}, HTTP_NOT_FOUND);

```

### 🍩控制器

```php
// 根据不同继承的类，本控制器所生成的数据结果将不一样
// ViewCon 是普通的HTML代码，ApiCon 则会生成JSON代码 (可自定义)
class Main extends ViewCon
{
    // 接受两个参数，Request 和 Response
    public function index($req, $resp)
    {
        // 返回结果不会直接输出，框架将根据父类的生成规则进行处理后再输出结果。
        // View::render 会使用模板引擎进行渲染。
        return View::render('index', []);
    }
}
```

### 🎨 错误抛出

```php
// 框架支持在任何地方抛出异常，并结束处理程序
Response::abort(404); // 抛出一个 404 异常
Response::abort(HTTP_NOT_FOUND); // 使用常量表示会更加的优雅
abort(HTTP_NOT_FOUND); // 使用助手函数可直接使用 abort 函数
// Abort 后会调用到定义的 Exception 对象处理，结束后将结束程序运行
```



### ⚾错误处理

```php
class Exception extends ViewCon
{
    // 当错误处理器无法查找到相关函数，则会默认调用 _default 函数
    public function _default($req, $resp)
    {
        // 通过 HTTP_CODE 可以取得本次的错误代码
        $http_code = $req->options['HTTP_CODE'];
        
        // 通过 EXCEPTION 可以取得本次的错误对象
        $exception = $req->options['EXCEPTION'];
        
        // 返回的最终结果（HTTP状态码会自动更新，不需要手动提交 header）
        return "<h1>" . $http_code . " Error!</h1>";
    }

    // 通过 _[HTTP_CODE] 的方式可以定义每个状态码的处理程序
    public function _404($req, $resp)
    {
        // 当你不返回任何值时，浏览器会渲染默认的 404 页面
        // return "404 Not Found";
        // 这里还是建议渲染自定义的错误文件内容。
    }
}
```

### 🎲事件系统

```php
// 框架支持你在一些程序前后进行自定义操作。
Event::on("event_name",function ($name) {
    // 当 event_name 被触发时，本闭包函数将被调用
    echo "Hello World";
});

// 触发一个事件，可以传递参数
Event::trigger("event_name","mrxiaozhuox");
```

### 💾数据库操作

```php
$conn = Connector::connect("mydb");

// 查询表中所有的数据
$conn->select("table_name","*");

// 删除 id 为 1 的数据
$database->delete("table_name", [
	"id" => 1
]);

// 插入一条数据
$last_user_id = $database->insert("table_name", [
    "user_name" => "mrxiaozhuox",
    "email" => "mrxzx@qq.com",
    "age" => 16
]);

```

## 👨‍🎓作者

作者: mr小卓X

Q Q: 3507952990

交流群: 769094015 (加群提问)

个人博客: http://mrxzx.info

Gitee: https://gitee.com/mrxzx/

GitHub: https://github.com/xiaozhuox/

PS: 任何问题直接联系我就行，我会第一时间解决问题。



## 📔文档

> 文档整理中，近期将完成编写工作。

[项目文档 - 不完整](https://mrxzx.gitee.io/lyapi/)

## 📡合作

### Dorea DB

一款轻量级的 Key-Value 数据库系统，适用于中小型项目。

目前 LyApi 框架已经内置了强大的 `DoreaDB` 驱动，方便开发者快速开发。

> PHP 的存在本身就为了快速开发，还不来试试 DoreaDB 嘛。

- 项目作者：mr小卓X <mrxzx.info@gmail.com>
- 项目地址：[Dorea KV Database](https://dorea.mrxzx.info/)