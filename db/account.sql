-- phpMyAdmin SQL Dump
-- version 3.4.10.1deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: Apr 19, 2015 at 10:39 PM
-- Server version: 5.5.40
-- PHP Version: 5.5.21-1+deb.sury.org~precise+2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `account`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

CREATE TABLE IF NOT EXISTS `account` (
  `account_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(20) NOT NULL,
  `password_hash` varchar(120) NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `salt` varchar(10) DEFAULT NULL,
  `failed_attempts` int(2) unsigned DEFAULT NULL,
  `last_failed_time` time DEFAULT NULL,
  `last_active_login` timestamp NULL DEFAULT NULL,
  `first_name` varchar(30) DEFAULT NULL,
  `last_name` varchar(30) DEFAULT NULL,
  `sex` varchar(20) DEFAULT NULL,
  `BMI` int(3) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `ethnicity` varchar(50) DEFAULT NULL,
  `weight` int(4) DEFAULT NULL,
  `height` int(4) DEFAULT NULL,
  PRIMARY KEY (`account_id`),
  UNIQUE KEY `account_id` (`account_id`),
  UNIQUE KEY `username_2` (`username`),
  KEY `account_ibfk_1` (`group_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=48 ;

--
-- Dumping data for table `account`
--

INSERT INTO `account` (`account_id`, `username`, `password_hash`, `group_id`, `salt`, `failed_attempts`, `last_failed_time`, `last_active_login`, `first_name`, `last_name`, `sex`, `BMI`, `email`, `description`, `ethnicity`, `weight`, `height`) VALUES
(1, 'vtang', '$2y$12$VBnX4yL7Uvw9wP0A2cO6ruo.4/u0OUfvjEw4TUZ6BOrVqKA7FEJMq', 1, NULL, NULL, NULL, '2015-04-12 01:21:28', 'Vic', 'Tang', 'Male', 19, 'vtang@gmail.com', '4th year computer science student at McMaster university', 'Chinese', 118, 168),
(2, 'test', '$2y$12$a7QVMMTh7Xh0mFlh1sbIOu0ouzzvyzvo2fVGoLcnX5T0wyDB3hgBS', 1, NULL, NULL, NULL, '2015-04-09 15:46:25', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL),
(27, 'admin', '$2y$12$RMl4Kl7AhuKGRPlymeqKp.lTvyYBgk/P0f2wjrtfK1H5lJo1RJyRy', 2, NULL, NULL, NULL, '2015-04-17 20:48:59', NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `group`
--

CREATE TABLE IF NOT EXISTS `group` (
  `group_id` int(11) unsigned NOT NULL,
  `description` varchar(255) NOT NULL,
  `group_name` varchar(60) NOT NULL,
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `group`
--

INSERT INTO `group` (`group_id`, `description`, `group_name`) VALUES
(1, 'access to own data,\r\naccess to visualization', 'user'),
(2, 'access to everyone''s data,\r\naccess to visualizations,\r\ncreate account,\r\ndelete account', 'admin');

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account`
--
ALTER TABLE `account`
  ADD CONSTRAINT `account_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `group` (`group_id`) ON DELETE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
