DROP DATABASE IF EXISTS gamergyx;

CREATE DATABASE IF NOT EXISTS gamergyx;
USE gamergyx;

CREATE TABLE IF NOT EXISTS `usuarios` (
  `id_usuarios` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  `descripcion` TEXT,
  `contraseña` VARCHAR(255) NOT NULL,
  `rol` VARCHAR(20) NOT NULL,
  `fotoPerfil` VARCHAR(255),
  `email` VARCHAR(100) NOT NULL,
  `monedero_virtual` DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  PRIMARY KEY (`id_usuarios`)
);


CREATE TABLE IF NOT EXISTS `generos` (
  `id_generos` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_generos`)
);


CREATE TABLE IF NOT EXISTS `plataformas` (
  `id_plataformas` INT NOT NULL AUTO_INCREMENT,
  `nombre` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`id_plataformas`)
);


CREATE TABLE IF NOT EXISTS `videojuegos` (
  `id_videojuegos` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(50) NOT NULL,
  `descripcion` TEXT NOT NULL,
  `fecha_lanzamiento` DATE NOT NULL,
  `id_generos` INT NOT NULL,
  `id_plataforma` INT NOT NULL,
  `precio` DECIMAL(10,2) NOT NULL,
  `stock` INT NOT NULL,
  `imagen` VARCHAR(255),
  PRIMARY KEY (`id_videojuegos`),
  FOREIGN KEY (`id_generos`) REFERENCES `generos`(`id_generos`),
  FOREIGN KEY (`id_plataforma`) REFERENCES `plataformas`(`id_plataformas`)
);


CREATE TABLE IF NOT EXISTS `favoritos` (
  `id_favoritos` INT NOT NULL AUTO_INCREMENT,
  `id_videojuegos` INT NOT NULL,
  `id_usuarios` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`id_favoritos`),
  FOREIGN KEY (`id_videojuegos`) REFERENCES `videojuegos`(`id_videojuegos`),
  FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios`(`id_usuarios`)
);


CREATE TABLE IF NOT EXISTS  `carrito` (
  `id_carrito` INT NOT NULL AUTO_INCREMENT,
  `id_videojuegos` INT NOT NULL,
  `id_usuarios` INT NOT NULL,
  `cantidad` INT NOT NULL,
  PRIMARY KEY (`id_carrito`),
  FOREIGN KEY (`id_videojuegos`) REFERENCES `videojuegos`(`id_videojuegos`),
  FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios`(`id_usuarios`)
);

CREATE TABLE IF NOT EXISTS `pedidos` (
  `id_pedido` INT NOT NULL AUTO_INCREMENT,
  `id_usuarios` INT NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`id_pedido`),
  FOREIGN KEY (`id_usuarios`) REFERENCES `usuarios`(`id_usuarios`)
);


CREATE TABLE IF NOT EXISTS `detalle_pedido` (
  `id_detalle` INT NOT NULL AUTO_INCREMENT,
  `id_pedido` INT NOT NULL,
  `id_videojuegos` INT NOT NULL,
  `fecha` DATETIME NOT NULL, 
  PRIMARY KEY (`id_detalle`),
  FOREIGN KEY (`id_pedido`) REFERENCES `pedidos`(`id_pedido`),
  FOREIGN KEY (`id_videojuegos`) REFERENCES `videojuegos`(`id_videojuegos`)
);

CREATE TABLE IF NOT EXISTS `reseñas` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `usuario` VARCHAR(50) NOT NULL,
  `fotoPerfil` VARCHAR(255),
  `id_videojuegos` INT NOT NULL,
  `comentarios` TEXT NOT NULL,
  `fecha` DATETIME NOT NULL,
  PRIMARY KEY (`id`),
  FOREIGN KEY (`id_videojuegos`) REFERENCES `videojuegos`(`id_videojuegos`)
);


CREATE TABLE IF NOT EXISTS `noticias` (
  `id_noticias` INT NOT NULL AUTO_INCREMENT,
  `titulo` VARCHAR(100) NOT NULL,
  `contenido` TEXT NOT NULL,
  `fecha` DATETIME NOT NULL,
  `portada` VARCHAR(255),
  `fuente` VARCHAR(255),
  `enlace` VARCHAR(255),
  PRIMARY KEY (`id_noticias`)
);

