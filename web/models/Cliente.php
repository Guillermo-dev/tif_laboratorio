<?php

namespace models;

use JsonSerializable;
use Exception;

class Cliente implements JsonSerializable {

    private $id;

    private $cuilCuit;

    private $razonSocial;

    private $nombre;

    private $apellido;

    private $telefono;

    private $email;

    public function __construct(int $id = 0, string $cuilCuit = '', string $razonSocial = '', string $nombre = '', string $apellido = '', string $telefono = '', string $email = '') {
        $this->id = $id;
        $this->cuilCuit = $cuilCuit;
        $this->razonSocial = $razonSocial;
        $this->nombre = $nombre;
        $this->apellido = $apellido;
        $this->telefono = $telefono;
        $this->email = $email;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getCuilCuit(): string {
        return $this->cuilCuit;
    }

    public function getRazonSocial(): string {
        return $this->razonSocial;
    }

    public function getNombre(): string {
        return $this->nombre;
    }

    public function getApellido(): string {
        return $this->apellido;
    }

    public function getTelefono(): string {
        return $this->telefono;
    }

    public function getEmail(): string {
        return $this->email;
    }

    public function setId(int $id) {
        $this->id = $id;
    }

    public function setCuilCuit(string $cuilCuit) {
        $this->cuilCuit = $cuilCuit;
    }

    public function setRazonSocial(string $razonSocial) {
        $this->razonSocial = $razonSocial;
    }

    public function setNombre(string $nombre) {
        $this->nombre = $nombre;
    }

    public function setApellido(string $apellido) {
        $this->apellido = $apellido;
    }

    public function setTelefono(string $telefono) {
        $this->telefono = $telefono;
    }

    public function setEmail(string $email) {
        $this->email = $email;
    }

    public function jsonSerialize(): array {
        return get_object_vars($this);
    }

    /**************************** Metodos BD ****************************/

    public static function getClientes(): array {
        $database = Connection::getDatabase();

        $clientes = null;
        $clientes = $database->select('clientes', [
            'cliente_id',
            'cuil_cuit',
            'razon_social',
            'nombre',
            'apellido',
            'telefono',
            'email'
        ]);

        if (isset($database->error))
            throw new Exception('Clientes no encontrados: ' . $database->error);

        return $clientes;
    }

    public static function createCliente(Cliente $cliente): void {
        $database = Connection::getDatabase();

        $database->insert('clientes', [
            'cuil_cuit' => $cliente->getCuilCuit(),
            'razon_social' => $cliente->getRazonSocial(),
            'nombre' => $cliente->getNombre(),
            'apellido' => $cliente->getApellido(),
            'telefono' => $cliente->getTelefono(),
            'email' => $cliente->getEmail()
        ]);

        if (isset($database->error))
            throw new Exception('Error al crear el usuario: ' . $database->error);
    }

    public static function updateCampania(Cliente $cliente): void {
        $database = Connection::getDatabase();

        $database->update('clientes', [
            'cuil_cuit' => $cliente->getCuilCuit(),
            'razon_social' => $cliente->getRazonSocial(),
            'nombre' => $cliente->getNombre(),
            'apellido' => $cliente->getApellido(),
            'telefono' => $cliente->getTelefono(),
            'email' => $cliente->getEmail()
        ], [
            'cliente_id' => $cliente->getId()
        ]);

        if (isset($database->error))
            throw new Exception('Error al actualizar el usuario: ' . $database->error);
    }

    public static function deleteCliente(int $clienteId) {
        $database = Connection::getDatabase();

        $database->delete('clientes', [
            'AND' => [
                'cliente_id' => $clienteId
            ]
        ]);

        if (isset($database->error))
            throw new Exception('Error al borrar el cliente: ' . $database->error);
    }
}
