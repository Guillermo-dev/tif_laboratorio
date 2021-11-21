<?php

namespace api;

use models\Campania;
use models\Cliente;
use models\Localidad;
use api\util\Response;
use api\util\Request;
use Exception;

abstract class Campanias {

    public static function getCampanias(): void {
        if (isset($_GET['search']))
            Response::getResponse()->appendData('campanias', Campania::getCampaniasSearch($_GET['search']));
        else
            Response::getResponse()->appendData('campanias', Campania::getCampanias());
    }


    public static function getCampania(int $id = 0): void {
        response::getResponse()->appendData('campania', Campania::getCampaniaById($id));
        response::getResponse()->appendData('cliente', Cliente::getClienteByCampaniaId($id));
        response::getResponse()->appendData('localidades', Localidad::getLocalidadesByCampaniaId($id));
    }

    public static function createCampania(): void {
        $data = Request::getBodyAsJson();

        if (isset($data['localidades'])) {
            $localidadesArr = json_decode($data['localidades']);
            $localidadesIds = [];
            foreach ($localidadesArr as $localidadId) {
                $localidad = Localidad::getLocalidadById($localidadId);
                if ($localidad)
                    $localidadesIds[] = $localidad->getId();
                else
                    throw new Exception('La localidad no existe');
            }
        }

        if (isset($data['cliente'])) {
            $cliente = Cliente::getClienteById($data['cliente']->id);
            if ($cliente != null) {
                $clienteId = $cliente->getId();
            } else {
                throw new Exception('El cliente no existe');
                /* TODO: 
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

                $clienteId = Cliente::createCliente($cliente);
                */
            }
        } else
            throw new Exception('Datos del cliente requerido');
            
        $campania = new Campania();
        if (isset($data['campania']->nombre))
            $campania->setNombre($data['campania']->nombre);
        else throw new Exception('Nombre de campaña requerido');

        if (isset($data['campania']->textSMS))
            $campania->setTextoSMS($data['campania']->textSMS);
        else throw new Exception('Texto de SMS requerido');

        if (isset($data['campania']->cantidadMensajes))
            $campania->setCantidadMensajes($data['campania']->cantidadMensajes);
        else throw new Exception('Cantidad de mensajes requerido');

        $campania->setEstado('creada');

        if (isset($data['campania']->fechaInicio))
            $campania->setFechaInicio($data['campania']->fechaInicio);
        else throw new Exception('Fecha de inicio requerida');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('Fecha de inicio invalida');

        $campania->setClienteId($clienteId);

        $campaniaId = Campania::createCampania($campania);

        foreach ($localidadesIds as $localidadId) {
            Campania::createCampaniaLocalida($campaniaId, $localidadId);
        }
    }

    public static function updateCampania(int $id): void {
        $data = Request::getBodyAsJson();

        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe');

        if (isset($data['campania']->nombre))
            $campania->setNombre($data['campania']->nombre);

        if (isset($data['campania']->textSMS))
            $campania->setTextoSMS($data['campania']->textSMS);

        if (isset($data['campania']->cantidadMensajes))
            $campania->setCantidadMensajes($data['campania']->cantidadMensajes);

        if (isset($data['campania']->estado))
            $campania->setEstado($data['campania']->estado);

        if (isset($data['campania']->fechaInicio))
            $campania->setFechaInicio($data['campania']->fechaInicio);

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('Fecha invalida');

        if (isset($data['localidades'])) {
            $localidadesArr = json_decode($data['localidades']);
            $localidadesIds = [];
            foreach ($localidadesArr as $localidadId) {
                $localidad = Localidad::getLocalidadById($localidadId);
                if ($localidad)
                    $localidadesIds[] = $localidad->getId();
                else
                    throw new Exception('La localidad no existe');
            }
        }

        if (isset($data['cliente'])) {
            $cliente = Cliente::getClienteById($data['cliente']->id);
            if ($cliente != null)
                $campania->setClienteId($cliente->getId());
                /* TODO:
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

                $clienteId = Cliente::createCliente($cliente);
            } 
            */
        }

        Campania::deleteCampaniasLocalidades($data['campania']->id_campania);
        foreach ($localidadesIds as $localidadId) {
            Campania::createCampaniaLocalida($data['campania']->id_campania, $localidadId);
        }

        Campania::updateCampania($campania);
    }

    public static function deleteCampania(int $id): void {
        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe');

        Campania::deleteCampania($id);
    }
}
