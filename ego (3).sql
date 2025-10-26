-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 15, 2025 at 02:24 PM
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
-- Database: `ego`
--

-- --------------------------------------------------------

--
-- Table structure for table `cart`
--

CREATE TABLE `cart` (
  `cart_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `session_id` varchar(255) DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `session_id`, `created_at`, `updated_at`) VALUES
(1, NULL, 'ql621idgr0tqpj7hjljvt4g3ca', '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(2, 4, NULL, '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(3, NULL, 'selo0vaculesaabq7h1o2hqlb4', '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(4, NULL, '1cmbjvuucv1r543j5u6i7mnsou', '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(5, NULL, 'su9bs8ag2dg5vb7foj1id30q48', '2025-10-13 13:11:14', '2025-10-13 13:11:14');

-- --------------------------------------------------------

--
-- Table structure for table `cart_item`
--

CREATE TABLE `cart_item` (
  `item_id` int(11) NOT NULL,
  `cart_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `image_id` int(11) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `price` decimal(10,2) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `image`, `created_at`) VALUES
(2, 'Tops', '1757670809_Tops.jpg', '2025-09-12 09:53:29'),
(3, 'Jeans', '1757682907_Jeans.jpg', '2025-09-12 13:15:07'),
(4, 'Dresses', '1758485396_Dresses.png', '2025-09-21 20:09:56'),
(5, 'Sets', '1759839963_Tops.jpg', '2025-10-07 12:26:03'),
(7, 'Blazer', '68ebbf5b5b8b5-blazer10.webp', '2025-10-12 14:28:22'),
(8, 'Shoes', '1760284259_blazer11.webp', '2025-10-12 15:50:59');

-- --------------------------------------------------------

--
-- Table structure for table `colors`
--

CREATE TABLE `colors` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `hex_code` varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `colors`
--

INSERT INTO `colors` (`id`, `name`, `hex_code`) VALUES
(16, 'White', '#FFFFFF'),
(18, 'Red', '#FF0A0A'),
(20, 'Blue', '#0561F5'),
(21, 'Black', '#000000'),
(22, 'Yellow', '#FBFF05'),
(23, 'Gray', '#BABDB7');

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` int(11) NOT NULL,
  `code` varchar(50) NOT NULL,
  `discount_type` enum('percentage','fixed') NOT NULL,
  `discount_value` decimal(10,2) NOT NULL,
  `min_order_value` decimal(10,2) DEFAULT 0.00,
  `times_used` int(11) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `discount_type`, `discount_value`, `min_order_value`, `times_used`, `start_date`, `end_date`, `is_active`, `created_at`) VALUES
(2, 'ALI34', 'percentage', 20.00, 10.00, 0, '2025-10-15', '2025-11-15', 0, '2025-10-14 11:16:49');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `shipping_region_id` int(11) DEFAULT NULL,
  `payment_method` enum('COD','Card','WhatsApp') NOT NULL DEFAULT 'COD',
  `payment_status` enum('pending','paid','failed') DEFAULT 'pending',
  `status` enum('pending','shipped','completed','cancelled') DEFAULT 'pending',
  `coupon_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `variant_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `discount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `note` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `description` text DEFAULT NULL,
  `base_price` decimal(10,2) NOT NULL,
  `weight` decimal(10,2) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_top` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `base_price`, `weight`, `category_id`, `created_at`, `is_top`, `is_active`) VALUES
(24, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 4, '2025-09-22 12:59:58', 0, 1),
(25, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 4, '2025-09-22 13:01:12', 1, 1),
(26, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 4, '2025-09-22 14:26:22', 1, 1),
(27, 'Jersey midi dress with cut', 'test', 300.00, 0.00, 2, '2025-09-22 14:34:35', 1, 1),
(43, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 3, '2025-10-08 12:33:11', 1, 1),
(44, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 5, '2025-10-08 12:34:51', 1, 1),
(45, 'Jersey midi dress with cut', 'test', 100.00, 0.00, 2, '2025-10-08 12:40:50', 1, 1),
(46, 'Jersey midi dress with cut', 'gjglkhk', 100.00, 0.00, 5, '2025-10-10 11:40:03', 0, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_discounts`
--

