<?php 
require __DIR__."/../vendor/autoload.php";

use Dancesmile\Dttp;




$response = Dttp::addMiddleware("test",function ($handler)
{
	return function ($request, $option)use($handler)
	{
		$request = $request->withHeader("cailei","test");
		return $handler($request, $option);
	};
})->withCookie(["cailei" => "test"],"baidu.com")->asFormParams()->addMiddleware("name",function($handler){
	return function ($request, $option) use ($handler)
	{
		var_dump($request->getHeaders());
		$promise = $handler($request, $option);

		 return $promise->then(
                    function ( $response) {
                       return $response;
                    }
                );
	};
})->beforeSending(function($request, $option){

		var_dump($request->getHeaders());

})->post("http://baidu.com");



