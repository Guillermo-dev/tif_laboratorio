<?php

namespace controllers;

abstract class Error {

    public static function page404(): void {
        header('HTTP/1.0 404 Not Found');
        echo file_get_contents('src/pages/page-404/page-404.html');
    }
}
