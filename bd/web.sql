CREATE DATABASE IF NOT EXISTS laboratorioWeb;

CREATE TABLE clientes (
  cliente_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY,
  cuil_cuit VARCHAR(25) NOT NULL UNIQUE,
  razon_social VARCHAR(25) NOT NULL,
  nombre VARCHAR(25) NOT NULL,
  apellido VARCHAR(25) NOT NULL,
  telefono VARCHAR(45) NOT NULL,
  email VARCHAR(45) NOT NULL
);

CREATE TABLE campanias (
  campania_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY,
  nombre VARCHAR(25) NOT NULL UNIQUE,
  texto_SMS TEXT NOT NULL,
  cantidad_mensajes INT NOT NULL,
  estado VARCHAR(25) NOT NULL,
  fecha_inicio DATE NOT NULL,
  cliente_id INT NOT NULL,
  FOREIGN KEY (cliente_id) REFERENCES clientes (cliente_id)
);

CREATE TABLE localidades(
  localidad_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY,
  nombre VARCHAR(255) NOT NULL
);

CREATE TABLE campanias_localidades (
  campanias_id INT NOT NULL,
  localidad_id INT NOT NULL,
  PRIMARY KEY (campania_id, localidad_id),
  FOREIGN KEY (campania_id) REFERENCES campanias(campania_id),
  FOREIGN KEY (localidad_id) REFERENCES localidades(localidad_id)
);