-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 30, 2025 at 02:22 PM
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
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `username`, `password`, `created_at`) VALUES
(1, 'admin', '$2y$10$S2L6AevVVcnXtEYLweZU4u4OYvvZfqjDzaeQhbKQunn4lT2tCin9C', '2025-09-17 07:37:32');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_percent` int(11) NOT NULL,
  `expiry_date` date NOT NULL,
  `status` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_percent`, `expiry_date`, `status`, `created_at`) VALUES
(2, 'NEWYEAR24', 24, '2025-09-16', 1, '2025-09-12 04:50:05'),
(8, 'SAVE10', 10, '2025-10-08', 1, '2025-10-07 11:50:09');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `question` varchar(255) NOT NULL,
  `answer` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `question`, `answer`) VALUES
(1, 'hello', 'Hello! How can I help you today?'),
(3, 'return', 'You can return items within 14 days of delivery.'),
(4, 'payment', 'We accept cards, UPI, and digital wallets.'),
(5, 'track order', 'Once shipped, you will get a tracking link via email/SMS.'),
(6, 'hi', 'Hello'),
(8, 'shipping', 'Parcel will delivered in 6 to 7 day'),
(9, 'products', 'swtshirt,hoodies,tees'),
(10, 'products', 'swtshirt,hoodies,tees');

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(11) NOT NULL,
  `feedback` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `feedback`
--

INSERT INTO `feedback` (`id`, `user_id`, `rating`, `feedback`, `created_at`) VALUES
(1, 40, 5, 'Nice Product', '2025-09-19 10:44:51'),
(2, 40, 4, 'avdfgdf', '2025-09-19 10:50:59'),
(3, 40, 5, 'adsfsdg', '2025-09-22 04:27:50'),
(4, 3, 4, 'sdfsf', '2025-09-24 12:17:48'),
(5, 40, 5, 'nice', '2025-10-03 07:20:27'),
(6, 40, 5, 'nice', '2025-10-04 05:48:20'),
(7, 42, 5, 'nice product', '2025-10-04 09:38:08'),
(8, 42, 5, 'ok', '2025-10-04 10:56:11'),
(9, 42, 5, 'cAS', '2025-10-06 06:01:27'),
(10, 40, 5, 'Nice product', '2025-10-06 09:07:48'),
(11, 40, 5, 'GOOD', '2025-10-06 10:17:57'),
(12, 44, 5, 'asfsdf', '2025-10-07 05:30:28');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `order_id` varchar(100) NOT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `customer_name` varchar(100) NOT NULL,
  `customer_email` varchar(100) NOT NULL,
  `customer_phone` varchar(20) NOT NULL,
  `city` varchar(100) NOT NULL,
  `state` varchar(100) NOT NULL,
  `pincode` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `items` text DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `order_id`, `payment_id`, `signature`, `customer_name`, `customer_email`, `customer_phone`, `city`, `state`, `pincode`, `address`, `items`, `amount`, `status`, `created_at`) VALUES
