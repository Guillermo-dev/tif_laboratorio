<?php

use Bramus\Router\Router;

include_once 'vendor/autoload.php';

$router = new Router();

include_once 'routes/web.php';

try {
    $router->run();
} catch (Exception $e) {
    
}
