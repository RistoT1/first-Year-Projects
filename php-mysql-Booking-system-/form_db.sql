-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: 16.07.2025 klo 09:38
-- Palvelimen versio: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `form_db`
--

-- --------------------------------------------------------

--
-- Rakenne taululle `bookings`
--

CREATE TABLE `bookings` (
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `description` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `bookings`
--

INSERT INTO `bookings` (`booking_id`, `user_id`, `booking_date`, `start_time`, `end_time`, `description`, `created_at`) VALUES
(1, 0, '2025-08-01', '19:00:00', '20:00:00', 'Iltakävely', '2025-07-08 12:47:07'),
(2, 0, '2025-08-01', '20:00:00', '23:00:00', 'Elokuvailta', '2025-07-08 12:47:07'),
(3, 0, '2025-08-02', '13:00:00', '20:00:00', 'Työvuoro', '2025-07-08 12:47:07'),
(4, 0, '2025-08-03', '17:00:00', '20:00:00', 'Kuntosali + rentoutumista', '2025-07-08 12:47:07'),
(5, 0, '2025-08-04', '10:00:00', '12:00:00', 'Kirjastossa lukemassa', '2025-07-08 12:47:07'),
(6, 0, '2025-08-04', '14:00:00', '16:00:00', 'Ystävän tapaaminen', '2025-07-08 12:47:07'),
(7, 0, '2025-08-05', '09:00:00', '10:00:00', 'Aamupala & uutiset', '2025-07-08 12:47:07'),
(8, 0, '2025-08-05', '18:00:00', '20:00:00', 'Saunailta, leipä', '2025-07-08 12:47:07'),
(9, 2, '2025-08-06', '09:30:00', '11:30:00', 'Pyöräretki', '2025-07-08 12:47:07'),
(10, 0, '2025-08-06', '16:00:00', '18:00:00', 'Kotitöitä ja ruokaa', '2025-07-08 12:47:07'),
(11, 0, '2025-08-27', '19:00:00', '20:00:00', 'Illallinen ystävien kanssa', '2025-07-08 12:47:07'),
(12, 0, '2025-08-27', '20:00:00', '23:00:00', 'Lautapeli-ilta', '2025-07-08 12:47:07'),
(13, 2, '2025-09-10', '14:00:00', '16:00:00', 'Kävely puistossa', '2025-07-09 13:17:55'),
(14, 2, '2025-09-11', '16:40:00', '17:00:00', 'Nouseva 1', '2025-07-09 13:30:00'),
(15, 0, '2025-09-09', '16:40:00', '17:00:00', 'Nouseva 1', '2025-07-09 13:30:00');

-- --------------------------------------------------------

--
-- Rakenne taululle `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `nimi` varchar(100) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Vedos taulusta `users`
--

INSERT INTO `users` (`id`, `nimi`, `email`, `created_at`) VALUES
(1, 'nami', 'ristotoiv.r@gmail.com', '2025-07-08 10:27:37'),
(2, 'name', 'ristotoiv.rt@gmail.com', '2025-07-08 12:31:47'),
(3, 'name', 'ristotoiv@gmail.com', '2025-07-08 13:02:07'),
(4, 'pekka', 'olli.rt@gmail.com', '2025-07-09 10:10:39'),
(5, 'Risto Toivanen', 'ristotoiv.ot@gmail.com', '2025-07-09 13:29:18');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`booking_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `booking_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
