<?php

use api\util\Response;
use Bramus\router\Router;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/api.php';

try {
    $router->run();
} catch (Exception $e) {
    Response::getResponse()->setError($e->getMessage());
    Response::getResponse()->setStatus('error');
    Response::getResponse()->setData(null);
}
Response::getResponse()->send();
