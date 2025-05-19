-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost
-- Tiempo de generación: 19-05-2025 a las 05:03:53
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `hotel_paraiso`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `habitaciones`
--

CREATE TABLE `habitaciones` (
  `id` int(11) NOT NULL,
  `numero` varchar(10) NOT NULL,
  `tipo` varchar(50) NOT NULL,
  `precio` decimal(10,2) NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'disponible',
  `capacidad` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `habitaciones`
--

INSERT INTO `habitaciones` (`id`, `numero`, `tipo`, `precio`, `estado`, `capacidad`) VALUES
(1, '101', 'Estándar', 100.00, 'disponible', 2),
(2, '102', 'Suite', 200.00, 'ocupada', 5),
(3, '103', 'Familiar', 150.00, 'disponible', 6),
(4, '104', 'Suite', 250.00, 'ocupada', 5),
(5, '105', 'Estandar', 150.00, 'disponible', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reservas`
--

CREATE TABLE `reservas` (
  `id` int(11) NOT NULL,
  `usuario_id` int(11) NOT NULL,
  `habitacion_id` int(11) NOT NULL,
  `fecha_entrada` date NOT NULL,
  `fecha_salida` date NOT NULL,
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `reservas`
--

INSERT INTO `reservas` (`id`, `usuario_id`, `habitacion_id`, `fecha_entrada`, `fecha_salida`, `estado`) VALUES
(1, 2, 2, '2025-05-19', '2025-05-21', 'finalizada'),
(2, 3, 1, '2025-05-19', '2025-05-21', 'cancelada'),
(3, 3, 1, '2025-05-20', '2025-05-22', 'cancelada'),
(4, 2, 1, '2025-05-20', '2025-05-22', 'finalizada'),
(5, 2, 1, '2025-05-19', '2025-05-22', 'cancelada'),
(6, 3, 3, '2025-05-20', '2025-05-22', 'finalizada'),
(7, 2, 1, '2025-05-19', '2025-05-21', 'cancelada'),
(8, 2, 2, '2025-05-20', '2025-05-22', 'asignada'),
(9, 3, 4, '2025-05-19', '2025-05-21', 'pendiente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `roles`
--

INSERT INTO `roles` (`id`, `nombre`) VALUES
(1, 'administrador'),
(4, 'mucama'),
(2, 'recepcionista'),
(3, 'usuario');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tareas_limpieza`
--

CREATE TABLE `tareas_limpieza` (
  `id` int(11) NOT NULL,
  `habitacion_id` int(11) NOT NULL,
  `tipo_tarea` varchar(50) NOT NULL DEFAULT 'limpieza_general',
  `descripcion` text DEFAULT 'Limpieza general',
  `estado` varchar(20) NOT NULL DEFAULT 'pendiente',
  `fecha_asignada` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_completada` datetime DEFAULT NULL,
  `prioridad` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tareas_limpieza`
--

INSERT INTO `tareas_limpieza` (`id`, `habitacion_id`, `tipo_tarea`, `descripcion`, `estado`, `fecha_asignada`, `fecha_completada`, `prioridad`) VALUES
(18, 3, 'limpieza_general', 'organizar', 'completada', '2025-05-18 21:43:18', '2025-05-18 21:43:22', 2),
(19, 3, 'limpieza_general', 'hacer aseo', 'completada', '2025-05-18 22:00:45', '2025-05-18 22:01:02', 2),
(20, 1, 'limpieza_general', 'limpiar el baño', 'pendiente', '2025-05-18 22:01:00', NULL, 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `contrasena` varchar(255) NOT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `username`, `contrasena`, `nombre`, `email`, `rol_id`) VALUES
(1, 'admin', '$2y$12$3HlWIAc5HuvcHufqo.0MPuDNU49fvvDvVyI010XiBFjWKzpjWcgLy', 'Admin User', 'admin@hotelparaiso.com', 1),
(2, 'user', '$2y$12$WQxwc/cY.lCrqF0grKnjtOe/qDFG.00cyQyjVjIAU35QxDSV11hNq', 'user', 'user@gmail.com', 3),
(3, 'user2', '$2y$12$lX9e3lISj7sZjs6xfbbGOefcqM1sxBUzB0dNsGaEuZbjI3ne0GeFm', 'user2', 'user2@gmail.com', 3),
(4, 'recepcionista', '$2y$12$3HlWIAc5HuvcHufqo.0MPuDNU49fvvDvVyI010XiBFjWKzpjWcgLy', 'recepcionista', 'recepcionista@gmail.com', 2),
(5, 'mucama', '$2y$12$iRX.r7F1DMGTCYdXhaklmuciqsamlz4Bauw1xtYvtB8o/BAuTK/la', 'mucama', 'mucama@gmail.com', 4);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `numero` (`numero`);

--
-- Indices de la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `habitacion_id` (`habitacion_id`);

--
-- Indices de la tabla `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nombre` (`nombre`);

--
-- Indices de la tabla `tareas_limpieza`
--
ALTER TABLE `tareas_limpieza`
  ADD PRIMARY KEY (`id`),
  ADD KEY `habitacion_id` (`habitacion_id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `rol_id` (`rol_id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `habitaciones`
--
ALTER TABLE `habitaciones`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `reservas`
--
ALTER TABLE `reservas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tareas_limpieza`
--
ALTER TABLE `tareas_limpieza`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `reservas`
--
ALTER TABLE `reservas`
  ADD CONSTRAINT `reservas_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `reservas_ibfk_2` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`);

--
-- Filtros para la tabla `tareas_limpieza`
--
ALTER TABLE `tareas_limpieza`
  ADD CONSTRAINT `tareas_limpieza_ibfk_1` FOREIGN KEY (`habitacion_id`) REFERENCES `habitaciones` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`rol_id`) REFERENCES `roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
