<?php

namespace models;

use Medoo\Medoo;
use Exception;

abstract class Connection {
    // Coneccion con la bd con patron de diseño Singleton
    private static $database = null;

    public static function getDatabase() {
        if (self::$database == null) {
            try {
                // estructura de archivo db-config: toda la informacion de configuracion junta y separada solo por punto y coma (;)
                //Ej: localhost;laboratorio;root;;3307; 
                $config = explode(';', file_get_contents('config/db-config'));
                $database = new Medoo([
                    'type' => 'mysql',
                    'host' => $config[0],
                    'database' => $config[1],
                    'username' => $config[2],
                    'password' => $config[3],
                    'port' => $config[4],
                ]);
            } catch (Exception $e) {
                //TODO:
                var_dump($e);
            }
        }
        return $database;
    }
}
