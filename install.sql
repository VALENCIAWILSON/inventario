CREATE DATABASE IF NOT EXISTS inventario CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE inventario;

CREATE TABLE IF NOT EXISTS usuarios (
  cedula VARCHAR(20) PRIMARY KEY,
  nombre VARCHAR(100) NOT NULL,
  password VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS articulos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nombre VARCHAR(150) NOT NULL,
  unidades INT NOT NULL DEFAULT 0,
  tipo ENUM('PC','teclado','disco duro','mouse') NOT NULL,
  bodega ENUM('norte','sur','oriente','occidente') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Insertar usuario administrador (cedula: 1111, password: 1234)
-- Generado con password_hash('1234', PASSWORD_DEFAULT) (hash puede cambiar entre instalaciones)
INSERT INTO usuarios (cedula, nombre, password) VALUES
('1111', 'Administrador', '$2y$10$0e1u1q8E0wVbXq0u2a6r.ejQvG6qY3o0ZQGf5cY6qj9uYQbQzT6W6');
