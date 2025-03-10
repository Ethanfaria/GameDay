-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 10, 2025 at 05:59 PM
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
  `venue_id` varchar(255) DEFAULT NULL,
  `admy_img` varchar(255) DEFAULT NULL,
  `level` varchar(50) DEFAULT NULL,
  `age_group` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `features` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academys`
--

INSERT INTO `academys` (`ac_id`, `aca_nm`, `ac_location`, `ac_charges`, `venue_id`, `admy_img`, `level`, `age_group`, `description`, `features`) VALUES
('ac_001', 'Sesa Football Academy', 'Panjim', 5000, 'ven_001', NULL, 'Advanced', 'U-18', 'A premier football academy known for its elite coaching staff and state-of-the-art facilities. Focused on developing future professional players.', 'Artificial turf, fitness center, video analysis, licensed coaches'),
('ac_002', 'Sporting Clube de Goa Football Academy', 'Panjim', 4000, 'ven_002', NULL, 'Intermediate', 'U-15', 'A well-established football academy dedicated to nurturing young players with structured training programs and competitive match experience.', 'Grass and artificial pitches, gym, tactical training sessions'),
('ac_003', 'M7 Football Club', 'Panjim', 6000, 'ven_003', NULL, 'Professional', 'Open', 'An advanced football club and academy that provides professional-level training for aspiring footballers aiming for national and international leagues.', 'Pro-level fitness training, mentorship from ex-professionals, scouting opportunities'),
('ac_004', 'Panjim Gymkhana Sports Club', 'Panjim', 3500, 'ven_004', NULL, 'Beginner', 'U-12', 'A multi-sport training academy offering specialized coaching for football enthusiasts of all ages.', 'Floodlit fields, skill development workshops, sports science support'),
('ac_005', 'Panjim Gymkhana', 'Panjim', 4500, 'ven_005', NULL, 'Intermediate', 'U-16', 'A top-tier football training institution that provides intensive coaching and tournament exposure to young players.', 'Custom training plans, weekly matches, strength and conditioning programs'),
('ac_006', 'Geno Sports Club', 'Mapusa', 7000, 'ven_006', NULL, 'Professional', 'Open', 'A grassroots football academy committed to developing young players with a focus on technical excellence and teamwork.', 'Youth development programs, experienced coaches, modern training equipment'),
('ac_007', 'Laxmi Prasad Sports Club', 'Mapusa', 4800, 'ven_007', NULL, 'Advanced', 'U-14', 'A football academy focused on building discipline, teamwork, and game intelligence in young athletes.', 'Small-group coaching, nutrition guidance, international training camps'),
('ac_008', 'Cunchelim Sports Club', 'Mapusa', 5200, 'ven_008', NULL, 'Advanced', 'U-18', 'A prestigious football academy offering year-round training for competitive players looking to enhance their technical and tactical skills.', 'Goalkeeper training, tactical analysis sessions, performance tracking'),
('ac_009', 'Calangute Football Association', 'Calangute', 3800, 'ven_009', NULL, 'Intermediate', 'U-13', 'A community-driven football academy that provides an inclusive and fun learning environment for beginners and experienced players alike.', 'Affordable training\r\nCertified coaching staff\r\nMatch simulation drills'),
('ac_010', 'United Football Academy', 'Calangute', 4200, 'ven_010', NULL, 'Advanced', 'U-17', 'A high-performance football academy that integrates cutting-edge sports technology and innovative training methods.', 'AI-powered performance analysis, endurance training, personalized coaching'),
('ac_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', 8000, 'ven_011', NULL, 'Professional', 'Open', 'A specialized futsal academy focusing on fast-paced, skill-based training to develop quick-thinking and agile players.', 'Indoor futsal courts, advanced dribbling drills, high-intensity workouts'),
('ac_012', 'Candolim Sports Club', 'Candolim', 5500, 'ven_012', NULL, 'Advanced', 'U-18', 'An academy that blends traditional football training with modern sports science to enhance player performance and longevity.', 'Rehabilitation center, strength training, tactical development workshops'),
('ac_013', 'FC Goa House', 'Porvorim', 3000, 'ven_013', NULL, 'Beginner', 'U-10', 'A youth-focused football academy that promotes teamwork, fitness, and skill-building through structured coaching programs.', 'Weekend training camps, certified FIFA instructors, fun-based learning approach'),
('ac_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', 7500, 'ven_014', NULL, 'Professional', 'Open', 'An elite football institution that works closely with professional clubs to identify and develop top-tier talent.', 'Scouting programs, club partnerships, video-assisted training'),
('ac_015', 'Churchill Brothers FC', 'Margao', 6800, 'ven_015', NULL, 'Advanced', 'U-19', 'A dynamic football academy known for its competitive training and emphasis on mental conditioning.', 'Game psychology workshops, endurance tests, regular tournament participation'),
('ac_016', 'Youth Futsal Academy', 'Margao', 4000, 'ven_016', NULL, 'Intermediate', 'U-15', 'A school of excellence in football that prepares players for club trials and professional-level games.', 'High-intensity drills, advanced fitness testing, positional play coaching'),
('ac_017', 'Salgaocar Football Club', 'Vasco da Gama', 9000, 'ven_017', NULL, 'Professional', 'Open', 'An international-standard football academy providing top-class training with opportunities for scholarships and overseas exposure.', 'Foreign coaching staff, scholarship programs, exchange tours'),
('ac_018', 'Vasco Sports Club', 'Vasco da Gama', 4700, 'ven_018', NULL, 'Advanced', 'U-16', 'A football learning center designed to help players improve their game through innovative training modules and expert guidance.', 'Skill mastery sessions, competitive tournaments, live match analysis'),
('ac_019', 'Baina Football Academy', 'Vasco da Gama', 6200, 'ven_019', NULL, 'Advanced', 'U-18', 'A cutting-edge football academy offering professional training with a focus on fitness, speed, and endurance.', 'Sports physiotherapy, personalized diet plans, speed drills'),
('ac_020', 'Chicalim Sports Complex', 'Vasco da Gama', 3500, 'ven_020', NULL, 'Beginner', 'U-12', 'An academy for budding footballers that emphasizes technical ability and strategic thinking in the game.', 'Tactical awareness training, one-on-one coaching, video performance review'),
('ac_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 5000, 'ven_021', NULL, 'Intermediate', 'U-17', 'A progressive football academy aimed at producing top-quality players through skill-based learning and match experiences.', 'Strength training, scrimmage matches, leadership development'),
('ac_022', 'Majestic Futsal Turf', 'Ponda', 7200, 'ven_022', NULL, 'Professional', 'Open', 'A futsal and football academy that helps players enhance their agility, speed, and ball control in high-pressure scenarios.', 'Agility drills, precision passing sessions, defensive strategy training');

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
-- Table structure for table `book`
--

CREATE TABLE `book` (
  `bk_date` date DEFAULT NULL,
  `bk_dur` double DEFAULT NULL,
  `venue_id` varchar(10) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `enroll`
--

CREATE TABLE `enroll` (
  `ac_id` varchar(10) NOT NULL,
  `en_dur` int(11) DEFAULT NULL,
  `en_date` date DEFAULT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `owner`
--

CREATE TABLE `owner` (
  `owner_nm` varchar(255) DEFAULT NULL,
  `o_email` varchar(255) NOT NULL,
  `owner_ph` int(11) DEFAULT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `ac_id` varchar(10) DEFAULT NULL
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
  `charges` double DEFAULT NULL,
  `yrs_exp` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referee`
