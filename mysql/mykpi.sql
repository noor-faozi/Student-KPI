-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 23, 2024 at 08:11 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `mykpi`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity`
--

CREATE TABLE `activity` (
  `act_id` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `sem` int(3) NOT NULL,
  `year` varchar(10) NOT NULL,
  `activity` text NOT NULL,
  `remark` text DEFAULT NULL,
  `img_path` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `activity`
--

INSERT INTO `activity` (`act_id`, `userID`, `sem`, `year`, `activity`, `remark`, `img_path`) VALUES
(1, 1, 2, '2021/2022', 'Participated in Hackathon Team', '', ''),
(2, 1, 1, '2022/2023', 'Collaborated on Group Project: Predictive Analytics AI Modelling for Students Handwriting Performance', '', ''),
(3, 1, 2, '2022/2023', 'Collaborated on Group Project: 3D Movie Creation using OpenGL', '', ''),
(4, 1, 1, '2023/2024', 'Participated in NextLevel2 Youth Empowerment Program', 'A collaboration between Nestlé and LOréal', '');

-- --------------------------------------------------------

--
-- Table structure for table `challenge`
--

CREATE TABLE `challenge` (
  `ch_id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `sem` int(3) DEFAULT NULL,
  `year` varchar(10) DEFAULT NULL,
  `challenge` text DEFAULT NULL,
  `plan` text DEFAULT NULL,
  `remark` text DEFAULT NULL,
  `img_path` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `challenge`
--

INSERT INTO `challenge` (`ch_id`, `userID`, `sem`, `year`, `challenge`, `plan`, `remark`, `img_path`) VALUES
(1, 1, 1, '2023/2024', 'Time management issues with many courses', 'Create a detailed study schedule with designated time slots for each subject and assignment.', '', 'diary-planning.jpg'),
(2, 1, 2, '2022/2023', 'Procrastination because of the many assignments stress', 'Break down larger tasks into smaller, more manageable chunks to reduce my overwhelm', 'UPDATE: The plan worked :)', NULL),
(3, 1, 1, '2022/2023', 'Exam anxiety because its my first time taking them f2f', 'Practice & develop a thorough exam preparation strategy, including mock exams', 'UPDATE: mock exams have helped a lot', ''),
(4, 1, 2, '2021/2022', 'Maintaining a healthy work-life balance', 'Allocating time for hobbies, exercise, and socializing.', '', ''),
(5, 1, 1, '2021/2022', 'Ineffective note-taking for lectures', 'Try various note-taking methods to find what works best for me', 'UPDATE: mind mapping works best for memorizing-based courses', '');

-- --------------------------------------------------------

--
-- Table structure for table `indicator`
--

