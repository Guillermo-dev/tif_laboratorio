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

    private $fechaInicio;

    private $clienteId;

    public function __construct(int $id = 0, string $nombre = '', string $textoSMS = '', string $cantidadMensajes = '', string $estado = '', string $fechaInicio = '', int $clienteId = 0) {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->textoSMS = $textoSMS;
        $this->cantidadMensajes = $cantidadMensajes;
        $this->estado = $estado;
        $this->fechaInicio = $fechaInicio;
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

    public function getFechaInicio(): string {
        return $this->fechaInicio;
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
        $this->cantidadMensajes = $cantidadMensajes;
    }

    public function setEstado(string $estado) {
        $this->estado = $estado;
    }

    public function setFechaInicio(string $fechaInicio) {
        $this->fechaInicio = $fechaInicio;
    }

    public function setClienteId(string $clienteId) {
        $this->clienteId = $clienteId;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    public function updateEstado(): void {
        if (date('o-m-d') == $this->getFechaInicio()) {
            $this->setEstado('ejecucion');
        } else if (date('o-m-d') < $this->getFechaInicio()) {
            $this->setEstado('creada');
        } else
            $this->setEstado('finalizada');
    }

    /**************************** Metodos BD ****************************/

    public static function getCampanias(): array {
        $database = Connection::getDatabase();

        $database->update(
            'campanias', [
                'estado' => 'ejecucion'
            ],[
                'fecha_inicio' => date('o-m-d')
            ]
        );
        $database->update(
            'campanias', [
                'estado' => 'creada'
            ],[
                'fecha_inicio[>]' => date('o-m-d')
            ]
        );
        $database->update(
            'campanias', [
                'estado' => 'finalizada'
            ],[
                'fecha_inicio[<]' => date('o-m-d')
            ]
        );

        $campanias = [];
        $campanias = $database->select(
            'campanias',
            [
                'campania_id',
                'nombre',
                'texto_SMS',
                'cantidad_mensajes',
                'estado',
                'fecha_inicio',
                'cliente_id'
            ],
            [
                "ORDER" => ['fecha_inicio'  => "DESC"]
            ]
        );

        if (isset($database->error))
            throw new Exception($database->error);

        return $campanias;
    }

    public static function getCampaniasSearch(string $search): array {
        $database = Connection::getDatabase();

        $database->update(
            'campanias', [
                'estado' => 'ejecucion'
            ],[
                'fecha_inicio' => date('o-m-d')
            ]
        );
        $database->update(
            'campanias', [
                'estado' => 'creada'
            ],[
                'fecha_inicio[>]' => date('o-m-d')
            ]
        );
        $database->update(
            'campanias', [
                'estado' => 'finalizada'
            ],[
                'fecha_inicio[<]' => date('o-m-d')
            ]
        );

        $campanias = [];
        $campanias = $database->select(
            'campanias',
            [
                'campania_id',
                'nombre',
                'texto_SMS',
                'cantidad_mensajes',
                'estado',
                'fecha_inicio',
                'cliente_id'
            ],
            [
                'OR' => [
                    'nombre[~]' => $search,
                    'fecha_inicio[~]' => $search
                ],
                "ORDER" => ['fecha_inicio'  => "DESC"]
            ]
        );

        if (isset($database->error))
            throw new Exception($database->error);

        return $campanias;
    }

    public static function getCampaniaById(int $id): ?Campania {
        $database = Connection::getDatabase();

        $campanias = $database->select(
            'campanias',
            [
                'campania_id',
                'nombre',
                'texto_SMS',
                'cantidad_mensajes',
                'estado',
                'fecha_inicio',
                'cliente_id',
            ],
            [
                'campania_id' => $id
            ]
        );

        $campania = null;
        if (!empty($campanias)) {
            $campania = new Campania();
            $campania->setId($campanias[0]['campania_id']);
            $campania->setNombre($campanias[0]['nombre']);
            $campania->setTextoSMS($campanias[0]['texto_SMS']);
            $campania->setCantidadMensajes($campanias[0]['cantidad_mensajes']);
            $campania->setEstado($campanias[0]['estado']);
            $campania->setFechaInicio($campanias[0]['fecha_inicio']);
            $campania->setClienteId($campanias[0]['cliente_id']);
        }

        if (isset($database->error))
            throw new Exception($database->error);

        return $campania;
    }

    public static function createCampania(Campania $campania): void {
        $database = Connection::getDatabase();

        $database->insert('campanias', [
            'nombre' => $campania->getNombre(),
            'texto_SMS' => $campania->getTextoSMS(),
            'cantidad_mensajes' => $campania->getCantidadMensajes(),
            'estado' => $campania->getEstado(),
            'fecha_inicio' => $campania->getFechaInicio(),
            'cliente_id' => $campania->getClienteId(),
        ]);

        if (isset($database->error))
            throw new Exception($database->error);

        $campania->setId($database->id());
    }

    public static function updateCampania(Campania $campania): void {
        $database = Connection::getDatabase();

        $database->update('campanias', [
            'nombre' => $campania->getNombre(),
            'texto_SMS' => $campania->getTextoSMS(),
            'cantidad_mensajes' => $campania->getCantidadMensajes(),
            'estado' => $campania->getEstado(),
            'fecha_inicio' => $campania->getFechaInicio(),
            'cliente_id' => $campania->getClienteId(),
        ], [
            'campania_id' => $campania->getId()
        ]);

        if (isset($database->error))
            throw new Exception($database->error);
    }

    public static function deleteCampania(int $campaniaId) {
        $database = Connection::getDatabase();

        $database->delete('campanias', [
            'AND' => [
                'campania_id' => $campaniaId
            ]
        ]);

        if (isset($database->error))
            throw new Exception($database->error);
    }

    public static function invalidFecha(string $fecha, string $mensajesNuevos, int $id = 0): bool {
        $database = Connection::getDatabase();

        $campanias = $database->select(
            'campanias',
            [
                'campania_id',
                'nombre',
                'texto_SMS',
                'cantidad_mensajes',
                'estado',
                'fecha_inicio',
                'cliente_id',
            ],
            [
                'fecha_inicio' => $fecha
            ]
        );

        if (isset($database->error))
            throw new Exception($database->error);

        $cantidadMensajes = 0;
        foreach ($campanias as $campania) {
            if ($campania['campania_id'] != $id) {
                $cantidadMensajes += intval($campania['cantidad_mensajes'], 10);
            }
        }

        return $cantidadMensajes + intval($mensajesNuevos) > 70000;
    }

    public static function createCampaniaLocalida(int $campaniaId, int $localidadId): void {
        $database = Connection::getDatabase();

        $database->insert('campanias_localidades', [
            'campania_id' => $campaniaId,
            'localidad_id' => $localidadId,
        ]);

        if (isset($database->error))
            throw new Exception($database->error);
    }

    public static function deleteCampaniasLocalidades(int $campaniaId): void {
        $database = Connection::getDatabase();

        $database->delete('campanias_localidades', [
            'AND' => [
                'campania_id' => $campaniaId
            ]
        ]);

        if (isset($database->error))
            throw new Exception($database->error);
    }
}
