-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 26, 2024 at 02:49 PM
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
-- Database: `majorproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `picture` varchar(255) NOT NULL,
  `registered` datetime NOT NULL,
  `method` enum('Facebook','Google','linkedin') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`id`, `name`, `email`, `picture`, `registered`, `method`) VALUES
(1, 'dhara gandhi', 'dharahareshgandhi@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocIo2wvqEHnEE0zFr4OLn6i7TnS2m6A78xuGx2gHniY4B2RgKg=s96-c', '2024-06-26 14:32:05', 'Google'),
(2, 'dhara gandhi', 'dharagandhi458@gmail.com', 'https://lh3.googleusercontent.com/a/ACg8ocKkKZBzlE1asCxxrr-L9jI25XFtA0aCiOgKrEuMDJ8BftI8cA=s96-c', '2024-06-26 14:33:25', 'Google');

-- --------------------------------------------------------

--
-- Table structure for table `userinfo`
--

CREATE TABLE `userinfo` (
  `userid` int(100) NOT NULL,
  `username` varchar(15) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phoneno` int(8) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `userinfo`
--

INSERT INTO `userinfo` (`userid`, `username`, `password`, `email`, `phoneno`) VALUES
(1, 'abcd', '$2y$10$mX0npaguaUt0OJW8hHmsHuJYPH1Q8CXlF3Z6OI/QeTM.DwhEEsuDG', 'abcd@gmail.com', 12345678),
(2, 'abcde', '$2y$10$PRhZHyzo/qsw.HYQ97LuoekA/wTqIw.YHKuhRu0VNQ93r9ag9oyMy', 'abcde@gmail.com', 12349876),
(3, 'dhara', '$2y$10$kim0K/Q1d2ytVzV7ppbCeeWaKxxYtQeJRMjbNjIabuoC4b0jAUluS', 'dhara@gmail.com', 87651234),
(4, 'dhara1', '$2y$10$.anhNAzckHM46zICgBSNK.A7/Wb.2kNVPsAreobURNNxqoL/fspLq', 'dhara1@gmail.com', 9876123);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `userinfo`
--
ALTER TABLE `userinfo`
  ADD PRIMARY KEY (`userid`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `phoneno` (`phoneno`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `userinfo`
--
ALTER TABLE `userinfo`
  MODIFY `userid` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
