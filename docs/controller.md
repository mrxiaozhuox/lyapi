# 控制器系统

> 控制器是应用中最重要的模块，决定了每次访问将被怎么处理。

## ⚽控制器基础

一个控制器即为一个类文件，这个类中的函数即为控制器的最终处理程序。

一个简单的控制器：

```php
class Main extends ViewCon
{
    public function index($req, $resp)
    {
        return View::render('index', []);
    }
}
```

它只有一个函数：“index”，这个函数的结果为渲染 index.html 的模板引擎。

在大部分功能中要访问一个控制器只需要使用：**Namespace.Class.Function**

当控制器位于 application/Controller 的根目录下时，命名空间不需要填写。

比如在路由绑定中填写对应的控制器："**Main.index**"

## 🎯控制器父类

控制器所继承的类决定了本控制器最终结果将以什么模式去展现：

- JSON - 接口常用
- HTML - 网站常用

以及一些自定义的结构，都可以使用控制器父类来实现。

以下是系统默认的接口控制器父类实现方式：

```php
class ApiCon extends Bcontrol
{
    public function _export($data)
    {
        return View::api($data, API_STRUCTURE_INFO);
    }
}
```

父类也需要继承 Bcontrol 这个对象。没有理由，继承就行了。

**_export** 函数则是最终结果输出的实现，如上方代码则是调用了 View 中的 API 生成器来完成结果处理。

你也可以在 控制器父类 中定义一些特有的函数供控制器调用。

## 🎱控制器参数

每个控制器函数都需要接受至少两个参数：$req 和 $resp

它们分别代表：Request 对象 和 Response 对象。

其中 $req 可以使用 **$req->variable** 取得路由的动态参数。

或者：
- $req->args("name") 获取Get参数。
- $req->form("name") 获取Post参数。

而 $resp 则是基础的 **重定向、错误报出** 等功能的实现。

## 🏈关于其他

控制器本身的功能并不多，但是应用的所有业务逻辑都是在这里完成的，所以它非常重要！😏