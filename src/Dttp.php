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
            'http_errors' => false,
        ];
        $this->bodyFormat = "json";
    }

    public static  function getInstance(){
        return new self();
    }
    function buildClient(){
        return new Client();
    }

    function asJson(){
        return $this->bodyFormat('json')->contentType('application/json');
    }
    function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    /**
     *      name: (string, required) 表单字段名称
            contents: (StreamInterface/resource/string, required) 表单元素中要使用的数据
            headers: (array) 可选的表单元素要使用的键值对数组
            filename: (string) 可选的作为要发送的文件名称
     * @return mixed
     */
    function asMultipart()
    {
        return $this->bodyFormat('multipart');
    }

    function timeout($seconds)
    {
        return tap($this, function ($request) use ($seconds) {
            $this->options['timeout'] = $seconds;
        });
    }

    function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
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

    function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }


    function withHeaders($headers = []){
        return tap($this,function($request)use($headers){
            $this->options = $this->mergeOptions([
                "headers" => $headers
            ]);
        });
    }

    function withOptions($options = []){
        return tap($this, function ($request)use($options){
            $this->options = $this->mergeOptions($options);
        });
    }

    function redirect($status = false)
    {
        return tap($this, function ($request)use($status) {
            return $this->options = array_merge_recursive($this->options, [
                'allow_redirects' => (bool)$status,
            ]);
        });
    }

    function bodyFormat ($format){
        return tap($this,function ($request)use($format){
            $this->bodyFormat = $format;
        });
    }

    function send($method, $url, $options){
        try{
            return new Response($this->buildClient()->request($method, $url,$this->mergeOptions([
                   "query" => $this->parseQueryParams($url)
                 ],$options)
            ));
        }catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }

    }
    function verify($status){
        return tap($this,function ($request)use($status){
            $this->options = $this->mergeOptions([
                "verify" => (bool)$status
            ]);
        });
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

    function header($header){
        return $this->response->getHeaderLine($header);
    }

    function json()
    {
        return json_decode($this->response->getBody(), true);
    }

    function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return $this->response->{$name}($arguments);
    }

    function status()
    {
        return $this->response->getStatusCode();
    }



    function __toString()
    {
        return $this->body();

    }
}

function tap($value, $callback) {
    $callback($value);
    return $value;
}

class ConnectionException extends \Exception {}


