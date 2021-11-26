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

        $clientes = [];
        $clientes = $database->select('clientes', [
            'cliente_id',
            'cuil_cuit',
            'razon_social',
            'nombre',
            'apellido',
            'telefono',
            'email'
        ], [
            "ORDER" => [
                'apellido',
                'nombre'
            ]
        ]);

        if (isset($database->error))
            throw new Exception($database->error);

        return $clientes;
    }

    public static function getClientesSearch(string $search): array {
        $database = Connection::getDatabase();

        $clientes = [];
        $clientes = $database->select('clientes', [
            'cliente_id',
            'cuil_cuit',
            'razon_social',
            'nombre',
            'apellido',
            'telefono',
            'email'
        ], [
            'OR' => [
                'nombre[~]' => $search,
                'apellido[~]' => $search,
                'cuil_cuit[~]' => $search
            ],
            "ORDER" => [
                'apellido',
                'nombre'
            ]
        ]);

        if (isset($database->error))
            throw new Exception($database->error);

        return $clientes;
    }

    public static function getClienteByCampaniaId(int $id): ?Cliente {
        $database = Connection::getDatabase();

        $clientes = $database->select(
            'clientes',
            ['[><]campanias' => ['cliente_id' => 'cliente_id'],],
            [
                'clientes.cliente_id',
                'clientes.cuil_cuit',
                'clientes.razon_social',
                'clientes.nombre',
                'clientes.apellido',
                'clientes.telefono',
                'clientes.email'
            ],
            [
                'campanias.campania_id' => $id
            ]
        );

        $cliente = null;
        if (!empty($clientes)) {
            $cliente = new CLiente();
            $cliente->setId($clientes[0]['cliente_id']);
            $cliente->setCuilCuit($clientes[0]['cuil_cuit']);
            $cliente->setRazonSocial($clientes[0]['razon_social']);
            $cliente->setNombre($clientes[0]['nombre']);
            $cliente->setApellido($clientes[0]['apellido']);
            $cliente->setTelefono($clientes[0]['telefono']);
            $cliente->setEmail($clientes[0]['email']);
        }

        if (isset($database->error))
            throw new Exception($database->error);

        return $cliente;
    }

    public static function getClienteById(int $id): ?Cliente {
        $database = Connection::getDatabase();

        $clientes = $database->select(
            'clientes',
            [
                'cliente_id',
                'cuil_cuit',
                'razon_social',
                'nombre',
                'apellido',
                'telefono',
                'email'
            ],
            [
                'cliente_id' => $id
            ]
        );

        $cliente = null;
        if (!empty($clientes)) {
            $cliente = new CLiente();
            $cliente->setId($clientes[0]['cliente_id']);
            $cliente->setCuilCuit($clientes[0]['cuil_cuit']);
            $cliente->setRazonSocial($clientes[0]['razon_social']);
            $cliente->setNombre($clientes[0]['nombre']);
            $cliente->setApellido($clientes[0]['apellido']);
            $cliente->setTelefono($clientes[0]['telefono']);
            $cliente->setEmail($clientes[0]['email']);
        }

        if (isset($database->error))
            throw new Exception($database->error);

        return $cliente;
    }

    public static function getClienteByCuil(string $cuil_cuit): ?Cliente {
        $database = Connection::getDatabase();

        $clientes = $database->select(
            'clientes',
            [
                'cliente_id',
                'cuil_cuit',
                'razon_social',
                'nombre',
                'apellido',
                'telefono',
                'email'
            ],
            [
                'cuil_cuit' => $cuil_cuit
            ]
        );

        $cliente = null;
        if (!empty($clientes)) {
            $cliente = new CLiente();
            $cliente->setId($clientes[0]['cliente_id']);
            $cliente->setCuilCuit($clientes[0]['cuil_cuit']);
            $cliente->setRazonSocial($clientes[0]['razon_social']);
            $cliente->setNombre($clientes[0]['nombre']);
            $cliente->setApellido($clientes[0]['apellido']);
            $cliente->setTelefono($clientes[0]['telefono']);
            $cliente->setEmail($clientes[0]['email']);
        }

        if (isset($database->error))
            throw new Exception($database->error);

        return $cliente;
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
            throw new Exception($database->error);

        $cliente->setId($database->id());
    }

    public static function updateCliente(Cliente $cliente): void {
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
            throw new Exception($database->error);
    }

    public static function deleteCliente(int $clienteId) {
        $database = Connection::getDatabase();

        $database->delete('clientes', [
            'AND' => [
                'cliente_id' => $clienteId
            ]
        ]);

        if (isset($database->error))
            throw new Exception($database->error, $database->errorInfo[1]);
    }
}
