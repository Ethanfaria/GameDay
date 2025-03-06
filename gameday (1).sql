-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 02, 2025 at 12:19 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gameday`
--

-- --------------------------------------------------------

--
-- Table structure for table `academys`
--

CREATE TABLE `academys` (
  `ac_id` varchar(255) NOT NULL,
  `aca_nm` varchar(255) DEFAULT NULL,
  `ac_location` varchar(255) DEFAULT NULL,
  `ac_charges` double DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academys`
--

INSERT INTO `academys` (`ac_id`, `aca_nm`, `ac_location`, `ac_charges`, `venue_id`) VALUES
('ac_001', 'Sesa Football Academy', 'Panjim', NULL, 'ven_001'),
('ac_002', 'Sporting Clube de Goa Football Academy', 'Panjim', NULL, 'ven_002'),
('ac_003', 'M7 Football Club', 'Panjim', NULL, 'ven_003'),
('ac_004', 'Panjim Gymkhana Sports Club', 'Panjim', NULL, 'ven_004'),
('ac_005', 'Panjim Gymkhana', 'Panjim', NULL, 'ven_005'),
('ac_006', 'Geno Sports Club', 'Mapusa', NULL, 'ven_006'),
('ac_007', 'Laxmi Prasad Sports Club', 'Mapusa', NULL, 'ven_007'),
('ac_008', 'Cunchelim Sports Club', 'Mapusa', NULL, 'ven_008'),
('ac_009', 'Calangute Football Association', 'Calangute', NULL, 'ven_009'),
('ac_010', 'United Football Academy', 'Calangute', NULL, 'ven_010'),
('ac_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', NULL, 'ven_011'),
('ac_012', 'Candolim Sports Club', 'Candolim', NULL, 'ven_012'),
('ac_013', 'FC Goa House', 'Porvorim', NULL, 'ven_013'),
('ac_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', NULL, 'ven_014'),
('ac_015', 'Churchill Brothers FC', 'Margao', NULL, 'ven_015'),
('ac_016', 'Youth Futsal Academy', 'Margao', NULL, 'ven_016'),
('ac_017', 'Salgaocar Football Club', 'Vasco da Gama', NULL, 'ven_017'),
('ac_018', 'Vasco Sports Club', 'Vasco da Gama', NULL, 'ven_018'),
('ac_019', 'Baina Football Academy', 'Vasco da Gama', NULL, 'ven_019'),
('ac_020', 'Chicalim Sports Complex', 'Vasco da Gama', NULL, 'ven_020'),
('ac_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', NULL, 'ven_021'),
('ac_022', 'Majestic Futsal Turf', 'Ponda', NULL, 'ven_022');

-- --------------------------------------------------------

--
-- Table structure for table `academy_reviews`
--

