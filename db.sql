CREATE DATABASE IF NOT EXISTS inventarioDB CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventarioDB;
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
INSERT IGNORE INTO users (cedula, nombre, password) VALUES
('1111', 'Administrador', '$2y$10$9bR0q1w2XhLq5KzJ1C6aF.Oo6dP5z2uGJqT8r4lHq1z2e6sY7aB2');