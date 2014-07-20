-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Jul 20, 2014 at 10:46 PM
-- Server version: 5.5.38-0ubuntu0.14.04.1
-- PHP Version: 5.5.9-1ubuntu4.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `Jokkebrok`
--

-- --------------------------------------------------------

--
-- Table structure for table `Aanwezigheid`
--

CREATE TABLE IF NOT EXISTS `Aanwezigheid` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` date NOT NULL,
  `KindVoogd` int(11) NOT NULL,
  `Werking` int(11) NOT NULL,
  `Opmerkingen` text NOT NULL,
  `MiddagNaarHuis` tinyint(1) NOT NULL DEFAULT '0',
  `LastChanged` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`Id`),
  KEY `KindVoogdId_idx` (`KindVoogd`),
  KEY `WerkingId_idx` (`Werking`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1359 ;

-- --------------------------------------------------------

--
-- Table structure for table `Betaling`
--

CREATE TABLE IF NOT EXISTS `Betaling` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `KindVoogd` int(11) NOT NULL,
  `Bedrag` decimal(10,2) NOT NULL,
  `Opmerking` text NOT NULL,
  `Datum` date NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Extraatje`
--

CREATE TABLE IF NOT EXISTS `Extraatje` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschrijving` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

-- --------------------------------------------------------

--
-- Table structure for table `ExtraatjeAanwezigheid`
--

CREATE TABLE IF NOT EXISTS `ExtraatjeAanwezigheid` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Aanwezigheid` int(11) NOT NULL,
  `Extraatje` int(11) NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=947 ;

-- --------------------------------------------------------

--
-- Table structure for table `Kind`
--

CREATE TABLE IF NOT EXISTS `Kind` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Voornaam` varchar(150) NOT NULL,
  `Naam` varchar(150) NOT NULL,
  `Geboortejaar` year(4) NOT NULL,
  `DefaultWerking` int(11) NOT NULL,
  `Belangrijk` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=426 ;

-- --------------------------------------------------------

--
-- Table structure for table `KindVoogd`
--

CREATE TABLE IF NOT EXISTS `KindVoogd` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Kind` int(11) NOT NULL,
  `Voogd` int(11) NOT NULL,
  `Saldo` decimal(10,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Kind` (`Kind`,`Voogd`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=493 ;

-- --------------------------------------------------------

--
-- Table structure for table `Log`
--

CREATE TABLE IF NOT EXISTS `Log` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `Title` text NOT NULL,
  `Value` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=42 ;

-- --------------------------------------------------------

--
-- Table structure for table `Uitstap`
--

CREATE TABLE IF NOT EXISTS `Uitstap` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Datum` date NOT NULL,
  `Omschrijving` text NOT NULL,
  `DashboardZichtbaar` tinyint(1) NOT NULL,
  `AanwezigheidZichtbaar` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Table structure for table `UitstapKind`
--

CREATE TABLE IF NOT EXISTS `UitstapKind` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Kind` int(11) NOT NULL,
  `Uitstap` int(11) NOT NULL,
  PRIMARY KEY (`Id`),
  UNIQUE KEY `Kind_Uitstap` (`Kind`,`Uitstap`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=40 ;

-- --------------------------------------------------------

--
-- Table structure for table `Voogd`
--

CREATE TABLE IF NOT EXISTS `Voogd` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Naam` text NOT NULL,
  `Voornaam` text NOT NULL,
  `Opmerkingen` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=381 ;

-- --------------------------------------------------------

--
-- Table structure for table `Vordering`
--

CREATE TABLE IF NOT EXISTS `Vordering` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Aanwezigheid` int(11) NOT NULL,
  `Bedrag` decimal(10,2) NOT NULL,
  `Opmerking` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `Werking`
--

CREATE TABLE IF NOT EXISTS `Werking` (
  `Id` int(11) NOT NULL AUTO_INCREMENT,
  `Omschrijving` text NOT NULL,
  `Afkorting` text NOT NULL,
  PRIMARY KEY (`Id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
