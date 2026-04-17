CREATE DATABASE IF NOT EXISTS `adet_db`;
USE `adet_db`;

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `role` enum('guest','user','admin') DEFAULT 'user',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


INSERT INTO `users` (`user_id`, `full_name`, `email`, `password`, `phone`, `address`, `role`, `created_at`) VALUES
(6, 'Leinardt Omadto', 'leinardt@anikahon.com', 'password123', '09123456781', 'Legazpi City, Albay', 'user', '2026-04-17 04:19:29'),
(7, 'Nyan Alcovindas', 'nyan@anikahon.com', 'password123', '09123456782', 'Legazpi City, Albay', 'user', '2026-04-17 04:19:29'),
(8, 'Lance Christopher Delos Reyes', 'lance@anikahon.com', 'password123', '09123456783', 'Daraga, Albay', 'user', '2026-04-17 04:19:29'),
(9, 'Rhona Mae Pancho', 'rhona@anikahon.com', 'password123', '09123456784', 'Legazpi City, Albay', 'admin', '2026-04-17 04:19:29');


ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`);


ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
COMMIT;