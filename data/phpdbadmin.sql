-- phpMyAdmin SQL Dump
-- version 4.2.10.1
-- http://www.phpmyadmin.net
--
-- Host: db.emonlade.net
-- Generation Time: Dec 18, 2014 at 02:23 AM
-- Server version: 5.5.32-log
-- PHP Version: 5.4.17

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `phpdbadmin`
--
CREATE DATABASE IF NOT EXISTS `phpdbadmin` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `phpdbadmin`;

-- --------------------------------------------------------

--
-- Table structure for table `data_sync_sets`
--

CREATE TABLE IF NOT EXISTS `data_sync_sets` (
`id` int(11) NOT NULL,
  `set_name` varchar(100) NOT NULL,
  `datasource_1` varchar(50) NOT NULL,
  `datasource_2` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `data_sync_table_cols`
--

CREATE TABLE IF NOT EXISTS `data_sync_table_cols` (
`id` int(11) NOT NULL,
  `data_sync_set_id` int(11) NOT NULL,
  `ds_table` varchar(50) NOT NULL,
  `ds_table_col` varchar(50) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=102 DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE IF NOT EXISTS `users` (
`id` int(11) NOT NULL,
  `userid` varchar(30) NOT NULL,
  `password` varchar(50) NOT NULL,
  `first_name` varchar(30) NOT NULL,
  `last_name` varchar(30) NOT NULL,
  `disabled` tinyint(4) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `data_sync_sets`
--
ALTER TABLE `data_sync_sets`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `data_sync_table_cols`
--
ALTER TABLE `data_sync_table_cols`
 ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
 ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `data_sync_sets`
--
ALTER TABLE `data_sync_sets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `data_sync_table_cols`
--
ALTER TABLE `data_sync_table_cols`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=102;
--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
