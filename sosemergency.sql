-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2015 at 02:39 PM
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `emergency_codes`
--

INSERT INTO `emergency_codes` (`id`, `color_name`, `description`, `icon`, `color_hex`) VALUES
(1, 'Red', 'Fire', 'fire.png', '#CF000F'),
(2, 'Blue', 'Medical Need', 'mass.png', '#22A7F0'),
(3, 'Orange', 'Mass Casualties', 'masscasualties.png', '#e67e22'),
(4, 'Yellow', 'Missing Patient and Abducted Person', 'mass.png', '#F7CA18'),
(5, 'Brown', 'Hazardous Spill', 'hazardous.png', '#7a5230'),
(6, 'Black', 'Crime Incident', 'crime.png', '#3B3433');

-- --------------------------------------------------------

--
-- Table structure for table `person_units`
--

CREATE TABLE IF NOT EXISTS `person_units` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `birth_date` date DEFAULT NULL,
  `gender` varchar(10) COLLATE utf8_unicode_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  `contact_number` varchar(20) COLLATE utf8_unicode_ci DEFAULT NULL,
  `deviceID` text COLLATE utf8_unicode_ci,
  `updated_at` timestamp NOT NULL,
  `created_at` timestamp NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
  `date_received` timestamp NULL DEFAULT NULL,
  `date_responded` timestamp NULL DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `report_group` varchar(8) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_pu_reports` (`pu_id`),
  KEY `fk_ru_reports` (`ru_id`),
  KEY `fk_ec_reports` (`ec_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Table structure for table `reports_queue`
--

CREATE TABLE IF NOT EXISTS `reports_queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pu_id` int(11) DEFAULT NULL,
  `ru_id` int(11) DEFAULT NULL,
  `ec_id` int(11) DEFAULT NULL,
  `lat` float(10,6) DEFAULT NULL,
  `lng` float(10,6) DEFAULT NULL,
  `date_reported` timestamp NULL DEFAULT NULL,
  `date_received` timestamp NULL DEFAULT NULL,
  `mobile` int(11) DEFAULT NULL,
  `report_group` varchar(8) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `rescue_units`
--

INSERT INTO `rescue_units` (`id`, `name`, `address`, `lat`, `lng`, `email`, `type`, `status`) VALUES
(1, 'Tisa Hospital', 'lot 27 Francisco Llamas Street, Cebu City, 6000 Cebu, Philippines', 10.300627, 123.869629, 'tisa', 'hospital', 1),
(2, 'Quiot Hospital', 'East Sabellano Street, Cebu City, Cebu, Philippines', 10.292203, 123.859894, '', 'hospital', 1),
(3, 'Pardo Hospital', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.279477, 123.855576, 'pardohospital@gmail.com', 'hospital', 1),
(4, 'Pardo Fire Station', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.278881, 123.855164, 'pardofirestation@gmail.com', 'firecontrol', 1),
(5, 'Quiot Fire Station', 'East Sabellano Street, Cebu City, Cebu, Philippines', 10.287199, 123.856873, 'quiotfirestation@gmail.com', 'firecontrol', 1),
(6, 'Lawaan Fire Station', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.271481, 123.847443, 'lawaanfirestation@gmail.com', 'firecontrol', 1),
(7, 'Minglanilla Police Station', 'Natalio B. Bacalso South National Highway, Minglanilla, Cebu, Philippines', 10.245161, 123.794243, 'minglanillapolice@gmail.com', 'police', 1),
(8, 'Mabolo Police Station', 'M. J. Cuenco Avenue, Cebu City, Cebu, Philippines', 10.314017, 123.914207, 'mabolopolice@gmail.com', 'police', 1),
(9, 'Salazar Police Station', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.294272, 123.881973, 'salazarpolice@yahoo.com', 'police', 1),
(10, 'Mambaling ERAF', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.290599, 123.876297, 'mambalingeraf@gmail.com', 'rescuevolunteer', 1),
(11, 'Inayawan ERAF', 'F. Jaca Street, Cebu City, 6000 Cebu, Philippines', 10.269665, 123.855858, 'inayawaneraf@gmail.com', 'rescuevolunteer', 1),
(12, 'Fuente ERAF', 'Osmeña Boulevard, Cebu City, Cebu, Philippines', 10.309050, 123.893463, 'fuenteeraf@gmail.com', 'rescuevolunteer', 1);

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=13 ;

--
-- Dumping data for table `ru_contacts`
--

INSERT INTO `ru_contacts` (`id`, `ru_id`, `contact_number`, `deviceID`) VALUES
(1, 4, '639332587113', NULL),
(2, 3, '639322962732', NULL),
(3, 2, '639332587113', NULL),
(4, 12, '639332587113', NULL),
(5, 8, '639332587113', NULL),
(6, 6, '639322962732', NULL),
(7, 11, '639322962732', NULL),
(8, 9, '639322962732', NULL),
(9, 10, '639325083706', NULL),
(10, 1, '639325083706', NULL),
(11, 5, '639325083706', NULL),
(12, 7, '639325083706', NULL);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=25 ;

