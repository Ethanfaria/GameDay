-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 10:28 PM
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
  `venue_id` varchar(255) DEFAULT NULL,
  `admy_img` varchar(255) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `timings` varchar(255) DEFAULT NULL,
  `days` varchar(255) DEFAULT NULL,
  `feature1` varchar(255) DEFAULT NULL,
  `feature2` varchar(255) DEFAULT NULL,
  `feature3` varchar(255) DEFAULT NULL,
  `duration` int(11) DEFAULT NULL,
  `owner_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academys`
--

INSERT INTO `academys` (`ac_id`, `aca_nm`, `ac_location`, `ac_charges`, `venue_id`, `admy_img`, `level`, `age_group`, `description`, `timings`, `days`, `feature1`, `feature2`, `feature3`, `duration`, `owner_email`) VALUES
('ac_001', 'Sesa Football Academy', 'Panjim', 5000, 'ven_001', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0rQeDT8x-XWdoXOUoKJmF7RHkbNondCca8w&', 'Advanced', 'U-18', 'A premier football academy known for its elite coaching staff and state-of-the-art facilities. Focused on developing future professional players.', '06:00 AM - 08:00 AM', 'Monday - Friday', 'Artificial Turf', 'Fitness Center', 'Youth Development Program', 5, NULL),
('ac_002', 'Sporting Clube de Goa Football Academy', 'Panjim', 4000, 'ven_002', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRLCi8nLjCMGK6UtodWL0TRHDKS-6rKEhJgdA&s', 'Intermediate', 'U-15', 'A well-established football academy dedicated to nurturing young players with structured training programs and competitive match experience.', '07:30 AM - 09:30 AM', 'Tuesday, Thursday, Saturday', 'Floodlights', 'Technical Drills', 'Customized Training Modules', 12, NULL),
('ac_003', 'M7 Football Club', 'Panjim', 6000, 'ven_003', 'https://ss-i.thgim.com/public/incoming/article37461949.ece/alternates/LANDSCAPE_1200/fc-goajfif', 'Professional', 'Open', 'An advanced football club and academy that provides professional-level training for aspiring footballers aiming for national and international leagues.', '05:00 PM - 07:00 PM', 'Weekends Only', 'Indoor Training Facility', 'Tactical Development', 'Game Awareness Coaching', 10, NULL),
('ac_006', 'Geno Sports Club', 'Mapusa', 7000, 'ven_006', 'https://livenewsgoa.com/wp-content/uploads/2022/05/InShot_20220513_234730699-scaled.jpg', 'Professional', 'Open', 'A grassroots football academy committed to developing young players with a focus on technical excellence and teamwork.', '07:00 AM - 09:00 AM', 'Tuesday - Thursday - Saturday', 'Physiotherapy Center', 'Sports Psychology', 'High-Intensity Training', 11, NULL),
('ac_007', 'Laxmi Prasad Sports Club', 'Mapusa', 4800, 'ven_007', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJ49umkzrhdWTaXBJTW9Yfrvor4RiikKRH0w&s ', 'Advanced', 'U-14', 'A football academy focused on building discipline, teamwork, and game intelligence in young athletes.', '05:30 PM - 07:30 PM', 'Weekends Only', 'Nutrition Guidance', 'Strength Training', 'Cross-Training with Other Sports', 2, NULL),
('ac_008', 'Cunchelim Sports Club', 'Mapusa', 5200, 'ven_008', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSCw8nReJoBLCvERvMLG0CZunt9_KpFSbo98w&s', 'Advanced', 'U-18', 'A prestigious football academy offering year-round training for competitive players looking to enhance their technical and tactical skills.', '06:30 AM - 08:30 AM', 'Monday - Wednesday - Friday', 'Video Analysis', 'AI Performance Analysis', 'Advanced Ball Control Drills', 2, NULL),
('ac_009', 'Calangute Football Association', 'Calangute', 3800, 'ven_009', 'https://www.arunfoot.com/wp-content/uploads/2016/08/FC-Bardez-practice.jpg', 'Intermediate', 'U-13', 'A community-driven football academy that provides an inclusive and fun learning environment for beginners and experienced players alike.', '04:30 PM - 06:30 PM', 'Monday - Friday', 'Skill Development Workshops', 'Small-Sided Games', 'Match Readiness Assessments', 2, NULL),
('ac_010', 'United Football Academy', 'Calangute', 4200, 'ven_010', 'https://www.thegoan.net/uploads/news/big_74140_SGU.jpg', 'Advanced', 'U-17', 'A high-performance football academy that integrates cutting-edge sports technology and innovative training methods.', '08:00 AM - 10:00 AM', 'Sundays Only', 'Speed & Agility Training', 'Weekend Training Camps', 'Elite Mentorship Programs', 4, NULL),
('ac_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', 8000, 'ven_011', 'https://www.thegoan.net/uploads/news/big_118186_IMG_3993.jpeg', 'Professional', 'Open', 'A specialized futsal academy focusing on fast-paced, skill-based training to develop quick-thinking and agile players.', '06:00 PM - 08:00 PM', 'Tuesday - Thursday', 'Goalkeeper Training', 'Women’s Football Training', 'Tactical Communication Training', 3, NULL),
('ac_012', 'Candolim Sports Club', 'Candolim', 5500, 'ven_012', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTlghsqHHubkW-ApjynFeSxrUPAQ02vHpPziw&s', 'Advanced', 'U-18', 'An academy that blends traditional football training with modern sports science to enhance player performance and longevity.', '07:00 PM - 09:00 PM', 'Weekends Only', 'Personalized Training Plans', 'Overseas Exchange Programs', 'Post-Match Performance Reviews', 2, NULL),
('ac_013', 'FC Goa House', 'Porvorim', 3000, 'ven_013', 'https://fcgoa.in/wordpress/wp-content/uploads/2024/08/FC-Goa-kick-off-2024-25-season-in-style-with-Gaur-Fest-Article-Banner-1180x350.png', 'Beginner', 'U-10', 'A youth-focused football academy that promotes teamwork, fitness, and skill-building through structured coaching programs.', '06:00 AM - 07:30 AM', 'Monday - Friday', 'Strength & Conditioning', 'Multi-Sport Facilities', 'Position-Specific Training', 11, NULL),
('ac_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', 7500, 'ven_014', 'https://content.jdmagicbox.com/comp/belgaum/a9/9999px831.x831.230901155656.z6a9/catalogue/belgaum-united-football-academy-belgaum-football-clubs-ozyixxltrx.jpg', 'Professional', 'Open', 'An elite football institution that works closely with professional clubs to identify and develop top-tier talent.', '05:00 PM - 06:30 PM', 'Wednesday - Saturday', 'Match Simulations', 'High-Performance Diet Plans', 'Sports Science Workshops', 1, NULL),
('ac_015', 'Churchill Brothers FC', 'Margao', 6800, 'ven_015', 'https://i2.wp.com/theawayend.co/wp-content/uploads/2018/09/churchill-brothers-the-bridge.jpg?fit=1920%2C1080', 'Advanced', 'U-19', 'A dynamic football academy known for its competitive training and emphasis on mental conditioning.', '06:00 AM - 08:00 AM', 'Monday - Wednesday - Friday', 'Scouting Programs', 'Club Trials Preparation', 'Match Exposure & Experience', 7, NULL),
('ac_016', 'Youth Futsal Academy', 'Margao', 4000, 'ven_016', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSDs6ANYg4vg-c40yoR0GmQymwCza1PH80Saw&s', 'Intermediate', 'U-15', 'A school of excellence in football that prepares players for club trials and professional-level games.', '07:00 AM - 09:00 AM', 'Tuesday - Thursday - Saturday', 'Professional Coaching', 'Team Strategy Building', 'Youth National Level Competitions', 8, NULL),
('ac_017', 'Salgaocar Football Club', 'Vasco da Gama', 9000, 'ven_017', 'https://akm-img-a-in.tosshub.com/indiatoday/images/story/201606/salgaocar_fbsport_647_062416105958.jpg?VersionId=Xh4IzWR1xW5i1jOpoloObnrrrFukr4au', 'Professional', 'Open', 'An international-standard football academy providing top-class training with opportunities for scholarships and overseas exposure.', '04:00 PM - 06:00 PM', 'Monday - Friday', 'Rehabilitation Support', 'Professional Contracts Assistance', 'Mental Strength Workshops', 6, NULL),
('ac_018', 'Vasco Sports Club', 'Vasco da Gama', 4700, 'ven_018', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR87z-RW4mkpiV1EHyd_hwYncj-GnJLsN9LuA&s', 'Advanced', 'U-16', 'A football learning center designed to help players improve their game through innovative training modules and expert guidance.', '05:30 PM - 07:30 PM', 'Sundays Only', 'Scholarship Programs', 'Elite-Level Training', 'Tournament Participation', 6, NULL),
('ac_019', 'Baina Football Academy', 'Vasco da Gama', 6200, 'ven_019', 'https://www.navhindtimes.in/wp-content/uploads/2023/03/15rising.jpeg.jpg', 'Advanced', 'U-18', 'A cutting-edge football academy offering professional training with a focus on fitness, speed, and endurance.', '06:30 AM - 08:30 AM', 'Tuesday - Thursday', 'One-on-One Training', 'Defensive Drills', 'Agility & Quickness Programs', 1, NULL),
('ac_020', 'Chicalim Sports Complex', 'Vasco da Gama', 3500, 'ven_020', 'https://content3.jdmagicbox.com/v2/comp/goa/d2/0832px832.x832.231108200624.c9d2/catalogue/vasco-united-football-academy-chicalim-goa-football-clubs-4o7pig0h1g.jpg', 'Beginner', 'U-12', 'An academy for budding footballers that emphasizes technical ability and strategic thinking in the game.', '04:30 PM - 06:30 PM', 'Monday - Wednesday - Friday', 'Club Partnerships', 'Footwork Precision Training', 'Game Intelligence Analysis', 10, NULL),
('ac_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 5000, 'ven_021', 'https://static.footballcounter.com/wp-content/uploads/2021/06/10000330/DSouza-Football-Academy.jpg ', 'Intermediate', 'U-17', 'A progressive football academy aimed at producing top-quality players through skill-based learning and match experiences.', '08:00 AM - 10:00 AM', 'Weekends Only', 'Live Match Analysis', 'Tactical Formations', 'Player Development Reports', 9, NULL),
('ac_67d90c7d9a9a7', 'Boscos', 'Panjim', 15000, 'ven_67d904645de79', NULL, 'intermediate', 'teens', 'help', NULL, NULL, 'coaches', 'video', 'training', 2, NULL),
('ac_67dae7c0508fb', 'Boscos2', 'Panjim', 2500, 'ven_67d904645de79', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', 'intermediate', 'teens', '', '6-9', 'monday', 'coaches', 'video', 'training', 2, NULL),
('ac_67e4dcd0c1fea', 'BOscos3', 'Panjim', 5000, 'ven_016', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', 'intermediate', 'teens', 'Test', '10:40 AM - 04:41 PM', 'Monday, Tuesday, Wednesday', 'coaches', 'video', 'training', 3, 'vowner@gameday.com'),
('ac_67e4f9fdd3d6b', 'academy_1', 'Panjim', 500, 'ven_001', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', 'intermediate', 'teens', 'test', '08:00 AM - 10:00 AM', 'Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday', 'coaches', 'video', 'training', 3, 'vowner@gameday.com'),
('ac_67e50706a3408', 'academy_2', 'Panjim', 500, 'ven_002', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', 'intermediate', 'kids', 'test2', '07 PM - 08 PM', 'Monday, Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday', 'coaches', 'video', 'training', 3, 'vowner@gameday.com'),
('ac_67ee3c82624bf', 'test academy', 'margao', 3000, 'ven_042', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', 'professional', 'teens', 'description', '11 AM - 07 AM', 'Tuesday, Wednesday, Thursday, Friday, Saturday, Sunday', 'coaches', 'video', 'training', 12, 'owner@gmail.com');

-- --------------------------------------------------------

--
-- Table structure for table `academy_reviews`
--

CREATE TABLE `academy_reviews` (
  `a_review_id` varchar(255) NOT NULL,
  `a_reviews` mediumtext NOT NULL,
  `a_ratings` int(1) NOT NULL,
  `ac_id` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `admin_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`admin_id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@gameday.com', '$2y$10$vJYy3AlvT3rrJ4ZQkxVbZ.5UtQbuBCd.zKcCaI7SwJaESw6wSJM4O');

-- --------------------------------------------------------

--
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `booking_id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `venue_id` varchar(50) NOT NULL,
  `bk_date` date NOT NULL,
  `bk_dur` varchar(50) NOT NULL,
  `referee_email` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','declined','confirmed','cancelled') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`booking_id`, `email`, `venue_id`, `bk_date`, `bk_dur`, `referee_email`, `status`) VALUES
(1, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-14', '8:00 AM - 9:00 AM', NULL, 'pending'),
(4, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-14', '12:00 PM - 1:00 PM', NULL, 'pending'),
(7, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-14', '11:00 AM - 12:00 PM', NULL, 'pending'),
(9, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-14', '3:00 PM - 4:00 PM', NULL, 'pending'),
(13, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-14', '10:00 AM - 11:00 AM', NULL, 'pending'),
(18, 'Ethanfaria@gmail.com', 'ven_002', '2025-03-14', '11:00 AM - 12:00 PM', NULL, 'pending'),
(22, 'Ethanfaria@gmail.com', 'ven_002', '2025-03-14', '1:00 PM - 2:00 PM', NULL, 'pending'),
(26, 'Ethanfaria@gmail.com', 'ven_002', '2025-03-15', '11:00 AM - 12:00 PM', NULL, 'pending'),
(28, 'Ethanfaria@gmail.com', 'ven_002', '2025-03-14', '8:00 AM - 9:00 AM', NULL, 'pending'),
(29, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-17', '3:00 PM - 4:00 PM', NULL, 'pending'),
(32, 'Ethanfaria@gmail.com', 'ven_014', '2025-03-22', '2:00 PM - 3:00 PM', NULL, 'pending'),
(33, 'Ethanfaria@gmail.com', 'ven_005', '2025-03-22', '1:00 PM - 2:00 PM', NULL, 'pending'),
(34, 'Ethanfaria@gmail.com', 'ven_006', '2025-03-19', '10:00 AM - 11:00 AM', NULL, 'pending'),
(35, 'Ethanfaria@gmail.com', 'ven_008', '2025-03-21', '12:00 PM - 1:00 PM', NULL, 'pending'),
(36, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-17', '9:00 PM - 10:00 PM', NULL, 'pending'),
(37, 'Ethanfaria@gmail.com', 'ven_67d8feb321ab9', '2025-03-21', '10:00 AM - 11:00 AM', NULL, 'pending'),
(38, 'user@gameday.com', 'ven_001', '2025-03-19', '9:00 PM - 10:00 PM', NULL, 'pending'),
(39, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-28', '11:00 AM - 12:00 PM', NULL, 'pending'),
(40, 'maria@gmail.com', 'ven_008', '2025-03-26', '7:00 AM - 8:00 AM', NULL, 'pending'),
(41, 'maria@gmail.com', 'ven_008', '2025-03-26', '9:00 AM - 10:00 AM', NULL, 'pending'),
(42, 'maria@gmail.com', 'ven_002', '2025-03-26', '10:00 AM - 11:00 AM', NULL, 'pending'),
(43, 'Ethanfaria@gmail.com', 'ven_001', '2025-03-27', '5:00 PM - 6:00 PM', NULL, 'pending'),
(45, 'ethanfaria@gmail.com', 'ven_001', '2025-03-27', '6:00 PM - 7:00 PM', NULL, 'pending'),
(47, 'ethanfaria@gmail.com', 'ven_004', '2025-03-28', '6:00 AM - 7:00 AM', NULL, 'pending'),
(49, 'ethanfaria@gmail.com', 'ven_001', '2025-03-30', '10:00 AM - 11:00 AM', NULL, 'pending'),
(50, 'ethanfaria@gmail.com', 'ven_67e297bacb3fd', '2025-03-30', '10:00 AM - 11:00 AM', NULL, 'pending'),
(51, 'ethanfaria@gmail.com', 'ven_67e297bacb3fd', '2025-03-28', '9:00 PM - 10:00 PM', NULL, 'pending'),
(53, 'ethanfaria@gmail.com', 'ven_008', '2025-04-01', '10:00 AM - 11:00 AM', 'referee@gameday.com', 'pending'),
(54, 'ethanfaria@gmail.com', 'ven_001', '2025-03-30', '2:00 PM - 3:00 PM', NULL, 'pending'),
(57, 'ethanfaria@gmail.com', 'ven_001', '2025-03-30', '4:00 PM - 5:00 PM', NULL, 'pending'),
(58, 'ethanfaria@gmail.com', 'ven_002', '2025-04-03', '11:00 AM - 12:00 PM', 'referee@gameday.com', 'pending'),
(60, 'ethanfaria@gmail.com', 'ven_001', '2025-04-01', '1:00 PM - 2:00 PM', NULL, 'pending'),
(61, 'ethanfaria@gmail.com', 'ven_001', '2025-03-31', '2:00 PM - 3:00 PM', NULL, 'pending'),
(62, 'ethanfaria@gmail.com', 'ven_001', '2025-03-31', '5:00 PM - 6:00 PM', NULL, 'pending'),
(63, 'ethanfaria@gmail.com', 'ven_004', '2025-04-01', '9:00 AM - 10:00 AM', NULL, 'pending'),
(64, 'user1@gamil.com', 'ven_001', '2025-04-04', '10:00 AM - 11:00 AM', 'referee@gameday.com', 'pending'),
(65, 'ethanfaria@gmail.com', 'ven_001', '2025-04-07', '10:00 AM - 11:00 AM', NULL, 'pending'),
(66, 'Ethanfaria@gmail.com', 'ven_001', '2025-04-07', '11:00 AM - 12:00 PM', NULL, 'pending'),
(67, 'ethanfaria@gmail.com', 'ven_001', '2025-04-08', '4:00 PM - 5:00 PM', NULL, 'pending'),
(68, 'ethanfaria@gmail.com', 'ven_001', '2025-04-08', '5:00 PM - 6:00 PM', NULL, 'pending'),
(69, 'ethanfaria13@gmail.com', 'ven_001', '2025-04-09', '10:00 AM - 11:00 AM', NULL, 'pending'),
(70, 'ethanfaria13@gmail.com', 'ven_001', '2025-04-08', '6:00 PM - 7:00 PM', NULL, 'pending'),
(71, 'ethanfaria@gmail.com', 'ven_001', '2025-04-08', '9:00 PM - 10:00 PM', NULL, 'pending'),
(72, 'ethanfaria@gmail.com', 'ven_001', '2025-04-12', '11:00 AM - 12:00 PM', 'referee@gameday.com', 'confirmed'),
(73, 'ethanfaria@gmail.com', 'ven_001', '2025-04-08', '10:00 AM - 11:00 AM', NULL, 'pending'),
(74, 'ethanfaria@gmail.com', 'ven_001', '2025-04-11', '12:00 PM - 1:00 PM', NULL, 'pending'),
(75, 'ethanfaria@gmail.com', 'ven_006', '2025-04-12', '11:00 AM - 12:00 PM', 'referee@gameday.com', 'pending'),
(76, 'ethanfaria@gmail.com', 'ven_006', '2025-04-11', '10:00 AM - 11:00 AM', 'akshaya.shetgaonkar@gmail.com', 'pending'),
(77, 'ethanfaria1@gmail.com', 'ven_001', '2025-04-09', '3:00 PM - 4:00 PM', 'adesh.palni@gmail.com', 'pending'),
(78, 'ethanfaria1@gmail.com', 'ven_002', '2025-04-09', '4:00 PM - 5:00 PM', 'referee@gameday.com', 'confirmed');

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--

CREATE TABLE `enroll` (
  `en_id` varchar(100) NOT NULL,
  `ac_id` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `en_dur` int(11) DEFAULT NULL,
  `en_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enroll`
--

INSERT INTO `enroll` (`en_id`, `ac_id`, `email`, `en_dur`, `en_date`) VALUES
('', 'ac_002', 'maria@gmail.com', 3, '0000-00-00'),
('en_67e4bc195ce1a', 'ac_011', 'Ethanfaria@gmail.com', 3, '2025-03-27'),
('en_67e51dbc85cf2', 'ac_001', 'vowner@gameday.com', 3, '2025-03-27'),
('en_67e628481b678', 'ac_001', 'ethanfaria@gmail.com', 3, '2025-03-28'),
('en_67ee350fcf03d', 'ac_001', 'user1@gamil.com', 3, '2025-04-03'),
('en_67f4e5dcbefc5', 'ac_001', 'ethanfaria13@gmail.com', NULL, '2025-04-08'),
('en_67f5914794ec1', 'ac_003', 'ethanfaria@gmail.com', NULL, '2025-04-08'),
('en_67f5930e43cfd', 'ac_002', 'ethanfaria@gmail.com', 12, '2025-04-08'),
('en_67f5a27a8f585', 'ac_010', 'ethanfaria@gmail.com', 4, '2025-04-09'),
('en_67f5a2c750def', 'ac_008', 'ethanfaria@gmail.com', 2, '2025-04-09'),
('en_67f5f7614b79b', 'ac_001', 'ethanfaria1@gmail.com', 5, '2025-04-09');

-- --------------------------------------------------------

--
-- Table structure for table `referee`
--

CREATE TABLE `referee` (
  `ref_id` varchar(255) NOT NULL,
  `ref_location` varchar(255) DEFAULT NULL,
  `ref_pic` varchar(255) DEFAULT NULL,
  `charges` double DEFAULT NULL,
  `yrs_exp` int(11) DEFAULT NULL,
  `referee_email` varchar(255) DEFAULT NULL,
  `booking_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referee`
--

INSERT INTO `referee` (`ref_id`, `ref_location`, `ref_pic`, `charges`, `yrs_exp`, `referee_email`, `booking_id`) VALUES
('ref_001', 'Goa Velha', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1679647008.jpg', 1500, 5, 'aaron.cardoso@gmail.com', NULL),
('ref_002', 'Marcel', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1656239515.jpg', 1200, 3, 'adesh.palni@gmail.com', NULL),
('ref_003', 'Pernem', 'http://refereeadmin.the-aiff.com/uploads/refree_img/21151732665b8059b2af50a.JPG', 1800, 7, 'akshaya.shetgaonkar@gmail.com', NULL),
('ref_004', 'Margao Goa', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1672555312.jpg', 1000, 2, 'aliston.fernandes@gmail.com', NULL),
('ref_005', 'Ponda', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1729436483.jpg', 1400, 4, 'atharv.desai@gmail.com', NULL),
('ref_006', 'Mapusa', 'http://refereeadmin.the-aiff.com/uploads/refree_img/9134725215b3c63b5b2e63.jpg', 2000, 10, 'bhavesh.sawant@gmail.com', NULL),
('ref_007', 'Taleigao', 'http://refereeadmin.the-aiff.com/uploads/refree_img/844127305575583dde44b2.jpg', 1700, 6, 'celrich.almeida@gmail.com', NULL),
('ref_008', 'Mapusa', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1688806088.jpg', 1300, 3, 'joseph.lobo@gmail.com', NULL),
('ref_009', 'Goa', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1656216376.jpg', 1600, 5, 'jayesh.kadam@gmail.com', NULL),
('ref_010', 'Sanquelim', 'http://refereeadmin.the-aiff.com/uploads/refree_img/10076977065dcbfbcd76dbc.jpg', 1100, 2, 'kashinath.kamat@gmail.com', NULL),
('ref_011', 'Margao', 'http://refereeadmin.the-aiff.com/uploads/refree_img/3301226015ce23f3ef40d4.jpg', 1900, 8, 'leroy.sequeira@gmail.com', NULL),
('ref_012', 'Panaji', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1715837297.jpeg', 1250, 3, 'naved.almeida@gmail.com', NULL),
('ref_67e1aaf334486', 'Panjim', 'https://thispersondoesnotexist.com', 500, 7, 'referee@gameday.com', NULL),
('ref_67e43ba12755f', 'Panjim', 'https://plus.unsplash.com/premium_photo-1689568126014-06fea9d5d341?fm=jpg&q=60&w=3000&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8MXx8cHJvZmlsZXxlbnwwfHwwfHx8MA%3D%3D', 1000, 5, 'john@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `reg_id` varchar(10) NOT NULL,
  `tr_id` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `team_nm` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `register`
--

INSERT INTO `register` (`reg_id`, `tr_id`, `email`, `team_nm`) VALUES
('REG4576', 't_006', 'ethanfaria1@gmail.com', NULL),
('REG6973', 't_005', 'ethanfaria@gmail.com', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tournaments`
--

CREATE TABLE `tournaments` (
  `tr_id` varchar(255) NOT NULL,
  `tr_time` varchar(255) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `tr_name` varchar(255) DEFAULT NULL,
  `ac_id` varchar(10) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `entry_fee` decimal(10,2) DEFAULT NULL,
  `prize` decimal(10,2) DEFAULT NULL,
  `players_per_team` int(11) DEFAULT NULL,
  `max_teams` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`tr_id`, `tr_time`, `start_date`, `end_date`, `venue_id`, `tr_name`, `ac_id`, `img_url`, `description`, `entry_fee`, `prize`, `players_per_team`, `max_teams`) VALUES
('t_001', '10:00 AM - 11:00 AM', '2025-02-01', '2025-02-28', 'ven_001', 'CAM Goa Pro League', NULL, 'https://www.gfagoa.com/media/logos/leagues/WhatsApp_Image_2023-08-28_at_20.00.50_1.png', 'The premier football league in Goa, featuring the top teams battling for supremacy. High competition and intense matches await!', 1000.00, 50000.00, 5, 0),
('t_002', '11:30 AM - 12:30 PM', '2025-03-01', '2025-03-20', 'ven_002', 'GFA U-15 Division 1', NULL, 'https://www.gfagoa.com/media/logos/leagues/13.png', 'A prestigious youth league designed for U-15 players. This tournament is a breeding ground for future football stars in Goa.', 800.00, 25000.00, 5, 0),
('t_003', '1:00 PM - 2:00 PM', '2025-04-01', '2025-04-15', 'ven_003', 'Bhaisaheb Bandodkar Memorial Trophy', NULL, 'https://www.gfagoa.com/media/logos/leagues/4322ce59-dd4a-4bd2-83c1-87eea6d3c679.png', 'A historic memorial tournament honoring Goa’s football legacy. Teams compete fiercely to claim the coveted trophy.', 1200.00, 60000.00, 5, 10),
('t_005', '4:00 PM - 5:00 PM', '2025-06-01', '2025-06-15', 'ven_005', 'GFA U-13 League Division 1', NULL, 'https://www.gfagoa.com/media/logos/leagues/12.png', 'A developmental league focused on U-13 players, providing them with the first taste of professional football tournaments.', 600.00, 15000.00, 5, 0),
('t_006', '5:30 PM - 6:30 PM', '2025-07-01', '2025-07-31', 'ven_006', 'First Division League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A high-stakes first-division league where clubs showcase their tactical prowess and compete for major honors in Goa.', 1500.00, 70000.00, 5, 0),
('t_008', '8:30 AM - 9:30 AM', '2025-09-01', '2025-09-15', 'ven_008', 'Summer Sports League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A summer football league designed to keep players in peak form during the off-season. Open for all levels.', 500.00, 10000.00, 5, 0),
('t_009', '9:45 AM - 10:45 AM', '2025-10-01', '2025-10-05', 'ven_009', 'Goa Sports Challenge', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'An elite sports challenge where the best athletes from across Goa compete in football and other disciplines.', 750.00, 25000.00, 5, 0),
('t_010', '12:00 PM - 1:00 PM', '2025-06-01', '2025-07-15', NULL, 'Urban Sports League', NULL, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKZDuuMF7utZJ57AsqI6jkdP7oPdTBO6Eawg&s', 'A high-energy urban football league, bringing the excitement of street football to an organized competition.', 600.00, 18000.00, 5, 0),
('t_011', '3:15 PM - 4:15 PM', '2025-07-10', '2025-08-20', NULL, 'Autumn Sports Championship', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'An autumn championship that brings together top-tier teams for a battle of skill, endurance, and strategy.', 950.00, 28000.00, 5, 0),
('t_012', '6:00 PM - 7:00 PM', '2025-08-05', '2025-09-10', NULL, 'CAM GFA Girls League', NULL, 'https://www.gfagoa.com/media/logos/leagues/cam.png', 'A girls-only football tournament designed to nurture young female talent in Goa and promote gender inclusivity in sports.', 400.00, 12000.00, 5, 0),
('t_013', '7:45 PM - 8:45 PM', '2025-09-01', '2025-10-05', NULL, 'Charity Match', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A special charity match held annually to support community initiatives, featuring professional and amateur players alike.', 300.00, 8000.00, 5, 0),
('t_014', '9:00 AM - 10:00 AM', '2025-10-10', '2025-11-15', NULL, 'Laye GFA Futsal League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo_TCZ01QP.png', 'An exciting futsal league with fast-paced matches, showcasing the finest futsal talent in the region.', 700.00, 22000.00, 5, 0),
('t_015', '09 AM - 12 PM', '2025-03-28', '2025-04-05', 'ven_67e297bacb3fd', 'Tournament-1', NULL, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMSEhUTExMWFhUXFRgVFhYYFRcVFhYXFRgXFxcYFxgYHSggGBolGxYVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGy0lHyUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAMIBAwMBEQACEQEDEQH/', 'text', 500.00, 5000.00, 5, 10),
('t_016', '07 AM - 12 PM', '2025-03-28', '2025-04-05', 'ven_67e297bacb3fd', 'Tournament-1', NULL, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMSEhUTExMWFhUXFRgVFhYYFRcVFhYXFRgXFxcYFxgYHSggGBolGxYVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGy0lHyUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAMIBAwMBEQACEQEDEQH/', 'text', 500.00, 5000.00, 5, 10),
('t_017', '01 PM - 05 PM', '2025-04-09', '2025-04-10', 'ven_67e297bacb3fd', 'Tournament-1', NULL, 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMSEhUTExMWFhUXFRgVFhYYFRcVFhYXFRgXFxcYFxgYHSggGBolGxYVITEhJSkrLi4uFx8zODMtNygtLisBCgoKDg0OGhAQGy0lHyUtLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIAMIBAwMBEQACEQEDEQH/', 'details', 1000.00, 50000.00, 10, 20);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_name` varchar(255) DEFAULT NULL,
  `user_ph` varchar(15) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) DEFAULT NULL,
  `user_type` enum('normal','owner','admin','referee') NOT NULL DEFAULT 'normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_name`, `user_ph`, `email`, `password`, `user_type`) VALUES
('12345melrick', '0', '12345$@gnail.com', '$2y$10$zcYbA3tPvie9A5vr513zWO0zpBDr3G3YlCehG5QtXW87hEDA80X1a', 'normal'),
('Aaron Abel Cardoso', '9876543210', 'aaron.cardoso@gmail.com', 'Aaron@123', 'referee'),
('Adesh Narayan Palni', '9876543211', 'adesh.palni@gmail.com', 'Adesh@123', 'referee'),
('Admin', '2147483647', 'admin@gameday.com', '$2y$10$WZqMWV2fMsLro4ww9gF90ezhqq.VWCBXnZE8JyL2aNzwlljrYEx0u', 'admin'),
('Akshaya Pundalik Shetgaonkar', '9876543212', 'akshaya.shetgaonkar@gmail.com', 'Akshaya@123', 'referee'),
('Aliston Fernandes', '9876543213', 'aliston.fernandes@gmail.com', 'Aliston@123', 'referee'),
('Atharv Desai', '9876543214', 'atharv.desai@gmail.com', 'Atharv@123', 'referee'),
('Bhavesh Bharat Sawant', '9876543215', 'bhavesh.sawant@gmail.com', 'Bhavesh@123', 'referee'),
('Celrich Amyster Almeida', '9876543216', 'celrich.almeida@gmail.com', 'Celrich@123', 'referee'),
('dkamat', '2147483647', 'dk@gmail.com', '$2y$10$CO0sxgnyLKPs88lvG0cnjufocDI432HHv.1f24HA4yPC5KSZKP6sK', 'normal'),
('Ethan', '3213214232', 'ethanfaria13@gmail.com', '$2y$10$JklTB2NikVL5QRno8Ikr2.XkRxIQYMkvIPH5jv8bxXjjgNHxQsWCy', 'normal'),
('Ethan', '2147483647', 'ethanfaria14@gmail.com', '$2y$10$Z0b7Eivx4yj9VWjGv0b6gu/BsIAyNL5viTi24g7mr0qMAJ3./T49C', 'normal'),
('Ethan', '3453412324', 'ethanfaria1@gmail.com', '$2y$10$VagvvyYcbjqAueNQEKYwweVUChoUg3bYiLgDQr6cRmgOg6cYzf2Jm', 'normal'),
('Ethan', '2147483647', 'ethanfaria@gmail.com', '$2y$10$eLnCUgSgfEpCFvsc1lngpup.8pr/n9GcZOJkFUIR8C3x6DzaR1EbO', 'normal'),
('Jayesh Kadam', '9876543218', 'jayesh.kadam@gmail.com', 'Jayesh@123', 'referee'),
('John', '2147483647', 'john@gmail.com', '$2y$10$nA5/tN.Ty2XMoam1irbiherFfy0QCQU8gM0sawViPSYO6gI1WsFS6', 'referee'),
('Joseph Lobo', '9876543217', 'joseph.lobo@gmail.com', 'Joseph@123', 'referee'),
('Kashinath Vithal Kamat', '9876543219', 'kashinath.kamat@gmail.com', 'Kashinath@123', 'referee'),
('Leroy Gonzaga Sequeira', '9876543220', 'leroy.sequeira@gmail.com', 'Leroy@123', 'referee'),
('Maria', '2147483647', 'maria@gmail.com', '$2y$10$Te/VEwf/diVgSLhPifageuGjUGnO5/EKsV7CdxY.05VX6ucVGBAim', 'normal'),
('Naved Francis Almeida', '9876543221', 'naved.almeida@gmail.com', 'Naved@123', 'referee'),
('Owner', '1122334455', 'owner@gmail.com', '$2y$10$Pej2okvCzYcaRHF8gVw0IOEfb5eCMGL2DbAu3EFS8H0QiMrG8zEkS', 'owner'),
('Referee', '2147483647', 'referee@gameday.com', '$2y$10$WL.TMZZVUTBYzCvj9t5nrOmOFa9xijMLUH0LEC55QReIjqb53V4hC', 'referee'),
('samay', '1234567890', 'samay@gmail.com', 'samay123', 'normal'),
('sairajsirsat', '1234567890', 'ss@gmail.com', '$2y$10$lMdNsgNUkEUeikXDM5iB6eOKgSJ3zuS3O3vgSuY2GNLAR.V.mVMW2', 'normal'),
('User1', '1334567890', 'user1@gamil.com', '$2y$10$8bKlSoun.btuM5JMntILVO92brNUgZ6XQ.VeuCQnjeYGrlsO/6oZ6', 'normal'),
('User', '2147483647', 'user@gameday.com', '$2y$10$4.1Z/FFZ5wgfs.XyQ/JdKuIDm02OQn6oOf..UKF0LpZO6HL9H6pYO', 'normal'),
('ved', '2147483647', 'vedk2004@gmail.com', '$2y$10$Q/dVhIsvopjl.mmvAfkHnOSlLGHCGXMi22Jp77ESh0.KRFcZB0AQy', 'normal'),
('V_Owner', '2147483647', 'vowner@gameday.com', '$2y$10$sMXJ.Vw4TBWuA9EROeMgkOfnQqVJaZextRo6dhstf3IXF5pezmAxS', 'owner');

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
  `size` varchar(255) DEFAULT NULL,
  `turf_img` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amenity1` varchar(255) DEFAULT NULL,
  `amenity2` varchar(255) DEFAULT NULL,
  `amenity3` varchar(255) DEFAULT NULL,
  `owner_email` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_nm`, `location`, `availability`, `price`, `size`, `turf_img`, `description`, `amenity1`, `amenity2`, `amenity3`, `owner_email`) VALUES
('ven_001', 'Play Goa', 'Panjim', 1, 800, '25m x 15m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTj3Fi_lngrUiWu0ZvFmV_0xxmYYf7Smo29Tw&s', 'A top-tier futsal arena with high-quality turf and professional lighting.', 'Floodlights', 'Synthetic Grass', 'Flexible Booking Options', NULL),
('ven_002', 'Don Bosco Futsal', 'Panjim', 1, 1200, '30m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNOYRVk51Qqqmi2ZIaDA2WcD44jcGBkb23LQ&s', 'Spacious football ground with well-maintained grass and seating areas.', 'Shaded Spectator Area', 'Locker Rooms', 'Automated Lighting System', NULL),
('ven_003', 'Dengoa', 'Panjim', 1, 1000, '28m x 16m', 'https://media.hudle.in/venues/5a267275-be88-494f-ada8-6716f90c3330/photo/b14d2548aa603e410eee9dee60386f4f9507ecc5', 'A compact and fast-paced futsal turf ideal for small-sided games.', 'Artificial Turf', 'LED Scoreboard', 'Weekly Tournaments Hosted', NULL),
('ven_004', 'Kicks N\' Flicks', 'Panjim', 1, 1400, '35m x 20m', 'https://content.jdmagicbox.com/comp/goa/q3/0832px832.x832.230913204513.f7q3/catalogue/kicks-n-flicks-goa-mini-football-fields-9soy7roh71.jpg', 'Popular football ground with a great atmosphere for local matches.', 'CCTV Security', 'Live Streaming Available', 'Music System', NULL),
('ven_005', 'Campal New Ground', 'Panjim', 1, 900, '32m x 18m', 'https://www.mappls.com/place/PJK11A_1662036443317_0.png', 'Floodlit futsal arena designed for intense 5-a-side matches.', 'Team Dugouts', 'Seating for 200+', 'Kids Play Area', NULL),
('ven_006', 'Dr Alvero Pinto Ground', 'Panjim', 1, 1600, '40m x 20m', 'https://content.jdmagicbox.com/comp/goa/i5/0832px832.x832.220317225912.u4i5/catalogue/nfa-nagoa-futsal-arena-saligao-goa-stadiums-xpsn1xzfdi.jpg', 'Well-kept football ground perfect for tournaments and training.', 'Scoreboard', 'On-site Cafeteria', 'Discounted Group Booking', NULL),
('ven_007', 'Bambolim Stadium', 'Panjim', 1, 2000, '42m x 22m', 'https://content.jdmagicbox.com/v2/comp/goa/f2/0832px832.x832.230430224856.w6f2/catalogue/agnel-futsal-arena-mapusa-goa-futsal-courts-mn568d6b4h-250.jpg', 'A multipurpose ground offering excellent playability for all skill levels.', 'Drinking Water Station', 'Ice Pack Availability', 'Night Matches Allowed', NULL),
('ven_008', 'Merces Football Ground', 'Panjim', 1, 2500, '38m x 18m', 'https://goemkarponn.com/wp-content/uploads/2024/09/Dnyanprassarak-Mandals-College.jpg', 'Premium futsal turf with soft padding to reduce player fatigue.', 'First Aid Kit', 'Surround Sound System', 'Fan Stand with TV Screen', NULL),
('ven_009', 'Santa Cruz Futsal Ground', 'Panjim', 1, 2200, '36m x 20m', 'https://media.hudle.in/venues/8e1f1ce9-b9e0-4142-a3a7-301044ba2c61/photo/e304c997efff4a22a6d4e7d4d4df3f5cfec153f9', 'A scenic football ground located in a peaceful setting.', 'Seating Area', 'Dedicated Warm-up Zone', 'Full-Sized Goals', NULL),
('ven_010', 'Duler Football Stadium', 'Mapusa', 1, 1100, '34m x 17m', 'https://content.jdmagicbox.com/comp/goa/a4/0832px832.x832.200930011201.j2a4/catalogue/poriat-football-ground-calangute-goa-sports-clubs-63186pzta2.jpg', 'Artificial turf with state-of-the-art drainage for all-weather play.', 'Changing Rooms', 'Artificial Grass Maintenance', 'No Footwear Restriction', NULL),
('ven_011', 'Ganeshpuri Football Ground', 'Mapusa', 1, 1700, '39m x 19m', 'https://www.mappls.com/place/48FD3D_1662033457955_0.png', 'Compact futsal ground with a vibrant atmosphere and spectator seating.', 'Restrooms', 'Mini Goalposts for Training', 'Freestyle Football Zone', NULL),
('ven_012', 'St Xavier Football Ground', 'Mapusa', 1, 1800, '41m x 21m', 'https://content3.jdmagicbox.com/comp/goa/i3/0832px832.x832.190517102700.g1i3/catalogue/o-jogo-goa-futsal-turf-porvorim-goa-sports-clubs-ovvewjbgnc.jpg', 'A community-friendly ground known for its weekend football tournaments.', 'Ball Rental Service', 'Family Seating Area', 'Exclusive Club Membership', NULL),
('ven_013', 'St Sabastiean Sports', 'Mapusa', 1, 1950, '37m x 20m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A well-lit and spacious futsal ground suited for intense matches.', 'Canteen', 'Turf Shoe Rental', 'Custom Jerseys Available', NULL),
('ven_014', 'Nagao Futsal Arena', 'Mapusa', 1, 1300, '31m x 17m', 'https://content.jdmagicbox.com/comp/goa/l5/0832px832.x832.220612020300.r8l5/catalogue/rosa-arena-airport-dabolim-goa-futsal-courts-df4jr2j21v.jpg', 'A traditional football ground with a history of hosting major local games.', 'Shower Facility', 'Floodlight Control Panel', 'Player Lockers', NULL),
('ven_015', 'Agnel Futsal Arena', 'Mapusa', 1, 2100, '40m x 22m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR__nhbZf-Wa_K-C2hbZipmtnKwlrCFzjcsCw&s', 'An advanced futsal facility with superior turf and modern amenities.', 'Footwear Cleaning Area', 'Energy Drinks on Sale', 'Private Parking', NULL),
('ven_016', 'Calangute Futsal', 'Calangute', 1, 600, '26m x 14m', 'https://content.jdmagicbox.com/comp/goa/d1/0832px832.x832.171225191400.a5d1/catalogue/sangolda-football-ground-sangolda-goa-sports-ground-rg4fpw2x5b.jpg', 'A football ground ideal for both casual and competitive matches.', 'Music System', 'Player Lounge', 'Live Score Updates', NULL),
('ven_017', 'Poriaot Football Ground', 'Calangute', 1, 750, '27m x 15m', 'https://content.jdmagicbox.com/comp/goa/e4/0832px832.x832.140222122030.f1e4/catalogue/duler-stadium-mapusa-goa-stadiums-2gcrdhafwj.jpg', 'State-of-the-art futsal pitch with enclosed walls for high-speed gameplay.', 'Free Wi-Fi', 'Security Personnel On-Site', 'Tournament Hosting Facilities', NULL),
('ven_018', 'Dr. Gustavo Monteiro Football Ground', 'Candolim', 1, 1450, '33m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgQFJj8jmDQMi-0giVqqQMXU4jaA_0PAWi_A&s', 'A football ground known for its great maintenance and sports culture.', 'Storage Lockers', 'Premium Sound System', 'Rest & Recovery Zone', NULL),
('ven_019', 'O Jogo, Goa Futsal', 'Porvorim', 1, 2300, '41m x 23m', 'https://campsouza.com/wp-content/uploads/2022/12/Untitled-design-2022-12-11T171410.858.jpg', 'A modern futsal arena equipped with high-tech goal-line technology.', 'Coaching Available', 'Dedicated Rest Area', 'Free Entry for Spectators', NULL),
('ven_020', 'First Strike Arena Football', 'Porvorim', 1, 1250, '29m x 16m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A professional training ground for serious football enthusiasts.', 'Umbrella Rentals', 'Extra Ball Storage', 'Vending Machines', NULL),
('ven_021', 'ATB Ground', 'Porvorim', 1, 2700, '42m x 25m', 'https://content.jdmagicbox.com/v2/comp/kottayam/y6/9999px481.x481.240418230251.f6y6/catalogue/tigers-turf-puthenangady-kottayam-football-clubs-d5qvkvlu1t-250.jpg', 'A multi-use futsal turf with excellent visibility and seating arrangements.', 'Private Lounge', 'High-Quality Turf Surface', 'Kids Training Camps', NULL),
('ven_022', 'Shriram Shrimati Siolim Futsal Arena', 'Siolim', 1, 1500, '34m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkSk4xHNTdeo6hyGnU9IvVYqC64Y3CbmzGkw&s', 'A competitive futsal ground featuring international standard turf.', 'VIP Seating', 'Social Media Streaming', 'Mobile App Booking', NULL),
('ven_023', 'St. Anthony\'s Church Ground', 'Siolim', NULL, 1200, '30m x 17m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzccj3ebRH_d39cWv-tymcHb9qLLRdv9kyeQ&s', 'A church-owned football ground fostering youth sports development.', 'Equipment Rental', 'Coach Viewing Stand', 'Climate Controlled Hall', NULL),
('ven_024', 'Chapora Football Ground', 'Siolim', NULL, 1300, '31m x 18m', 'https://content.jdmagicbox.com/comp/goa/a8/0832px832.x832.190912205053.b3a8/catalogue/mpt-grounds-vasco-da-gama-goa-sports-ground-k0676h6bsl.jpg', 'A football ground with a beautiful view and smooth grass surface.', 'Drinking Water Facility', 'Picnic Area Nearby', 'Premium Turf Grass', NULL),
('ven_025', 'Morjim Football Ground', 'Siolim', NULL, 1400, '36m x 20m', 'https://1.bp.blogspot.com/-YMOFUZcP8LY/UQCVac-f1SI/AAAAAAAAI8k/ExuKlrchsZw/s1600/Cortalim-23-%232-2.jpg', 'A beachside football ground offering a unique playing experience.', 'Food Stalls', 'Automated Sprinklers', 'FIFA Standard Goalposts', NULL),
('ven_026', 'Siolim Turf Ground', 'Siolim', NULL, 1100, '28m x 15m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJpPfZiSBJiqaSsmjQhJkBo8HpaPEzD1gWJA&s', 'A modern synthetic turf designed for optimal ball control and speed.', 'Ice Bath Facility', 'Team Dugout with Fans', 'Advanced Sprinkler System', NULL),
('ven_027', 'Siolim Football Turf', 'Siolim', NULL, 1250, '32m x 17m', 'https://content3.jdmagicbox.com/comp/goa/s1/0832px832.x832.220131235659.g4s1/catalogue/ella-ground-carambolim-goa-hlslvm5cr7.jpg', 'A neighborhood futsal turf known for friendly matches and leagues.', 'Hydration Station', 'Water Dispenser', 'Tactical Video Analysis', NULL),
('ven_028', 'Chowgule Sports Ground', 'Margao', NULL, 1600, '39m x 21m', 'https://content.jdmagicbox.com/comp/goa/s4/0832px832.x832.221218202837.w2s4/catalogue/majestic-futsal-turf-goa-futsal-courts-14pvsitj0m-250.jpg', 'A large sports complex accommodating multiple sports disciplines.', 'Kids Play Area', 'Multi-Sport Compatibility', 'Ball Return Nets', NULL),
('ven_029', 'First Strike Arena', 'Margao', NULL, 1350, '35m x 19m', 'https://content.jdmagicbox.com/comp/goa/a5/0832px832.x832.220921230217.s6a5/catalogue/first-strike-arena-goa-sports-clubs-p3l089gl0b.jpg', 'A compact and well-lit futsal arena, perfect for night matches.', 'Live Streaming Setup', 'Goalkeeper Training Net', 'Coach Viewing Platform', NULL),
('ven_030', 'Don Bosco Ground', 'Margao', NULL, 1450, '33m x 17m', 'https://media.hudle.in/venues/cc49b444-b8d8-485d-aab4-4bcb5e1549a1/photo/b46e9a176bb3fb9ee9f2575d285753227b523b70', 'A historic football ground that has hosted legendary local matches.', 'Backup Generator', 'Tactical Discussion Board', 'Player Lounge Access', NULL),
('ven_031', 'Camp Souza Futsal Turf', 'Margao', NULL, 1550, '38m x 20m', 'https://campsouza.com/wp-content/uploads/2022/12/Untitled-design-2022-12-11T171410.858.jpg', 'A spacious futsal ground with professional-grade artificial grass.', 'Dedicated Coaching Area', 'Photo Booth for Players', 'Tactical Video Analysis', NULL),
('ven_032', 'KICKS - Infinity Futsal', 'Margao', NULL, 1650, '40m x 22m', 'https://content.jdmagicbox.com/comp/goa/q3/0832px832.x832.230913204513.f7q3/catalogue/kicks-n-flicks-goa-mini-football-fields-9soy7roh71.jpg', 'A cutting-edge futsal pitch offering an immersive playing experience.', 'On-Site Physiotherapist', 'Power Backup', 'Ball Return Nets', NULL),
('ven_033', 'Navelim Football Ground', 'Margao', NULL, 1700, '37m x 19m', 'https://www.mappls.com/place/PJK11A_1662036443317_0.png', 'A well-designed football ground, ideal for training and friendly games.', 'Parking Area', 'Visitor Restroom', 'Coach Viewing Platform', NULL),
('ven_034', 'Tiger Turf', 'Margao', NULL, 1500, '34m x 18m', 'https://content.jdmagicbox.com/comp/goa/i5/0832px832.x832.220317225912.u4i5/catalogue/nfa-nagoa-futsal-arena-saligao-goa-stadiums-xpsn1xzfdi.jpg', 'A recently renovated futsal turf with high-quality infrastructure.', 'Locker Facility', 'Sports Drinks Available', 'Player Lounge Access', NULL),
('ven_035', 'Tilak Maidan Stadium', 'Vasco', NULL, 1800, '42m x 24m', 'https://content.jdmagicbox.com/v2/comp/goa/f2/0832px832.x832.230430224856.w6f2/catalogue/agnel-futsal-arena-mapusa-goa-futsal-courts-mn568d6b4h-250.jpg', 'A stadium-style football ground with seating and modern facilities.', 'Tournament Management Service', 'Shower Rooms', 'Referee Changing Rooms', NULL),
('ven_036', 'Rose Circle Ground', 'Vasco', NULL, 1250, '30m x 16m', 'https://goemkarponn.com/wp-content/uploads/2024/09/Dnyanprassarak-Mandals-College.jpg', 'A unique circular ground offering a different playing experience.', 'Photo/Video Recording', 'Fast Food Corner', 'Event Live Streaming', NULL),
('ven_037', 'IMS Football Ground', 'Vasco', NULL, 1350, '31m x 17m', 'https://media.hudle.in/venues/8e1f1ce9-b9e0-4142-a3a7-301044ba2c61/photo/e304c997efff4a22a6d4e7d4d4df3f5cfec153f9', 'A popular football training ground used by local teams and academies.', 'Beverage Counter', 'Instant Replay Setup', 'Player Recovery Zone', NULL),
('ven_038', 'Mpt Ground', 'Vasco', NULL, 1400, '35m x 19m', 'https://content.jdmagicbox.com/comp/goa/a4/0832px832.x832.200930011201.j2a4/catalogue/poriat-football-ground-calangute-goa-sports-clubs-63186pzta2.jpg', 'A high-speed futsal turf suited for technical and tactical training.', 'Shaded Seating', 'Drinking Water Filter', 'Multi-Sport Facility', NULL),
('ven_039', 'Cansaulim Sports Stadium', 'Cansaulim', NULL, 1550, '39m x 21m', 'https://www.mappls.com/place/48FD3D_1662033457955_0.png', 'A scenic sports stadium known for hosting annual football tournaments.', 'Waterproof Surface', 'Soft Play Zone', 'Personal Coaching Sessions', NULL),
('ven_040', 'Lit Arena Futsal', 'Cansaulim', NULL, 1150, '28m x 16m', 'https://content3.jdmagicbox.com/comp/goa/i3/0832px832.x832.190517102700.g1i3/catalogue/o-jogo-goa-futsal-turf-porvorim-goa-sports-clubs-ovvewjbgnc.jpg', 'A well-maintained futsal arena with top-class playing conditions.', 'Fan Area', 'Community Football Events', 'High-Speed Wi-Fi', NULL),
('ven_041', 'Dr Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', NULL, 1450, '36m x 20m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A modern sports complex featuring high-end facilities and seating.', 'Tactical Whiteboard', 'Wall Climbing Facility', 'VIP Spectator Lounge', NULL),
('ven_042', 'Camorlim Village Football Ground', 'Cansaulim', NULL, 1300, '32m x 18m', 'https://content.jdmagicbox.com/comp/goa/l5/0832px832.x832.220612020300.r8l5/catalogue/rosa-arena-airport-dabolim-goa-futsal-courts-df4jr2j21v.jpg', 'A village football ground with a rich history and great community support.', 'Referee Stand', 'Outdoor Benches', 'LED Floodlights', NULL),
('ven_043', 'Lit Arena Futsal', 'Ponda', NULL, 1200, '30m x 17m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR__nhbZf-Wa_K-C2hbZipmtnKwlrCFzjcsCw&s', 'A premium futsal facility equipped with high-speed turf and lighting.', 'Event Hosting Facility', 'Medical Assistance Booth', 'Automated Booking System', NULL),
('ven_044', 'Majestic Futsal Turf', 'Ponda', NULL, 1250, '29m x 16m', 'https://content.jdmagicbox.com/comp/goa/d1/0832px832.x832.171225191400.a5d1/catalogue/sangolda-football-ground-sangolda-goa-sports-ground-rg4fpw2x5b.jpg', 'A small yet professional futsal ground known for competitive leagues.', 'Warm-up Area', 'Gaming Zone Nearby', 'On-Site Physiotherapy', NULL),
('ven_67d880040c3e9', 'Boscos2', 'Panjim', 1, 1312, '12314', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUUExMWFRUWGBcYGBcYGR0aGhgaGhcaGhgdGhcYHyggHxslHRgaITEhJSkrLi4uGB8zODMsNygtLisBCgoKDg0OGhAQGy8lICUtLy0vLS0rLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIANIA8AMBIgACEQEDEQH/', NULL, 'lights', 'rooms', 'parking', NULL),
('ven_67d8feb321ab9', 'Boscos3', 'Panjim', 1, 1500, '1234', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUUExMWFRUWGBcYGBcYGR0aGhgaGhcaGhgdGhcYHyggHxslHRgaITEhJSkrLi4uGB8zODMsNygtLisBCgoKDg0OGhAQGy8lICUtLy0vLS0rLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIANIA8AMBIgACEQEDEQH/', NULL, 'lights', 'rooms', 'parking', NULL),
('ven_67d9030df01be', 'Boscos4', 'Panjim', 1, 2132, '21421', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUUExMWFRUWGBcYGBcYGR0aGhgaGhcaGhgdGhcYHyggHxslHRgaITEhJSkrLi4uGB8zODMsNygtLisBCgoKDg0OGhAQGy8lICUtLy0vLS0rLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIANIA8AMBIgACEQEDEQH/', NULL, 'lights', 'rooms', 'parking', NULL),
('ven_67d904645de79', 'Boscos5', 'Panjim', 1, 123142, '12314', 'data:image/jpeg;base64,/9j/4AAQSkZJRgABAQAAAQABAAD/2wCEAAkGBxMTEhUUExMWFRUWGBcYGBcYGR0aGhgaGhcaGhgdGhcYHyggHxslHRgaITEhJSkrLi4uGB8zODMsNygtLisBCgoKDg0OGhAQGy8lICUtLy0vLS0rLy0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLS0tLf/AABEIANIA8AMBIgACEQEDEQH/', NULL, 'lights', 'rooms', 'parking', NULL),
('ven_67dae84603b30', 'Boscos6', 'Panjim', 1, 1500, '123', 'https://content.jdmagicbox.com/comp/kolkata/t7/033pxx33.xx33.230906042936.q6t7/catalogue/futsal-ground-newtown-new-town-kolkata-sports-ground-7bmrjjpu6v.jpg', NULL, 'lights', 'rooms', 'parking', NULL),
('ven_67e297bacb3fd', 'Boscos-test', 'Panjim', 1, 1500, '1000', 'https://media.hudle.in/photos/47422', NULL, 'Lights', 'rooms', 'parking', 'vowner@gameday.com');

-- --------------------------------------------------------

--
-- Table structure for table `venue_reviews`
--

CREATE TABLE `venue_reviews` (
  `v_review_id` varchar(255) NOT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `v_ratings` int(11) DEFAULT NULL,
  `v_reviews` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue_reviews`
--

INSERT INTO `venue_reviews` (`v_review_id`, `venue_id`, `v_ratings`, `v_reviews`) VALUES
('vrev_67ea4b638f145', 'ven_001', 5, 'very good');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academys`
--
ALTER TABLE `academys`
  ADD PRIMARY KEY (`ac_id`),
  ADD UNIQUE KEY `ac_id` (`ac_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `fk_academy_owner` (`owner_email`);

--
-- Indexes for table `academy_reviews`
--
ALTER TABLE `academy_reviews`
  ADD PRIMARY KEY (`a_review_id`),
  ADD KEY `fk_academy_reviews_academys` (`ac_id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`booking_id`),
  ADD UNIQUE KEY `unique_booking` (`email`,`venue_id`,`bk_date`,`bk_dur`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `fk_referee_email` (`referee_email`);

--
-- Indexes for table `enroll`
--
ALTER TABLE `enroll`
  ADD PRIMARY KEY (`en_id`),
  ADD KEY `ac_id` (`ac_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `referee`
--
ALTER TABLE `referee`
  ADD PRIMARY KEY (`ref_id`),
  ADD KEY `fk_referee_user` (`referee_email`),
  ADD KEY `fk_referee_booking` (`booking_id`);

--
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`reg_id`),
  ADD KEY `tr_id` (`tr_id`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`tr_id`),
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `fk_academy` (`ac_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`),
  ADD KEY `fk_venue_owner` (`owner_email`);

--
-- Indexes for table `venue_reviews`
--
ALTER TABLE `venue_reviews`
  ADD PRIMARY KEY (`v_review_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book`
--
ALTER TABLE `book`
  MODIFY `booking_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `academys`
--
ALTER TABLE `academys`
  ADD CONSTRAINT `academys_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`),
  ADD CONSTRAINT `fk_academy_owner` FOREIGN KEY (`owner_email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `academy_reviews`
--
ALTER TABLE `academy_reviews`
  ADD CONSTRAINT `fk_academy_reviews_academys` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`),
  ADD CONSTRAINT `fk_referee_email` FOREIGN KEY (`referee_email`) REFERENCES `referee` (`referee_email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `enroll`
--
ALTER TABLE `enroll`
  ADD CONSTRAINT `enroll_ibfk_1` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`),
  ADD CONSTRAINT `enroll_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `referee`
--
ALTER TABLE `referee`
  ADD CONSTRAINT `fk_referee_booking` FOREIGN KEY (`booking_id`) REFERENCES `book` (`booking_id`),
  ADD CONSTRAINT `fk_referee_user` FOREIGN KEY (`referee_email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `register`
--
ALTER TABLE `register`
  ADD CONSTRAINT `register_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tournaments` (`tr_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `register_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `fk_academy` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `tournaments_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venue`
--
ALTER TABLE `venue`
  ADD CONSTRAINT `fk_venue_owner` FOREIGN KEY (`owner_email`) REFERENCES `user` (`email`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `venue_reviews`
--
ALTER TABLE `venue_reviews`
  ADD CONSTRAINT `venue_reviews_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
