-- phpMyAdmin SQL Dump
-- version 4.0.9
-- http://www.phpmyadmin.net
--
-- Servidor: localhost
-- Tiempo de generación: 19-05-2017 a las 21:10:12
-- Versión del servidor: 5.5.46-0ubuntu0.14.04.2
-- Versión de PHP: 5.5.9-1ubuntu4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Base de datos: `test`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `chat`
--

CREATE TABLE IF NOT EXISTS `chat` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `from` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `message` text COLLATE latin1_general_ci NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `to` (`to`),
  KEY `from` (`from`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci AUTO_INCREMENT=13 ;

--
-- Volcado de datos para la tabla `chat`
--


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `cursos`
--

CREATE TABLE IF NOT EXISTS `cursos` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Nombre` text CHARACTER SET latin1,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=11 ;

--
-- Volcado de datos para la tabla `cursos`
--

INSERT INTO `cursos` (`Id`, `Nombre`) VALUES
(1, 'Registro CIIO2018');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `horarios_curso`
--

CREATE TABLE IF NOT EXISTS `horarios_curso` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_curso` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL,
  PRIMARY KEY (`id`),
  KEY `id_curso` (`id_curso`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

--
-- Volcado de datos para la tabla `horarios_curso`
--

INSERT INTO `horarios_curso` (`id`, `id_curso`, `hora_inicio`, `hora_fin`) VALUES
(1, 1, '08:00:00', '20:30:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `news`
--

CREATE TABLE IF NOT EXISTS `news` (
  `id` int(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `counting` int(10) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Volcado de datos para la tabla `news`
--

INSERT INTO `news` (`id`, `title`, `add_date`, `counting`) VALUES
(1, 'Moderador', '2018-07-25 03:32:13', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `registradores`
--

CREATE TABLE IF NOT EXISTS `registradores` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Id_Usuarios` varchar(30) CHARACTER SET utf8 DEFAULT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Id` (`Id`),
  KEY `Id_Usuarios` (`Id_Usuarios`),
  KEY `Id_Usuarios_2` (`Id_Usuarios`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=4 ;

--
-- Volcado de datos para la tabla `registradores`
--

INSERT INTO `registradores` (`Id`, `Id_Usuarios`) VALUES
(1, 'gpcardonav'),
(2, 'escastrogo'),
(3, 'apaipillas'),
(4, 'frsmartinmo'),
(5, 'josfgarciamur');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Registrados`
--

CREATE TABLE IF NOT EXISTS `Registrados` (
  `id` varchar(30) NOT NULL,
  `add_date` date NOT NULL,
  `add_hour` time DEFAULT NULL,
  `id_curso` int(10) DEFAULT NULL,
  `id_registrador` varchar(30) DEFAULT NULL,
  KEY `id_curso` (`id_curso`),
  KEY `id` (`id`),
  KEY `id_registrador` (`id_registrador`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Registrados`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `Usuarios`
--

CREATE TABLE IF NOT EXISTS `Usuarios` (
  `Id` varchar(30) NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `correo` varchar(50) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `movil` varchar(20) NOT NULL,
  `fechaInscripcion` date NOT NULL,
  `consignacion` int(11) NOT NULL,
  `valor` int(11) NOT NULL,
  `CC` varchar(30) DEFAULT NULL,
  `U_password` varchar(32) DEFAULT NULL,
  `Tipo` varchar(1) NOT NULL DEFAULT 'S',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `CC` (`CC`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Volcado de datos para la tabla `Usuarios`
--

INSERT INTO `Usuarios` (`Id`, `username`, `correo`, `telefono`, `movil`, `fechaInscripcion`, `consignacion`, `valor`, `CC`, `U_password`, `Tipo`) VALUES
('1', 'Administrador', '', '', '', '0000-00-00', 0, 0, '999999999', 'c4ca4238a0b923820dcc509a6f75849b', 'S'),
('gpcardonav', 'Gheraldinne Paulette Cardona Valenzuela', '', '', '', '0000-00-00', 0, 0, 'gpcardonav', 'c4ca4238a0b923820dcc509a6f75849b', 'R'),
('escastrogo', 'Estefania Castro Gomez', '', '', '', '0000-00-00', 0, 0, 'escastrogo', 'c4ca4238a0b923820dcc509a6f75849b', 'R'),
('apaipillas', 'Andres David Paipilla Salgado', '', '', '', '0000-00-00', 0, 0, 'apaipillas', 'c4ca4238a0b923820dcc509a6f75849b', 'R'),
('frsmartinmo', 'Francy Solangi Martin Montenegro', '', '', '', '0000-00-00', 0, 0, 'frsmartinmo', 'c4ca4238a0b923820dcc509a6f75849b', 'R'),
('josfgarciamur', 'Jose Fernando Garcia Murcia', '', '', '', '0000-00-00', 0, 0, 'josfgarciamur', 'c4ca4238a0b923820dcc509a6f75849b', 'R')
;


-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios_curso`
--

CREATE TABLE IF NOT EXISTS `usuarios_curso` (
  `Id_Usuarios` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Id_Curso` int(11) NOT NULL,
  UNIQUE KEY `Id_Usuarios_2` (`Id_Usuarios`,`Id_Curso`),
  KEY `Id_Usuarios` (`Id_Usuarios`,`Id_Curso`),
  KEY `Id_Curso` (`Id_Curso`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Volcado de datos para la tabla `usuarios_curso`
--



--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `horarios_curso`
--
ALTER TABLE `horarios_curso`
  ADD CONSTRAINT `FK_hora_curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id`);

--
-- Filtros para la tabla `registradores`
--
ALTER TABLE `registradores`
  ADD CONSTRAINT `FK_Registr_Usu` FOREIGN KEY (`Id_Usuarios`) REFERENCES `Usuarios` (`Id`);

--
-- Filtros para la tabla `Registrados`
--
ALTER TABLE `Registrados`
  ADD CONSTRAINT `FK_Registra_Registrador` FOREIGN KEY (`id_registrador`) REFERENCES `Usuarios` (`Id`),
  ADD CONSTRAINT `FK_Registrados_Curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id`),
  ADD CONSTRAINT `U_R_Fk` FOREIGN KEY (`id`) REFERENCES `Usuarios` (`Id`);

--
-- Filtros para la tabla `usuarios_curso`
--
ALTER TABLE `usuarios_curso`
  ADD CONSTRAINT `FK_UC_U` FOREIGN KEY (`Id_Usuarios`) REFERENCES `Usuarios` (`Id`),
  ADD CONSTRAINT `FK_UC_C` FOREIGN KEY (`Id_Curso`) REFERENCES `cursos` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
