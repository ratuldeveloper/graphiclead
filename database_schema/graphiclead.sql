-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 05, 2022 at 06:46 PM
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
  `uuid` varchar(500) NOT NULL,
  `user_id` int(11) NOT NULL,
  `content_type` varchar(256) NOT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `submitted` datetime DEFAULT NULL,
  `processed` datetime DEFAULT NULL,
  `ttl` datetime DEFAULT NULL,
  `status` varchar(256) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `imagesqr`
--

INSERT INTO `imagesqr` (`id`, `uuid`, `user_id`, `content_type`, `image_url`, `submitted`, `processed`, `ttl`, `status`) VALUES
(1, '2124243b469d411a7adf1e9f849f672a-333a3461fac7a8e17bb9c47bfc-61b06c9e6ef082-409b', 9, 'image/jpeg', NULL, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(2, 'de175c24ec165e63822eddf444e6237299-cb95f33d90ede1e65eea2fe2-c3b250bbb0cd', 9, 'image/jpeg', 'img\\qrimage\\de175c24ec165e63822eddf444e6237299-cb95f33d90ede1e65eea2fe2-c3b250bbb0cd.jpeg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(3, '27962c2880ae25b8a184465aefe1808f-7e8b9036e5825690b9e86d3e65-75e9e03e71b1bd4aa9c3-3ce3891887ff', 9, 'image/jpeg', '27962c2880ae25b8a184465aefe1808f-7e8b9036e5825690b9e86d3e65-75e9e03e71b1bd4aa9c3-3ce3891887ff.jpeg', '0000-00-00 00:00:00', '0000-00-00 00:00:00', '0000-00-00 00:00:00', ''),
(4, '2f6bea501290eddff89072a4e66f39-f065edde8c07019139842d-78a1bd36136f0f79', 9, 'image/jpeg', '2f6bea501290eddff89072a4e66f39-f065edde8c07019139842d-78a1bd36136f0f79.jpeg', '2022-04-05 16:27:10', NULL, NULL, 'SUBMITTED'),
(5, '78bccca972c50c03f80b908c1454c527-a4f13e2468a61c69c3aa08969b-16db4df242c90a-26', 9, 'image/jpeg', '78bccca972c50c03f80b908c1454c527-a4f13e2468a61c69c3aa08969b-16db4df242c90a-26.jpeg', '2022-04-05 16:32:08', NULL, NULL, 'SUBMITTED'),
(6, 'ed38ee0406023d4817cbd1fb6e6b6de44f-5b5fc0b6b82210e6d0b093921609-0d24ef32ea97234e63e7-b10dfd403edfa8', 9, 'image/jpeg', 'ed38ee0406023d4817cbd1fb6e6b6de44f-5b5fc0b6b82210e6d0b093921609-0d24ef32ea97234e63e7-b10dfd403edfa8.jpeg', '2022-04-05 16:35:27', NULL, NULL, 'SUBMITTED'),
(7, '957938e3af693cd93a2fd4b85f955a52ab-39600b8c25cac8f17cc24886c5-215859f92c39bc86158d-d737dfa583', 9, 'image/jpeg', '957938e3af693cd93a2fd4b85f955a52ab-39600b8c25cac8f17cc24886c5-215859f92c39bc86158d-d737dfa583.jpeg', '2022-04-05 16:36:38', NULL, NULL, 'SUBMITTED'),
(8, 'e05f5f65e00c6d597b3b324c24c71522-a9b87e7988fda36d866d60-fcc1fe0a4cd8-51058c', 9, 'image/jpeg', 'e05f5f65e00c6d597b3b324c24c71522-a9b87e7988fda36d866d60-fcc1fe0a4cd8-51058c.jpeg', '2022-04-05 16:37:44', NULL, NULL, 'SUBMITTED');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
