<?php 
require_once('./vendor/autoload.php');

use src\router\RouterClass;

$uri = $_SERVER['REQUEST_URI']; 

$router = new RouterClass($uri);

print_r($router->get('teste','Controller@index'));