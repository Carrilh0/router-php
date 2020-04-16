<?php
 
require_once __DIR__ . '/../bootstrap.php';
 
$request = new src\Request;
 
echo $request->uri();