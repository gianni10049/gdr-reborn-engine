-- phpMyAdmin SQL Dump
-- version 4.1.7
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Ago 09, 2020 alle 23:50
-- Versione del server: 5.6.33-log
-- PHP Version: 5.3.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `my_reborngdr`
--
CREATE DATABASE IF NOT EXISTS `my_reborngdr` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `my_reborngdr`;

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
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=17 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `characters`
--

CREATE TABLE IF NOT EXISTS `characters` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `account` int(11) NOT NULL,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `select_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `miniavatar_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_login` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `favorite` tinyint(1) NOT NULL DEFAULT '0',
  `selected` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=13 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `characters_stats`
--

CREATE TABLE IF NOT EXISTS `characters_stats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `character` int(11) NOT NULL,
  `stat` int(11) NOT NULL,
  `value` int(3) NOT NULL,
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
-- Struttura della tabella `layout_options`
--

CREATE TABLE IF NOT EXISTS `layout_options` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=2 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `login_invalid`
--

CREATE TABLE IF NOT EXISTS `login_invalid` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `username` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `timerror` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=47 ;

-- --------------------------------------------------------

--
-- Struttura della tabella `menu`
--

CREATE TABLE IF NOT EXISTS `menu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `box` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `clickable` tinyint(1) NOT NULL DEFAULT '1',
  `text` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `link_container` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `father_id` int(11) NOT NULL DEFAULT '0',
  `link` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=10 ;

--
-- Dump dei dati per la tabella `menu`
--

REPLACE INTO `menu` (`id`, `box`, `clickable`, `text`, `icon`, `link_container`, `father_id`, `link`, `active`) VALUES
(1, 'left', 1, 'Account', 'fas fa-user-alt', 'central', 0, NULL, 1),
(2, 'left', 1, 'Scheda PG', 'fa fa-id-card', 'central', 1, '/Card-Main', 1),
(3, 'left', 1, 'Cambia PG', 'fas fa-exchange-alt', 'central', 1, '/ChangeCharacter', 1),
(4, 'left', 1, 'Logout', 'fas fa-sign-out-alt', 'central', 1, '/Logout\r\n', 1),
(5, 'card-menu', 1, 'Scheda', NULL, 'card-complete', 0, '/Card-Main', 1),
(6, 'card-menu', 1, 'Background', NULL, 'card-internal', 0, '/Card-Background', 1),
(7, 'card-menu', 1, 'Inventario', NULL, 'card-internal', 0, '/Card-Inventory', 1),
(8, 'card-menu', 1, 'Abilità', NULL, 'card-internal', 0, '/Card-Ability\r\n', 1),
(9, 'card-menu', 1, 'Modifica', NULL, 'card-internal', 0, '/Card-Edit', 1);

-- --------------------------------------------------------

--
-- Struttura della tabella `routes`
--

CREATE TABLE IF NOT EXISTS `routes` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modulo` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `alias` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=21 ;

--
-- Dump dei dati per la tabella `routes`
--

REPLACE INTO `routes` (`id`, `modulo`, `file`, `alias`, `active`) VALUES
(19, 'Lobby', 'Lobby', '/Lobby', 1),
(2, 'Scheda', 'Card/Card-Container', '/Card-Main', 1),
(3, 'Scheda', 'Card/Pages/Card-Background', '/Card-Background', 1),
(4, 'Logout', 'Logout', '/Logout', 1),
(5, 'Homepage', 'Homepage/Visual', '/Homepage', 1),
(7, 'Homepage', 'Homepage/Signin', '/Signin', 1),
(8, 'Homepage', 'Homepage/PasswordRecovery', '/PasswordRecovery', 1),
(9, 'Homepage', 'Homepage/UsernameRecovery', '/UsernameRecovery', 1),
(20, 'Homepage', 'Homepage/Operations\r\n', '/HomepageOperations', 1),
(14, 'ChangeCharacter', 'ChangeCharacter/Visual', '/ChangeCharacter', 1),
(16, 'ChangeCharacter', 'ChangeCharacter/Operations', '/ChangeCharacterOperation', 1);

-- --------------------------------------------------------
--
-- Struttura della tabella `stats_list`
--

CREATE TABLE IF NOT EXISTS `stats_list` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `init_min` int(11) NOT NULL,
  `init_max` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=5 ;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
