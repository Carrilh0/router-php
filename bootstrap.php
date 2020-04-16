<?php 
require_once __DIR__ . './vendor/autoload.php';

use src\Router;

session_start();

try {
    $router = new Router;
    require __DIR__ . '/routes/routes.php';
} catch(\Exception $e){
    echo $e->getMessage();
}