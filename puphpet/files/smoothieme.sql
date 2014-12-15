-- phpMyAdmin SQL Dump
-- version 4.2.7.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Erstellungszeit: 15. Dez 2014 um 22:22
-- Server Version: 5.6.20
-- PHP-Version: 5.5.15

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Datenbank: `dbsmoothieme`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `accounts`
--

CREATE TABLE IF NOT EXISTS `accounts` (
`ID` bigint(20) unsigned NOT NULL,
  `role` enum('admin','user') NOT NULL,
  `name` varchar(45) NOT NULL,
  `password` text NOT NULL,
  `salt` varchar(50) NOT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=2 ;

--
-- Daten für Tabelle `accounts`
--

INSERT INTO `accounts` (`ID`, `role`, `name`, `password`, `salt`) VALUES
(1, 'admin', 'admin', 'ed1c972305635e5be40aa72f6c0c1bd84cb0a8d1', 'saltsaltsalt');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `addresses`
--

CREATE TABLE IF NOT EXISTS `addresses` (
`ID` bigint(20) unsigned NOT NULL,
  `customer_ID` bigint(20) unsigned NOT NULL,
  `street` varchar(70) NOT NULL,
  `house_nr` varchar(5) NOT NULL,
  `city` varchar(70) NOT NULL,
  `zip` int(5) NOT NULL,
  `country` varchar(70) NOT NULL,
  `delivery_notes` text
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `customer`
--

CREATE TABLE IF NOT EXISTS `customer` (
`ID` bigint(20) unsigned NOT NULL,
  `accounts_ID` bigint(20) unsigned NOT NULL,
  `surname` varchar(70) NOT NULL,
  `lastname` varchar(70) NOT NULL,
  `email` varchar(70) NOT NULL,
  `gender` enum('f','m') NOT NULL,
  `tel` varchar(70) NOT NULL,
  `birthdate` date DEFAULT NULL
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=5 ;

--
-- Daten für Tabelle `customer`
--

INSERT INTO `customer` (`ID`, `accounts_ID`, `surname`, `lastname`, `email`, `gender`, `tel`, `birthdate`) VALUES
(4, 1, 'S', 'A', 's@b.com', 'm', '123123', NULL);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `fruits`
--

CREATE TABLE IF NOT EXISTS `fruits` (
`ID` bigint(20) unsigned NOT NULL,
  `name` varchar(70) NOT NULL,
  `color` char(6) NOT NULL,
  `price` decimal(14,7) NOT NULL COMMENT 'je 100 ml',
  `description` text NOT NULL,
  `kcal` int(11) NOT NULL,
  `origin` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `orders`
--

CREATE TABLE IF NOT EXISTS `orders` (
`ID` bigint(20) unsigned NOT NULL,
  `delivery_address` bigint(20) unsigned NOT NULL,
  `invoice_address` bigint(20) unsigned NOT NULL,
  `delivery_method` enum('bike','post') NOT NULL,
  `payment_method` enum('rechnung','nachnahme','paypal') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `smoothies`
--

CREATE TABLE IF NOT EXISTS `smoothies` (
`ID` bigint(20) unsigned NOT NULL,
  `size` enum('S','M','L') NOT NULL,
  `name` varchar(70) DEFAULT NULL,
  `customer_ID` bigint(20) unsigned DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `smoothies_has_fruits`
--

CREATE TABLE IF NOT EXISTS `smoothies_has_fruits` (
  `smoothie_ID` bigint(20) unsigned NOT NULL,
  `fruit_ID` bigint(20) unsigned NOT NULL,
  `ml` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `smoothies_has_orders`
--

CREATE TABLE IF NOT EXISTS `smoothies_has_orders` (
  `smoothies_ID` bigint(20) unsigned NOT NULL,
  `orders_ID` bigint(20) unsigned NOT NULL,
  `count` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
 ADD PRIMARY KEY (`ID`), ADD KEY `customer_ID` (`customer_ID`);

--
-- Indexes for table `customer`
--
ALTER TABLE `customer`
 ADD PRIMARY KEY (`ID`,`accounts_ID`), ADD KEY `fk_customer_accounts1_idx` (`accounts_ID`);

--
-- Indexes for table `fruits`
--
ALTER TABLE `fruits`
 ADD PRIMARY KEY (`ID`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
 ADD PRIMARY KEY (`ID`), ADD UNIQUE KEY `ID` (`ID`), ADD KEY `delivery_address` (`delivery_address`), ADD KEY `send-bill-to_idx` (`invoice_address`);

--
-- Indexes for table `smoothies`
--
ALTER TABLE `smoothies`
 ADD PRIMARY KEY (`ID`), ADD KEY `customer_ID` (`customer_ID`);

--
-- Indexes for table `smoothies_has_fruits`
--
ALTER TABLE `smoothies_has_fruits`
 ADD PRIMARY KEY (`smoothie_ID`,`fruit_ID`), ADD KEY `fk_smoothie_has_fruits_fruits1_idx` (`fruit_ID`), ADD KEY `fk_smoothie_has_fruits_smoothie1_idx` (`smoothie_ID`);

--
-- Indexes for table `smoothies_has_orders`
--
ALTER TABLE `smoothies_has_orders`
 ADD PRIMARY KEY (`smoothies_ID`,`orders_ID`), ADD KEY `fk_smoothies_has_orders_orders1_idx` (`orders_ID`), ADD KEY `fk_smoothies_has_orders_smoothies1_idx` (`smoothies_ID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `customer`
--
ALTER TABLE `customer`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `fruits`
--
ALTER TABLE `fruits`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `smoothies`
--
ALTER TABLE `smoothies`
MODIFY `ID` bigint(20) unsigned NOT NULL AUTO_INCREMENT;
--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `accounts`
--
ALTER TABLE `accounts`
ADD CONSTRAINT `accounts_ibfk_1` FOREIGN KEY (`ID`) REFERENCES `customer` (`accounts_ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `addresses`
--
ALTER TABLE `addresses`
ADD CONSTRAINT `addresses_ibfk_1` FOREIGN KEY (`customer_ID`) REFERENCES `customer` (`ID`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `orders`
--
ALTER TABLE `orders`
ADD CONSTRAINT `send-bill-to` FOREIGN KEY (`invoice_address`) REFERENCES `addresses` (`ID`) ON UPDATE CASCADE,
ADD CONSTRAINT `send-packet-to` FOREIGN KEY (`delivery_address`) REFERENCES `addresses` (`ID`) ON UPDATE CASCADE;

--
-- Constraints der Tabelle `smoothies`
--
ALTER TABLE `smoothies`
ADD CONSTRAINT `smoothie_ibfk_2` FOREIGN KEY (`customer_ID`) REFERENCES `customer` (`ID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints der Tabelle `smoothies_has_fruits`
--
ALTER TABLE `smoothies_has_fruits`
ADD CONSTRAINT `fk_smoothie_has_fruits_fruits1` FOREIGN KEY (`fruit_ID`) REFERENCES `fruits` (`ID`) ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_smoothie_has_fruits_smoothie1` FOREIGN KEY (`smoothie_ID`) REFERENCES `smoothies` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION;

--
-- Constraints der Tabelle `smoothies_has_orders`
--
ALTER TABLE `smoothies_has_orders`
ADD CONSTRAINT `fk_smoothies_has_orders_orders1` FOREIGN KEY (`orders_ID`) REFERENCES `orders` (`ID`) ON DELETE CASCADE ON UPDATE NO ACTION,
ADD CONSTRAINT `fk_smoothies_has_orders_smoothies1` FOREIGN KEY (`smoothies_ID`) REFERENCES `smoothies` (`ID`) ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
