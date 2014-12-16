-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Dec 15, 2014 at 11:13 PM
-- Server version: 5.5.40-0ubuntu0.14.04.1
-- PHP Version: 5.6.3-1+deb.sury.org~trusty+1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `dbsmoothieme`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `role` enum('admin','user') NOT NULL,
  `name` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `salt` varchar(50) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`ID`, `role`, `name`, `password`, `salt`) VALUES
(1, 'admin', 'admin', 'ed1c972305635e5be40aa72f6c0c1bd84cb0a8d1', 'saltsaltsalt'),
(13, 'user', 'user', 'ed1c972305635e5be40aa72f6c0c1bd84cb0a8d1', 'saltsaltsalt');

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `customer_ID` bigint(20) unsigned NOT NULL,
  `street` varchar(70) NOT NULL,
  `house_nr` varchar(5) NOT NULL,
  `city` varchar(70) NOT NULL,
  `zip` int(5) NOT NULL,
  `country` varchar(70) NOT NULL,
  `delivery_notes` text,
  PRIMARY KEY (`ID`),
  KEY `customer_ID` (`customer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `accounts_ID` bigint(20) unsigned NOT NULL,
  `surname` varchar(70) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `gender` enum('f','m') NOT NULL,
  `tel` varchar(70) NOT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`ID`,`accounts_ID`),
  KEY `fk_customer_accounts1_idx` (`accounts_ID`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=6 ;

--
-- Dumping data for table `customer`
--

INSERT INTO `customer` (`ID`, `accounts_ID`, `surname`, `lastname`, `email`, `gender`, `tel`, `birthdate`) VALUES
(4, 1, 'S', 'A', 's@b.com', 'm', '123123', NULL),
(5, 13, 'Franz', 'Müller', 'bla@blub.de', 'm', '12124124', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `fruits`
--

CREATE TABLE IF NOT EXISTS `fruits` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(70) NOT NULL,
  `color` char(6) NOT NULL,
  `price` decimal(14,7) NOT NULL COMMENT 'je 100 ml',
  `description` text NOT NULL,
  `kcal` int(11) NOT NULL,
  PRIMARY KEY (`ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `delivery_address` bigint(20) unsigned NOT NULL,
  `invoice_address` bigint(20) unsigned NOT NULL,
  `delivery_method` enum('bike','post') NOT NULL,
  `payment_method` enum('rechnung','nachnahme','paypal') NOT NULL,
  PRIMARY KEY (`ID`),
  UNIQUE KEY `ID` (`ID`),
  KEY `delivery_address` (`delivery_address`),
  KEY `send-bill-to_idx` (`invoice_address`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `smoothies`
--

CREATE TABLE IF NOT EXISTS `smoothies` (
  `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `size` enum('S','M','L') NOT NULL,
  `name` varchar(70) DEFAULT NULL,
  `customer_ID` bigint(20) unsigned DEFAULT NULL,
  PRIMARY KEY (`ID`),
  KEY `customer_ID` (`customer_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `smoothies_has_fruits`
--

CREATE TABLE IF NOT EXISTS `smoothies_has_fruits` (
  `smoothie_ID` bigint(20) unsigned NOT NULL,
  `fruit_ID` bigint(20) unsigned NOT NULL,
  `ml` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`smoothie_ID`,`fruit_ID`),
  KEY `fk_smoothie_has_fruits_fruits1_idx` (`fruit_ID`),
  KEY `fk_smoothie_has_fruits_smoothie1_idx` (`smoothie_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `smoothies_has_orders`
--

CREATE TABLE IF NOT EXISTS `smoothies_has_orders` (
  `smoothies_ID` bigint(20) unsigned NOT NULL,
  `orders_ID` bigint(20) unsigned NOT NULL,
  `count` int(11) NOT NULL,
  PRIMARY KEY (`smoothies_ID`,`orders_ID`),
  KEY `fk_smoothies_has_orders_orders1_idx` (`orders_ID`),
  KEY `fk_smoothies_has_orders_smoothies1_idx` (`smoothies_ID`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `accounts`
--
ALTER TABLE `accounts`
  ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `customer` (`accounts_ID`) ON DELETE CASCADE;

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`customer_ID`) REFERENCES `customer` (`ID`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `send-bill-to` FOREIGN KEY (`invoice_address`) REFERENCES `addresses` (`ID`) ON UPDATE CASCADE,
  ADD CONSTRAINT `send-packet-to` FOREIGN KEY (`delivery_address`) REFERENCES `addresses` (`ID`) ON UPDATE CASCADE;

--
-- Constraints for table `smoothies`
--
ALTER TABLE `smoothies`
  ADD CONSTRAINT `smoothie_ibfk_2` FOREIGN KEY (`customer_ID`) REFERENCES `customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `smoothies_has_fruits`
--
ALTER TABLE `smoothies_has_fruits`
  ADD CONSTRAINT `fk_smoothie_has_fruits_fruits1` FOREIGN KEY (`fruit_ID`) REFERENCES `fruits` (`ID`) ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_smoothie_has_fruits_smoothie1` FOREIGN KEY (`smoothie_ID`) REFERENCES `smoothies` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints for table `smoothies_has_orders`
--
ALTER TABLE `smoothies_has_orders`
  ADD CONSTRAINT `fk_smoothies_has_orders_orders1` FOREIGN KEY (`orders_ID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
  ADD CONSTRAINT `fk_smoothies_has_orders_smoothies1` FOREIGN KEY (`smoothies_ID`) REFERENCES `smoothies` (`ID`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