CREATE TABLE `product_discounts` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `discount_percentage` decimal(5,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `variant_id` int(11) DEFAULT NULL,
  `color_id` int(11) DEFAULT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_main` tinyint(1) DEFAULT 0,
  `display_order` int(11) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `product_id`, `variant_id`, `color_id`, `image_path`, `is_main`, `display_order`) VALUES
(1, 24, NULL, NULL, 'admin/uploads/68d1484e4059c_Jersey.png', 1, 1),
(2, 25, NULL, NULL, 'admin/uploads/68d14898f25d8_Jersey.png', 1, 1),
(3, 26, NULL, NULL, 'admin/uploads/68d15c8ee3ba8_Jersey.png', 1, 1),
(4, 26, NULL, NULL, 'admin/uploads/68d15c8ee74e0_Dresses.png', 0, 1),
(5, 27, NULL, NULL, 'admin/uploads/68d15e7ba29c3_Jersey.png', 1, 1),
(7, 43, NULL, NULL, 'uploads/products/p43_68e65a07a61c2_Tops.jpg', 1, 1),
(8, 44, NULL, NULL, 'uploads/products/p44_68e65a6b33688_Tops.jpg', 1, 1),
(9, 45, NULL, NULL, 'admin/uploads/p45_68e65bd284b2b_Tops.jpg', 1, 1),
(10, 46, NULL, NULL, 'admin/uploads/p46_68e8f093e90d3_blazer.webp', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `color_id` int(11) DEFAULT NULL,
  `size_id` int(11) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `color_id`, `size_id`, `price`, `quantity`, `is_active`, `created_at`, `updated_at`) VALUES
(13, 24, NULL, 2, 0.00, 100, 1, '2025-10-05 11:19:59', '2025-10-05 11:19:59'),
(14, 25, NULL, 2, 0.00, 10, 1, '2025-10-05 11:19:59', '2025-10-05 11:19:59'),
(15, 25, NULL, 2, 0.00, 20, 1, '2025-10-05 11:19:59', '2025-10-05 11:19:59'),
(16, 26, NULL, 2, 0.00, 10, 1, '2025-10-05 11:19:59', '2025-10-05 11:19:59'),
(17, 27, NULL, 2, 0.00, 10, 1, '2025-10-05 11:19:59', '2025-10-05 11:19:59'),
(22, 43, NULL, 2, 100.00, 100, 1, '2025-10-08 15:33:11', '2025-10-08 15:33:11'),
(23, 44, NULL, 2, 100.00, 100, 1, '2025-10-08 15:34:51', '2025-10-08 15:34:51'),
(24, 45, NULL, 2, 100.00, 10, 1, '2025-10-08 15:40:50', '2025-10-08 15:40:50'),
(25, 46, NULL, 2, 100.00, 10, 1, '2025-10-10 14:40:03', '2025-10-10 14:40:03');

-- --------------------------------------------------------

--
-- Table structure for table `shipping_regions`
--

CREATE TABLE `shipping_regions` (
  `id` int(11) NOT NULL,
  `region_name` varchar(100) NOT NULL,
  `fee_per_kg` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shipping_regions`
--

INSERT INTO `shipping_regions` (`id`, `region_name`, `fee_per_kg`, `is_active`, `created_at`) VALUES
(1, 'MEA', 25.00, 1, '2025-10-13 11:48:59'),
(3, 'LEB', 10.00, 1, '2025-10-13 12:12:06');

-- --------------------------------------------------------

--
-- Table structure for table `sizes`
--

CREATE TABLE `sizes` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `type` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `sizes`
--

INSERT INTO `sizes` (`id`, `name`, `type`) VALUES
(2, 'Xs', 'Clothe'),
(4, 'S', 'Clothe'),
(5, 'M', 'Clothe'),
(6, 'L', 'Clothe'),
(7, 'Xl', 'Clothe'),
(10, 'Xxs', 'Clothe'),
(12, '42', 'Shoes');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(50) DEFAULT NULL,
  `address` varchar(255) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `state` varchar(20) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL,
  `zip_code` varchar(100) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('super_admin','admin','customer') DEFAULT 'customer',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `address`, `city`, `state`, `country`, `zip_code`, `password`, `role`, `created_at`) VALUES
