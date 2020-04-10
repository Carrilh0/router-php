<?php

namespace src\router;

Class RouterClass {

    private $uri;

    public function __construct(String $uri)
    {
        $this->uri = $uri;
    }

}