-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Feb 09, 2015 at 03:27 PM
-- Server version: 5.6.17
-- PHP Version: 5.5.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `sosemergency`
--

-- --------------------------------------------------------

--
-- Table structure for table `emergency_codes`
--

CREATE TABLE IF NOT EXISTS `emergency_codes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `color_name` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `icon` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `color_hex` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `emergency_codes`
--

INSERT INTO `emergency_codes` (`id`, `color_name`, `description`, `icon`, `color_hex`) VALUES
(3, 'Orange', 'Mass Casualties', NULL, '#e67e22'),
(2, 'Blue', 'Medical Need', NULL, '#22A7F0'),
(1, 'Red', 'Fire', NULL, '#CF000F'),
(4, 'Yellow', 'Missing Patient and Abducted Person', NULL, '#F7CA18'),
(5, 'Brown', 'Hazardous Spill', NULL, '#7a5230'),
(6, 'Black', 'Crime Incident', NULL, '#0C090A');

-- --------------------------------------------------------

--
-- Table structure for table `person_units`
--

CREATE TABLE IF NOT EXISTS `person_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `home_lat` float(10,6) DEFAULT NULL,
  `home_lng` float(10,6) DEFAULT NULL,
  `birth_date` timestamp NULL DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deviceID` text COLLATE utf8_unicode_ci,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `person_units`
--

INSERT INTO `person_units` (`id`, `name`, `home_lat`, `home_lng`, `birth_date`, `gender`, `email`, `contact_number`, `deviceID`, `updated_at`, `created_at`) VALUES
(1, 'asdasd', 123.000000, 12.000000, NULL, 'dsa', 'asd', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(2, 'Kevin Rey Tabada', 123.000000, 12.000000, NULL, 'dsa', 'asd', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(3, 'Jerald Patalinghug', 12.000000, 12.000000, NULL, 'Male', 'GloriousInvocation', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(4, 'tabada', 12.000000, 12.000000, NULL, 'gender', 'jasdjklasd', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(5, 'jeraldpunx', 12.000000, 12.000000, NULL, 'Male', 'jeraldpunx@yahoo.com', '', '', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
(6, 'hospital1', 12.000000, 12.000000, '0000-00-00 00:00:00', 'male', 'hospital1', '', '', '2015-02-01 00:29:50', '2015-02-01 00:29:50');

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE IF NOT EXISTS `reports` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pu_id` int(11) DEFAULT NULL,
  `ru_id` int(11) DEFAULT NULL,
  `ec_id` int(11) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `date_reported` timestamp NULL DEFAULT NULL,
  `date_responded` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pu_reports` (`pu_id`),
  KEY `fk_ru_reports` (`ru_id`),
  KEY `fk_ec_reports` (`ec_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `pu_id`, `ru_id`, `ec_id`, `lat`, `lng`, `date_reported`, `date_responded`) VALUES
