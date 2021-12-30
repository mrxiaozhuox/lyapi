# 拓展系统 [ Extend ]

在 LyApi 1.X 中，插件系统仅仅是提供了一个简单的自动引入和命名空间，并没有任何实质性的功能。

而 LyApi 2.X 则提供了更加完善的拓展系统。我们特意为拓展提供了一些专属事件监听函数，方便拓展与本体的融合。

同时还内置了拓展 **LyDev** 它可以让你在线安装，管理当前存在的拓展。

## 事件监听函数

* **event_route_register**   路由注册事件，可以在拓展中注册路由。
* **event_helper_register**  函数注册事件，为 Helper 下的 ExtFunc 注册函数。
* **event_trigger_register** 事件处理注册，框架内部有许多事件可以让拓展进行管理。

## 拓展独有函数

* **render_template** 使用 **twig** 渲染拓展模板（位于 template 目录下）
* **ext_filecache** 获取拓展专属文件缓存对象（自动区分 group 信息）

## LyDev 拓展工具

LyDev 在原有的拓展系统上进行了更新，使得拓展更加方便管理与维护。