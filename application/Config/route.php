<?php

/**
 * 开发者可在本文件中自定义路由规则：
 */

use LyApi\Core\Route;
use LyApi\Support\Config;
use LyApi\Support\Extend;

// ----- 表达式注册程序

/**
 * 路由表达式本质上就是一个正则表达式处理器！
 * 本处可定义一些关键词用于路由注册时使用！
 */

Route::regex("any?", ".*");                     // 匹配任何字符（可空）
Route::regex("num?", "[1-9]*");                 // 匹配数字字符（可空）
Route::regex("letter?", "[a-zA-Z]*");           // 匹配字母字符（可空）
Route::regex("any", ".+");                      // 匹配任何字符
Route::regex("num", "[1-9]+");                  // 匹配数字字符
Route::regex("letter", "[a-zA-Z]+");            // 匹配字母字符

// ----- 静态路径注册

/**
 * 在程序处理过程中，静态资源的优先级是大于控制器路由的！
 * 所以说，被静态资源占用的路径，将无法被控制器程序注册。
 */

Route::resource("/resource/", ROOT_PATH . '/resource/static/');

// ----- 路由注册程序

/**
 * 路由注册有三个参数：表达式，控制器 和 访问类型
 * 表达式：用于匹配对应的路径，通过路径解析到不同控制器处理
 * 控制器：控制器对象路径，通过 "." 进行分割（最后由一位为函数名）
 * 访问类型：如 get post put 等，默认值为 any （支持所有访问）
 */

Route::rule("/", "Main.index", 'any');


Route::rule("/demo/{letter}", "Demo.{1}", "any");


/**
 * 插件注册路由
 * 注释此行可禁止所有插件注册
 * 参数1为数组，选择性禁用插件注册
 */
Extend::_router();

// 这种方法可以动态调用不同的控制器
// Route::rule("/{letter}", "Main.{1}", 'any');

/**
 * filter 可以添加过滤器，当参数1为false时，本路由无法触发，同时抛出参数2的异常
 * 可用于简单的检测系统（用户是否登录，是否拥有权限）
 * 第二参数使用方法：
 * 为空则不做处理，继续检测其他路由。
 * 为数字则抛出相对 HTTP CODE 异常。
 * 为字符串则重定向到目标路径。
 */

// Route::rule("/debug/{letter}", "Main.{1}", 'any')::filter(function () {
//     // 这里可以编写闭包函数来检查一些状态
//     return Config::dotConfig('app.open_debug');
// }, HTTP_NOT_FOUND);

/**
 * ⚠⚠⚠⚠⚠ 所有注册请在本代码前完成 ⚠⚠⚠⚠⚠
 * afterDefine 可以编写一个闭包函数来对最终控制器进行编辑
 * 控制器格式为：Class.Function
 * 其中所有命名空间，对象，函数均用 . 做分割。
 * 以下路由实现了 LyApi 1.X 的自动匹配路由（非常方便 QWQ ）
 */

// Route::rule("/{any}", "{1}")::afterDefine(function ($controller) {
//     // afterDefine 可以让您在控制器生成后手动调整一次控制器结果
//     $controller = str_replace("/", ".", $controller);
//     return $controller;
// });

/**
 * 本函数为访问已经注册好的所有数据
 * 位于其他文件声明的路由也可被提取！
 */

return Route::list();
