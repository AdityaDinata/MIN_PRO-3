-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 14, 2024 at 04:24 AM
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
-- Database: `online_health`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_table`
--

CREATE TABLE `activity_table` (
  `id` int(11) NOT NULL,
  `activity_name` varchar(255) NOT NULL,
  `duration_minutes` int(11) NOT NULL,
  `activity_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `consultations`
--

CREATE TABLE `consultations` (
  `id` int(11) NOT NULL,
  `patient_id` int(11) DEFAULT NULL,
  `consultation_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `message` text DEFAULT NULL,
  `response` text DEFAULT NULL,
  `responded` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `consultations`
--

INSERT INTO `consultations` (`id`, `patient_id`, `consultation_date`, `message`, `response`, `responded`) VALUES
(7, 258, '2024-04-13 06:20:11', 'halo', 'halo juga', 1),
(8, 258, '2024-04-13 12:21:58', 'saya susah untuk makan ', NULL, 0),
(11, 267, '2024-04-14 02:18:05', 'halo\r\n', 'halo juga', 1);

-- --------------------------------------------------------

--
-- Table structure for table `doctors`
--

CREATE TABLE `doctors` (
  `id` int(11) NOT NULL,
  `specialization` varchar(100) NOT NULL,
  `practice_license_number` varchar(50) NOT NULL,
  `education_history` text NOT NULL,
  `work_experience` text NOT NULL,
  `registration_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `id_dokter` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `doctors`
--

INSERT INTO `doctors` (`id`, `specialization`, `practice_license_number`, `education_history`, `work_experience`, `registration_date`, `id_dokter`) VALUES
(1, 'Spesialis Kedokteran Fisik', '12345678901234', 'S1 Kedokteran Umum, Spesialis Kedokteran Fisik', '5 tahun praktik sebagai dokter fisioterapi di RS A, 3 tahun sebagai kepala departemen rehabilitasi di RS B', '2024-04-13 04:32:13', 258),
(2, 'Spesialis Gizi Klinik', '23456789012345', 'S1 Gizi Kesehatan, Spesialis Gizi Klinik', '2 tahun praktik sebagai ahli gizi di Klinik C, 1 tahun sebagai konsultan gizi di Pusat Kesehatan Masyarakat', '2024-04-13 04:32:13', 259),
(3, 'Dokter Umum', '34567890123456', 'S1 Kedokteran, Program Internship di RS D', '3 tahun praktik sebagai dokter umum di Puskesmas X, 2 tahun sebagai dokter praktek mandiri di Klinik Y', '2024-04-13 04:32:13', 260);

-- --------------------------------------------------------

--
-- Table structure for table `food_intake_table`
--

CREATE TABLE `food_intake_table` (
  `id` int(11) NOT NULL,
  `food_name` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `intake_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `health_data_table`
--

CREATE TABLE `health_data_table` (
  `id` int(11) NOT NULL,
  `weight` decimal(5,2) NOT NULL,
  `height` decimal(5,2) NOT NULL,
  `measurement_date` date NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `Role` varchar(50) NOT NULL DEFAULT 'User',
  `Nama` varchar(255) NOT NULL,
  `umur` int(11) NOT NULL,
  `gender` varchar(10) NOT NULL,
  `no_telpon` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `Role`, `Nama`, `umur`, `gender`, `no_telpon`, `email`) VALUES
(255, 'admin', '12345', 'Admin', 'Admin', 35, 'Laki-laki', '000', 'admin@gmail.com'),
(258, 'dokter1', 'password1', 'Dokter', 'Dr. Maria Sulistyo', 60, 'Perempuan', '081234567890', 'maria@gmail.com'),
(259, 'dokter2', 'password2', 'Dokter', 'Dr. Andi Pratama', 55, 'Laki-laki', '087654321098', 'andi@gmail.com'),
(260, 'dokter3', 'password3', 'Dokter', 'Dr. Budi Santoso', 45, 'Laki-laki', '089876543210', 'budi@gmail.com'),
(267, 'adit', '1234', 'User', 'Aditya Dinata ', 19, 'Laki-laki', '082252661012', 'adityadinata647@gmail.com');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_table`
--
ALTER TABLE `activity_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `consultations`
--
ALTER TABLE `consultations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `consultations_ibfk_1` (`patient_id`);

--
-- Indexes for table `doctors`
--
ALTER TABLE `doctors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_user_id` (`id_dokter`);

--
-- Indexes for table `food_intake_table`
--
ALTER TABLE `food_intake_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `health_data_table`
--
ALTER TABLE `health_data_table`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_table`
--
ALTER TABLE `activity_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `consultations`
--
ALTER TABLE `consultations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `doctors`
--
ALTER TABLE `doctors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `food_intake_table`
--
ALTER TABLE `food_intake_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `health_data_table`
--
ALTER TABLE `health_data_table`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=268;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_table`
--
ALTER TABLE `activity_table`
  ADD CONSTRAINT `activity_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `consultations`
--
ALTER TABLE `consultations`
  ADD CONSTRAINT `consultations_ibfk_1` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `doctors`
--
ALTER TABLE `doctors`
  ADD CONSTRAINT `fk_user_id` FOREIGN KEY (`id_dokter`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `food_intake_table`
--
ALTER TABLE `food_intake_table`
  ADD CONSTRAINT `food_intake_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `health_data_table`
--
ALTER TABLE `health_data_table`
  ADD CONSTRAINT `health_data_table_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
