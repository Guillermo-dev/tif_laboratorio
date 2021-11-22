<?php

namespace controllers;

abstract class Home {

    public static function index(): void {
        echo file_get_contents('src/pages/home/home.html');
    }

    public static function nuevaCampania(): void {
        echo file_get_contents('src/pages/nuevaCampania/nuevaCampania.html');
    }

    public static function editarCampania():void {
        echo file_get_contents('src/pages/editarCampania/editarCampania.html');
    }

    public static function nuevoCliente(): void{
        echo file_get_contents('src/pages/nuevoCliente/nuevoCliente.html');
    }

    public static function clientes(): void{
        echo file_get_contents('src/pages/clientes/clientes.html');
    }

    public static function editarCliente(): void{
        echo file_get_contents('src/pages/editarCliente/editarCliente.html');
    }
}
