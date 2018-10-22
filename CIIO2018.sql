-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 26, 2018 at 01:37 AM
-- Server version: 5.7.22-0ubuntu0.16.04.1
-- PHP Version: 7.0.30-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `CIIO2018`
--
CREATE DATABASE IF NOT EXISTS `CIIO2018` DEFAULT CHARACTER SET utf8 COLLATE utf8_spanish_ci;
USE `CIIO2018`;

-- --------------------------------------------------------

--
-- Table structure for table `chat`
--

DROP TABLE IF EXISTS `chat`;
CREATE TABLE `chat` (
  `id` int(10) UNSIGNED NOT NULL,
  `from` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `to` varchar(255) COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `message` text COLLATE latin1_general_ci NOT NULL,
  `sent` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `recd` int(10) UNSIGNED NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cursos`
--

DROP TABLE IF EXISTS `cursos`;
CREATE TABLE `cursos` (
  `Id` int(11) NOT NULL,
  `Nombre` text CHARACTER SET latin1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `cursos`
--

INSERT INTO `cursos` (`Id`, `Nombre`) VALUES
(1, 'Registro CIIO2018');

-- --------------------------------------------------------

--
-- Table structure for table `horarios_curso`
--

DROP TABLE IF EXISTS `horarios_curso`;
CREATE TABLE `horarios_curso` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `hora_inicio` time NOT NULL,
  `hora_fin` time NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `horarios_curso`
--

INSERT INTO `horarios_curso` (`id`, `id_curso`, `hora_inicio`, `hora_fin`) VALUES
(18, 1, '00:00:00', '17:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
CREATE TABLE `news` (
  `id` int(10) NOT NULL,
  `title` varchar(255) NOT NULL,
  `add_date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `counting` int(10) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `news`
--

INSERT INTO `news` (`id`, `title`, `add_date`, `counting`) VALUES
(1, 'Moderador', '2018-07-25 03:32:13', 2);

-- --------------------------------------------------------

--
-- Table structure for table `registradores`
--

DROP TABLE IF EXISTS `registradores`;
CREATE TABLE `registradores` (
  `Id` int(11) NOT NULL,
  `Id_Usuarios` varchar(30) CHARACTER SET utf8 DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `registradores`
--

INSERT INTO `registradores` (`Id`, `Id_Usuarios`) VALUES
(1, 'apaipillas'),
(2, 'escastrogo'),
(3, 'frsmartinmo'),
(4, 'gpcardonav'),
(5, 'josfgarciamur');

-- --------------------------------------------------------

--
-- Table structure for table `Registrados`
--

DROP TABLE IF EXISTS `Registrados`;
CREATE TABLE `Registrados` (
  `id` varchar(30) NOT NULL,
  `add_date` date NOT NULL,
  `add_hour` time DEFAULT NULL,
  `id_curso` int(10) DEFAULT NULL,
  `id_registrador` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Registrados`
--

INSERT INTO `Registrados` (`id`, `add_date`, `add_hour`, `id_curso`, `id_registrador`) VALUES
('123456789', '2018-07-26', '00:27:48', 1, '1');

-- --------------------------------------------------------

--
-- Table structure for table `Usuarios`
--

DROP TABLE IF EXISTS `Usuarios`;
CREATE TABLE `Usuarios` (
  `Id` varchar(30) NOT NULL DEFAULT '0',
  `username` varchar(100) DEFAULT NULL,
  `correo` varchar(50) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `movil` varchar(20) DEFAULT NULL,
  `fechaInscripcion` date DEFAULT NULL,
  `consignacion` int(11) DEFAULT NULL,
  `valor` int(11) DEFAULT NULL,
  `CC` varchar(30) DEFAULT NULL,
  `U_password` varchar(32) DEFAULT NULL,
  `Tipo` varchar(1) NOT NULL DEFAULT 'S'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `Usuarios`
--

INSERT INTO `Usuarios` (`Id`, `username`, `correo`, `telefono`, `movil`, `fechaInscripcion`, `consignacion`, `valor`, `CC`, `U_password`, `Tipo`) VALUES
('1', 'Administrador', '', '', '', NULL, 0, 0, '999999999', '2837adbd1b12fe6dfaab6400ef01e1ab', 'S'),
('apaipillas', 'Andres David Paipilla Salgado', '', '', '', NULL, 0, 0, 'apaipillas', '99543d0349724c2c8a28bccf598f9913', 'R'),
('escastrogo', 'Estefania Castro Gomez', '', '', '', NULL, 0, 0, 'escastrogo', 'c34cf326cee3f990f987499c1c1052dc', 'R'),
('frsmartinmo', 'Francy Solangi Martin Montenegro', '', '', '', NULL, 0, 0, 'frsmartinmo', 'f3e253e9fbe95c50c937e1e588feeb72', 'R'),
('gpcardonav', 'Gheraldinne Paulette Cardona Valenzuela', '', '', '', NULL, 0, 0, 'gpcardonav', 'b8ca0ddf7c26c2e066f8ce83bbbd73c5', 'R'),
('josfgarciamur', 'Jose Fernando Garcia Murcia', '', '', '', NULL, 0, 0, 'josfgarciamur', '08e6664a34cab00f5f453130319d3d62', 'R');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios_curso`
--

DROP TABLE IF EXISTS `usuarios_curso`;
CREATE TABLE `usuarios_curso` (
  `Id_Usuarios` varchar(30) CHARACTER SET utf8 NOT NULL,
  `Id_Curso` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `chat`
--
ALTER TABLE `chat`
  ADD PRIMARY KEY (`id`),
  ADD KEY `to` (`to`),
  ADD KEY `from` (`from`);

--
-- Indexes for table `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `horarios_curso`
--
ALTER TABLE `horarios_curso`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Indexes for table `news`
--
ALTER TABLE `news`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `registradores`
--
ALTER TABLE `registradores`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Id` (`Id`),
  ADD KEY `Id_Usuarios` (`Id_Usuarios`),
  ADD KEY `Id_Usuarios_2` (`Id_Usuarios`);

--
-- Indexes for table `Registrados`
--
ALTER TABLE `Registrados`
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id` (`id`),
  ADD KEY `id_registrador` (`id_registrador`);

--
-- Indexes for table `Usuarios`
--
ALTER TABLE `Usuarios`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `CC` (`CC`);

--
-- Indexes for table `usuarios_curso`
--
ALTER TABLE `usuarios_curso`
  ADD UNIQUE KEY `Id_Usuarios_2` (`Id_Usuarios`,`Id_Curso`),
  ADD KEY `Id_Usuarios` (`Id_Usuarios`,`Id_Curso`),
  ADD KEY `Id_Curso` (`Id_Curso`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `chat`
--
ALTER TABLE `chat`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `horarios_curso`
--
ALTER TABLE `horarios_curso`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT for table `news`
--
ALTER TABLE `news`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `registradores`
--
ALTER TABLE `registradores`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `horarios_curso`
--
ALTER TABLE `horarios_curso`
  ADD CONSTRAINT `FK_hora_curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id`);

--
-- Constraints for table `registradores`
--
ALTER TABLE `registradores`
  ADD CONSTRAINT `FK_Registr_Usu` FOREIGN KEY (`Id_Usuarios`) REFERENCES `Usuarios` (`Id`);

--
-- Constraints for table `Registrados`
--
ALTER TABLE `Registrados`
  ADD CONSTRAINT `FK_Registra_Registrador` FOREIGN KEY (`id_registrador`) REFERENCES `Usuarios` (`Id`),
  ADD CONSTRAINT `FK_Registrados_Curso` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id`),
  ADD CONSTRAINT `U_R_Fk` FOREIGN KEY (`id`) REFERENCES `Usuarios` (`Id`);

--
-- Constraints for table `usuarios_curso`
--
ALTER TABLE `usuarios_curso`
  ADD CONSTRAINT `FK_UC_C` FOREIGN KEY (`Id_Curso`) REFERENCES `cursos` (`Id`),
  ADD CONSTRAINT `FK_UC_U` FOREIGN KEY (`Id_Usuarios`) REFERENCES `Usuarios` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
