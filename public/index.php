<?php
 
require __DIR__ . '/../bootstrap.php';
 
$request = new src\Request;
 
$router->resolve($request);