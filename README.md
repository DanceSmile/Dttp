
## usage

### 请求方式

#### 单个请求直接访问

```php
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

 ####  单个域名多个子链接访问

```php
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

### 响应返回 response

```php
$response = Dttp::post("http://localhost:9090/post",[
	"username" => "username"
]);

$body = $response->body();
$json = $response->json();
$content_type = $response->header("Content-Type");
$headers = $response->headers();

```

### 提交数据

```php
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

### 属性设置

```php
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

#### 请求前置操作

```php
Dttp::client("http://localhost:9090")->beforeSending(function($resquest, $option){
   //user code
})->get("/get")->json();

```








