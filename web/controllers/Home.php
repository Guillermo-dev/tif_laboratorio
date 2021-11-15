<?php

namespace controllers;

abstract class Home {

    public static function index(): void {
        echo file_get_contents('src/pages/home/home.html');
    }
}
