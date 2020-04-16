<?php

namespace src;

class RouteCollection
{
    protected $routesPost = [];
    protected $routesGet = [];
    protected $routesPut = [];
    protected $routesDelete = [];

    public function add($request_type, $pattern, $callback)
    {
        switch($request_type)
        {
            case 'post':
                return $this->addPost($pattern, $callback);
                break;
            case 'get':
                return $this->addGet($pattern, $callback);
                break;
            case 'put':
                return $this->addPut($pattern, $callback);
                break;
            case 'delete':
                return $this->addDelete($pattern, $callback);
                break;
            default:
                throw new \Exception('Tipo de requisição não implementado');
        }
    }

    public function where($request_type, $pattern)
    {
        switch($request_type)
        {
            case 'post':
                return $this->findPost($pattern);
                break;
            case 'get':
                return $this->findGet($pattern);
                break;
            case 'put':
                return $this->findPut($pattern);
                break;
            case 'delete':
                return $this->findDelete($pattern);
                break;
            default:
                throw new \Exception('Tipo de requisição não implementada!');
        }
    }

    public function parseUri($uri)
    {
        return implode('/', array_filter(explode('/', $uri)));
    }

    protected function findPost($pattern_sent)
    {
        $pattern_sent = $this->parseUri($pattern_sent);

        foreach($this->routesPost as $pattern => $callback)
        {
            if(preg_match($pattern, $pattern_sent, $pieces))
            {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        } 
        return false;
    }
    protected function findGet($pattern_sent)
    {
        $pattern_sent = $this->parseUri($pattern_sent);

        foreach($this->routesGet as $pattern => $callback)
        {
            if(preg_match($pattern, $pattern_sent, $pieces))
            {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        }             
        return false;
    }
    protected function findPut($pattern_sent)
    {
        $pattern_sent = $this->parseUri($pattern_sent);

        foreach($this->routesPut as $pattern => $callback)
        {
            if(preg_match($pattern, $pattern_sent, $pieces))
            {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
        } 
        return false;
    }
    protected function findDelete($pattern_sent)
    {
        $pattern_sent = $this->parseUri($pattern_sent);

        foreach($this->routesDelete as $pattern => $callback)
        {
            if(preg_match($pattern, $pattern_sent, $pieces))
            {
                return (object) ['callback' => $callback, 'uri' => $pieces];
            }
            return false;
        }
        return false;

    }

    public function definePattern($pattern)
    {
        $pattern = implode('/', array_filter(explode('/',$pattern)));
        return '/^' . str_replace('/', '\/', $pattern) . '$/';
    }

    public function addPost($pattern, $callback)
    {
        $this->routesPost[$this->definePattern($pattern)] = $callback;
        return $this;
    }

    public function addGet($pattern, $callback)
    {
        $this->routesGet[$this->definePattern($pattern)] = $callback;
        return $this;
    }

    public function addPut($pattern, $callback)
    {
        $this->routesPut[$this->definePattern($pattern)] = $callback;
        return $this;
    }

    public function addDelete($pattern, $callback)
    {
        $this->routesDelete[$this->definePattern($pattern)] = $callback;
        return $this;
    }
}