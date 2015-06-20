-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jun 20, 2015 at 02:28 PM
-- Server version: 5.5.43-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Jokkebrok`
--
CREATE DATABASE IF NOT EXISTS `Jokkebrok` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `Jokkebrok`;

-- --------------------------------------------------------

--
-- Table structure for table `Aanwezigheid`
--

DROP TABLE IF EXISTS `Aanwezigheid`;
CREATE TABLE IF NOT EXISTS `Aanwezigheid` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` date NOT NULL,
  `KindVoogd` int(11) NOT NULL,
  `Werking` int(11) NOT NULL,
  `Opmerkingen` text NOT NULL,
  `MiddagNaarHuis` tinyint(1) NOT NULL DEFAULT '0',
  `LastChanged` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `KindVoogd` (`KindVoogd`,`Datum`),
  KEY `KindVoogdId_idx` (`KindVoogd`),
  KEY `WerkingId_idx` (`Werking`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1380 ;

-- --------------------------------------------------------

--
-- Table structure for table `Betaling`
--

DROP TABLE IF EXISTS `Betaling`;
CREATE TABLE IF NOT EXISTS `Betaling` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `KindVoogd` int(11) NOT NULL,
  `Bedrag` decimal(10,2) NOT NULL,
  `Opmerking` text NOT NULL,
  `Datum` date NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `KindVoogd` (`KindVoogd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `Extraatje`
--

DROP TABLE IF EXISTS `Extraatje`;
CREATE TABLE IF NOT EXISTS `Extraatje` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschrijving` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `ExtraatjeAanwezigheid`
--

DROP TABLE IF EXISTS `ExtraatjeAanwezigheid`;
CREATE TABLE IF NOT EXISTS `ExtraatjeAanwezigheid` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Aanwezigheid` int(11) NOT NULL,
  `Extraatje` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Aanwezigheid` (`Aanwezigheid`),
  KEY `Extraatje` (`Extraatje`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=948 ;

-- --------------------------------------------------------

--
-- Table structure for table `Kind`
--

DROP TABLE IF EXISTS `Kind`;
CREATE TABLE IF NOT EXISTS `Kind` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Voornaam` varchar(150) NOT NULL,
  `Naam` varchar(150) NOT NULL,
  `Geboortejaar` year(4) NOT NULL,
  `DefaultWerking` int(11) NOT NULL,
  `Belangrijk` text NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `DefaultWerking` (`DefaultWerking`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=459 ;

-- --------------------------------------------------------

--
-- Table structure for table `KindVoogd`
--

DROP TABLE IF EXISTS `KindVoogd`;
CREATE TABLE IF NOT EXISTS `KindVoogd` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Kind` int(11) NOT NULL,
  `Voogd` int(11) NOT NULL,
  `Saldo` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Kind` (`Kind`,`Voogd`),
  KEY `Voogd` (`Voogd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=555 ;

-- --------------------------------------------------------

--
-- Table structure for table `Log`
--

DROP TABLE IF EXISTS `Log`;
CREATE TABLE IF NOT EXISTS `Log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` text NOT NULL,
  `Value` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=38 ;

-- --------------------------------------------------------

--
-- Table structure for table `Uitstap`
--

DROP TABLE IF EXISTS `Uitstap`;
CREATE TABLE IF NOT EXISTS `Uitstap` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` date NOT NULL,
  `Omschrijving` text NOT NULL,
  `DashboardZichtbaar` tinyint(1) NOT NULL,
  `AanwezigheidZichtbaar` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=6 ;

-- --------------------------------------------------------

--
-- Table structure for table `UitstapKind`
--

DROP TABLE IF EXISTS `UitstapKind`;
CREATE TABLE IF NOT EXISTS `UitstapKind` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Kind` int(11) NOT NULL,
  `Uitstap` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Kind_Uitstap` (`Kind`,`Uitstap`),
  KEY `Uitstap` (`Uitstap`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `Users`
--

DROP TABLE IF EXISTS `Users`;
CREATE TABLE IF NOT EXISTS `Users` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `Voogd`
--

DROP TABLE IF EXISTS `Voogd`;
CREATE TABLE IF NOT EXISTS `Voogd` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Naam` varchar(250) DEFAULT NULL,
  `Voornaam` varchar(250) DEFAULT NULL,
  `Opmerkingen` text NOT NULL,
  `Telefoon` text,
  PRIMARY KEY (`Id`),
  KEY `Naam` (`Naam`,`Voornaam`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=396 ;

-- --------------------------------------------------------

--
-- Table structure for table `Vordering`
--

DROP TABLE IF EXISTS `Vordering`;
CREATE TABLE IF NOT EXISTS `Vordering` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Aanwezigheid` int(11) NOT NULL,
  `Bedrag` decimal(10,2) NOT NULL,
  `Opmerking` text NOT NULL,
  PRIMARY KEY (`Id`),
  KEY `Aanwezigheid` (`Aanwezigheid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=3 ;

-- --------------------------------------------------------

--
-- Table structure for table `Werking`
--

DROP TABLE IF EXISTS `Werking`;
CREATE TABLE IF NOT EXISTS `Werking` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschrijving` text NOT NULL,
  `Afkorting` text NOT NULL,
  `Beginjaar` int(11) NOT NULL,
  `Eindjaar` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `Aanwezigheid`
--
ALTER TABLE `Aanwezigheid`
  ADD CONSTRAINT `Aanwezigheid_ibfk_1` FOREIGN KEY (`KindVoogd`) REFERENCES `KindVoogd` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `Aanwezigheid_ibfk_2` FOREIGN KEY (`Werking`) REFERENCES `Werking` (`Id`);

--
-- Constraints for table `Betaling`
--
ALTER TABLE `Betaling`
  ADD CONSTRAINT `Betaling_ibfk_1` FOREIGN KEY (`KindVoogd`) REFERENCES `KindVoogd` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `ExtraatjeAanwezigheid`
--
ALTER TABLE `ExtraatjeAanwezigheid`
  ADD CONSTRAINT `ExtraatjeAanwezigheid_ibfk_1` FOREIGN KEY (`Aanwezigheid`) REFERENCES `Aanwezigheid` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `ExtraatjeAanwezigheid_ibfk_2` FOREIGN KEY (`Extraatje`) REFERENCES `Extraatje` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Kind`
--
ALTER TABLE `Kind`
  ADD CONSTRAINT `Kind_ibfk_1` FOREIGN KEY (`DefaultWerking`) REFERENCES `Werking` (`Id`);

--
-- Constraints for table `KindVoogd`
--
ALTER TABLE `KindVoogd`
  ADD CONSTRAINT `KindVoogd_ibfk_1` FOREIGN KEY (`Kind`) REFERENCES `Kind` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `KindVoogd_ibfk_2` FOREIGN KEY (`Voogd`) REFERENCES `Voogd` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `UitstapKind`
--
ALTER TABLE `UitstapKind`
  ADD CONSTRAINT `UitstapKind_ibfk_1` FOREIGN KEY (`Kind`) REFERENCES `Kind` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `UitstapKind_ibfk_2` FOREIGN KEY (`Uitstap`) REFERENCES `Uitstap` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `Vordering`
--
ALTER TABLE `Vordering`
  ADD CONSTRAINT `Vordering_ibfk_1` FOREIGN KEY (`Aanwezigheid`) REFERENCES `Aanwezigheid` (`Id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
