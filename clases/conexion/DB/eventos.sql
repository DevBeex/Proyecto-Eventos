-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 03-12-2023 a las 08:20:53
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
(5, 1, 10),
(7, 1, 11),
(8, 1, 12),
(9, 1, 13);

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
(8, 'Fiesta', 'Lorem ipsum dolor sit amet', '2023-11-30', '03:31:00', 1, 2, './imagenes/656c208e9c0ec_Fiesta.jpg'),
(10, 'Reunion', 'Lorem ipsum dolor sit amet', '2023-12-12', '00:12:00', 1, 17, './imagenes/656c20c5da488_Reunion.jpg'),
(11, 'Graduacion', 'random', '2024-09-01', '00:12:00', 1, 13, './imagenes/656c20de836bb_Graduacion.jpg'),
(12, '15 Años', 'Lorem ipsum dolor sit amet', '2025-09-09', '13:34:00', 1, 8, './imagenes/656c21912088d_15 Años.jpg'),
(13, 'Concierto', 'Lorem ipsum dolor sit amet', '2029-10-16', '15:37:00', 1, 9, './imagenes/656c21c402af1_Concierto.jpg');

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
(3, 1, 8),
(4, 1, 10),
(5, 1, 11),
(6, 1, 12);

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
(1, 'panama', 'parque omar torrijos noseque'),
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
(1, 'Benjamin', 'Rodriguez', 'benjarod@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'usuario'),
(2, 'Benjamin', 'Rodriguez', 'benjarod2@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'usuario'),
(3, 'Juan', 'Pérez', 'juan@example.com', 'f1b4dea0aceeb5b732d62bad9545cd35', 'usuario'),
(4, 'María', 'López', 'maria@example.com', 'c9050e7078a260e808a8991e5cc1b3f0', 'administrador'),
(5, 'Carlos', 'González', 'carlos@example.com', '90fe2049445178a1840bd71dc6c07ce8', 'usuario'),
(6, 'Laura', 'Martínez', 'laura@example.com', '9794230d6e317739e0d2a1be87becb94', 'usuario'),
(7, 'Pedro', 'Sánchez', 'pedro@example.com', '6bf1c4df57af5ade48b354bf959b4464', 'administrador'),
(8, 'Ana', 'García', 'ana@example.com', '91a81c79944c294500eca88bd906ed13', 'usuario'),
(9, 'Alejandro', 'Fernández', 'alejandro@example.com', '1abc9603f106657665bcdd608f302b0b', 'usuario'),
(10, 'Natalia', 'Hernández', 'natalia@example.com', '8b07327223bcdd1a7c4e16fd9bf04831', 'usuario');

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
  MODIFY `idAsistente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `evento`
--
ALTER TABLE `evento`
  MODIFY `idEvento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `eventofavorito`
--
ALTER TABLE `eventofavorito`
  MODIFY `idFavorito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `lugar`
--
ALTER TABLE `lugar`
  MODIFY `idLugar` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `usuario`
--
ALTER TABLE `usuario`
  MODIFY `idUsuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

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
