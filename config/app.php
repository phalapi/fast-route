<?php
/**
 * 请在下面放置任何您需要的应用配置
 */

return array(

	/**
	 * 扩展类库 - 快速路由配置
	 */
    'FastRoute' => array(
         /**
          * 格式：array($method, $routePattern, $handler)
          *
          * @param string/array $method 允许的HTTP请求方法，可以为：GET/POST/HEAD/DELETE 等
          * @param string $routePattern 路由的正则表达式
          * @param string $handler 对应PhalApi中接口服务名称，即：?service=$handler
          */
        'routes' => array(
            array('GET', '/site/index', 'Site.Index'),
            array('GET', '/examples/curd/get/{id:\d}', 'Examples_CURD.Get'),
        ),
    ),
);
