-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 01, 2025 at 08:39 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `student_dashboard`
--

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `company_address` varchar(255) NOT NULL,
  `place` varchar(100) NOT NULL,
  `email_id` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `userid` int(11) NOT NULL,
  `ip_address` varchar(50) NOT NULL,
  `thumbnail_image` varchar(255) NOT NULL,
  `joining_date` date NOT NULL DEFAULT curdate(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_name`, `company_address`, `place`, `email_id`, `mobile_number`, `userid`, `ip_address`, `thumbnail_image`, `joining_date`, `created_at`) VALUES
(1, 'Wipro', 'Kerala', 'TVM', 'wipro@gmail.com', '9815089036', 37, '142.187.1.1', 'image7.jpg', '2025-02-27', '2025-02-27 07:11:23'),
(2, 'TCS', 'Kerala', 'TVM', 'TCS@gmail.com', '9815089036', 39, '142.187.1.1', 'image7.jpg', '2025-02-27', '2025-02-27 07:11:40'),
(3, 'Infosys', 'Kerala', 'kochi', 'Infosys@gmail.com', '9815089036', 39, '142.187.1.1', 'image7.jpg', '2025-02-27', '2025-02-27 07:12:02'),
(4, 'Hcl', 'Kerala', 'kochi', 'hcl@gmail.com', '9815089036', 79, '142.187.1.1', 'image7.jpg', '2025-02-27', '2025-02-27 07:12:32');

-- --------------------------------------------------------

--
-- Table structure for table `employee`
--

CREATE TABLE `employee` (
  `id` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `employee_id` varchar(50) NOT NULL,
  `employee_name` varchar(100) NOT NULL,
  `employee_address` varchar(255) NOT NULL,
  `place` varchar(100) NOT NULL,
  `mail_id` varchar(100) NOT NULL,
  `pincode` varchar(10) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee`
--

INSERT INTO `employee` (`id`, `companyid`, `employee_id`, `employee_name`, `employee_address`, `place`, `mail_id`, `pincode`, `mobile_number`, `thumbnail_image`) VALUES
(1, 4, 'EMP171', 'Sreeleksmi', 'Thrissur', 'Kerala', 'sreegmail.com', '675678', '9898867666', '67c0108725f20_pay3.png'),
(2, 4, 'EMP173', 'rajesh', 'Thrissur', 'Kerala', 'rajeshgmail.com', '675678', '9898867666', '67c01096314c7_pay3.png'),
(3, 2, 'EMP172', 'Arjun', 'TVM', 'Kerala', 'Arjugmail.com', '675678', '9898867666', '67c010ac38552_pay3.png'),
(4, 1, 'EMP188', 'Arun', 'kannur', 'Kerala', 'arungmail.com', '675678', '9898867666', '67c010c22a142_pay3.png');

-- --------------------------------------------------------

--
-- Table structure for table `mds`
--

CREATE TABLE `mds` (
  `id` int(11) NOT NULL,
  `mds_id` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `mds_name` varchar(100) NOT NULL,
  `total_salary` decimal(10,2) NOT NULL,
  `starting_date` date NOT NULL,
  `number_of_installments` int(11) NOT NULL,
  `end_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mds`
--

INSERT INTO `mds` (`id`, `mds_id`, `companyid`, `mds_name`, `total_salary`, `starting_date`, `number_of_installments`, `end_date`) VALUES
(1, 180, 2, 'mds277', 550700.00, '2025-03-01', 12, '2026-03-01'),
(2, 200, 3, 'namem22', 78000.00, '2023-10-01', 2, '2021-05-08'),
(3, 222, 2, 'nam22', 700.00, '2023-10-01', 2, '2021-05-08'),
(4, 112, 1, 'nam22', 7010.00, '2023-10-01', 2, '2021-05-08');

-- --------------------------------------------------------

--
-- Table structure for table `mdsmembers`
--

CREATE TABLE `mdsmembers` (
  `id` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `mds_id` int(11) NOT NULL,
  `memberid` int(11) NOT NULL,
  `joining_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mdsmembers`
--

INSERT INTO `mdsmembers` (`id`, `companyid`, `mds_id`, `memberid`, `joining_date`) VALUES
(1, 4, 112, 2, '2025-08-10'),
(2, 2, 222, 4, '2015-08-10'),
(3, 3, 180, 1, '2015-08-10'),
(4, 1, 200, 2, '2015-08-10');

-- --------------------------------------------------------

--
-- Table structure for table `members`
--

CREATE TABLE `members` (
  `id` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `address` varchar(255) NOT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `email_id` varchar(100) NOT NULL,
  `joined_date` date NOT NULL,
  `thumbnail_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `members`
--

INSERT INTO `members` (`id`, `companyid`, `account_id`, `name`, `address`, `mobile_number`, `email_id`, `joined_date`, `thumbnail_image`, `created_at`) VALUES
(1, 2, 12, 'areena', 'ijk', '9898898983', 'areeern@gmail.com', '0001-06-20', 'c4ca4238a0b923820dcc509a6f75849b_thumbnail.png', '2025-02-27 07:24:34'),
(2, 4, 15, 'naina', 'kochi', '9898898983', 'naina@gmail.com', '0001-06-20', 'c81e728d9d4c2f636f067f89cc14862c_thumbnail.png', '2025-02-27 07:25:17'),
(3, 1, 20, 'rani', 'kochi', '9898898983', 'rani@gmail.com', '0001-06-11', 'eccbc87e4b5ce2fe28308fd9f2a7baf3_thumbnail.png', '2025-02-27 07:25:58'),
(4, 2, 22, 'prince', 'kochi', '9898898983', 'prince@gmail.com', '0002-06-11', 'a87ff679a2f3e71d9181a67b7542122c_thumbnail.png', '2025-02-27 07:27:43');

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `id` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `notification_description` text NOT NULL,
  `date` date NOT NULL,
  `userid` int(11) NOT NULL,
  `type` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`id`, `companyid`, `notification_description`, `date`, `userid`, `type`) VALUES
(1, 3, 'hi this is christina', '2025-03-01', 7, 1),
(2, 4, 'new notifi', '2025-03-10', 17, 8),
(3, 4, 'Notification recieved ', '2025-08-01', 10, 22),
(4, 2, 'New offer ', '2025-08-22', 34, 8),
(5, 1, 'New job', '2025-08-01', 60, 33);

-- --------------------------------------------------------

--
-- Table structure for table `payment`
--

CREATE TABLE `payment` (
  `id` int(11) NOT NULL,
  `installment_id` int(11) NOT NULL,
  `memberid` int(11) NOT NULL,
  `companyid` int(11) NOT NULL,
  `mds_id` int(11) NOT NULL,
  `userid` int(11) NOT NULL,
  `created_at` date NOT NULL,
  `paid_date` date NOT NULL,
  `paid_amount` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payment`
--

INSERT INTO `payment` (`id`, `installment_id`, `memberid`, `companyid`, `mds_id`, `userid`, `created_at`, `paid_date`, `paid_amount`) VALUES
(2, 10, 2, 3, 180, 3, '2022-11-21', '2025-06-05', 25000.00),
(3, 6, 1, 2, 222, 8, '2022-11-21', '2025-06-05', 75000.00),
(4, 2, 1, 1, 200, 8, '2022-11-21', '2025-06-05', 22200.00);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `employee`
--
ALTER TABLE `employee`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `companyid` (`companyid`);

--
-- Indexes for table `mds`
--
ALTER TABLE `mds`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `mds_id` (`mds_id`),
  ADD KEY `companyid` (`companyid`);

--
-- Indexes for table `mdsmembers`
--
ALTER TABLE `mdsmembers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyid` (`companyid`),
  ADD KEY `mds_id` (`mds_id`),
  ADD KEY `memberid` (`memberid`);

--
-- Indexes for table `members`
--
ALTER TABLE `members`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `account_id` (`account_id`),
  ADD KEY `companyid` (`companyid`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`id`),
  ADD KEY `companyid` (`companyid`);

--
-- Indexes for table `payment`
--
ALTER TABLE `payment`
  ADD PRIMARY KEY (`id`),
  ADD KEY `memberid` (`memberid`),
  ADD KEY `companyid` (`companyid`),
  ADD KEY `mds_id` (`mds_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `employee`
--
ALTER TABLE `employee`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mds`
--
ALTER TABLE `mds`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `mdsmembers`
--
ALTER TABLE `mdsmembers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `members`
--
ALTER TABLE `members`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `employee`
--
ALTER TABLE `employee`
  ADD CONSTRAINT `employee_ibfk_1` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mds`
--
ALTER TABLE `mds`
  ADD CONSTRAINT `mds_ibfk_1` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mdsmembers`
--
ALTER TABLE `mdsmembers`
  ADD CONSTRAINT `mdsmembers_ibfk_1` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mdsmembers_ibfk_2` FOREIGN KEY (`mds_id`) REFERENCES `mds` (`mds_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `mdsmembers_ibfk_3` FOREIGN KEY (`memberid`) REFERENCES `members` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `members`
--
ALTER TABLE `members`
  ADD CONSTRAINT `members_ibfk_1` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `notification_ibfk_1` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payment`
--
ALTER TABLE `payment`
  ADD CONSTRAINT `payment_ibfk_1` FOREIGN KEY (`memberid`) REFERENCES `members` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_2` FOREIGN KEY (`companyid`) REFERENCES `company` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payment_ibfk_3` FOREIGN KEY (`mds_id`) REFERENCES `mds` (`mds_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
