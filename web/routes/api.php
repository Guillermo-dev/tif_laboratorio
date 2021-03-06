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
    $router->post('/campanias', 'api\Campanias@createCampania');
    $router->put('/campanias/(\d+)', 'api\Campanias@updateCampania');
    $router->delete('/campanias/(\d+)', 'api\Campanias@deleteCampania');

    $router->get('/clientes', 'api\Clientes@getClientes');
    $router->get('/clientes/(\d+)', 'api\Clientes@getCliente');
    $router->post('/clientes', 'api\Clientes@createCliente');
    $router->put('/clientes/(\d+)', 'api\Clientes@updateCliente');
    $router->delete('/clientes/(\d+)', 'api\Clientes@deleteCliente');

    $router->get('/localidades', 'api\Localidades@getLocalidades');

    $router->set404(function () {
        Response::getResponse()->setStatus('error');
        Response::getResponse()->setError('The end point does not exist', 'NOT FOUND');
        Response::getResponse()->setData(null);
    });
}

