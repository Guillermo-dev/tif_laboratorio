<?php

namespace models;

use JsonSerializable;
use Exception;

class Campania implements JsonSerializable {

    private $id;

    private $nombre;

    private $textoSMS;

    private $cantidadMensajes;

    private $estado;

    private $clienteId;

    public function __construct(int $id = 0, string $nombre = '', string $textoSMS = '', string $cantidadMensajes = '', string $estado = '', int $clienteId = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->textoSMS = $textoSMS;
        $this->cantidadMensajes = $cantidadMensajes;
        $this->estado = $estado;
        $this->clienteId = $clienteId;
    }

    // Metodos de clase

    public function getId(): int {
        return $this->id;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getTextoSMS(): string {
        return $this->textoSMS;
    }

    public function getCantidadMensajes(): string {
        return $this->cantidadMensajes;
    }

    public function getEstado(): string {
        return $this->estado;
    }

    public function getClienteId(): string {
        return $this->clienteId;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function setTextoSMS(string $textoSMS) {
        $this->textoSMS = $textoSMS;
    }

    public function setCantidadMensajes(string $cantidadMensajes) {
        $this->$cantidadMensajes = $cantidadMensajes;
    }

    public function setEstado(string $estado) {
        $this->estado = $estado;
    }

    public function setClienteID(string $clienteId) {
        $this->clienteId = $clienteId;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**************************** Metodos BD ****************************/

    public static function getCampanias(): array {
        $database = Connection::getDatabase();

        $campanias= null;
        $campanias = $database->select('campanias', [
            'Campania_id',
            'nombre',
            'texto_SMS',
            'cantidad_mensajes',
            'estado',
            'cliente_id'
        ]);

        if (isset($database->error))
            throw new Exception('Companias no encontradas: '.$database->error);

        return $campanias;
    }

    public static function getCampaniaById(int $id): ?Campania {
        $database = Connection::getDatabase();

        $campanias= null;
        $campanias = $database->select('campanias', [
            'Campania_id',
            'nombre',
            'texto_SMS',
            'cantidad_mensajes',
            'estado',
            'cliente_id'
        ], [
            'campania_id' => $id
        ]);

        if (isset($database->error))
            throw new Exception('Compania no encontradas: '.$database->error);

        return $campanias;
    }

    public static function createCampania(Campania $campania): void {
        $database = Connection::getDatabase();

        $database->insert('campanias', [
            'nombre' => $campania->getNombre(),
            'texto_SMS' => $campania->getTextoSMS(),
            'cantidad_mensajes' => $campania->getCantidadMensajes(),
            'estado' => $campania->getEstado(),
            'cliente_id' => $campania->getCliente_id
        ]);

        if (isset($database->error))
            throw new Exception('Error al crear la campania: '.$database->error);
    }

    public static function updateCampania(Campania $campania): void {
        $database = Connection::getDatabase();

        $database->update('campanias', [
            'nombre' => $campania->getNombre(),
            'texto_SMS' => $campania->getTextoSMS(),
            'cantidad_mensajes' => $campania->getCantidadMensajes(),
            'estado' => $campania->getEstado(),
            'cliente_id' => $campania->getCliente_id
        ], [
            'campania_id' => $campania->getId()
        ]);

        if (isset($database->error))
            throw new Exception('Error al actualizar la campania: '.$database->error);
    }

    public static function deleteCampania(int $campaniaId) {
        $database = Connection::getDatabase();

        $database->delete('campanias', [
            'AND' => [
                'campania_id' => $campaniaId
            ]
        ]);

        if (isset($database->error))
        throw new Exception('Error al borrar la campania: '.$database->error);
    }
}