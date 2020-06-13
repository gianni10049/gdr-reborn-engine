-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Giu 13, 2020 alle 04:07
-- Versione del server: 5.6.33-log
-- PHP Version: 5.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_giovannipaneselling2`
--

-- --------------------------------------------------------

--
-- Struttura della tabella `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `fingerprint` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_active` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=9 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `config`
--

CREATE TABLE IF NOT EXISTS `config` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` varchar(8) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `default` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `editable` tinyint(1) NOT NULL DEFAULT '1',
  `global` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=18 ;

--
-- Dump dei dati per la tabella `config`
--

INSERT INTO `config` (`id`, `type`, `name`, `default`, `value`, `description`, `editable`, `global`) VALUES
(1, 'Int', 'session_timeout', '360', NULL, NULL, 1, 1),
(2, 'Int', 'login_max_attempts', '10', NULL, NULL, 1, 1),
(3, 'Int', 'cookie_expire', '100000', NULL, NULL, 1, 1),
(4, 'String', 'cookie_path', '/', ' ', NULL, 1, 1),
(5, 'String', 'cookie_domain', ' ', NULL, NULL, 1, 1),
(6, 'Bool', 'cookie_secure', '0', NULL, NULL, 1, 1),
(7, 'Bool', 'cookie_httponly', '1', NULL, NULL, 1, 1),
(8, 'Int', 'password_min', '8', NULL, NULL, 1, 1),
(9, 'Int', 'password_max', '16', NULL, NULL, 1, 1),
(10, 'Int', 'email_min', '8', NULL, NULL, 1, 1),
(11, 'Int', 'email_max', '16', NULL, NULL, 1, 1),
(12, 'Int', 'cookie_lifetime', '0', NULL, NULL, 1, 1),
(13, 'Array', 'request_allowed_methods', 'GET,POST', NULL, NULL, 1, 1),
(14, 'String', 'cryptography_key', 'V6/qcAev/bcu0nkrsgA9M/TpR/+Pxz1CdNK/U/8f77s=', NULL, NULL, 1, 1),
(15, 'String', 'cryptography_method', 'aes-128-gcm', NULL, NULL, 1, 1);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
