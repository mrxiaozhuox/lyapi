# 项目部署

!> LyApi 2.X 暂不支持 Composer 快速安装。

## 🍪手动部署

> 有点麻烦，不过也挺好的。

#### ⏬下载

在安装前，请确保您的电脑已经安装好了 [GIT 工具](https://git-scm.com/) （别告诉我你连 git 是啥都不知道~ 😮）

然后，接下来用你的终端程序跑一下下面的指令（Windows 用 CMD 就行了）

```command
git clone https://gitee.com/mrxzx/LyApi.git
```

注意：先 **cd** 到需要下载的目录，不然下载到哪里去了都不知道😓

#### 💽安装

如果安装有composer，请运行：

```
composer install
```
或者手动下载 vendor 文件夹！

[Vendor 下载地址](https://pan.wwsg18.com/index.php?share/file&user=1&sid=sGa8uM7n)

并将 vendor 解压至框架更目录！

#### ⏫部署

根据自己系统上的服务器（Apache OR Nginx）创建相应的服务！

注意：根目录为 **public** 文件夹，其他目录将无法运行！！

#### ⏺伪静态

接下来则需要配置伪静态！

Apache 伪静态配置：

```apache
<IfModule mod_rewrite.c>
 RewriteEngine on
 RewriteCond %{REQUEST_FILENAME} !-f
 RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
</IfModule>
```

Nginx 伪静态配置：

```nginx
if (!-f $request_filename){
	set $rule_0 1$rule_0;
}
if ($rule_0 = "1"){
	rewrite ^/(.*)$ /index.php/$1 last;
}
```

#### 🆗完成

接下来，试着访问本框架，如果页面正常的加载出来了！则说明安装已经完成！