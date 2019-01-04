-- phpMyAdmin SQL Dump
-- version 4.7.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 26, 2018 at 05:38 PM
-- Server version: 10.1.30-MariaDB
-- PHP Version: 7.2.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
/*SET time_zone = "+00:00";*/


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tkid`
--

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(10) UNSIGNED NOT NULL,
  `url_key` varchar(100) CHARACTER SET utf8 DEFAULT NULL,
  `title` varchar(250) CHARACTER SET utf8 NOT NULL,
  `description` text CHARACTER SET utf8,
  `short_description` text CHARACTER SET utf8,
  `file` varchar(200) CHARACTER SET utf8 DEFAULT NULL,
  `status` tinyint(3) NOT NULL COMMENT '0 UNPUBLISHED 1 PUBLISHED',
  `event_type_id` int(10) UNSIGNED NOT NULL,
  `event_external_id` varchar (20) CHARACTER SET utf8 DEFAULT NULL,
  `event_css_class_id` tinyint(3) UNSIGNED DEFAULT NULL,
  `event_start_unix_milliseconds` int(10) UNSIGNED NOT NULL,
  `event_end_unix_milliseconds` int(10) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `event_css_classes`
--

CREATE TABLE `event_css_classes` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_css_classes`
--

INSERT INTO `event_css_classes` (`id`, `name`) VALUES
(1, 'event-important'),
(2, 'event-success'),
(3, 'event-warning'),
(4, 'event-info'),
(5, 'event-special'),
(6, 'event-inverse');

-- --------------------------------------------------------

--
-- Table structure for table `event_types`
--

CREATE TABLE `event_types` (
  `id` int(10) NOT NULL,
  `name` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `event_types`
--

INSERT INTO `event_types` (`id`, `name`) VALUES
(1, 'Local'),
(2, 'Facebook'),
(3, 'Google');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_css_classes`
--
ALTER TABLE `event_css_classes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_types`
--
ALTER TABLE `event_types`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `event_css_classes`
--
ALTER TABLE `event_css_classes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `event_types`
--
ALTER TABLE `event_types`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
