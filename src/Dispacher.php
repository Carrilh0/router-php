<?php

namespace src;

class Dispacher
{
    public function dispach($callback, $params = [], $namespace = "App\\")
    {
        if(is_callable($callback))
        {
            return call_user_func_array($callback, array_values($params));
        } else if (is_string($callback)){
            if(!!strpos($callback,'@') !== false){
                $callback = explode('@', $callback);
                $controller = $namespace.$callback[0];
                $method = $callback[1];

                $rc = new \ReflectionClass($controller);

                if($rc->isInstantiable() && $rc->hasMethod($method))
                {
                    return call_user_func_array(array(new $controller, $method), array_values($params));
                }else {
                    throw new \Exception("Erro ao despachar: controller não pode ser instanciado, ou metodo não existe!");
                }
            }
        }
        throw new \Exception("Erro ao despachar: método não implementado");
    }
}