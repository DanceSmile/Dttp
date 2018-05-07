<?php

namespace Dancesmile;

use GuzzleHttp\HandlerStack;
use GuzzleHttp\Client;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Cookie\CookieJarInterface;





class Dttp
{
    public static function __callStatic($name, $arguments)
    {
        // TODO: Implement __callStatic() method.
        if ($name === 'client') {
            return  Pending::getInstance(...$arguments);
        }
        return Pending::getInstance()->{$name}(...$arguments);
    }
}

class Pending
{
    /**
     * init clinet options
     * @param array
     */
    public function __construct(array $clientOptions)
    {
        $this->clientOptions = $clientOptions;
        $this->options = [
            'http_errors' => false,
        ];
        $this->bodyFormat = "json";

        $this->jar = new \GuzzleHttp\Cookie\CookieJar();

        $this->beforeSendingCallbacks = [];

        $this->middlewares = [];

        $this->middlewares["before"] = function(callable $handler){
            return function($request, array $options)use($handler){
                $this->runBeforeCallbacks($request, $options);
                return $handler($request, $options);
            };
        };

        $this->middlewares["after"] = function(callable $handler){
            return function($request, array $options)use($handler){
                $promise = $handler($request, $options);
                return $promise->then(
                    function (ResponseInterface $response) {
                        return $response;
                    }
                );
            };
        };

    }

    public function addMiddleware($name, callable $callback)
    {
        if($name === "before" || $name === "after") throw new ConnectionException("$name is system used !", 1);
        return  tap($this,function ()use($name, $callback)
         {
             $this->middlewares[$name] = $callback;
         });

    }

    // 带cookie
    public function withCookie($cookies,$domain)
    {
        return tap($this, function () use ($cookies, $domain)
        {
            $this->jar->fromArray($cookies, $domain);
        });
    }

    /**
     *  请求之前动作
     */
    public function  beforeSending(callable $callback ){

        return tap($this,function() use($callback) {
             $this->beforeSendingCallbacks[] = $callback;
        });
    }

    private function runBeforeCallbacks( $request, $options)
    {

        foreach ($this->beforeSendingCallbacks as $callback) {
            call_user_func($callback, $request, $options);
        }

        
    }



    public static function getInstance(array $clientOptions = [])
    {
        return new self($clientOptions);
    }

    /**
     * 创建请求处理器
     * @return [type]
     */
    public function buildClient()
    {
        $stack = HandlerStack::create();

        $this->clientOptions['handler'] = $stack;


        foreach ($this->middlewares as $name => $middleware) {

            $stack->push($middleware);
            
        }

        return new Client($this->clientOptions);
    }

    /**
     * 请求数据类型
     */
    
    public function asString()
    {
        return $this->bodyFormat('body');
    }

    public function asJson()
    {
        return $this->bodyFormat('json')->contentType('application/json');
    }
    public function asFormParams()
    {
        return $this->bodyFormat('form_params')->contentType('application/x-www-form-urlencoded');
    }

    public function asMultipart()
    {
        return $this->bodyFormat('multipart');
    }

    public function bodyFormat($format)
    {
        return tap($this, function ($request) use ($format) {
            $this->bodyFormat = $format;
        });
    }




    /**
     * 设置属性
     */
    public function timeout($seconds)
    {
        return tap($this, function ($request) use ($seconds) {
            $this->options['timeout'] = $seconds;
        });
    }

    public function redirect($status = false)
    {
        return tap($this, function ($request) use ($status) {
            return $this->options = array_merge($this->options, [
                'allow_redirects' => (bool)$status,
            ]);
        });
    }

    public function verify( $status = true)
    {
        return tap($this, function ($request) use ($status) {
            return $this->options = array_merge($this->options, [
              "verify" => (bool) $status
            ]);
        });
    }




    public function accept($header)
    {
        return $this->withHeaders(['Accept' => $header]);
    }

    public function contentType($contentType)
    {
        return $this->withHeaders(['Content-Type' => $contentType]);
    }


    public function withHeaders($headers = [])
    {
        return tap($this, function ($request) use ($headers) {
            $this->options = $this->mergeOptions([
                "headers" => $headers
            ]);
        });
    }

    public function withOptions($options = [])
    {
        return tap($this, function ($request) use ($options) {
            $this->options = array_merge($this->options, $options);
        });
    }

    




    

    /**
     *  基础请求
     *
     */
    
    public function get($url, $queryParams = [])
    {
        return $this->send("GET", $url, [
           "query" => $queryParams
        ]);
    }

    public function post($url, $params = [])
    {
        return $this->send("POST", $url, [
            $this->bodyFormat => $params
        ]);
    }

    public function patch($url, $params = [])
    {
        return $this->send('PATCH', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function put($url, $params = [])
    {
        return $this->send('PUT', $url, [
            $this->bodyFormat => $params,
        ]);
    }

    public function delete($url, $params = [])
    {
        return $this->send('DELETE', $url, [
            $this->bodyFormat => $params,
        ]);
    }




    /**
     * 发送请求
     */

    private function send($method, $url, $options)
    {
        try {
            return new Response($this->buildClient()->request(
                $method,
                $url,
                $this->mergeOptions([
                   "query" => $this->parseQueryParams($url),
                   "cookies" => $this->jar
                 ], $options)
            ));
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            throw new ConnectionException($e->getMessage(), 0, $e);
        }
    }
    


    private function mergeOptions(...$options)
    {
        return array_merge_recursive($this->options, ...$options);
    }

    private function parseQueryParams($url)
    {
        return tap([], function (&$query) use ($url) {
            parse_str(parse_url($url, PHP_URL_QUERY), $query);
        });
    }
}

class Response
{
    public function __construct(\GuzzleHttp\Psr7\Response $response)
    {
        $this->response = $response;
    }
    public function body()
    {
        return (string)$this->response->getBody();
    }
    public function headers()
    {
        return collect($this->response->getHeaders())->mapWithKeys(function ($v, $k) {
            return [$k => $v[0]];
        })->all();
    }

    public function header($header)
    {
        return $this->response->getHeaderLine($header);
    }

    public function json()
    {
        return json_decode($this->response->getBody(), true);
    }

    public function __call($name, $arguments)
    {
        // TODO: Implement __call() method.
        return call_user_func_array([$this->response,$name], $arguments);
    }

    public function status()
    {
        return $this->response->getStatusCode();
    }

    public function __toString()
    {
        return $this->body();
    }
}
/**
 *
 */
class Request
{

    public function __construct(RequestInterface $request)
    {
        $this->request = $request;
    }

    public function  header($name)
    {

        return $this->request->getHeaderLine($name);
        
    }

    public function __call($name, $arguments)
    {
        return call_user_func_array([$this->request,$name], $arguments);
    }
}


function tap($value, $callback)
{
    $callback($value);
    return $value;
}

class ConnectionException extends \Exception
{
}
