-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 13, 2025 at 07:44 AM
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
  `features` text DEFAULT NULL,
  `timings` varchar(255) DEFAULT NULL,
  `duration` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `academys`
--

INSERT INTO `academys` (`ac_id`, `aca_nm`, `ac_location`, `ac_charges`, `venue_id`, `admy_img`, `level`, `age_group`, `description`, `features`, `timings`, `duration`) VALUES
('ac_001', 'Sesa Football Academy', 'Panjim', 5000, 'ven_001', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT0rQeDT8x-XWdoXOUoKJmF7RHkbNondCca8w&', 'Advanced', 'U-18', 'A premier football academy known for its elite coaching staff and state-of-the-art facilities. Focused on developing future professional players.', 'Artificial turf, fitness center, video analysis, licensed coaches', NULL, NULL),
('ac_002', 'Sporting Clube de Goa Football Academy', 'Panjim', 4000, 'ven_002', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRLCi8nLjCMGK6UtodWL0TRHDKS-6rKEhJgdA&s', 'Intermediate', 'U-15', 'A well-established football academy dedicated to nurturing young players with structured training programs and competitive match experience.', 'Grass and artificial pitches, gym, tactical training sessions', NULL, NULL),
('ac_003', 'M7 Football Club', 'Panjim', 6000, 'ven_003', 'https://ss-i.thgim.com/public/incoming/article37461949.ece/alternates/LANDSCAPE_1200/fc-goajfif', 'Professional', 'Open', 'An advanced football club and academy that provides professional-level training for aspiring footballers aiming for national and international leagues.', 'Pro-level fitness training, mentorship from ex-professionals, scouting opportunities', NULL, NULL),
('ac_004', 'Panjim Gymkhana Sports Club', 'Panjim', 3500, 'ven_004', NULL, 'Beginner', 'U-12', 'A multi-sport training academy offering specialized coaching for football enthusiasts of all ages.', 'Floodlit fields, skill development workshops, sports science support', NULL, NULL),
('ac_005', 'Panjim Gymkhana', 'Panjim', 4500, 'ven_005', NULL, 'Intermediate', 'U-16', 'A top-tier football training institution that provides intensive coaching and tournament exposure to young players.', 'Custom training plans, weekly matches, strength and conditioning programs', NULL, NULL),
('ac_006', 'Geno Sports Club', 'Mapusa', 7000, 'ven_006', 'https://livenewsgoa.com/wp-content/uploads/2022/05/InShot_20220513_234730699scaled.jpg', 'Professional', 'Open', 'A grassroots football academy committed to developing young players with a focus on technical excellence and teamwork.', 'Youth development programs, experienced coaches, modern training equipment', NULL, NULL),
('ac_007', 'Laxmi Prasad Sports Club', 'Mapusa', 4800, 'ven_007', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcQJ49umkzrhdWTaXBJTW9Yfrvor4RiikKRH0w&s', 'Advanced', 'U-14', 'A football academy focused on building discipline, teamwork, and game intelligence in young athletes.', 'Small-group coaching, nutrition guidance, international training camps', NULL, NULL),
('ac_008', 'Cunchelim Sports Club', 'Mapusa', 5200, 'ven_008', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcSCw8nReJoBLCvERvMLG0CZunt9_KpFSbo98w&s', 'Advanced', 'U-18', 'A prestigious football academy offering year-round training for competitive players looking to enhance their technical and tactical skills.', 'Goalkeeper training, tactical analysis sessions, performance tracking', NULL, NULL),
('ac_009', 'Calangute Football Association', 'Calangute', 3800, 'ven_009', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcQpqe68SodBG74V8h3ZrxcOsStazTQFlSUY9w&s', 'Intermediate', 'U-13', 'A community-driven football academy that provides an inclusive and fun learning environment for beginners and experienced players alike.', 'Affordable training\r\nCertified coaching staff\r\nMatch simulation drills', NULL, NULL),
('ac_010', 'United Football Academy', 'Calangute', 4200, 'ven_010', 'https://www.thegoan.net/uploads/news/big_74140_SGU.jpg', 'Advanced', 'U-17', 'A high-performance football academy that integrates cutting-edge sports technology and innovative training methods.', 'AI-powered performance analysis, endurance training, personalized coaching', NULL, NULL),
('ac_011', 'Super30 × Bengaluru FC Soccer Schools', 'Candolim', 8000, 'ven_011', 'https://www.thegoan.net/uploads/news/big_118186_IMG_3993.jpeg', 'Professional', 'Open', 'A specialized futsal academy focusing on fast-paced, skill-based training to develop quick-thinking and agile players.', 'Indoor futsal courts, advanced dribbling drills, high-intensity workouts', NULL, NULL),
('ac_012', 'Candolim Sports Club', 'Candolim', 5500, 'ven_012', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcTlghsqHHubkWApjynFeSxrUPAQ02vHpPziw&s', 'Advanced', 'U-18', 'An academy that blends traditional football training with modern sports science to enhance player performance and longevity.', 'Rehabilitation center, strength training, tactical development workshops', NULL, NULL),
('ac_013', 'FC Goa House', 'Porvorim', 3000, 'ven_013', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcQidPWhYGse79bxbao2qXxk3W5YxLDmdIx-2Q&s', 'Beginner', 'U-10', 'A youth-focused football academy that promotes teamwork, fitness, and skill-building through structured coaching programs.', 'Weekend training camps, certified FIFA instructors, fun-based learning approach', NULL, NULL),
('ac_014', 'Alto Porvorim Sports And Cultural Club', 'Porvorim', 7500, 'ven_014', 'https://content.jdmagicbox.com/comp/belgaum/a9/9999px831.x831.230901155656.z6a9/catalogue/belgaum-united-football-academy-belgaum-football-clubs-ozyixxltrx.jpg', 'Professional', 'Open', 'An elite football institution that works closely with professional clubs to identify and develop top-tier talent.', 'Scouting programs, club partnerships, video-assisted training', NULL, NULL),
('ac_015', 'Churchill Brothers FC', 'Margao', 6800, 'ven_015', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcQF1ANSG1xjb0tE0NWZ0_8G_WPFxwYcPVT2tg&s', 'Advanced', 'U-19', 'A dynamic football academy known for its competitive training and emphasis on mental conditioning.', 'Game psychology workshops, endurance tests, regular tournament participation', NULL, NULL),
('ac_016', 'Youth Futsal Academy', 'Margao', 4000, 'ven_016', 'https://encryptetbn0.gstatic.com/imagesq=tbn:ANd9GcSDs6ANYg4vgc40yoR0GmQymwCza1PH80Saw&s', 'Intermediate', 'U-15', 'A school of excellence in football that prepares players for club trials and professional-level games.', 'High-intensity drills, advanced fitness testing, positional play coaching', NULL, NULL),
('ac_017', 'Salgaocar Football Club', 'Vasco da Gama', 9000, 'ven_017', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcQqg0se5hZbJ1lLFpfkpsLkvR1ppoaENJl4W-mFqsPdK-9d9YX7Ys8YkeL8u3kHSWtV3pU&usqp=CAU', 'Professional', 'Open', 'An international-standard football academy providing top-class training with opportunities for scholarships and overseas exposure.', 'Foreign coaching staff, scholarship programs, exchange tours', NULL, NULL),
('ac_018', 'Vasco Sports Club', 'Vasco da Gama', 4700, 'ven_018', 'https://encryptedtbn0.gstatic.com/imagesq=tbn:ANd9GcR87zRW4mkpiV1EHyd_hwYncjGnJLsN9LuA&s', 'Advanced', 'U-16', 'A football learning center designed to help players improve their game through innovative training modules and expert guidance.', 'Skill mastery sessions, competitive tournaments, live match analysis', NULL, NULL),
('ac_019', 'Baina Football Academy', 'Vasco da Gama', 6200, 'ven_019', 'https://www.navhindtimes.in/wp-content/uploads/2023/03/15rising.jpeg.jpg', 'Advanced', 'U-18', 'A cutting-edge football academy offering professional training with a focus on fitness, speed, and endurance.', 'Sports physiotherapy, personalized diet plans, speed drills', NULL, NULL),
('ac_020', 'Chicalim Sports Complex', 'Vasco da Gama', 3500, 'ven_020', 'https://content3.jdmagicbox.com/v2/comp/goa/d2/0832px832.x832.231108200624.c9d2/catalogue/vasco-united-football-academy-chicalim-goa-football-clubs-4o7pig0h1g.jpg', 'Beginner', 'U-12', 'An academy for budding footballers that emphasizes technical ability and strategic thinking in the game.', 'Tactical awareness training, one-on-one coaching, video performance review', NULL, NULL),
('ac_021', 'Dr. Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 5000, 'ven_021', NULL, 'Intermediate', 'U-17', 'A progressive football academy aimed at producing top-quality players through skill-based learning and match experiences.', 'Strength training, scrimmage matches, leadership development', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `academy_reviews`
--

CREATE TABLE `academy_reviews` (
  `a_review_id` varchar(255) NOT NULL,
  `a_review` text DEFAULT NULL,
  `a_rating` tinyint(4) DEFAULT NULL,
  `ac_id` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

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
  `email` varchar(255) NOT NULL,
  `Availability` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book`
--

INSERT INTO `book` (`bk_date`, `bk_dur`, `venue_id`, `email`, `Availability`) VALUES
('2025-03-13', 9, 'ven_001', 'ss@gmail.com', 0);

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
  `yrs_exp` int(11) DEFAULT NULL,
  `ref_ph` varchar(15) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referee`
--

INSERT INTO `referee` (`ref_id`, `ref_name`, `ref_location`, `ref_contact`, `ref_pic`, `charges`, `yrs_exp`, `ref_ph`) VALUES
('ref_001', 'Aaron Abel Cardoso', 'Goa Velha', '9876543210', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1679647008.jpg', 1500, 5, '9876543210'),
('ref_002', 'Adesh Narayan Palni', 'Marcel', '9876543211', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1656239515.jpg', 1200, 3, '8765432109'),
('ref_003', 'Akshaya Pundalik Shetgaonkar', 'Pernem', '9876543212', 'http://refereeadmin.the-aiff.com/uploads/refree_img/21151732665b8059b2af50a.JPG', 1800, 7, '7654321098'),
('ref_004', 'Aliston Fernandes', 'Margao Goa', '9876543213', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1672555312.jpg', 1000, 2, '6543210987'),
('ref_005', 'Atharv Desai', 'Ponda', '9876543214', 'http://refereeadmin.the-aiff.com/uploads/refree_img/imagemedia_1729436483.jpg', 1400, 4, '5432109876'),
('ref_006', 'Bhavesh Bharat Sawant', 'Mapusa', '9876543215', 'http://refereeadmin.the-aiff.com/uploads/refree_img/9134725215b3c63b5b2e63.jpg', 2000, 10, '4321098765'),
('ref_007', 'Celrich Amyster Almeida', 'Taleigao', '9876543216', 'http://refereeadmin.the-aiff.com/uploads/refree_img/844127305575583dde44b2.jpg', 1700, 6, '3210987654'),
('ref_008', 'Joseph Lobo', 'Mapusa', '9876543217', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1688806088.jpg', 1300, 3, '2109876543'),
('ref_009', 'Jayesh Kadam', 'Goa', '9876543218', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1656216376.jpg', 1600, 5, '1098765432'),
('ref_010', 'Kashinath Vithal Kamat', 'Sanquelim', '9876543219', 'http://refereeadmin.the-aiff.com/uploads/refree_img/10076977065dcbfbcd76dbc.jpg', 1100, 2, '9988776655'),
('ref_011', 'Leroy Gonzaga Sequeira', 'Margao', '9876543220', 'http://refereeadmin.the-aiff.com/uploads/refree_img/3301226015ce23f3ef40d4.jpg', 1900, 8, '8877665544'),
('ref_012', 'Naved Francis Almeida', 'Panaji', '9876543221', 'http://refereeadmin.the-aiff.com/uploads/refree_img/Referee-Profilepic-1715837297.jpeg', 1250, 3, '7766554433');

-- --------------------------------------------------------

--
-- Table structure for table `register`
--

CREATE TABLE `register` (
  `reg_id` varchar(10) NOT NULL,
  `tr_id` varchar(10) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `team_nm` varchar(255) DEFAULT NULL,
  `captain_nm` varchar(255) DEFAULT NULL,
  `captain_ph` varchar(15) DEFAULT NULL,
  `captain_e` varchar(255) DEFAULT NULL,
  `no_players` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
('t_001', 'Every Saturday and Sunday, 10:00 AM - 5:00 PM', '2025-02-01', '2025-02-28', 'ven_001', 'CAM Goa Pro League', NULL, 'https://www.gfagoa.com/media/logos/leagues/WhatsApp_Image_2023-08-28_at_20.00.50_1.png', 'The premier football league in Goa, featuring the top teams battling for supremacy. High competition and intense matches await!', 1000.00, 50000.00),
('t_002', 'Weekdays, 4:00 PM - 7:00 PM', '2025-03-01', '2025-03-20', 'ven_002', 'GFA U-15 Division 1', NULL, 'https://www.gfagoa.com/media/logos/leagues/13.png', 'A prestigious youth league designed for U-15 players. This tournament is a breeding ground for future football stars in Goa.', 800.00, 25000.00),
('t_003', 'Weekdays, 5:00 PM - 8:00 PM', '2025-04-01', '2025-04-15', 'ven_003', 'Bhaisaheb Bandodkar Memorial Trophy', NULL, 'https://www.gfagoa.com/media/logos/leagues/4322ce59-dd4a-4bd2-83c1-87eea6d3c679.png', 'A historic memorial tournament honoring Goa’s football legacy. Teams compete fiercely to claim the coveted trophy.', 1200.00, 60000.00),
('t_004', 'Saturdays, 9:00 AM - 12:00 PM', '2025-05-01', '2025-05-20', 'ven_004', 'Third Division League Bardez Zone', NULL, 'https://www.gfagoa.com/static/images/logo.png', 'An exciting third-division league for emerging football clubs. This is the gateway for aspiring teams to climb the ranks.', 700.00, 20000.00),
('t_005', 'Weekdays, 4:00 PM - 6:00 PM', '2025-06-01', '2025-06-15', 'ven_005', 'GFA U-13 League Division 1', NULL, 'https://www.gfagoa.com/media/logos/leagues/12.png', 'A developmental league focused on U-13 players, providing them with the first taste of professional football tournaments.', 600.00, 15000.00),
('t_006', 'Every Friday, 3:00 PM - 8:00 PM', '2025-07-01', '2025-07-31', 'ven_006', 'First Division League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A high-stakes first-division league where clubs showcase their tactical prowess and compete for major honors in Goa.', 1500.00, 70000.00),
('t_007', 'Weekends, 10:00 AM - 3:00 PM', '2025-08-01', '2025-08-30', 'ven_007', 'Vedanta Women\'s League', NULL, 'https://www.gfagoa.com/media/logos/leagues/vedentaWomanL.png', 'The leading women’s football tournament in Goa. A platform for female footballers to shine and make their mark.', 900.00, 30000.00),
('t_008', 'Weekdays, 5:00 PM - 7:00 PM', '2025-09-01', '2025-09-15', 'ven_008', 'Summer Sports League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A summer football league designed to keep players in peak form during the off-season. Open for all levels.', 500.00, 10000.00),
('t_009', 'Special Event, 10:00 AM - 4:00 PM', '2025-10-01', '2025-10-05', 'ven_009', 'Goa Sports Challenge', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'An elite sports challenge where the best athletes from across Goa compete in football and other disciplines.', 750.00, 25000.00),
('t_010', 'Weekly - Every Monday, 6:00 PM - 9:00 PM', '2025-06-01', '2025-07-15', NULL, 'Urban Sports League', NULL, 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQKZDuuMF7utZJ57AsqI6jkdP7oPdTBO6Eawg&s', 'A high-energy urban football league, bringing the excitement of street football to an organized competition.', 600.00, 18000.00),
('t_011', 'Bi-Weekly - Every 1st & 3rd Saturday, 4:00 PM - 8:00 PM', '2025-07-10', '2025-08-20', NULL, 'Autumn Sports Championship', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'An autumn championship that brings together top-tier teams for a battle of skill, endurance, and strategy.', 950.00, 28000.00),
('t_012', 'Monthly - Last Sunday of the month, 10:00 AM - 3:00 PM', '2025-08-05', '2025-09-10', NULL, 'CAM GFA Girls League', NULL, 'https://www.gfagoa.com/media/logos/leagues/cam.png', 'A girls-only football tournament designed to nurture young female talent in Goa and promote gender inclusivity in sports.', 400.00, 12000.00),
('t_013', 'Weekend Only - Saturdays & Sundays, 5:00 PM - 9:00 PM', '2025-09-01', '2025-10-05', NULL, 'Charity Match', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo.png', 'A special charity match held annually to support community initiatives, featuring professional and amateur players alike.', 300.00, 8000.00),
('t_014', 'Daily - 7:00 PM - 10:00 PM', '2025-10-10', '2025-11-15', NULL, 'Laye GFA Futsal League', NULL, 'https://www.gfagoa.com/media/logos/leagues/logo_TCZ01QP.png', 'An exciting futsal league with fast-paced matches, showcasing the finest futsal talent in the region.', 700.00, 22000.00);

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
('ghdghdhgdhhgh_com', 1111111111, 'dhdghdh@gdgd.gdg', '$2y$10$EKOwOe/HrvID1f/hwLgoou8E5rZFLT.ZDn3/kEw0jSvCiEZgRHgV6'),
('dkamat', 2147483647, 'dk@gmail.com', '$2y$10$CO0sxgnyLKPs88lvG0cnjufocDI432HHv.1f24HA4yPC5KSZKP6sK'),
('Ethan', 2147483647, 'ethanfaria14@gmail.com', '$2y$10$xcEjseBkqBHVwRwshj8z..VuxIoY9FfxPUWZqvn3oiYvz0zoZ41/K'),
('sairajsirsat', 1234567890, 'ss@gmail.com', '$2y$10$lMdNsgNUkEUeikXDM5iB6eOKgSJ3zuS3O3vgSuY2GNLAR.V.mVMW2'),
('ved', 2147483647, 'vedk2004@gmail.com', '$2y$10$Q/dVhIsvopjl.mmvAfkHnOSlLGHCGXMi22Jp77ESh0.KRFcZB0AQy');

-- --------------------------------------------------------

--
-- Table structure for table `venue`
--

CREATE TABLE `venue` (
  `venue_id` varchar(255) NOT NULL,
  `venue_nm` varchar(255) DEFAULT NULL,
  `location` varchar(255) DEFAULT NULL,
  `price` double DEFAULT NULL,
  `size` varchar(255) DEFAULT NULL,
  `turf_img` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `amenity1` varchar(255) DEFAULT NULL,
  `amenity2` varchar(255) DEFAULT NULL,
  `amenity3` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `venue`
--

INSERT INTO `venue` (`venue_id`, `venue_nm`, `location`, `price`, `size`, `turf_img`, `description`, `amenity1`, `amenity2`, `amenity3`) VALUES
('ven_001', 'Play Goa', 'Panjim', 800, '25m x 15m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTj3Fi_lngrUiWu0ZvFmV_0xxmYYf7Smo29Tw&s', 'A top-tier futsal arena with high-quality turf and professional lighting.', 'Floodlights', 'Synthetic Grass', 'Flexible Booking Options'),
('ven_002', 'Don Bosco Futsal', 'Panjim', 1200, '30m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQNOYRVk51Qqqmi2ZIaDA2WcD44jcGBkb23LQ&s', 'Spacious football ground with well-maintained grass and seating areas.', 'Shaded Spectator Area', 'Locker Rooms', 'Automated Lighting System'),
('ven_003', 'Dengoa', 'Panjim', 1000, '28m x 16m', 'https://media.hudle.in/venues/5a267275-be88-494f-ada8-6716f90c3330/photo/b14d2548aa603e410eee9dee60386f4f9507ecc5', 'A compact and fast-paced futsal turf ideal for small-sided games.', 'Artificial Turf', 'LED Scoreboard', 'Weekly Tournaments Hosted'),
('ven_004', 'Kicks N\' Flicks', 'Panjim', 1400, '35m x 20m', 'https://content.jdmagicbox.com/comp/goa/q3/0832px832.x832.230913204513.f7q3/catalogue/kicks-n-flicks-goa-mini-football-fields-9soy7roh71.jpg', 'Popular football ground with a great atmosphere for local matches.', 'CCTV Security', 'Live Streaming Available', 'Music System'),
('ven_005', 'Campal New Ground', 'Panjim', 900, '32m x 18m', 'https://www.mappls.com/place/PJK11A_1662036443317_0.png', 'Floodlit futsal arena designed for intense 5-a-side matches.', 'Team Dugouts', 'Seating for 200+', 'Kids Play Area'),
('ven_006', 'Dr Alvero Pinto Ground', 'Panjim', 1600, '40m x 20m', 'https://content.jdmagicbox.com/comp/goa/i5/0832px832.x832.220317225912.u4i5/catalogue/nfa-nagoa-futsal-arena-saligao-goa-stadiums-xpsn1xzfdi.jpg', 'Well-kept football ground perfect for tournaments and training.', 'Scoreboard', 'On-site Cafeteria', 'Discounted Group Booking'),
('ven_007', 'Bambolim Stadium', 'Panjim', 2000, '42m x 22m', 'https://content.jdmagicbox.com/v2/comp/goa/f2/0832px832.x832.230430224856.w6f2/catalogue/agnel-futsal-arena-mapusa-goa-futsal-courts-mn568d6b4h-250.jpg', 'A multipurpose ground offering excellent playability for all skill levels.', 'Drinking Water Station', 'Ice Pack Availability', 'Night Matches Allowed'),
('ven_008', 'Merces Football Ground', 'Panjim', 2500, '38m x 18m', 'https://goemkarponn.com/wp-content/uploads/2024/09/Dnyanprassarak-Mandals-College.jpg', 'Premium futsal turf with soft padding to reduce player fatigue.', 'First Aid Kit', 'Surround Sound System', 'Fan Stand with TV Screen'),
('ven_009', 'Santa Cruz Futsal Ground', 'Panjim', 2200, '36m x 20m', 'https://media.hudle.in/venues/8e1f1ce9-b9e0-4142-a3a7-301044ba2c61/photo/e304c997efff4a22a6d4e7d4d4df3f5cfec153f9', 'A scenic football ground located in a peaceful setting.', 'Seating Area', 'Dedicated Warm-up Zone', 'Full-Sized Goals'),
('ven_010', 'Duler Football Stadium', 'Mapusa', 1100, '34m x 17m', 'https://content.jdmagicbox.com/comp/goa/a4/0832px832.x832.200930011201.j2a4/catalogue/poriat-football-ground-calangute-goa-sports-clubs-63186pzta2.jpg', 'Artificial turf with state-of-the-art drainage for all-weather play.', 'Changing Rooms', 'Artificial Grass Maintenance', 'No Footwear Restriction'),
('ven_011', 'Ganeshpuri Football Ground', 'Mapusa', 1700, '39m x 19m', 'https://www.mappls.com/place/48FD3D_1662033457955_0.png', 'Compact futsal ground with a vibrant atmosphere and spectator seating.', 'Restrooms', 'Mini Goalposts for Training', 'Freestyle Football Zone'),
('ven_012', 'St Xavier Football Ground', 'Mapusa', 1800, '41m x 21m', 'https://content3.jdmagicbox.com/comp/goa/i3/0832px832.x832.190517102700.g1i3/catalogue/o-jogo-goa-futsal-turf-porvorim-goa-sports-clubs-ovvewjbgnc.jpg', 'A community-friendly ground known for its weekend football tournaments.', 'Ball Rental Service', 'Family Seating Area', 'Exclusive Club Membership'),
('ven_013', 'St Sabastiean Sports', 'Mapusa', 1950, '37m x 20m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A well-lit and spacious futsal ground suited for intense matches.', 'Canteen', 'Turf Shoe Rental', 'Custom Jerseys Available'),
('ven_014', 'Nagao Futsal Arena', 'Mapusa', 1300, '31m x 17m', 'https://content.jdmagicbox.com/comp/goa/l5/0832px832.x832.220612020300.r8l5/catalogue/rosa-arena-airport-dabolim-goa-futsal-courts-df4jr2j21v.jpg', 'A traditional football ground with a history of hosting major local games.', 'Shower Facility', 'Floodlight Control Panel', 'Player Lockers'),
('ven_015', 'Agnel Futsal Arena', 'Mapusa', 2100, '40m x 22m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR__nhbZf-Wa_K-C2hbZipmtnKwlrCFzjcsCw&s', 'An advanced futsal facility with superior turf and modern amenities.', 'Footwear Cleaning Area', 'Energy Drinks on Sale', 'Private Parking'),
('ven_016', 'Calangute Futsal', 'Calangute', 600, '26m x 14m', 'https://content.jdmagicbox.com/comp/goa/d1/0832px832.x832.171225191400.a5d1/catalogue/sangolda-football-ground-sangolda-goa-sports-ground-rg4fpw2x5b.jpg', 'A football ground ideal for both casual and competitive matches.', 'Music System', 'Player Lounge', 'Live Score Updates'),
('ven_017', 'Poriaot Football Ground', 'Calangute', 750, '27m x 15m', 'https://content.jdmagicbox.com/comp/goa/e4/0832px832.x832.140222122030.f1e4/catalogue/duler-stadium-mapusa-goa-stadiums-2gcrdhafwj.jpg', 'State-of-the-art futsal pitch with enclosed walls for high-speed gameplay.', 'Free Wi-Fi', 'Security Personnel On-Site', 'Tournament Hosting Facilities'),
('ven_018', 'Dr. Gustavo Monteiro Football Ground', 'Candolim', 1450, '33m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTgQFJj8jmDQMi-0giVqqQMXU4jaA_0PAWi_A&s', 'A football ground known for its great maintenance and sports culture.', 'Storage Lockers', 'Premium Sound System', 'Rest & Recovery Zone'),
('ven_019', 'O Jogo, Goa Futsal', 'Porvorim', 2300, '41m x 23m', 'https://campsouza.com/wp-content/uploads/2022/12/Untitled-design-2022-12-11T171410.858.jpg', 'A modern futsal arena equipped with high-tech goal-line technology.', 'Coaching Available', 'Dedicated Rest Area', 'Free Entry for Spectators'),
('ven_020', 'First Strike Arena Football', 'Porvorim', 1250, '29m x 16m', 'https://encryptedtbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A professional training ground for serious football enthusiasts.', 'Umbrella Rentals', 'Extra Ball Storage', 'Vending Machines'),
('ven_021', 'ATB Ground', 'Porvorim', 2700, '42m x 25m', 'https://content.jdmagicbox.com/v2/comp/kottayam/y6/9999px481.x481.240418230251.f6y6/catalogue/tigers-turf-puthenangady-kottayam-football-clubs-d5qvkvlu1t-250.jpg', 'A multi-use futsal turf with excellent visibility and seating arrangements.', 'Private Lounge', 'High-Quality Turf Surface', 'Kids Training Camps'),
('ven_022', 'Shriram Shrimati Siolim Futsal Arena', 'Siolim', 1500, '34m x 18m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTkSk4xHNTdeo6hyGnU9IvVYqC64Y3CbmzGkw&s', 'A competitive futsal ground featuring international standard turf.', 'VIP Seating', 'Social Media Streaming', 'Mobile App Booking'),
('ven_023', 'St. Anthony\'s Church Ground', 'Siolim', 1200, '30m x 17m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQzccj3ebRH_d39cWv-tymcHb9qLLRdv9kyeQ&s', 'A church-owned football ground fostering youth sports development.', 'Equipment Rental', 'Coach Viewing Stand', 'Climate Controlled Hall'),
('ven_024', 'Chapora Football Ground', 'Siolim', 1300, '31m x 18m', 'https://content.jdmagicbox.com/comp/goa/a8/0832px832.x832.190912205053.b3a8/catalogue/mpt-grounds-vasco-da-gama-goa-sports-ground-k0676h6bsl.jpg', 'A football ground with a beautiful view and smooth grass surface.', 'Drinking Water Facility', 'Picnic Area Nearby', 'Premium Turf Grass'),
('ven_025', 'Morjim Football Ground', 'Siolim', 1400, '36m x 20m', 'https://1.bp.blogspot.com/-YMOFUZcP8LY/UQCVac-f1SI/AAAAAAAAI8k/ExuKlrchsZw/s1600/Cortalim-23-%232-2.jpg', 'A beachside football ground offering a unique playing experience.', 'Food Stalls', 'Automated Sprinklers', 'FIFA Standard Goalposts'),
('ven_026', 'Siolim Turf Ground', 'Siolim', 1100, '28m x 15m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQJpPfZiSBJiqaSsmjQhJkBo8HpaPEzD1gWJA&s', 'A modern synthetic turf designed for optimal ball control and speed.', 'Ice Bath Facility', 'Team Dugout with Fans', 'Advanced Sprinkler System'),
('ven_027', 'Siolim Football Turf', 'Siolim', 1250, '32m x 17m', 'https://content3.jdmagicbox.com/comp/goa/s1/0832px832.x832.220131235659.g4s1/catalogue/ella-ground-carambolim-goa-hlslvm5cr7.jpg', 'A neighborhood futsal turf known for friendly matches and leagues.', 'Hydration Station', 'Water Dispenser', 'Tactical Video Analysis'),
('ven_028', 'Chowgule Sports Ground', 'Margao', 1600, '39m x 21m', 'https://content.jdmagicbox.com/comp/goa/s4/0832px832.x832.221218202837.w2s4/catalogue/majestic-futsal-turf-goa-futsal-courts-14pvsitj0m-250.jpg', 'A large sports complex accommodating multiple sports disciplines.', 'Kids Play Area', 'Multi-Sport Compatibility', 'Ball Return Nets'),
('ven_029', 'First Strike Arena', 'Margao', 1350, '35m x 19m', 'https://encryptedtbn0.gstatic.com/images?q=tbn:ANd9GcTj3Fi_lngrUiWu0ZvFmV_0xxmYYf7Smo29Tw&s', 'A compact and well-lit futsal arena, perfect for night matches.', 'Live Streaming Setup', 'Goalkeeper Training Net', 'Coach Viewing Platform'),
('ven_030', 'Don Bosco Ground', 'Margao', 1450, '33m x 17m', 'https://encryptedtbn0.gstatic.com/images?q=tbn:ANd9GcQNOYRVk51Qqqmi2ZIaDA2WcD44jcGBkb23LQ&s', 'A historic football ground that has hosted legendary local matches.', 'Backup Generator', 'Tactical Discussion Board', 'Player Lounge Access'),
('ven_031', 'Camp Souza Futsal Turf', 'Margao', 1550, '38m x 20m', 'https://campsouza.com/wp-content/uploads/2022/12/Untitled-design-2022-12-11T171410.858.jpg', 'A spacious futsal ground with professional-grade artificial grass.', 'Dedicated Coaching Area', 'Photo Booth for Players', 'Tactical Video Analysis'),
('ven_032', 'KICKS - Infinity Futsal', 'Margao', 1650, '40m x 22m', 'https://content.jdmagicbox.com/comp/goa/q3/0832px832.x832.230913204513.f7q3/catalogue/kicks-n-flicks-goa-mini-football-fields-9soy7roh71.jpg', 'A cutting-edge futsal pitch offering an immersive playing experience.', 'On-Site Physiotherapist', 'Power Backup', 'Ball Return Nets'),
('ven_033', 'Navelim Football Ground', 'Margao', 1700, '37m x 19m', 'https://www.mappls.com/place/PJK11A_1662036443317_0.png', 'A well-designed football ground, ideal for training and friendly games.', 'Parking Area', 'Visitor Restroom', 'Coach Viewing Platform'),
('ven_034', 'Tiger Turf', 'Margao', 1500, '34m x 18m', 'https://content.jdmagicbox.com/comp/goa/i5/0832px832.x832.220317225912.u4i5/catalogue/nfa-nagoa-futsal-arena-saligao-goa-stadiums-xpsn1xzfdi.jpg', 'A recently renovated futsal turf with high-quality infrastructure.', 'Locker Facility', 'Sports Drinks Available', 'Player Lounge Access'),
('ven_035', 'Tilak Maidan Stadium', 'Vasco', 1800, '42m x 24m', 'https://content.jdmagicbox.com/v2/comp/goa/f2/0832px832.x832.230430224856.w6f2/catalogue/agnel-futsal-arena-mapusa-goa-futsal-courts-mn568d6b4h-250.jpg', 'A stadium-style football ground with seating and modern facilities.', 'Tournament Management Service', 'Shower Rooms', 'Referee Changing Rooms'),
('ven_036', 'Rose Circle Ground', 'Vasco', 1250, '30m x 16m', 'https://goemkarponn.com/wp-content/uploads/2024/09/Dnyanprassarak-Mandals-College.jpg', 'A unique circular ground offering a different playing experience.', 'Photo/Video Recording', 'Fast Food Corner', 'Event Live Streaming'),
('ven_037', 'IMS Football Ground', 'Vasco', 1350, '31m x 17m', 'https://media.hudle.in/venues/8e1f1ce9-b9e0-4142-a3a7-301044ba2c61/photo/e304c997efff4a22a6d4e7d4d4df3f5cfec153f9', 'A popular football training ground used by local teams and academies.', 'Beverage Counter', 'Instant Replay Setup', 'Player Recovery Zone'),
('ven_038', 'Mpt Ground', 'Vasco', 1400, '35m x 19m', 'https://content.jdmagicbox.com/comp/goa/a4/0832px832.x832.200930011201.j2a4/catalogue/poriat-football-ground-calangute-goa-sports-clubs-63186pzta2.jpg', 'A high-speed futsal turf suited for technical and tactical training.', 'Shaded Seating', 'Drinking Water Filter', 'Multi-Sport Facility'),
('ven_039', 'Cansaulim Sports Stadium', 'Cansaulim', 1550, '39m x 21m', 'https://www.mappls.com/place/48FD3D_1662033457955_0.png', 'A scenic sports stadium known for hosting annual football tournaments.', 'Waterproof Surface', 'Soft Play Zone', 'Personal Coaching Sessions'),
('ven_040', 'Lit Arena Futsal', 'Cansaulim', 1150, '28m x 16m', 'https://content3.jdmagicbox.com/comp/goa/i3/0832px832.x832.190517102700.g1i3/catalogue/o-jogo-goa-futsal-turf-porvorim-goa-sports-clubs-ovvewjbgnc.jpg', 'A well-maintained futsal arena with top-class playing conditions.', 'Fan Area', 'Community Football Events', 'High-Speed Wi-Fi'),
('ven_041', 'Dr Tristao Braganza Da Cunha Sports Complex', 'Cansaulim', 1450, '36m x 20m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSzuWZUZQieiQ93uNrPza0gBjGxrpOLHasNRg&s', 'A modern sports complex featuring high-end facilities and seating.', 'Tactical Whiteboard', 'Wall Climbing Facility', 'VIP Spectator Lounge'),
('ven_042', 'Camorlim Village Football Ground', 'Cansaulim', 1300, '32m x 18m', 'https://content.jdmagicbox.com/comp/goa/l5/0832px832.x832.220612020300.r8l5/catalogue/rosa-arena-airport-dabolim-goa-futsal-courts-df4jr2j21v.jpg', 'A village football ground with a rich history and great community support.', 'Referee Stand', 'Outdoor Benches', 'LED Floodlights'),
('ven_043', 'Lit Arena Futsal', 'Ponda', 1200, '30m x 17m', 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcR__nhbZf-Wa_K-C2hbZipmtnKwlrCFzjcsCw&s', 'A premium futsal facility equipped with high-speed turf and lighting.', 'Event Hosting Facility', 'Medical Assistance Booth', 'Automated Booking System'),
('ven_044', 'Majestic Futsal Turf', 'Ponda', 1250, '29m x 16m', 'https://content.jdmagicbox.com/comp/goa/d1/0832px832.x832.171225191400.a5d1/catalogue/sangolda-football-ground-sangolda-goa-sports-ground-rg4fpw2x5b.jpg', 'A small yet professional futsal ground known for competitive leagues.', 'Warm-up Area', 'Gaming Zone Nearby', 'On-Site Physiotherapy');

-- --------------------------------------------------------

--
-- Table structure for table `venue_reviews`
--

CREATE TABLE `venue_reviews` (
  `v_review_id` varchar(255) NOT NULL,
  `venue_id` varchar(255) DEFAULT NULL,
  `ratings` tinyint(4) DEFAULT NULL,
  `v_review` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `academys`
--
ALTER TABLE `academys`
  ADD PRIMARY KEY (`ac_id`),
  ADD UNIQUE KEY `ac_id` (`ac_id`),
  ADD KEY `venue_id` (`venue_id`);

--
-- Indexes for table `academy_reviews`
--
ALTER TABLE `academy_reviews`
  ADD PRIMARY KEY (`a_review_id`),
  ADD KEY `ac_id` (`ac_id`);

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
-- Indexes for table `register`
--
ALTER TABLE `register`
  ADD PRIMARY KEY (`reg_id`),
  ADD KEY `tr_id` (`tr_id`),
  ADD KEY `email` (`email`);

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
  ADD PRIMARY KEY (`email`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `venue`
--
ALTER TABLE `venue`
  ADD PRIMARY KEY (`venue_id`);

--
-- Indexes for table `venue_reviews`
--
ALTER TABLE `venue_reviews`
  ADD PRIMARY KEY (`v_review_id`),
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
-- Constraints for table `register`
--
ALTER TABLE `register`
  ADD CONSTRAINT `register_ibfk_1` FOREIGN KEY (`tr_id`) REFERENCES `tournaments` (`tr_id`),
  ADD CONSTRAINT `register_ibfk_2` FOREIGN KEY (`email`) REFERENCES `user` (`email`);

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
