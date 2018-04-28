<?php
/**
 * Created by PhpStorm.
 * User: cailei
 * Date: 2018/4/26
 * Time: 下午11:33
 */

require  __DIR__."/../vendor/autoload.php";






$response = \Dancesmile\Dttp::get("http://dev.youyouchina.cn/api/v1/index/adsense")->json();

$response = \Dancesmile\Dttp::post("http://dev.youyouchina.cn/api/v1/index/adsense")->status();



$data = [
    "phone"=>15370536039,
    "code"=>1160,
    "password"=>md5("137681502"),
    "deviceId"=>"asd",
    "type" => 0
];
$response = \Dancesmile\Dttp::asjson()->post("http://dev.youyouchina.cn/api/v1/user/login",$data)->json();


$token = $response["result"]['token'];

$fiel = file_get_contents("http://www.youyouchina.cn/web/images/code.png");

dd($fiel);


$formData = [
    [
        "name" => "file",
        "contents" => fopen("http://www.youyouchina.cn/web/images/code.png","r"),
        "headers"=> [
            "Content-Type" => "image/jpeg"
        ]
    ],
    [
        "name" => "json",
        "contents" => json_encode([
            "token" => $token,
            "nickname" => "testMutipart"
        ])
    ]
];
$response = \Dancesmile\Dttp::asMultipart()->post('http://dev.youyouchina.cn/api/v1/user/edit_user',$formData)->json();


dd($response);







