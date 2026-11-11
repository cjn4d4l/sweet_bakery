-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 09, 2026 at 07:05 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sweet_bakery`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(10) UNSIGNED NOT NULL,
  `username` varchar(60) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password_hash`, `created_at`) VALUES
(1, 'admin', '$2y$10$zbsmI60Lu2vp5vFO1PQ9V.l7q7SwzA6WyTV.cpZQH/wwgLUN3KJz.', '2026-11-09 05:32:34');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(10) UNSIGNED NOT NULL,
  `name` varchar(160) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(8,2) UNSIGNED NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `description`, `price`, `image_path`, `is_active`, `sort_order`, `created_at`, `updated_at`) VALUES
(1, 'Rainbow Dream Cupcakes', 'Fluffy vanilla cake topped with swirled rainbow buttercream, edible glitter, and a cherry on top. Pure kawaii magic!', 4.50, 'images/rainbow-cupcakes.jpg', 1, 1, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(2, 'Strawberry Glazed Donuts', 'Soft and fluffy donuts with sweet strawberry glaze and rainbow sprinkles. A breakfast favorite!', 3.50, 'images/glazed-donuts.jpg', 1, 2, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(3, 'Pastel Macarons', 'Delicate French macarons in lavender, rose, and mint flavors with creamy fillings.', 2.50, 'images/french-macarons.jpg', 1, 3, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(4, 'Choco Chip Cookies', 'Classic chocolate chip cookies, perfectly crispy on the edges and chewy in the center.', 2.00, 'images/chocolate-cookies.jpg', 1, 4, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(5, 'Kawaii Birthday Cake', 'Custom-designed celebration cake with your choice of flavors and adorable decorations.', 45.00, 'images/decorated-cake.jpg', 1, 5, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(6, 'Happy Face Brownies', 'Rich, fudgy brownies topped with cream cheese frosting and a sweet smile.', 3.00, 'images/brownies.jpg', 1, 6, '2026-11-09 05:23:36', '2026-11-09 05:23:36'),
(7, 'Blue Berry Cake', 'Blue Berry Cake', 5.00, 'admin/uploads/menu/b112d54b4b5d7e7883f75e18b3ab9091.jpg', 1, 10, '2026-11-09 05:35:48', '2026-11-09 05:35:48');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admin_users_username` (`username`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_products_public_menu` (`is_active`,`sort_order`,`name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
