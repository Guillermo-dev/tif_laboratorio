CREATE DATABASE IF NOT EXISTS laboratorioPython;

CREATE TABLE codigo_area (
  codigo_area_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY,
  numero VARCHAR(15) NOT NULL UNIQUE,
  localidad VARCHAR(255) NOT NULL UNIQUE
);

CREATE TABLE numeros (
  numero_id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY,
  numero VARCHAR(15) NOT NULL UNIQUE,
  codigo_area_id INT NOT NULL,
  FOREIGN KEY (codigo_area_id) REFERENCES codigo_area(codigo_area_id)
);