(1, 1, 2, 6, 51.503365, -0.127625, '2015-02-09 07:44:47', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `rescue_units`
--

CREATE TABLE IF NOT EXISTS `rescue_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `email` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=49 ;

--
-- Dumping data for table `rescue_units`
--

INSERT INTO `rescue_units` (`id`, `name`, `address`, `lat`, `lng`, `email`, `type`, `status`) VALUES
(1, 'Hospital 11', ' Perpetual Succour Hospital - Emergency Room, Cebu City, Cebu, Philippines', 10.314925, 123.899460, 'testing@yahoo.com', 'hospital', 1),
(2, 'Hospital 2', 'Rosal Street, Cebu City, Cebu, Philippines', 10.310258, 123.890717, 'testing@yahoo.com', 'hospital', 1),
(3, 'Hospital 3', ' Dr. Victoria Redula, Cebu City, 6000 Cebu, Philippines', 10.306480, 123.894791, 'testing@yahoo.com', 'hospital', 1),
(4, 'FireControl 1', 'Tabunok, Cebu City, Philippines', 10.266134, 123.841866, 'testing@yahoo.com', 'firecontrol', 1),
(5, 'FireControl 2', 'Bulacao, Cebu City, Philippines', 10.272290, 123.847961, 'testing@yahoo.com', 'firecontrol', 1),
(6, 'FireControl 3', 'Pardo, Cebu City,Philippines', 10.279087, 123.855019, 'testing@yahoo.com', 'firecontrol', 1),
(7, 'Police 1', 'Pardo, Cebu City, Philippines', 10.280226, 123.856247, 'testing@yahoo.com', 'police', 1),
(8, 'Police 2', 'Basak, Cebu City, Philippines', 10.286032, 123.861458, 'testing@yahoo.com', 'police', 1),
(9, 'Police 3', 'Mambaling, Cebu City, Philippines', 10.290212, 123.875854, 'testing@yahoo.com', 'police', 1),
(29, 'erap1', 'asdasdssss', 10.315595, 123.919601, 'asdasdss', 'rescuevolunteer', 1),
(30, 'erap2', 'axxxsssss', 10.309346, 123.915314, 'xxxasd', 'rescuevolunteer', 1),
(31, 'erap3', '', 10.318635, 123.898483, NULL, 'rescuevolunteer', 1),
(42, 'Test', 'Sunshine Valley Road, Cebu City, Cebu, Philippines', 10.334273, 123.850594, 'Test', 'hospital', 1),
(34, 'asd', 'Magahaway Road, Talisay City, Cebu, Philippines', 10.264959, 123.815231, 'dsads', 'hospital', 1),
(43, 'Tests111', 'Mundo Farm, Cebu City, Cebu, Philippines', 10.315257, 123.846130, 'asdasd', 'rescuevolunteer', 0),
(44, 'asdasd', 'San Isidro Road, Talisay City, 6045 Cebu, Philippines', 10.255905, 123.840981, 'asdasd', 'hospital', 1),
(45, 'asdasd', 'Cebu South Coastal Road, Talisay City, Cebu, Philippines', 10.256580, 123.825188, 'dsasd', 'firecontrol', 1),
(46, '123123asdasd', 'Saint Mary Street, Minglanilla, Cebu, Philippines', 10.247796, 123.793945, 'asdasd', 'rescuevolunteer', 0),
(47, 'asdasd', 'Estaka, City of Naga, Cebu, Philippines', 10.253878, 123.763390, 'dsasdasd', 'police', 1),
(48, 'fukme', 'jongkera', 10.304887, 123.848534, 'punxhazzard_69@yahoo.com', 'police', 1);

-- --------------------------------------------------------

--
-- Table structure for table `ru_contacts`
--

CREATE TABLE IF NOT EXISTS `ru_contacts` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ru_id` int(11) DEFAULT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `deviceID` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=14 ;

--
-- Dumping data for table `ru_contacts`
--

INSERT INTO `ru_contacts` (`id`, `ru_id`, `contact_number`, `deviceID`) VALUES
(5, 29, '5555s', NULL),
(6, 29, '6666666666666', NULL),
(7, 29, '7777777', NULL),
(8, 29, '8888888', NULL),
(9, 29, '9999999', NULL),
(10, 29, '10', NULL),
(12, 29, '1222', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `ru_ec`
--

CREATE TABLE IF NOT EXISTS `ru_ec` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ru_id` int(11) DEFAULT NULL,
  `ec_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ru_ruec` (`ru_id`),
  KEY `fk_ec_ruec` (`ec_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=24 ;

--
-- Dumping data for table `ru_ec`
--

INSERT INTO `ru_ec` (`id`, `ru_id`, `ec_id`) VALUES
(1, 29, 1),
(2, 30, 1),
(3, 31, 1),
(4, 29, 2),
(5, 30, 2),
(6, 31, 2),
(23, 48, 6),
(22, 48, 4),
(10, 4, 1),
(11, 5, 1),
(12, 6, 1),
(13, 44, 2),
(14, 45, 1),
(15, 46, 1),
(16, 46, 2),
(17, 46, 6),
(18, 47, 6),
(19, 7, 6),
(20, 8, 6),
(21, 9, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `puid_ruid` int(11) DEFAULT NULL,
  `type` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `username` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `password` varchar(60) COLLATE utf8_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ru_users` (`puid_ruid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=19 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `puid_ruid`, `type`, `username`, `password`, `status`, `created_at`, `updated_at`, `remember_token`) VALUES
(4, 4, 'pu', 'tabada123', '$2y$10$dT5l9GZHrdy.lKwZ/Bg9nOWXtQF89cFB5FYgjCBplbpfm/LNIh/ry', NULL, '2015-01-21 05:12:05', '2015-01-21 05:12:05', NULL),
(3, 3, 'pu', 'jeraldpunx', '$2y$10$dT5l9GZHrdy.lKwZ/Bg9nOWXtQF89cFB5FYgjCBplbpfm/LNIh/ry', NULL, '2015-01-17 08:54:46', '2015-01-17 08:54:46', NULL),
(5, 5, 'pu', 'punxnotdead', '$2y$10$dT5l9GZHrdy.lKwZ/Bg9nOWXtQF89cFB5FYgjCBplbpfm/LNIh/ry', NULL, '2015-01-21 05:16:09', '2015-01-21 05:16:09', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
