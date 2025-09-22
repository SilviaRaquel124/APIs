-- 1) Crea BD y usa
CREATE DATABASE IF NOT EXISTS controlfinanzas
  DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;
USE controlfinanzas;

-- 2) roles: PK = nombre
CREATE TABLE IF NOT EXISTS roles (
  nombre VARCHAR(50) PRIMARY KEY
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 3) usuarios
CREATE TABLE IF NOT EXISTS usuarios (
  id         INT AUTO_INCREMENT PRIMARY KEY,
  nombre     VARCHAR(120) NOT NULL,
  email      VARCHAR(150) NOT NULL UNIQUE,
  password   VARCHAR(255) NOT NULL,
  RolNombre  VARCHAR(50)  NULL,
  createdAt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                       ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_usuarios_roles
    FOREIGN KEY (RolNombre) REFERENCES roles(nombre)
    ON UPDATE CASCADE ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 4) entradas
CREATE TABLE IF NOT EXISTS entradas (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuarioId   INT NOT NULL,
  tipo        VARCHAR(100) NOT NULL,
  monto       DECIMAL(12,2) NOT NULL,
  fecha       DATE NOT NULL,
  facturaRuta VARCHAR(255) NULL,
  createdAt   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_entradas_usuario
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 5) salidas (mismo esquema que entradas)
CREATE TABLE IF NOT EXISTS salidas (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  usuarioId   INT NOT NULL,
  tipo        VARCHAR(100) NOT NULL,
  monto       DECIMAL(12,2) NOT NULL,
  fecha       DATE NOT NULL,
  facturaRuta VARCHAR(255) NULL,
  createdAt   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updatedAt   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP
                        ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_salidas_usuario
    FOREIGN KEY (usuarioId) REFERENCES usuarios(id)
    ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- 6) Semilla
INSERT IGNORE INTO roles(nombre)
VALUES ('administrador'), ('personal');

-- Admin por defecto si no existe
INSERT INTO usuarios (nombre, email, password, RolNombre, createdAt, updatedAt)
SELECT 'Administrador', 'admin@gmail.com',
       -- cambia la clave si quieres
       '$2y$10$QeTsg2I7C4zSxM1h5wQH6uQ2u0Ck9o7Rr1G3Q5m7d2k0v1Y3nJm1W',
       'administrador', NOW(), NOW()
WHERE NOT EXISTS (SELECT 1 FROM usuarios);