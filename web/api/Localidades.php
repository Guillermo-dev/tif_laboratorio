<?php

namespace api;

use models\Localidad;
use api\util\Response;

abstract class Localidades {

    public static function getLocalidades(): void {
        Response::getResponse()->appendData('localidades', Localidad::getLocalidades());
    }
}