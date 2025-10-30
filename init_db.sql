CREATE DATABASE IF NOT EXISTS inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario;

CREATE TABLE IF NOT EXISTS users (
  cedula VARCHAR(20) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS articles (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  unidades INT NOT NULL DEFAULT 0,
  tipo ENUM('PC','teclado','disco duro','mouse') NOT NULL,
  bodega ENUM('norte','sur','oriente','occidente') NOT NULL
);

-- Usuario admin requerido por el enunciado (contraseña en texto plano según solicitud)
INSERT INTO users (cedula, nombre, password) VALUES ('1111','Administrador','1234')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre), password=VALUES(password);
