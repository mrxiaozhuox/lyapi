# 事件监听

> 在部分情况下，我们需要监听一些事件并作出相关的响应。

## 监听与触发

```php
// 框架支持你在一些程序前后进行自定义操作。
Event::on("event_name",function ($name) {
    // 当 event_name 被触发时，本闭包函数将被调用
    echo "Hello World";
});

// 触发一个事件，可以传递参数
Event::trigger("event_name","mrxiaozhuox");
```

## 中断事件

当多个触发器指向了同一个事件，则可以使用中断方法来结束后续调用。

```php
Event::on("event_name",function () { echo "001"; });
Event::on("event_name",function () { echo "002"; });
Event::on("event_name",function () { echo "003"; });

// 执行顺序：003 002 001 (栈实现，先进后出)
```

如果不希望一次性执行完三个函数，则可使用 **interrupt** 函数。

```php
Event::on("event_name",function () {
    echo "001"; 
    Event::interrupt();
});
Event::on("event_name",function () { echo "002"; });
```

以上程序在 event_name 第一次触发时只调用第一个函数，而第二次才会运行第二个。

## 关于系统

本系统使用栈结构实现，先注册的事件将最后被调用。

框架内置栈对象：**LyApi\Support\DataStruct\Stack**