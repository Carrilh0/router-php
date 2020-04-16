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
        return $this->routeCollection->where($requestType);
    }

}