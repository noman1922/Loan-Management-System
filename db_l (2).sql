-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 03, 2025 at 10:06 PM
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
-- Database: `db_l`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_loans`
--

CREATE TABLE `tb_loans` (
  `loan_id` int(11) NOT NULL,
  `user_name` varchar(100) NOT NULL,
  `nid_number` varchar(50) NOT NULL,
  `father_name` varchar(100) NOT NULL,
  `mother_name` varchar(100) NOT NULL,
  `dob` date NOT NULL,
  `nationality` varchar(50) NOT NULL,
  `loan_amount` decimal(10,2) NOT NULL,
  `interest_rate` decimal(5,2) NOT NULL,
  `loan_year` int(11) NOT NULL,
  `loan_type` enum('Personal','Business','Education') NOT NULL,
  `application_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_loans`
--

INSERT INTO `tb_loans` (`loan_id`, `user_name`, `nid_number`, `father_name`, `mother_name`, `dob`, `nationality`, `loan_amount`, `interest_rate`, `loan_year`, `loan_type`, `application_date`, `status`) VALUES
(2, 'sumaiya', '15469846428', 'xy', 'yx', '2001-10-19', 'bangladeshi', 50000.00, 10.00, 1, 'Business', '2025-01-03 19:26:57', 'Approved'),
(3, 'nmn', '1546984642', 'x', 'y', '2003-06-04', 'bangladeshi', 500000.00, 2.00, 3, 'Education', '2025-01-03 19:28:40', 'Approved');

-- --------------------------------------------------------

--
-- Table structure for table `tb_payments`
--

CREATE TABLE `tb_payments` (
  `payment_id` int(11) NOT NULL,
  `loan_id` int(11) NOT NULL,
  `payment_amount` decimal(10,2) NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tb_payments`
--

INSERT INTO `tb_payments` (`payment_id`, `loan_id`, `payment_amount`, `payment_date`) VALUES
(2, 2, 5000.00, '2025-01-04'),
(3, 2, 60000.00, '2025-01-05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_loans`
--
ALTER TABLE `tb_loans`
  ADD PRIMARY KEY (`loan_id`);

--
-- Indexes for table `tb_payments`
--
ALTER TABLE `tb_payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `loan_id` (`loan_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_loans`
--
ALTER TABLE `tb_loans`
  MODIFY `loan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `tb_payments`
--
ALTER TABLE `tb_payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_payments`
--
ALTER TABLE `tb_payments`
  ADD CONSTRAINT `tb_payments_ibfk_1` FOREIGN KEY (`loan_id`) REFERENCES `tb_loans` (`loan_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