--
-- Dumping data for table `ru_ec`
--

INSERT INTO `ru_ec` (`id`, `ru_id`, `ec_id`) VALUES
(1, 1, 2),
(2, 1, 5),
(3, 2, 2),
(4, 2, 5),
(5, 3, 2),
(6, 3, 5),
(7, 4, 1),
(8, 4, 3),
(9, 5, 1),
(10, 5, 3),
(11, 6, 1),
(12, 6, 3),
(13, 7, 4),
(14, 7, 6),
(15, 8, 2),
(16, 8, 5),
(17, 9, 4),
(18, 9, 6),
(19, 10, 1),
(20, 10, 2),
(21, 11, 2),
(22, 11, 5),
(23, 12, 1),
(24, 12, 2);

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
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `remember_token` varchar(100) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_ru_users` (`puid_ruid`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `puid_ruid`, `type`, `username`, `password`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, NULL, 'admin', 'admin123', '$2y$10$R7YvKWuWp5FC273VoVxYEuVDlPpDSxutlMkUCYdDNbGm7Gq7Su24e', '2015-02-26 07:28:28', '2015-02-26 07:28:28', NULL),
(2, 1, 'ru', 'tisahospital', '$2y$10$8Fe0uIhkXU0DFB3e5qVkIe/P9lqAPb2NKTqT/SZ0bAuY6bTIo4fLa', '2015-03-08 13:16:40', '2015-03-08 13:16:40', NULL),
(3, 2, 'ru', 'quiothospital', '$2y$10$MeoBjokiGZZGgctHX6Bjr.ZJmU7/9aXY1iXMZ2nGbP3dAMUN2lzwW', '2015-03-08 13:17:46', '2015-03-08 13:17:46', NULL),
(4, 3, 'ru', 'pardohospital', '$2y$10$BYtXbLFGgZ0GE/eKBD0yB.HYNiJmuAmk1vNfE0JAFbcaq3WOWyQ9G', '2015-03-08 13:18:39', '2015-03-08 13:18:39', NULL),
(5, 4, 'ru', 'pardofirestation', '$2y$10$trmOM/TObDfFJnlhZNNwluOsuxnXzTJqZEjwh.TNUyLmZxZxcAigq', '2015-03-08 13:19:25', '2015-03-08 13:19:25', NULL),
(6, 5, 'ru', 'quiotfirestation', '$2y$10$5O/Bo7PQV243Zv9r.UEJ5uxqpPlgOSL2fn3HTwJLfH6KDKOT/A1JO', '2015-03-08 13:20:44', '2015-03-08 13:20:44', NULL),
(7, 6, 'ru', 'lawaanfirestation', '$2y$10$lZigqAtnTSsxM0r3tWcUx.CcMKh5VxNHTTN5Be52MEkm6L4eZfBiG', '2015-03-08 13:21:41', '2015-03-08 13:21:41', NULL),
(8, 7, 'ru', 'minglanillapolice', '$2y$10$7ViUCknb6WHAz2f5P8hFMeAHSbAfh2Ymd8UaBLF40MNbAJxuWcm..', '2015-03-08 13:22:37', '2015-03-08 13:22:37', NULL),
(9, 8, 'ru', 'mabolopolice', '$2y$10$AIDzUAe2YHCQX7OYX2RPNO9v0FIFU5Z8cjtNUBDFRTz5r8yYNyHvm', '2015-03-08 13:23:23', '2015-03-08 13:23:23', NULL),
(10, 9, 'ru', 'salazarpolice', '$2y$10$GyLvYFJ6aTBpeJrkjJnbPu26ia42r2Df0pSw50Anv/4uO4Ky6F58y', '2015-03-08 13:24:49', '2015-03-08 13:24:49', NULL),
(11, 10, 'ru', 'mambalingeraf', '$2y$10$JruBmNfmIgAgtTWELzEK1OEhot46FSlthcEbI7I/4gM.A.30fqmOW', '2015-03-08 13:25:56', '2015-03-08 13:25:56', NULL),
(12, 11, 'ru', 'inayawaneraf', '$2y$10$/IUJTdJX8QrpjZi24yIm/OidvADVrNzwrTZ8Fpl2bkTxBvK1Np9Za', '2015-03-08 13:26:55', '2015-03-08 13:26:55', NULL),
(13, 12, 'ru', 'fuenteeraf', '$2y$10$FpHloW0CljWAb1IJgIB4SeZ3fpJaNjrX4qvd2XJl2/MK4gP.qXaHK', '2015-03-08 13:27:47', '2015-03-08 13:27:47', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
