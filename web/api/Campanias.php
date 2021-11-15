<?php

namespace api;

use Models\Campania;
use models\Cliente;
use models\Localidad;
use api\util\Response;
use api\util\Request;
use Exception;

abstract class Campanias {

    public static function getCampanias(): void {
        if (isset($_GET['search'])) {
            $campanias = Campania::getCampaniasSearch($_GET['search']);
            foreach ($campanias as $campania) {
                $campania['cliente'] = Cliente::getClienteByCampaniaId($campania['campania_id']);
                $campania['localidades'] = Localidad::getLocalidadesByCampaniaId($campania['campania_id']);
                Response::getResponse()->appendData('campanias', $campania);
            }
        } else {
            $campanias = Campania::getCampanias();
            foreach ($campanias as $campania) {
                $campania['cliente'] = Cliente::getClienteByCampaniaId($campania['campania_id']);
                $campania['localidades'] = Localidad::getLocalidadesByCampaniaId($campania['campania_id']);
                Response::getResponse()->appendData('campanias', $campania);
            }
        }
    }


    public static function getCampania(int $id = 0): void {
        response::getResponse()->appendData('campania', Campania::getCampaniaById($id));
        response::getResponse()->appendData('cliente', Cliente::getClienteByCampaniaId($id));
        response::getResponse()->appendData('localidades', Localidad::getLocalidadesByCampaniaId($id));
    }

    public static function createCampania(): void {
        $data = Request::getBodyAsJson();
        if (!isset($data['campania']))
            throw new Exception('Bad Request');
        if (!isset($data['cliente']))
            throw new Exception('Bad Request');
        if (!isset($data['localidades']))
            throw new Exception('Bad Request');

        $localidadesIds = [];
        foreach ($_GET['localidades'] as $localidadData) {
            $localidad = Localidad::getLocaldadByPaisProvCiud($localidadData->pais, $localidadData->provincia, $localidadData->ciudad);
            if ($localidad) {
                $localidadesIds[] = $localidad->getId();
            } else
                throw new Exception('La localidad no existe');
            /* TODO: O crear una nueva localidad
                
                    $localidad = new Localidad();
                    if (isset($localidadData->pais))
                        $localidad->setPais($localidadData->pais);
                    else throw new Exception('Bad Request');
    
                    if (isset($localidadData->provincia))
                        $localidad->setProvincia($localidadData->provincia);
                    else throw new Exception('Bad Request');
    
                    if (isset($localidadData->ciudad))
                        $localidad->setCiudad($localidadData->ciudad);
                    else throw new Exception('Bad Request');
    
                    $localidadesIds[] = Localidad::createLocalidad($localidad);*/
        }

        $cliente = Cliente::getClienteByCuilCuit($_GET['cliente']->cuilCuit);
        if ($cliente) {
            $clienteId = $cliente->getId();
        } else {
            $cliente = new Cliente();
            if (isset($_GET['cliente']->cuilCuit))
                $cliente->setCuilCuit($_GET['cliente']->cuilCuit);
            else throw new Exception('Bad Request');

            if (isset($_GET['cliente']->razonSocial))
                $cliente->setRazonSocial($_GET['cliente']->razonSocial);
            else throw new Exception('Bad Request');

            if (isset($_GET['cliente']->nombre))
                $cliente->setNombre($_GET['cliente']->nombre);
            else throw new Exception('Bad Request');

            if (isset($_GET['cliente']->apellido))
                $cliente->setApellido($_GET['cliente']->apellido);
            else throw new Exception('Bad Request');

            if (isset($_GET['cliente']->telefono))
                $cliente->setTelefono($_GET['cliente']->telefono);
            else throw new Exception('Bad Request');

            if (isset($_GET['cliente']->email))
                $cliente->setEmail($_GET['cliente']->email);
            else throw new Exception('Bad Request');

            $clienteId = Cliente::createCliente($cliente);
        }

        $campania = new Campania();
        if (isset($_GET['campania']->nombre))
            $campania->setNombre($_GET['campania']->nombre);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->textSMS))
            $campania->setTextoSMS($_GET['campania']->textSMS);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->cantidadMensajes))
            $campania->setCantidadMensajes($_GET['campania']->cantidadMensajes);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->estado))
            $campania->setEstado($_GET['campania']->estado);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->fechaInicio))
            $campania->setFechaInicio($_GET['campania']->fechaInicio);
        else throw new Exception('Bad Request');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('Fecha invalida');

        $campania->setClienteId($clienteId);

        $campaniaId = Campania::createCampania($campania);

        foreach ($localidadesIds as $localidadId) {
            Campania::createCampaniaLocalida($campaniaId, $localidadId);
        }
    }

    public static function updateCampania(int $id): void {
        $data = Request::getBodyAsJson();
        if (!isset($data['campania']))
            throw new Exception('Bad Request');
        if (!isset($data['cliente']))
            throw new Exception('Bad Request');
        if (!isset($data['localidades']))
            throw new Exception('Bad Request');

        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe');

        if (isset($_GET['campania']->nombre))
            $campania->setNombre($_GET['campania']->nombre);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->textSMS))
            $campania->setTextoSMS($_GET['campania']->textSMS);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->cantidadMensajes))
            $campania->setCantidadMensajes($_GET['campania']->cantidadMensajes);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->estado))
            $campania->setEstado($_GET['campania']->estado);
        else throw new Exception('Bad Request');

        if (isset($_GET['campania']->fechaInicio))
            $campania->setFechaInicio($_GET['campania']->fechaInicio);
        else throw new Exception('Bad Request');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('Fecha invalida');

        $localidadesIds = [];
        foreach ($_GET['localidades'] as $localidadData) {
            $localidad = Localidad::getLocaldadByPaisProvCiud($localidadData->pais, $localidadData->provincia, $localidadData->ciudad);
            if ($localidad) {
                $localidadesIds[] = $localidad->getId();
            } else
                throw new Exception('La localidad no existe');
        }
        Campania::deleteCampaniaLocalida($data['campania']->id_campania);
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
