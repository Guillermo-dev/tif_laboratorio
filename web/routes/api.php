<?php

use api\util\Response;

if (isset($router)) {
    $router->setBasePath('/api');

    $router->get('', function () {
        Response::getResponse()->appendData('message', 'Welcome!');
    });
    
    // Direcciones aca
    $router->get('/campanias', 'api\Campanias@getCampanias');

    $router->set404(function () {
        Response::getResponse()->setCode(Response::NOT_FOUND);
        Response::getResponse()->setError('The end point does not exist', Response::NOT_FOUND);
        Response::getResponse()->setData(null);
    });
}
