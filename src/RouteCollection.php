<?php

namespace src;

class RouteCollection
{
    protected $routesPost = [];
    protected $routesGet = [];
    protected $routesPut = [];
    protected $routesDelete = [];
    protected $routeNames = [];

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
        $pattern = '/^' . str_replace('/','\/', $pattern) . '$/';

        if (preg_match("/\{[A-Za-z0-9\_\-]{1,}\}/", $pattern))
        {
            $pattern = preg_replace("/\{[A-Za-z0-9\_\-]{1,}\}/","[A-Za-z0-9]{1,}", $pattern);
        }

        return $pattern;
    }

    public function strPosArray(String $haystack, array $needles, int $offset = 0)
    {
        $result = false;

        if(strlen($haystack) > 0 && count($needles) > 0)
        {
            foreach($needles as $element)
            {
                $result = strpos($haystack, $element, $offset);
                if($result !== false)
                {
                    break;
                }
            }
        }
        return $result;
    }

    public function toMap($pattern)
    {
        $result = [];

        $needles = ['{','[','(','\\'];

        $pattern = array_filter(explode('/',$pattern));

        foreach($pattern as $key => $element)
        {
            $found = $this->strPosArray($element, $needles);

            if($found !== false)
            {
                if(substr($element, 0, 1) === '{')
                {
                    $result[preg_filter('/([\{\}]/', '', $element)] = $key -1;
                }else {
                    $index = 'value_' . !empty($result) ? count($result) +1 : 1;
                    array_merge($result, [$index => $key -1]);
                }
            }
        }
        return count($result) > 0 ? $result : false;
    }

    protected function parsePattern(array $pattern)
    {
        //Define the pattern
        $result['set'] = $pattern['set'] ?? null;
        //Allows route name settings
        $result['as'] = $pattern['as'] ?? null;
        //Allows new namespace definition for Controllers
        $result['namespace'] = $pattern['namespace'] ?? null;

        return $result;
    }

    public function isThereAnyHow($name)
    {
        return $this->routeNames[$name] ?? false;
    }

    public function convert($pattern, $params)
    {
        if(!is_array($params))
        {
            $params = [$params];
        }

        $positions = $this->toMap($pattern);

        if($positions === false)
        {
            $positions = [];
        }
        $pattern = array_filter(explode('/', $pattern));
    
        if(count($positions) < count($pattern))
        {
            $uri = [];
            foreach($pattern as $key => $element)
            {
                if(in_array($key - 1, $positions))
                {
                    $uri[] = array_shift($params);
                } else {
                    $uri[] = $element;
                }
            }
            return implode('/', array_filter($uri));
    
        }
        return false;
 
    }

    public function addPost($pattern, $callback)
    {
        if(is_array($pattern))
        {
            $settings = $this->parsePattern($pattern);

            $pattern = $settings['set'];
        }else{
            $settings = [];
        }

        $values = $this->toMap($pattern);

        $this->routesPost[$this->definePattern($pattern)] = [
        'callback' => $callback,
        'values' => $values,
        'namespace' => $settings['namespace'] ?? null
        ];
        
        if(isset($settings['as']))
        {
            $this->routeNames[$settings['as']] = $pattern;
        }

        return $this;
    }

    public function addGet($pattern, $callback)
    {
        if(is_array($pattern))
        {
            $settings = $this->parsePattern($pattern);

            $pattern = $settings['set'];
        }else{
            $settings = [];
        }

        $values = $this->toMap($pattern);

        $this->routesGe[$this->definePattern($pattern)] = [
        'callback' => $callback,
        'values' => $values,
        'namespace' => $settings['namespace'] ?? null
        ];
        
        if(isset($settings['as']))
        {
            $this->routeNames[$settings['as']] = $pattern;
        }

        return $this;
    }

    public function addPut($pattern, $callback)
    {
        if(is_array($pattern))
        {
            $settings = $this->parsePattern($pattern);

            $pattern = $settings['set'];
        }else{
            $settings = [];
        }

        $values = $this->toMap($pattern);

        $this->routesPost[$this->definePattern($pattern)] = [
        'callback' => $callback,
        'values' => $values,
        'namespace' => $settings['namespace'] ?? null
        ];
        
        if(isset($settings['as']))
        {
            $this->routeNames[$settings['as']] = $pattern;
        }

        return $this;
    }

    public function addDelete($pattern, $callback)
    {
        if(is_array($pattern))
        {
            $settings = $this->parsePattern($pattern);

            $pattern = $settings['set'];
        }else{
            $settings = [];
        }

        $values = $this->toMap($pattern);

        $this->routesPost[$this->definePattern($pattern)] = [
        'callback' => $callback,
        'values' => $values,
        'namespace' => $settings['namespace'] ?? null
        ];
        
        if(isset($settings['as']))
        {
            $this->routeNames[$settings['as']] = $pattern;
        }

        return $this;
    }
}