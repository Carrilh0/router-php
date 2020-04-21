<?php

$router->get(['set' => '/', 'as' => 'home'], function() use($router){
    echo '<a href="' . $router->route('contatos.show', 1) . '">Clique aqui para testar a rota clientes.edit</a>';
});

$router->get(['set' => '/contatos/{id}', 'as' => 'contatos.show'], function($id){
    echo $id;
});


 