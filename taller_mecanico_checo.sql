-- ============================================================
-- Taller Mecánico CHECO - Script de Base de Datos Completo
-- ============================================================

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

CREATE DATABASE IF NOT EXISTS `taller_mecanico_checo`
  DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `taller_mecanico_checo`;

-- -------------------------------------------------------
-- Tabla: usuarios
-- -------------------------------------------------------
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE `usuarios` (
  `id_usuario`  INT(11)      NOT NULL AUTO_INCREMENT,
  `nombre`      VARCHAR(100) NOT NULL,
  `email`       VARCHAR(100) NOT NULL UNIQUE,
  `password`    VARCHAR(255) NOT NULL,
  `rol`         ENUM('admin','cliente') NOT NULL DEFAULT 'cliente',
  `telefono`    VARCHAR(20)  DEFAULT NULL,
  `direccion`   VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (`id_usuario`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabla: vehiculos
-- -------------------------------------------------------
DROP TABLE IF EXISTS `vehiculos`;
CREATE TABLE `vehiculos` (
  `id_vehiculo` INT(11)      NOT NULL AUTO_INCREMENT,
  `id_cliente`  INT(11)      NOT NULL,
  `placa`       VARCHAR(20)  NOT NULL UNIQUE,
  `marca`       VARCHAR(50)  NOT NULL,
  `modelo`      VARCHAR(50)  NOT NULL,
  `anio`        YEAR         NOT NULL,
  `color`       VARCHAR(30)  DEFAULT NULL,
  PRIMARY KEY (`id_vehiculo`),
  CONSTRAINT `fk_vehiculo_cliente`
    FOREIGN KEY (`id_cliente`) REFERENCES `usuarios` (`id_usuario`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabla: refacciones
-- -------------------------------------------------------
DROP TABLE IF EXISTS `refacciones`;
CREATE TABLE `refacciones` (
  `id_refaccion` INT(11)        NOT NULL AUTO_INCREMENT,
  `nombre`       VARCHAR(100)   NOT NULL,
  `descripcion`  TEXT           DEFAULT NULL,
  `precio`       DECIMAL(10,2)  NOT NULL DEFAULT 0.00,
  `stock`        INT(11)        NOT NULL DEFAULT 0,
  `categoria`    VARCHAR(60)    DEFAULT NULL,
  PRIMARY KEY (`id_refaccion`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabla: citas
-- -------------------------------------------------------
DROP TABLE IF EXISTS `citas`;
CREATE TABLE `citas` (
  `id_cita`            INT(11)  NOT NULL AUTO_INCREMENT,
  `id_cliente`         INT(11)  NOT NULL,
  `id_vehiculo`        INT(11)  NOT NULL,
  `fecha_cita`         DATE     NOT NULL,
  `hora_cita`          TIME     NOT NULL,
  `servicio_solicitado` VARCHAR(100) NOT NULL,
  `estatus`            ENUM('Pendiente','En proceso','Terminado','Cancelado')
                        NOT NULL DEFAULT 'Pendiente',
  `notas`              TEXT     DEFAULT NULL,
  `fecha_registro`     TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id_cita`),
  CONSTRAINT `fk_cita_cliente`
    FOREIGN KEY (`id_cliente`)  REFERENCES `usuarios`  (`id_usuario`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_cita_vehiculo`
    FOREIGN KEY (`id_vehiculo`) REFERENCES `vehiculos` (`id_vehiculo`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- -------------------------------------------------------
-- Tabla: servicios_cita
-- -------------------------------------------------------
DROP TABLE IF EXISTS `servicios_cita`;
CREATE TABLE `servicios_cita` (
  `id`             INT(11)       NOT NULL AUTO_INCREMENT,
  `id_cita`        INT(11)       NOT NULL,
  `id_refaccion`   INT(11)       NOT NULL,
  `cantidad`       INT(11)       NOT NULL DEFAULT 1,
  `precio_unitario` DECIMAL(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_sc_cita`
    FOREIGN KEY (`id_cita`)      REFERENCES `citas`      (`id_cita`)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_sc_refaccion`
    FOREIGN KEY (`id_refaccion`) REFERENCES `refacciones` (`id_refaccion`)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- Datos de prueba
-- ============================================================

-- 1 administrador + 2 clientes
-- Contraseñas en texto plano (el sistema actual usa texto plano):
--   admin@taller.com  → admin123
--   carlos@mail.com   → 123456
--   maria@mail.com    → 123456
INSERT INTO `usuarios` (`nombre`, `email`, `password`, `rol`, `telefono`, `direccion`) VALUES
('Administrador CHECO', 'admin@taller.com', 'admin123', 'admin', '4831000001', 'Taller Mecánico CHECO, Tamazunchale SLP'),
('Carlos Ramírez',      'carlos@mail.com',  '123456',   'cliente', '4831112233', 'Av. Hidalgo 45, Tamazunchale SLP'),
('María López',         'maria@mail.com',   '123456',   'cliente', '4839876543', 'Calle Morelos 12, Tamazunchale SLP');

-- Vehículos de prueba
INSERT INTO `vehiculos` (`id_cliente`, `placa`, `marca`, `modelo`, `anio`, `color`) VALUES
(2, 'ABC-123-A', 'Nissan',     'Sentra',  2019, 'Blanco'),
(2, 'XYZ-456-B', 'Volkswagen', 'Jetta',   2021, 'Gris'),
(3, 'LMN-789-C', 'Ford',       'Ranger',  2018, 'Negro');

-- Refacciones de prueba
INSERT INTO `refacciones` (`nombre`, `descripcion`, `precio`, `stock`, `categoria`) VALUES
('Filtro de aceite',       'Filtro para cambio de aceite motor',         85.00,  25, 'Filtros'),
('Bujías NGK (juego 4)',   'Juego de 4 bujías de iridio',                320.00, 12, 'Encendido'),
('Balatas delanteras',     'Par de balatas para freno delantero',         580.00,  8, 'Frenos'),
('Aceite 5W-30 (1L)',      'Aceite de motor sintético',                  135.00, 40, 'Lubricantes'),
('Filtro de aire',         'Filtro de aire para motor',                   95.00,  3, 'Filtros'),
('Correa de distribución', 'Kit correa distribución completo',          1200.00,  5, 'Motor');

-- Citas de prueba
INSERT INTO `citas` (`id_cliente`, `id_vehiculo`, `fecha_cita`, `hora_cita`, `servicio_solicitado`, `estatus`, `notas`) VALUES
(2, 1, DATE_ADD(CURDATE(), INTERVAL 2 DAY),  '09:00:00', 'Afinación',  'Pendiente',   'Cliente solicita revisión de inyectores'),
(3, 3, DATE_ADD(CURDATE(), INTERVAL 3 DAY),  '11:00:00', 'Frenos',     'Pendiente',   'Ruido en freno trasero derecho'),
(2, 2, DATE_SUB(CURDATE(), INTERVAL 5 DAY),  '10:00:00', 'Scanner',    'Terminado',   'Check Engine apagado, sensor O2 limpiado'),
(3, 3, DATE_SUB(CURDATE(), INTERVAL 1 DAY),  '14:00:00', 'Suspensión', 'En proceso',  'Cambio de amortiguadores traseros');