CREATE TABLE `indicator` (
  `ind_id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `semYear` varchar(20) DEFAULT NULL,
  `cgpa` decimal(3,2) DEFAULT NULL,
  `facultyAct` int(11) DEFAULT NULL,
  `uniAct` int(11) DEFAULT NULL,
  `nationalAct` int(11) DEFAULT NULL,
  `interAct` int(11) DEFAULT NULL,
  `facultyComp` int(11) DEFAULT NULL,
  `uniComp` int(11) DEFAULT NULL,
  `nationalComp` int(11) DEFAULT NULL,
  `interComp` int(11) DEFAULT NULL,
  `certificate` int(11) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `activity_id` int(11) DEFAULT NULL,
  `competition_id` int(11) DEFAULT NULL,
  `certificate_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `indicator`
--

INSERT INTO `indicator` (`ind_id`, `userID`, `semYear`, `cgpa`, `facultyAct`, `uniAct`, `nationalAct`, `interAct`, `facultyComp`, `uniComp`, `nationalComp`, `interComp`, `certificate`, `remark`, `activity_id`, `competition_id`, `certificate_id`) VALUES
(30, 1, 'FACULTY KPI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 1, 'STUDENT AIM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 1, 'YEAR 1 SEM 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 1, 'YEAR 1 SEM 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 1, 'YEAR 2 SEM 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 1, 'YEAR 2 SEM 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 1, 'YEAR 3 SEM 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 1, 'YEAR 3 SEM 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 1, 'YEAR 4 SEM 1', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 1, 'YEAR 4 SEM 2', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kpiactivity`
--

CREATE TABLE `kpiactivity` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `semYear` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1: Faculty Competition\r\n2: University Competition\r\n3: National Competition\r\n4: International Competition',
  `remark` varchar(255) DEFAULT NULL,
  `indicatorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kpicertificate`
--

CREATE TABLE `kpicertificate` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `semYear` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `remark` varchar(255) DEFAULT NULL,
  `indicatorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `kpicompetition`
--

CREATE TABLE `kpicompetition` (
  `id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `semYear` varchar(20) DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `type` int(11) DEFAULT NULL COMMENT '1: Faculty Competition\r\n2: University Competition\r\n3: National Competition\r\n4: International Competition',
  `remark` varchar(255) DEFAULT NULL,
  `indicatorID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `profile`
--

CREATE TABLE `profile` (
  `id` int(4) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `username` varchar(255) DEFAULT NULL,
  `program` varchar(50) DEFAULT NULL,
  `mentor` varchar(255) DEFAULT NULL,
  `motto` text DEFAULT NULL,
  `img_path` varchar(20) DEFAULT 'photo.png'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `profile`
--

INSERT INTO `profile` (`id`, `userID`, `username`, `program`, `mentor`, `motto`, `img_path`) VALUES
(1, 1, 'Noor', 'Software Engineering', '', '', 'photo.png');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `userID` int(11) NOT NULL,
  `matricNo` varchar(20) NOT NULL,
  `userEmail` varchar(100) NOT NULL,
  `userPwd` varchar(255) NOT NULL,
  `userRoles` int(11) NOT NULL DEFAULT 2 COMMENT '1 - Admin, 2 - User',
  `registrationDate` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`userID`, `matricNo`, `userEmail`, `userPwd`, `userRoles`, `registrationDate`) VALUES
(1, '1234', 'noor@edu.com', '$2y$10$bH5h5BloinLfZDkElr5QB.9Fh3lZrC1x0eQGGHc4V5BvsrJJBP9He', 2, '2024-11-23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity`
--
ALTER TABLE `activity`
  ADD PRIMARY KEY (`act_id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `challenge`
--
ALTER TABLE `challenge`
  ADD PRIMARY KEY (`ch_id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `indicator`
--
ALTER TABLE `indicator`
  ADD PRIMARY KEY (`ind_id`),
  ADD UNIQUE KEY `uc_user_semYear` (`userID`,`semYear`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_activity` (`activity_id`),
  ADD KEY `fk_competition` (`competition_id`),
  ADD KEY `fk_certificate` (`certificate_id`);

--
-- Indexes for table `kpiactivity`
--
ALTER TABLE `kpiactivity`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_kpiactivity_indicator` (`indicatorID`);

--
-- Indexes for table `kpicertificate`
--
ALTER TABLE `kpicertificate`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_kpicertificate_indicator` (`indicatorID`);

--
-- Indexes for table `kpicompetition`
--
ALTER TABLE `kpicompetition`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`),
  ADD KEY `fk_kpicompetition_indicator` (`indicatorID`);

--
-- Indexes for table `profile`
--
ALTER TABLE `profile`
  ADD PRIMARY KEY (`id`),
  ADD KEY `userID` (`userID`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`userID`),
  ADD UNIQUE KEY `userEmail` (`userEmail`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity`
--
ALTER TABLE `activity`
  MODIFY `act_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `challenge`
--
ALTER TABLE `challenge`
  MODIFY `ch_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `indicator`
--
ALTER TABLE `indicator`
  MODIFY `ind_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- AUTO_INCREMENT for table `kpiactivity`
--
ALTER TABLE `kpiactivity`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `kpicertificate`
--
ALTER TABLE `kpicertificate`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `kpicompetition`
--
ALTER TABLE `kpicompetition`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `profile`
--
ALTER TABLE `profile`
  MODIFY `id` int(4) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `userID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity`
--
ALTER TABLE `activity`
  ADD CONSTRAINT `activity_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `challenge`
--
ALTER TABLE `challenge`
  ADD CONSTRAINT `challenge_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;

--
-- Constraints for table `indicator`
--
ALTER TABLE `indicator`
  ADD CONSTRAINT `fk_activity` FOREIGN KEY (`activity_id`) REFERENCES `kpiactivity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_certificate` FOREIGN KEY (`certificate_id`) REFERENCES `kpicertificate` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_competition` FOREIGN KEY (`competition_id`) REFERENCES `kpicompetition` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicator_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`),
  ADD CONSTRAINT `indicator_ibfk_2` FOREIGN KEY (`activity_id`) REFERENCES `kpiactivity` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicator_ibfk_3` FOREIGN KEY (`competition_id`) REFERENCES `kpicompetition` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `indicator_ibfk_4` FOREIGN KEY (`certificate_id`) REFERENCES `kpicertificate` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kpiactivity`
--
ALTER TABLE `kpiactivity`
  ADD CONSTRAINT `fk_kpiactivity_indicator` FOREIGN KEY (`indicatorID`) REFERENCES `indicator` (`ind_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kpiactivity_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `kpicertificate`
--
ALTER TABLE `kpicertificate`
  ADD CONSTRAINT `fk_kpicertificate_indicator` FOREIGN KEY (`indicatorID`) REFERENCES `indicator` (`ind_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kpicertificate_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `kpicompetition`
--
ALTER TABLE `kpicompetition`
  ADD CONSTRAINT `fk_kpicompetition_indicator` FOREIGN KEY (`indicatorID`) REFERENCES `indicator` (`ind_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `kpicompetition_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`);

--
-- Constraints for table `profile`
--
ALTER TABLE `profile`
  ADD CONSTRAINT `profile_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `user` (`userID`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
