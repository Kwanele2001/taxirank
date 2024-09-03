-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 30, 2024 at 12:42 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `taxi_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `taxi_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `application_date` date DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`id`, `user_id`, `taxi_id`, `status`, `application_date`) VALUES
(1, 2, 1, 'rejected', '2024-08-21'),
(2, 2, 1, 'rejected', '2024-08-21'),
(3, 2, 1, 'approved', '2024-08-21'),
(4, 2, 1, 'pending', '2024-08-21'),
(5, 2, 1, 'rejected', '2024-08-21'),
(6, 2, 1, 'rejected', '2024-08-21');

-- --------------------------------------------------------

--
-- Table structure for table `taxis`
--

CREATE TABLE `taxis` (
  `id` int(11) NOT NULL,
  `owner_id` int(11) NOT NULL,
  `driver_name` varchar(50) NOT NULL,
  `taxi_disc_num` varchar(20) NOT NULL,
  `number_plate` varchar(20) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'inactive'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taxis`
--

INSERT INTO `taxis` (`id`, `owner_id`, `driver_name`, `taxi_disc_num`, `number_plate`, `driver_id`, `status`) VALUES
(1, 1, 'Sipho', 'NC38930', 'ND  50928', NULL, 'active'),
(2, 1, 'Sipho', 'NC38930', 'ND  50928', NULL, 'inactive'),
(3, 1, 'Mami', 'NF 0927738', 'NU Nxamalala', NULL, 'inactive'),
(4, 1, 'Mami', 'NF 0927738', 'NU Nxamalala', NULL, 'inactive'),
(5, 1, 'Mami', 'NF 0927738', 'NU Nxamalala', NULL, 'inactive');

-- --------------------------------------------------------

--
-- Table structure for table `taxi_drivers`
--

CREATE TABLE `taxi_drivers` (
  `id` int(11) NOT NULL,
  `profile_picture` varchar(255) DEFAULT NULL,
  `name` varchar(50) NOT NULL,
  `drivers_license_photo` varchar(255) DEFAULT NULL,
  `drivers_license_code` varchar(20) NOT NULL,
  `date_obtained` date NOT NULL,
  `taxi_id` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `trips`
--

CREATE TABLE `trips` (
  `id` int(11) NOT NULL,
  `driver_id` int(11) DEFAULT NULL,
  `taxi_id` int(11) DEFAULT NULL,
  `start_time` datetime DEFAULT NULL,
  `end_time` datetime DEFAULT NULL,
  `start_location` varchar(255) DEFAULT NULL,
  `end_location` varchar(255) DEFAULT NULL,
  `start_address` varchar(255) DEFAULT NULL,
  `end_address` varchar(255) DEFAULT NULL,
  `trip_number` int(11) DEFAULT NULL,
  `earnings` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `trips`
--

INSERT INTO `trips` (`id`, `driver_id`, `taxi_id`, `start_time`, `end_time`, `start_location`, `end_location`, `start_address`, `end_address`, `trip_number`, `earnings`) VALUES
(5, 2, 1, '2024-08-29 14:10:20', '2024-08-29 14:49:23', NULL, NULL, NULL, NULL, NULL, 300.00),
(6, 2, 2, '2024-08-29 14:49:49', '2024-08-29 15:09:02', NULL, NULL, NULL, NULL, NULL, 500.00),
(7, 2, 1, '2024-08-29 15:09:20', '2024-08-29 15:10:14', NULL, NULL, NULL, NULL, NULL, 300.00),
(8, 2, 1, '2024-08-29 15:14:32', '2024-08-29 17:02:07', NULL, NULL, NULL, NULL, NULL, 500.00),
(9, 2, 1, '2024-08-29 16:48:30', '2024-08-29 16:48:44', NULL, NULL, NULL, NULL, NULL, 500.00),
(10, 2, 1, '2024-08-29 17:02:17', '2024-08-29 17:21:40', NULL, NULL, NULL, NULL, NULL, 555.00),
(11, 2, 1, '2024-08-29 17:04:12', '2024-08-29 17:19:44', NULL, NULL, NULL, NULL, NULL, 555.00),
(13, 2, 1, '2024-08-29 17:07:19', '2024-08-29 17:19:35', NULL, NULL, NULL, NULL, NULL, 555.00),
(15, 2, 1, '2024-08-29 17:11:56', '2024-08-29 17:19:25', NULL, NULL, NULL, NULL, NULL, 555.00),
(16, 2, 2, '2024-08-29 17:11:56', '2024-08-29 17:16:26', NULL, NULL, NULL, NULL, NULL, 666.00),
(17, 2, 1, '2024-08-29 17:16:35', '2024-08-29 17:19:10', NULL, NULL, NULL, NULL, NULL, 555.00),
(19, 2, 1, '2024-08-29 17:18:01', '2024-08-29 17:18:52', NULL, NULL, NULL, NULL, NULL, 5000.00),
(20, 2, 1, '2024-08-29 17:18:01', '2024-08-29 17:18:29', NULL, NULL, NULL, NULL, NULL, 555.00),
(21, 2, 1, '2024-08-30 11:28:07', '2024-08-30 11:28:29', NULL, NULL, NULL, NULL, NULL, 555.00),
(22, 2, 1, '2024-08-30 11:28:07', '2024-08-30 11:28:23', NULL, NULL, NULL, NULL, NULL, 5555.00);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `id_number` varchar(20) NOT NULL,
  `cellphone` varchar(15) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `pin` varchar(255) NOT NULL,
  `user_type` enum('manager','owner','driver') NOT NULL DEFAULT 'owner'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `surname`, `id_number`, `cellphone`, `email`, `username`, `pin`, `user_type`) VALUES
(1, 'Taxi', 'Owner', '', '', 'user@test.com', 'user', '$2y$10$G1iV1Zs.MpQTkMRP5REIMuRK824Pct92on4cF/3u0Z3E8CJ.Tx0oC', 'owner'),
(2, 'Driver', 'Shumaka', '', '', 'smesihlentshangase2908@gmail.com', 'driver', '$2y$10$rGaiPdadX4DHSM7GVps6wO3h/.lRyqoZGUXSOe7Lb0Z3koeDn7IvO', 'driver'),
(3, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'admin', '$2y$10$Oy7rnDUmkeIxPC.GqxI8EeH2aOecKKGh5scGq.o9iV9OnMjv28vRW', 'manager'),
(4, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'sili', '$2y$10$kh6vQsj4T2Ojee1NlVacPOBHdKLVRglppSRYZb79rtRCQQj7q9a4S', 'driver'),
(5, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'sili', '$2y$10$/2UvhsJ98FWhID7ajmG0nOOjxGNkHX1a8TKC8y1cmnQodRb8WBaf.', 'driver'),
(6, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'sili', '$2y$10$8d8WxrQq2jjzTCsEEssQQOv6cYFAZ3x7x24UTD5VhJ1Xx3gyQWnYO', 'driver'),
(7, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'sili', '$2y$10$7vT0eSv/1CMeD2Df8PzmDuBxvHNKS35qT5arb3YUqcG1j60aSD5dK', 'driver'),
(8, 'Silindile', 'Ntshangase', '', '', 'codesteps.info24@yahoo.com', 'thisthat', '$2y$10$hfraxqB.aHaJ/eAjwiKI5e0h006dBgwnWHcemj8oFPUeiwazauRpu', 'manager'),
(9, 'Silindile', 'Ntshangase', '', '', 'codesteps.info24@yahoo.com', 'thisthat', '$2y$10$wFZkCS0e1x2yW/6oQC0ev.QEVVM74JFwFfR4RHGXmWLQijV/4nLQK', 'manager'),
(10, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', 'ffghh', '$2y$10$Z.62nrZ2erj45pphdqWzbOHOTbhaXNJ9Wt8HBOsZvmxCE1NraYy6W', 'manager'),
(11, 'Silindile', 'Ntshangase', '', '', 'edgytrends131@gmail.com', '0725873436', '$2y$10$pkCpIw9j4k42uanwr27mqeMZAeSH4JoW8lsVTP2uAUVRopAgahCVa', 'manager'),
(12, 'ntokozo', 'mthembu', '', '', 'mthembuntokozo@gmail.com', 'ntokozo', '$2y$10$ouOa8b.yi1UK0M64.vOMzebLiJ/M9vXA5/xUMRLz38L4SUBOZ71ZC', 'owner'),
(13, 'ntokozo', 'mthembu', '', '', 'mthembuntokozo@gmail.com', 'ntokozo', '$2y$10$ROlMCyNUR41wLpJ0NZ/sWuNnNbBH31GUgvn0nea2FFZ/G/9XfwsXu', 'owner'),
(14, 'ntokozo', 'mthembu', '', '', 'mthembuntokozo025@gmail.com', 'ntokozo', '$2y$10$h0flvzfJuze5X9Vm//BMMemkvfuvLT8lXqvsQuco0txBVUi0zlKhy', 'owner'),
(15, 'ntokozo', 'mthembu', '', '', 'mthembuntokozo025@gmail.com', 'ntokozo', '$2y$10$R0HmENMNyPQsMJAXPt0UdOpRVDxIiK8XJFuhgorqJ1CsLR07k6IHC', 'owner'),
(16, 'ntokozo', 'mthembu', '', '', 'mthembuntokozo025@gmail.com', 'ntokozo', '$2y$10$DVdRtoR/AII5SvBtlPN64ufiWUigY2iEgsNYcH0hvfbiovsQZBOGO', 'owner'),
(17, 'Eddy', 'Eddy', '', '', 'mthembuntokozo025@gmail.com', 'user2', '$2y$10$uthh5sM9dV4QpsztxU6g8uxLCTcXc95A9mpHpi0MKj7616w7qG4q6', 'owner'),
(18, 'Eddy', 'Eddy', '', '', 'mthembuntokozo025@gmail.com', 'user2', '$2y$10$0ll6yW1UOJwSrJDYZwYGv.IpFhGe47M/73gvL/gG4yM8wKUMgics2', 'owner');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `taxi_id` (`taxi_id`);

--
-- Indexes for table `taxis`
--
ALTER TABLE `taxis`
  ADD PRIMARY KEY (`id`),
  ADD KEY `owner_id` (`owner_id`),
  ADD KEY `fk_driver` (`driver_id`);

--
-- Indexes for table `taxi_drivers`
--
ALTER TABLE `taxi_drivers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_taxi_driver` (`taxi_id`);

--
-- Indexes for table `trips`
--
ALTER TABLE `trips`
  ADD PRIMARY KEY (`id`),
  ADD KEY `driver_id` (`driver_id`),
  ADD KEY `taxi_id` (`taxi_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `taxis`
--
ALTER TABLE `taxis`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `taxi_drivers`
--
ALTER TABLE `taxi_drivers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `trips`
--
ALTER TABLE `trips`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`taxi_id`) REFERENCES `taxis` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `taxis`
--
ALTER TABLE `taxis`
  ADD CONSTRAINT `fk_driver` FOREIGN KEY (`driver_id`) REFERENCES `taxi_drivers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `taxis_ibfk_1` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `taxi_drivers`
--
ALTER TABLE `taxi_drivers`
  ADD CONSTRAINT `taxi_drivers_ibfk_1` FOREIGN KEY (`taxi_id`) REFERENCES `taxis` (`id`);

--
-- Constraints for table `trips`
--
ALTER TABLE `trips`
  ADD CONSTRAINT `trips_ibfk_1` FOREIGN KEY (`driver_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `trips_ibfk_2` FOREIGN KEY (`taxi_id`) REFERENCES `taxis` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
