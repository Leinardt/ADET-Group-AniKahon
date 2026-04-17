-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 16, 2026 at 08:13 PM
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
-- Database: `ecommerce_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `product_name` varchar(200) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `description` text DEFAULT NULL,
  `image_url` varchar(500) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`product_id`, `category_id`, `product_name`, `price`, `stock_quantity`, `description`, `image_url`, `created_at`) VALUES
(1, 1, 'Silver Heart Pendant Necklace', 150.00, 30, 'Elegant silver-plated heart pendant on chain. Perfect gift for loved ones.', NULL, '2026-04-16 17:00:03'),
(2, 1, 'Gold Plated Name Necklace', 250.00, 20, 'Customizable name necklace (message us your name after ordering).', NULL, '2026-04-16 17:00:03'),
(3, 1, 'Pearl Choker Necklace', 180.00, 25, 'Classic faux pearl choker. Adjustable length.', NULL, '2026-04-16 17:00:03'),
(4, 1, 'Minimalist Bar Necklace', 120.00, 40, 'Simple gold bar pendant. Daily wear friendly.', NULL, '2026-04-16 17:00:03'),
(5, 1, 'Crystal Pendant Necklace', 200.00, 15, 'Shiny crystal pendant with silver chain. Catches the light beautifully.', NULL, '2026-04-16 17:00:03'),
(6, 2, 'Beaded Stretch Bracelet', 80.00, 50, 'Colorful elastic beaded bracelet. One size fits most.', NULL, '2026-04-16 17:00:03'),
(7, 2, 'Chain Link Bracelet', 130.00, 35, 'Delicate gold chain bracelet with lobster clasp.', NULL, '2026-04-16 17:00:03'),
(8, 2, 'Leather Wrap Bracelet', 160.00, 25, 'Brown faux leather wrap bracelet with magnetic closure.', NULL, '2026-04-16 17:00:03'),
(9, 2, 'Friendship Bracelet Set', 100.00, 60, 'Set of 2 matching thread bracelets. Share with a friend!', NULL, '2026-04-16 17:00:03'),
(10, 2, 'Charm Bracelet', 190.00, 20, 'Silver chain bracelet with 5 assorted charms.', NULL, '2026-04-16 17:00:03'),
(11, 3, 'Stud Earrings Set', 120.00, 45, 'Set of 3 pairs: gold, silver, and rose gold studs.', NULL, '2026-04-16 17:00:03'),
(12, 3, 'Hoops Earrings', 150.00, 40, 'Medium-sized gold hoops. Lightweight and comfortable.', NULL, '2026-04-16 17:00:03'),
(13, 3, 'Dangle Drop Earrings', 180.00, 30, 'Elegant teardrop dangle earrings with cubic zirconia.', NULL, '2026-04-16 17:00:03'),
(14, 3, 'Tassel Earrings', 140.00, 35, 'Colorful thread tassel earrings. Choose from 5 colors.', NULL, '2026-04-16 17:00:03'),
(15, 3, 'Clip-on Earrings', 130.00, 25, 'Stylish clip-on earrings for non-pierced ears. No pain, all style.', NULL, '2026-04-16 17:00:03'),
(16, 4, 'Cute Animal Phone Charm', 50.00, 100, 'Adorable animal design (cat, dog, bear, or bunny). Fits all phone cases.', NULL, '2026-04-16 17:00:03'),
(17, 4, 'Pearl Phone Strap', 70.00, 80, 'Elegant pearl bead phone strap. Wristlet style.', NULL, '2026-04-16 17:00:03'),
(18, 4, 'Initial Letter Charm', 60.00, 90, 'Gold charm with your choice of letter A-Z. Personalize your phone.', NULL, '2026-04-16 17:00:03'),
(19, 4, 'Flower Dangle Charm', 55.00, 85, 'Pressed flower design inside resin. Unique and pretty.', NULL, '2026-04-16 17:00:03'),
(20, 4, 'Kawaii Food Charm', 45.00, 120, 'Cute food designs: donut, ice cream, or boba tea.', NULL, '2026-04-16 17:00:03'),
(21, 5, 'Adjustable Heart Ring', 90.00, 60, 'Silver heart ring that fits any finger. Adjustable band.', NULL, '2026-04-16 17:00:03'),
(22, 5, 'Stackable Thin Rings Set', 150.00, 40, 'Set of 3 thin rings (gold, silver, rose gold). Mix and match.', NULL, '2026-04-16 17:00:03'),
(23, 5, 'Cubic Zirconia Promise Ring', 200.00, 30, 'Sparkling cubic zirconia stone. Looks like real diamond.', NULL, '2026-04-16 17:00:03'),
(24, 5, 'Infinity Symbol Ring', 110.00, 50, 'Elegant infinity design ring. Gold or silver available.', NULL, '2026-04-16 17:00:03'),
(25, 5, 'Minimalist Band Ring', 80.00, 70, 'Simple, thin band. Perfect for everyday wear.', NULL, '2026-04-16 17:00:03');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`product_id`),
  ADD KEY `category_id` (`category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `product_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
