<?php
namespace Dancesmile;

class Dttp{

   public static function __callstatic($method, $argv){
       return Pending::getInstance()->{$method}(...$argv);
   }

}

class Pending{

    public  static  function getInstance(...$argv){
        return new self($argv);
    }

    function buildClient()
    {
        return new \GuzzleHttp\Client(['handler' => $this->buildHandlerStack()]);
    }

    function buildHandlerStack()
    {
        return tap(\GuzzleHttp\HandlerStack::create(), function ($stack) {
            $stack->push($this->buildBeforeSendingHandler());
        });
    }

    function buildBeforeSendingHandler()
    {
        return function ($handler) {
            return function ($request, $options) use ($handler) {
                return $handler($this->runBeforeSendingCallbacks($request), $options);
            };
        };
    }

    public function request($request, $uri){
        rturn ($this->buildClient())->request($request, $uri);
    }

    public function createRequest(){

    }

    function send($method, $url, $options)
    {
        try {
            return new ZttpResponse($this->buildClient()->request($method, $url, $this->mergeOptions([
                'query' => $this->parseQueryParams($url),
            ], $options)));
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }

}


class Response{

    protected  $response;
    public function __construct($response)
    {
        $this->response = $response;
    }

    public function body(){
        return (string)$this->response->getBody();
    }

}

class Request{

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function url(){
        return (string)$this->request->getUri();
    }

    public function body(){
        return (string)$this->request->getBody();
    }

    public function method(){
        return $this->request->getMethod();
    }

    public function headers(){


    }
}

function tap($value,callable $callback) {
    call_user_func($callback, $value);
    return $value;

}


