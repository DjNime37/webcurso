-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 13-10-2026 a las 14:06:35
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";
/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
--
-- Base de datos: `tienda_ropa`
--
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `carrito`
--
CREATE TABLE `carrito` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `talla` varchar(50) NOT NULL DEFAULT '',
  `cantidad` int(11) NOT NULL DEFAULT 1,
  `fecha_actualizacion` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `categorias`
--
CREATE TABLE `categorias` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Volcado de datos para la tabla `categorias`
--
INSERT INTO `categorias` (`id`, `nombre`) VALUES
(1, 'Chandals'),
(2, 'Deportivas'),
(3, 'Reproductores'),
(4, 'Térmicas');
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `direcciones`
--
CREATE TABLE `direcciones` (
  `id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `nombre_via` varchar(150) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `piso` varchar(20) DEFAULT NULL,
  `puerta` varchar(20) DEFAULT NULL,
  `pais` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Volcado de datos para la tabla `direcciones`
--
INSERT INTO `direcciones` (`id`, `usuario_id`, `codigo_postal`, `nombre_via`, `numero`, `piso`, `puerta`, `pais`, `tipo`, `created_at`, `updated_at`) VALUES
(10, 9, '22003', 'htdhs', '28', '8º', 'H', 'Alemania', 'entrega', '0000-00-00 00:00:00', NULL),
(11, 9, '44456', 'dgteht', '90', '9º', '', 'Reino Unido', 'facturación', '0000-00-00 00:00:00', NULL);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `direcciones_historial`
--
CREATE TABLE `direcciones_historial` (
  `id` int(10) UNSIGNED NOT NULL,
  `direccion_id` int(10) UNSIGNED NOT NULL,
  `usuario_id` int(10) UNSIGNED NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `nombre_via` varchar(150) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `piso` varchar(20) DEFAULT NULL,
  `puerta` varchar(20) DEFAULT NULL,
  `pais` varchar(100) NOT NULL,
  `tipo` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `was_deleted` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Volcado de datos para la tabla `direcciones_historial`
--
INSERT INTO `direcciones_historial` (`id`, `direccion_id`, `usuario_id`, `codigo_postal`, `nombre_via`, `numero`, `piso`, `puerta`, `pais`, `tipo`, `created_at`, `was_deleted`) VALUES
(17, 11, 9, '44456', 'dgteht', '90', '9º', '', 'Reino Unido', 'facturación', '2026-10-12 00:56:40', NULL);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `productos`
--
CREATE TABLE `productos` (
  `id` int(11) NOT NULL,
  `categoria_id` int(11) NOT NULL,
  `nombre` varchar(150) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `imagen` varchar(255) NOT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `productos`
--
INSERT INTO `productos` (`id`, `categoria_id`, `nombre`, `precio`, `stock`, `imagen`, `activo`) VALUES
(1, 1, 'Chándal azul', 39.99, 32, 'imagenes/chandals/chandalazul.jpeg', 1),
(2, 1, 'Chándal rojo', 39.99, 96, 'imagenes/chandals/chandalrojo.jpeg', 1),
(3, 1, 'Chándal gris', 39.99, 43, 'imagenes/chandals/chandalgris.jpeg', 1),
(4, 2, 'Deportivas Nike', 59.99, 67, 'imagenes/deportivas/nikes.jpeg', 1),
(5, 2, 'Deportivas Skechers', 54.99, 24, 'imagenes/deportivas/sketches.jpeg', 1),
(6, 2, 'Zapatillas deportivas', 49.99, 59, 'imagenes/deportivas/zapatillas.jpeg', 1),
(7, 2, 'Unas deportivas', 44.99, 44, 'imagenes/deportivas/unas.jpeg', 1),
(8, 3, 'Reproductor MP4 azul', 29.99, 23, 'imagenes/mp4/azul.jpeg', 1),
(9, 3, 'Reproductor MP4 negro', 29.99, 40, 'imagenes/mp4/negro.jpeg', 1),
(10, 3, 'Reproductor MP4', 29.99, 30, 'imagenes/mp4/wiki.jpeg', 1),
(11, 4, 'Ropa térmica azul', 24.99, 92, 'imagenes/termicas/azul.jpeg', 1),
(12, 4, 'Ropa térmica gris', 24.99, 30, 'imagenes/termicas/gris.jpeg', 1),
(13, 4, 'Ropa térmica negra', 24.99, 94, 'imagenes/termicas/negra.jpeg', 1);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `producto_tallas`
--
CREATE TABLE `producto_tallas` (
  `id` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `talla_id` int(11) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `producto_tallas`
--
INSERT INTO `producto_tallas` (`id`, `producto_id`, `talla_id`, `stock`) VALUES
(1, 1, 4, 10),
(2, 2, 4, 10),
(3, 3, 4, 10),
(4, 1, 3, 10),
(5, 2, 3, 10),
(6, 3, 3, 10),
(7, 1, 2, 10),
(8, 2, 2, 10),
(9, 3, 2, 10),
(10, 1, 5, 10),
(11, 2, 5, 10),
(12, 3, 5, 10),
(13, 1, 1, 10),
(14, 2, 1, 10),
(15, 3, 1, 10),
(16, 1, 6, 10),
(17, 2, 6, 10),
(18, 3, 6, 10),
(32, 4, 8, 10),
(33, 5, 8, 10),
(34, 6, 8, 10),
(35, 7, 8, 10),
(36, 4, 9, 10),
(37, 5, 9, 10),
(38, 6, 9, 10),
(39, 7, 9, 10),
(40, 4, 10, 10),
(41, 5, 10, 10),
(42, 6, 10, 10),
(43, 7, 10, 10),
(44, 4, 11, 10),
(45, 5, 11, 10),
(46, 6, 11, 10),
(47, 7, 11, 10),
(48, 4, 12, 10),
(49, 5, 12, 10),
(50, 6, 12, 10),
(51, 7, 12, 10),
(52, 4, 13, 10),
(53, 5, 13, 10),
(54, 6, 13, 10),
(55, 7, 13, 10),
(56, 4, 14, 10),
(57, 5, 14, 10),
(58, 6, 14, 10),
(59, 7, 14, 10),
(60, 4, 15, 10),
(61, 5, 15, 10),
(62, 6, 15, 10),
(63, 7, 15, 10),
(64, 4, 16, 10),
(65, 5, 16, 10),
(66, 6, 16, 10),
(67, 7, 16, 10),
(68, 4, 17, 10),
(69, 5, 17, 10),
(70, 6, 17, 10),
(71, 7, 17, 10),
(95, 11, 4, 10),
(96, 12, 4, 10),
(97, 13, 4, 10),
(98, 11, 3, 10),
(99, 12, 3, 10),
(100, 13, 3, 10),
(101, 11, 2, 10),
(102, 12, 2, 10),
(103, 13, 2, 10),
(104, 11, 5, 10),
(105, 12, 5, 10),
(106, 13, 5, 10),
(107, 11, 1, 10),
(108, 12, 1, 10),
(109, 13, 1, 10),
(110, 11, 6, 10),
(111, 12, 6, 10),
(112, 13, 6, 10);
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `tallas`
--
CREATE TABLE `tallas` (
  `id` int(11) NOT NULL,
  `nombre` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Volcado de datos para la tabla `tallas`
--
INSERT INTO `tallas` (`id`, `nombre`) VALUES
(8, '36'),
(9, '37'),
(10, '38'),
(11, '39'),
(12, '40'),
(13, '41'),
(14, '42'),
(15, '43'),
(16, '44'),
(17, '45'),
(4, 'L'),
(3, 'M'),
(2, 'S'),
(7, 'Única'),
(5, 'XL'),
(1, 'XS'),
(6, 'XXL');
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `usuarios`
--
CREATE TABLE `usuarios` (
  `id` int(10) UNSIGNED NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `prefijo` varchar(10) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `created_at` datetime NOT NULL,
  `updated_at` datetime DEFAULT NULL,
  `dni_hash` char(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
--
-- Volcado de datos para la tabla `usuarios`
--
INSERT INTO `usuarios` (`id`, `nombre`, `primer_apellido`, `segundo_apellido`, `usuario`, `email`, `password`, `fecha_nacimiento`, `prefijo`, `telefono`, `created_at`, `updated_at`, `dni_hash`) VALUES
(9, 'Mario', 'González', 'Fuentes', 'eiofka777', 'mariogonzalezfuentes087@gmail.com', '$2y$10$gr5EJeb47/EBSJoBx8MeaeZz4psFgbz36uoe6jLoCMC2iLcf80RwK', '2026-10-15', '+34', '618948433', '2026-10-11 22:26:20', NULL, 'fd1aab4b60756856b00efc5db1c4b83f41001e6d901e3d8964d9c63a4c9bfc0f');
-- --------------------------------------------------------
--
-- Estructura de tabla para la tabla `ya_comprado`
--
CREATE TABLE `ya_comprado` (
  `id` int(11) NOT NULL,
  `id_pedido` int(11) NOT NULL,
  `producto_id` int(11) NOT NULL,
  `nombre` varchar(255) NOT NULL,
  `imagen` varchar(500) NOT NULL,
  `talla` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `cantidad` int(11) NOT NULL,
  `fecha_hora` datetime NOT NULL,
  `total` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Volcado de datos para la tabla `ya_comprado`
--
INSERT INTO `ya_comprado` (`id`, `id_pedido`, `producto_id`, `nombre`, `imagen`, `talla`, `precio`, `cantidad`, `fecha_hora`, `total`) VALUES
(2, 1, 2, 'Chándal rojo', 'imagenes/chandals/chandalrojo.jpeg', 'S', 39.99, 1, '2026-10-12 10:00:24', 114.97),
(3, 1, 6, 'Zapatillas deportivas', 'imagenes/deportivas/zapatillas.jpeg', '40', 49.99, 1, '2026-10-12 10:00:24', 114.97),
(4, 1, 12, 'Ropa térmica gris', 'imagenes/termicas/gris.jpeg', 'M', 24.99, 1, '2026-10-12 10:00:24', 114.97);
--
-- Índices para tablas volcadas
--
--
-- Indices de la tabla `carrito`
--
ALTER TABLE `carrito`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usuario_producto_talla` (`usuario_id`,`producto_id`,`talla`),
  ADD KEY `idx_carrito_usuario` (`usuario_id`);
--
-- Indices de la tabla `categorias`
--
ALTER TABLE `categorias`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);
--
-- Indices de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  ADD PRIMARY KEY (`id`);
--
-- Indices de la tabla `direcciones_historial`
--
ALTER TABLE `direcciones_historial`
  ADD PRIMARY KEY (`id`);
