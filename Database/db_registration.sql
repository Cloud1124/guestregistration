-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Jul 26, 2025 at 03:10 PM
-- Server version: 9.1.0
-- PHP Version: 8.4.0

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_registration`
--

-- --------------------------------------------------------

--
-- Table structure for table `guests`
--

DROP TABLE IF EXISTS `guests`;
CREATE TABLE IF NOT EXISTS `guests` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `agency_org` varchar(150) DEFAULT NULL,
  `designation` varchar(100) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `mobile` varchar(30) DEFAULT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `signature_data` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci,
  `registered_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `guests`
--

INSERT INTO `guests` (`id`, `name`, `agency_org`, `designation`, `email`, `mobile`, `gender`, `signature_data`, `registered_at`) VALUES
(5, 'Sample juan', 'Juan sample', 'Sample sample', 'sample.hatdogemail@gmail.com', '097998455123', 'Prefer not', 'sig_6860dd97762923.29246238.png', '2025-06-29 14:30:47'),
(6, 'Urgaiiw', 'Uqyytahh', 'Jejusis', 'jjako@gmail.com', '091576788451', 'Female', 'sig_6860dfa51e6229.31260584.png', '2025-06-29 14:39:33'),
(4, 'Sample', 'Sample', 'Sample', 'sample.hatdogemail@gmail.com', '09151234567', 'Male', 'sig_6860da082601e9.58847190.png', '2025-06-29 14:15:36');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(60) NOT NULL,
  `username` varchar(35) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `password`) VALUES
(1, 'Admin', 'admin', '$2y$12$3b5CeF4oZWeQvTu9vFFI7eP6sp5Be5NAEjdEwXDdcXNqbuBYWu4oW');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
