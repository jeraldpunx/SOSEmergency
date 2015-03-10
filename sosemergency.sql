-- phpMyAdmin SQL Dump
-- version 4.1.14
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2015 at 02:31 AM
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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `person_units`
--

INSERT INTO `person_units` (`id`, `name`, `birth_date`, `gender`, `email`, `contact_number`, `deviceID`, `updated_at`, `created_at`) VALUES
(1, 'kevin rey tabada', '2015-03-09', 'male', 'reytabs@yahoo.com', '639332587113', 'APA91bEuRT2AuRYvRW9GQ6mTMk3kKWdQh_b-f51M_ftfelauWruf3pOrQfWUGlKIGsR-cYhlXD0HQujAGgzQFJTQt_DDsvkTtWWWpI4cjZhJe6TpnLDa26wOczxhxXS2drVc7erCPn9RZplEes65b2_RojmYR_Se8I-8aO1ZOD_bHJ3hh8YdQ0A', '2015-03-09 00:13:40', '2015-03-09 00:13:40');

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
  `report_image` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `fk_pu_reports` (`pu_id`),
  KEY `fk_ru_reports` (`ru_id`),
  KEY `fk_ec_reports` (`ec_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=58 ;

--
-- Dumping data for table `reports`
--

INSERT INTO `reports` (`id`, `pu_id`, `ru_id`, `ec_id`, `lat`, `lng`, `date_reported`, `date_received`, `date_responded`, `mobile`, `report_group`, `report_image`) VALUES
(1, 3, 2, 2, 10.289056, 123.862350, '2015-03-09 00:08:37', '2015-03-09 00:08:37', NULL, 0, 'S3HUKSOH', NULL),
(2, 3, 2, 2, 10.289056, 123.862350, '2015-03-09 00:08:58', '2015-03-09 00:08:58', NULL, 0, 'E4KZL3X3', NULL),
(3, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:14:24', '2015-03-09 00:14:24', NULL, 0, 'JVNS8J7S', NULL),
(4, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:14:29', '2015-03-09 00:15:00', NULL, 0, 'JVNS8J7S', NULL),
(5, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:15:15', '2015-03-09 00:15:15', NULL, 0, 'T1XBLC8Y', NULL),
(6, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:14:29', '2015-03-09 00:15:30', NULL, 0, 'JVNS8J7S', NULL),
(7, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:15:15', '2015-03-09 00:15:46', NULL, 0, 'T1XBLC8Y', NULL),
(8, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:15:53', '2015-03-09 00:15:53', NULL, 0, '3Y0W5TSW', NULL),
(9, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:15:15', '2015-03-09 00:16:16', NULL, 0, 'T1XBLC8Y', NULL),
(10, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:15:53', '2015-03-09 00:16:24', NULL, 0, '3Y0W5TSW', NULL),
(11, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:16:28', '2015-03-09 00:16:28', NULL, 0, '3EQ6RNDF', NULL),
(12, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:16:51', '2015-03-09 00:16:51', NULL, 0, 'J2UV8LQ8', NULL),
(13, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:15:53', '2015-03-09 00:16:54', NULL, 0, '3Y0W5TSW', NULL),
(14, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:16:28', '2015-03-09 00:16:59', NULL, 0, '3EQ6RNDF', NULL),
(15, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:17:21', '2015-03-09 00:17:21', NULL, 0, 'LKUMPSS8', NULL),
(16, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:16:52', '2015-03-09 00:17:23', NULL, 0, 'J2UV8LQ8', NULL),
(17, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:16:28', '2015-03-09 00:17:29', NULL, 0, '3EQ6RNDF', NULL),
(18, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:17:21', '2015-03-09 00:17:52', NULL, 0, 'LKUMPSS8', NULL),
(19, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:16:52', '2015-03-09 00:17:53', NULL, 0, 'J2UV8LQ8', NULL),
(20, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:18:11', '2015-03-09 00:18:11', NULL, 0, '7Q4QAEL3', NULL),
(21, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:17:21', '2015-03-09 00:18:22', NULL, 0, 'LKUMPSS8', NULL),
(22, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:18:11', '2015-03-09 00:18:42', NULL, 0, '7Q4QAEL3', NULL),
(23, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:18:11', '2015-03-09 00:19:12', NULL, 0, '7Q4QAEL3', NULL),
(24, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:23:30', '2015-03-09 00:23:30', NULL, 0, 'N1ND08Z4', NULL),
(25, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:23:30', '2015-03-09 00:24:01', NULL, 0, 'N1ND08Z4', NULL),
(26, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:23:30', '2015-03-09 00:24:31', NULL, 0, 'N1ND08Z4', NULL),
(27, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:26:01', '2015-03-09 00:26:01', NULL, 0, '1FN4ZHNY', NULL),
(28, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:26:01', '2015-03-09 00:26:32', NULL, 0, '1FN4ZHNY', NULL),
(29, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:26:01', '2015-03-09 00:27:02', NULL, 0, '1FN4ZHNY', NULL),
(30, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:33:49', '2015-03-09 00:33:49', NULL, 0, 'T4AJNUQR', NULL),
(31, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:33:49', '2015-03-09 00:34:20', NULL, 0, 'T4AJNUQR', NULL),
(32, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:33:50', '2015-03-09 00:34:51', NULL, 0, 'T4AJNUQR', NULL),
(33, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:51:27', '2015-03-09 00:51:27', NULL, 0, '1VH0YREK', NULL),
(34, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:51:46', '2015-03-09 00:51:46', NULL, 0, 'H8DPDOVJ', NULL),
(35, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:51:46', '2015-03-09 00:52:17', NULL, 0, 'H8DPDOVJ', NULL),
(36, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:52:34', '2015-03-09 00:52:34', NULL, 0, 'T116J8FF', NULL),
(37, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:51:47', '2015-03-09 00:52:48', NULL, 0, 'H8DPDOVJ', NULL),
(38, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:52:34', '2015-03-09 00:53:05', NULL, 0, 'T116J8FF', NULL),
(39, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:52:34', '2015-03-09 00:53:35', NULL, 0, 'T116J8FF', NULL),
(40, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:53:41', '2015-03-09 00:53:41', NULL, 0, 'EOJDZ4RH', NULL),
(41, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:53:42', '2015-03-09 00:54:13', NULL, 0, 'EOJDZ4RH', NULL),
(42, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:53:42', '2015-03-09 00:54:43', NULL, 0, 'EOJDZ4RH', NULL),
(43, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:54:47', '2015-03-09 00:54:47', NULL, 0, 'ERVP7BAI', NULL),
(44, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:54:48', '2015-03-09 00:55:19', NULL, 0, 'ERVP7BAI', NULL),
(45, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:54:48', '2015-03-09 00:55:49', NULL, 0, 'ERVP7BAI', NULL),
(46, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 00:57:27', '2015-03-09 00:57:27', NULL, 0, '50304NWZ', NULL),
(47, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 00:57:27', '2015-03-09 00:57:58', NULL, 0, '50304NWZ', NULL),
(48, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 00:57:27', '2015-03-09 00:58:28', NULL, 0, '50304NWZ', NULL),
(49, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 01:07:28', '2015-03-09 01:07:28', NULL, 0, 'UXUNMBT5', NULL),
(50, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 01:07:28', '2015-03-09 01:07:59', NULL, 0, 'UXUNMBT5', NULL),
(51, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 01:07:28', '2015-03-09 01:08:29', NULL, 1, 'UXUNMBT5', NULL),
(52, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 01:12:34', '2015-03-09 01:12:34', NULL, 0, 'JZSWYJSA', NULL),
(53, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 01:12:35', '2015-03-09 01:13:06', NULL, 0, 'JZSWYJSA', NULL),
(54, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 01:12:35', '2015-03-09 01:13:36', NULL, 0, 'JZSWYJSA', NULL),
(55, 1, 2, 2, 10.289056, 123.862350, '2015-03-09 01:17:08', '2015-03-09 01:17:08', NULL, 0, 'WPPNEF06', NULL),
(56, 1, 3, 2, 10.289056, 123.862350, '2015-03-09 01:17:09', '2015-03-09 01:17:40', '2015-03-09 01:17:40', 0, 'WPPNEF06', NULL),
(57, 1, 1, 2, 10.289056, 123.862350, '2015-03-09 01:17:09', '2015-03-09 01:18:10', NULL, 0, 'WPPNEF06', NULL);

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
  `report_image` text,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=28 ;

--
-- Dumping data for table `rescue_units`
--

INSERT INTO `rescue_units` (`id`, `name`, `address`, `lat`, `lng`, `email`, `type`, `status`) VALUES
(18, 'Dr. Arco Medical Clinic', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.296817, 123.885223, 'DrArcoMedicalClinic@gmail.com', 'hospital', 1),
(19, 'Miller Hospital', 'Tres de Abril Street Extension, Cebu City, Cebu, Philippines', 10.295998, 123.887756, 'MillerHospital@gmail.com', 'hospital', 1),
(20, 'Pari-an Fire Sub Station', 'Sikatuna Street, Cebu City, Cebu, Philippines', 10.299223, 123.903206, 'pari-anfiresubstation@gmail.com', 'firecontrol', 1),
(21, 'Cebu Filipino-Chinese Volunteer Fire Brigade', '5th Street, Cebu City, Cebu, Philippines', 10.310120, 123.888603, 'cebufilipinochinese@gmail.com', 'firecontrol', 1),
(22, 'Bureau of Fire Protection - Cebu City Fire Station', 'B.Aranas Extension, Cebu City, Cebu, Philippines', 10.295217, 123.883553, 'bureauoffireprotection@gmail.com', 'firecontrol', 1),
(23, 'Bureau of Fire Protection', 'Panganiban Street, Cebu City, Cebu, Philippines', 10.297729, 123.891815, 'bureauoffireprotection@gmail.com', 'firecontrol', 1),
(13, 'Chong Hua Hospital', 'Rosal Street, Cebu City, Cebu, Philippines', 10.309889, 123.891159, '', 'hospital', 1),
(14, 'Vicente Sotto Memorial Medical Center OPD', 'B. Rodriguez Street, Cebu City, Cebu, Philippines', 10.307978, 123.891602, '', 'hospital', 1),
(15, 'Chong Hua Medical Arts Center', 'Don Julio Llorente Street, Cebu City, Cebu, Philippines', 10.309755, 123.889442, '', 'hospital', 1),
(16, 'Visayas Community Medical Center', 'Osmeña Boulevard, Cebu City, 6000 Cebu, Philippines', 10.306476, 123.895020, 'visayascommunity@gmail.com', 'hospital', 1),
(17, 'Miller Hospital', 'Tres de Abril Street, Cebu City, Cebu, Philippines', 10.297413, 123.876907, 'MillerHospital@gmail.com', 'hospital', 1),
(24, 'Pardo Police Station', 'I Tabura Streeet, Cebu City, Cebu, Philippines', 10.280186, 123.856255, 'pardopolicestation@gmail.com', 'police', 1),
(25, 'Tisa Police Station', 'lot 27 Francisco Llamas Street, Cebu City, 6000 Cebu, Philippines', 10.300127, 123.869820, 'tisapolicestation@gmail.com', 'police', 1),
(26, 'Quiot Police Station', 'Greenbelt Drive, Cebu City, Cebu, Philippines', 10.292196, 123.859840, 'quiotpolicestation@gmail.com', 'police', 1),
(27, 'Mambaling Police Station', 'Natalio B. Bacalso Avenue, Cebu City, Cebu, Philippines', 10.292266, 123.878792, 'mambalingpolicestation@gmail.com', 'police', 1);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=74 ;

--
-- Dumping data for table `ru_ec`
--

INSERT INTO `ru_ec` (`id`, `ru_id`, `ec_id`) VALUES
(31, 14, 3),
(30, 14, 2),
(37, 15, 6),
(36, 15, 5),
(49, 18, 6),
(48, 18, 5),
(47, 18, 3),
(46, 18, 2),
(39, 16, 3),
(38, 16, 2),
(43, 17, 3),
(42, 17, 2),
(45, 17, 6),
(44, 17, 5),
(27, 13, 3),
(26, 13, 2),
(33, 14, 6),
(32, 14, 5),
(35, 15, 3),
(34, 15, 2),
(41, 16, 6),
(40, 16, 5),
(29, 13, 6),
(28, 13, 5),
(50, 19, 2),
(51, 19, 3),
(52, 19, 5),
(53, 19, 6),
(68, 25, 4),
(67, 24, 6),
(60, 22, 1),
(59, 20, 1),
(58, 21, 1),
(66, 24, 4),
(65, 23, 1),
(69, 25, 6),
(70, 26, 4),
(71, 26, 6),
(72, 27, 4),
(73, 27, 6);

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `puid_ruid`, `type`, `username`, `password`, `created_at`, `updated_at`, `remember_token`) VALUES
(1, NULL, 'admin', 'admin123', '$2y$10$R7YvKWuWp5FC273VoVxYEuVDlPpDSxutlMkUCYdDNbGm7Gq7Su24e', '2015-02-26 07:28:28', '2015-02-26 07:28:28', NULL),
(19, 17, 'ru', 'millerhospital', '$2y$10$L/girFtGXdas3cceSdPDK.YB.xQHyCUvz4FV7iAWe3h36n7kOwQnW', '2015-03-10 00:07:46', '2015-03-10 00:07:46', NULL),
(20, 18, 'ru', 'drarcomedicalclinic', '$2y$10$qbhGWf8upj5jFY1P2aXehe/vismL4eHaYQdj.x1OzOJ8qQAIyBjW2', '2015-03-10 00:08:49', '2015-03-10 00:08:49', NULL),
(21, 19, 'ru', 'millerhospital2', '$2y$10$HO3XSBmWhxBLwm7c542huOwNFbXEeOD5a80.ERuVzcnqn3ubw1gya', '2015-03-10 00:09:30', '2015-03-10 00:09:30', NULL),
(22, 20, 'ru', 'pari-anfiresubstatio', '$2y$10$taci0uaY50p5ng/wEXtnDeOssUIAu6Zq8Nw4YGOz6MXBR.0k3NCva', '2015-03-10 00:11:44', '2015-03-10 00:11:44', NULL),
(23, 21, 'ru', 'cebufilipinochinese', '$2y$10$vg8jqu7y1uCzxb/WiBYro.MTgoEP.K44gGtTM5GBpDKzZAJndex/u', '2015-03-10 00:12:47', '2015-03-10 00:12:47', NULL),
(24, 22, 'ru', 'bureauoffireprotecti', '$2y$10$PCHkwoFr9tOoSAWFJZAd7Or.rjTnj3BbxRqFEVD3d2jwSXxB2sUWG', '2015-03-10 00:13:56', '2015-03-10 00:13:56', NULL),
(15, 13, 'ru', 'chonghuahospital', '$2y$10$tkDgqhZBf/WF9oRz2AAnDuDz3biIWPlyxS04KnRreTmFFHCrbBpai', '2015-03-10 00:04:09', '2015-03-10 00:04:09', NULL),
(16, 14, 'ru', 'vincentesotto', '$2y$10$XJ4.VrUlnWV7GMFHl22n3.2XJx63c7MY0wBDUiyT0jq7keOBfJGoW', '2015-03-10 00:04:44', '2015-03-10 00:04:44', NULL),
(17, 15, 'ru', 'chonghuamedical', '$2y$10$YnVPVJIxSqbi2/svast1YuCSoUPyvQYXpHrrJScxPsJ9dJOzSs0r2', '2015-03-10 00:05:08', '2015-03-10 00:05:08', NULL),
(18, 16, 'ru', 'visayascommunity', '$2y$10$StMgn0WmuyRiEQHmcq5Dj.edaxMJaC7ENpsJWBZbTciRv7cWLsukS', '2015-03-10 00:05:50', '2015-03-10 00:05:50', NULL),
(14, 1, 'pu', 'kevinrey', '$2y$10$.bsZVUPzODbPX/0LNLLD8etnJrWsqnpQwXRx9cvoIh2qd0yY64SC2', '2015-03-08 17:19:08', '2015-03-08 17:19:08', NULL),
(25, 23, 'ru', 'bureauoffireprotecti', '$2y$10$KOVPgn2w.U4gad8hi0Wd5O1CpOMDZSMJkaythUcfo69HwH4sddo4e', '2015-03-10 00:14:39', '2015-03-10 00:14:39', NULL),
(26, 24, 'ru', 'pardopolicestation', '$2y$10$FoausjmxwD6V0SBmJ3qibeMXOIz5yLMpMttjhJpRI23SWQnbiRS7K', '2015-03-10 00:16:19', '2015-03-10 00:16:19', NULL),
(27, 25, 'ru', 'tisapolicestation', '$2y$10$/1OV/I380NBAjPaO8fWzzOYqUVGdRfvXiBF.wKrfIm9uF8VRH.J4S', '2015-03-10 00:17:01', '2015-03-10 00:17:01', NULL),
(28, 26, 'ru', 'quiotpolicestation', '$2y$10$Rl1fF.Y17KxLttcdClNVr.txa9GsJXTNiMVAHJMpAzhdNWrNHg1T2', '2015-03-10 00:17:29', '2015-03-10 00:17:29', NULL),
(29, 27, 'ru', 'mambalingpolicestati', '$2y$10$nGL/Ee/L0Xmo922KCjndH.4JMAYrGmCcK6nrVJPNnKpWQFgYp1mTu', '2015-03-10 00:18:02', '2015-03-10 00:18:02', NULL);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
