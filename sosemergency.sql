-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 08, 2015 at 05:02 AM
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
(1, 'Red', 'Fire', NULL, '#CF000F'),
(2, 'Blue', 'Medical Need', NULL, '#22A7F0'),
(3, 'Orange', 'Mass Casualties', NULL, '#e67e22'),
(4, 'Yellow', 'Missing Patient and Abducted Person', NULL, '#F7CA18'),
(5, 'Brown', 'Hazardous Spill', NULL, '#7a5230'),
(6, 'Black', 'Crime Incident', NULL, '#3B3433');

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;


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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `puid_ruid`, `type`, `username`, `password`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, NULL, 'admin', 'admin123', '$2y$10$R7YvKWuWp5FC273VoVxYEuVDlPpDSxutlMkUCYdDNbGm7Gq7Su24e', '2015-02-26 07:28:28', '2015-02-26 07:28:28', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
