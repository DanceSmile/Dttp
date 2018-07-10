<h1 align="center">Http client for human</h1>

[![Build Status](https://travis-ci.org/DanceSmile/Dttp.svg?branch=master)](https://travis-ci.org/DanceSmile/Dttp)
[![License](https://poser.pugx.org/dancesmile/dttp/license)](https://packagist.org/packages/dancesmile/dttp)
[![Latest Unstable Version](https://poser.pugx.org/dancesmile/dttp/v/unstable)](//packagist.org/packages/dancesmile/dttp)
[![Latest Stable Version](https://poser.pugx.org/dancesmile/dttp/version)](https://packagist.org/packages/dancesmile/dttp)

## 安装
```
composer require dancesmile/dttp
```

## 单个请求直接访问


```php
<?php 
use Dancesmile\Dttp;

// get request  http://localhost:9090/get?param1=1uery1&param2=query2
$response = Dttp::get("http://localhost:9090/get",[
	"param1" => "query1",
	"param2" => "query2"
]);
// post request http://localhost:9090/post and post www-form-urlencode data `username=username`
$response = Dttp::post("http://localhost:9090/post",[
	"username" => "username"
]);

// delete request 
$response = Dttp::delete("http://localhost:9090/delete");
// put request 
$response = Dttp::put("http://localhost:9090/put");
// patch request 
$response = Dttp::patch("http://localhost:9090/patch");

 ```

## 单个域名多个子链接访问 

```php
<?php
use Dancesmile\Dttp;

$client = Dttp::client(["base_uri" => "http://localhost:9090"]);
// get request  http://localhost:9090/get?param1=1uery1&param2=query2
$response = $client->get("/get",[
	"param1" => "query1",
	"param2" => "query2"
]);
// post  request http://localhost:9090/post and post www-form-urlencode data `username=username`
$response = $client->post("/post",[
	"username" => "username"
]);

```

## 响应返回 response

```php
<?php
use Dancesmile\Dttp;

$response = Dttp::post("http://localhost:9090/post",[
	"username" => "username"
]);

$body = $response->body();
$json = $response->json();
$content_type = $response->header("Content-Type");
$headers = $response->headers();

```

## 提交数据

```php
<?php
use Dancesmile\Dttp;

 Dttp::asJson()->post("http://localhost:9090/post",[
	"username" => "username"
 ]);

 Dttp::asString()->post("http://localhost:9090/post","hello");

 Dttp::asMultipart()->post("http://localhost:9090/post",[
 	[
 		"name" => "name",
 		"contents" => "contens",
 		"Content-Type" => "text/plain"
 	]
 ]);

 Dttp::asFormParams()->post("http://localhost:9090/post",[
	"username" => "username"
 ]);
```

## 属性设置

```php
<?php
use Dancesmile\Dttp;

 $client = Dttp::asJson()->withHeaders([
 	"dttp-version" => 1.0
 ]);

 $client->timeout(2);

 $client->accept("text/html");

 $client->redirect(true)

 $client->verify(false)

 $client->contentType("application/json");

 $client->post("http://localhost:9090/post",[
	"username" => "username"
 ]);

```

### 中间件
```php
use Dancesmile\Dttp;
<!-- 前置中间件 -->
Dttp::addMiddware("test",function($request, array $options)use($handler)
{
    $this->runBeforeCallbacks($request, $options);
    return $handler($request, $options);
})->post("http://localhost:9090/post",[
	"username" => "username"
 ]);

 <!-- 后置中间件 -->
Dttp::addMiddware("test",function($request, array $options)use($handler){
    $promise = $handler($request, $options);
    return $promise->then(
        function (ResponseInterface $response) {
            return $response;
        }
    );
})->post("http://localhost:9090/post",[
	"username" => "username"
 ]);

```

## 请求前置操作

```php
<?php
use Dancesmile\Dttp;

Dttp::client("http://localhost:9090")->beforeSending(function($resquest, $option){

   //user code
   
})->get("/get")->json();
```








