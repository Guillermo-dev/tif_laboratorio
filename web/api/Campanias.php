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
            if ($cliente != null)
                $clienteId = $cliente->getId();
            else
                throw new Exception('El cliente no existe');
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

        //DODO:
        if(date('y-m-d') == $data['campania']->fechaInicio){
            $campania->setEstado('en ejecucion');
        }else if (date('y-m-d') < $data['campania']->fechaInicio) {
            $campania->setEstado('creada');
        }else throw new Exception('Fecha invalida');

        if (isset($data['campania']->fechaInicio))
            $campania->setFechaInicio($data['campania']->fechaInicio);
        else throw new Exception('Fecha de inicio requerida');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('Fecha de inicio invalida');

        $campania->setClienteId($clienteId);

        Campania::createCampania($campania);

        foreach ($localidadesIds as $localidadId) {
            Campania::createCampaniaLocalida($campania->getId(), $localidadId);
        }
    }

    public static function updateCampania(int $id): void {
        $data = Request::getBodyAsJson();

        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe');

        if (isset($data['nombre']))
            $campania->setNombre($data['nombre']);

        if (isset($data['textSMS']))
            $campania->setTextoSMS($data['textSMS']);

        if (isset($data['cantidadMensajes']))
            $campania->setCantidadMensajes($data['cantidadMensajes']);

        if (isset($data['estado']))
            $campania->setEstado($data['estado']);

        if (isset($data['fechaInicio']))
            $campania->setFechaInicio($data['fechaInicio']);

        //DODO:
        if(date('y-m-d') == $data['fechaInicio']){
            $campania->setEstado('en ejecucion');
        }else if (date('y-m-d') < $data['fechaInicio']) {
            $campania->setEstado('creada');
        }else throw new Exception('Fecha invalida');

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

        if (isset($data['cliente_id'])) {
            $cliente = Cliente::getClienteById($data['cliente_id']);
            if ($cliente != null)
                $campania->setClienteId($cliente->getId());
        }

        Campania::deleteCampaniasLocalidades($data['cliente_id']);
        foreach ($localidadesIds as $localidadId) {
            Campania::createCampaniaLocalida($campania->getId(), $localidadId);
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
