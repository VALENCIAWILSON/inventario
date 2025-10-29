CREATE DATABASE IF NOT EXISTS inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario;

CREATE TABLE IF NOT EXISTS usuarios (
  cedula VARCHAR(64) PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS articulos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(255) NOT NULL,
  unidades INT NOT NULL DEFAULT 0,
  tipo ENUM('PC','teclado','disco duro','mouse') NOT NULL,
  bodega ENUM('norte','sur','oriente','occidente') NOT NULL
);

-- Insert admin user (password = 1234)
INSERT INTO usuarios (cedula, nombre, password) VALUES
('admin', 'Administrador', '$2y$10$F0sHhJq2Y5uR2Zt8YfGxOO5mCqNl2YQzv4mKQy0u6mQ1fV0vQ5f0S')
ON DUPLICATE KEY UPDATE nombre=VALUES(nombre);
-- The hash above is password_hash('1234', PASSWORD_DEFAULT)
