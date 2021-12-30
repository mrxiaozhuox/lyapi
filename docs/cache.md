# 缓存系统

> LyApi 2.X 相对 1.X 提供了更加强大的缓存系统。

## 文件缓存

> 文件缓存即为将数据写入到本地文件中，属于最方便的缓存方法。

### 获取对象

框架内置了两个缓存相关的类：**FileCache** 和 **Cache**

它们都用于缓存的相关操作，前者为文件缓存的专属类，而后者包含了 Redis 等其他方案。

当您确定要使用文件缓存时，最好使用 FileCache 类，它会减少不必要的加载和检测。

```php
// FileCache 需要先获取对象，再进行操作
$cache = new FileCache("group_name");
$cache->set("name","liuzhuoer");

// 而 Cache 则定义了静态函数，可直接调用
Cache::set("name","liuzhuoer");
```

Cache 对象在加载时会根据框架配置文件加载相应的设定：

**Application\Config\cache.php: cache_system**

你也可以使用静态函数  **Cache::system("FILE")**  切换至文件缓存。


### 设置数据

```php
// 三个参数：Key Value Expire
Cache::set("name","liuzhuoer",60 * 1000);
```

Key 和 Value 就不必解释了吧，这第三个参数代表数据过期时间。

Expire 默认为 0 代表不过期，**60 × 1000** 则代表了一分钟。

!> 当你传入的值为 **Object** 类型，系统会自动将其转换为 JSON 再写入。


### 读取数据

```php
$my_name = Cache::get("name");
```

当数据不存在则会返回一个空字符串。（数据过期一样）

!> 建议先调用 **Cache::has** 后在做获取，这是一个好的习惯。

### 判断数据

```php
if (Cache::has("name")) {
    // do some thing ...
} else {
    throw new Exception("数据不存在...");
}
```
判断一个缓存键是否存在，在读取前判断是一个好习惯哦！

### 自增数据

```php
// 参数：Key Add
Cahce::inc("name",1);
```
s
很好理解，就是让一条数据在其基础上增加一个值。默认 +1。

当你想让它减少时，第二个参数给个负值即可。

!> 仅限储存类型为数字，其他类型无法操作。


### 删除数据

```php
Cache::delete("name");
```

删除一个缓存键，不需要多解释吧 😝

### 清空数据

```php
Cache::clean();
```

一行代码将清空所有的缓存（仅当前分组）