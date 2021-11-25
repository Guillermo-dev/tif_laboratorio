<?php

namespace api;

use models\Cliente;
use api\util\Response;
use api\util\Request;
use Exception;

abstract class Clientes {

    public static function getClientes(): void {
        if (isset($_GET['search']))
            Response::getResponse()->appendData('clientes', Cliente::getClientesSearch($_GET['search']));
        else
            Response::getResponse()->appendData('clientes', Cliente::getClientes());
    }

    public static function getCliente(int $id): void {
        if (isset($_GET['cuil_cuit']))
            response::getResponse()->appendData('cliente', Cliente::getClienteByCuil($_GET['cuil_cuit']));
        else
            response::getResponse()->appendData('cliente', Cliente::getClienteById($id));
    }

    public static function createCliente(): void {
        $data = Request::getBodyAsJson();

        $cliente = new Cliente();

        if (isset($data->cuil_cuit))
            $cliente->setCuilCuit($data->cuil_cuit);
        else throw new Exception('Cuil/Cuit de cliente requerido');

        if (isset($data->razon_social))
            $cliente->setRazonSocial($data->razon_social);
        else throw new Exception('Razon social de cliente requerida');

        if (isset($data->nombre))
            $cliente->setNombre($data->nombre);
        else throw new Exception('Nombre de cliente requerido');

        if (isset($data->apellido))
            $cliente->setApellido($data->apellido);
        else throw new Exception('Apellido de cliente requerido');

        if (isset($data->telefono))
            $cliente->setTelefono($data->telefono);
        else throw new Exception('Telefono de cliente requerido');

        if (isset($data->email))
            $cliente->setEmail($data->email);
        else throw new Exception('Email de cliente requerido');

        Cliente::createCliente($cliente);
    }

    public static function updateCliente(int $id): void {
        $data = Request::getBodyAsJson();

        $cliente = Cliente::getClienteById($id);
        if ($cliente == null)
            throw new Exception('El cliente no existe');
        else {
            if (isset($data->cuil_cuit))
                $cliente->setCuilCuit($data->cuil_cuit);

            if (isset($data->razon_social))
                $cliente->setRazonSocial($data->razon_social);

            if (isset($data->nombre))
                $cliente->setNombre($data->nombre);

            if (isset($data->apellido))
                $cliente->setApellido($data->apellido);

            if (isset($data->telefono))
                $cliente->setTelefono($data->telefono);

            if (isset($data->email))
                $cliente->setEmail($data->email);

            Cliente::updateCliente($cliente);
        }
    }

    public static function deleteCliente(int $id): void {
        $cliente = Cliente::getClienteById($id);
        if ($cliente == null)
            throw new Exception('El cliente no existe');

        Cliente::deleteCliente($id);
    }
}
