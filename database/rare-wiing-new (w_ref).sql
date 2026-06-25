-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 10, 2025 at 09:14 AM
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
-- Database: `rare-wiing-new`
--

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `available_sizes` varchar(255) DEFAULT NULL,
  `color_options` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `season` varchar(100) DEFAULT NULL,
  `sku_id` varchar(100) DEFAULT NULL,
  `color_way` varchar(100) DEFAULT NULL,
  `main_color` varchar(100) DEFAULT NULL,
  `front_image` varchar(255) DEFAULT NULL,
  `back_image` varchar(255) DEFAULT NULL,
  `side_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `available_sizes`, `color_options`, `description`, `season`, `sku_id`, `color_way`, `main_color`, `front_image`, `back_image`, `side_image`, `created_at`) VALUES
(6, 'Fear of God Essentials Relaxed Hoodie Iron (SS22)', 2000.00, 'xxs,xs,s,m,l,xs', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Fall/Winter 2024', '192BT246250F', 'iron,black', 'red', '_front_premium-sweatshirt-black.jpg', '_back_tshirt.png', '_side_hoodies.png', '2025-09-09 05:59:18'),
(7, 'Fear of God Essentials Relaxed Hoodie Off Black (SS23)', 5000.00, 'xxs,xs,s,m,l,xs', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Fall/Winter 2024', '192BT246250F', 'iron,black', 'Black', 'fitted-tee.jpg', 'fitted-tee.png', 'sweatshirt.png', '2025-09-09 06:20:22'),
(8, 'Fear of God Essentials Relaxed Hoodie Plum (SS23)', 8000.00, 'xxs,xs,s,m,l,xs', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Spring/Summer 2023', '192BT222054F', 'Sand', 'Black', 'premium-sweatshirt-black.jpg', 'premium.png', 'tshirt.png', '2025-09-09 06:21:30'),
(9, 'Fear of God Essentials Relaxed Hoodie Wood (FW22)', 6000.00, 'xxs,xs,s,m,', 'iron,black', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Fall/Winter ', '192BT246250F', 'iron,black', 'iron', 'premium.png', 'premium-sweatshirt-black.jpg', 'sweatshirt.png', '2025-09-09 06:23:15'),
(10, 'Fear of God Essentials Relaxed Hoodie Seal (SS23)', 9000.00, 'xxs,xs,s,m,l,xs', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Spring/Summer 2023', '192BT246250F', 'iron,black', 'Black', '_front_slide 8 white color red text 1.jpg', 'second-2.jpg', 'about-images.jpg', '2025-09-09 06:24:29');

-- --------------------------------------------------------

--
-- Table structure for table `promo_codes`
--

CREATE TABLE `promo_codes` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_codes`
--

INSERT INTO `promo_codes` (`id`, `code`, `discount_percent`, `expiry_date`, `status`, `created_at`) VALUES
(1, 'SAVE10', 10, '2025-12-31', 1, '2025-09-05 10:42:36'),
(2, 'NEWYEAR25', 25, '2026-01-15', 1, '2025-09-05 10:42:36'),
(3, 'NEWYEAR24', 24, '2025-09-06', 1, '2025-09-06 08:32:33');

-- --------------------------------------------------------

--
-- Table structure for table `promo_usage`
--

CREATE TABLE `promo_usage` (
  `id` int(11) NOT NULL,
  `user_email` varchar(150) NOT NULL,
  `promo_id` int(11) NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `promo_usage`
--

INSERT INTO `promo_usage` (`id`, `user_email`, `promo_id`, `used_at`) VALUES
(1, 'abc@gmail.com', 1, '2025-09-05 10:54:23'),
(2, 'abc@gmail.com', 2, '2025-09-05 11:02:25'),
(4, 'def@gmail.com', 1, '2025-09-08 07:22:14'),
(5, 'grt@gmail.com', 1, '2025-09-08 07:38:03'),
(6, 'lkj@gmail.com', 1, '2025-09-08 07:49:07');

-- --------------------------------------------------------

--
-- Table structure for table `subscribe`
--

CREATE TABLE `subscribe` (
  `id` int(11) NOT NULL,
  `first_name` varchar(100) NOT NULL,
  `last_name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `terms` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `subscribe`
--

INSERT INTO `subscribe` (`id`, `first_name`, `last_name`, `email`, `terms`, `created_at`) VALUES
(1, 'abc', 'def', 'abc@gmail.com', 1, '2025-09-05 09:11:28'),
(2, 'def', 'def', 'def@gmail.com', 1, '2025-09-05 09:19:32'),
(3, 'sty', 'def', 'sty@gmail.com', 1, '2025-09-05 09:20:43'),
(4, 'styz', 'def', 'styz@gmail.com', 1, '2025-09-05 09:25:37'),
(6, 'stv', 'fed', 'stv@gmail.com', 1, '2025-09-05 10:18:48'),
(7, 'stev', 'def', 'stev@gmail.com', 1, '2025-09-05 10:37:45'),
(8, 'klm', 'lkm', 'klm@gmail.com', 1, '2025-09-05 11:51:08');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fname` varchar(50) NOT NULL,
  `lname` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `referral_code` varchar(50) NOT NULL,
  `referred_by` varchar(50) DEFAULT NULL,
  `balance` decimal(10,2) DEFAULT 0.00,
  `term` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `promo_codes`
--
ALTER TABLE `promo_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `promo_usage`
--
ALTER TABLE `promo_usage`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_usage` (`user_email`,`promo_id`),
  ADD KEY `promo_id` (`promo_id`);

--
-- Indexes for table `subscribe`
--
ALTER TABLE `subscribe`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `referral_code` (`referral_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `promo_codes`
--
ALTER TABLE `promo_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `promo_usage`
--
ALTER TABLE `promo_usage`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=40;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `promo_usage`
--
ALTER TABLE `promo_usage`
  ADD CONSTRAINT `promo_usage_ibfk_1` FOREIGN KEY (`promo_id`) REFERENCES `promo_codes` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
