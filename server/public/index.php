<?php

require "../vendor/autoload.php";

use Laravel\Lumen\Application;

$app = new Application(
    realpath(__DIR__.'/../')
);

function build_response($request){
     return response()->json([
        'headers' => $request->header(),
        'query' => $request->query(),
        'json' => $request->json()->all(),
        'form_params' => $request->request->all(),
    ], $request->header('Status', 200));
}

$app->router->get("/get",function (){
    return   build_response(app("request"));
});


