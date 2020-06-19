-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Giu 16, 2020 alle 17:23
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
  `fingerprint_ip` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fingerprint_lang` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_active` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=19 ;

--
-- Dump dei dati per la tabella `config`
--

REPLACE INTO `config` (`id`, `type`, `name`, `default`, `value`, `description`, `editable`, `global`) VALUES
(1, 'Int', 'session_timeout', '360', NULL, NULL, 1, 1),
(2, 'Int', 'login_max_attempts', '10', NULL, NULL, 1, 1),
(3, 'Int', 'cookie_expire', '100000', NULL, NULL, 1, 1),
(4, 'String', 'cookie_path', '/', ' ', NULL, 1, 1),
(5, 'String', 'cookie_domain', ' ', NULL, NULL, 1, 1),
(6, 'Bool', 'cookie_secure', '0', NULL, NULL, 1, 1),
(7, 'Bool', 'cookie_httponly', '1', NULL, NULL, 1, 1),
(8, 'Int', 'password_min', '5', NULL, NULL, 1, 1),
(9, 'Int', 'password_max', '16', NULL, NULL, 1, 1),
(10, 'Int', 'email_min', '8', NULL, NULL, 1, 1),
(11, 'Int', 'email_max', '30', NULL, NULL, 1, 1),
(12, 'Int', 'cookie_lifetime', '0', NULL, NULL, 1, 1),
(13, 'Array', 'request_allowed_methods', 'GET,POST', NULL, NULL, 1, 1),
(18, 'String', 'domain_email', 'example@gmail.com', 'gdr_reborn@gmail.com', NULL, 1, 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `login_invalid`
--

CREATE TABLE IF NOT EXISTS `login_invalid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timerror` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=1 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `layout_options`
--

CREATE TABLE IF NOT EXISTS `layout_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dump dei dati per la tabella `layout_options`
--

REPLACE INTO `layout_options` (`id`, `name`, `value`) VALUES
(1, 'color_default', '#323232');


/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
