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
            Response::getResponse()->appendData('campanias',  Campania::getCampaniasSearch($_GET['search']));
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

        if (isset($data->localidades)) {
            $localidadesIds = [];
            foreach ($data->localidades as $localidadId) {
                $localidad = Localidad::getLocalidadById(intval($localidadId, 10));
                if ($localidad != null)
                    $localidadesIds[] = $localidad->getId();
                else
                    throw new Exception('La localidad no existe');
            }
        }

        if (isset($data->cliente_id)) {
            $cliente = Cliente::getClienteById($data->cliente_id);
            if ($cliente != null)
                $clienteId = $cliente->getId();
            else
                throw new Exception('El cliente no existe');
        } else
            throw new Exception('Datos del cliente requerido');

        $campania = new Campania();
        if (isset($data->nombre))
            $campania->setNombre($data->nombre);
        else throw new Exception('Nombre de campaña requerido');

        if (isset($data->text_SMS))
            $campania->setTextoSMS($data->text_SMS);
        else throw new Exception('Texto de SMS requerido');

        if (isset($data->cantidad_mensajes))
            $campania->setCantidadMensajes($data->cantidad_mensajes);
        else throw new Exception('Cantidad de mensajes requerido');

        if (date('o-m-d') == $data->fecha_inicio) {
            $campania->setEstado('ejecucion');
        } else if (date('o-m-d') < $data->fecha_inicio) {
            $campania->setEstado('creada');
        } else throw new Exception('Fecha invalida');

        if (isset($data->fecha_inicio))
            $campania->setFechaInicio($data->fecha_inicio);
        else throw new Exception('Fecha de inicio requerida');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes()))
            throw new Exception('La cantidad de mensajes excede el maximo en el dia seleccionado');

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

        if (isset($data->nombre))
            $campania->setNombre($data->nombre);

        if (isset($data->text_SMS))
            $campania->setTextoSMS($data->text_SMS);

        if (isset($data->cantidad_mensajes))
            $campania->setCantidadMensajes($data->cantidad_mensajes);

        if (isset($data->estado))
            $campania->setEstado($data->estado);

        if (isset($data->fecha_inicio))
            $campania->setFechaInicio($data->fecha_inicio);

        if (date('o-m-d') == $data->fecha_inicio) {
            $campania->setEstado('ejecucion');
        } else if (date('o-m-d') < $data->fecha_inicio) {
            $campania->setEstado('creada');
        } else throw new Exception('Fecha invalida');

        if (Campania::invalidFecha($campania->getFechaInicio(), $campania->getCantidadMensajes(), $campania->getId()))
            throw new Exception('La cantidad de mensajes excede el maximo en el dia seleccionado');

        if (isset($data->localidades)) {
            $localidadesIds = [];
            foreach ($data->localidades as $localidadId) {
                $localidad = Localidad::getLocalidadById(intval($localidadId, 10));
                if ($localidad != null)
                    $localidadesIds[] = $localidad->getId();
                else
                    throw new Exception('La localidad no existe');
            }

            Campania::deleteCampaniasLocalidades($id);
            foreach ($localidadesIds as $localidadId) {
                Campania::createCampaniaLocalida($campania->getId(), $localidadId);
            }
        }

        if (isset($data->cliente_id)) {
            $cliente = Cliente::getClienteById($data->cliente_id);
            if ($cliente != null)
                $campania->setClienteId($cliente->getId());
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
