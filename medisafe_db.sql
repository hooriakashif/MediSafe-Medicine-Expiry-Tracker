-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 24, 2025 at 08:41 PM
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
-- Database: `medisafe_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `medicines`
--

CREATE TABLE `medicines` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `type` enum('Tablet','Capsule','Syrup','Injection','Cream','Drops','Other') NOT NULL,
  `expiry_date` date NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `status` enum('active','used','discarded') DEFAULT 'active',
  `added_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `medicines`
--

INSERT INTO `medicines` (`id`, `user_id`, `name`, `type`, `expiry_date`, `quantity`, `status`, `added_date`) VALUES
(1, 2, 'Paracetamol 650mg', 'Tablet', '2024-11-01', 20, 'active', '2025-11-24 23:12:45'),
(2, 2, 'Amoxicillin 500mg', 'Capsule', '2025-11-28', 10, 'active', '2025-11-24 23:13:35'),
(3, 2, 'Crocin Cold & Flu', 'Tablet', '2025-12-01', 15, 'used', '2025-11-24 23:14:43'),
(4, 2, 'Dolo-650', 'Tablet', '2026-06-20', 60, 'active', '2025-11-24 23:16:15'),
(5, 2, 'ORS Powder', 'Other', '2027-03-10', 8, 'discarded', '2025-11-24 23:17:17'),
(6, 2, 'Liv-52', 'Tablet', '2025-12-31', 60, 'discarded', '2025-11-24 23:28:04'),
(7, 2, 'Crocin Cold & Flu', 'Tablet', '2025-12-01', 15, 'active', '2025-11-24 23:32:20'),
(8, 2, 'ORS Powder', 'Other', '2027-03-10', 8, 'active', '2025-11-24 23:33:08'),
(9, 2, 'Liv-52', 'Tablet', '2025-12-31', 60, 'active', '2025-11-24 23:34:03'),
(10, 4, 'Paracetamol 650mg', 'Tablet', '2025-10-15', 30, 'active', '2025-11-25 00:00:29'),
(11, 4, 'Amoxicillin 500mg', 'Capsule', '2025-11-28', 12, 'active', '2025-11-25 00:01:05'),
(12, 4, 'Crocin Advance', 'Tablet', '2025-11-30', 20, 'active', '2025-11-25 00:01:36'),
(15, 4, 'Betadine Ointment', 'Cream', '2025-12-05', 1, 'active', '2025-11-25 00:03:37'),
(16, 4, 'ORS Powder', 'Other', '2027-05-10', 10, 'active', '2025-11-25 00:12:38'),
(17, 4, 'Liv-52 Tablets', 'Tablet', '2026-08-28', 60, 'active', '2025-11-25 00:13:22');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `created_at`) VALUES
(1, 'Test User', 'test@gmail.com', '123456', '2025-11-24 22:18:33'),
(2, 'Rahul', 'rahul@gmail.com', '123456', '2025-11-24 22:53:30'),
(3, 'Hooria Kashif', 'hooria@gmail.com', '0', '2025-11-24 23:57:13'),
(4, 'fatima', 'fatima@gmail.com', '$2y$10$tnyGtR1yvEXB.nprGVy29uf17z.4f1HUEJQ1mzl/Faj20EdmMWcWm', '2025-11-24 23:59:32');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `medicines`
--
ALTER TABLE `medicines`
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
-- AUTO_INCREMENT for table `medicines`
--
ALTER TABLE `medicines`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `medicines`
--
ALTER TABLE `medicines`
  ADD CONSTRAINT `medicines_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
