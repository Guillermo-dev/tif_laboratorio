<?php

namespace models;

use JsonSerializable;
use Exception;

class Localidad implements JsonSerializable {

    private $id;

    private $pais;

    private $provincia;

    private $ciudad;

    public function __construct(int $id = 0, string $pais = '', string $provincia = '', string $ciudad = '') {
        $this->id = $id;
        $this->pais = $pais;
        $this->provincia = $provincia;
        $this->ciudad = $ciudad;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getPais(): string {
        return $this->pais;
    }

    public function getProvincia(): string {
        return $this->provincia;
    }

    public function getciudad(): string {
        return $this->ciudad;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setPais(string $pais) {
        $this->pais = $pais;
    }

    public function setProvincia(string $provincia) {
        $this->provincia = $provincia;
    }

    public function setCiudad(string $ciudad) {
        $this->ciudad = $ciudad;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**************************** Metodos BD ****************************/

    public static function getLocalidades(): array {
        $database = Connection::getDatabase();

        $localidades = null;
        $localidades = $database->select('localidades', [
            'localidad_id',
            'pais',
            'provincia',
            'ciudad'
        ]);

        if (isset($database->error))
            throw new Exception('Localidades no encontradas: ' . $database->error);

        return $localidades;
    }

    public static function createLocalidad(Localidad $localidad): void {
        $database = Connection::getDatabase();

        $database->insert('localidades', [
            'localidad_id' => $localidad->getId(),
            'pais' => $localidad->getPais(),
            'provincia' => $localidad->getProvincia(),
            'ciudad' => $localidad->getciudad()
        ]);

        if (isset($database->error))
            throw new Exception('Error al crear la localidad: ' . $database->error);
    }

    public static function updateLocalidad(Localidad $localidad): void {
        $database = Connection::getDatabase();

        $database->update('localidades', [
            'localidad_id' => $localidad->getId(),
            'pais' => $localidad->getPais(),
            'provincia' => $localidad->getProvincia(),
            'ciudad' => $localidad->getciudad()
        ], [
            'localidad_id' => $localidad->getId()
        ]);

        if (isset($database->error))
            throw new Exception('Error al actualizar la localidad ' . $database->error);
    }

    public static function deleteLocalidad(int $localidadId) {
        $database = Connection::getDatabase();

        $database->delete('localidades', [
            'AND' => [
                'localidad_id' => $localidadId
            ]
        ]);

        if (isset($database->error))
            throw new Exception('Error al borrar la localidad: ' . $database->error);
    }

    //TODO: campanias_localidades
}