(1, 40, 'order_RJMF62aAlphXT4', 'pay_RJMFC0u7pTAHqc', '4b79116ef45eefa919c1baffd127c688770c3eab533b09c7db07f8c17ea2f7fc', 'abc', 'abc@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', NULL, 8100, 'paid', '2025-09-19 06:15:31'),
(2, 40, 'order_RJNeiKxDQCdzjW', 'pay_RJNep7et5tNIKE', 'b168f8efec8c00dd818950e3f31f1af0374653863fea6f11d620ffd5d9311fd4', 'abc', 'abc@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', NULL, 2699, 'paid', '2025-09-19 07:38:28'),
(3, 40, 'order_RJO4zltR0eux8t', NULL, NULL, 'abc', 'abc@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', NULL, 7198, 'pending', '2025-09-19 08:03:21'),
(4, 40, 'order_RJO68HNgdSZZom', 'pay_RJO6DYDsv96KUq', '6560484b713462a291cd0dd9cdb1b37e46b1aecff65e9da881d1e42d4a9ba5e0', 'abc', 'abc@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', NULL, 7198, 'paid', '2025-09-19 08:04:26'),
(5, 40, 'order_RJOEczAyo9eZw0', 'pay_RJOEiqW7D0qEHT', '5ed373a593605cfdde66b1bb8a4cb1f1018cd35b385ce7d452e6467b6b6072d4', 'Ankit', 'abc@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Aether Hoodies x 1, Aether Hoodies x 1', 7198, 'paid', '2025-09-19 08:12:28'),
(6, 41, 'order_RJPWVk875TYGEm', 'pay_RJPWdPy0TQBwEm', 'bd9ed2ec856e1067e8ba1d5503f6b9053853f0aaff6b60a5d7ce39dee4e25661', 'Ankit', 'ankit@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1', 7200, 'paid', '2025-09-19 09:28:06'),
(7, 41, 'order_RJPq6qDelbvqqa', 'pay_RJPqDxbRyR3wyX', 'd9a736b96641a23358fef38c8f927118453536149195bb4affd2770393c00ed6', 'Ankit', 'ankit@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1, Arc Hoodies x 2, Aether Hoodies x 1', 18899, 'paid', '2025-09-19 09:46:39'),
(8, 41, 'order_RJQJRc6gQ2L8ee', 'pay_RJQJWvAGZV7qhh', '7bf829cebdd3ac13e861772e1c30cc3359cdfc5bc5395d9ab875f08a7e03c762', 'Ankit', 'ankit@gmail.com', '09662705761', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1, Arc Hoodies x 2, Aether Hoodies x 1', 18899, 'paid', '2025-09-19 10:14:25'),
(9, 41, 'order_RJQc2QXBndajhH', 'pay_RJQcAp3Md6rSMf', '0215bcdce1b22168fa247bd49be193cd65c159611620f77fd6bfff69d5dd17be', 'Ankit ja', 'abc@gmail.com', '2457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1, Arc Hoodies x 2, Aether Hoodies x 1', 18899, 'paid', '2025-09-19 10:32:01'),
(10, 40, 'order_RJQonhT4lXqCrV', 'pay_RJQouQ4nJw5tto', '20fea6b4dd9f5b015a0ad9354d8680f032e316b90308a1ab73df75a0f114c477', 'abc', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc  x 1', 1800, 'paid', '2025-09-19 10:44:06'),
(11, 40, 'order_RKW0C7cnjuIQ8K', 'pay_RKW0JdpNDoym1d', 'f06a6e66226aeed1a6cfcfe6d3e92cdac5eb93bc3595b93823dba15f2183badb', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodies x 1', 5000, 'paid', '2025-09-22 04:27:20'),
(12, 3, 'order_RLR4qQEjSoxg8i', 'pay_RLR4zEUNEkV0Tb', '5f8d3b473dcc0b84f345be69fe156b779cdfb01d65380fcf0219679ba2407557', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1', 9000, 'paid', '2025-09-24 12:17:18'),
(13, 40, 'order_ROuoeYcDN7Z5Q1', 'pay_ROuomX93TxZkm3', 'f7487d389fd3e1ad972500714e09553d845be6bd55a6aa04d9c45d4de9df05d9', 'abc', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Aether Hoodies x 1', 2999, 'paid', '2025-10-03 07:19:47'),
(14, 40, 'order_RPHmSJP3M3tbc5', 'pay_RPHmafCthdkcne', '4427452270197080c576f3c47df1d8a4a67cd30757468d4f259f91446421c49a', 'abc', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1, Arc Hoodies x 1', 13000, 'paid', '2025-10-04 05:47:39'),
(15, 42, 'order_RPLhIaJYw7W5Vw', 'pay_RPLhSqtaQo9Uez', '9a08af8a0ea60575adfd420f060a313f2d4e8b8d3f1b818501332153b698f0fb', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodies x 2, Arc  x 1, Arc Hoodies x 1', 17000, 'paid', '2025-10-04 09:37:33'),
(16, 42, 'order_RPN1uxJk4HlZDl', 'pay_RPN229HFSnvVDA', 'e8389e1ced6a5d8d847768385e5c0384c1b6a3284c42828e995232c0a4086fa2', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodies x 1', 5000, 'paid', '2025-10-04 10:55:46'),
(17, 42, 'order_RPNP0q2EJM7ok1', 'pay_RPNPB8UuUXXntx', 'f91a3d8b8d245486c967a1c96727e9e38b279ca82e802f09a35d56e26c9da077', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodies x 1, Arc Hoodie x 1', 13000, 'paid', '2025-10-04 11:17:38'),
(18, 42, 'order_RQ3raqgBU8KmhZ', NULL, NULL, 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', '[{\"id\":\"8\",\"title\":\"Arc Hoodie\",\"price\":\"8000.00\",\"image\":\"_front_hoodie-(front)-white.jpg\",\"size\":\"S\",\"color\":\"black\",\"main_color\":null,\"quantity\":\"2\"}]', 16000, 'pending', '2025-10-06 04:49:48'),
(19, 42, 'order_RQ4jXE2gIpEES1', NULL, NULL, 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', '[{\"id\":\"8\",\"title\":\"Arc Hoodie\",\"price\":\"8000.00\",\"image\":\"_front_hoodie-(front)-white.jpg\",\"size\":\"S\",\"color\":\"black\",\"main_color\":null,\"quantity\":\"2\"}]', 16000, 'pending', '2025-10-06 05:40:52'),
(20, 42, 'order_RQ54lW5y0rT08o', 'pay_RQ54tIl6KC19DT', 'b5eb2fd13a3707642916b2c18615998419f433d7695b7bdd9370e8240400fb4e', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1, Aether Hoodies x 2', 13998, 'paid', '2025-10-06 06:00:58'),
(21, 42, 'order_RQ5BirNSjG9Cmk', 'pay_RQ5BoYz3DdvrzF', 'ed498f95b15a1813e9f582ef1fdd848de8479820b4c0b2aef207ec309415672f', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1 = 8000.00, Aether Hoodies x 2 = 2999.00', 13998, 'paid', '2025-10-06 06:07:33'),
(22, 42, 'order_RQ5ShtppbMWWY2', 'pay_RQ5SzRWAfyWGw6', '9b24d9a905b73e35eb1278792ffa3cbea86b55a83fe41941babaf34d29c14627', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 1 = 8000.00, Aether Hoodies x 2 = 2999.00', 13998, 'paid', '2025-10-06 06:23:38'),
(23, 42, 'order_RQ5gDwyS4Ig320', 'pay_RQ5gJecI8vL1Jj', '761c61c13211c605647b5b852a1d8491171ada1ec55ba6bb86f507cd85985957', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Aether Hoodies x 1 = 2999.00, Arc Hoodies x 1 = 4999.00, Aurum Half-zip x 2 = 5999.00', 19996, 'paid', '2025-10-06 06:36:26'),
(24, 42, 'order_RQ5jkWknedmevH', 'pay_RQ5jpyF0D9IUIC', '57a41df246e378a9ce48253749d554821e7370df650cd689a6cdb26dd590c7ff', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Aether Hoodies x 1 = 2999.00, Arc Hoodies x 1 = 4999.00, Aurum Half-zip x 2 = 5999.00', 17996, 'paid', '2025-10-06 06:39:46'),
(25, 40, 'order_RQ8FVZOEsJp9Y9', 'pay_RQ8Fdsg7qnnXuN', 'c31bda0040281cf5ca8de86fb52a1e88a438640d3c423c247183f210f93380f9', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodie x 2 = 8000.00', 14400, 'paid', '2025-10-06 09:07:13'),
(26, 40, 'order_RQ9RZZh9CDslpC', 'pay_RQ9Rj3C0CSiIgK', '215eab10d8bbacf5971f707c7fdd7ac243b1cc28cb3d8d047396e232318048b3', 'Ankit ja', 'abc@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '11\r\nAadharshila Flat', 'Arc Hoodies x 1 = 5000.00, Arc Hoodie x 1 = 8000.00', 11700, 'paid', '2025-10-06 10:17:20'),
(27, 44, 'order_RQT5CUhrhJqS48', 'pay_RQT5Ky2Q9nXThJ', '10af706666868aede229c88c08e227735671a72bd5c17df02de6456d9f73bfb2', 'def', 'def@gmail.com', '02457362198', 'Ahmedabad', 'GJ', '380008', '23545,\r\nAadharshila Flat', 'Aether Hoodies x 1 = 5999.00, Aether Hoodies x 1 = 3999.00', 8998, 'paid', '2025-10-07 05:30:01');

-- --------------------------------------------------------

--
-- Table structure for table `orders_old`
--

CREATE TABLE `orders_old` (
  `id` int(11) NOT NULL,
  `order_id` varchar(100) NOT NULL,
  `payment_id` varchar(100) DEFAULT NULL,
  `signature` varchar(255) DEFAULT NULL,
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_email` varchar(100) DEFAULT NULL,
  `amount` int(11) NOT NULL,
  `status` enum('pending','paid','failed') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders_old`
--

INSERT INTO `orders_old` (`id`, `order_id`, `payment_id`, `signature`, `customer_name`, `customer_email`, `amount`, `status`, `created_at`) VALUES
(1, 'order_RIFgnnflYY9UKL', NULL, NULL, 'Guest', 'guest@example.com', 2700000, 'pending', '2025-09-16 11:11:54'),
(2, 'order_RIFhGtErwxOc4A', NULL, NULL, 'Guest', 'guest@example.com', 200000, 'pending', '2025-09-16 11:12:21'),
(3, 'order_RIG5ofMmPR1s1P', NULL, NULL, NULL, NULL, 2000, '', '2025-09-16 11:35:35'),
(4, 'order_RIGFEbkfdnqL6N', NULL, NULL, NULL, NULL, 2000, '', '2025-09-16 11:44:30'),
(5, 'order_RIGVRXt2oWyJdO', 'pay_RIGYkrO1j82jVu', 'c353cfc43207633e0658055191c4b617028d6c0d51a92f166cbe12a9a0e593eb', NULL, NULL, 2000, 'paid', '2025-09-16 11:59:51'),
(6, 'order_RIGeG2GT82Q1xn', 'pay_RIGeTW8CZH0hrn', '537936c6d0da4cbccba9bc1778b121ba82b82bfc1049d0fd98ff47a0cada7a8d', NULL, NULL, 32000, 'paid', '2025-09-16 12:08:11'),
(7, 'order_RIGg2YbFS5IeoJ', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:09:52'),
(8, 'order_RIGqoE4t3O012G', 'pay_RIGs5U1XepjM9b', 'd04c2812ecfd8515c232f21f27a6bedc729f52c43561745249a6a22cc8b86317', NULL, NULL, 32000, 'paid', '2025-09-16 12:20:04'),
(9, 'order_RIHF2xis6GQbCo', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:43:01'),
(10, 'order_RIHFwfWAqlTLvo', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:43:52'),
(11, 'order_RIHG54MDgGiJLp', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:44:00'),
(12, 'order_RIHGQKv0ImuQKQ', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:44:19'),
(13, 'order_RIHGXybYXcGrSF', NULL, NULL, NULL, NULL, 32000, '', '2025-09-16 12:44:26'),
(14, 'order_RIYTHqvUHFk65v', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:34:17'),
(15, 'order_RIYjwcIXjsbWyU', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:50:03'),
(16, 'order_RIYl9x2pOIigGU', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:51:12'),
(17, 'order_RIYq4RaGvdakz2', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:55:51'),
(18, 'order_RIYqOaPtgFIoQY', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:56:09'),
(19, 'order_RIYqZeatkXfQHu', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:56:20'),
(20, 'order_RIYqltbwaAxHXZ', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:56:31'),
(21, 'order_RIYrFdYSIjBmee', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:56:58'),
(22, 'order_RIYsmjW8WHoETh', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:58:25'),
(23, 'order_RIYtGywq0wbaeV', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 05:58:53'),
(24, 'order_RIYuqpja6z8I7n', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:00:22'),
(25, 'order_RIYxDVV2IGxDw6', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:02:37'),
(26, 'order_RIYxfupS4Pm2TR', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:03:03'),
(27, 'order_RIYy0pMcuR3sg5', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:03:22'),
(28, 'order_RIYzMqypxbhPJQ', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:04:39'),
(29, 'order_RIZ1cXIDjZG1BW', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:06:47'),
(30, 'order_RIZ2OIUIKqEO07', NULL, NULL, NULL, NULL, 8000, '', '2025-09-17 06:07:31'),
(31, 'order_RIZ7sna45HJT1m', NULL, NULL, NULL, NULL, 5000, '', '2025-09-17 06:12:43'),
(32, 'order_RIZ8RpzVE3NTH3', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:13:15'),
(33, 'order_RIZ9KZfd0i1Jbe', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:14:05'),
(34, 'order_RIZBBh79C0kSw5', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:15:50'),
(35, 'order_RIZDcaP8556pcv', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:18:09'),
(36, 'order_RIZEOxslZAkG46', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:18:53'),
(37, 'order_RIZIeybROp3png', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:22:55'),
(38, 'order_RIZJLpnINOtSV8', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:23:34'),
(39, 'order_RIZJuBAd0Vj9TE', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:24:06'),
(40, 'order_RIZKCZummiDHZB', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:24:22'),
(41, 'order_RIZKgIGupgIBNq', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:24:50'),
(42, 'order_RIZNh2ldGCodpF', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:27:41'),
(43, 'order_RIZQShWTDW3I4m', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:30:18'),
(44, 'order_RIZSZguyUJmWew', NULL, NULL, NULL, NULL, 10000, '', '2025-09-17 06:32:18'),
(45, 'order_RIZSr5uTJejJi5', 'pay_RIZT1vwoz5UUG7', '20a3cefbe761eba987956c105bc260a1a63f9f2e149d7ba60470abe18fcf8432', NULL, NULL, 10000, 'paid', '2025-09-17 06:32:34'),
(46, 'order_RIZYcEcGVsZtB5', NULL, NULL, NULL, NULL, 5000, '', '2025-09-17 06:38:01'),
(47, 'order_RIdlxeZynoRfjk', 'pay_RIdmExNKsYkuJe', '061d4946f55216199b072d2649408747f3501d014306f00962a4a7fffc0dcc7e', NULL, NULL, 6000, 'paid', '2025-09-17 10:45:26'),
(48, 'order_RIdmw49SnAKfO4', NULL, NULL, NULL, NULL, 6000, '', '2025-09-17 10:46:21'),
(49, 'order_RIedypDnb7pUuo', 'pay_RIeempNT7DX9rd', 'dc66b95f3f46e8def5bc6399c6093584c07c75e5ab645423cc10a8888d4ffbab', NULL, NULL, 8000, 'paid', '2025-09-17 11:36:34'),
(50, 'order_RJ1PSFIZ2oq9M9', NULL, NULL, NULL, NULL, 10000, '', '2025-09-18 09:52:46'),
(51, 'order_RJ3BBGzvI2ooWj', NULL, NULL, NULL, NULL, 23994, '', '2025-09-18 11:36:38'),
(52, 'order_RJ3m0FHa2RM0S8', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:11:30'),
(53, 'order_RJ3m48wGvxX4Gb', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:11:33'),
(54, 'order_RJ3m7SLWXP0fQi', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:11:36'),
(55, 'order_RJ3mFlvRL96pXD', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:11:44'),
(56, 'order_RJ3pt2UBVnjeA2', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:15:10'),
(57, 'order_RJ3sieGRefdcUt', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:17:51'),
(58, 'order_RJ48Td4oQmnkFR', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:32:46'),
(59, 'order_RJ4HW3dFp5syWe', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:41:20'),
(60, 'order_RJ4KTli2mjmsF4', NULL, NULL, NULL, NULL, 8000, '', '2025-09-18 12:44:08'),
(61, 'order_RJ4tZS932R3zaw', NULL, NULL, NULL, NULL, 7200, '', '2025-09-18 13:17:21'),
(62, 'order_RJ4tlHdNjuSEYv', NULL, NULL, NULL, NULL, 7200, '', '2025-09-18 13:17:32'),
(63, 'order_RJ51QTL6EUfcXz', NULL, NULL, NULL, NULL, 7200, '', '2025-09-18 13:24:47'),
(64, 'order_RJ56HEQdpyrd6c', 'pay_RJ56PKYRnV76Xz', '22b9fcd8cd16d84768c50ac2da2cbf40f23229ddc28ac0b2c15ae94c33775c85', 'abc', 'abc@gmail.com', 55797, 'paid', '2025-09-18 13:29:23'),
(65, 'order_RJLyrbJUqLu6ke', 'pay_RJLz03qUDsltZ9', 'c94ef90b358a5df8e5b208f11f2f4fd11098bfa8add43a5a6dd538afe94e9c84', 'abc', 'abc@gmail.com', 8100, 'paid', '2025-09-19 06:00:09');

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
  `category` varchar(150) NOT NULL,
  `gender` varchar(150) NOT NULL,
  `out_stock` tinyint(1) DEFAULT 0,
  `front_image` varchar(255) DEFAULT NULL,
  `back_image` varchar(255) DEFAULT NULL,
  `side_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `title`, `price`, `available_sizes`, `color_options`, `description`, `season`, `sku_id`, `color_way`, `main_color`, `category`, `gender`, `out_stock`, `front_image`, `back_image`, `side_image`, `created_at`) VALUES
(6, 'arc hoodies', 2000.00, 'S,M,L,XL', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Fall/Winter 2024', '192BT246250F', 'iron,black', 'red', '', '', 0, '_front_slide 8 white color red text png.png', '_back_tshirt.png', '_side_hoodies.png', '2025-09-09 05:59:18'),
(7, 'Arc Hoodies', 5000.00, 'S,M,L', 'black,blue,green', '80% cotton / 20% polyester fleece, 480GSM\r\nEssentials Relaxed fit - relaxed body, sleeves\r\n\"Essentials Fear of God\" soft-touch logo on front\r\nDouble-layer hoodie\r\nRib-knit cuffs\r\nFear of God Essentials velour label\r\nImported', 'Fall/Winter 2024', '192BT246250F', 'iron,black', 'Black', '', '', 0, '_front_slide 8 white color red text png new.png', 'fitted-tee.png', 'sweatshirt.png', '2025-09-09 06:20:22'),
(10, 'Arc Hoodie', 9000.00, 'S,M,L,XL', 'black,blue,green', 'Where minimalism meets light. Made from bamboo fleece (300 GSM) with subtle golden-inspired details. Quietly premium, designed to stand apart.', 'Winter-2025-26', '192BT246250F', 'iron,black', 'Black', '', '', 1, '_front_hoodie-(front)-dutch-canal.jpg', '_back_hoodie-(sleeve)-dutch-canal.jpg', '_side_hoodie-(sleeve)-dutch-canal.jpg', '2025-09-09 06:24:29'),
(11, 'Arc Hoodie', 6000.00, 'S,M,L,XL,XXL', 'Black,Green', 'Where minimalism meets light. Made from bamboo fleece (300 GSM) with subtle golden-inspired details. Quietly premium, designed to stand apart.', 'Fall/Winter 2025-26', '192BT246250F', 'Black', 'Black', '', '', 0, '_front_hoodie-(front)-black.jpg', '_back_tshirt-(sleeve)-black.png', '_side_hoodie-(sleeve)-black.jpg', '2025-09-10 12:08:55'),
(12, 'Arc tees', 2000.00, 'xxs,xs,s,m,l,xs', 'black,blue,green', 'abkshde', 'Fall/Winter 2024', '192BT246250F', 'iron,black', 'Black', 'Tshirts', '0', 0, '_front_image_1759838819_b-fitted-tees-front.png', 'slide 8 white color red text png.png', '_side_image_1759838819_b-fitted-tees-sleeve.png', '2025-09-15 08:04:58'),
(13, 'Premium tee', 10000.00, 'XXL,XL,L,M,S', 'Black', 'Best forever', 'Spring/Summer 2023', '192BT246250F', 'iron,black', 'Black', 'Bags', '0', 1, '_front_image_1759840538_pre tee.png', '_back_image_1759840617_white-premium.png', '_side_image_1759840617_black-premium.png', '2025-09-17 10:07:19'),
(14, 'Aether Hoodies', 2999.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canel,White', 'Lightweight with a soft fleece touch. Cozy yet breathable, offering comfort without added weight.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canel,White', 'White', 'Hoodies', 'men', 0, '_front_w-back-hoodie.png', '_back_w-front-hoodie.png', '_side_w-front-hoodie.png', '2025-09-18 05:27:38'),
(15, 'Aether Hoodies', 3999.00, 'XXL,XL,L,M', 'Black,Dutch-Canel,White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter 2025-26', '192BT246250F', 'Black,Dutch-Canel,White', 'Black', 'Hoodies', 'men', 0, 'b-back-hoodies.png', 'b-front-hoodies.png', 'b-front-hoodies.png', '2025-09-18 05:38:33'),
(16, 'Aether Hoodies', 4999.00, 'XL,L,M,S', 'Black,Dutch-Canel,White', 'Light, airy, and breathable with a clean, minimal drape. Naturally soft on the skin and designed for effortless everyday comfort.', 'Fall/Winter 2025-26', '192BT222054F', 'Black,Dutch-Canel,White', 'Dutch-Canel', 'Hoodies', 'men', 0, 'd-front-hoodie.png', 'd-back-hoodie.png', 'd-back-hoodie.png', '2025-09-18 05:40:55'),
(17, 'Aether Hoodies', 5999.00, 'XXL,L,M,S', 'Black,Dutch-Canel,White', 'Lightweight with a soft fleece touch. Cozy yet breathable, offering comfort without added weight.', 'Fall/Winter 2025-26', '192BT246250F', 'Black,Dutch-Canel,White', 'White', 'Hoodies', 'men', 0, '_front_w-back-hoodie.png', 'w-back-hoodie.png', '_side_w-front-hoodie.png', '2025-09-18 05:53:12'),
(18, 'Arc Hoodies', 2999.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canel,White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter ', '192BT246250F', 'Black,Dutch-Canel,White', 'White', 'Hoodies', 'men', 0, 'b-front.png', 'b-sleeve.png', 'b-sleeve.png', '2025-09-18 06:10:41'),
(19, 'Arc Hoodies', 3999.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canel,White', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Fall/Winter 2024', '192BT246250F', 'Black,Dutch-Canel,White', 'Dutch-Canel', 'Hoodies', 'men', 0, 'd-front.png', 'd-sleeve.png', 'd-sleeve.png', '2025-09-18 06:23:30'),
(20, 'Arc Hoodies', 4999.00, 'XXL,XL,L,S', 'Black,Dutch-Canal,White', 'Thick, soft, and highly structured. Offers a premium warmth and comfort while maintaining breathability.', 'Fall/Winter 2024', '192BT246250F', 'Black,Dutch-Canal,White', 'White', 'Hoodies', 'men', 0, 'w-front.png', 'w-sleeve.png', 'w-sleeve.png', '2025-09-18 06:28:26'),
(21, 'Arte Hoodies', 2999.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canal,White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Spring/Summer 2023', '192BT222054F', 'Black,Dutch-Canal,White', 'Black', 'Hoodies', 'men', 0, 'b-front-hoodies.png', 'b-sleeve-hoodies.png', 'b-sleeve-hoodies.png', '2025-09-18 06:32:18'),
(22, 'Arte Hoodies', 3999.00, 'XL,L,M', 'Black,Dutch-Canal,White', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canal,White', 'Dutch-Canal', 'Hoodies', 'men', 0, 'd-front-hoodie.png', 'd-sleeve-hoodie.png', 'd-sleeve-hoodie.png', '2025-09-18 06:34:30'),
(23, 'Arte Hoodies', 4999.00, 'XL,L,M,S', 'Black,Dutch-Canal,White', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canal,White', 'White', 'Hoodies', '0', 0, '_front_image_1759840430_w-front-hoodie.png', 'w-sleeve-hoodie.png', 'w-sleeve-hoodie.png', '2025-09-18 06:36:05'),
(24, 'Aura fitted tee', 3999.00, 'XXL,XL,L,M,S', 'Black', 'Soft, breathable, and naturally flexible. Offers a smooth, structured feel with a premium finish while staying light on the skin.', 'Spring/Summer 2023', '192BT246250F', 'Black', 'Black', 'Premium collection', 'women', 0, 'pre full sleeve.png', 'pre full sleeve.png', 'pre full sleeve.png', '2025-09-18 06:39:30'),
(25, 'Aura Tee', 3999.00, 'XXL,XL,L,M,S', 'Black', 'Soft, breathable, and naturally flexible. Offers a smooth, structured feel with a premium finish while staying light on the skin.', 'Spring/Summer 2023', '192BT246250F', 'Black', 'Black', 'Premium collection', 'men', 0, 'pre tee.png', 'pre tee.png', 'pre tee.png', '2025-09-18 06:42:06'),
(26, 'Aurelia Sweatshirts', 4999.00, 'XXL,XL,L,M,S', 'Black', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter ', '192BT246250F', 'Black', 'Black', 'Premium collection', 'men', 0, 'black-premium.png', 'black-premium.png', 'black-premium.png', '2025-09-18 06:43:58'),
(27, 'Aurelia Sweatshirt', 5999.00, 'XXL,XL,L,M,S', 'White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter ', '192BT222054F', 'White', 'White', 'Premium collection', 'men', 0, 'white-premium.png', 'white-premium.png', 'white-premium.png', '2025-09-18 06:45:29'),
(28, 'Aurum Half-zip', 5999.00, 'XXL,XL,L,M,S', 'Black', 'Soft, breathable, and naturally flexible. Offers a smooth, structured feel with a premium finish while staying light on the skin', 'Fall/Winter ', '192BT222054F', 'Black', 'Black', 'Premium collection', 'men', 0, 'half-zip-front.png', 'half-zip-sleeve.png', 'half-zip-sleeve.png', '2025-09-18 06:47:29'),
(29, 'Aether Sweatshirts', 4999.00, 'XL,L,M,S', 'Black,Dutch-Canal,White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter ', '192BT246250F', 'Black', 'Black', 'Sweatshirts', 'men', 0, '_front_b-sweat-front.png', '_back_b-sweat-back.png', '_side_b-sweat-back.png', '2025-09-18 06:50:57'),
(30, 'Aether Sweatshirts', 4999.00, 'XL,L,M,S', 'Black,Dutch-Canal,White', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Fall/Winter ', '192BT246250F', 'Black,Dutch-Canal,White', 'Dutch-Canel', 'Sweatshirts', 'men', 0, '_front_d-sweat-front.png', '_back_d-sweat-back.png', '_side_d-sweat-back.png', '2025-09-18 06:56:42'),
(31, 'Aether Sweatshirts', 3999.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canal,White', 'Thick, soft, and highly structured. Offers a premium warmth and comfort while maintaining breathability.', 'Fall/Winter ', '192BT222054F', 'Black,Dutch-Canal,White', 'White', 'Sweatshirts', '0', 1, '_front_image_1759838988_w-sweat-front.png', 'w-sweat-back.png', 'w-sweat-back.png', '2025-09-18 06:59:35'),
(32, 'Arc Sweatshirts', 1999.00, 'XXL,XL,L,M,S', 'Black', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Spring/Summer 2023', '192BT222054F', 'Black', 'Black', 'Sweatshirts', 'men', 0, 'b-front.png', 'b-sleeve.png', 'b-sleeve.png', '2025-09-18 07:11:41'),
(33, 'Arc Sweatshirts', 1999.00, 'XXL,XL,L,M,S', 'Dutch-Canal', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Spring/Summer 2023', '192BT246250F', 'Dutch-Canal', 'Dutch-Canal', 'Sweatshirts', 'men', 0, 'd-front.png', 'd-sleeve.png', 'd-sleeve.png', '2025-09-18 07:13:28'),
(34, 'Arc Sweatshirts', 1999.00, 'XXL,XL,L,M,S', 'White', 'Thick, soft, and highly structured. Offers a premium warmth and comfort while maintaining breathability.', 'Spring/Summer 2023', '192BT222054F', 'White', 'White', 'Sweatshirts', 'men', 0, 'w-front.png', 'w-sleeve.png', 'w-sleeve.png', '2025-09-18 07:15:46'),
(35, 'Arte Sweatshirts', 2999.00, 'XXL,XL,L,M,S', 'Black', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Spring/Summer 2023', '192BT222054F', 'Black', 'Black', 'Sweatshirts', 'men', 0, 'b-sweat-front.png', 'b-sweat-sleeve.png', 'b-sweat-sleeve.png', '2025-09-18 07:20:13'),
(36, 'Arte Sweatshirts', 1999.00, 'XL,L,M,S', 'Dutch-Canal', 'Soft and structured with a refined feel. This midweight build brings breathable warmth and an elevated finish.', 'Spring/Summer 2023', '192BT246250F', 'Dutch-Canal', 'Dutch-Canal', 'Sweatshirts', 'men', 0, 'd-sweat-front.png', 'd-sweat-sleeve.png', 'd-sweat-sleeve.png', '2025-09-18 07:22:26'),
(37, 'Arte Sweatshirts', 1999.00, 'XL,L,M,S', 'White', 'Thick, soft, and highly structured. Offers a premium warmth and comfort while maintaining breathability.', 'Fall/Winter ', '192BT246250F', 'White', 'White', 'Sweatshirts', 'men', 0, '_front_w-sweat-front.png', '_back_w-sweat-sleeve.png', '_side_w-sweat-sleeve.png', '2025-09-18 07:24:00'),
(38, 'Arc tee', 599.00, 'XXL,XL,L,M,S', 'Black', 'Soft, breathable, and naturally comfortable. Crafted from bamboo cotton with a smooth, minimal finish designed for everyday wear.', 'Spring/Summer 2023', '192BT222054F', 'Black', 'Black', 'Tshirts', 'men', 0, 'b-front.png', 'b-sleeve.png', 'b-sleeve.png', '2025-09-18 07:26:53'),
(39, 'Arc tee', 599.00, 'XXL,XL,L,M', 'Dutch-Canal', 'Light, airy, and breathable with a clean, minimal drape. Naturally soft on the skin and designed for effortless everyday comfort.', 'Spring/Summer 2023', '192BT246250F', 'Dutch-Canal', 'Dutch-Canal', 'Tshirts', 'men', 0, 'd-front.png', 'd-sleeve.png', 'd-sleeve.png', '2025-09-18 07:29:18'),
(40, 'Arc tee', 599.00, 'XXL,XL,L,M,S', 'White', 'Lightweight with a soft fleece touch. Cozy yet breathable, offering comfort without added weight.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canal,White', 'White', 'Tshirts', 'men', 0, 'w-front.png', 'w-sleeve.png', 'w-sleeve.png', '2025-09-18 07:30:27'),
(41, 'Arc fitted tee', 599.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canal,White', 'Soft, breathable, and naturally comfortable. Crafted from bamboo cotton with a smooth, minimal finish designed for everyday wear.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canal,White', 'Black', 'Tshirts', 'women', 0, 'b-fitted-tees-front.png', 'b-fitted-tees-sleeve.png', 'b-fitted-tees-sleeve.png', '2025-09-18 07:33:33'),
(42, 'Arc fitted tee', 599.00, 'XL,L,M,S', 'Black,Dutch-Canel,White', 'Light, airy, and breathable with a clean, minimal drape. Naturally soft on the skin and designed for effortless everyday comfort.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canal,White', 'Dutch-Canal', 'Tshirts', 'women', 0, 'd-fitted-tees-front.png', 'd-fitted-tees-sleeve.png', 'd-fitted-tees-sleeve.png', '2025-09-18 07:35:07'),
(43, 'Arc fitted tee', 599.00, 'XXL,XL,L,M,S', 'Black,Dutch-Canel,White', 'Lightweight with a soft fleece touch. Cozy yet breathable, offering comfort without added weight.', 'Spring/Summer 2023', '192BT246250F', 'Black,Dutch-Canel,White', 'White', 'Tshirts', 'women', 0, 'w-fitted-tees-front.png', 'w-fitted-tees-sleeve.png', 'w-fitted-tees-sleeve.png', '2025-09-18 07:36:24'),
(44, 'Arc Premium  Product', 5000.00, 'XXL,XL,L,M', 'Black,Dutch-Canel,White', 'Dense and warm with a premium structure. Designed for a heavier feel while staying soft and breathable.', 'Fall/Winter 2024', '192BT246250F', 'Black,Dutch-Canal,White', 'Black', 'Premium collection', '0', 0, '_front_image_1759840077_white-premium.png', '_back_image_1759840176_black-premium.png', '_side_image_1759840077_black-premium.png', '2025-10-07 12:25:58');

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
(8, 'klm', 'lkm', 'klm@gmail.com', 1, '2025-09-05 11:51:08'),
(12, 'Ankit', 'ja', 'abcdef@gmail.com', 1, '2025-10-08 07:23:28');

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
  `term` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `reset_token` varchar(255) DEFAULT NULL,
  `reset_expiry` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fname`, `lname`, `email`, `password`, `term`, `created_at`, `reset_token`, `reset_expiry`) VALUES
(40, 'abc', 'def', 'abc@gmail.com', '$2y$10$uw1rIUTco8m6FR55wc5K2u7/qTBYIa51Ms0gifw9rM7Qfi1nxSSnK', 1, '2025-09-10 07:28:02', 'ec3bf354315dfc6ddece88936b725f03', '2025-09-25 15:05:49'),
(41, 'ankit', 'jain', 'ankit@gmail.com', '$2y$10$SLHRR6gPEhLmb5sBzF.bOOIaqZ.pISmq6lY09i3.6srwTm4AgtnjO', 1, '2025-09-19 09:27:32', '7871f6b5d1399ff75d535c587e097ecd', '2025-09-25 14:51:01'),
(42, 'Ankit', 'ja', 'abcs@gmail.com', '$2y$10$JvbZ0PkwlIjqUvrm5rLMGu/huQdrCxV.uwxzXbGG9DXHITyVXJAIW', 1, '2025-10-04 09:36:05', NULL, NULL),
(43, 'dinesh', 'gohil', 'dinesh@gmail.com', '$2y$10$mmMOyqyRSNlEyf1kZxgz.umgcUUdUjV3fjqrQc3uKi3rOdzdXRsV2', 1, '2025-10-07 05:14:13', NULL, NULL),
(44, 'def', 'def', 'def@gmail.com', '$2y$10$7zPB18mY2ka2w.0SxBJNC.vJoE6iGVAN/YYJrcRHC7.6WDqZvavE.', 1, '2025-10-07 05:23:57', NULL, NULL),
(45, 'sql', 'lqs', 'sql@gmail.com', '$2y$10$Y0.EDfsCIx1EHzW/Rnb0UO.qvFd83vPIIr84mTuS9B2gcPRW4Eje2', 1, '2025-10-07 06:16:41', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `product_title` varchar(255) NOT NULL,
  `product_price` decimal(10,2) NOT NULL,
  `product_image` varchar(255) NOT NULL,
  `size` varchar(255) NOT NULL,
  `color` varchar(199) NOT NULL,
  `product_quantity` int(11) NOT NULL,
  `added_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `wishlist`
--

INSERT INTO `wishlist` (`id`, `user_id`, `product_id`, `product_title`, `product_price`, `product_image`, `size`, `color`, `product_quantity`, `added_at`) VALUES
(187, 44, 15, 'Aether Hoodies', 3999.00, 'b-back-hoodies.png', 'XXL', 'White', 1, '2025-10-07 05:28:23');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_old`
--
ALTER TABLE `orders_old`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

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
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD UNIQUE KEY `user_id` (`user_id`,`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `orders_old`
--
ALTER TABLE `orders_old`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `subscribe`
--
ALTER TABLE `subscribe`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
