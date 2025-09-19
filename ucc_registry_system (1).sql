-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 07, 2024 at 01:26 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ucc_registry_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `course_code` varchar(10) NOT NULL,
  `title` varchar(100) NOT NULL,
  `credits` int(11) NOT NULL,
  `degree_level` enum('Undergraduate','Graduate') NOT NULL,
  `prerequisites` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`course_code`, `title`, `credits`, `degree_level`, `prerequisites`) VALUES
('CS101', 'Introduction to Computer Science', 3, 'Undergraduate', NULL),
('DS601', 'Machine Learning', 3, 'Graduate', 'CS501'),
('MATH301', 'Advanced Calculus', 3, 'Undergraduate', 'MATH101'),
('PHYS201', 'Classical Mechanics', 4, 'Undergraduate', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_enrollment`
--

CREATE TABLE `course_enrollment` (
  `enrollment_id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `student_id` int(11) NOT NULL,
  `coursework_grade` decimal(5,2) DEFAULT NULL,
  `final_exam_grade` decimal(5,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_enrollment`
--

INSERT INTO `course_enrollment` (`enrollment_id`, `course_code`, `student_id`, `coursework_grade`, `final_exam_grade`) VALUES
(7, 'PHYS201', 9, NULL, NULL),
(12, 'DS601', 5, NULL, NULL),
(13, 'MATH301', 5, NULL, NULL),
(14, 'PHYS201', 5, NULL, NULL),
(15, 'PHYS201', 10, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `course_schedule`
--

CREATE TABLE `course_schedule` (
  `schedule_id` int(11) NOT NULL,
  `course_code` varchar(10) NOT NULL,
  `semester` enum('Spring','Summer','Fall','Winter') NOT NULL,
  `year` year(4) NOT NULL,
  `section` varchar(10) NOT NULL,
  `lecturer_id` int(11) DEFAULT NULL,
  `day_of_week` enum('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday') NOT NULL,
  `time` time NOT NULL,
  `location` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `course_schedule`
--

INSERT INTO `course_schedule` (`schedule_id`, `course_code`, `semester`, `year`, `section`, `lecturer_id`, `day_of_week`, `time`, `location`) VALUES
(1, 'CS101', 'Fall', 2024, 'A', 1, 'Monday', '09:00:00', 'Room 101'),
(3, 'MATH301', 'Fall', 2024, 'A', 3, 'Wednesday', '11:00:00', 'Room 303'),
(5, 'DS601', 'Fall', 2024, 'D', 5, 'Friday', '15:00:00', 'Room 505'),
(6, 'CS101', 'Fall', 2024, 'Maxime eni', 1, 'Wednesday', '17:00:00', 'room 334');

-- --------------------------------------------------------

--
-- Table structure for table `lecturers`
--

CREATE TABLE `lecturers` (
  `lecturer_id` int(11) NOT NULL,
  `title` varchar(10) DEFAULT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `department` varchar(50) DEFAULT NULL,
  `position` enum('Adjunct','Staff') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `lecturers`
--

INSERT INTO `lecturers` (`lecturer_id`, `title`, `first_name`, `last_name`, `department`, `position`) VALUES
(1, 'Dr.', 'Alice', 'Walker', 'Computer Science', 'Staff'),
(2, 'Prof.', 'John', 'Miller', 'Physics', 'Staff'),
(3, 'Dr.', 'Emma', 'Green', 'Mathematics', 'Adjunct'),
(4, 'Prof.', 'Michael', 'Taylor', 'English Literature', 'Staff'),
(5, 'Dr.', 'Sophia', 'Johnson', 'Data Science', 'Adjunct');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `student_id` int(11) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `middle_name` varchar(50) DEFAULT NULL,
  `last_name` varchar(50) NOT NULL,
  `personal_email` varchar(100) NOT NULL,
  `student_email` varchar(100) DEFAULT NULL,
  `mobile_number` varchar(15) NOT NULL,
  `home_contact` varchar(15) DEFAULT NULL,
  `work_contact` varchar(15) DEFAULT NULL,
  `home_address` varchar(255) DEFAULT NULL,
  `next_of_kin` varchar(100) DEFAULT NULL,
  `next_of_kin_contact` varchar(15) DEFAULT NULL,
  `program_of_study` varchar(100) NOT NULL,
  `gpa` decimal(3,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`student_id`, `first_name`, `middle_name`, `last_name`, `personal_email`, `student_email`, `mobile_number`, `home_contact`, `work_contact`, `home_address`, `next_of_kin`, `next_of_kin_contact`, `program_of_study`, `gpa`) VALUES
(5, 'Sophia', 'L.', 'Johnson', 'sophia.johnson@gmail.com', 'sjohnson@ucc.edu', '5678901234', '', '', '654 Birch St', 'Edward Johnson', '9999999999', 'Data Science', '3.99'),
(9, 'Lenore', 'Signe Pacheco', 'Stokes', 'nedo@mailinator.com', 'cyfur@mailinator.com', '+1 (222) 482-66', '+1 (545) 395-71', '+1 (259) 478-14', 'Quos suscipit obcaec', 'Vel fugit est provi', '+1 (335) 345-69', 'Magna quis sit nesci', '3.55'),
(10, 'Chester', 'Clayton Bright', 'Blanchard', 'nypegyqin@mailinator.com', 'lenehyjaxo@mailinator.com', '+1 (352) 285-13', '+1 (434) 761-44', '+1 (633) 747-75', 'Quam esse omnis eli', 'Laboriosam sint tem', '+1 (939) 479-35', 'Eius ipsum minus al', '3.88');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('Admin','Student','Lecturer') NOT NULL,
  `student_id` int(11) DEFAULT NULL,
  `lecturer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `role`, `student_id`, `lecturer_id`) VALUES
(3, 'jane_lecturer', '7ffe1fae3bda46117b9e3c2c20d49c96', 'Lecturer', NULL, 1),
(4, 'admin', '0192023a7bbd73250516f069df18b500', 'Admin', NULL, NULL),
(6, 'sopia', '482c811da5d5b4bc6d497ffa98491e38', 'Student', 5, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`course_code`);

--
-- Indexes for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  ADD PRIMARY KEY (`enrollment_id`),
  ADD KEY `course_code` (`course_code`),
  ADD KEY `student_id` (`student_id`);

--
-- Indexes for table `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD PRIMARY KEY (`schedule_id`),
  ADD KEY `course_code` (`course_code`),
  ADD KEY `lecturer_id` (`lecturer_id`);

--
-- Indexes for table `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`lecturer_id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`student_id`),
  ADD UNIQUE KEY `personal_email` (`personal_email`),
  ADD UNIQUE KEY `student_email` (`student_email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `student_id` (`student_id`),
  ADD UNIQUE KEY `lecturer_id` (`lecturer_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  MODIFY `enrollment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `course_schedule`
--
ALTER TABLE `course_schedule`
  MODIFY `schedule_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `lecturer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `student_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `course_enrollment`
--
ALTER TABLE `course_enrollment`
  ADD CONSTRAINT `course_enrollment_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollment_ibfk_2` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE;

--
-- Constraints for table `course_schedule`
--
ALTER TABLE `course_schedule`
  ADD CONSTRAINT `course_schedule_ibfk_1` FOREIGN KEY (`course_code`) REFERENCES `courses` (`course_code`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `course_schedule_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `students` (`student_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`lecturer_id`) REFERENCES `lecturers` (`lecturer_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
