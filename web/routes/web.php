<?php

if (isset($router)) {
    // Direcciones de web aca
    $router->get('/', 'controllers\Home@index');
    $router->get('/nuevaCampania','controllers\Home@nuevaCampania');
    $router->get('/campania(\d+)', 'controllers\Home@campania');

    $router->set404('controllers\Error@page404');
}
