<?php 
require_once('./vendor/autoload.php');

use src\router\RouterClass;

$uri = $_SERVER['REQUEST_URI']; 

var_dump(new RouterClass($uri));