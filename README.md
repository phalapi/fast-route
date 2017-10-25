
## PhalApi 2.x 扩展类库：基于FastRoute的快速路由

此扩展基于 [FastRoute](https://github.com/nikic/FastRoute) 实现，需要 **PHP 5.4.0** 及以上版本，可以通过配置实现自定义路由配置，从而轻松映射到PhalApi中的service接口服务。  
  
## 安装

在项目的composer.json文件中，添加：

```
{
    "require": {
        "phalapi/fast-route": "dev-master"
    }
}
```

配置好后，执行composer update更新操作即可。
  
## 配置

我们需要在 **./config/app.php** 配置文件中追加以下配置：
```php
	/**
	 * 扩展类库 - 快速路由配置
	 */
    'FastRoute' => array(
         /**
          * 格式：array($method, $routePattern, $handler)
          *
          * @param string/array $method 允许的HTTP请求方式，可以为：GET/POST/HEAD/DELETE 等
          * @param string $routePattern 路由的正则表达式
          * @param string $handler 对应PhalApi中接口服务名称，即：?service=$handler
          */
        'routes' => array(
            array('GET', '/site/index', 'Site.Index'),
            array('GET', '/examples/curd/get/{id:\d}', 'Examples_CURD.Get'),
        ),
    ),


```
  
## nginx的协助配置（省略index.php）

如果是使用nginx的情况下，需要添加以下配置：

```
    # 最终交由index.php文件处理
    location / {
        try_files $uri $uri/ $uri/index.php;
    }

    # 匹配未找到的文件路径
    if (!-e $request_filename) {
        rewrite ^/(.*)$ /index.php/$1 last;
    }
```
然后重启nginx。  
  
  
## 入门使用
### (1)入口注册
```php
//$ vim ./public/index.php
require_once dirname(__FILE__) . '/init.php';

//显式初始化，并调用分发
\PhalApi\DI()->fastRoute = new PhalApi\FastRoute\Lite();
\PhalApi\DI()->fastRoute->dispatch();

$pai = new \PhalApi\PhalApi();
$pai->response()->output();
```
  
## 调用效果及扩展
### (1)通过新的路由正常访问
在完成上面的配置后，我们就可以这样进行页面访问测试：
```
  http://library.phalapi.com/site/index
  等效于：http://library.phalapi.com/?service=Site.Index
 
  http://library.phalapi.com/examples/curd/get/1
  等效于：http://library.phalapi.com/?service=Examples_CURD.Get=1
```
 
### (2)非法访问
当请求的HTTP方法与配置的不符合时，就会返回405错误，如我们配置了：
```php
array('POST', '/user/{id:\d+}/{name}', 'handler2'),
```
但是通过GET方式来访问，即：
```
http://library.phalapi.com/user/123/name
```
则会返回：
```
{
    "ret": 405,
    "data": [],
    "msg": "快速路由的HTTP请求方法错误，应该为：POST"
}
```

### (3)路由配置错误
当在./config/app.php的文件里配置错误的路由时，会直接抛出FastRoute\BadRouteException异常，以及时提示开发人员修正。
  
### (4)异常错误处理器
我们也可以实现```PhalApi\FastRoute\Handler```接口来自定义我们自己的错误异常处理回调函数。如：
```php
<?php
use PhalApi\FastRoute\Handler;
use PhalApi\Response;

class MyHandler implements Handler {

    public function excute(Response $response) {
        // ... ...
    }
}
```
然后，在分发时指定handler：
```php
\PhalApi\DI()->fastRoute->dispatch(new MyHandler());
```

### 更多路由配置说明
请访问 [FastRoute](https://github.com/nikic/FastRoute) ，查看其官方说明。
