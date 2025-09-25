-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 25, 2025 at 05:59 PM
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
-- Database: `ro_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `student_id` int(11) NOT NULL,
  `event_id` int(11) NOT NULL,
  `time_in` datetime DEFAULT NULL,
  `time_out` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `attendance`
--

INSERT INTO `attendance` (`id`, `student_id`, `event_id`, `time_in`, `time_out`, `created_at`) VALUES
(1, 4, 10, '0000-00-00 00:00:00', '0000-00-00 00:00:00', '2025-09-25 15:30:51');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `title`, `event_date`, `description`) VALUES
(9, 'COElympics', '2025-09-29', 'POSSIBLE PARADE AND ENTRANCE OF COLORS\n'),
(10, 'Sample', '2025-09-09', 'Sample');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `student_number` varchar(50) NOT NULL,
  `name` varchar(100) NOT NULL,
  `gender` varchar(10) DEFAULT NULL,
  `course` varchar(100) NOT NULL,
  `college` varchar(100) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `additional_info` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_number`, `name`, `gender`, `course`, `college`, `contact_number`, `address`, `additional_info`) VALUES
(3, '24-050027', 'Aniel Adrian F Bolibol', 'Male', 'BS in Electronics Engineering', 'COE', '09 - numbers', 'Sample Address', ''),
(4, '24-040652', 'Angel Faith U Cabutaje', 'Female', 'Bachelor of Special Needs Education', 'CTE', '09 - numbers', 'Sample Address', ''),
(5, '24-050212', 'Krizia Reign R Diza', 'Female', 'BS in Electronics Engineering', 'COE', '09 - numbers', 'Sample Address\r\n\r\n', '\r\n'),
(6, '24-140077', 'John Rey V Galam', 'Male', 'BSIT', 'CCIS', '09 - numbers', 'Sample Address', ''),
(7, '24-040269', 'Mishael R Adzuara', 'Female', 'Bachelor of Secondary Education - Mathematics', 'CTE', '09 - numbers', 'Sample Address\r\n', ''),
(8, '23-010008', 'Eunice V Gaoiran', 'Female', 'BS Forestry', 'CAFSD', '09 - numbers', 'Piddig, Ilocos Norte', 'Corps Commander'),
(9, '23-010018', 'Xavier V Bartolome', 'Male', 'BS in Environmental Science', 'CAFSD', '09 - numbers', 'Sample Address', ''),
(10, '23-010446', 'Ian Cesar T Putolan', 'Male', 'BS Forestry', 'CAFSD', '09 - numbers', 'Sample Address', ''),
(11, '23-140007', 'John Benedict G Alberto', 'Male', 'BSCS', 'CCIS', '09 - numbers', 'Sample Address\r\n', ''),
(12, '23-030257', 'John Kenneth A Sagun', 'Male', 'BS in Economics', 'CBEA', '09 - numbers', 'Sample Address', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'admin', '8ddf878039b70767c4a5bcf4f0c4f65e'),
(2, 'Intelligence', 'fac989447cad2edbc89fbcba70003b36');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `ux_student_event` (`student_id`,`event_id`),
  ADD KEY `fk_att_event` (`event_id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_number` (`student_number`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `fk_att_event` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_att_student` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
