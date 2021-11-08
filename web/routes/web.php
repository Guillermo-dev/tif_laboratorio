<?php

if (isset($router)) {
    // Direcciones de web aca
    $router->get('/', 'controllers\Home@index');

    $router->set404('controllers\Error@page404');
}