--

INSERT INTO `referee` (`ref_id`, `ref_name`, `ref_location`, `ref_contact`, `ref_pic`, `charges`, `yrs_exp`) VALUES
('ref_001', 'Aaron Abel Cardoso', 'Goa Velha', '9876543210', NULL, 1500, 5),
('ref_002', 'Adesh Narayan Palni', 'Marcel', '9876543211', NULL, 1200, 3),
('ref_003', 'Akshaya Pundalik Shetgaonkar', 'Pernem', '9876543212', NULL, 1800, 7),
('ref_004', 'Aliston Fernandes', 'Margao Goa', '9876543213', NULL, 1000, 2),
('ref_005', 'Atharv Desai', 'Ponda', '9876543214', NULL, 1400, 4),
('ref_006', 'Bhavesh Bharat Sawant', 'Mapusa', '9876543215', NULL, 2000, 10),
('ref_007', 'Celrich Amyster Almeida', 'Taleigao', '9876543216', NULL, 1700, 6),
('ref_008', 'Joseph Lobo', 'Mapusa', '9876543217', NULL, 1300, 3),
('ref_009', 'Jayesh Kadam', 'Goa', '9876543218', NULL, 1600, 5),
('ref_010', 'Kashinath Vithal Kamat', 'Sanquelim', '9876543219', NULL, 1100, 2),
('ref_011', 'Leroy Gonzaga Sequeira', 'Margao', '9876543220', NULL, 1900, 8),
('ref_012', 'Naved Francis Almeida', 'Panaji', '9876543221', NULL, 1250, 3);

-- --------------------------------------------------------

--
-- Table structure for table `teams`
--

