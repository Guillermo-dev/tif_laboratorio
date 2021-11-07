/*CREATE DATABASE IF NOT EXISTS laboratorio;*/

CREATE TABLE IF NOT EXISTS clientes (
  cliente_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  cuil_cuit VARCHAR(25) NOT NULL UNIQUE,
  razon_social VARCHAR(25) NOT NULL,
  nombre VARCHAR(25) NOT NULL,
  apellido VARCHAR(25) NOT NULL,
  telefono VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL
);

CREATE TABLE IF NOT EXISTS campanias (
  campania_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(25) NOT NULL UNIQUE,
  texto_SMS TEXT NOT NULL,
  cantidad_mensajes VARCHAR(10) NOT NULL CHECK (cantidad_mensajes IN ('7.000', '14.000', '21.000',' 28.000', '35.000', '42.000', '49.000', '56.000', '63.000', '70.000')),
  estado VARCHAR(10) NOT NULL CHECK (estado IN ( 'creada', 'en ejecucion', 'finalizada')),
  fecha_inicio DATE NOT NULL,
  cliente_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes (cliente_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS localidades(
  localidad_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  pais VARCHAR (45) NOT NULL,
  provincia VARCHAR(45) NOT NULL,
  ciudad VARCHAR (45) NOT NULL,
);

CREATE TABLE IF NOT EXISTS campanias_localidades (
  campania_id INT UNSIGNED NOT NULL,
  localidad_id INT UNSIGNED NOT NULL,
  PRIMARY KEY (campania_id, localidad_id),
  FOREIGN KEY (campania_id) REFERENCES campanias(campania_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY (localidad_id) REFERENCES localidades(localidad_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);

CREATE TABLE IF NOT EXISTS prefijos_internacionales (
  prefijo_internacional_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  prefijo VARCHAR(10) NOT NULL
);

CREATE TABLE IF NOT EXISTS codigos_area (
  codigo_area_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  codigo VARCHAR (10) NOT NULL
);

CREATE TABLE IF NOT EXISTS numeros (
  numero_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  numero VARCHAR(15) NOT NULL UNIQUE,
  localidad_id INT UNSIGNED NOT NULL,
  codigo_area_id INT UNSIGNED NOT NULL,
  prefijo_internacional_id INT UNSIGNED NOT NULL,
  FOREIGN KEY (localidad_id) REFERENCES localidades (localidad_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY (prefijo_internacional_id) REFERENCES prefijos_internacionales(prefijo_internacional_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  FOREIGN KEY (codigo_area_id) REFERENCES codigos_area (codigo_area_id)
    ON DELETE NO ACTION
    ON UPDATE NO ACTION
);


