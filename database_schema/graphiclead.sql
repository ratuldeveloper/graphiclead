-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 02, 2022 at 06:22 PM
-- Server version: 10.4.20-MariaDB
-- PHP Version: 7.4.22

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `graphiclead`
--

-- --------------------------------------------------------

--
-- Table structure for table `imagesqr`
--

CREATE TABLE `imagesqr` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` varchar(256) NOT NULL,
  `submitted` datetime NOT NULL,
  `processed` datetime NOT NULL,
  `ttl` datetime NOT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `user_name` varchar(256) NOT NULL,
  `password` varchar(500) NOT NULL,
  `email` varchar(256) NOT NULL,
  `first_name` varchar(256) NOT NULL,
  `last_name` varchar(256) NOT NULL,
  `company` varchar(256) NOT NULL,
  `active` tinyint(1) NOT NULL,
  `token` varchar(500) NOT NULL,
  `last_login` datetime NOT NULL,
  `code_count` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `user_name`, `password`, `email`, `first_name`, `last_name`, `company`, `active`, `token`, `last_login`, `code_count`, `created`, `modified`) VALUES
(7, '', '$2y$10$acearlIBpZHp.ZAW4EK8OeJGTPt8hEEvpUQ1erYfSw/scQWdslLei', 'ratulece@gmail.com', 'Ratul', 'Samanta', '', 0, '', '0000-00-00 00:00:00', 0, '2022-04-01 10:19:39', '2022-04-01 10:19:39'),
(9, 'ratul', '$2y$10$OSECd8jQjDLOyQvo2ghFFeKmvgkCyZAsoAoMQWQPebJhsCOvHdTyG', 'ratul.etce@gmail.com', 'Ratul', 'Samanta', 'Freelancer', 0, '', '0000-00-00 00:00:00', 4, '2022-04-02 16:19:40', '2022-04-02 16:19:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `imagesqr`
--
ALTER TABLE `imagesqr`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `imagesqr`
--
ALTER TABLE `imagesqr`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