CREATE TABLE `academy_reviews` (
  `review_id` varchar(255) NOT NULL,
  `ac_id` varchar(255) DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `announcement`
--

CREATE TABLE `announcement` (
  `ann_id` varchar(255) NOT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `offers` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking`
--

CREATE TABLE `booking` (
  `bk_date` date NOT NULL,
  `bk_dur` double DEFAULT NULL,
  `user_email` varchar(255) NOT NULL,
  `venue_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_nm` varchar(255) DEFAULT NULL,
  `o_email` varchar(255) NOT NULL,
  `owner_ph` int(11) DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referee`
--

CREATE TABLE `referee` (
  `ref_id` varchar(255) NOT NULL,
  `ref_name` varchar(255) DEFAULT NULL,
  `ref_location` varchar(255) DEFAULT NULL,
  `ref_contact` varchar(255) DEFAULT NULL,
  `ref_pic` varchar(255) DEFAULT NULL,
  `charges` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referee`
--

INSERT INTO `referee` (`ref_id`, `ref_name`, `ref_location`, `ref_contact`, `ref_pic`, `charges`) VALUES
('ref_001', 'Aaron Abel Cardoso', 'Goa Velha', '9876543210', NULL, NULL),
('ref_002', 'Adesh Narayan Palni', 'Marcel', '9876543211', NULL, NULL),
('ref_003', 'Akshaya Pundalik Shetgaonkar', 'Pernem', '9876543212', NULL, NULL),
('ref_004', 'Aliston Fernandes', 'Margao Goa', '9876543213', NULL, NULL),
('ref_005', 'Atharv Desai', 'Ponda', '9876543214', NULL, NULL),
('ref_006', 'Bhavesh Bharat Sawant', 'Mapusa', '9876543215', NULL, NULL),
('ref_007', 'Celrich Amyster Almeida', 'Taleigao', '9876543216', NULL, NULL),
('ref_008', 'Joseph Lobo', 'Mapusa', '9876543217', NULL, NULL),
('ref_009', 'Jayesh Kadam', 'Goa', '9876543218', NULL, NULL),
('ref_010', 'Kashinath Vithal Kamat', 'Sanquelim', '9876543219', NULL, NULL),
('ref_011', 'Leroy Gonzaga Sequeira', 'Margao', '9876543220', NULL, NULL),
('ref_012', 'Naved Francis Almeida', 'Panaji', '9876543221', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_nm` varchar(255) NOT NULL,
  `tr_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `tr_id` varchar(255) NOT NULL,
  `tr_schedule` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`tr_id`, `tr_schedule`, `start_date`, `end_date`, `venue_id`) VALUES
('t_001', 'Every Saturday and Sunday, 10:00 AM - 5:00 PM', '2025-02-01', '2025-02-28', 'ven_001'),
('t_002', 'Weekdays, 4:00 PM - 7:00 PM', '2025-03-01', '2025-03-20', 'ven_002'),
('t_003', 'Weekdays, 5:00 PM - 8:00 PM', '2025-04-01', '2025-04-15', 'ven_003'),
('t_004', 'Saturdays, 9:00 AM - 12:00 PM', '2025-05-01', '2025-05-20', 'ven_004'),
('t_005', 'Weekdays, 4:00 PM - 6:00 PM', '2025-06-01', '2025-06-15', 'ven_005'),
('t_006', 'Every Friday, 3:00 PM - 8:00 PM', '2025-07-01', '2025-07-31', 'ven_006'),
('t_007', 'Weekends, 10:00 AM - 3:00 PM', '2025-08-01', '2025-08-30', 'ven_007'),
('t_008', 'Weekdays, 5:00 PM - 7:00 PM', '2025-09-01', '2025-09-15', 'ven_008'),
('t_009', 'Special Event, 10:00 AM - 4:00 PM', '2025-10-01', '2025-10-05', 'ven_009');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_name` varchar(255) DEFAULT NULL,
  `user_ph` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` varchar(255) NOT NULL,
  `venue_nm` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `availability` tinyint(1) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `size` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_nm`, `location`, `availability`, `price`, `size`) VALUES
('ven_001', 'Sesa Football Academy', 'Panjim', 1, 5000, 100),
('ven_002', 'Sporting Clube de Goa Football Academy', 'Panjim', 1, 6000, 120),
('ven_003', 'M7 Football Club', 'Panjim', 1, 7000, 150),
('ven_004', 'Panjim Gymkhana Sports Club', 'Panjim', 1, 5500, 110),
('ven_005', 'Panjim Gymkhana', 'Panjim', 1, 5000, 100),
('ven_006', 'Geno Sports Club', 'Mapusa', 1, 4000, 90),
('ven_007', 'Laxmi Prasad Sports Club', 'Mapusa', 1, 4500, 100),
('ven_008', 'Cunchelim Sports Club', 'Mapusa', 1, 5000, 110),
('ven_009', 'Calangute Football Association', 'Calangute', 1, 6000, 120),
('ven_010', 'United Football Academy', 'Calangute', 1, 7000, 130),
('ven_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', 1, 5500, 100),
('ven_012', 'Candolim Sports Club', 'Candolim', 1, 5000, 90),
('ven_013', 'FC Goa House', 'Porvorim', 1, 6500, 125),
('ven_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', 1, 6000, 120),
('ven_015', 'Churchill Brothers FC', 'Margao', 1, 7000, 130),
('ven_016', 'Youth Futsal Academy', 'Margao', 1, 5000, 100),
('ven_017', 'Salgaocar Football Club', 'Vasco da Gama', 1, 8000, 150),
('ven_018', 'Vasco Sports Club', 'Vasco da Gama', 1, 7000, 130),
('ven_019', 'Baina Football Academy', 'Vasco da Gama', 1, 5000, 100),
('ven_020', 'Chicalim Sports Complex', 'Vasco da Gama', 1, 6000, 120),
('ven_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 1, 4000, 90),
('ven_022', 'Majestic Futsal Turf', 'Ponda', 1, 5000, 100);

-- --------------------------------------------------------

--
-- Table structure for table `venue_reviews`
--

CREATE TABLE `venue_reviews` (
  `review_id` varchar(255) NOT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `ratings` int(11) DEFAULT NULL,
  `feedback_clm` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academys`
--
ALTER TABLE `academys`
  ADD PRIMARY KEY (`ac_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `academy_reviews`
--
ALTER TABLE `academy_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `ac_id` (`ac_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `announcement`
--
ALTER TABLE `announcement`
  ADD PRIMARY KEY (`ann_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `booking`
--
ALTER TABLE `booking`
  ADD PRIMARY KEY (`bk_date`,`user_email`,`venue_id`),
  ADD KEY `user_email` (`user_email`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`o_email`);

--
-- Indexes for table `referee`
--
ALTER TABLE `referee`
  ADD PRIMARY KEY (`ref_id`);

--
-- Indexes for table `teams`
--
ALTER TABLE `teams`
  ADD PRIMARY KEY (`team_nm`,`tr_id`),
  ADD KEY `tr_id` (`tr_id`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`tr_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`);

--
-- Indexes for table `venue_reviews`
--
ALTER TABLE `venue_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academys`
--
ALTER TABLE `academys`
  ADD CONSTRAINT `academys_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `academy_reviews`
--
ALTER TABLE `academy_reviews`
  ADD CONSTRAINT `academy_reviews_ibfk_1` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`),
  ADD CONSTRAINT `academy_reviews_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `announcement`
--
ALTER TABLE `announcement`
  ADD CONSTRAINT `announcement_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `booking`
--
ALTER TABLE `booking`
  ADD CONSTRAINT `booking_ibfk_1` FOREIGN KEY (`user_email`) REFERENCES `user` (`email`),
  ADD CONSTRAINT `booking_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tournaments` (`tr_id`);

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `tournaments_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);

--
-- Constraints for table `venue_reviews`
--
ALTER TABLE `venue_reviews`
  ADD CONSTRAINT `venue_reviews_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
