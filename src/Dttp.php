<?php

namespace Dancesmile;


use GuzzleHttp\Client;
use Psr\Http\Message\RequestInterface;


class Dttp{

    public static function  __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        return Pending::getInstance()->{$name}(...$arguments);

    }


}

class Pending{
    public function  __construct()
    {
        $this->options = [
            'http_errors' => false
        ];
        $this->bodyFormat = "json";
    }

    public static  function getInstance(){
        return new self();
    }
    function buildClient(){

        return new Client();
    }

    function get($url, $queryParams = []){

        return $this->send("GET", $url, [
           "query" => $queryParams
        ]);

    }

    function post($url, $params = [] ){
        return $this->send("POST",$url,[
            $this->bodyFormat => $params
        ]);
    }

    function patch($url, $params = [])
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    function put($url, $params = [])
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    function delete($url, $params = [])
    {
        return $this->send('DELETE', $url, [
            $this->bodyFormat => $params,
        ]);
    }


    function withHeaders($headers = []){
        return tap($this,function($request)use($headers){
            $this->options = $this->mergeOptions($this->options,[
                "headers" => $headers
            ]);
        });
    }

    function send($method, $url, $options){
        return new Response($this->buildClient()->request($method, $url,$this->mergeOptions([
            "query" => $this->parseQueryParams($url)

            ],$options)
        ));
    }

    function  mergeOptions(...$options){
        return array_merge_recursive($this->options, ...$options);
    }

    function parseQueryParams($url)
    {
        return tap([], function (&$query) use ($url) {
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
        });
    }


}

class Response {

    function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }
    function body(){
        return (string)$this->response->getBody();
    }
    function headers()
    {
        return collect($this->response->getHeaders())->mapWithKeys(function ($v, $k) {
            return [$k => $v[0]];
        })->all();
    }
}

function tap($value, $callback) {
    $callback($value);
    return $value;
}


