<?php
namespace Dancesmile\Http;

use GuzzleHttp\Client;

class Http{

    protected  $client =  null;

    public function __construct(){

    }

    public function index(){
        return "hello  world";
    }

}


