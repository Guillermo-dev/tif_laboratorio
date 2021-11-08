<?php

use api\util\Response;
use Bramus\router\Router;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/api.php';

try {
    $router->run();
} catch (Exception $e) {
    var_dump($e);
}

Response::getResponse()->send();