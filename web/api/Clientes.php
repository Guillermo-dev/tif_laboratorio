<?php

namespace api;

use models\Cliente;
use api\util\Response;
use api\util\Request;
use Exception;

abstract class Clientes {

    public static function getClientes(): void {
        if (isset($_GET['search']))
            Response::getResponse()->appendData('campanias', Cliente::getClientesSearch($_GET['search']));
        else
            Response::getResponse()->appendData('campanias', Cliente::getClientes());
    }

    public static function getCliente(int $id): void {
        if (isset($_GET['cuil_cuit']))
            response::getResponse()->appendData('cliente', Cliente::getClienteByCuil($_GET['cuil_cuit']));
        else
            response::getResponse()->appendData('cliente', Cliente::getClienteById($id));
    }

    public static function createCliente(): void {
        $data = Request::getBodyAsJson();

        if (!isset($data['cliente']))
            throw new Exception('Datos del cliente requerido');
        else {
            $cliente = new Cliente();

            if (isset($data['cliente']->cuilCuit))
                $cliente->setCuilCuit($data['cliente']->cuilCuit);
            else throw new Exception('Cuil/Cuit de cliente requerido');

            if (isset($data['cliente']->razonSocial))
                $cliente->setRazonSocial($data['cliente']->razonSocial);
            else throw new Exception('Razon social de cliente requerida');

            if (isset($data['cliente']->nombre))
                $cliente->setNombre($data['cliente']->nombre);
            else throw new Exception('Nombre de cliente requerido');

            if (isset($data['cliente']->apellido))
                $cliente->setApellido($data['cliente']->apellido);
            else throw new Exception('Apellido de cliente requerido');

            if (isset($data['cliente']->telefono))
                $cliente->setTelefono($data['cliente']->telefono);
            else throw new Exception('Telefono de cliente requerido');

            if (isset($data['cliente']->email))
                $cliente->setEmail($data['cliente']->email);
            else throw new Exception('Email de cliente requerido');

            Cliente::createCliente($cliente);
        }
    }

    public static function updateCliente(int $id): void {
        $data = Request::getBodyAsJson();

        if (!isset($data['cliente']))
            throw new Exception('Datos del cliente requerido');
        else {
            $cliente = Cliente::getClienteById($id);
            if ($cliente == null)
                throw new Exception('El cliente no existe');
            else {
                if (isset($data['cliente']->cuilCuit))
                    $cliente->setCuilCuit($data['cliente']->cuilCuit);

                if (isset($data['cliente']->razonSocial))
                    $cliente->setRazonSocial($data['cliente']->razonSocial);

                if (isset($data['cliente']->nombre))
                    $cliente->setNombre($data['cliente']->nombre);

                if (isset($data['cliente']->apellido))
                    $cliente->setApellido($data['cliente']->apellido);

                if (isset($data['cliente']->telefono))
                    $cliente->setTelefono($data['cliente']->telefono);

                if (isset($data['cliente']->email))
                    $cliente->setEmail($data['cliente']->email);

                Cliente::updateCliente($cliente);
            }
        }
    }

    public static function deleteCliente(int $id): void {
        $cliente = Cliente::getClienteById($id);
        if ($cliente == null)
            throw new Exception('El cliente no existe');

        Cliente::deleteCliente($id);
    }
}
