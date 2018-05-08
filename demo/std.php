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
})->asFormParams()->addMiddleware("name",function($handler){
	return function ($request, $option) use ($handler)
	{
		$promise = $handler($request, $option);

		 return $promise->then(
                    function ( $response) {
                       return $response;
                    }
                );
	};
})->beforeSending(function($request, $option){

		var_dump($request->getBody()->getContents());

})->get("http://www.baidu.com");



