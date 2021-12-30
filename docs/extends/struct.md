# 拓展结构

拓展系统目前分为：`原生拓展` 和 `@Dev拓展`

### @Dev 拓展

`LyDev` 的拓展结构，它在原生拓展的基础上可以增加控制台系统。

> 以下为 Dorea 拓展的控制台页面（使用了HTML开发）

![uTools_1640496983081.png](https://s2.loli.net/2021/12/26/RJkY8nOGu6FNQm1.png)

只有基于 `@Dev` 拓展结构的拓展才允许被 LyDev 官方库所收录。

#### 目录结构

```text
- Plugin-Dir
  - application # 用于编写插件逻辑程序
  - extdev      # 用于配置 @dev 信息
  - Main.php    # 插件入口文件
```