<?php

namespace api\util;

use stdClass;

class Request {

    private function __construct() { }

    public static function getBodyAsJson(): stdClass {
        return json_decode(file_get_contents('php://input'), false, 512, JSON_THROW_ON_ERROR);
    }
}