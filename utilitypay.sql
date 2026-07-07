-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 02, 2026 at 04:58 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `utilitypay`
--

-- --------------------------------------------------------

--
-- Table structure for table `bills`
--

CREATE TABLE `bills` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `utility_type` enum('water','electricity','internet') NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `due_date` date NOT NULL,
  `status` enum('pending','paid') DEFAULT 'pending',
  `bill_number` varchar(50) DEFAULT NULL,
  `provider` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `bills`
--

INSERT INTO `bills` (`id`, `user_id`, `utility_type`, `amount`, `due_date`, `status`, `bill_number`, `provider`, `created_at`) VALUES
(1, 3, 'water', 15000.00, '2026-06-02', 'paid', NULL, 'DAWASA', '2026-06-01 21:16:09'),
(2, 4, 'electricity', 50000.00, '2026-06-03', 'paid', NULL, 'TANESCO', '2026-06-01 21:23:18'),
(5, 10, 'internet', 100000.00, '2026-06-02', 'paid', NULL, 'TTCL', '2026-06-01 23:43:18');

-- --------------------------------------------------------

--
-- Table structure for table `payment_schedules`
--

CREATE TABLE `payment_schedules` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `utility_type` enum('water','electricity','internet') DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `frequency` enum('monthly','quarterly') DEFAULT NULL,
  `next_payment` date DEFAULT NULL,
  `active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `bill_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `payment_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `payment_method` varchar(50) DEFAULT NULL,
  `status` enum('success','failed') DEFAULT 'success',
  `reference` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `bill_id`, `amount`, `payment_date`, `payment_method`, `status`, `reference`) VALUES
(1, 4, 2, 50000.00, '2026-06-01 22:22:41', 'M-Pesa', 'success', 'UPAY178035256150837'),
(2, 3, 1, 15000.00, '2026-06-01 22:27:24', 'M-Pesa', 'success', 'UPAY178035284440063'),
(3, 3, 1, 15000.00, '2026-06-01 22:27:34', 'M-Pesa', 'success', 'UPAY178035285479717'),
(5, 10, 5, 100000.00, '2026-06-01 23:43:46', 'M-Pesa', 'success', 'UPAY178035742620463');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(1, 'Admin', 'admin@utilitypay.co.tz', '$2y$10$CEKsdRGKfCgu.ESfehx6RubjvrVmZjUdqFoqdA2Sh669jJfLjagsi', NULL, NULL, 'admin', '2026-06-01 19:00:47'),
(3, 'John Mwita', 'john@gmail.com', '$2y$10$pzOM1z1ruTVZRyCM4UXPquIlSuK/HPGTcg0SXd7Kk0GNHb4d/UndO', '0712345678', NULL, 'user', '2026-06-01 21:12:07'),
(4, 'Mwaisa Moshi', 'Mwaisa@gmail.com', '$2y$10$TTq6UlPjRbMqJqreBDKKIeb226t193.Ly1cPx6ojqAgbeRZ2A4vhm', '0756453254', '', 'user', '2026-06-01 21:21:54'),
(10, 'Maximillian Salvatory', 'Maximilliansalvatory@gmail.com', '$2y$10$3QQbQI0VtBQIGDFJfhfeKeTeNo2z9ektTYqG/5JiHc.xwzVjZ2SmS', '0747305370', NULL, 'user', '2026-06-01 23:42:19');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bills`
--
ALTER TABLE `bills`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payment_schedules`
--
ALTER TABLE `payment_schedules`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bills`
--
ALTER TABLE `bills`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `payment_schedules`
--
ALTER TABLE `payment_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bills`
--
ALTER TABLE `bills`
  ADD CONSTRAINT `bills_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
