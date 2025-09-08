-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3307
-- Generation Time: Sep 08, 2025 at 02:16 PM
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
-- Database: `watchshop`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `logins`
--

CREATE TABLE `logins` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `login_time` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `payment_method` enum('UPI','COD','Card','NetBanking') NOT NULL,
  `address` text NOT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `status` enum('pending','delivered','cancelled') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `brand` varchar(100) DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `rating` float DEFAULT NULL,
  `total_sales` int(11) DEFAULT 0,
  `is_top_rated` tinyint(1) DEFAULT 0,
  `is_top_seller` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `brand`, `category`, `price`, `image`, `description`, `rating`, `total_sales`, `is_top_rated`, `is_top_seller`) VALUES
(1, 'Kenneth Cole Men | Mist', 'Kenneth Cole', 'Men', 17499.00, 'images/helios_kenneth-cole_men.jpg', 'Premium Kenneth Cole watch for men.', 4.5, 50, 0, 0),
(2, 'Versace Men | Hellenium', 'Versace', 'Men', 99400.00, 'images/helios_versace_men.jpg', 'Luxury Versace Hellenium watch for men.', 4.8, 30, 1, 0),
(3, 'Versace Women | V-TRIBUTE', 'Versace', 'Women', 107600.00, 'images/helios_versace_women.jpg', 'Elegant Versace V-TRIBUTE for women.', 4.7, 25, 1, 0),
(4, 'Michael Kors Women | Lexington', 'Michael Kors', 'Women', 21995.00, 'images/helios_michael_kors_women_lexington.jpg', 'Michael Kors Lexington collection for women.', 4.6, 40, 0, 1),
(5, 'Michael Kors Women | Lennox', 'Michael Kors', 'Women', 23995.00, 'images/helios_michael_kors_women.jpg', 'Michael Kors Lennox women’s watch.', 4.6, 35, 0, 1),
(6, 'Titan Classic Men | Leather Strap', 'Titan', 'Men', 2999.00, 'images/Titan_Men\'s_Elegance_Watch.jpg', 'Elegant Titan leather strap watch.', 4.4, 80, 0, 0),
(7, 'Fossil Gen 5 | Smartwatch', 'Fossil', 'Unisex', 14999.00, 'images/Titan_Smart_Watch.jpg', 'Fossil Gen 5 smartwatch with modern features.', 4.5, 70, 0, 1),
(8, 'Balmain Men | BALMAIN ICONIC CHRONO GENT', 'Balmain', 'Men', 59100.00, 'images/helios_balmain_men.jpg', 'Balmain Iconic Chrono Gent men’s watch.', 4.7, 20, 1, 0),
(9, 'Casio Unisex | Vintage', 'Casio', 'Unisex', 5295.00, 'images/helios_casio_unisex_black.jpg', 'Classic Casio Vintage series.', 4.3, 100, 0, 1),
(10, 'Casio Unisex | Vintage', 'Casio', 'Unisex', 5299.00, 'images/helios_casio_unisex.jpg', 'Classic Casio Vintage design.', 4.3, 90, 0, 0),
(11, 'Fitbit Unisex | Sense', 'Fitbit', 'Unisex', 22999.00, 'images/helios_Fitbit_unisex.jpg', 'Fitbit Sense smartwatch with health features.', 4.6, 50, 1, 1),
(12, 'G-Shock Men | G-Shock', 'G-Shock', 'Men', 9795.00, 'images/helios_g-stock_men.jpg', 'Rugged G-Shock watch for men.', 4.5, 60, 0, 1),
(13, 'Garmin Unisex | Venu Sq 2 Music', 'Garmin', 'Unisex', 22999.00, 'images/helios_gaemin_unisex.jpg', 'Garmin Venu Sq 2 Music edition.', 4.6, 40, 1, 0),
(14, 'Victorinox Men | MAVERICK', 'Victorinox', 'Men', 68700.00, 'images/helios_victorinox_men.jpg', 'Victorinox Maverick luxury watch.', 4.7, 15, 1, 0),
(15, 'Edge Couple | Edge Pai', 'Edge', 'Couple', 26700.00, 'images/helios_edge_couple.jpg', 'Stylish Edge Pai couple watches.', 4.4, 20, 0, 0),
(16, 'Titan Couple | BANDHAN', 'Titan', 'Couple', 19790.00, 'images/helios_titan_couple_bandhan.jpg', 'Titan Bandhan couple watch set.', 4.3, 25, 0, 1),
(17, 'Titan Couple | BANDHAN', 'Titan', 'Couple', 13190.00, 'images/helios_titan_couple.jpg', 'Titan Bandhan couple watches.', 4.3, 20, 0, 0),
(18, 'Titan Couple | BANDHAN', 'Titan', 'Couple', 8700.00, 'images/helios_titan_couple1.jpg', 'Titan Bandhan affordable couple set.', 4.2, 18, 0, 0),
(19, 'Bvlgari women | Serpenti Seduttori Watch', 'Bvlgari', 'Women', 3350000.00, 'images/BVLGARI_serpenti_seduttori_watch.jpg', 'Luxury Bvlgari Serpenti Seduttori.', 4.9, 5, 1, 0),
(20, 'BVLGARI | Serpenti Tubogas Watch', 'Bvlgari', 'Women', 3980000.00, 'images/bvlgari-serpenti-103903-large.jpg', 'Bvlgari Serpenti Tubogas luxury watch.', 4.9, 3, 1, 0),
(21, 'Rolex | Sky-Dweller', 'Rolex', 'Men', 1545500.00, 'images/rolex-sky-dweller.jpg', 'Rolex Sky-Dweller premium watch.', 5, 8, 1, 0),
(22, 'Rolex | Sky-Dweller | Oyster, 42 mm, yellow gold', 'Rolex', 'Men', 1350000.00, 'images/rolex-sky-dweller1.jpg', 'Rolex Sky-Dweller Oyster in yellow gold.', 5, 6, 1, 0),
(23, 'Casio G-Shock Series', 'Casio', 'Men', 8795.00, 'images/casio_gshock.jpg', 'Rugged G-Shock watch with shock resistance.', 4.5, 150, 1, 0),
(24, 'Fastrack Couple Black Set', 'Fastrack', 'Couple', 5199.00, 'images/fastrack_couple.jpg', 'Stylish couple watch set with leather strap.', 4.3, 90, 0, 1),
(25, 'Fastrack Couple Silver Set', 'Fastrack', 'Couple', 5699.00, 'images/fastrack_couple_1.jpg', 'Elegant silver-toned couple watch pair.', 4.4, 85, 1, 0),
(26, 'Fastrack Couple Gold Edition', 'Fastrack', 'Couple', 5999.00, 'images/fastrack_couple_2.jpg', 'Gold-plated couple edition watches.', 4.6, 110, 1, 1),
(27, 'Fastrack Couple Casual Set', 'Fastrack', 'Couple', 4599.00, 'images/fastrack_couple_3.jpg', 'Casual and trendy couple watch combo.', 4.2, 70, 0, 0),
(28, 'Fastrack Groove Men', 'Fastrack', 'Men', 3499.00, 'images/Fastrack_Groove_men.jpg', 'Trendy design for casual outfits.', 4.1, 120, 0, 1),
(29, 'Fastrack Guys Series', 'Fastrack', 'Men', 2999.00, 'images/fastrack_guys.jpg', 'Bold and youthful watch for guys.', 4.3, 95, 0, 0),
(30, 'Fastrack Smartwatch', 'Fastrack', 'Unisex', 6999.00, 'images/fastrack_smartwatch_unisex.jpg', 'Smartwatch with multiple sports modes.', 4.7, 210, 1, 1),
(31, 'Fastrack Stunners Women', 'Fastrack', 'Women', 3299.00, 'images/Fastrack_Stunners_women.jpg', 'Elegant stunner series for women.', 4.4, 140, 1, 0),
(32, 'Fastrack Stunners Blue Dial', 'Fastrack', 'Women', 3599.00, 'images/Fastrack_Stunners_women_blue.jpg', 'Blue dial edition for fashion lovers.', 4.6, 160, 1, 1),
(33, 'Fastrack Style Up', 'Fastrack', 'Women', 3199.00, 'images/Fastrack_Style_Up_women.jpg', 'Trendy and casual watch for daily wear.', 4.3, 130, 0, 0),
(34, 'Fastrack Style Women', 'Fastrack', 'Women', 2999.00, 'images/Fastrack_Style_women.jpg', 'Simple and minimalistic design.', 4.2, 100, 0, 0),
(35, 'Fastrack Thor Quartz Multifunction', 'Fastrack', 'Men', 4299.00, 'images/Fastrack_Thor_Quartz_Multifunction.jpg', 'Multifunction watch inspired by Thor.', 4.5, 160, 1, 0),
(36, 'Fastrack Trendies Men', 'Fastrack', 'Men', 2799.00, 'images/Fastrack_Trendies_men.jpg', 'Casual watch for young men.', 4.1, 115, 0, 1),
(37, 'Fastrack Vyb Aurora', 'Fastrack', 'Women', 3799.00, 'images/Fastrack_Vyb_Aurora.jpg', 'Aurora series with premium design.', 4.4, 145, 1, 1),
(38, 'Fossil Gen Smartwatch', 'Fossil', 'Unisex', 12999.00, 'images/fossil_gen.jpg', 'Fossil Gen series smartwatch with health tracking.', 4.8, 200, 1, 1),
(39, 'Rado HyperChrome', 'Rado', 'Men', 112000.00, 'images/rado-hyperchrome.jpg', 'Luxury Rado HyperChrome watch.', 4.9, 80, 1, 0),
(40, 'Titan Raga Premium Women', 'Titan', 'Women', 9999.00, 'images/titan_raga.jpg', 'Elegant Titan Raga watch with gold finish and crystal accents.', 4.7, 180, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `fullname` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `phone` varchar(20) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `fullname`, `email`, `username`, `password`, `created_at`, `phone`, `address`) VALUES
(1, 'hetvi satishkumar patel', 'hetvipatel2069@gmail.com', 'hetvipatel', '$2y$10$PndqsfatuoSgaPDYdD62D.vK1N55NeXIQdeYXHE8W./.dVocN1FA6', '2025-09-08 09:19:55', '1234567890', 'vir vital bhumi\r\ndasaj raod'),
(3, 'hetvi satishkumar patel', 'h11@gmail.com', 'hetvi2069', '$2y$10$oh6D90aKMii0gtn4eWKo3.e0BUe6Xob6BNbZ/QOtSfRLRBL5x8erC', '2025-09-08 09:22:18', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `wishlist`
--

CREATE TABLE `wishlist` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `added_on` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `logins`
--
ALTER TABLE `logins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_wishlist` (`user_id`,`product_id`),
  ADD KEY `product_id` (`product_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `logins`
--
ALTER TABLE `logins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `wishlist`
--
ALTER TABLE `wishlist`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `logins`
--
ALTER TABLE `logins`
  ADD CONSTRAINT `logins_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`order_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `wishlist`
--
ALTER TABLE `wishlist`
  ADD CONSTRAINT `wishlist_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `wishlist_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
