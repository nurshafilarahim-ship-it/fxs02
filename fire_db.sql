-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2026 at 08:56 AM
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
-- Database: `fire_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `extinguisher`
--

CREATE TABLE `extinguisher` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `type` varchar(50) DEFAULT NULL,
  `serial_no` varchar(100) DEFAULT NULL,
  `date_checkup` date DEFAULT NULL,
  `expired_date` date DEFAULT NULL,
  `status` varchar(20) DEFAULT NULL,
  `qr_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `extinguisher`
--

INSERT INTO `extinguisher` (`id`, `name`, `type`, `serial_no`, `date_checkup`, `expired_date`, `status`, `qr_image`) VALUES
(5, 'fieyla', 'Water', '111', '2026-01-06', '2026-01-31', 'Active', '695cb7c132df0.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `extinguisher`
--
ALTER TABLE `extinguisher`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `extinguisher`
--
ALTER TABLE `extinguisher`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
