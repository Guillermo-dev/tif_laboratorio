<?php

if (isset($router)) {
    // Direcciones de web aca
    $router->get('/', 'controllers\Home@index');
    $router->get('/nuevaCampania','controllers\Home@nuevaCampania');
    $router->get('/editarCampania/(\d+)', 'controllers\Home@editarCampania');
    $router->get('/nuevoCliente' , 'controllers\Home@nuevoCliente');
    $router->get('/clientes' , 'controllers\Home@clientes');
    $router->get('/editarCliente/(\d+)' , 'controllers\Home@editarCliente');
    



    $router->set404('controllers\Error@page404');
}
