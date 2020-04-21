<?php

$router->get('/', function(){
    echo "Página inicial";
});

$router->get('/contatos/{id}/{teste}', function($id, $teste){
    echo $id,$teste;
});


 