(1, 'Ali Shaaban', 'ali.shaaban2002@example.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$ZVMtUyieyuSHMgvTAUCNtu1NLG8v9p3zQ94tmBiSLIY/fkUU/w7we', 'admin', '2025-09-01 12:39:04'),
(4, 'Ali Shaaban', 'ali.shaaban2002@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$oVrBCLrFEoA9OZ9SIh1Syunb2SfO8WnHefJf0Gxnv1NG2q1EyOCqW', 'super_admin', '2025-09-14 17:30:50'),
(5, 'ali', 'ali.shaaban@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$JYtM6JBqa.lgc.hQ.FAfze5pDv5.GVzOpHJeJ4MMpBQ2d5K5mI/f6', 'customer', '2025-10-01 10:49:29'),
(7, 'kareem kobisi', 'kareem.kobbisi@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$OTlfMTT.KvptGmff7qI3eeWyf6kUMczbMt5QSFpPFvlIXCrEYumEm', 'super_admin', '2025-10-12 19:10:24');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `cart`
--
ALTER TABLE `cart`
  ADD PRIMARY KEY (`cart_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD PRIMARY KEY (`item_id`),
  ADD KEY `cart_id` (`cart_id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `variant_id` (`variant_id`),
  ADD KEY `image_id` (`image_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `colors`
--
ALTER TABLE `colors`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `coupon_id` (`coupon_id`),
  ADD KEY `fk_orders_shipping_region` (`shipping_region_id`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `idx_payment_status` (`payment_status`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_id` (`order_id`),
  ADD KEY `variant_id` (`variant_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_product` (`product_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `fk_product_images_variant` (`variant_id`),
  ADD KEY `fk_product_images_color` (`color_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_variant` (`product_id`,`color_id`,`size_id`),
  ADD KEY `fk_variant_color` (`color_id`),
  ADD KEY `fk_variant_size` (`size_id`);

--
-- Indexes for table `shipping_regions`
--
ALTER TABLE `shipping_regions`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sizes`
--
ALTER TABLE `sizes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `cart`
--
ALTER TABLE `cart`
  MODIFY `cart_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `cart_item`
--
ALTER TABLE `cart_item`
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `shipping_regions`
--
ALTER TABLE `shipping_regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sizes`
--
ALTER TABLE `sizes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `cart`
--
ALTER TABLE `cart`
  ADD CONSTRAINT `cart_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Constraints for table `cart_item`
--
ALTER TABLE `cart_item`
  ADD CONSTRAINT `cart_item_ibfk_1` FOREIGN KEY (`cart_id`) REFERENCES `cart` (`cart_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_item_ibfk_3` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `cart_item_ibfk_4` FOREIGN KEY (`image_id`) REFERENCES `product_images` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_shipping_region` FOREIGN KEY (`shipping_region_id`) REFERENCES `shipping_regions` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `orders_ibfk_2` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_ibfk_2` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_discounts`
--
ALTER TABLE `product_discounts`
  ADD CONSTRAINT `product_discounts_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_images`
--
ALTER TABLE `product_images`
  ADD CONSTRAINT `fk_product_images_color` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `fk_product_images_variant` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `product_images_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `fk_variant_color` FOREIGN KEY (`color_id`) REFERENCES `colors` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_variant_product` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_variant_size` FOREIGN KEY (`size_id`) REFERENCES `sizes` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
