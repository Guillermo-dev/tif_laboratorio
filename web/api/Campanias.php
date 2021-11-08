<?php

namespace api;

use Models\Campania;
use api\util\Response;
use api\util\Request;
use api\exceptions\ApiException;

abstract class Campanias {

    public static function getCampanias(): void {
        Response::getResponse()->appendData('campanias', Campania::getCampanias());
    }

    public static function createCampania(): void {
        $emisorData = Request::getBodyAsJson();
        if (!isset($emisorData->nombre))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        $campania = new Campania();
        Campania::createCampania($campania);
    }

    public static function updateCampania(int $id): void {
        $emisorData = Request::getBodyAsJson();
        if (!isset($emisorData->nombre))
            throw new ApiException('Bad Request', Response::BAD_REQUEST);

        //TODO: getByID
    }

    public static function deleteCampania(int $id): void {
        //TODO: getByID
    }
}
