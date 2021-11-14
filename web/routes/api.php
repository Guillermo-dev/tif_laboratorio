<?php

use api\util\Response;

if (isset($router)) {
    $router->setBasePath('/api');

    $router->get('', function () {
        Response::getResponse()->appendData('message', 'Welcome!');
    });

    // Direcciones aca
    $router->get('/campanias', 'api\Campanias@getCampanias');
    $router->get('/campanias/(\d+)', 'api\Campanias@getCampania');

    $router->set404(function () {
        Response::getResponse()->setError('The end point does not exist', 'NOT FOUND');
        Response::getResponse()->setData(null);
    });
}

