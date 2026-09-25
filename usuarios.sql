-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 25-09-2026 a las 09:20:41
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
-- Base de datos: `usuarios`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `primer_apellido` varchar(100) NOT NULL,
  `segundo_apellido` varchar(100) NOT NULL,
  `usuario` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `prefijo` varchar(10) NOT NULL,
  `telefono` varchar(15) NOT NULL,
  `codigo_postal` varchar(10) NOT NULL,
  `nombre_via` varchar(150) NOT NULL,
  `numero` varchar(20) NOT NULL,
  `piso` varchar(20) DEFAULT NULL,
  `puerta` varchar(20) DEFAULT NULL,
  `pais` varchar(100) NOT NULL,
  `creado_en` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `nombre`, `primer_apellido`, `segundo_apellido`, `usuario`, `email`, `password`, `fecha_nacimiento`, `prefijo`, `telefono`, `codigo_postal`, `nombre_via`, `numero`, `piso`, `puerta`, `pais`, `creado_en`) VALUES
(1, 'afgerg', 'dvsfa', 'sfbth', 'wodfq0605', 'm@gmail.com', '$2y$10$ykUtEY/6SqTyIAmBRDccleT0Z9RKBWfg4mvufRtgcRNggC9kcXJXO', '2026-09-12', '+244', '654234789', '22003', 'frv467 hy', '78', NULL, 'G', 'Argelia', '2026-09-06 22:51:34'),
(2, 'Marcos antonio', 'zfdhsy', 'dfnatha', 'eiofka777', 'mariogonzalezfuentes@gmail.com', '$2y$10$Et2bJ6Irtk6mfQOCYnxjn.uhbNYfd9vz7BF8m.80PI0LKGQBOnRIi', '2026-09-15', '+34', '618948433', '22006', '12 doctubre', '45', '4', 'G', 'España', '2026-09-16 00:45:25');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario` (`usuario`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
