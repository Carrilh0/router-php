<?php
 
namespace Src;
 
use src\Request;
use src\Dispacher;
use src\RouteCollection;
 
class Router 
{
     
    protected $routeCollection;
    protected $dispacher;

    public function __construct()
    {
        $this->routeCollection = new RouteCollection;
        $this->dispacher = new Dispacher;
    }

    protected function getValues($pattern, $positions)
    {
        $result = [];

        $pattern = array_filter(explode('/', $pattern));

        foreach($pattern as $key => $value)
        {
            if(in_array($key, $positions))
            {
                $result[array_search($key, $positions)] = $value;
            }
        }

        return $result;
    }

    protected function dispach($route, $params, $namespace = "app\\")
    {
        return $this->dispacher->dispach($route->callback, $params, $namespace);
    }

    protected function notFound()
    {
        return header("HTTP/1.0 404 Not Found",true ,404);
    }

    public function resolve($request)
    {
        $route = $this->find($request->method(), $request->uri());
        if($route)
        {
            $params = $route->callback['values'] ? $this->getValues($request->uri(), $route->callback['values']) : [];

            return $this->dispach($route, $params);
        }
        return $this->notFound();
    }

    public function route($name, $params)
    {
        $pattern = $this->routeCollection->isThereAnyHow($name);

        if($pattern)
        {
            $protocol = isset($_SERVER['HTTPS']) ? 'https://' : 'http://';
            $server = $_SERVER['SERVER_NAME'] . '/';
            $uri = [];

            foreach(array_filter(explode('/', $_SERVER['REQUEST_URI'])) as $key => $value)
            {
                if($value == 'public')
                {
                    $uri[] = $value;
                    break;
                }
                $uri[] = $value;
            }
            $uri = implode('/', array_filter($uri)) . '/';

            return $protocol . $server . $uri . $this->routeCollection->convert($pattern, $params);
        }
        return false;
    }

    public function get($pattern, $callback)
    {
        $this->routeCollection->add('get', $pattern, $callback);
        return $this;
    }

    public function post($pattern, $callback)
    {
        $this->routeCollection->add('post', $pattern, $callback);
        return $this;
    }

    public function put($pattern, $callback)
    {
        $this->routeCollection->add('put', $pattern, $callback);
        return $this;
    }

    public function delete($pattern, $callback)
    {
        $this->routeCollection->add('delete', $pattern, $callback);
        return $this;
    }

    public function find($requestType, $pattern)
    {
        return $this->routeCollection->where($requestType, $pattern);
    }

}