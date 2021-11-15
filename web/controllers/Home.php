<?php

namespace controllers;

abstract class Home {

    public static function index(): void {
        echo file_get_contents('src/pages/home/home.html');
    }

    public static function nuevaCampania(): void {
        echo file_get_contents('src/pages/nuevaCampania/nuevaCampania.html');
    }

    public static function campania():void {
        echo file_get_contents('src/pages/campania/campania.html');
    }
}