--
-- Indices de la tabla `productos`
--
ALTER TABLE `productos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_productos_categoria` (`categoria_id`);
--
-- Indices de la tabla `producto_tallas`
--
ALTER TABLE `producto_tallas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_producto_talla` (`producto_id`,`talla_id`),
  ADD KEY `fk_producto_tallas_talla` (`talla_id`);
--
-- Indices de la tabla `tallas`
--
ALTER TABLE `tallas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);
--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`);
--
-- Indices de la tabla `ya_comprado`
--
ALTER TABLE `ya_comprado`
  ADD PRIMARY KEY (`id`);
--
-- AUTO_INCREMENT de las tablas volcadas
--
--
-- AUTO_INCREMENT de la tabla `carrito`
--
ALTER TABLE `carrito`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT de la tabla `categorias`
--
ALTER TABLE `categorias`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT de la tabla `direcciones`
--
ALTER TABLE `direcciones`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
--
-- AUTO_INCREMENT de la tabla `direcciones_historial`
--
ALTER TABLE `direcciones_historial`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `productos`
--
ALTER TABLE `productos`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT de la tabla `producto_tallas`
--
ALTER TABLE `producto_tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=113;
--
-- AUTO_INCREMENT de la tabla `tallas`
--
ALTER TABLE `tallas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;
--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
--
-- AUTO_INCREMENT de la tabla `ya_comprado`
--
ALTER TABLE `ya_comprado`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- Restricciones para tablas volcadas
--
--
-- Filtros para la tabla `productos`
--
ALTER TABLE `productos`
  ADD CONSTRAINT `fk_productos_categoria` FOREIGN KEY (`categoria_id`) REFERENCES `categorias` (`id`) ON UPDATE CASCADE;
--
-- Filtros para la tabla `producto_tallas`
--
ALTER TABLE `producto_tallas`
  ADD CONSTRAINT `fk_producto_tallas_producto` FOREIGN KEY (`producto_id`) REFERENCES `productos` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_producto_tallas_talla` FOREIGN KEY (`talla_id`) REFERENCES `tallas` (`id`) ON UPDATE CASCADE;
COMMIT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
