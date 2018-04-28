<?php
/**
 * Created by PhpStorm.
 * User: cailei
 * Date: 2018/4/26
 * Time: 下午11:33
 */

require  __DIR__."/../vendor/autoload.php";


$response = \Dancesmile\Dttp::withHeaders([
    "test" =>"htader"
])->post("http://localhost:9090/post?hello=world",[
    "name" => "cailei"
])->body();


var_dump($response);
