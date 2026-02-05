-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 28, 2025 at 09:09 AM
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
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `cart`
--

INSERT INTO `cart` (`cart_id`, `user_id`, `created_at`, `updated_at`) VALUES
(1, NULL, '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(2, 4, '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(3, NULL, '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(4, NULL, '2025-10-13 13:11:14', '2025-10-13 13:11:14'),
(5, NULL, '2025-10-13 13:11:14', '2025-10-13 13:11:14');

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

--
-- Dumping data for table `cart_item`
--

INSERT INTO `cart_item` (`item_id`, `cart_id`, `product_id`, `variant_id`, `image_id`, `quantity`, `price`) VALUES
(13, 2, 74, 60, NULL, 3, 0.00);

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
(10, 'Jacket', 'admin/uploads/categories/1761497776_blazer10.webp', '2025-10-26 16:56:16'),
(11, 'Blazer', 'admin/uploads/categories/1761497826_blazer12.webp', '2025-10-26 16:57:06');

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
(23, 'Gray', '#BABDB7'),
(24, 'Olive', '#94B47E');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_read` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `name` varchar(150) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(74, 'Test', 'test', 100.00, NULL, 11, '2025-10-26 17:29:09', 1, 1),
(75, 'Elegant Blazer', 'Professional blazer for modern women', 120.00, NULL, 11, '2025-10-26 19:44:30', 0, 1),
(76, 'Casual Blazer', 'Comfortable blazer for everyday wear', 85.00, NULL, 11, '2025-10-26 19:44:31', 0, 1),
(77, 'Winter Jacket', 'Warm jacket for cold weather', 150.00, NULL, 10, '2025-10-26 19:44:31', 0, 1),
(78, 'Denim Jacket', 'Classic denim jacket', 90.00, NULL, 10, '2025-10-26 19:44:31', 0, 1),
(79, 'Leather Jacket', 'Stylish leather jacket', 200.00, NULL, 10, '2025-10-26 19:44:31', 0, 1);

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

--
-- Dumping data for table `product_discounts`
--

INSERT INTO `product_discounts` (`id`, `product_id`, `discount_percentage`, `is_active`) VALUES
(2, 74, 30.00, 1);

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
(33, 74, 60, 23, 'admin/uploads/products/p74_68fe5a6532b80_blazer11.webp', 1, 1),
(34, 75, NULL, NULL, 'admin/assets/no-image.png', 1, 1),
(35, 76, NULL, NULL, 'admin/assets/no-image.png', 1, 1),
(36, 77, NULL, NULL, 'admin/assets/no-image.png', 1, 1),
(37, 78, NULL, NULL, 'admin/assets/no-image.png', 1, 1),
(38, 79, NULL, NULL, 'admin/assets/no-image.png', 1, 1);

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
(60, 74, 23, 6, 0.00, 0, 1, '2025-10-26 19:29:09', '2025-10-26 21:01:42'),
(61, 74, 23, 7, 150.00, 5, 1, '2025-10-26 20:55:21', '2025-10-26 20:55:21');

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
(3, 'LEB', 10.00, 1, '2025-10-13 12:12:06'),
(6, 'EUROPE', 35.00, 1, '2025-10-25 19:08:20'),
(7, 'NORTH AMERICA', 45.00, 1, '2025-10-25 19:08:20'),
(8, 'ASIA', 30.00, 1, '2025-10-25 19:08:20'),
(9, 'AFRICA', 40.00, 0, '2025-10-25 19:08:20'),
(10, 'AUSTRALIA', 50.00, 1, '2025-10-25 19:08:20');

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
(5, 'ali', 'ali.shaaban@gmail.com', NULL, NULL, NULL, NULL, NULL, NULL, '$2y$10$JYtM6JBqa.lgc.hQ.FAfze5pDv5.GVzOpHJeJ4MMpBQ2d5K5mI/f6', 'customer', '2025-10-01 10:49:29');

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
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_created_at` (`created_at`),
  ADD KEY `idx_is_read` (`is_read`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

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
  MODIFY `item_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `colors`
--
ALTER TABLE `colors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=80;

--
-- AUTO_INCREMENT for table `product_discounts`
--
ALTER TABLE `product_discounts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `shipping_regions`
--
ALTER TABLE `shipping_regions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

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
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

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

-- --------------------------------------------------------

--
-- Table structure for table `website_settings`
--

CREATE TABLE IF NOT EXISTS `website_settings` (
  `id` int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `setting_key` varchar(255) NOT NULL UNIQUE,
  `setting_value` longtext,
  `created_at` datetime DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `website_settings`
--

INSERT INTO `website_settings` (`setting_key`, `setting_value`) VALUES
('website_name', 'Ego Clothing'),
('website_url', 'http://localhost:8080'),
('contact_email', 'contact@egoclothing.com'),
('phone_number', '+1 (555) 000-0000'),
('company_description', 'Premium fashion clothing for everyone'),
('primary_color', '#b7926f'),
('secondary_color', '#9e7e59'),
('accent_color', '#88663d'),
('meta_title', 'Ego Clothing - Premium Fashion'),
('meta_description', 'Discover our exclusive collection of premium clothing'),
('meta_keywords', 'clothing, fashion, style, ego'),
('google_analytics_id', ''),
('instagram_url', ''),
('facebook_url', ''),
('twitter_url', ''),
('tiktok_url', ''),
('linkedin_url', ''),
('youtube_url', '');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
  
  -- GENERAL SETTINGS
  `website_name` VARCHAR(255) DEFAULT 'Ego Clothing',
  `website_url` VARCHAR(255) DEFAULT 'https://ego-clothing.com',
  `contact_email` VARCHAR(255) DEFAULT NULL,
  `support_email` VARCHAR(255) DEFAULT NULL,
  `phone_number` VARCHAR(20) DEFAULT NULL,
  `working_hours` LONGTEXT DEFAULT NULL,
  
  -- BRANDING SETTINGS
  `logo` VARCHAR(500) DEFAULT NULL,
  `logo_light` VARCHAR(500) DEFAULT NULL,
  `logo_dark` VARCHAR(500) DEFAULT NULL,
  `favicon` VARCHAR(500) DEFAULT NULL,
  `primary_color` VARCHAR(7) DEFAULT '#b7926f',
  `secondary_color` VARCHAR(7) DEFAULT '#9e7e59',
  `accent_color` VARCHAR(7) DEFAULT '#88663d',
  `primary_font` VARCHAR(100) DEFAULT NULL,
  `secondary_font` VARCHAR(100) DEFAULT NULL,
  
  -- BRANDING - PAGE BACKGROUNDS
  `homepage_bg` VARCHAR(500) DEFAULT NULL,
  `shop_bg` VARCHAR(500) DEFAULT NULL,
  `contact_bg` VARCHAR(500) DEFAULT NULL,
  `login_bg` VARCHAR(500) DEFAULT NULL,
  `signup_bg` VARCHAR(500) DEFAULT NULL,
  
  -- CONTACT & LOCATION
  `address` LONGTEXT DEFAULT NULL,
  `google_maps_link` VARCHAR(500) DEFAULT NULL,
  `whatsapp_number` VARCHAR(20) DEFAULT NULL,
  
  -- SOCIAL LINKS
  `instagram_url` VARCHAR(500) DEFAULT NULL,
  `facebook_url` VARCHAR(500) DEFAULT NULL,
  `tiktok_url` VARCHAR(500) DEFAULT NULL,
  `twitter_url` VARCHAR(500) DEFAULT NULL,
  `linkedin_url` VARCHAR(500) DEFAULT NULL,
  `youtube_url` VARCHAR(500) DEFAULT NULL,
  
  -- SEO SETTINGS
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_description` LONGTEXT DEFAULT NULL,
  `meta_keywords` LONGTEXT DEFAULT NULL,
  `og_image` VARCHAR(500) DEFAULT NULL,
  
  -- PAYMENT SETTINGS
  `currency` VARCHAR(10) DEFAULT 'USD',
  `require_payment_proof` BOOLEAN DEFAULT FALSE,
  
  -- Payment Methods - COD
  `enable_cod` BOOLEAN DEFAULT TRUE,
  `cod_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - Wish Money
  `enable_wish_money` BOOLEAN DEFAULT FALSE,
  `wish_money_number` VARCHAR(100) DEFAULT NULL,
  `wish_money_name` VARCHAR(255) DEFAULT NULL,
  `wish_money_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - Bank Transfer
  `enable_bank_transfer` BOOLEAN DEFAULT FALSE,
  `bank_name` VARCHAR(255) DEFAULT NULL,
  `bank_account` VARCHAR(100) DEFAULT NULL,
  `bank_account_name` VARCHAR(255) DEFAULT NULL,
  `bank_instructions` LONGTEXT DEFAULT NULL,
  
  -- Payment Methods - OMT/Western Union
  `enable_omt` BOOLEAN DEFAULT FALSE,
  `omt_name` VARCHAR(255) DEFAULT NULL,
  `omt_instructions` LONGTEXT DEFAULT NULL,
  
  -- POLICIES
  `about_us` LONGTEXT DEFAULT NULL,
  `return_policy` LONGTEXT DEFAULT NULL,
  `shipping_policy` LONGTEXT DEFAULT NULL,
  `privacy_policy` LONGTEXT DEFAULT NULL,
  `terms_conditions` LONGTEXT DEFAULT NULL,
  
  -- EMAIL/SMTP SETTINGS
  `enable_smtp` BOOLEAN DEFAULT FALSE,
  `smtp_host` VARCHAR(255) DEFAULT NULL,
  `smtp_port` INT(5) DEFAULT 587,
  `smtp_username` VARCHAR(255) DEFAULT NULL,
  `smtp_password` VARCHAR(255) DEFAULT NULL,
  `smtp_encryption` ENUM('none', 'tls', 'ssl') DEFAULT 'tls',
  `smtp_from_name` VARCHAR(255) DEFAULT NULL,
  `smtp_from_email` VARCHAR(255) DEFAULT NULL,
  
  -- ANALYTICS & TRACKING
  `google_analytics_id` VARCHAR(50) DEFAULT NULL,
  `gtm_id` VARCHAR(50) DEFAULT NULL,
  `meta_pixel_id` VARCHAR(50) DEFAULT NULL,
  `tiktok_pixel_id` VARCHAR(50) DEFAULT NULL,
  
  -- SECURITY & MAINTENANCE
  `enable_maintenance` BOOLEAN DEFAULT FALSE,
  `maintenance_message` LONGTEXT DEFAULT NULL,
  `enable_recaptcha` BOOLEAN DEFAULT FALSE,
  `recaptcha_site_key` VARCHAR(255) DEFAULT NULL,
  `recaptcha_secret_key` VARCHAR(255) DEFAULT NULL,
  
  -- TIMESTAMPS
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  INDEX `idx_created_at` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (
  `website_name`, `website_url`, `primary_color`, `secondary_color`, `accent_color`, 
  `currency`, `enable_cod`, `smtp_port`, `smtp_encryption`
) VALUES (
  'Ego Clothing', 'https://ego-clothing.com', '#b7926f', '#9e7e59', '#88663d',
  'USD', TRUE, 587, 'tls'
);

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
