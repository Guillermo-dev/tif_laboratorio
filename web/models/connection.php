<?php

namespace models;

use Medoo\Medoo;
use Exception;
use PDO;

// Coneccion con la bd con patron de diseño Singleton
abstract class Connection {
    private static $database = null;

    public static function getDatabase() {
        if (self::$database == null) {
            try {
                // Dentro de una carpeta 'web' crear una nueva carpeta llamada 'config' y ahi adentro un archivo llamado db-config.conf
                // Dentro de este escribir toda la informacion de configuracion de la db, separada solo por punto y coma (;)
                // De la siguiente forma:
                //Ej: localhost;laboratorio;root;;3307; 
                $config = explode(';', file_get_contents('config/db-config'));
                $database = new Medoo([
                    'type' => 'mysql',
                    'host' => $config[0],
                    'database' => $config[1],
                    'username' => $config[2],
                    'password' => $config[3],
                    'port' => $config[4],

                    'error' => PDO::ERRMODE_SILENT,
                ]);
            } catch (Exception $e) {
                throw new Exception($e);
            }
        }
        return $database;
    }
}
