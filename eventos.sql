-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 10-12-2023 a las 00:35:01
-- Versión del servidor: 10.4.28-MariaDB
-- Versión de PHP: 8.1.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `eventos`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistente`
--

CREATE TABLE `asistente` (
  `idAsistente` int(11) NOT NULL,
  `idUsuarioAsistente` int(11) DEFAULT NULL,
  `idEvento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `asistente`
--

INSERT INTO `asistente` (`idAsistente`, `idUsuarioAsistente`, `idEvento`) VALUES
(6, 10, 12),
(7, 11, 10),
(8, 11, 11);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `evento`
--

CREATE TABLE `evento` (
  `idEvento` int(11) NOT NULL,
  `nombre` varchar(100) DEFAULT NULL,
  `descripcion` text DEFAULT NULL,
  `fecha` date DEFAULT NULL,
  `hora` time DEFAULT NULL,
  `idUsuarioOrganizador` int(11) DEFAULT NULL,
  `idLugar` int(11) DEFAULT NULL,
  `imagenEvento` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `evento`
--

INSERT INTO `evento` (`idEvento`, `nombre`, `descripcion`, `fecha`, `hora`, `idUsuarioOrganizador`, `idLugar`, `imagenEvento`) VALUES
(9, 'Fiesta', 'una fiesta para celebrar el fin de año', '2023-12-25', '00:00:00', 8, 2, './imagenes/6574f640a5514_Fiesta.jpg'),
(10, '15 Años', 'la celebración de 15 años de la hija de un compañero de la fisc', '2024-12-10', '15:00:00', 8, 21, './imagenes/6574f6c41cfe1_15 Años.jpg'),
(11, 'Partido FISC vs Mello', 'un partido de futbol entre los estudiantes de FISC contra los empleados de Mello', '2024-01-01', '09:00:00', 8, 14, './imagenes/6574f7224a7e4_Partido FISC vs Mello.jpg'),
(12, 'Desfile del 3 de noviembre', 'ven a celebrar el 3 de noviembre en las calles de Panamá', '2023-11-03', '10:00:00', 8, 2, './imagenes/6574f78151083_Desfile del 3 de noviembre.jpg'),
(13, 'Cena de fin de año', 'ven a la cena de fin de año de la FISC', '2023-12-31', '23:00:00', 10, 9, './imagenes/6574f7ffe5f9d_Cena de fin de año.jpg'),
(14, 'Concierto del club de musica', 'escucha al club de musica de la FISC', '2024-10-10', '13:00:00', 10, 13, './imagenes/6574f8714e42e_Concierto del club de musica.jpg'),
(15, 'Estudio para el examen de calculo 2', 'nos reuniremos para estudiar el examen final de calculo 2 del profesor [X]', '2023-06-12', '13:00:00', 11, 10, './imagenes/6574f8ffb0f76_Estudio para el examen de calculo 2.jpg'),
(16, 'Este es un evento de prueba', 'veremos si la img default funciona bien', '2013-12-23', '13:13:00', 11, 9, './imagenes/6574f9238f9f4_Este es un evento de prueba.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `eventofavorito`
--

CREATE TABLE `eventofavorito` (
  `idFavorito` int(11) NOT NULL,
  `idUsuario` int(11) DEFAULT NULL,
  `idEvento` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `eventofavorito`
--

INSERT INTO `eventofavorito` (`idFavorito`, `idUsuario`, `idEvento`) VALUES
(4, 10, 11),
(5, 10, 9),
(6, 11, 13),
(7, 11, 14);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `lugar`
--

CREATE TABLE `lugar` (
  `idLugar` int(11) NOT NULL,
  `nombreLugar` varchar(100) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `lugar`
--

INSERT INTO `lugar` (`idLugar`, `nombreLugar`, `direccion`) VALUES
(2, 'Ciudad de Panamá', 'Avenida Principal 123'),
(3, 'Casco Antiguo', 'Calle Histórica 456'),
(4, 'Bocas del Toro', 'Playa Hermosa 789'),
(5, 'Boquete', 'Calle del Café 101'),
(6, 'David', 'Calle Flores 25'),
(7, 'Colón', 'Avenida Central 567'),
(8, 'Chitré', 'Calle del Sol 876'),
(9, 'Azuero', 'Calle de la Cultura 321'),
(10, 'Penonomé', 'Avenida Central 654'),
(11, 'Chiriquí Grande', 'Calle Marítima 987'),
(12, 'Los Santos', 'Calle de los Olivos 432'),
(13, 'Veraguas', 'Avenida de las Flores 111'),
(14, 'Coclé', 'Calle del Río 222'),
(15, 'Herrera', 'Avenida del Trabajo 333'),
(16, 'Darién', 'Calle del Mar 444'),
(17, 'Guna Yala', 'Avenida de las Palmas 555'),
(18, 'Ngäbe-Buglé', 'Calle de la Montaña 666'),
(19, 'Emberá-Wounaan', 'Avenida del Bosque 777'),
(20, 'Panamá Oeste', 'Calle del Viento 888'),
(21, 'Panamá Este', 'Avenida del Sol 999');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario`
--

CREATE TABLE `usuario` (
  `idUsuario` int(11) NOT NULL,
  `nombre` varchar(50) DEFAULT NULL,
  `apellido` varchar(50) DEFAULT NULL,
  `correoElectronico` varchar(100) DEFAULT NULL,
  `contrasena` varchar(255) DEFAULT NULL,
  `rol` varchar(20) DEFAULT NULL CHECK (`rol` in ('administrador','usuario'))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuario`
--

INSERT INTO `usuario` (`idUsuario`, `nombre`, `apellido`, `correoElectronico`, `contrasena`, `rol`) VALUES
(8, 'Juan', 'Pérez', 'juan@example.com', 'f8032d5cae3de20fcec887f395ec9a6a', 'usuario'),
(9, 'Admin', 'Admin', 'admin@example.com', '21232f297a57a5a743894a0e4a801fc3', 'administrador'),
(10, 'María', 'Gómez', 'maria@example.com', 'f8032d5cae3de20fcec887f395ec9a6a', 'usuario'),
(11, 'Pedro', 'Rodríguez', 'pedro@example.com', 'f8032d5cae3de20fcec887f395ec9a6a', 'usuario');

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `asistente`
--
ALTER TABLE `asistente`
  ADD PRIMARY KEY (`idAsistente`),
  ADD KEY `idUsuarioAsistente` (`idUsuarioAsistente`),
  ADD KEY `idEvento` (`idEvento`);

--
-- Indices de la tabla `evento`
--
ALTER TABLE `evento`
  ADD PRIMARY KEY (`idEvento`),
  ADD KEY `idUsuarioOrganizador` (`idUsuarioOrganizador`),
  ADD KEY `idLugar` (`idLugar`);

--
-- Indices de la tabla `eventofavorito`
--
ALTER TABLE `eventofavorito`
  ADD PRIMARY KEY (`idFavorito`),
  ADD KEY `idUsuario` (`idUsuario`),
  ADD KEY `idEvento` (`idEvento`);

--
-- Indices de la tabla `lugar`
--
ALTER TABLE `lugar`
  ADD PRIMARY KEY (`idLugar`);

--
-- Indices de la tabla `usuario`
--
ALTER TABLE `usuario`
  ADD PRIMARY KEY (`idUsuario`),
  ADD UNIQUE KEY `correoElectronico` (`correoElectronico`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `asistente`
--
ALTER TABLE `asistente`
  MODIFY `idAsistente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `eventofavorito`
--
ALTER TABLE `eventofavorito`
  MODIFY `idFavorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT de la tabla `lugar`
--
ALTER TABLE `lugar`
  MODIFY `idLugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `asistente`
--
ALTER TABLE `asistente`
  ADD CONSTRAINT `asistente_ibfk_2` FOREIGN KEY (`idUsuarioAsistente`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `asistente_ibfk_3` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`);

--
-- Filtros para la tabla `evento`
--
ALTER TABLE `evento`
  ADD CONSTRAINT `evento_ibfk_1` FOREIGN KEY (`idUsuarioOrganizador`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `evento_ibfk_2` FOREIGN KEY (`idLugar`) REFERENCES `lugar` (`idLugar`);

--
-- Filtros para la tabla `eventofavorito`
--
ALTER TABLE `eventofavorito`
  ADD CONSTRAINT `eventofavorito_ibfk_1` FOREIGN KEY (`idUsuario`) REFERENCES `usuario` (`idUsuario`),
  ADD CONSTRAINT `eventofavorito_ibfk_2` FOREIGN KEY (`idEvento`) REFERENCES `evento` (`idEvento`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
