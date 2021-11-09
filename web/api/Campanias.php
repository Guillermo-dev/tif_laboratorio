<?php

namespace api;

use Models\Campania;
use api\util\Response;
use api\util\Request;
use Exception;

abstract class Campanias {

    public static function getCampanias(): void {
        Response::getResponse()->appendData('campanias', Campania::getCampanias());
    }

    public static function createCampania(): void {
        //TODO: $campaniasData => $data
        $campaniaData = Request::getBodyAsJson();
        if (!isset($campaniaData->nombre))
            throw new Exception('Bad Request', Response::BAD_REQUEST);

        $campania = new Campania();
        Campania::createCampania($campania);
    }

    public static function updateCampania(int $id): void {
        $campaniaData = Request::getBodyAsJson();
        if (!isset($campaniaData->nombre))
            throw new Exception('Bad Request', Response::BAD_REQUEST);

        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe', Response::NOT_FOUND);

        Campania::updateCampania($campania);
    }

    public static function deleteCampania(int $id): void {
        $campania = Campania::getCampaniaById($id);
        if (!$campania)
            throw new Exception('La campania no existe', Response::NOT_FOUND);

        Campania::deleteCampania($campania->getId());
    }
}