CREATE TABLE `teams` (
  `team_nm` varchar(255) NOT NULL,
  `tr_id` varchar(255) NOT NULL,
  `captain_nm` varchar(255) DEFAULT NULL,
  `captain_ph` varchar(15) DEFAULT NULL,
  `alt_ph` varchar(15) DEFAULT NULL,
  `captain_em` varchar(255) DEFAULT NULL,
  `participants` int(11) DEFAULT NULL
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
  `venue_id` varchar(255) DEFAULT NULL,
  `tr_name` varchar(255) DEFAULT NULL,
  `ac_id` varchar(10) DEFAULT NULL,
  `img_url` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `entry_fee` decimal(10,2) DEFAULT NULL,
  `prize` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `tournaments`
--

INSERT INTO `tournaments` (`tr_id`, `tr_schedule`, `start_date`, `end_date`, `venue_id`, `tr_name`, `ac_id`, `img_url`, `description`, `entry_fee`, `prize`) VALUES
('t_001', 'Every Saturday and Sunday, 10:00 AM - 5:00 PM', '2025-02-01', '2025-02-28', 'ven_001', 'CAM Goa Pro League', NULL, NULL, 'The premier football league in Goa, featuring the top teams battling for supremacy. High competition and intense matches await!', 1000.00, 50000.00),
('t_002', 'Weekdays, 4:00 PM - 7:00 PM', '2025-03-01', '2025-03-20', 'ven_002', 'GFA U-15 Division 1', NULL, NULL, 'A prestigious youth league designed for U-15 players. This tournament is a breeding ground for future football stars in Goa.', 800.00, 25000.00),
('t_003', 'Weekdays, 5:00 PM - 8:00 PM', '2025-04-01', '2025-04-15', 'ven_003', 'Bhaisaheb Bandodkar Memorial Trophy', NULL, NULL, 'A historic memorial tournament honoring Goa’s football legacy. Teams compete fiercely to claim the coveted trophy.', 1200.00, 60000.00),
('t_004', 'Saturdays, 9:00 AM - 12:00 PM', '2025-05-01', '2025-05-20', 'ven_004', 'Third Division League Bardez Zone', NULL, NULL, 'An exciting third-division league for emerging football clubs. This is the gateway for aspiring teams to climb the ranks.', 700.00, 20000.00),
('t_005', 'Weekdays, 4:00 PM - 6:00 PM', '2025-06-01', '2025-06-15', 'ven_005', 'GFA U-13 League Division 1', NULL, NULL, 'A developmental league focused on U-13 players, providing them with the first taste of professional football tournaments.', 600.00, 15000.00),
('t_006', 'Every Friday, 3:00 PM - 8:00 PM', '2025-07-01', '2025-07-31', 'ven_006', 'First Division League', NULL, NULL, 'A high-stakes first-division league where clubs showcase their tactical prowess and compete for major honors in Goa.', 1500.00, 70000.00),
('t_007', 'Weekends, 10:00 AM - 3:00 PM', '2025-08-01', '2025-08-30', 'ven_007', 'Vedanta Women\'s League', NULL, NULL, 'The leading women’s football tournament in Goa. A platform for female footballers to shine and make their mark.', 900.00, 30000.00),
('t_008', 'Weekdays, 5:00 PM - 7:00 PM', '2025-09-01', '2025-09-15', 'ven_008', 'Summer Sports League', NULL, NULL, 'A summer football league designed to keep players in peak form during the off-season. Open for all levels.', 500.00, 10000.00),
('t_009', 'Special Event, 10:00 AM - 4:00 PM', '2025-10-01', '2025-10-05', 'ven_009', 'Goa Sports Challenge', NULL, NULL, 'An elite sports challenge where the best athletes from across Goa compete in football and other disciplines.', 750.00, 25000.00),
('t_010', 'Weekly - Every Monday, 6:00 PM - 9:00 PM', '2025-06-01', '2025-07-15', NULL, 'Urban Sports League', NULL, NULL, 'A high-energy urban football league, bringing the excitement of street football to an organized competition.', 600.00, 18000.00),
('t_011', 'Bi-Weekly - Every 1st & 3rd Saturday, 4:00 PM - 8:00 PM', '2025-07-10', '2025-08-20', NULL, 'Autumn Sports Championship', NULL, NULL, 'An autumn championship that brings together top-tier teams for a battle of skill, endurance, and strategy.', 950.00, 28000.00),
('t_012', 'Monthly - Last Sunday of the month, 10:00 AM - 3:00 PM', '2025-08-05', '2025-09-10', NULL, 'CAM GFA Girls League', NULL, NULL, 'A girls-only football tournament designed to nurture young female talent in Goa and promote gender inclusivity in sports.', 400.00, 12000.00),
('t_013', 'Weekend Only - Saturdays & Sundays, 5:00 PM - 9:00 PM', '2025-09-01', '2025-10-05', NULL, 'Charity Match', NULL, NULL, 'A special charity match held annually to support community initiatives, featuring professional and amateur players alike.', 300.00, 8000.00),
('t_014', 'Daily - 7:00 PM - 10:00 PM', '2025-10-10', '2025-11-15', NULL, 'Laye GFA Futsal League', NULL, NULL, 'An exciting futsal league with fast-paced matches, showcasing the finest futsal talent in the region.', 700.00, 22000.00);

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

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_name`, `user_ph`, `email`, `password`) VALUES
('ghdghdhgdhhgh_com', 1111111111, 'dhdghdh@gdgd.gdg', '$2y$10$EKOwOe/HrvID1f/hwLgoou8E5rZFLT.ZDn3/kEw0jSvCiEZgRHgV6');

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
  `size` int(11) DEFAULT NULL,
  `turf_img` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_nm`, `location`, `availability`, `price`, `size`, `turf_img`) VALUES
