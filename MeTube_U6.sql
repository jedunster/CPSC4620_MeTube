-- phpMyAdmin SQL Dump
-- version 4.0.10deb1
-- http://www.phpmyadmin.net
--
-- Host: mysql1.cs.clemson.edu
-- Generation Time: Apr 18, 2017 at 11:25 AM
-- Server version: 5.5.52-0ubuntu0.12.04.1
-- PHP Version: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `MeTube_U6`
--

-- --------------------------------------------------------

--
-- Table structure for table `account`
--

DROP TABLE IF EXISTS `account`;
CREATE TABLE IF NOT EXISTS `account` (
  `username` varchar(30) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `summary` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `comment`
--

DROP TABLE IF EXISTS `comment`;
CREATE TABLE IF NOT EXISTS `comment` (
  `comment_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `mediaid` int(11) NOT NULL,
  `comment_date` datetime DEFAULT NULL,
  `message` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `comment_ibfk_1` (`username`),
  KEY `comment_ibfk_2` (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=62 ;

-- --------------------------------------------------------

--
-- Table structure for table `download`
--

DROP TABLE IF EXISTS `download`;
CREATE TABLE IF NOT EXISTS `download` (
  `download_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) DEFAULT NULL,
  `mediaid` int(11) NOT NULL,
  `download_date` datetime DEFAULT NULL,
  `download_ip` varchar(64) NOT NULL,
  PRIMARY KEY (`download_id`),
  KEY `download_ibfk_1` (`username`),
  KEY `download_ibfk_2` (`mediaid`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=56 ;

-- --------------------------------------------------------

--
-- Table structure for table `favorited_media`
--

DROP TABLE IF EXISTS `favorited_media`;
CREATE TABLE IF NOT EXISTS `favorited_media` (
  `username` varchar(30) NOT NULL DEFAULT '',
  `mediaid` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`username`,`mediaid`),
  KEY `favorited_media_ibfk_2` (`mediaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
CREATE TABLE IF NOT EXISTS `media` (
  `title` varchar(40) DEFAULT NULL,
  `username` varchar(30) NOT NULL,
  `type` varchar(30) DEFAULT NULL,
  `mediaid` int(11) NOT NULL AUTO_INCREMENT,
  `path` varchar(100) DEFAULT NULL,
  `size` int(11) DEFAULT NULL,
  `upload_date` datetime DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  `category` varchar(30) DEFAULT NULL,
  `allow_comments` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`mediaid`),
  KEY `media_ibfk_1` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=82 ;

-- --------------------------------------------------------

--
-- Table structure for table `media_keyword`
--

DROP TABLE IF EXISTS `media_keyword`;
CREATE TABLE IF NOT EXISTS `media_keyword` (
  `mediaid` int(11) NOT NULL,
  `keyword` varchar(30) NOT NULL,
  PRIMARY KEY (`mediaid`,`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

DROP TABLE IF EXISTS `message`;
CREATE TABLE IF NOT EXISTS `message` (
  `message_id` int(11) NOT NULL AUTO_INCREMENT,
  `sender_username` varchar(30) NOT NULL,
  `send_date` datetime DEFAULT NULL,
  `message_contents` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`message_id`),
  KEY `message_ibfk_1` (`sender_username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=1282 ;

-- --------------------------------------------------------

--
-- Table structure for table `message_recipient`
--

DROP TABLE IF EXISTS `message_recipient`;
CREATE TABLE IF NOT EXISTS `message_recipient` (
  `message_id` int(11) NOT NULL,
  `recipient_username` varchar(30) NOT NULL,
  PRIMARY KEY (`message_id`,`recipient_username`),
  KEY `message_recipient_ibfk_2` (`recipient_username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `playlist`
--

DROP TABLE IF EXISTS `playlist`;
CREATE TABLE IF NOT EXISTS `playlist` (
  `playlist_id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(30) NOT NULL,
  `creation_date` datetime DEFAULT NULL,
  `name` varchar(40) DEFAULT NULL,
  `description` varchar(1000) DEFAULT NULL,
  PRIMARY KEY (`playlist_id`),
  KEY `playlist_ibfk_1` (`username`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=25 ;

-- --------------------------------------------------------

--
-- Table structure for table `playlist_keyword`
--

DROP TABLE IF EXISTS `playlist_keyword`;
CREATE TABLE IF NOT EXISTS `playlist_keyword` (
  `playlist_id` int(11) NOT NULL,
  `keyword` varchar(30) NOT NULL DEFAULT '',
  PRIMARY KEY (`playlist_id`,`keyword`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `playlist_media`
--

DROP TABLE IF EXISTS `playlist_media`;
CREATE TABLE IF NOT EXISTS `playlist_media` (
  `playlist_id` int(11) NOT NULL,
  `mediaid` int(11) NOT NULL,
  PRIMARY KEY (`playlist_id`,`mediaid`),
  KEY `playlist_media_ibfk_2` (`mediaid`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `subscription`
--

DROP TABLE IF EXISTS `subscription`;
CREATE TABLE IF NOT EXISTS `subscription` (
  `subscriber_username` varchar(30) NOT NULL,
  `subscribee_username` varchar(30) NOT NULL,
  PRIMARY KEY (`subscriber_username`,`subscribee_username`),
  KEY `subscription_ibfk_2` (`subscribee_username`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `comment`
--
ALTER TABLE `comment`
  ADD CONSTRAINT `comment_ibfk_2` FOREIGN KEY (`mediaid`) REFERENCES `media` (`mediaid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `comment_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `download`
--
ALTER TABLE `download`
  ADD CONSTRAINT `download_ibfk_2` FOREIGN KEY (`mediaid`) REFERENCES `media` (`mediaid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `download_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `favorited_media`
--
ALTER TABLE `favorited_media`
  ADD CONSTRAINT `favorited_media_ibfk_2` FOREIGN KEY (`mediaid`) REFERENCES `media` (`mediaid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `favorited_media_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `media_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `media_keyword`
--
ALTER TABLE `media_keyword`
  ADD CONSTRAINT `media_keyword_ibfk_1` FOREIGN KEY (`mediaid`) REFERENCES `media` (`mediaid`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message`
--
ALTER TABLE `message`
  ADD CONSTRAINT `message_ibfk_1` FOREIGN KEY (`sender_username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `message_recipient`
--
ALTER TABLE `message_recipient`
  ADD CONSTRAINT `message_recipient_ibfk_2` FOREIGN KEY (`recipient_username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `message_recipient_ibfk_1` FOREIGN KEY (`message_id`) REFERENCES `message` (`message_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist`
--
ALTER TABLE `playlist`
  ADD CONSTRAINT `playlist_ibfk_1` FOREIGN KEY (`username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist_keyword`
--
ALTER TABLE `playlist_keyword`
  ADD CONSTRAINT `playlist_keyword_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`playlist_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `playlist_media`
--
ALTER TABLE `playlist_media`
  ADD CONSTRAINT `playlist_media_ibfk_2` FOREIGN KEY (`mediaid`) REFERENCES `media` (`mediaid`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `playlist_media_ibfk_1` FOREIGN KEY (`playlist_id`) REFERENCES `playlist` (`playlist_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `subscription`
--
ALTER TABLE `subscription`
  ADD CONSTRAINT `subscription_ibfk_2` FOREIGN KEY (`subscribee_username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `subscription_ibfk_1` FOREIGN KEY (`subscriber_username`) REFERENCES `account` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
