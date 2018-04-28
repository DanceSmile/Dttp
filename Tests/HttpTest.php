<?php
/**
 * Created by PhpStorm.
 * User: siyue
 * Date: 2018/04/27
 * Time: 12:12
 */
require  __DIR__."/../vendor/autoload.php";
use Dancesmile\Http;


$response = Http::buildClient();

var_dump($response);