('ven_001', 'Sesa Football Academy', 'Panjim', 1, 5000, 100, NULL),
('ven_002', 'Sporting Clube de Goa Football Academy', 'Panjim', 1, 6000, 120, NULL),
('ven_003', 'M7 Football Club', 'Panjim', 1, 7000, 150, NULL),
('ven_004', 'Panjim Gymkhana Sports Club', 'Panjim', 1, 5500, 110, NULL),
('ven_005', 'Panjim Gymkhana', 'Panjim', 1, 5000, 100, NULL),
('ven_006', 'Geno Sports Club', 'Mapusa', 1, 4000, 90, NULL),
('ven_007', 'Laxmi Prasad Sports Club', 'Mapusa', 1, 4500, 100, NULL),
('ven_008', 'Cunchelim Sports Club', 'Mapusa', 1, 5000, 110, NULL),
('ven_009', 'Calangute Football Association', 'Calangute', 1, 6000, 120, NULL),
('ven_010', 'United Football Academy', 'Calangute', 1, 7000, 130, NULL),
('ven_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', 1, 5500, 100, NULL),
('ven_012', 'Candolim Sports Club', 'Candolim', 1, 5000, 90, NULL),
('ven_013', 'FC Goa House', 'Porvorim', 1, 6500, 125, NULL),
('ven_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', 1, 6000, 120, NULL),
('ven_015', 'Churchill Brothers FC', 'Margao', 1, 7000, 130, NULL),
('ven_016', 'Youth Futsal Academy', 'Margao', 1, 5000, 100, NULL),
('ven_017', 'Salgaocar Football Club', 'Vasco da Gama', 1, 8000, 150, NULL),
('ven_018', 'Vasco Sports Club', 'Vasco da Gama', 1, 7000, 130, NULL),
('ven_019', 'Baina Football Academy', 'Vasco da Gama', 1, 5000, 100, NULL),
('ven_020', 'Chicalim Sports Complex', 'Vasco da Gama', 1, 6000, 120, NULL),
('ven_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 1, 4000, 90, NULL),
('ven_022', 'Majestic Futsal Turf', 'Ponda', 1, 5000, 100, NULL);

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
-- Indexes for table `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`venue_id`,`email`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `enroll`
--
ALTER TABLE `enroll`
  ADD PRIMARY KEY (`ac_id`,`email`),
  ADD KEY `email` (`email`);

--
-- Indexes for table `owner`
--
ALTER TABLE `owner`
  ADD PRIMARY KEY (`o_email`),
  ADD KEY `fk_owner_academy` (`ac_id`);

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
  ADD KEY `venue_id` (`venue_id`),
  ADD KEY `fk_academy` (`ac_id`);

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
-- Constraints for table `book`
--
ALTER TABLE `book`
  ADD CONSTRAINT `book_ibfk_1` FOREIGN KEY (`venue_id`) REFERENCES `venue` (`venue_id`),
  ADD CONSTRAINT `book_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`);

--
-- Constraints for table `enroll`
--
ALTER TABLE `enroll`
  ADD CONSTRAINT `enroll_ibfk_1` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`),
  ADD CONSTRAINT `enroll_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`);

--
-- Constraints for table `owner`
--
ALTER TABLE `owner`
  ADD CONSTRAINT `fk_owner_academy` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`);

--
-- Constraints for table `teams`
--
ALTER TABLE `teams`
  ADD CONSTRAINT `teams_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tournaments` (`tr_id`);

--
-- Constraints for table `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `fk_academy` FOREIGN KEY (`ac_id`) REFERENCES `academys` (`ac_id`),
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
