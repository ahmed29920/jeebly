-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Apr 23, 2026 at 04:08 PM
-- Server version: 11.4.10-MariaDB
-- PHP Version: 8.4.19

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `teamqeem_geeble`
--

-- --------------------------------------------------------

--
-- Table structure for table `addresses`
--

CREATE TABLE `addresses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(150) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `address` varchar(255) NOT NULL,
  `latitude` double DEFAULT NULL,
  `longitude` double DEFAULT NULL,
  `city` varchar(100) NOT NULL,
  `country` varchar(100) NOT NULL,
  `state` varchar(100) DEFAULT NULL,
  `postal_code` varchar(20) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `addresses`
--

INSERT INTO `addresses` (`id`, `user_id`, `name`, `phone`, `address`, `latitude`, `longitude`, `city`, `country`, `state`, `postal_code`, `status`, `created_at`, `updated_at`) VALUES
(1, 5, 'Home', '0123456789', '3 Hadayek Al Qobba', 30.08986478428955, 31.285872226143614, 'Cairo', 'Egypt', NULL, NULL, 1, '2025-12-09 07:35:24', '2025-12-09 07:35:24'),
(3, 5, 'xxvccx', '4555525455', 'gfgfgfh', NULL, NULL, 'gfhgfhgf', 'gfhfggf', NULL, NULL, 0, '2026-02-04 12:42:09', '2026-02-04 15:34:30'),
(4, 19, 'jhhjk', '01324156451', 'uiiyiu', NULL, NULL, 'juyuyy', 'gtyrtrtr', NULL, NULL, 1, '2026-02-04 15:49:41', '2026-02-04 15:49:41'),
(5, 5, 'xvdfg', '21321231312', 'gfdg', 30.046503959653553, 31.238763369619846, 'dgfdgfdg', 'ddggf', NULL, NULL, 1, '2026-02-11 11:24:45', '2026-02-11 11:24:45'),
(6, 20, 'test', '123467857876', '3makram ebid', 30.043101054298415, 31.22699048370123, 'cairo', 'egypt', NULL, NULL, 1, '2026-02-23 18:18:39', '2026-02-23 18:18:39'),
(7, 5, 'روشنبيري', '07739004030', 'اوفيس', 36.1355721, 44.0357878, 'الموصل', 'العراق', NULL, NULL, 1, '2026-02-25 19:03:17', '2026-02-25 19:03:17'),
(8, 5, 'تيست', '077964588', 'شارع المتنبي', 29.97951737067705, 31.547029651701447, 'القاهرة', 'مصر', NULL, NULL, 1, '2026-04-12 18:16:10', '2026-04-12 18:16:10'),
(9, 31, 'مكتب', '07739004060', 'مستشفى', NULL, NULL, 'اربيل', 'العراق', NULL, NULL, 1, '2026-04-16 08:56:40', '2026-04-16 08:56:40'),
(10, 32, 'مكتب', '07512148568', 'المكتب', 30.017991691821287, 31.54039353132248, 'الموصل', 'العراق', NULL, NULL, 1, '2026-04-18 16:06:47', '2026-04-18 16:06:47'),
(11, 31, 'test address', '07739004060', 'Erbil capat', 30.084066874721117, 31.572350710630417, 'erbil', 'iraq', NULL, NULL, 1, '2026-04-18 16:20:40', '2026-04-18 16:20:40');

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `type` varchar(255) NOT NULL,
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_filterable` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_options`
--

CREATE TABLE `attribute_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `value` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `booking_lists`
--

CREATE TABLE `booking_lists` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL,
  `expected_at` datetime DEFAULT NULL,
  `status` enum('pending','confirmed','cancelled','completed') NOT NULL DEFAULT 'pending',
  `notified` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `slug` varchar(255) NOT NULL,
  `address` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`address`)),
  `phone` varchar(255) DEFAULT NULL,
  `latitude` decimal(10,8) NOT NULL,
  `longitude` decimal(11,8) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_online` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `name`, `slug`, `address`, `phone`, `latitude`, `longitude`, `is_active`, `is_online`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '{\"ar\": \"فرع مدينة نصر\", \"en\": \"Nasr City Branch\"}', 'nasr-city-branch', '{\"ar\": \"3 مكرم عبيد، بجوار محجوب\", \"en\": \"3 makram st, nearby mahgoub\"}', '+20112200112', 30.05509654, 31.34658527, 1, 1, '2025-12-07 12:22:31', '2026-02-25 19:06:23', NULL),
(2, '{\"ar\": \"فرع التجمع الخامس\", \"en\": \"5th Settlement Branch\"}', '5th-settlement-branch', '{\"ar\": \"مول بوينت 90 بارتيشن A1 الطابق الأول، التجمع الخامس\", \"en\": \"Point 90 mall, Partition A1 First Floor, 5th Settlement\"}', '+20112200116', 30.02060446, 31.49492781, 1, 1, '2025-12-07 12:28:33', '2025-12-07 12:28:33', NULL),
(3, '{\"ar\": \"فرع الشيخ زايد\", \"en\": \"Sheikh Zayed Branch\"}', 'sheikh-zayed-branch', '{\"ar\": \"داندي مول ، الطابق الثاني ، الشيخ زايد\", \"en\": \"Dandy Mall, 2nd floor, Sheikh Zayed City\"}', '+20112200113', 30.06757766, 31.02710837, 1, 1, '2025-12-07 12:30:58', '2025-12-07 12:30:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_product_stocks`
--

CREATE TABLE `branch_product_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_product_stocks`
--

INSERT INTO `branch_product_stocks` (`id`, `branch_id`, `product_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 13, '2025-12-08 08:14:09', '2026-02-25 15:37:25', NULL),
(2, 2, 1, 15, '2025-12-08 08:14:09', '2025-12-08 08:14:09', NULL),
(3, 3, 1, 7, '2025-12-08 08:14:09', '2025-12-08 08:14:09', NULL),
(4, 1, 3, 78, '2025-12-08 10:00:20', '2026-02-18 21:04:16', NULL),
(5, 2, 3, 40, '2025-12-08 10:00:20', '2025-12-08 10:00:34', NULL),
(6, 3, 3, 0, '2025-12-08 10:00:20', '2025-12-08 10:00:20', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `branch_stock_history`
--

CREATE TABLE `branch_stock_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED DEFAULT NULL,
  `product_variant_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `type` enum('manual_add','manual_update','order_decrease','order_cancel','adjustment') NOT NULL DEFAULT 'manual_update',
  `quantity_change` int(11) NOT NULL,
  `quantity_before` int(11) NOT NULL,
  `quantity_after` int(11) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_stock_history`
--

INSERT INTO `branch_stock_history` (`id`, `branch_id`, `product_id`, `product_variant_id`, `order_id`, `user_id`, `type`, `quantity_change`, `quantity_before`, `quantity_after`, `notes`, `created_at`, `updated_at`) VALUES
(1, 1, 1, NULL, NULL, 1, 'manual_update', -5, 15, 10, 'Manual stock update from dashboard', '2025-12-08 10:38:44', '2025-12-08 10:38:44'),
(2, 1, 4, 4, NULL, 1, 'manual_update', 4, 8, 12, 'Manual stock update from dashboard', '2025-12-08 10:39:41', '2025-12-08 10:39:41'),
(4, 1, 4, 4, NULL, 2, 'manual_update', 3, 12, 15, 'Manual stock update from dashboard', '2025-12-09 10:57:47', '2025-12-09 10:57:47'),
(7, 1, 4, 4, NULL, 2, 'manual_update', -2, 15, 13, 'Manual stock update from dashboard', '2025-12-09 10:58:51', '2025-12-09 10:58:51'),
(9, 1, 3, 4, 3, 2, 'order_decrease', -1, 13, 12, 'Stock decreased due to order', '2025-12-09 11:03:52', '2025-12-09 11:03:52'),
(10, 1, 4, 4, 3, 2, 'order_decrease', -1, 12, 11, 'Stock decreased due to order', '2025-12-09 11:03:52', '2025-12-09 11:03:52'),
(11, 1, 3, NULL, 3, 2, 'order_decrease', -1, 50, 49, 'Stock decreased due to order', '2025-12-09 11:08:11', '2025-12-09 11:08:11'),
(12, 1, 4, 4, 3, 2, 'order_decrease', -1, 11, 10, 'Stock decreased due to order', '2025-12-09 11:08:11', '2025-12-09 11:08:11'),
(13, 3, 4, 3, NULL, 4, 'manual_update', -3, 10, 7, 'Manual stock update from dashboard', '2025-12-09 12:28:55', '2025-12-09 12:28:55'),
(15, 1, 4, 5, NULL, 1, 'manual_update', 15, 0, 15, 'Manual stock update from dashboard', '2025-12-10 08:44:02', '2025-12-10 08:44:02'),
(16, 2, 4, 5, NULL, 1, 'manual_update', 13, 0, 13, 'Manual stock update from dashboard', '2025-12-10 08:44:02', '2025-12-10 08:44:02'),
(17, 3, 4, 5, NULL, 1, 'manual_update', 20, 0, 20, 'Manual stock update from dashboard', '2025-12-10 08:44:02', '2025-12-10 08:44:02'),
(18, 1, 1, NULL, NULL, 2, 'manual_update', 1, 10, 11, 'Manual stock update from dashboard', '2025-12-10 12:37:40', '2025-12-10 12:37:40'),
(19, 1, 1, NULL, NULL, 2, 'manual_update', 4, 11, 15, 'Manual stock update from dashboard', '2025-12-10 12:46:45', '2025-12-10 12:46:45'),
(20, 1, 1, NULL, NULL, 2, 'manual_update', -2, 15, 13, 'Manual stock update from dashboard', '2025-12-10 12:46:59', '2025-12-10 12:46:59'),
(21, 2, 4, 3, 2, 1, 'order_decrease', -1, 25, 24, 'Stock decreased due to order', '2025-12-11 13:28:54', '2025-12-11 13:28:54'),
(22, 2, 4, 4, 2, 1, 'order_decrease', -1, 7, 6, 'Stock decreased due to order', '2025-12-11 13:28:54', '2025-12-11 13:28:54'),
(23, 1, 3, NULL, 5, 1, 'order_decrease', -5, 49, 44, 'Stock decreased due to order', '2025-12-29 06:33:11', '2025-12-29 06:33:11'),
(24, 1, 4, 4, 5, 1, 'order_decrease', -1, 10, 9, 'Stock decreased due to order', '2025-12-29 06:33:11', '2025-12-29 06:33:11'),
(25, 1, 4, 4, 7, 1, 'order_decrease', -1, 9, 8, 'Stock decreased due to order', '2025-12-29 09:28:23', '2025-12-29 09:28:23'),
(26, 1, 2, 1, 36, 1, 'order_decrease', -1, 15, 14, 'Stock decreased due to order', '2026-01-13 11:27:11', '2026-01-13 11:27:11'),
(27, 1, 2, 1, 35, 1, 'order_decrease', -1, 14, 13, 'Stock decreased due to order', '2026-01-13 14:17:37', '2026-01-13 14:17:37'),
(28, 1, 2, 1, 34, 1, 'order_decrease', -1, 13, 12, 'Stock decreased due to order', '2026-01-13 14:44:03', '2026-01-13 14:44:03'),
(29, 1, 1, NULL, 33, 1, 'order_decrease', -1, 13, 12, 'Stock decreased due to order', '2026-01-13 14:49:58', '2026-01-13 14:49:58'),
(30, 1, 4, 3, 33, 1, 'order_decrease', -1, 25, 24, 'Stock decreased due to order', '2026-01-13 14:49:58', '2026-01-13 14:49:58'),
(31, 1, 4, 4, 33, 1, 'order_decrease', -1, 8, 7, 'Stock decreased due to order', '2026-01-13 14:49:58', '2026-01-13 14:49:58'),
(32, 1, 1, NULL, 37, 2, 'order_decrease', -2, 12, 10, 'Stock decreased due to order', '2026-01-29 11:00:10', '2026-01-29 11:00:10'),
(33, 1, 1, NULL, NULL, 2, 'manual_update', 23, 10, 33, 'Manual stock update from dashboard', '2026-02-18 14:05:42', '2026-02-18 14:05:42'),
(34, 1, 1, NULL, NULL, 2, 'manual_update', 23, 33, 56, 'Manual stock update from dashboard', '2026-02-18 14:05:51', '2026-02-18 14:05:51'),
(35, 1, 1, NULL, NULL, 2, 'manual_update', 23, 56, 79, 'Manual stock update from dashboard', '2026-02-18 14:05:59', '2026-02-18 14:05:59'),
(36, 1, 1, NULL, NULL, 2, 'manual_update', 21, 79, 100, 'Manual stock update from dashboard', '2026-02-18 14:06:13', '2026-02-18 14:06:13'),
(37, 1, 1, NULL, NULL, 2, 'manual_update', 23, 100, 123, 'Manual stock update from dashboard', '2026-02-18 14:06:26', '2026-02-18 14:06:26'),
(38, 1, 1, NULL, NULL, 2, 'manual_update', 23, 123, 146, 'Manual stock update from dashboard', '2026-02-18 14:06:32', '2026-02-18 14:06:32'),
(39, 1, 1, NULL, NULL, 2, 'manual_update', 21, 146, 167, 'Manual stock update from dashboard', '2026-02-18 14:07:42', '2026-02-18 14:07:42'),
(40, 1, 1, NULL, NULL, 2, 'manual_update', 21, 167, 188, 'Manual stock update from dashboard', '2026-02-18 14:08:02', '2026-02-18 14:08:02'),
(41, 1, 3, NULL, NULL, 2, 'manual_update', -44, 44, 0, 'Manual stock update from dashboard', '2026-02-18 21:04:04', '2026-02-18 21:04:04'),
(42, 1, 3, NULL, NULL, 2, 'manual_update', 39, 0, 39, 'Manual stock update from dashboard', '2026-02-18 21:04:14', '2026-02-18 21:04:14'),
(43, 1, 3, NULL, NULL, 2, 'manual_update', 39, 39, 78, 'Manual stock update from dashboard', '2026-02-18 21:04:16', '2026-02-18 21:04:16'),
(44, 1, 1, NULL, NULL, 2, 'manual_update', 12, 188, 200, 'Manual stock update from dashboard', '2026-02-23 19:33:43', '2026-02-23 19:33:43'),
(45, 1, 1, NULL, NULL, 2, 'manual_update', -187, 200, 13, 'Manual stock update from dashboard', '2026-02-25 15:37:25', '2026-02-25 15:37:25');

-- --------------------------------------------------------

--
-- Table structure for table `branch_variant_stocks`
--

CREATE TABLE `branch_variant_stocks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branch_variant_stocks`
--

INSERT INTO `branch_variant_stocks` (`id`, `branch_id`, `product_variant_id`, `quantity`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 1, 12, '2025-12-08 09:45:34', '2026-01-13 14:44:03', NULL),
(2, 2, 1, 12, '2025-12-08 09:45:34', '2025-12-08 09:45:34', NULL),
(3, 3, 1, 20, '2025-12-08 09:45:34', '2025-12-08 09:45:34', NULL),
(4, 1, 2, 15, '2025-12-08 09:45:34', '2025-12-08 09:45:34', NULL),
(5, 2, 2, 14, '2025-12-08 09:45:34', '2025-12-08 09:45:34', NULL),
(6, 3, 2, 10, '2025-12-08 09:45:34', '2025-12-08 09:57:56', NULL),
(7, 1, 3, 24, '2025-12-08 10:07:08', '2026-01-13 14:49:58', NULL),
(8, 2, 3, 24, '2025-12-08 10:07:08', '2025-12-11 13:28:54', NULL),
(9, 3, 3, 7, '2025-12-08 10:07:08', '2025-12-09 12:28:55', NULL),
(10, 1, 4, 7, '2025-12-08 10:07:08', '2026-01-13 14:49:58', NULL),
(11, 2, 4, 6, '2025-12-08 10:07:08', '2025-12-11 13:28:54', NULL),
(12, 3, 4, 3, '2025-12-08 10:07:08', '2025-12-08 10:07:08', NULL),
(13, 1, 5, 15, '2025-12-10 08:44:02', '2025-12-10 08:44:02', NULL),
(14, 2, 5, 13, '2025-12-10 08:44:02', '2025-12-10 08:44:02', NULL),
(15, 3, 5, 20, '2025-12-10 08:44:02', '2025-12-10 08:44:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('geeble-cache-settings.all', 'a:19:{s:8:\"app_name\";s:6:\"GEEBLE\";s:8:\"app_logo\";s:53:\"settings/8Nc503jJ3AumyERrQE27JoaVZNzQxbNnOTFDnnTK.png\";s:8:\"app_icon\";s:53:\"settings/gaW4wfyfjCwmJxfh9wNkPfS92VPcPgH2F801X9hD.png\";s:26:\"invitation_discount_points\";s:3:\"100\";s:13:\"shipping_cost\";s:2:\"10\";s:7:\"fb_link\";s:24:\"https://www.facebook.com\";s:10:\"insta_link\";s:24:\"ttps://www.instagram.com\";s:12:\"tik_tok_link\";s:22:\"https://www.tiktok.com\";s:17:\"order_points_rate\";s:1:\"5\";s:25:\"inviter_order_points_rate\";s:1:\"2\";s:19:\"point_to_money_rate\";s:2:\"25\";s:18:\"allow_order_points\";s:1:\"1\";s:26:\"allow_inviter_order_points\";s:1:\"1\";s:29:\"max_points_discount_per_order\";s:3:\"100\";s:29:\"allow_more_than_one_free_item\";s:1:\"1\";s:32:\"allow_branch_admin_to_edit_stock\";s:1:\"1\";s:17:\"min_shipping_cost\";s:2:\"25\";s:31:\"delivery_man_calculation_method\";s:10:\"percentage\";s:30:\"delivery_man_calculation_value\";s:1:\"2\";}', 2087219696),
('geeble-cache-spatie.permission.cache', 'a:3:{s:5:\"alias\";a:0:{}s:11:\"permissions\";a:0:{}s:5:\"roles\";a:0:{}}', 1776429752);

-- --------------------------------------------------------

--
-- Table structure for table `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cart_items`
--

CREATE TABLE `cart_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cart_items`
--

INSERT INTO `cart_items` (`id`, `user_id`, `product_id`, `quantity`, `created_at`, `updated_at`, `variant_id`) VALUES
(45, 19, 1, 2, '2026-02-10 12:12:22', '2026-02-10 12:12:23', NULL),
(194, 31, 1, 1, '2026-04-22 19:32:52', '2026-04-22 19:32:52', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `parent_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `slug` varchar(255) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` int(11) NOT NULL DEFAULT 1,
  `view_in_home` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_title`)),
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_description`)),
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_keywords`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `parent_id`, `name`, `slug`, `description`, `image`, `is_active`, `sort_order`, `view_in_home`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, '{\"ar\": \"فواكة\", \"en\": \"Fruits\"}', 'fruits', '{\"ar\": null}', 'categories/8BDTPW6d93X8WZkjrKUGeSVJDSTRAu3dxxeGtyUA.png', 1, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-07 12:11:36', '2026-01-14 11:55:14', NULL),
(2, NULL, '{\"ar\": \"مخبوزات\", \"en\": \"Breads\"}', 'breads', '{\"ar\": null}', 'categories/JP5DszreQl2CeXIARhKw3dJWbRfTloj6tOUgR3HX.png', 1, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-07 12:12:35', '2025-12-07 12:12:35', NULL),
(3, NULL, '{\"ar\": \"خضروات\", \"en\": \"Vegetables\"}', 'vegetables', '{\"ar\": null}', 'categories/sK8AYG9Kwin16aEE0D3paQlawofOYSxk5KkBIujg.png', 1, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-07 12:13:24', '2025-12-07 12:13:24', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `category_product`
--

CREATE TABLE `category_product` (
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `category_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `category_product`
--

INSERT INTO `category_product` (`product_id`, `category_id`, `created_at`, `updated_at`) VALUES
(1, 3, NULL, NULL),
(2, 1, NULL, NULL),
(3, 1, NULL, NULL),
(4, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `coupons`
--

CREATE TABLE `coupons` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(255) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `type` enum('percentage','fixed') NOT NULL,
  `coupon_discount_value` decimal(10,2) NOT NULL,
  `min_cart_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `usage_limit` int(11) DEFAULT NULL,
  `used_count` int(11) NOT NULL DEFAULT 0,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `coupons`
--

INSERT INTO `coupons` (`id`, `code`, `description`, `type`, `coupon_discount_value`, `min_cart_amount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Hello25', '{\"ar\":null}', 'percentage', 25.00, 0.00, 1, 0, NULL, NULL, 1, '2026-01-04 12:38:20', '2026-01-04 12:38:29');

-- --------------------------------------------------------

--
-- Table structure for table `coupon_usages`
--

CREATE TABLE `coupon_usages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `used_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `coupon_user`
--

CREATE TABLE `coupon_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coupon_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `usage_count` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `deliveries`
--

CREATE TABLE `deliveries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `is_online` tinyint(1) NOT NULL DEFAULT 1,
  `plate_number` varchar(255) NOT NULL,
  `vehicle_name` varchar(255) NOT NULL,
  `vehicle_type` varchar(255) NOT NULL,
  `vehicle_color` varchar(255) NOT NULL,
  `wallet` decimal(10,2) NOT NULL,
  `documents` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`documents`)),
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `deliveries`
--

INSERT INTO `deliveries` (`id`, `user_id`, `is_online`, `plate_number`, `vehicle_name`, `vehicle_type`, `vehicle_color`, `wallet`, `documents`, `branch_id`, `created_at`, `updated_at`) VALUES
(3, 10, 0, '321 ABC', 'Halawa', 'Motorcycle', 'Blue', 0.00, NULL, NULL, '2025-12-28 13:38:23', '2025-12-28 13:38:23'),
(4, 11, 0, 'YUY 252', '--', 'Bike', 'Black', 0.00, '[\"deliveries/documents/fiE7FZy6IEaKLO0HOtmVaOSnq5V4w2Mz7OmgIuAR.jpg\"]', 2, '2025-12-29 06:25:32', '2025-12-29 06:25:32'),
(5, 12, 1, 'PPP 999', 'Honda', 'Motorcycle', 'Red', 1083.11, '[\"deliveries/documents/8P1D7yVlTAVhO4cgQ9TLGnZaCsKsJDpe631JKDE3.png\"]', 1, '2025-12-29 06:58:43', '2026-03-16 17:24:27'),
(7, 14, 1, '111', 'honda', 'motorocycle', 'white', 2000.00, '[]', 1, '2026-02-02 09:58:27', '2026-02-02 09:58:27');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_wallet_history`
--

CREATE TABLE `delivery_wallet_history` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(10,2) NOT NULL,
  `type` enum('credit','debit') NOT NULL DEFAULT 'credit',
  `wallet_before` decimal(10,2) NOT NULL,
  `wallet_after` decimal(10,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_wallet_history`
--

INSERT INTO `delivery_wallet_history` (`id`, `delivery_id`, `order_id`, `amount`, `type`, `wallet_before`, `wallet_after`, `notes`, `created_at`, `updated_at`) VALUES
(2, 5, 65, 4.20, 'credit', 0.00, 4.20, 'Payment for completed order #65', '2026-02-18 12:21:59', '2026-02-18 12:21:59'),
(3, 5, 69, 9.40, 'credit', 4.20, 13.60, 'Payment for completed order #69', '2026-02-18 12:23:02', '2026-02-18 12:23:02'),
(4, 5, 75, 1.80, 'credit', 13.60, 15.40, 'Payment for completed order #75', '2026-02-18 12:24:48', '2026-02-18 12:24:48'),
(5, 5, 77, 943.51, 'credit', 15.40, 958.91, 'Payment for completed order #77', '2026-02-18 12:25:17', '2026-02-18 12:25:17'),
(6, 5, 100, 3.80, 'credit', 958.91, 962.71, 'Payment for completed order #100', '2026-02-24 13:41:52', '2026-02-24 13:41:52'),
(7, 5, 72, 5.40, 'credit', 962.71, 968.11, 'Payment for completed order #72', '2026-02-24 14:08:12', '2026-02-24 14:08:12'),
(8, 5, 78, 3.40, 'credit', 968.11, 971.51, 'Payment for completed order #78', '2026-02-24 14:26:32', '2026-02-24 14:26:32'),
(9, 5, 80, 7.40, 'credit', 971.51, 978.91, 'Payment for completed order #80', '2026-02-26 02:41:56', '2026-02-26 02:41:56'),
(10, 5, 81, 3.40, 'credit', 978.91, 982.31, 'Payment for completed order #81', '2026-02-26 02:42:12', '2026-02-26 02:42:12'),
(11, 5, 82, 2.20, 'credit', 982.31, 984.51, 'Payment for completed order #82', '2026-02-26 02:42:28', '2026-02-26 02:42:28'),
(12, 5, 101, 3.80, 'credit', 984.51, 988.31, 'Payment for completed order #101', '2026-02-26 02:42:36', '2026-02-26 02:42:36'),
(13, 5, 118, 3.80, 'credit', 988.31, 992.11, 'Payment for completed order #118', '2026-02-26 22:38:13', '2026-02-26 22:38:13'),
(14, 5, 119, 2.20, 'credit', 992.11, 994.31, 'Payment for completed order #119', '2026-02-27 12:08:24', '2026-02-27 12:08:24'),
(15, 5, 116, 3.80, 'credit', 994.31, 998.11, 'Payment for completed order #116', '2026-02-28 01:06:05', '2026-02-28 01:06:05'),
(16, 5, 106, 3.80, 'credit', 998.11, 1001.91, 'Payment for completed order #106', '2026-02-28 01:06:11', '2026-02-28 01:06:11'),
(17, 5, 105, 5.80, 'credit', 1001.91, 1007.71, 'Payment for completed order #105', '2026-02-28 01:06:18', '2026-02-28 01:06:18'),
(18, 5, 122, 3.80, 'credit', 1007.71, 1011.51, 'Payment for completed order #122', '2026-02-28 01:10:16', '2026-02-28 01:10:16'),
(19, 5, 125, 4.20, 'credit', 1011.51, 1015.71, 'Payment for completed order #125', '2026-03-03 00:12:10', '2026-03-03 00:12:10'),
(20, 5, 126, 7.00, 'credit', 1015.71, 1022.71, 'Payment for completed order #126', '2026-03-03 23:11:58', '2026-03-03 23:11:58'),
(21, 5, 127, 2.20, 'credit', 1022.71, 1024.91, 'Payment for completed order #127', '2026-03-04 19:29:55', '2026-03-04 19:29:55'),
(22, 5, 67, 3.80, 'credit', 1024.91, 1028.71, 'Payment for completed order #67', '2026-03-04 19:35:58', '2026-03-04 19:35:58'),
(23, 5, 98, 8.60, 'credit', 1028.71, 1037.31, 'Payment for completed order #98', '2026-03-04 19:36:06', '2026-03-04 19:36:06'),
(24, 5, 99, 3.80, 'credit', 1037.31, 1041.11, 'Payment for completed order #99', '2026-03-04 19:36:13', '2026-03-04 19:36:13'),
(25, 5, 108, 5.40, 'credit', 1041.11, 1046.51, 'Payment for completed order #108', '2026-03-04 19:36:23', '2026-03-04 19:36:23'),
(26, 5, 109, 5.40, 'credit', 1046.51, 1051.91, 'Payment for completed order #109', '2026-03-04 19:36:30', '2026-03-04 19:36:30'),
(27, 5, 110, 3.80, 'credit', 1051.91, 1055.71, 'Payment for completed order #110', '2026-03-04 19:36:39', '2026-03-04 19:36:39'),
(28, 5, 112, 3.00, 'credit', 1055.71, 1058.71, 'Payment for completed order #112', '2026-03-04 19:36:46', '2026-03-04 19:36:46'),
(29, 5, 113, 3.80, 'credit', 1058.71, 1062.51, 'Payment for completed order #113', '2026-03-04 19:36:53', '2026-03-04 19:36:53'),
(30, 5, 115, 3.80, 'credit', 1062.51, 1066.31, 'Payment for completed order #115', '2026-03-04 19:36:59', '2026-03-04 19:36:59'),
(31, 5, 117, 4.20, 'credit', 1066.31, 1070.51, 'Payment for completed order #117', '2026-03-04 19:37:06', '2026-03-04 19:37:06'),
(32, 5, 124, 1.80, 'credit', 1070.51, 1072.31, 'Payment for completed order #124', '2026-03-04 19:37:16', '2026-03-04 19:37:16'),
(33, 5, 129, 6.20, 'credit', 1072.31, 1078.51, 'Payment for completed order #129', '2026-03-04 21:23:40', '2026-03-04 21:23:40'),
(34, 5, 135, 4.60, 'credit', 1078.51, 1083.11, 'Payment for completed order #135', '2026-03-16 17:24:27', '2026-03-16 17:24:27');

-- --------------------------------------------------------

--
-- Table structure for table `delivery_zone`
--

CREATE TABLE `delivery_zone` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `delivery_id` bigint(20) UNSIGNED NOT NULL,
  `zone_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `delivery_zone`
--

INSERT INTO `delivery_zone` (`id`, `delivery_id`, `zone_id`, `created_at`, `updated_at`) VALUES
(1, 7, 2, '2026-04-08 15:29:55', '2026-04-08 15:29:55'),
(2, 5, 2, '2026-04-08 15:29:55', '2026-04-08 15:29:55'),
(3, 5, 1, '2026-04-08 15:30:15', '2026-04-08 15:30:15');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `favorites`
--

CREATE TABLE `favorites` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `favorites`
--

INSERT INTO `favorites` (`id`, `user_id`, `product_id`, `created_at`, `updated_at`) VALUES
(32, 19, 3, '2026-02-10 08:54:03', '2026-02-10 08:54:03'),
(35, 19, 1, '2026-02-10 14:29:23', '2026-02-10 14:29:23');

-- --------------------------------------------------------

--
-- Table structure for table `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jobs`
--

INSERT INTO `jobs` (`id`, `queue`, `payload`, `attempts`, `reserved_at`, `available_at`, `created_at`) VALUES
(1, 'default', '{\"uuid\":\"753e478a-0774-4931-8d9a-8b775673c609\",\"displayName\":\"App\\\\Events\\\\DeliveryLocationUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":15:{s:5:\\\"event\\\";O:34:\\\"App\\\\Events\\\\DeliveryLocationUpdated\\\":3:{s:10:\\\"deliveryId\\\";i:9;s:3:\\\"lat\\\";s:11:\\\"30.05509654\\\";s:3:\\\"lng\\\";s:11:\\\"31.34658527\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1767007964,\"delay\":null}', 0, NULL, 1767007964, 1767007964),
(2, 'default', '{\"uuid\":\"4e89273c-8e04-4f1a-888f-0592934e3650\",\"displayName\":\"App\\\\Events\\\\DeliveryLocationUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":15:{s:5:\\\"event\\\";O:34:\\\"App\\\\Events\\\\DeliveryLocationUpdated\\\":3:{s:10:\\\"deliveryId\\\";i:9;s:3:\\\"lat\\\";s:11:\\\"30.05509651\\\";s:3:\\\"lng\\\";s:11:\\\"31.34658526\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1767085643,\"delay\":null}', 0, NULL, 1767085643, 1767085643),
(3, 'default', '{\"uuid\":\"470a8d3b-efba-4eb4-bd9f-8546d4205ad3\",\"displayName\":\"App\\\\Events\\\\DeliveryLocationUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":15:{s:5:\\\"event\\\";O:34:\\\"App\\\\Events\\\\DeliveryLocationUpdated\\\":3:{s:10:\\\"deliveryId\\\";i:9;s:3:\\\"lat\\\";s:11:\\\"30.05509653\\\";s:3:\\\"lng\\\";s:11:\\\"31.34658525\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1767085694,\"delay\":null}', 0, NULL, 1767085694, 1767085694),
(4, 'default', '{\"uuid\":\"bc03fff6-3e2f-4b7e-b86a-73a39e4a6a9c\",\"displayName\":\"App\\\\Events\\\\DeliveryLocationUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":15:{s:5:\\\"event\\\";O:34:\\\"App\\\\Events\\\\DeliveryLocationUpdated\\\":3:{s:10:\\\"deliveryId\\\";i:9;s:3:\\\"lat\\\";s:11:\\\"30.05509653\\\";s:3:\\\"lng\\\";s:11:\\\"31.34658525\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1767086049,\"delay\":null}', 0, NULL, 1767086049, 1767086049),
(5, 'default', '{\"uuid\":\"49b3e5a4-3922-4bfb-845b-271b22d921e4\",\"displayName\":\"App\\\\Events\\\\DeliveryLocationUpdated\",\"job\":\"Illuminate\\\\Queue\\\\CallQueuedHandler@call\",\"maxTries\":null,\"maxExceptions\":null,\"failOnTimeout\":false,\"backoff\":null,\"timeout\":null,\"retryUntil\":null,\"data\":{\"commandName\":\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\",\"command\":\"O:38:\\\"Illuminate\\\\Broadcasting\\\\BroadcastEvent\\\":15:{s:5:\\\"event\\\";O:34:\\\"App\\\\Events\\\\DeliveryLocationUpdated\\\":3:{s:10:\\\"deliveryId\\\";i:9;s:3:\\\"lat\\\";s:11:\\\"30.05509653\\\";s:3:\\\"lng\\\";s:11:\\\"31.34658525\\\";}s:5:\\\"tries\\\";N;s:7:\\\"timeout\\\";N;s:7:\\\"backoff\\\";N;s:13:\\\"maxExceptions\\\";N;s:10:\\\"connection\\\";N;s:5:\\\"queue\\\";N;s:12:\\\"messageGroup\\\";N;s:5:\\\"delay\\\";N;s:11:\\\"afterCommit\\\";N;s:10:\\\"middleware\\\";a:0:{}s:7:\\\"chained\\\";a:0:{}s:15:\\\"chainConnection\\\";N;s:10:\\\"chainQueue\\\";N;s:19:\\\"chainCatchCallbacks\\\";N;}\"},\"createdAt\":1767086146,\"delay\":null}', 0, NULL, 1767086146, 1767086146);

-- --------------------------------------------------------

--
-- Table structure for table `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(43, '0001_01_01_000000_create_users_table', 1),
(44, '0001_01_01_000001_create_cache_table', 1),
(45, '0001_01_01_000002_create_jobs_table', 1),
(46, '2025_09_23_095404_create_personal_access_tokens_table', 1),
(47, '2025_09_23_100139_create_permission_tables', 1),
(48, '2025_09_23_102157_create_settings_table', 1),
(49, '2025_09_23_113838_create_categories_table', 1),
(50, '2025_09_23_150515_create_attributes_table', 1),
(51, '2025_09_23_151013_create_attribute_options_table', 1),
(52, '2025_09_24_085031_create_products_table', 1),
(53, '2025_09_24_085855_create_product_attribute_values_table', 1),
(54, '2025_09_24_091840_create_product_images_table', 1),
(55, '2025_09_24_161738_create_product_relations_table', 1),
(56, '2025_09_25_141535_create_favorites_table', 1),
(57, '2025_09_25_143525_create_cart_items_table', 1),
(58, '2025_09_27_094018_create_coupons_table', 1),
(59, '2025_09_27_110009_create_addresses_table', 1),
(60, '2025_09_27_110528_create_orders_table', 1),
(61, '2025_09_27_110700_create_order_items_table', 1),
(62, '2025_09_28_093306_create_order_comments_table', 1),
(63, '2025_09_28_110309_create_notifications_table', 1),
(64, '2025_09_28_154028_create_reviews_table', 1),
(65, '2025_09_29_115028_create_coupon_user_table', 1),
(66, '2025_09_30_145706_create_transactions_table', 1),
(67, '2025_10_14_100842_edit_products_table', 1),
(68, '2025_10_14_101848_create_category_product_table', 1),
(69, '2025_10_22_131304_create_tickets_table', 1),
(70, '2025_10_22_131522_create_ticket_messages_table', 1),
(71, '2025_11_02_161251_add_points_to_users_table', 1),
(72, '2025_11_03_095945_create_offers_table', 1),
(73, '2025_11_03_103816_add_offer_id_to_orders_table', 1),
(74, '2025_11_03_114625_add_free_quantity_toorder_items_table', 1),
(75, '2025_11_04_092857_create_branches_table', 1),
(76, '2025_11_04_095437_add_branch_id_to_orders_table', 1),
(77, '2025_11_04_102436_add_image_to_offers_table', 1),
(78, '2025_11_04_103329_create_sliders_table', 1),
(79, '2025_11_04_153101_create_variants_table', 1),
(80, '2025_11_04_155919_create_variant_options_table', 1),
(81, '2025_11_04_163459_create_product_variants_table', 1),
(82, '2025_11_04_164214_create_product_variant_values_table', 1),
(83, '2025_11_05_092211_convert_product_images_to_polymorphic_table', 1),
(84, '2025_11_05_105906_add_type_to_products_table', 1),
(85, '2025_11_05_144232_edit_cart_items_table', 1),
(86, '2025_11_05_152349_edit_order_items_table', 1),
(87, '2025_11_06_094851_add_is_bookable_to_products_table', 1),
(88, '2025_11_06_134458_create_booking_lists_table', 1),
(89, '2025_12_07_130217_create_branch_product_stocks_table', 1),
(90, '2025_09_27_1102589_create_coupon_usages_table', 2),
(91, '2025_12_07_143733_create_branch_variant_stocks_table', 3),
(92, '2025_12_07_151316_create_units_table', 4),
(93, '2025_12_07_152220_add_unit_id_to_products_table', 5),
(94, '2025_12_08_123534_create_branch_stock_history_table', 6),
(95, '2025_12_08_124450_add_branch_id_to_users_table', 7),
(96, '2025_12_08_181006_add_lat_and_kong_to_address_table', 8),
(97, '2025_12_28_120838_add_delivery_to_users_role', 9),
(99, '2025_12_28_122433_create_deliveries_table', 10),
(100, '2025_12_28_174059_add_delivery_id_to_orders_table', 11),
(101, '2025_12_29_095127_add_out_for_delivery_status_to_orders_table', 12),
(103, '2025_12_29_101013_create_delivery_wallet_history_table', 13),
(104, '2026_02_25_111236_add_is_online_in_branches_table', 14),
(105, '2026_03_01_124444_add_note_to_orders_table', 15),
(106, '2026_04_08_101615_create_zones_table', 16),
(107, '2026_04_08_102239_create_delivery_zone_table', 16);

-- --------------------------------------------------------

--
-- Table structure for table `model_has_permissions`
--

CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `model_has_roles`
--

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `model_has_roles`
--

INSERT INTO `model_has_roles` (`role_id`, `model_type`, `model_id`) VALUES
(1, 'App\\Models\\User', 1),
(2, 'App\\Models\\User', 2),
(2, 'App\\Models\\User', 3),
(2, 'App\\Models\\User', 4),
(3, 'App\\Models\\User', 5),
(2, 'App\\Models\\User', 6),
(4, 'App\\Models\\User', 9),
(4, 'App\\Models\\User', 10),
(4, 'App\\Models\\User', 11),
(4, 'App\\Models\\User', 12),
(4, 'App\\Models\\User', 13),
(3, 'App\\Models\\User', 15),
(3, 'App\\Models\\User', 16),
(3, 'App\\Models\\User', 17),
(3, 'App\\Models\\User', 18),
(3, 'App\\Models\\User', 19),
(3, 'App\\Models\\User', 20),
(3, 'App\\Models\\User', 22),
(3, 'App\\Models\\User', 23),
(3, 'App\\Models\\User', 24),
(3, 'App\\Models\\User', 25),
(3, 'App\\Models\\User', 26),
(3, 'App\\Models\\User', 27),
(3, 'App\\Models\\User', 28),
(3, 'App\\Models\\User', 29),
(3, 'App\\Models\\User', 30),
(3, 'App\\Models\\User', 31),
(3, 'App\\Models\\User', 32),
(3, 'App\\Models\\User', 33);

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` char(36) NOT NULL,
  `type` varchar(255) NOT NULL,
  `notifiable_type` varchar(255) NOT NULL,
  `notifiable_id` bigint(20) UNSIGNED NOT NULL,
  `data` text NOT NULL,
  `read_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `type`, `notifiable_type`, `notifiable_id`, `data`, `read_at`, `created_at`, `updated_at`) VALUES
('00bf27e2-151d-4bd8-b43a-175b5fbb50d4', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":123,\"order_uuid\":\"f59ed107-5c12-480a-9b03-9cbe3602def9\",\"customer_name\":\"Ahmed dd\",\"total\":\"310.06\",\"status\":\"pending\"}', NULL, '2026-03-02 22:16:09', '2026-03-02 22:16:09'),
('03c55304-8ff9-46d1-9d15-4e81a44e864e', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":113,\"order_uuid\":\"f5e73b82-0711-4bf4-ad40-3de2171e64c1\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 13:01:38', '2026-02-26 13:01:38'),
('06e7840a-5f5e-43c0-8a67-da13c51c6e3e', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":135,\"order_uuid\":\"49b69c00-27ef-4603-90b1-98dfd7506d49\",\"customer_name\":\"Ahmed dd\",\"total\":\"230.06\",\"status\":\"pending\"}', NULL, '2026-03-05 16:06:20', '2026-03-05 16:06:20'),
('08fe73fd-8881-437f-871e-8879860f0159', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":34,\"order_uuid\":\"0650de62-92dc-4fb4-a2ae-fd0391378563\",\"customer_name\":\"test user\",\"total\":\"155.06\",\"status\":\"pending\"}', NULL, '2026-01-04 12:46:43', '2026-01-04 12:46:43'),
('09d33016-e00e-447e-bd89-7843657c623e', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":66,\"order_uuid\":\"66cab802-4a29-4735-8b9f-75172e6438af\",\"customer_name\":\"Ahmed dd\",\"total\":\"310.06\",\"status\":\"pending\"}', NULL, '2026-02-02 11:26:18', '2026-02-02 11:26:18'),
('0bcabc92-11e5-4207-9456-777342679d4a', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":131,\"order_uuid\":\"81374bdd-df79-4bd5-ab49-bdf280f0c684\",\"customer_name\":\"Ahmed dd\",\"total\":\"210.06\",\"status\":\"pending\"}', NULL, '2026-03-05 15:16:29', '2026-03-05 15:16:29'),
('12c22f75-4e94-42a5-b66c-19bb3cfdfa6f', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":116,\"order_uuid\":\"df3774b1-9a5e-4b6d-a913-2bd25b1e847e\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 14:01:12', '2026-02-26 14:01:12'),
('17d954fb-5147-4c4f-a0ac-ffa1551df02a', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":81,\"order_uuid\":\"4ecf1151-188a-46c3-8c25-820f9aadce12\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-06 09:50:31', '2026-02-06 09:50:31'),
('1aa0f9a9-2045-4f1d-9f73-e3381454779d', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":137,\"order_uuid\":\"d947c4db-7589-476b-80a0-85973000f611\",\"customer_name\":\"Ahmed dd\",\"total\":\"210.06\",\"status\":\"pending\"}', NULL, '2026-03-05 16:33:27', '2026-03-05 16:33:27'),
('1b3a7fb9-97f6-4400-a928-98f5ee7ec183', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":111,\"order_uuid\":\"04fa8ec5-2fee-4294-9ad8-0c659de28b7b\",\"customer_name\":\"Ahmed dd\",\"total\":\"270.06\",\"status\":\"pending\"}', NULL, '2026-02-26 09:51:49', '2026-02-26 09:51:49'),
('1e930228-c4b4-4562-8487-0b74ef9883e9', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":5,\"order_uuid\":\"c9e19010-bbf8-410b-99df-44ec408e5721\",\"customer_name\":\"test user\",\"total\":\"690.06\",\"status\":\"pending\"}', NULL, '2025-12-29 06:32:48', '2025-12-29 06:32:48'),
('1f31f238-1aa4-4697-b3f7-a2d2cbdd4632', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":68,\"order_uuid\":\"811c278e-09cd-49cf-bfe7-5ebd956c51be\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-02 11:27:01', '2026-02-02 11:27:01'),
('208c85bd-8e9c-41bd-a4ee-08a827fd961d', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":110,\"order_uuid\":\"07ba231c-5f11-4ba4-996d-15e03b1b4db9\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 02:34:22', '2026-02-26 02:34:22'),
('2191cb5e-67dc-4c48-b8c3-8fdc617f9c78', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":124,\"order_uuid\":\"767a071f-5ba9-4a41-9b3d-74672c279931\",\"customer_name\":\"Ahmed dd\",\"total\":\"90.06\",\"status\":\"pending\"}', NULL, '2026-03-02 22:25:39', '2026-03-02 22:25:39'),
('2eb497ff-ee93-42bf-a2f7-110a19003c41', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":120,\"order_uuid\":\"ac4a697e-ea09-495b-8a74-849ac7497e5a\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-27 12:41:33', '2026-02-27 12:41:33'),
('389896c8-3685-4855-a351-13fadccfe12c', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":67,\"order_uuid\":\"f8c4b114-14d3-4be9-b3c0-04dd48c20686\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-02 11:26:27', '2026-02-02 11:26:27'),
('39f3c62d-f09c-4d16-871c-fb5f2e7b59c9', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":129,\"order_uuid\":\"6a9efd26-c998-4e2d-bf35-977c9191e07b\",\"customer_name\":\"Ahmed dd\",\"total\":\"310.06\",\"status\":\"pending\"}', NULL, '2026-03-04 21:10:52', '2026-03-04 21:10:52'),
('3ddb4c06-2101-470a-8b60-2ff8f87975fd', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":128,\"order_uuid\":\"13925c49-d744-4cdb-b1dc-00edacacf7bb\",\"customer_name\":\"Ahmed dd\",\"total\":\"90.06\",\"status\":\"pending\"}', NULL, '2026-03-04 19:11:27', '2026-03-04 19:11:27'),
('5035f0a8-f461-46b2-8324-db89615c18ef', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":69,\"order_uuid\":\"0d50900f-660a-4563-92f8-4b6abeeb212d\",\"customer_name\":\"Ahmed dd\",\"total\":\"470.06\",\"status\":\"pending\"}', NULL, '2026-02-03 13:38:55', '2026-02-03 13:38:55'),
('516ffacf-a538-41f5-b759-3509e3925903', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":114,\"order_uuid\":\"14582ab5-bb0e-47ab-ab8e-1d4cac83da32\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 14:00:55', '2026-02-26 14:00:55'),
('518eb68e-83bc-42a4-8d44-daf3397d2ab4', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":103,\"order_uuid\":\"29988b15-3ed6-42ac-aa8b-7e8270e4eeed\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:12:51', '2026-02-23 15:12:51'),
('5847aacd-2a4b-4daf-bae4-7e05ed6f4ff5', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":102,\"order_uuid\":\"8b3933b5-d2f2-4f28-89a0-c44ec6059abe\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:12:40', '2026-02-23 15:12:40'),
('59556db6-be8c-418d-99ed-4fe95b6a4c1f', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":112,\"order_uuid\":\"e732e64b-d2aa-4f61-b2e4-dcd75da1c5cb\",\"customer_name\":\"Ahmed dd\",\"total\":\"150.06\",\"status\":\"pending\"}', NULL, '2026-02-26 10:06:13', '2026-02-26 10:06:13'),
('634512d1-2ee6-4a3d-97d1-d5b4b2df9293', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":82,\"order_uuid\":\"c55c4e3d-b48a-438a-8396-8953f9bb4822\",\"customer_name\":\"Ahmed dd\",\"total\":\"110.06\",\"status\":\"pending\"}', NULL, '2026-02-06 20:42:27', '2026-02-06 20:42:27'),
('6a6759c5-93d5-49a8-be0e-1ae2fc1a9cb8', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":132,\"order_uuid\":\"dcb77960-431e-46d1-acaa-14cafb36993c\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-03-05 15:16:35', '2026-03-05 15:16:35'),
('6bf8dc4f-3df7-414f-85d5-6d20ef35aede', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":75,\"order_uuid\":\"d349a7dd-53a6-4895-9fe4-47ca8f4325a7\",\"customer_name\":\"Ahmed dd\",\"total\":\"90.06\",\"status\":\"pending\"}', NULL, '2026-02-04 12:56:15', '2026-02-04 12:56:15'),
('71d1796f-552c-40d0-82b6-d357675c05f7', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":65,\"order_uuid\":\"422cfe2c-8d8a-4743-b408-c21be41c4fff\",\"customer_name\":\"Ahmed dd\",\"total\":\"210.06\",\"status\":\"pending\"}', NULL, '2026-02-02 11:25:27', '2026-02-02 11:25:27'),
('7db693bd-d189-447e-bb76-7bb1c140945d', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":78,\"order_uuid\":\"b90c8e9e-29ab-45a5-a6a1-2f6580bc839b\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-04 21:08:56', '2026-02-04 21:08:56'),
('813888b3-f2d3-41ed-952e-e0012e366316', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":98,\"order_uuid\":\"2654ba13-3366-4db2-b51b-2928aeb51723\",\"customer_name\":\"Ahmed dd\",\"total\":\"430.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:11:52', '2026-02-23 15:11:52'),
('8310fac5-761c-4edf-b085-2563d4c79297', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":73,\"order_uuid\":\"b1dfe78f-227f-4c42-a461-8f94d2c9f776\",\"customer_name\":\"Ahmed dd\",\"total\":\"47169.15\",\"status\":\"pending\"}', NULL, '2026-02-04 12:42:23', '2026-02-04 12:42:23'),
('87b5723f-0a4a-417d-af98-f375ea7c0037', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":77,\"order_uuid\":\"e46e9c34-1676-4fbb-a391-4b9afbd5436b\",\"customer_name\":\"rtrter\",\"total\":\"47175.68\",\"status\":\"pending\"}', NULL, '2026-02-04 15:49:44', '2026-02-04 15:49:44'),
('8874269c-9dd6-4563-9c0b-03450c8bb45a', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":126,\"order_uuid\":\"315aacea-b649-4d1a-a85e-5f75579035ca\",\"customer_name\":\"Ahmed dd\",\"total\":\"350.06\",\"status\":\"pending\"}', NULL, '2026-03-03 23:09:39', '2026-03-03 23:09:39'),
('917025d7-545e-4963-87ef-06a77b4e333f', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":117,\"order_uuid\":\"e5c858d2-8505-43af-a153-c7f2b3dba545\",\"customer_name\":\"Ahmed dd\",\"total\":\"210.06\",\"status\":\"pending\"}', NULL, '2026-02-26 14:51:49', '2026-02-26 14:51:49'),
('93ffad75-9b07-4924-89ad-d1fd9ab0e7af', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":38,\"order_uuid\":\"971ccf41-bf16-47f9-b9fa-a3e01730974b\",\"customer_name\":\"Ahmed\",\"total\":\"110.06\",\"status\":\"pending\"}', NULL, '2026-01-14 15:19:04', '2026-01-14 15:19:04'),
('9a353ea1-7f4b-4e06-91cc-3bf6d21e2df5', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":125,\"order_uuid\":\"13309404-97c1-4da8-b762-6837195a39df\",\"customer_name\":\"Ahmed dd\",\"total\":\"210.06\",\"status\":\"pending\"}', NULL, '2026-03-03 00:09:05', '2026-03-03 00:09:05'),
('9deb7292-8886-44f8-b5d0-5acffc2a80d4', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":139,\"order_uuid\":\"eed879ee-fc3a-444e-a41d-ffa4919efd31\",\"customer_name\":\"Ahmed dd\",\"total\":\"130.06\",\"status\":\"pending\"}', NULL, '2026-03-23 13:41:16', '2026-03-23 13:41:16'),
('a0902977-217a-4f40-aa91-16f15720a3a0', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":6,\"order_uuid\":\"dac2488b-93b9-4cd4-aab8-a58180732988\",\"customer_name\":\"test user\",\"total\":\"310.06\",\"status\":\"pending\"}', NULL, '2025-12-29 07:07:09', '2025-12-29 07:07:09'),
('a6c548ea-5611-4cd0-9795-1722041fede8', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":104,\"order_uuid\":\"96e102ea-6cce-49b6-a064-667d4bd1cf32\",\"customer_name\":\"ayat\",\"total\":\"135.88\",\"status\":\"pending\"}', NULL, '2026-02-23 18:18:46', '2026-02-23 18:18:46'),
('ad52ae4c-632e-4a24-b509-f41382575872', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":33,\"order_uuid\":\"ece64ef1-db75-49bf-9291-c2d2abc1284f\",\"customer_name\":\"test user\",\"total\":\"285.06\",\"status\":\"pending\"}', NULL, '2026-01-04 12:45:28', '2026-01-04 12:45:28'),
('bc6b067f-ee15-4240-b45c-cefd9f51054a', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":99,\"order_uuid\":\"7b17ff1b-78de-4a5a-9562-270f14ddab19\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:12:11', '2026-02-23 15:12:11'),
('c2f658c8-ea64-408b-9466-8e1c5a058ac3', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":107,\"order_uuid\":\"51832e55-fdb0-4fef-8e69-962ee6816a30\",\"customer_name\":\"Ahmed dd\",\"total\":\"13562.18\",\"status\":\"pending\"}', NULL, '2026-02-25 19:03:29', '2026-02-25 19:03:29'),
('c5059f7e-f91d-4762-882e-209c6d776524', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":138,\"order_uuid\":\"95fd7c22-6f6b-4589-a151-b2ea78bac8b4\",\"customer_name\":\"Ahmed dd\",\"total\":\"290.06\",\"status\":\"pending\"}', NULL, '2026-03-16 17:20:14', '2026-03-16 17:20:14'),
('c5444358-7480-4162-a6fd-d81aefb93edc', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":76,\"order_uuid\":\"1aed3833-bb4c-4689-9a06-ae1689ac0fd1\",\"customer_name\":\"Ahmed dd\",\"total\":\"47175.68\",\"status\":\"pending\"}', NULL, '2026-02-04 13:26:42', '2026-02-04 13:26:42'),
('c589cba3-4c45-4007-80da-37c3f961839f', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":134,\"order_uuid\":\"fe07bb83-0c7c-4d9b-a875-a5d3a666f845\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-03-05 15:47:00', '2026-03-05 15:47:00'),
('c95a5ffe-62a2-4389-a1bb-7bebdd13fdca', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":7,\"order_uuid\":\"b87382b7-c0ac-4734-86f1-e34713d1eebc\",\"customer_name\":\"test user\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2025-12-29 09:27:37', '2025-12-29 09:27:37'),
('caf9a64a-2bf1-4965-ad60-250feffd06dd', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":79,\"order_uuid\":\"401f8a47-566b-4202-b521-dd549a382f34\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-04 21:14:43', '2026-02-04 21:14:43'),
('cc1048c2-d6b4-422f-b77e-d1f8fd47550b', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":119,\"order_uuid\":\"3540886c-4b7f-41ec-8c2f-be6642fb5255\",\"customer_name\":\"Ahmed dd\",\"total\":\"110.06\",\"status\":\"pending\"}', NULL, '2026-02-26 23:06:18', '2026-02-26 23:06:18'),
('cd77fa7d-5013-4d00-a2e5-b1aaddae9ed3', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":37,\"order_uuid\":\"6ad31e2f-da1d-4530-9c2a-a15916d15ef1\",\"customer_name\":\"Ahmed\",\"total\":\"110.06\",\"status\":\"pending\"}', NULL, '2026-01-14 12:26:23', '2026-01-14 12:26:23'),
('d17db01d-557e-4f6c-b89b-925af90acd96', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":122,\"order_uuid\":\"6b7acaae-7a1e-4dd7-866d-71de83c1cd58\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-28 01:03:13', '2026-02-28 01:03:13'),
('d29c2aad-e254-45aa-a4df-74eedc2130ee', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":80,\"order_uuid\":\"f8656314-f11b-4f37-82b1-2968d5b57400\",\"customer_name\":\"Ahmed dd\",\"total\":\"370.06\",\"status\":\"pending\"}', NULL, '2026-02-04 21:56:39', '2026-02-04 21:56:39'),
('d3409f40-8525-4f04-84d2-627a0f577563', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":72,\"order_uuid\":\"ac752d0a-9d37-467b-adc2-c53a28f2ba76\",\"customer_name\":\"Ahmed dd\",\"total\":\"270.06\",\"status\":\"pending\"}', NULL, '2026-02-04 12:38:29', '2026-02-04 12:38:29'),
('d39a079d-312b-4549-a487-7ec64d4ea6f0', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":101,\"order_uuid\":\"b4d961cc-dad0-477f-811f-f17e4414717e\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:12:32', '2026-02-23 15:12:32'),
('d4b931b8-91c6-49f7-b03b-60285386f970', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":106,\"order_uuid\":\"62b5e6ef-3d00-4fbb-9bfb-18e18f6fdcce\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-24 14:27:34', '2026-02-24 14:27:34'),
('d6f29f57-ee2f-4b06-8cf8-f7fcd8917b86', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":136,\"order_uuid\":\"63df54a3-2806-42a5-b7b9-1539da5fb529\",\"customer_name\":\"Ahmed dd\",\"total\":\"370.06\",\"status\":\"pending\"}', NULL, '2026-03-05 16:32:25', '2026-03-05 16:32:25'),
('db05b034-ad68-48c8-9f1c-b994ce5f9ed6', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":130,\"order_uuid\":\"81f60564-5401-4953-b12c-e4cbdbeb42f6\",\"customer_name\":\"Ahmed dd\",\"total\":\"330.06\",\"status\":\"pending\"}', NULL, '2026-03-05 01:04:59', '2026-03-05 01:04:59'),
('db647d1f-08f0-4221-8ec7-1fdb3f274fb8', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":105,\"order_uuid\":\"ed829c5a-047a-44c3-9442-4e4e7ed47856\",\"customer_name\":\"Ahmed dd\",\"total\":\"290.06\",\"status\":\"pending\"}', NULL, '2026-02-24 14:27:28', '2026-02-24 14:27:28'),
('dbcc1dad-7e7e-401d-b308-87f955bc7c2d', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":118,\"order_uuid\":\"99eadee5-c5d6-42e0-955a-c18e0f107c42\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 22:35:42', '2026-02-26 22:35:42'),
('dd8023e3-8408-4e0a-8111-01e15d5c2d8d', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":121,\"order_uuid\":\"2b52a7f9-fbd3-43c3-adf6-407fc0d48b38\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-27 12:45:37', '2026-02-27 12:45:37'),
('de46f993-581b-444b-bc92-e0e8ef6c02de', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":109,\"order_uuid\":\"6fed3b8a-eabf-4b47-a5d8-de586548c98b\",\"customer_name\":\"Ahmed dd\",\"total\":\"270.06\",\"status\":\"pending\"}', NULL, '2026-02-25 20:56:17', '2026-02-25 20:56:17'),
('e1d6e865-e1a0-485f-9938-c9af80d7deeb', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":108,\"order_uuid\":\"9ac6aec9-c45a-4285-90f4-599f441644c1\",\"customer_name\":\"Ahmed dd\",\"total\":\"270.06\",\"status\":\"pending\"}', NULL, '2026-02-25 19:42:42', '2026-02-25 19:42:42'),
('e249cb3d-ce1b-4491-9f0d-e392131194ec', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":36,\"order_uuid\":\"a4053417-504b-44b1-a0b1-f4fa3a47c527\",\"customer_name\":\"test user\",\"total\":\"155.06\",\"status\":\"pending\"}', NULL, '2026-01-04 12:49:44', '2026-01-04 12:49:44'),
('e518bfe5-b0aa-4a3c-85c4-1bc1ca508bb0', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":133,\"order_uuid\":\"c9310680-fca0-4797-aea1-1268e1b62c54\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-03-05 15:17:05', '2026-03-05 15:17:05'),
('e5a26f51-789c-42e9-b061-db4c173c0c5a', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":35,\"order_uuid\":\"a8a38e7b-ebe3-43f8-be10-4e7739eb3f80\",\"customer_name\":\"test user\",\"total\":\"155.06\",\"status\":\"pending\"}', NULL, '2026-01-04 12:48:56', '2026-01-04 12:48:56'),
('e92ed21c-3351-49ee-b15d-cb52c7aed058', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":127,\"order_uuid\":\"d25a69b8-bae9-4df8-8747-99e3a1c82200\",\"customer_name\":\"Ahmed dd\",\"total\":\"110.06\",\"status\":\"pending\"}', NULL, '2026-03-04 18:54:58', '2026-03-04 18:54:58'),
('f0a9f243-6fcf-4f2a-8875-54fb1412fe89', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":115,\"order_uuid\":\"933ac8c8-0349-4f8d-b0e5-14e94fd80ef5\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-26 14:01:03', '2026-02-26 14:01:03'),
('f27b18e8-1d9b-45ec-b803-545370807bf3', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":74,\"order_uuid\":\"86bb9df8-260c-46f4-ac00-e12e2f1a7c27\",\"customer_name\":\"Ahmed dd\",\"total\":\"370.06\",\"status\":\"pending\"}', NULL, '2026-02-04 12:46:57', '2026-02-04 12:46:57'),
('f44172e2-cbfd-48d2-953e-d41cd29f57c7', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":70,\"order_uuid\":\"af4ae1d5-6983-42f7-b7fd-539623bf8d67\",\"customer_name\":\"Ahmed dd\",\"total\":\"170.06\",\"status\":\"pending\"}', NULL, '2026-02-03 13:40:29', '2026-02-03 13:40:29'),
('f5ade596-4915-4376-9de2-f6a062f20d6b', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":2,\"order_uuid\":\"25a2d184-f204-4c5e-a35c-89d60b2acc16\",\"customer_name\":\"test user\",\"total\":\"205.00\",\"status\":\"pending\"}', NULL, '2025-12-09 07:42:07', '2025-12-09 07:42:07'),
('f6a2dc5f-af09-4e39-a178-f3fb99f51f02', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":3,\"order_uuid\":\"9f54f917-f7b2-4c1d-b6b0-a0ec1c73d9a1\",\"customer_name\":\"test user\",\"total\":\"250.00\",\"status\":\"pending\"}', NULL, '2025-12-09 07:45:27', '2025-12-09 07:45:27'),
('fe499a76-2202-427c-8088-7be057a8ad87', 'App\\Notifications\\NewOrderNotification', 'App\\Models\\User', 1, '{\"order_id\":100,\"order_uuid\":\"2b410e06-d465-4638-aa69-9b618c1ea483\",\"customer_name\":\"Ahmed dd\",\"total\":\"190.06\",\"status\":\"pending\"}', NULL, '2026-02-23 15:12:23', '2026-02-23 15:12:23');

-- --------------------------------------------------------

--
-- Table structure for table `offers`
--

CREATE TABLE `offers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `image` varchar(255) DEFAULT NULL,
  `type` enum('product','category','cart','shipping') NOT NULL,
  `condition` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`condition`)),
  `discount_type` enum('fixed','percent','free_shipping','bogo') NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `start_date` timestamp NULL DEFAULT NULL,
  `end_date` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` enum('pending','processing','shipped','out_for_delivery','completed','cancelled') NOT NULL DEFAULT 'pending',
  `total` decimal(10,2) NOT NULL,
  `shipping_cost` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_id` bigint(20) UNSIGNED DEFAULT NULL,
  `offer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `offer_discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `coupon_discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `points_discount_value` decimal(10,2) NOT NULL DEFAULT 0.00,
  `final_total` decimal(10,2) NOT NULL,
  `payment_status` enum('pending','paid','failed','refunded') NOT NULL DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `shipping_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `billing_address_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_id` bigint(20) UNSIGNED DEFAULT NULL,
  `delivery_assigned_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `uuid`, `user_id`, `status`, `total`, `shipping_cost`, `coupon_id`, `offer_id`, `offer_discount_value`, `coupon_discount_value`, `points_discount_value`, `final_total`, `payment_status`, `payment_method`, `note`, `shipping_address_id`, `billing_address_id`, `branch_id`, `delivery_id`, `delivery_assigned_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '25a2d184-f204-4c5e-a35c-89d60b2acc16', 5, 'completed', 195.00, 10.00, NULL, NULL, 0.00, 0.00, 0.00, 205.00, 'paid', 'cash', NULL, 1, 1, 2, NULL, NULL, '2025-12-09 07:42:03', '2025-12-11 13:29:57', NULL),
(3, '9f54f917-f7b2-4c1d-b6b0-a0ec1c73d9a1', 5, 'shipped', 240.00, 10.00, NULL, NULL, 0.00, 0.00, 0.00, 250.00, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2025-12-09 07:45:25', '2025-12-10 13:21:06', NULL),
(5, 'c9e19010-bbf8-410b-99df-44ec408e5721', 5, 'completed', 620.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 690.06, 'pending', 'cash', 'test notes', 1, 1, 1, NULL, '2025-12-29 06:40:55', '2025-12-29 06:32:45', '2025-12-29 08:01:36', NULL),
(6, 'dac2488b-93b9-4cd4-aab8-a58180732988', 5, 'pending', 240.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 310.06, 'pending', 'cash', NULL, 1, 1, NULL, NULL, NULL, '2025-12-29 07:07:07', '2025-12-29 07:16:50', NULL),
(7, 'b87382b7-c0ac-4734-86f1-e34713d1eebc', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2025-12-30 08:26:40', '2025-12-29 09:27:35', '2025-12-30 10:34:14', NULL),
(33, 'ece64ef1-db75-49bf-9291-c2d2abc1284f', 5, 'out_for_delivery', 215.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 285.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2026-01-13 14:50:04', '2026-01-04 12:45:27', '2026-01-13 15:00:49', NULL),
(34, '0650de62-92dc-4fb4-a2ae-fd0391378563', 5, 'out_for_delivery', 85.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 155.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2026-01-13 14:44:11', '2026-01-04 12:46:42', '2026-01-13 14:48:39', NULL),
(35, 'a8a38e7b-ebe3-43f8-be10-4e7739eb3f80', 5, 'out_for_delivery', 85.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 155.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2026-01-13 14:19:09', '2026-01-04 12:48:55', '2026-01-13 14:42:50', NULL),
(36, 'a4053417-504b-44b1-a0b1-f4fa3a47c527', 5, 'out_for_delivery', 85.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 155.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2026-01-13 11:27:56', '2026-01-04 12:49:44', '2026-01-13 13:05:39', NULL),
(37, '6ad31e2f-da1d-4530-9c2a-a15916d15ef1', 5, 'shipped', 40.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 110.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-01-14 12:26:22', '2026-01-29 11:00:27', NULL),
(38, '971ccf41-bf16-47f9-b9fa-a3e01730974b', 5, 'shipped', 40.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 110.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-01-14 15:19:02', '2026-01-29 12:45:31', NULL),
(65, '422cfe2c-8d8a-4743-b408-c21be41c4fff', 5, 'completed', 140.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 210.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-02 15:06:37', '2026-02-02 11:25:26', '2026-02-18 12:21:59', NULL),
(66, '66cab802-4a29-4735-8b9f-75172e6438af', 5, 'cancelled', 240.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 310.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-02-02 11:26:17', '2026-02-02 12:41:11', NULL),
(67, 'f8c4b114-14d3-4be9-b3c0-04dd48c20686', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 13:53:12', '2026-02-02 11:26:26', '2026-03-04 19:35:58', NULL),
(68, '811c278e-09cd-49cf-bfe7-5ebd956c51be', 5, 'shipped', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, '2026-02-02 14:11:25', '2026-02-02 11:27:00', '2026-02-02 14:11:26', NULL),
(69, '0d50900f-660a-4563-92f8-4b6abeeb212d', 5, 'completed', 400.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 470.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-03 14:42:15', '2026-02-03 13:38:54', '2026-02-18 12:23:02', NULL),
(70, 'af4ae1d5-6983-42f7-b7fd-539623bf8d67', 5, 'pending', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, NULL, NULL, NULL, '2026-02-03 13:40:28', '2026-02-03 13:55:28', NULL),
(72, 'ac752d0a-9d37-467b-adc2-c53a28f2ba76', 5, 'completed', 200.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 270.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-18 13:56:01', '2026-02-04 12:38:28', '2026-02-24 14:08:12', NULL),
(73, 'b1dfe78f-227f-4c42-a461-8f94d2c9f776', 5, 'pending', 100.00, 47075.68, NULL, NULL, 0.00, 0.00, 6.53, 47169.15, 'pending', 'cash', NULL, 3, 3, NULL, NULL, NULL, '2026-02-04 12:42:21', '2026-02-18 11:20:53', NULL),
(74, '86bb9df8-260c-46f4-ac00-e12e2f1a7c27', 5, 'shipped', 300.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 370.06, 'pending', 'cash', NULL, 1, 1, 1, 7, '2026-02-17 21:00:32', '2026-02-04 12:46:57', '2026-02-17 21:00:32', NULL),
(75, 'd349a7dd-53a6-4895-9fe4-47ca8f4325a7', 5, 'completed', 20.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 90.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-14 14:07:40', '2026-02-04 12:56:14', '2026-02-18 12:24:48', NULL),
(76, '1aed3833-bb4c-4689-9a06-ae1689ac0fd1', 5, 'shipped', 100.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47175.68, 'pending', 'cash', NULL, 3, 3, 1, 7, '2026-02-18 11:22:49', '2026-02-04 13:26:41', '2026-02-18 11:22:49', NULL),
(77, 'e46e9c34-1676-4fbb-a391-4b9afbd5436b', 19, 'completed', 100.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47175.68, 'pending', 'cash', NULL, 4, 4, 1, 5, '2026-02-13 07:27:06', '2026-02-04 15:49:43', '2026-02-18 12:25:17', NULL),
(78, 'b90c8e9e-29ab-45a5-a6a1-2f6580bc839b', 5, 'completed', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-13 07:33:56', '2026-02-04 21:08:55', '2026-02-24 14:26:32', NULL),
(79, '401f8a47-566b-4202-b521-dd549a382f34', 5, 'pending', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, NULL, NULL, NULL, '2026-02-04 21:14:42', '2026-02-11 07:45:18', NULL),
(80, 'f8656314-f11b-4f37-82b1-2968d5b57400', 5, 'completed', 300.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 370.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-05 13:04:48', '2026-02-04 21:56:38', '2026-02-26 02:41:56', NULL),
(81, '4ecf1151-188a-46c3-8c25-820f9aadce12', 5, 'completed', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-11 07:34:08', '2026-02-06 09:50:30', '2026-02-26 02:42:12', NULL),
(82, 'c55c4e3d-b48a-438a-8396-8953f9bb4822', 5, 'completed', 40.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 110.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-06 20:44:27', '2026-02-06 20:42:25', '2026-02-26 02:42:28', NULL),
(98, '2654ba13-3366-4db2-b51b-2928aeb51723', 5, 'completed', 360.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 430.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 13:49:21', '2026-02-23 15:11:51', '2026-03-04 19:36:06', NULL),
(99, '7b17ff1b-78de-4a5a-9562-270f14ddab19', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 13:59:44', '2026-02-23 15:12:10', '2026-03-04 19:36:13', NULL),
(100, '2b410e06-d465-4638-aa69-9b618c1ea483', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-24 13:30:43', '2026-02-23 15:12:22', '2026-02-24 13:41:52', NULL),
(101, 'b4d961cc-dad0-477f-811f-f17e4414717e', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-24 13:30:20', '2026-02-23 15:12:31', '2026-02-26 02:42:36', NULL),
(102, '8b3933b5-d2f2-4f28-89a0-c44ec6059abe', 5, 'cancelled', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-02-23 15:12:40', '2026-02-24 13:09:25', NULL),
(103, '29988b15-3ed6-42ac-aa8b-7e8270e4eeed', 5, 'shipped', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 7, '2026-02-24 12:49:28', '2026-02-23 15:12:50', '2026-02-24 12:49:28', NULL),
(104, '96e102ea-6cce-49b6-a064-667d4bd1cf32', 20, 'pending', 20.00, 115.88, NULL, NULL, 0.00, 0.00, 0.00, 135.88, 'pending', 'cash', NULL, 6, 6, NULL, NULL, NULL, '2026-02-23 18:18:45', '2026-02-24 12:43:49', NULL),
(105, 'ed829c5a-047a-44c3-9442-4e4e7ed47856', 5, 'completed', 220.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 290.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-24 14:28:43', '2026-02-24 14:27:27', '2026-02-28 01:06:18', NULL),
(106, '62b5e6ef-3d00-4fbb-9bfb-18e18f6fdcce', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-24 14:28:25', '2026-02-24 14:27:33', '2026-02-28 01:06:11', NULL),
(107, '51832e55-fdb0-4fef-8e69-962ee6816a30', 5, 'pending', 60.00, 13502.18, NULL, NULL, 0.00, 0.00, 0.00, 13562.18, 'pending', 'cash', NULL, 7, 7, 2, NULL, NULL, '2026-02-25 19:03:28', '2026-02-25 19:03:28', NULL),
(108, '9ac6aec9-c45a-4285-90f4-599f441644c1', 5, 'completed', 200.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 270.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-25 19:44:29', '2026-02-25 19:42:41', '2026-03-04 19:36:23', NULL),
(109, '6fed3b8a-eabf-4b47-a5d8-de586548c98b', 5, 'completed', 200.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 270.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-25 20:58:09', '2026-02-25 20:56:16', '2026-03-04 19:36:30', NULL),
(110, '07ba231c-5f11-4ba4-996d-15e03b1b4db9', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 02:36:00', '2026-02-26 02:34:21', '2026-03-04 19:36:39', NULL),
(111, '04fa8ec5-2fee-4294-9ad8-0c659de28b7b', 5, 'shipped', 200.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 270.06, 'pending', 'cash', NULL, 1, 1, 1, 7, '2026-02-26 09:53:14', '2026-02-26 09:51:48', '2026-02-26 09:53:14', NULL),
(112, 'e732e64b-d2aa-4f61-b2e4-dcd75da1c5cb', 5, 'completed', 80.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 150.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 10:07:36', '2026-02-26 10:06:12', '2026-03-04 19:36:46', NULL),
(113, 'f5e73b82-0711-4bf4-ad40-3de2171e64c1', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 13:55:57', '2026-02-26 13:01:37', '2026-03-04 19:36:53', NULL),
(114, '14582ab5-bb0e-47ab-ab8e-1d4cac83da32', 5, 'cancelled', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-02-26 14:00:55', '2026-03-04 19:19:54', NULL),
(115, '933ac8c8-0349-4f8d-b0e5-14e94fd80ef5', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 14:11:26', '2026-02-26 14:01:02', '2026-03-04 19:36:59', NULL),
(116, 'df3774b1-9a5e-4b6d-a913-2bd25b1e847e', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 14:06:48', '2026-02-26 14:01:11', '2026-02-28 01:06:05', NULL),
(117, 'e5c858d2-8505-43af-a153-c7f2b3dba545', 5, 'completed', 140.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 210.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-04 19:20:46', '2026-02-26 14:51:48', '2026-03-04 19:37:06', NULL),
(118, '99eadee5-c5d6-42e0-955a-c18e0f107c42', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-26 22:37:01', '2026-02-26 22:35:41', '2026-02-26 22:38:13', NULL),
(119, '3540886c-4b7f-41ec-8c2f-be6642fb5255', 5, 'completed', 40.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 110.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-27 12:05:56', '2026-02-26 23:06:17', '2026-02-27 12:08:24', NULL),
(120, 'ac4a697e-ea09-495b-8a74-849ac7497e5a', 5, 'cancelled', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-02-27 12:41:32', '2026-02-27 12:43:54', NULL),
(121, '2b52a7f9-fbd3-43c3-adf6-407fc0d48b38', 5, 'pending', 100.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 170.06, 'pending', 'cash', NULL, 1, 1, NULL, NULL, NULL, '2026-02-27 12:45:36', '2026-02-27 12:46:39', NULL),
(122, '6b7acaae-7a1e-4dd7-866d-71de83c1cd58', 5, 'completed', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-02-28 01:04:38', '2026-02-28 01:03:12', '2026-02-28 01:10:16', NULL),
(123, 'f59ed107-5c12-480a-9b03-9cbe3602def9', 5, 'cancelled', 240.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 310.06, 'pending', 'cash', 'بنبتتبنقزقننق', 1, 1, 1, NULL, NULL, '2026-03-02 22:16:08', '2026-03-04 19:20:06', NULL),
(124, '767a071f-5ba9-4a41-9b3d-74672c279931', 5, 'completed', 20.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 90.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-04 01:09:49', '2026-03-02 22:25:38', '2026-03-04 19:37:16', NULL),
(125, '13309404-97c1-4da8-b762-6837195a39df', 5, 'completed', 140.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 210.06, 'pending', 'cash', 'test temp1', 1, 1, 1, 5, '2026-03-03 00:10:37', '2026-03-03 00:09:04', '2026-03-03 00:12:10', NULL),
(126, '315aacea-b649-4d1a-a85e-5f75579035ca', 5, 'completed', 280.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 350.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-03 23:10:52', '2026-03-03 23:09:38', '2026-03-03 23:11:58', NULL),
(127, 'd25a69b8-bae9-4df8-8747-99e3a1c82200', 5, 'completed', 40.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 110.06, 'pending', 'cash', 'test1000', 1, 1, 1, 5, '2026-03-04 19:27:54', '2026-03-04 18:54:56', '2026-03-04 19:29:55', NULL),
(128, '13925c49-d744-4cdb-b1dc-00edacacf7bb', 5, 'cancelled', 20.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 90.06, 'pending', 'cash', NULL, 1, 1, 1, NULL, NULL, '2026-03-04 19:11:26', '2026-03-04 19:18:27', NULL),
(129, '6a9efd26-c998-4e2d-bf35-977c9191e07b', 5, 'completed', 240.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 310.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-04 21:21:16', '2026-03-04 21:10:51', '2026-03-04 21:23:40', NULL),
(130, '81f60564-5401-4953-b12c-e4cbdbeb42f6', 5, 'shipped', 260.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 330.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-05 01:05:58', '2026-03-05 01:04:58', '2026-03-05 01:05:59', NULL),
(131, '81374bdd-df79-4bd5-ab49-bdf280f0c684', 5, 'shipped', 140.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 210.06, 'pending', 'cash', 'dddsdds', 1, 1, 1, 5, '2026-03-16 13:54:23', '2026-03-05 15:16:27', '2026-03-16 13:54:25', NULL),
(132, 'dcb77960-431e-46d1-acaa-14cafb36993c', 5, 'shipped', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', 'dddsdds', 1, 1, 1, 5, '2026-03-05 15:45:50', '2026-03-05 15:16:34', '2026-03-05 15:45:50', NULL),
(133, 'c9310680-fca0-4797-aea1-1268e1b62c54', 5, 'shipped', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', 'dddsdds', 1, 1, 1, 5, '2026-03-05 15:24:18', '2026-03-05 15:17:05', '2026-03-05 15:24:19', NULL),
(134, 'fe07bb83-0c7c-4d9b-a875-a5d3a666f845', 5, 'shipped', 120.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 190.06, 'pending', 'cash', 'dddsdds', 1, 1, 1, 5, '2026-03-14 18:07:28', '2026-03-05 15:46:41', '2026-03-14 18:07:29', NULL),
(135, '49b69c00-27ef-4603-90b1-98dfd7506d49', 5, 'completed', 160.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 230.06, 'pending', 'cash', 'test-younis', 1, 1, 1, 5, '2026-03-05 16:07:34', '2026-03-05 16:06:19', '2026-03-16 17:24:27', NULL),
(136, '63df54a3-2806-42a5-b7b9-1539da5fb529', 5, 'shipped', 300.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 370.06, 'pending', 'cash', 'younis-test2', 1, 1, 1, 5, '2026-03-05 16:54:28', '2026-03-05 16:31:57', '2026-03-05 16:54:29', NULL),
(137, 'd947c4db-7589-476b-80a0-85973000f611', 5, 'shipped', 140.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 210.06, 'pending', 'cash', 'test_younis', 1, 1, 1, 5, '2026-03-05 16:34:16', '2026-03-05 16:33:26', '2026-03-05 16:34:17', NULL),
(138, '95fd7c22-6f6b-4589-a151-b2ea78bac8b4', 5, 'completed', 220.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 290.06, 'pending', 'cash', '123456789', 1, 1, 1, 5, '2026-03-16 17:21:56', '2026-03-16 17:20:12', '2026-04-08 13:22:09', NULL),
(139, 'eed879ee-fc3a-444e-a41d-ffa4919efd31', 5, 'shipped', 60.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 130.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-03-23 13:42:44', '2026-03-23 13:41:15', '2026-03-23 13:42:46', NULL),
(156, '5525d028-a7ec-46c3-8552-fd028fbdd94f', 5, 'pending', 20.00, 67.86, NULL, NULL, 0.00, 0.00, 0.00, 87.86, 'pending', 'cash', 'test zone', 8, 8, 2, NULL, NULL, '2026-04-14 12:45:57', '2026-04-14 12:45:57', NULL),
(157, 'c6d1621a-1a6d-4876-978a-214de4b14b31', 5, 'shipped', 20.00, 70.06, NULL, NULL, 0.00, 0.00, 0.00, 90.06, 'pending', 'cash', NULL, 1, 1, 1, 5, '2026-04-15 07:51:15', '2026-04-15 07:50:00', '2026-04-15 07:51:15', NULL),
(162, 'a847ea50-2980-433e-8500-84711d14f9c0', 31, 'pending', 200.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47275.68, 'pending', 'cash', NULL, 9, 9, 1, NULL, NULL, '2026-04-18 11:59:57', '2026-04-18 11:59:57', NULL),
(163, 'e3c577a5-e301-4a2b-8285-08e5af572e4d', 31, 'pending', 20.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47095.68, 'pending', 'cash', NULL, 9, 9, 1, NULL, NULL, '2026-04-18 13:00:32', '2026-04-18 13:00:32', NULL),
(164, '95b389c8-28f2-4b23-ae64-12aae3072420', 32, 'pending', 140.00, 43.87, NULL, NULL, 0.00, 0.00, 0.00, 183.87, 'pending', 'cash', 'all test', 10, 10, 2, NULL, NULL, '2026-04-18 16:08:04', '2026-04-18 16:08:04', NULL),
(165, '7029462f-e7f3-4a9a-9f0a-5d5935efbdcb', 31, 'shipped', 420.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47495.68, 'pending', 'cash', 'test all', 9, 9, 1, 5, '2026-04-18 16:15:55', '2026-04-18 16:14:36', '2026-04-18 16:15:56', NULL),
(166, '9d8b233a-9b5c-494c-a472-490a059f8163', 31, 'pending', 60.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47135.68, 'pending', 'cash', 'طماطه خيار', 9, 9, 1, NULL, NULL, '2026-04-19 15:47:22', '2026-04-19 15:47:22', NULL),
(167, '702fde1e-2f87-4e3b-babd-a174136718a8', 31, 'shipped', 260.00, 47075.68, NULL, NULL, 0.00, 0.00, 0.00, 47335.68, 'pending', 'cash', 'طماطة بطاطة خيار', 9, 9, 1, 5, '2026-04-19 15:58:53', '2026-04-19 15:55:22', '2026-04-19 15:58:54', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `order_comments`
--

CREATE TABLE `order_comments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `comment` text NOT NULL,
  `notify_customer` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_comments`
--

INSERT INTO `order_comments` (`id`, `order_id`, `user_id`, `comment`, `notify_customer`, `created_at`, `updated_at`) VALUES
(1, 3, 2, 'Test Comment', 0, '2025-12-09 11:03:40', '2025-12-09 11:03:40'),
(2, 138, 1, 'VIP', 0, '2026-04-08 13:21:05', '2026-04-08 13:21:05'),
(3, 139, 1, 'asdasdasdasda', 0, '2026-04-09 13:21:44', '2026-04-09 13:21:44'),
(4, 139, 1, 'dasdasdsadasdadsadas', 1, '2026-04-09 13:21:55', '2026-04-09 13:21:55');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(11) NOT NULL,
  `free_quantity` int(11) NOT NULL DEFAULT 0,
  `price` decimal(10,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `variant_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `quantity`, `free_quantity`, `price`, `created_at`, `updated_at`, `variant_id`) VALUES
(3, 2, 4, 1, 0, 75.00, '2025-12-09 07:42:03', '2025-12-09 07:42:03', 3),
(4, 2, 4, 1, 0, 120.00, '2025-12-09 07:42:03', '2025-12-09 07:42:03', 4),
(5, 3, 3, 1, 0, 120.00, '2025-12-09 07:45:25', '2025-12-09 07:45:25', NULL),
(6, 3, 4, 1, 0, 120.00, '2025-12-09 07:45:25', '2025-12-09 07:45:25', 4),
(7, 5, 3, 5, 0, 100.00, '2025-12-29 06:32:45', '2025-12-29 06:32:45', NULL),
(8, 5, 4, 1, 0, 120.00, '2025-12-29 06:32:45', '2025-12-29 06:32:45', 4),
(9, 6, 4, 2, 0, 120.00, '2025-12-29 07:07:07', '2025-12-29 07:07:07', 4),
(10, 7, 4, 1, 0, 120.00, '2025-12-29 09:27:35', '2025-12-29 09:27:35', 4),
(76, 33, 1, 1, 0, 20.00, '2026-01-04 12:45:27', '2026-01-04 12:45:27', NULL),
(77, 33, 4, 1, 0, 75.00, '2026-01-04 12:45:27', '2026-01-04 12:45:27', 3),
(78, 33, 4, 1, 0, 120.00, '2026-01-04 12:45:27', '2026-01-04 12:45:27', 4),
(79, 34, 2, 1, 0, 85.00, '2026-01-04 12:46:42', '2026-01-04 12:46:42', 1),
(80, 35, 2, 1, 0, 85.00, '2026-01-04 12:48:55', '2026-01-04 12:48:55', 1),
(81, 36, 2, 1, 0, 85.00, '2026-01-04 12:49:44', '2026-01-04 12:49:44', 1),
(82, 37, 1, 2, 0, 20.00, '2026-01-14 12:26:22', '2026-01-14 12:26:22', NULL),
(83, 38, 1, 2, 0, 20.00, '2026-01-14 15:19:02', '2026-01-14 15:19:02', NULL),
(136, 65, 1, 2, 0, 20.00, '2026-02-02 11:25:26', '2026-02-02 11:25:26', NULL),
(137, 65, 3, 1, 0, 100.00, '2026-02-02 11:25:26', '2026-02-02 11:25:26', NULL),
(138, 66, 4, 2, 0, 120.00, '2026-02-02 11:26:17', '2026-02-02 11:26:17', 4),
(139, 67, 4, 1, 0, 120.00, '2026-02-02 11:26:26', '2026-02-02 11:26:26', 4),
(140, 68, 3, 1, 0, 100.00, '2026-02-02 11:27:00', '2026-02-02 11:27:00', NULL),
(141, 69, 3, 4, 0, 100.00, '2026-02-03 13:38:54', '2026-02-03 13:38:54', NULL),
(142, 70, 3, 1, 0, 100.00, '2026-02-03 13:40:28', '2026-02-03 13:40:28', NULL),
(144, 72, 3, 2, 0, 100.00, '2026-02-04 12:38:28', '2026-02-04 12:38:28', NULL),
(145, 73, 3, 1, 0, 100.00, '2026-02-04 12:42:21', '2026-02-04 12:42:21', NULL),
(146, 74, 3, 3, 0, 100.00, '2026-02-04 12:46:57', '2026-02-04 12:46:57', NULL),
(147, 75, 1, 1, 0, 20.00, '2026-02-04 12:56:14', '2026-02-04 12:56:14', NULL),
(148, 76, 3, 1, 0, 100.00, '2026-02-04 13:26:41', '2026-02-04 13:26:41', NULL),
(149, 77, 3, 1, 0, 100.00, '2026-02-04 15:49:43', '2026-02-04 15:49:43', NULL),
(150, 78, 3, 1, 0, 100.00, '2026-02-04 21:08:55', '2026-02-04 21:08:55', NULL),
(151, 79, 3, 1, 0, 100.00, '2026-02-04 21:14:42', '2026-02-04 21:14:42', NULL),
(152, 80, 3, 3, 0, 100.00, '2026-02-04 21:56:38', '2026-02-04 21:56:38', NULL),
(153, 81, 3, 1, 0, 100.00, '2026-02-06 09:50:30', '2026-02-06 09:50:30', NULL),
(154, 82, 1, 2, 0, 20.00, '2026-02-06 20:42:25', '2026-02-06 20:42:25', NULL),
(174, 98, 4, 3, 0, 120.00, '2026-02-23 15:11:51', '2026-02-23 15:11:51', 4),
(175, 99, 4, 1, 0, 120.00, '2026-02-23 15:12:10', '2026-02-23 15:12:10', 4),
(176, 100, 4, 1, 0, 120.00, '2026-02-23 15:12:22', '2026-02-23 15:12:22', 4),
(177, 101, 4, 1, 0, 120.00, '2026-02-23 15:12:31', '2026-02-23 15:12:31', 4),
(178, 102, 4, 1, 0, 120.00, '2026-02-23 15:12:40', '2026-02-23 15:12:40', 4),
(179, 103, 4, 1, 0, 120.00, '2026-02-23 15:12:50', '2026-02-23 15:12:50', 4),
(180, 104, 1, 1, 0, 20.00, '2026-02-23 18:18:45', '2026-02-23 18:18:45', NULL),
(181, 105, 3, 1, 0, 100.00, '2026-02-24 14:27:27', '2026-02-24 14:27:27', NULL),
(182, 105, 4, 1, 0, 120.00, '2026-02-24 14:27:27', '2026-02-24 14:27:27', 4),
(183, 106, 4, 1, 0, 120.00, '2026-02-24 14:27:33', '2026-02-24 14:27:33', 4),
(184, 107, 1, 3, 0, 20.00, '2026-02-25 19:03:28', '2026-02-25 19:03:28', NULL),
(185, 108, 3, 2, 0, 100.00, '2026-02-25 19:42:41', '2026-02-25 19:42:41', NULL),
(186, 109, 3, 2, 0, 100.00, '2026-02-25 20:56:16', '2026-02-25 20:56:16', NULL),
(187, 110, 1, 1, 0, 20.00, '2026-02-26 02:34:21', '2026-02-26 02:34:21', NULL),
(188, 110, 3, 1, 0, 100.00, '2026-02-26 02:34:21', '2026-02-26 02:34:21', NULL),
(189, 111, 3, 2, 0, 100.00, '2026-02-26 09:51:48', '2026-02-26 09:51:48', NULL),
(190, 112, 1, 4, 0, 20.00, '2026-02-26 10:06:12', '2026-02-26 10:06:12', NULL),
(191, 113, 4, 1, 0, 120.00, '2026-02-26 13:01:37', '2026-02-26 13:01:37', 4),
(192, 114, 4, 1, 0, 120.00, '2026-02-26 14:00:55', '2026-02-26 14:00:55', 4),
(193, 115, 4, 1, 0, 120.00, '2026-02-26 14:01:02', '2026-02-26 14:01:02', 4),
(194, 116, 4, 1, 0, 120.00, '2026-02-26 14:01:11', '2026-02-26 14:01:11', 4),
(195, 117, 1, 2, 0, 20.00, '2026-02-26 14:51:48', '2026-02-26 14:51:48', NULL),
(196, 117, 3, 1, 0, 100.00, '2026-02-26 14:51:48', '2026-02-26 14:51:48', NULL),
(197, 118, 1, 1, 0, 20.00, '2026-02-26 22:35:41', '2026-02-26 22:35:41', NULL),
(198, 118, 3, 1, 0, 100.00, '2026-02-26 22:35:41', '2026-02-26 22:35:41', NULL),
(199, 119, 1, 2, 0, 20.00, '2026-02-26 23:06:17', '2026-02-26 23:06:17', NULL),
(200, 120, 1, 1, 0, 20.00, '2026-02-27 12:41:32', '2026-02-27 12:41:32', NULL),
(201, 120, 3, 1, 0, 100.00, '2026-02-27 12:41:32', '2026-02-27 12:41:32', NULL),
(202, 121, 1, 5, 0, 20.00, '2026-02-27 12:45:36', '2026-02-27 12:45:36', NULL),
(203, 122, 1, 1, 0, 20.00, '2026-02-28 01:03:12', '2026-02-28 01:03:12', NULL),
(204, 122, 3, 1, 0, 100.00, '2026-02-28 01:03:12', '2026-02-28 01:03:12', NULL),
(205, 123, 1, 1, 0, 20.00, '2026-03-02 22:16:08', '2026-03-02 22:16:08', NULL),
(206, 123, 3, 1, 0, 100.00, '2026-03-02 22:16:08', '2026-03-02 22:16:08', NULL),
(207, 123, 4, 1, 0, 120.00, '2026-03-02 22:16:08', '2026-03-02 22:16:08', 4),
(208, 124, 1, 1, 0, 20.00, '2026-03-02 22:25:38', '2026-03-02 22:25:38', NULL),
(209, 125, 1, 2, 0, 20.00, '2026-03-03 00:09:04', '2026-03-03 00:09:04', NULL),
(210, 125, 3, 1, 0, 100.00, '2026-03-03 00:09:04', '2026-03-03 00:09:04', NULL),
(211, 126, 1, 4, 0, 20.00, '2026-03-03 23:09:38', '2026-03-03 23:09:38', NULL),
(212, 126, 3, 2, 0, 100.00, '2026-03-03 23:09:38', '2026-03-03 23:09:38', NULL),
(213, 127, 1, 2, 0, 20.00, '2026-03-04 18:54:56', '2026-03-04 18:54:56', NULL),
(214, 128, 1, 1, 0, 20.00, '2026-03-04 19:11:26', '2026-03-04 19:11:26', NULL),
(215, 129, 1, 2, 0, 20.00, '2026-03-04 21:10:51', '2026-03-04 21:10:51', NULL),
(216, 129, 3, 2, 0, 100.00, '2026-03-04 21:10:51', '2026-03-04 21:10:51', NULL),
(217, 130, 1, 3, 0, 20.00, '2026-03-05 01:04:58', '2026-03-05 01:04:58', NULL),
(218, 130, 3, 2, 0, 100.00, '2026-03-05 01:04:58', '2026-03-05 01:04:58', NULL),
(219, 131, 1, 1, 0, 20.00, '2026-03-05 15:16:27', '2026-03-05 15:16:27', NULL),
(220, 131, 4, 1, 0, 120.00, '2026-03-05 15:16:27', '2026-03-05 15:16:27', 4),
(221, 132, 4, 1, 0, 120.00, '2026-03-05 15:16:34', '2026-03-05 15:16:34', 4),
(222, 133, 4, 1, 0, 120.00, '2026-03-05 15:17:05', '2026-03-05 15:17:05', 4),
(223, 134, 4, 1, 0, 120.00, '2026-03-05 15:46:41', '2026-03-05 15:46:41', 4),
(224, 135, 1, 3, 0, 20.00, '2026-03-05 16:06:19', '2026-03-05 16:06:19', NULL),
(225, 135, 3, 1, 0, 100.00, '2026-03-05 16:06:19', '2026-03-05 16:06:19', NULL),
(226, 136, 3, 3, 0, 100.00, '2026-03-05 16:31:57', '2026-03-05 16:31:57', NULL),
(227, 137, 1, 7, 0, 20.00, '2026-03-05 16:33:26', '2026-03-05 16:33:26', NULL),
(228, 138, 1, 1, 0, 20.00, '2026-03-16 17:20:12', '2026-03-16 17:20:12', NULL),
(229, 138, 3, 2, 0, 100.00, '2026-03-16 17:20:12', '2026-03-16 17:20:12', NULL),
(230, 139, 1, 3, 0, 20.00, '2026-03-23 13:41:15', '2026-03-23 13:41:15', NULL),
(259, 156, 1, 1, 0, 20.00, '2026-04-14 12:45:57', '2026-04-14 12:45:57', NULL),
(260, 157, 1, 1, 0, 20.00, '2026-04-15 07:50:00', '2026-04-15 07:50:00', NULL),
(265, 162, 3, 2, 0, 100.00, '2026-04-18 11:59:57', '2026-04-18 11:59:57', NULL),
(266, 163, 1, 1, 0, 20.00, '2026-04-18 13:00:32', '2026-04-18 13:00:32', NULL),
(267, 164, 1, 2, 0, 20.00, '2026-04-18 16:08:04', '2026-04-18 16:08:04', NULL),
(268, 164, 3, 1, 0, 100.00, '2026-04-18 16:08:04', '2026-04-18 16:08:04', NULL),
(269, 165, 1, 1, 0, 20.00, '2026-04-18 16:14:36', '2026-04-18 16:14:36', NULL),
(270, 165, 3, 4, 0, 100.00, '2026-04-18 16:14:36', '2026-04-18 16:14:36', NULL),
(271, 166, 1, 3, 0, 20.00, '2026-04-19 15:47:22', '2026-04-19 15:47:22', NULL),
(272, 167, 1, 3, 0, 20.00, '2026-04-19 15:55:22', '2026-04-19 15:55:22', NULL),
(273, 167, 3, 2, 0, 100.00, '2026-04-19 15:55:22', '2026-04-19 15:55:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `permissions`
--

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` text NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(1, 'App\\Models\\User', 5, 'auth_token', '6dd852f663b5cc581266fc16f33622e6a326599f43a5104523f8b6f805c9eb08', '[\"*\"]', NULL, NULL, '2025-12-08 12:02:10', '2025-12-08 12:02:10'),
(2, 'App\\Models\\User', 5, 'auth_token', '63d4136af9ae815445cfa92cbb8b802ecb887da723de2fe7a47b16ed98eb528e', '[\"*\"]', '2026-03-02 22:38:29', NULL, '2025-12-08 12:02:27', '2026-03-02 22:38:29'),
(3, 'App\\Models\\User', 5, 'auth_token', '1e5f3d70e5bc0d1817ff331120943db48c983029e9a6e1e9895b2fcf58ae6aca', '[\"*\"]', '2026-04-20 11:27:41', NULL, '2025-12-09 07:33:02', '2026-04-20 11:27:41'),
(4, 'App\\Models\\User', 2, 'auth_token', '44b363730004255d085121cd5fd49863c6f5410cc181b4e70c7057855d983134', '[\"*\"]', '2026-04-15 07:49:19', NULL, '2025-12-10 11:56:22', '2026-04-15 07:49:19'),
(5, 'App\\Models\\User', 5, 'auth_token', 'fbe3df968924fe5a411a9d4ec36280e2da24d9d8d05f01ff7a0dc07725e4e32c', '[\"*\"]', '2025-12-29 06:31:53', NULL, '2025-12-29 06:28:54', '2025-12-29 06:31:53'),
(6, 'App\\Models\\User', 9, 'auth_token', 'd1c01cf02a24eaf98a72844aac927affc865de6f5cc5ce7acd01bec4ed92a980', '[\"*\"]', '2026-02-18 12:21:11', NULL, '2025-12-29 07:32:27', '2026-02-18 12:21:11'),
(7, 'App\\Models\\User', 5, 'auth_token', 'a6d7b48f616587a06529d5172894b703019df6a1213c5bf4c8833aec6df54e52', '[\"*\"]', '2025-12-31 18:56:58', NULL, '2025-12-31 13:24:50', '2025-12-31 18:56:58'),
(8, 'App\\Models\\User', 5, 'auth_token', '740a380e61cbd79f3103bd8f000cfc74c6518563077968428440c67ab3d9f0fa', '[\"*\"]', '2026-01-01 08:25:41', NULL, '2026-01-01 08:25:29', '2026-01-01 08:25:41'),
(9, 'App\\Models\\User', 5, 'auth_token', 'b024aa2168b3efd81d063deabd616d5e4d20ecdd15f0419673bc4229094e01dd', '[\"*\"]', NULL, NULL, '2026-01-01 08:29:37', '2026-01-01 08:29:37'),
(10, 'App\\Models\\User', 5, 'auth_token', '8703994cef02cc5a18bcd0042103bba90e1435660aae6519091e20d8e711f0f4', '[\"*\"]', NULL, NULL, '2026-01-01 08:29:51', '2026-01-01 08:29:51'),
(11, 'App\\Models\\User', 5, 'auth_token', '2e9e5a7187cb599598ec5149570d84b90a0478e09ea0bf8bfab2a9f7553be90d', '[\"*\"]', NULL, NULL, '2026-01-01 08:30:29', '2026-01-01 08:30:29'),
(12, 'App\\Models\\User', 5, 'auth_token', '9455c391c6b5cd17d9ae7e09c7e36f674689cda78bcc92373acb8932a5fad5dc', '[\"*\"]', NULL, NULL, '2026-01-01 09:18:23', '2026-01-01 09:18:23'),
(13, 'App\\Models\\User', 5, 'auth_token', 'c7184b9a5a956bcbb20eaaf96168c3b9cbc5b8fd1fa7e41d4343a6821621b673', '[\"*\"]', '2026-01-01 09:25:34', NULL, '2026-01-01 09:18:25', '2026-01-01 09:25:34'),
(14, 'App\\Models\\User', 5, 'auth_token', '16bddc0cc31a18c2c26bcee5bc511738fd46f28b00311287b5e5f34352ef4522', '[\"*\"]', '2026-01-04 13:08:38', NULL, '2026-01-01 09:55:37', '2026-01-04 13:08:38'),
(15, 'App\\Models\\User', 5, 'auth_token', '835aa841ef10c4db5b34b200208e31dafcc38cb78be4e990a82560110eabd8ae', '[\"*\"]', '2026-01-04 12:51:00', NULL, '2026-01-03 08:53:44', '2026-01-04 12:51:00'),
(16, 'App\\Models\\User', 5, 'auth_token', 'b184cdf8447e47087619c653649b94e87b6bf3500df8cab25157edb44fb2ae61', '[\"*\"]', '2026-01-04 13:13:02', NULL, '2026-01-04 13:12:49', '2026-01-04 13:13:02'),
(17, 'App\\Models\\User', 5, 'auth_token', 'd964bf825563efddef1773457d2181ee359e3ead91c9159e46a3901e582cd805', '[\"*\"]', '2026-01-06 10:44:38', NULL, '2026-01-04 13:17:57', '2026-01-06 10:44:38'),
(18, 'App\\Models\\User', 9, 'auth_token', '5341d9402b58ad896507bf27d25e5dfcfa694bbea634fb7408173e849e902bd6', '[\"*\"]', NULL, NULL, '2026-01-12 10:49:22', '2026-01-12 10:49:22'),
(19, 'App\\Models\\User', 5, 'auth_token', '67ec94846f2a04868961f09df75168b7fe76aee7b841c4a9772f18cc58a44723', '[\"*\"]', NULL, NULL, '2026-01-12 10:55:01', '2026-01-12 10:55:01'),
(20, 'App\\Models\\User', 5, 'auth_token', '59cf6c4ab1cddb56091c79d7514fa79523ff722d4d30d200ee629ae429c82bf1', '[\"*\"]', '2026-01-12 12:14:26', NULL, '2026-01-12 11:40:31', '2026-01-12 12:14:26'),
(21, 'App\\Models\\User', 9, 'auth_token', '1501d08eab584c638af4acbd16a868fd999650648ed73d5617f6680dc219a1de', '[\"*\"]', NULL, NULL, '2026-01-12 12:46:50', '2026-01-12 12:46:50'),
(22, 'App\\Models\\User', 5, 'auth_token', 'e63e4dcc2263977c6de82ca1abea07538069895dc958ad8f31b834658b2628df', '[\"*\"]', '2026-01-15 09:09:44', NULL, '2026-01-12 12:49:50', '2026-01-15 09:09:44'),
(23, 'App\\Models\\User', 9, 'auth_token', 'ffced258ae65c349bdbdfd442b51a6343a61d465a8e03305107fd6f91d87f720', '[\"*\"]', '2026-01-12 12:56:13', NULL, '2026-01-12 12:55:58', '2026-01-12 12:56:13'),
(24, 'App\\Models\\User', 9, 'auth_token', '6c8535ad3f5a277c4b95eb7b3e148a14c89b01eba2f5dda6253afe48202bba4a', '[\"*\"]', '2026-01-12 13:49:50', NULL, '2026-01-12 12:56:26', '2026-01-12 13:49:50'),
(25, 'App\\Models\\User', 9, 'auth_token', 'f62ddfe66cc2bb0311518e7d44f2b03f265f5b9a63bc87ad79ebcb4317458f1e', '[\"*\"]', NULL, NULL, '2026-01-12 13:50:11', '2026-01-12 13:50:11'),
(26, 'App\\Models\\User', 9, 'auth_token', 'ccb225101a6dd48e0c9957a76d067442d9219616d9ed0fc190407a8b28ef1375', '[\"*\"]', '2026-01-12 14:53:06', NULL, '2026-01-12 13:50:56', '2026-01-12 14:53:06'),
(27, 'App\\Models\\User', 9, 'auth_token', '67132b8852e861c6d0406b6dff7cb6fa5c30cb47febf187cfc0e103e0dccc08e', '[\"*\"]', '2026-01-12 15:00:52', NULL, '2026-01-12 14:54:06', '2026-01-12 15:00:52'),
(28, 'App\\Models\\User', 9, 'auth_token', '70f122d7640d4cce43ece8e389abf449a77eb78f94c46e5654619a9e83e86ef8', '[\"*\"]', '2026-01-12 15:36:07', NULL, '2026-01-12 15:01:00', '2026-01-12 15:36:07'),
(30, 'App\\Models\\User', 5, 'auth_token', '1da7abdef5ee9aaba36c0c244b405acdbc87ab4bb627e8f9bfc892c33f8d380f', '[\"*\"]', '2026-01-13 09:15:02', NULL, '2026-01-13 08:37:00', '2026-01-13 09:15:02'),
(31, 'App\\Models\\User', 5, 'auth_token', '364f99294d06172b940bb8c90c004e21704748d00b8a4bf188f93b79f68d1b44', '[\"*\"]', '2026-01-13 11:30:12', NULL, '2026-01-13 08:43:05', '2026-01-13 11:30:12'),
(32, 'App\\Models\\User', 9, 'auth_token', '314592ed2bd503347d6db51cd6144d5f7a711cdd42bf479dcddb999e823d9fb5', '[\"*\"]', '2026-01-13 10:24:57', NULL, '2026-01-13 09:22:18', '2026-01-13 10:24:57'),
(33, 'App\\Models\\User', 9, 'auth_token', '86d98c0c216d9681447f72e652614cec86bbed28773248ad3d1ef2254308bc43', '[\"*\"]', '2026-01-13 11:29:08', NULL, '2026-01-13 11:27:46', '2026-01-13 11:29:08'),
(34, 'App\\Models\\User', 9, 'auth_token', '7d561a9f8dd683fc11b661b4bba9daa0860456fb8fcfce2d9bc119756dda4b54', '[\"*\"]', '2026-01-13 13:05:39', NULL, '2026-01-13 11:38:34', '2026-01-13 13:05:39'),
(35, 'App\\Models\\User', 9, 'auth_token', '0c6919bf124ed140e63a5e2360baa527e274725b5ba0bb0e7242052667596e36', '[\"*\"]', '2026-01-13 12:03:56', NULL, '2026-01-13 12:03:40', '2026-01-13 12:03:56'),
(36, 'App\\Models\\User', 5, 'auth_token', '67e56bb5086ca7f126f1d50b8b9a232781cefec7fa3a3f0dc808eb57abd4d32e', '[\"*\"]', '2026-01-13 13:38:37', NULL, '2026-01-13 13:10:35', '2026-01-13 13:38:37'),
(38, 'App\\Models\\User', 5, 'auth_token', '0e0443302cee28c6f1728d1890a4d23d6947c5cc4cdecdf1744099faf0ac97e0', '[\"*\"]', '2026-01-14 09:50:50', NULL, '2026-01-14 09:50:24', '2026-01-14 09:50:50'),
(39, 'App\\Models\\User', 5, 'auth_token', '86bc7187519a025d3a8f2620b3a4bb432d9b6b7653c161f9e6d91101c478af8e', '[\"*\"]', '2026-01-14 15:04:24', NULL, '2026-01-14 11:43:57', '2026-01-14 15:04:24'),
(40, 'App\\Models\\User', 5, 'auth_token', 'c0967104ce5b5f8efa8576bb8cf76e5f9655ce30afe1895860034b9511847086', '[\"*\"]', NULL, NULL, '2026-01-14 14:39:56', '2026-01-14 14:39:56'),
(42, 'App\\Models\\User', 5, 'auth_token', '217fc1a03b412b272be65054881e256163c9d1d804f603fe6f32ab450473a242', '[\"*\"]', '2026-01-15 10:19:37', NULL, '2026-01-14 15:18:37', '2026-01-15 10:19:37'),
(43, 'App\\Models\\User', 5, 'auth_token', 'b4f8f09ff9d81fbd32fc0ad86b5bd975534bc105293abea4a387f72aada3eec8', '[\"*\"]', '2026-01-15 10:10:03', NULL, '2026-01-15 10:09:50', '2026-01-15 10:10:03'),
(44, 'App\\Models\\User', 9, 'auth_token', 'fba0aa2691cf81864ebf54538a594496537855ca6b4df7b5041979e200d9396d', '[\"*\"]', '2026-01-15 12:09:23', NULL, '2026-01-15 10:19:50', '2026-01-15 12:09:23'),
(45, 'App\\Models\\User', 5, 'auth_token', '515c3985c891254b5b737eadc059f3baa7b40b313a34b4f654b99eb80830286f', '[\"*\"]', NULL, NULL, '2026-01-15 11:44:11', '2026-01-15 11:44:11'),
(46, 'App\\Models\\User', 9, 'auth_token', 'e4ed6a45c9449b71efe8ddcdd4976d1c06a7c129d05f8efa0ba169f6bb73c1db', '[\"*\"]', '2026-01-15 12:43:24', NULL, '2026-01-15 12:41:28', '2026-01-15 12:43:24'),
(47, 'App\\Models\\User', 5, 'auth_token', 'd39d01686364b8cb06df498ecfc003f64ac570b90f7886567512f186a1fca2b4', '[\"*\"]', '2026-01-15 13:15:12', NULL, '2026-01-15 13:07:48', '2026-01-15 13:15:12'),
(49, 'App\\Models\\User', 5, 'auth_token', '326d51e4e019db71d52f0108ebe991b6f6f54447851c5ac8aac6570ae40e1ad9', '[\"*\"]', '2026-01-15 22:24:12', NULL, '2026-01-15 13:22:55', '2026-01-15 22:24:12'),
(51, 'App\\Models\\User', 5, 'auth_token', 'e4c858b5763dfedb9f1f78987d9277faa4a0e8654990c64348626b4a7a182ffa', '[\"*\"]', '2026-01-17 00:42:07', NULL, '2026-01-15 22:28:25', '2026-01-17 00:42:07'),
(53, 'App\\Models\\User', 5, 'auth_token', '7285d9a16e447ab2b1655a12aaff82912239d2a219ff39c6e7592f9bf49f4dc1', '[\"*\"]', '2026-01-20 13:58:08', NULL, '2026-01-17 07:21:48', '2026-01-20 13:58:08'),
(54, 'App\\Models\\User', 5, 'auth_token', '90540334da941b754dadafc265ac44baa702e387af5bdc29533454b5dc201dd7', '[\"*\"]', '2026-01-29 15:10:20', NULL, '2026-01-17 12:14:34', '2026-01-29 15:10:20'),
(55, 'App\\Models\\User', 5, 'auth_token', 'a6cce7f57324701abca9c52c8c7f8a80d9ad636ca9f36574170dc54314a53af0', '[\"*\"]', '2026-01-21 19:05:35', NULL, '2026-01-20 14:27:46', '2026-01-21 19:05:35'),
(56, 'App\\Models\\User', 5, 'auth_token', '25e73585360ab142aa8bee7348fbb630ebed73d2e0ff14b230ef270625e8cba4', '[\"*\"]', '2026-01-20 19:46:11', NULL, '2026-01-20 19:45:47', '2026-01-20 19:46:11'),
(58, 'App\\Models\\User', 5, 'auth_token', '0819392bc77952a3160e42d619b886867b465e132e7b48689a5ccefdc3c734f1', '[\"*\"]', '2026-01-21 14:56:46', NULL, '2026-01-21 14:52:27', '2026-01-21 14:56:46'),
(60, 'App\\Models\\User', 5, 'auth_token', 'da66464285a1defc7ed72a287d42f0b62fd97fe0103460e90115a63fa9c62ab0', '[\"*\"]', '2026-01-29 10:03:11', NULL, '2026-01-22 14:16:48', '2026-01-29 10:03:11'),
(61, 'App\\Models\\User', 9, 'auth_token', 'd1df3900ce039d7381f115de611065d74f59072d3c45f14103d2361998c99092', '[\"*\"]', NULL, NULL, '2026-01-25 09:33:15', '2026-01-25 09:33:15'),
(62, 'App\\Models\\User', 2, 'auth_token', 'df58577fc22e91aad25c87d3f76b3a93d8d90446a7d86e07c2837140c27204cc', '[\"*\"]', NULL, NULL, '2026-01-25 09:57:04', '2026-01-25 09:57:04'),
(64, 'App\\Models\\User', 5, 'auth_token', '015c25ac99e990939e8340d97087ba72f31fda95da0a0704f285e0f7419ffd1b', '[\"*\"]', '2026-02-20 02:28:50', NULL, '2026-01-26 16:41:48', '2026-02-20 02:28:50'),
(65, 'App\\Models\\User', 2, 'auth_token', '4d30ac7989bf740100addda9a23df593bf13c7d2d7f895458de0bc7d30eb080d', '[\"*\"]', NULL, NULL, '2026-01-29 13:11:11', '2026-01-29 13:11:11'),
(66, 'App\\Models\\User', 5, 'auth_token', 'cc209279bbcc01a3bdcd68209fe2567854a9bd855d227470f2b28a33071aab94', '[\"*\"]', NULL, NULL, '2026-01-29 14:06:56', '2026-01-29 14:06:56'),
(67, 'App\\Models\\User', 5, 'auth_token', '04e1358bccd9eeecdc08898fb3b093f0eb534dab007bd27035d9925c9010052d', '[\"*\"]', '2026-01-29 15:24:34', NULL, '2026-01-29 14:07:25', '2026-01-29 15:24:34'),
(68, 'App\\Models\\User', 5, 'auth_token', '6ff01dd13f40d38d715fc06b49c12338e16ccf4654519349e71c37efe77ae4a1', '[\"*\"]', '2026-01-29 14:22:51', NULL, '2026-01-29 14:21:39', '2026-01-29 14:22:51'),
(69, 'App\\Models\\User', 5, 'auth_token', 'f811afb6567cd54ab96713700613f8f4a23335836866b03b346d2fd09b629d82', '[\"*\"]', '2026-01-29 14:26:52', NULL, '2026-01-29 14:24:36', '2026-01-29 14:26:52'),
(71, 'App\\Models\\User', 5, 'auth_token', 'c9e25b082ba844751c500cc13851d54b5e296d33b6388c8cd19eef53d5304e14', '[\"*\"]', '2026-02-01 10:15:41', NULL, '2026-01-29 15:25:49', '2026-02-01 10:15:41'),
(72, 'App\\Models\\User', 2, 'auth_token', '05aa917d6c6e19b1c85aeb13cbae1e74bcdf41777884da6eba0e24a0821cd60d', '[\"*\"]', '2026-01-29 21:48:01', NULL, '2026-01-29 21:48:00', '2026-01-29 21:48:01'),
(74, 'App\\Models\\User', 2, 'auth_token', 'b17b87c132d0ee10497baa5b668550d795235d2b7f9b0c3ef5d13e3101feb2c9', '[\"*\"]', NULL, NULL, '2026-02-01 10:34:40', '2026-02-01 10:34:40'),
(75, 'App\\Models\\User', 2, 'auth_token', '45e4f6a8fb2adbfc1ad7b907146774bfeeb0512564e6be580cbbaa87ca2e506e', '[\"*\"]', NULL, NULL, '2026-02-02 10:39:38', '2026-02-02 10:39:38'),
(76, 'App\\Models\\User', 5, 'auth_token', '44b775657ba71c517647a2c6c3679f4c22276847444ef7dcd7f7854e3c157171', '[\"*\"]', '2026-02-03 09:29:59', NULL, '2026-02-02 13:36:08', '2026-02-03 09:29:59'),
(77, 'App\\Models\\User', 5, 'auth_token', '1f59908a7bb522f05273d3f282866ea3ec0740298436702bb6376a7f4f2555cf', '[\"*\"]', '2026-02-03 10:02:16', NULL, '2026-02-03 09:37:07', '2026-02-03 10:02:16'),
(78, 'App\\Models\\User', 5, 'auth_token', 'e934cb785bcc161e7252edb1e47011184c70c0632f803257bfc95e01a6d9345e', '[\"*\"]', '2026-02-03 10:43:06', NULL, '2026-02-03 10:41:04', '2026-02-03 10:43:06'),
(79, 'App\\Models\\User', 9, 'auth_token', '780addab3398d7bbfe03b6299fe440c2f789af51d22ab64e050b2519f5befcee', '[\"*\"]', '2026-02-03 10:50:12', NULL, '2026-02-03 10:45:41', '2026-02-03 10:50:12'),
(80, 'App\\Models\\User', 5, 'auth_token', '18f3fdcdb64170dabe4c15ff166432327fa8992c5df330ecd18c77a407ec830b', '[\"*\"]', '2026-02-03 11:35:45', NULL, '2026-02-03 10:51:01', '2026-02-03 11:35:45'),
(81, 'App\\Models\\User', 2, 'auth_token', '335873ab820267eb26c531f673750bd2bcd55a72c20fe541de26cd8c3e0dc66d', '[\"*\"]', '2026-02-03 14:11:24', NULL, '2026-02-03 11:38:14', '2026-02-03 14:11:24'),
(82, 'App\\Models\\User', 2, 'auth_token', '16f41528c62942cc13d7fea9e1fa3767541506f06445f84a39b511c03992323f', '[\"*\"]', '2026-04-15 14:50:26', NULL, '2026-02-03 11:39:52', '2026-04-15 14:50:26'),
(83, 'App\\Models\\User', 5, 'auth_token', '331ab29ce5af2737ba0d4108603bc07e33905c94c49e5fc53caad65df673598d', '[\"*\"]', '2026-02-03 13:40:24', NULL, '2026-02-03 13:05:22', '2026-02-03 13:40:24'),
(86, 'App\\Models\\User', 5, 'auth_token', '02c122d97f613d5920c4b7dd1ffb98fc7f35e883a22c229302d5625d011d02c5', '[\"*\"]', '2026-02-04 15:31:57', NULL, '2026-02-04 07:55:29', '2026-02-04 15:31:57'),
(87, 'App\\Models\\User', 5, 'auth_token', 'd91c3400c56023a10893e36a5ae65271bea3a4f84b68c74da7472f00609f7a84', '[\"*\"]', '2026-02-04 15:45:35', NULL, '2026-02-04 15:34:22', '2026-02-04 15:45:35'),
(88, 'App\\Models\\User', 18, 'auth_token', '41d467320bcda242861b554d744940d9e9bf901b9a4ddee4933d5c2373a69bb7', '[\"*\"]', NULL, NULL, '2026-02-04 15:46:12', '2026-02-04 15:46:12'),
(89, 'App\\Models\\User', 19, 'auth_token', '40f0abc6c2bfc5db065e2212d29e8abcd24f6d9d1746ef2707f0c9f957fc5d3e', '[\"*\"]', '2026-02-10 14:38:40', NULL, '2026-02-04 15:48:47', '2026-02-10 14:38:40'),
(90, 'App\\Models\\User', 5, 'auth_token', '58dfa64c45dfbe6087e9fb84c621117ae3cb59fe4753a6ac135f17f3a88beaa8', '[\"*\"]', '2026-02-04 21:15:05', NULL, '2026-02-04 21:06:20', '2026-02-04 21:15:05'),
(92, 'App\\Models\\User', 5, 'auth_token', 'affbf27c5e8aec2143b562c1fd53f419757af143daaebd844c02f703dcaa74fa', '[\"*\"]', '2026-02-05 12:46:59', NULL, '2026-02-04 21:17:47', '2026-02-05 12:46:59'),
(96, 'App\\Models\\User', 5, 'auth_token', '4103125bb427d369f9a23b38b74ad8f4e1b21e3d5c9b449730a452d2b7b81fe2', '[\"*\"]', '2026-02-06 17:20:16', NULL, '2026-02-05 16:13:50', '2026-02-06 17:20:16'),
(98, 'App\\Models\\User', 5, 'auth_token', 'b9e8d0582ad968eb564448d4b3baa21b7a9b02c7d00c4947c874c16b8118cff5', '[\"*\"]', '2026-02-06 20:43:11', NULL, '2026-02-06 18:33:27', '2026-02-06 20:43:11'),
(100, 'App\\Models\\User', 5, 'auth_token', '6273323a8e5d3d5402c396cafc79ee9db769ac41e369afa2358d65fcac7ce1a3', '[\"*\"]', '2026-02-09 13:35:26', NULL, '2026-02-06 21:15:44', '2026-02-09 13:35:26'),
(101, 'App\\Models\\User', 5, 'auth_token', 'c1bc6077207eb7c42333c6b2ad013c352d71b154e137afedd6d8e3863a958ced', '[\"*\"]', '2026-02-07 16:18:42', NULL, '2026-02-07 14:19:23', '2026-02-07 16:18:42'),
(103, 'App\\Models\\User', 2, 'auth_token', 'a0d8834bb922b649ac274e7488ca5061cfbae01826689b1655476358cd15edcf', '[\"*\"]', '2026-02-10 12:01:03', NULL, '2026-02-10 09:35:49', '2026-02-10 12:01:03'),
(104, 'App\\Models\\User', 2, 'auth_token', '8a1df68726845e61590c25daccda435ee1b147e24f6139c4ce3919b0eba5fec2', '[\"*\"]', '2026-02-10 12:00:18', NULL, '2026-02-10 11:50:07', '2026-02-10 12:00:18'),
(105, 'App\\Models\\User', 5, 'auth_token', '8e49a4d48fea68cc2c36619d0f6113bb3639e0d287e4cfb048ee92e6c23cafd4', '[\"*\"]', '2026-04-15 07:49:55', NULL, '2026-02-10 11:51:43', '2026-04-15 07:49:55'),
(106, 'App\\Models\\User', 2, 'auth_token', '5520e10de6b341f2105e702e991f53653d8403fe24ff86d7e8c684f3c616c7eb', '[\"*\"]', NULL, NULL, '2026-02-10 14:14:51', '2026-02-10 14:14:51'),
(107, 'App\\Models\\User', 9, 'auth_token', 'ae3d1bf87ea1f552d8ba7f35bee82ba9bef7fb7709e62c88eaf3decedb125909', '[\"*\"]', '2026-02-10 15:19:12', NULL, '2026-02-10 14:49:46', '2026-02-10 15:19:12'),
(108, 'App\\Models\\User', 9, 'auth_token', '1a10ce3de9183e6fcb110ec570885b0ed6e9017ea05ad07671a2d2ce696792b8', '[\"*\"]', '2026-02-11 07:33:22', NULL, '2026-02-10 15:23:26', '2026-02-11 07:33:22'),
(109, 'App\\Models\\User', 9, 'auth_token', '71d37ab192492ca7f9a22236bdc4c0f6e226d8770a3575eaa0b31dec29dd1bd3', '[\"*\"]', NULL, NULL, '2026-02-10 15:24:56', '2026-02-10 15:24:56'),
(110, 'App\\Models\\User', 9, 'auth_token', '2731378eff298a5461e833ab098c903aed93f7c86887e2a37bbe3e6a4989bf4b', '[\"*\"]', '2026-02-10 15:32:53', NULL, '2026-02-10 15:28:20', '2026-02-10 15:32:53'),
(111, 'App\\Models\\User', 9, 'auth_token', '79e9d8d236ca6615dca7ea1bb7aead21fe545a8f1589f13e9e0ff41b7b32b53e', '[\"*\"]', NULL, NULL, '2026-02-10 15:37:00', '2026-02-10 15:37:00'),
(112, 'App\\Models\\User', 5, 'auth_token', 'c64bc146ef127be461bb83c06114e8ad2b4a9ba8931436448d3ea024b34d71de', '[\"*\"]', NULL, NULL, '2026-02-10 15:37:22', '2026-02-10 15:37:22'),
(113, 'App\\Models\\User', 9, 'auth_token', '21eee5fa262b08b0c5eef7486407ca6c1495fd4aa8720c98b0e1d2c5787484f4', '[\"*\"]', '2026-02-10 15:40:07', NULL, '2026-02-10 15:37:27', '2026-02-10 15:40:07'),
(114, 'App\\Models\\User', 2, 'auth_token', '6075892ecaf48c6a93225dafb6f3299e8ca7e6d109f868d13ce338a2fee65817', '[\"*\"]', '2026-02-11 10:47:35', NULL, '2026-02-11 07:15:46', '2026-02-11 10:47:35'),
(115, 'App\\Models\\User', 5, 'auth_token', '3514c9852f0d6f741a611dc5f98d87025739ffaaa8d78deb205a0403c0049b2c', '[\"*\"]', '2026-02-11 12:15:26', NULL, '2026-02-11 07:34:19', '2026-02-11 12:15:26'),
(116, 'App\\Models\\User', 9, 'auth_token', '88bb01a985b992a3b39f15eff5e8f448aa1fa9471a1c50246d101ae4f335eff3', '[\"*\"]', '2026-02-11 09:26:20', NULL, '2026-02-11 09:25:58', '2026-02-11 09:26:20'),
(117, 'App\\Models\\User', 11, 'auth_token', '8c992d00fd55ef17b9b1cece38e1f33004eac8078e9a686c80ae02e0a011b98a', '[\"*\"]', '2026-02-11 09:27:39', NULL, '2026-02-11 09:27:30', '2026-02-11 09:27:39'),
(118, 'App\\Models\\User', 12, 'auth_token', '3dcd57675b7e329c5a8f83395f653266347a17647af72c74d402993bee880aed', '[\"*\"]', '2026-02-11 09:30:30', NULL, '2026-02-11 09:30:16', '2026-02-11 09:30:30'),
(119, 'App\\Models\\User', 12, 'auth_token', 'c8fce59c025bdac1f6fbe04f80f65b7a9ae9ff9dee83bf65def9a1e9350c0572', '[\"*\"]', NULL, NULL, '2026-02-11 10:28:57', '2026-02-11 10:28:57'),
(120, 'App\\Models\\User', 5, 'auth_token', '51e23051fa899cda69213a0a8cad41d1cf4339f41b6f92449be6c61fcd356f3a', '[\"*\"]', '2026-02-11 11:25:54', NULL, '2026-02-11 10:29:02', '2026-02-11 11:25:54'),
(121, 'App\\Models\\User', 5, 'auth_token', '6035b155ca8b9227c5f9d1e436f181f34742c0f93da1c00bd67312bee6bddaa7', '[\"*\"]', '2026-02-12 17:17:00', NULL, '2026-02-11 18:57:40', '2026-02-12 17:17:00'),
(122, 'App\\Models\\User', 12, 'auth_token', '0eb8c240c802f6e3e8d52dd171010592acc77d2360bf2be9b0ffb5f68b66e628', '[\"*\"]', '2026-02-11 19:42:59', NULL, '2026-02-11 19:42:13', '2026-02-11 19:42:59'),
(123, 'App\\Models\\User', 12, 'auth_token', '70b63501b0e0fe1046b8ad6b79274a9229ed9bdc3da92a6cb4d69828a549d013', '[\"*\"]', NULL, NULL, '2026-02-11 19:43:09', '2026-02-11 19:43:09'),
(124, 'App\\Models\\User', 12, 'auth_token', '8fa7acf54127df8ecaa76f43247f490c1be50e70d0b6b6023582ec487ce5d88a', '[\"*\"]', '2026-02-11 19:47:29', NULL, '2026-02-11 19:47:10', '2026-02-11 19:47:29'),
(125, 'App\\Models\\User', 2, 'auth_token', '770a29eae5f4c5f415e36544a18fd448e838a0204249f6943085b564d2caa4e6', '[\"*\"]', NULL, NULL, '2026-02-11 19:56:14', '2026-02-11 19:56:14'),
(126, 'App\\Models\\User', 12, 'auth_token', 'e535a51b706be1ca1bcdeb619e6c3efdcc098424aea2c9d4df2212fadc32393e', '[\"*\"]', '2026-02-18 10:45:15', NULL, '2026-02-12 17:19:52', '2026-02-18 10:45:15'),
(127, 'App\\Models\\User', 12, 'auth_token', '772d1ab03a367729b4b3e9e4eeb861125b3150122fd9c61b5c156e06454fb5ea', '[\"*\"]', '2026-02-12 18:22:26', NULL, '2026-02-12 18:21:29', '2026-02-12 18:22:26'),
(132, 'App\\Models\\User', 5, 'auth_token', '998ed0bfc3e282b1570e11c2e59590fec50b01cae8ed7bd8867f25c62c47cb0e', '[\"*\"]', '2026-02-14 14:00:24', NULL, '2026-02-14 09:56:34', '2026-02-14 14:00:24'),
(135, 'App\\Models\\User', 5, 'auth_token', '8049b433120a5867948908e6745005ec7b3c578bf80f04832e41c9f3d6ecb951', '[\"*\"]', '2026-02-14 17:10:53', NULL, '2026-02-14 14:52:55', '2026-02-14 17:10:53'),
(137, 'App\\Models\\User', 5, 'auth_token', '3294d842eb0d1805a5c8c0ebd87ea8651f0fd28e5fbb93a2b4647a4f8232abd3', '[\"*\"]', '2026-02-14 17:21:36', NULL, '2026-02-14 17:21:29', '2026-02-14 17:21:36'),
(139, 'App\\Models\\User', 5, 'auth_token', 'f5d1300844fd56cacf282d60bfad4792787de863a9bbd151e924f7b2240124b3', '[\"*\"]', '2026-02-17 17:21:28', NULL, '2026-02-14 23:23:47', '2026-02-17 17:21:28'),
(142, 'App\\Models\\User', 5, 'auth_token', 'a7fb030ba4aaaa961dfa9eb8112deb2b7ae9fc60063dd391f880febc9afb9a88', '[\"*\"]', '2026-02-17 20:56:20', NULL, '2026-02-17 20:52:10', '2026-02-17 20:56:20'),
(146, 'App\\Models\\User', 5, 'auth_token', '9d1138b734da95eafebff25d335c09bde9df9c08a7e88099ceea8b75747df7f5', '[\"*\"]', '2026-02-18 11:19:31', NULL, '2026-02-18 10:48:00', '2026-02-18 11:19:31'),
(147, 'App\\Models\\User', 12, 'auth_token', '2cd7b39fc041c75e491ce18ee2b956d2255f6852038ffba3a8706f9b3e3ee49f', '[\"*\"]', NULL, NULL, '2026-02-18 11:14:14', '2026-02-18 11:14:14'),
(148, 'App\\Models\\User', 2, 'auth_token', '1d584774bdcf631370f72472f05effd0c95172945637ae4309c2cd17092b52f5', '[\"*\"]', NULL, NULL, '2026-02-18 11:14:44', '2026-02-18 11:14:44'),
(151, 'App\\Models\\User', 12, 'auth_token', '6fa4a20a965f99bc9b77eef06b05176f1a2365a5570c7c8ac0f664fc231f83be', '[\"*\"]', '2026-02-18 11:54:41', NULL, '2026-02-18 11:48:49', '2026-02-18 11:54:41'),
(160, 'App\\Models\\User', 5, 'auth_token', '70062653b0a984079fa1ec7b1a71d46d47a9986d209045a6ab082d7684b5c644', '[\"*\"]', '2026-02-25 15:43:59', NULL, '2026-02-18 22:39:43', '2026-02-25 15:43:59'),
(162, 'App\\Models\\User', 12, 'auth_token', '9052a7e8e565ac02397776149c8af1fbef3dd6002f96fee7691b4efb67f576f4', '[\"*\"]', '2026-02-22 17:07:25', NULL, '2026-02-22 15:02:04', '2026-02-22 17:07:25'),
(164, 'App\\Models\\User', 2, 'auth_token', '9bd6d00fc9530d89b0399474f51ea8bed6dd347468435e0f31e67e58a80ec910', '[\"*\"]', '2026-02-22 18:00:06', NULL, '2026-02-22 17:18:29', '2026-02-22 18:00:06'),
(165, 'App\\Models\\User', 5, 'auth_token', '9f8d42ed8d642b845fdb5745e3c5b6f540739dfbe1dd5bddab364a1c7e08a90c', '[\"*\"]', '2026-02-24 14:27:33', NULL, '2026-02-23 15:10:37', '2026-02-24 14:27:33'),
(166, 'App\\Models\\User', 20, 'auth_token', 'fa600ecb5b8098ffeb5725172cea511c25e66d50101f1305ead6c4a58f929818', '[\"*\"]', '2026-02-23 17:51:46', NULL, '2026-02-23 17:21:36', '2026-02-23 17:51:46'),
(167, 'App\\Models\\User', 5, 'auth_token', 'c7bfbc0e8ce5eb8f9f2c5f5863a39a777b6f1d5417f6d8644c48b59f71740702', '[\"*\"]', '2026-02-23 18:01:17', NULL, '2026-02-23 17:59:52', '2026-02-23 18:01:17'),
(168, 'App\\Models\\User', 20, 'auth_token', '5c2d5d7d4df578e00bfc34304d504e8f186d9dd8c42fc7ba1ba9567a3e14fa0f', '[\"*\"]', '2026-02-23 18:27:54', NULL, '2026-02-23 18:04:32', '2026-02-23 18:27:54'),
(169, 'App\\Models\\User', 20, 'auth_token', '5391b433eafc2830774909d28c953cf3379d54c8276ec52f2c9d89da4a195e14', '[\"*\"]', '2026-02-23 19:24:38', NULL, '2026-02-23 19:24:28', '2026-02-23 19:24:38'),
(170, 'App\\Models\\User', 5, 'auth_token', '241af38d363a3fc6ed3c270e17dbe05927ce15351d78cc35bc2b0cf983fddb88', '[\"*\"]', '2026-02-23 19:28:23', NULL, '2026-02-23 19:25:05', '2026-02-23 19:28:23'),
(177, 'App\\Models\\User', 12, 'auth_token', '186b4593e677059f9c9fd6b921ea45479472573f11f0b2d5b582493a44a0bb77', '[\"*\"]', '2026-02-24 14:08:53', NULL, '2026-02-24 13:35:40', '2026-02-24 14:08:53'),
(180, 'App\\Models\\User', 5, 'auth_token', 'c5c1ffa7510b0a9f1a8b910f8c90d6d25e90663251c1096e63d41aeeffc629c3', '[\"*\"]', '2026-02-25 16:45:49', NULL, '2026-02-24 14:32:07', '2026-02-25 16:45:49'),
(181, 'App\\Models\\User', 2, 'auth_token', '82c35059ab1840a1f1258e0b437a90e2969869a0bd018cfb5ca3474a91eb3634', '[\"*\"]', '2026-02-25 17:12:59', NULL, '2026-02-25 16:53:07', '2026-02-25 17:12:59'),
(182, 'App\\Models\\User', 5, 'auth_token', '6ebf4ced81d6c2514fc79d23fcb101facbd31ff25e9cb442e144e111cf1712d9', '[\"*\"]', '2026-02-25 19:04:13', NULL, '2026-02-25 19:00:46', '2026-02-25 19:04:13'),
(185, 'App\\Models\\User', 21, 'auth_token', '29546e816a3ab6dc2c16218ae7749b1c621976df9f57f6df0519c7223903ed00', '[\"*\"]', '2026-02-25 19:41:18', NULL, '2026-02-25 19:40:42', '2026-02-25 19:41:18'),
(186, 'App\\Models\\User', 5, 'auth_token', '8c5ac9f173f8d4590e7ed571a825ea810b221b07bf0547440f644e8ac950b554', '[\"*\"]', '2026-02-25 19:43:07', NULL, '2026-02-25 19:41:45', '2026-02-25 19:43:07'),
(189, 'App\\Models\\User', 5, 'auth_token', '8f39d59d6ce0cf482e604133018cfdd8420aeec726f54b48c75edfc7d91b8ce2', '[\"*\"]', '2026-02-25 20:56:33', NULL, '2026-02-25 20:53:04', '2026-02-25 20:56:33'),
(191, 'App\\Models\\User', 5, 'auth_token', '99f20e85638670a2979c76c6fc91df47d4e40efd0e12cf76c470285d1c59d380', '[\"*\"]', '2026-02-26 02:34:38', NULL, '2026-02-26 02:31:38', '2026-02-26 02:34:38'),
(194, 'App\\Models\\User', 9, 'auth_token', 'a798f876faf0380465628c6d41a9339278e172715738d0bd297c2128749c93b0', '[\"*\"]', '2026-02-26 02:39:43', NULL, '2026-02-26 02:38:07', '2026-02-26 02:39:43'),
(196, 'App\\Models\\User', 5, 'auth_token', 'fdddf0a13044de5f0b7ea087b3287305162cb72e4109e3f260a6dbf76ec39092', '[\"*\"]', '2026-02-26 09:52:05', NULL, '2026-02-26 09:41:07', '2026-02-26 09:52:05'),
(200, 'App\\Models\\User', 5, 'auth_token', '9ee528bc069f37b456a5898e20c6548a3148b14150ceac712b77086eec607c5e', '[\"*\"]', '2026-02-26 10:06:32', NULL, '2026-02-26 10:05:35', '2026-02-26 10:06:32'),
(203, 'App\\Models\\User', 5, 'auth_token', '09d115fcfbdc6a3063b84059d4c99b5776d50f251e778f4d5a648e0329fbb424', '[\"*\"]', '2026-02-26 10:22:37', NULL, '2026-02-26 10:22:37', '2026-02-26 10:22:37'),
(205, 'App\\Models\\User', 5, 'auth_token', '09be56b4b6bb58bb7ece7a6e6c7045cb445f099eda8239002e216c1923002b1b', '[\"*\"]', '2026-02-26 11:05:20', NULL, '2026-02-26 11:05:19', '2026-02-26 11:05:20'),
(206, 'App\\Models\\User', 12, 'auth_token', '22e2e51033dc4a083c5d3fe1c68a3f57bf47fb73e9185ae3cf376df93c7a30b6', '[\"*\"]', '2026-02-26 12:19:35', NULL, '2026-02-26 12:19:12', '2026-02-26 12:19:35'),
(209, 'App\\Models\\User', 12, 'auth_token', 'd8774818549965f21caee2cae38ef189de138027e7ccc6b29635f1c088238bf0', '[\"*\"]', '2026-02-26 12:41:09', NULL, '2026-02-26 12:38:39', '2026-02-26 12:41:09'),
(210, 'App\\Models\\User', 12, 'auth_token', '3c760eb3754eb1a3f6192992acf103519480f1cb7bcd5f1e02058cd1a17c92d9', '[\"*\"]', NULL, NULL, '2026-02-26 12:40:25', '2026-02-26 12:40:25'),
(212, 'App\\Models\\User', 2, 'auth_token', 'c1bf2dfa79f60e6f3f5398eceb4022941f6666d21ce4699ec8b3bfcee3cc39af', '[\"*\"]', '2026-02-26 13:59:47', NULL, '2026-02-26 12:58:20', '2026-02-26 13:59:47'),
(213, 'App\\Models\\User', 5, 'auth_token', '5b863a651097cdd2bd9ebdb97afd2c91e4ef0e82295d3eb81cdf26131b20e13c', '[\"*\"]', '2026-03-05 15:46:41', NULL, '2026-02-26 13:01:08', '2026-03-05 15:46:41'),
(215, 'App\\Models\\User', 5, 'auth_token', 'b2f4f1b0da03129197b4cff09c9a29441f944714ff7dd5ca3c7a715ab4b87194', '[\"*\"]', '2026-02-26 14:54:02', NULL, '2026-02-26 14:48:12', '2026-02-26 14:54:02'),
(217, 'App\\Models\\User', 5, 'auth_token', '71851800183ea785d838026fbfbabff548a6d8cce503c317bba2afd5a2499dfc', '[\"*\"]', '2026-02-26 22:36:07', NULL, '2026-02-26 22:34:45', '2026-02-26 22:36:07'),
(220, 'App\\Models\\User', 5, 'auth_token', 'a36a060c49b9df76251861ec977d5ace8346f7aa5ddbbc0c45b2d1a157687fa9', '[\"*\"]', '2026-02-27 12:02:37', NULL, '2026-02-26 22:42:27', '2026-02-27 12:02:37'),
(222, 'App\\Models\\User', 5, 'auth_token', '3e91c4a04047ccba5be8ea7c27edd8e196a5210ce85bf73789ebf2122c2f9dbb', '[\"*\"]', '2026-02-27 12:04:58', NULL, '2026-02-27 12:04:51', '2026-02-27 12:04:58'),
(224, 'App\\Models\\User', 5, 'auth_token', '807e6557d9a793a856896408647fa99e0d8ced74898e099f481d44d5f48b182b', '[\"*\"]', '2026-02-27 12:06:58', NULL, '2026-02-27 12:06:49', '2026-02-27 12:06:58'),
(226, 'App\\Models\\User', 5, 'auth_token', '6567f266f2ed780c01a7c83b08101fbebfe0645ce0a9fd7a9d7af3e987a970de', '[\"*\"]', '2026-02-27 12:42:16', NULL, '2026-02-27 12:09:30', '2026-02-27 12:42:16'),
(228, 'App\\Models\\User', 5, 'auth_token', '2b5fc09386335e4573bc6f2ea5da6f80b4e5589ec0eec242705f875ed4db3e28', '[\"*\"]', '2026-02-27 12:45:49', NULL, '2026-02-27 12:44:19', '2026-02-27 12:45:49'),
(230, 'App\\Models\\User', 5, 'auth_token', '6b750f09032db5899bba1947715b9c7df63bb71d8b21ff97312ba7e29e469d93', '[\"*\"]', '2026-02-28 00:59:25', NULL, '2026-02-27 12:50:46', '2026-02-28 00:59:25'),
(232, 'App\\Models\\User', 5, 'auth_token', '45c43fefa03e8651796e38d1feafc0eac8d8d5aeca98e5ef0977ebb071ac2832', '[\"*\"]', '2026-02-28 01:03:17', NULL, '2026-02-28 01:02:30', '2026-02-28 01:03:17'),
(234, 'App\\Models\\User', 12, 'auth_token', '3ea7d730087cec9323064c30db3763be4f9cb5f0ffe4b5791620dfefebf6a52d', '[\"*\"]', '2026-02-28 01:14:21', NULL, '2026-02-28 01:05:49', '2026-02-28 01:14:21'),
(235, 'App\\Models\\User', 5, 'auth_token', '2f80d259c379a17813ef1caf05ad0bf572bb3f8132a723410b98d365b77a835a', '[\"*\"]', '2026-03-02 21:16:15', NULL, '2026-03-01 19:08:16', '2026-03-02 21:16:15'),
(236, 'App\\Models\\User', 5, 'auth_token', 'e54f984ec4c2a4e879b9fbfba71811903cd9e6b3619eda4e7a392833fa248c4c', '[\"*\"]', '2026-03-02 22:43:30', NULL, '2026-03-02 21:33:43', '2026-03-02 22:43:30'),
(237, 'App\\Models\\User', 5, 'auth_token', 'e1a0d7d4e6a324c466124e80a30ec61a7d0f22f8a81f7124edb1b08777d8d8be', '[\"*\"]', '2026-03-03 00:09:31', NULL, '2026-03-03 00:07:30', '2026-03-03 00:09:31'),
(241, 'App\\Models\\User', 5, 'auth_token', '91640d66879d455b2e899eeb843b3a2a91ce60829ac199c5bc5fca8310bb86c5', '[\"*\"]', '2026-03-03 23:09:49', NULL, '2026-03-03 23:08:57', '2026-03-03 23:09:49'),
(244, 'App\\Models\\User', 5, 'auth_token', '5bcf5e46863a7632f563005bca2f8699b2b2853b00242de1f84dede88b1acfe2', '[\"*\"]', '2026-03-04 00:32:16', NULL, '2026-03-03 23:12:44', '2026-03-04 00:32:16'),
(246, 'App\\Models\\User', 5, 'auth_token', '0148ef717a97b4305831edad78e715c76a66f98efb88ab9b84d7faaddd619e6a', '[\"*\"]', '2026-03-04 14:40:09', NULL, '2026-03-04 14:36:27', '2026-03-04 14:40:09'),
(247, 'App\\Models\\User', 5, 'auth_token', '71a999da96ddb893663ed95c7542b2c3cec1881b0a10e189dd96227fabfbc2ba', '[\"*\"]', '2026-03-04 19:08:15', NULL, '2026-03-04 14:42:47', '2026-03-04 19:08:15'),
(248, 'App\\Models\\User', 5, 'auth_token', '142763c618c7ead27b8c501994d277c36abc2169bda538194092e13d2c71abdc', '[\"*\"]', '2026-03-04 19:17:04', NULL, '2026-03-04 19:09:57', '2026-03-04 19:17:04'),
(250, 'App\\Models\\User', 5, 'auth_token', '02712c62c26c48329446455e7500b01509186080fb336c6169e69dced0bdf1fb', '[\"*\"]', '2026-03-04 19:25:38', NULL, '2026-03-04 19:25:18', '2026-03-04 19:25:38'),
(252, 'App\\Models\\User', 5, 'auth_token', '4152ef130f1d717d371f8de1e1627718349933c6cc108c505535e29c23ea3638', '[\"*\"]', '2026-03-04 19:27:14', NULL, '2026-03-04 19:27:04', '2026-03-04 19:27:14'),
(255, 'App\\Models\\User', 5, 'auth_token', 'ed9e7601cfcd8cf5c46e9506e09b359ba64c8aebb8dd8c2cf9a1d32829af8a22', '[\"*\"]', '2026-03-04 21:19:04', NULL, '2026-03-04 21:07:57', '2026-03-04 21:19:04'),
(258, 'App\\Models\\User', 5, 'auth_token', '597efc212a0d726ba3442e37985f0d27ce8ab980883dbad82931fc52a419edd6', '[\"*\"]', '2026-03-05 00:28:13', NULL, '2026-03-04 21:24:17', '2026-03-05 00:28:13'),
(260, 'App\\Models\\User', 5, 'auth_token', 'b29c26be02001e6422e4709a79fc76f06b8c44901fd205091e3bb6f572235f31', '[\"*\"]', '2026-03-05 01:05:04', NULL, '2026-03-05 01:04:07', '2026-03-05 01:05:04'),
(266, 'App\\Models\\User', 5, 'auth_token', '8cba6897dca275a06632c9d08ef7ff845a5ef0adcf22c96e689c3c51730ab012', '[\"*\"]', '2026-03-05 12:13:46', NULL, '2026-03-05 01:09:39', '2026-03-05 12:13:46'),
(269, 'App\\Models\\User', 2, 'auth_token', '553ba1116fa8d73bd20e24aa37514f4b5b074809e9fb31781af4537496ea0e1a', '[\"*\"]', '2026-03-05 15:24:21', NULL, '2026-03-05 15:15:40', '2026-03-05 15:24:21'),
(272, 'App\\Models\\User', 2, 'auth_token', '0846419a34a29a0f68ad1891a209c176be9b8997e5d642cde0b5f47f6316901f', '[\"*\"]', '2026-03-14 15:21:32', NULL, '2026-03-05 15:46:19', '2026-03-14 15:21:32'),
(283, 'App\\Models\\User', 5, 'auth_token', '35c42cedfbeccc2f56166c28fdef981438a8bf502d6f4479b38222f53f10c0e1', '[\"*\"]', '2026-03-16 14:15:56', NULL, '2026-03-16 14:08:49', '2026-03-16 14:15:56'),
(293, 'App\\Models\\User', 5, 'auth_token', 'e5a4cc5a52346f0ea17a099d85bf21ad98f91f1bfaa9c3d2c7881e2868f4888e', '[\"*\"]', '2026-03-25 19:55:39', NULL, '2026-03-25 15:46:19', '2026-03-25 19:55:39'),
(301, 'App\\Models\\User', 5, 'auth_token', '918e2d83246efc8741493327fe5dfb23061ed2c3044a453de0f757f6ba71a4ec', '[\"*\"]', '2026-04-12 13:38:20', NULL, '2026-04-09 19:32:57', '2026-04-12 13:38:20'),
(302, 'App\\Models\\User', 5, 'auth_token', '7f8fdecec1ef07e641efde98eb60758c1bd89cd9b8ef28383f7f00f391adc0a1', '[\"*\"]', '2026-04-12 14:47:46', NULL, '2026-04-12 14:47:23', '2026-04-12 14:47:46'),
(304, 'App\\Models\\User', 5, 'auth_token', '7d386a9b4885cbc3512cfba0037a0355a4b08655d557c479ac4dea5265455608', '[\"*\"]', '2026-04-12 15:33:57', NULL, '2026-04-12 15:01:57', '2026-04-12 15:33:57'),
(306, 'App\\Models\\User', 20, 'auth_token', '647dcf0b2845543ff1e8af5b7ae543285e483f8f5d45276ebe0c532c524b0e36', '[\"*\"]', NULL, NULL, '2026-04-14 10:48:56', '2026-04-14 10:48:56'),
(307, 'App\\Models\\User', 22, 'auth_token', '3631a263e42543035b6e0feb37263c4578bc1eea42fc8f46d33a582c6d4f06c0', '[\"*\"]', NULL, NULL, '2026-04-14 12:48:36', '2026-04-14 12:48:36'),
(309, 'App\\Models\\User', 2, 'auth_token', 'fdd2c65324c5219d7809a916e6cfb89f4d2f6fcc1de0d94ca4e7500c970bb177', '[\"*\"]', '2026-04-15 07:51:17', NULL, '2026-04-15 07:48:58', '2026-04-15 07:51:17'),
(314, 'App\\Models\\User', 31, 'auth_token', 'dafda5b32fed1bab6a45094ef8f0c4d27499efa88268b60b608879cac1dca6b7', '[\"*\"]', '2026-04-18 13:37:37', NULL, '2026-04-15 15:47:13', '2026-04-18 13:37:37'),
(315, 'App\\Models\\User', 31, 'auth_token', 'aeab5f34c838e56edfcb7d17021fa8dae9610871b57a9e0cee9394fd04442c15', '[\"*\"]', '2026-04-16 08:38:55', NULL, '2026-04-16 08:38:54', '2026-04-16 08:38:55'),
(319, 'App\\Models\\User', 31, 'auth_token', '2d630fb7642768e1fe5615e4304911e5dd13450057f320184cd9994b749a2773', '[\"*\"]', '2026-04-16 21:15:52', NULL, '2026-04-16 21:15:11', '2026-04-16 21:15:52'),
(325, 'App\\Models\\User', 31, 'auth_token', 'f59178d6c3d5e21d88d98e0931d63656d906eb0d1aaff6e4723cebd74ff74fff', '[\"*\"]', '2026-04-18 10:43:54', NULL, '2026-04-18 09:36:54', '2026-04-18 10:43:54'),
(342, 'App\\Models\\User', 31, 'auth_token', '36f23e28cf121d90a74f2bc0095ba5f17f2b981885a88ddf196211b5e99ffad1', '[\"*\"]', '2026-04-22 19:33:03', NULL, '2026-04-22 19:32:43', '2026-04-22 19:33:03');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `slug` varchar(255) NOT NULL,
  `description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`description`)),
  `short_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`short_description`)),
  `sku` varchar(255) NOT NULL,
  `unit_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount` double NOT NULL DEFAULT 0,
  `discount_type` enum('percentage','fixed') NOT NULL DEFAULT 'percentage',
  `max_order_quantity` int(11) DEFAULT 1,
  `manage_stock` tinyint(1) NOT NULL DEFAULT 1,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_bookable` tinyint(1) NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `is_new` tinyint(1) NOT NULL DEFAULT 1,
  `meta_title` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_title`)),
  `meta_description` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_description`)),
  `meta_keywords` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`meta_keywords`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL,
  `type` enum('simple','variable') NOT NULL DEFAULT 'simple',
  `stock` int(11) DEFAULT NULL,
  `price` decimal(8,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `slug`, `description`, `short_description`, `sku`, `unit_id`, `discount`, `discount_type`, `max_order_quantity`, `manage_stock`, `is_active`, `is_bookable`, `is_featured`, `is_new`, `meta_title`, `meta_description`, `meta_keywords`, `created_at`, `updated_at`, `deleted_at`, `type`, `stock`, `price`) VALUES
(1, '{\"ar\": \"بطاطس\", \"en\": \"Potato\"}', 'potato', '{\"ar\": \"<p>وصف تجريبي&nbsp;</p>\", \"en\": \"<p>Just Test Desc</p>\"}', '{\"ar\": \"بطاطاس طازجة\", \"en\": \"Fresh Potatos\"}', 'POT', 2, 10, 'percentage', 25, 1, 1, 0, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-08 08:14:09', '2025-12-08 08:14:09', NULL, 'simple', NULL, 20.00),
(2, '{\"ar\": \"تفاح\", \"en\": \"Apple\"}', 'apple', '{\"ar\": \"<p>وصف تجريبي</p>\", \"en\": \"<p>test desc</p>\"}', '{\"ar\": \"تفاح طبيعي فريش\", \"en\": \"Fresh Organic Apple\"}', 'APL', 2, 10, 'percentage', 50, 1, 1, 0, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-08 09:40:56', '2025-12-11 13:42:41', NULL, 'variable', 0, 0.00),
(3, '{\"ar\": \"دراجون فروت\", \"en\": \"Dragon Fruit\"}', 'dragon-fruit', '{\"ar\": \"<p>وصف</p>\", \"en\": \"<p>desc</p>\"}', '{\"ar\": \"وصف دراجون فروت\", \"en\": \"Dragon Fruit Description\"}', 'DRGN', 1, 0, 'percentage', 50, 1, 1, 0, 0, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-08 10:00:20', '2025-12-08 10:08:27', NULL, 'simple', 0, 100.00),
(4, '{\"ar\": \"مانجو\", \"en\": \"Mango\"}', 'mango', '{\"ar\": \"<p>وصف&nbsp;</p>\", \"en\": \"<p>desc</p>\"}', '{\"ar\": \"مانجو فريش\", \"en\": \"Fresh Mango\"}', 'MNG', 2, 10, 'percentage', 50, 1, 1, 0, 1, 1, '{\"ar\": null}', '{\"ar\": null}', '{\"ar\": null}', '2025-12-08 10:07:08', '2025-12-10 08:44:02', NULL, 'variable', 0, 0.00);

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

CREATE TABLE `product_attribute_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_id` bigint(20) UNSIGNED NOT NULL,
  `attribute_option_id` bigint(20) UNSIGNED DEFAULT NULL,
  `value` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `product_images`
--

CREATE TABLE `product_images` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `imageable_id` bigint(20) UNSIGNED NOT NULL,
  `imageable_type` varchar(255) NOT NULL,
  `path` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_images`
--

INSERT INTO `product_images` (`id`, `imageable_id`, `imageable_type`, `path`, `created_at`, `updated_at`) VALUES
(1, 1, 'App\\Models\\Product', 'products/XJVB1eaHkFAby3pKdC44Kmkt9krRoSlQdm5D9Nnd.png', '2025-12-08 08:14:09', '2025-12-08 08:14:09'),
(2, 2, 'App\\Models\\Product', 'products/hrZeiBvf1BSkgV1WbzUdzZhUYMTz3PkAnbtOOTxv.png', '2025-12-08 09:40:56', '2025-12-08 09:40:56'),
(3, 1, 'App\\Models\\ProductVariant', 'products/variants/ARmwWwKvNN0gcj7rct1ELSRcrXSTtLHCS3TV4OJ5.png', '2025-12-08 09:40:56', '2025-12-08 09:40:56'),
(4, 2, 'App\\Models\\ProductVariant', 'products/variants/r7tAmfHxFOAJKUvrw60DZHkb5wP8sTgr3aO8QZIa.jpg', '2025-12-08 09:40:56', '2025-12-08 09:40:56'),
(5, 3, 'App\\Models\\Product', 'products/YdejXZ4EJ7lUgTyUM7sJu8x89G1Rbw2H0B45i19y.png', '2025-12-08 10:00:20', '2025-12-08 10:00:20'),
(6, 4, 'App\\Models\\Product', 'products/2jeau971DMDQMNyyO58Z7DqCaDn9ZaIPblQ4efhW.png', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(7, 4, 'App\\Models\\Product', 'products/KRXKlxE2BuEp31yY0m2OxTRKpxfMzXSxkCkbgjdd.png', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(8, 3, 'App\\Models\\ProductVariant', 'products/variants/nvsOtfUHGuiS2phXFvyXT9xYt2JtnaUdIrXcGaei.png', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(9, 4, 'App\\Models\\ProductVariant', 'products/variants/i4KZrypOZczRRJuCVu4bhNwe97I2gELs932p6ohf.png', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(10, 5, 'App\\Models\\ProductVariant', 'products/variants/csBI7M0WWi7HqLNdCbOpTYoimcUrfg6CVQ44FaBt.png', '2025-12-10 08:44:02', '2025-12-10 08:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_relations`
--

CREATE TABLE `product_relations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `related_product_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_relations`
--

INSERT INTO `product_relations` (`id`, `product_id`, `related_product_id`, `type`, `created_at`, `updated_at`) VALUES
(3, 3, 4, 'related', '2025-12-08 10:08:27', '2025-12-08 10:08:27'),
(4, 4, 3, 'related', '2025-12-10 08:44:02', '2025-12-10 08:44:02'),
(5, 4, 2, 'related', '2025-12-10 08:44:02', '2025-12-10 08:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `product_variants`
--

CREATE TABLE `product_variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `slug` varchar(255) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `stock` int(11) NOT NULL DEFAULT 0,
  `price` decimal(8,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variants`
--

INSERT INTO `product_variants` (`id`, `product_id`, `name`, `slug`, `sku`, `stock`, `price`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, '{\"ar\": \"تفاح احمر\", \"en\": \"Red Apple\"}', 'apl-r', 'APL-r', 0, 85.00, 1, '2025-12-08 09:40:56', '2025-12-08 09:45:34', NULL),
(2, 2, '{\"ar\": \"تفاح اخضر\", \"en\": \"Green Apple\"}', 'apl-g', 'APL-g', 0, 75.00, 1, '2025-12-08 09:40:56', '2025-12-08 09:45:34', NULL),
(3, 4, '{\"ar\": \"مانجو عويس\", \"en\": \"Kent Mango\"}', 'mng-r', 'MNG-r', 0, 75.00, 1, '2025-12-08 10:07:08', '2025-12-10 08:44:02', NULL),
(4, 4, '{\"ar\": \"مانجو نعوم\", \"en\": \"Neelam Mango\"}', 'mng-g', 'MNG-g', 0, 120.00, 1, '2025-12-08 10:07:08', '2025-12-10 08:44:02', NULL),
(5, 4, '{\"ar\": \"مانجو فص\", \"en\": \"Yellow Mango\"}', 'mng-y', 'MNG-y', 0, 65.00, 1, '2025-12-10 08:44:02', '2025-12-10 08:44:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_variant_values`
--

CREATE TABLE `product_variant_values` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_variant_id` bigint(20) UNSIGNED NOT NULL,
  `variant_option_id` bigint(20) UNSIGNED NOT NULL,
  `value` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`value`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_variant_values`
--

INSERT INTO `product_variant_values` (`id`, `product_variant_id`, `variant_option_id`, `value`, `created_at`, `updated_at`) VALUES
(1, 1, 5, '{\"en\": \"Red\"}', '2025-12-08 09:40:56', '2025-12-08 09:40:56'),
(2, 2, 6, '{\"en\": \"Green\"}', '2025-12-08 09:40:56', '2025-12-08 09:40:56'),
(3, 3, 5, '{\"en\": \"Red\"}', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(4, 4, 6, '{\"en\": \"Green\"}', '2025-12-08 10:07:08', '2025-12-08 10:07:08'),
(5, 5, 10, '{\"en\": \"Yellow\"}', '2025-12-10 08:44:02', '2025-12-10 08:44:02');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `rating` tinyint(4) NOT NULL,
  `comment` text NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `product_id`, `user_id`, `rating`, `comment`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 5, 5, 'Great Mango', 'approved', '2025-12-08 12:16:52', '2025-12-08 12:17:03'),
(2, 4, 5, 5, 'Great Mango', 'pending', '2026-02-11 19:02:18', '2026-02-11 19:02:18'),
(3, 2, 5, 3, 'good', 'pending', '2026-02-11 19:10:24', '2026-02-11 19:10:24'),
(4, 2, 5, 3, 'fffgg', 'pending', '2026-02-11 19:10:54', '2026-02-11 19:10:54'),
(5, 2, 5, 4, 'ggg', 'pending', '2026-02-11 19:13:04', '2026-02-11 19:13:04'),
(6, 3, 5, 5, 'test', 'pending', '2026-02-14 16:54:52', '2026-02-14 16:54:52'),
(7, 4, 5, 5, 'great', 'pending', '2026-02-15 17:35:46', '2026-02-15 17:35:46'),
(8, 1, 5, 5, 'good', 'pending', '2026-02-26 22:55:55', '2026-02-26 22:55:55');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`id`, `name`, `guard_name`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'web', '2025-09-23 04:07:30', '2025-09-23 04:07:30'),
(2, 'employee', 'web', '2025-09-23 04:07:30', '2025-09-23 04:07:30'),
(3, 'user', 'web', '2025-09-23 04:07:30', '2025-09-23 04:07:30'),
(4, 'delivery', 'web', '2025-09-23 04:07:30', '2025-09-23 04:07:30'),
(5, 'support', 'web', '2026-04-09 13:27:29', '2026-04-09 13:27:29');

-- --------------------------------------------------------

--
-- Table structure for table `role_has_permissions`
--

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1FJp6f2KkTRLYR4e7Nz0R09hoNiIpUm3uOp3hoWJ', NULL, '193.32.248.161', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:98.0) Gecko/20100101 Firefox/98.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiR2VTN3RuZ2YzWFFXaGZJNnRUcHhqb3JvN1FOZkJ5N3VhdDkyV1dIbSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL3d3dy5nZWVibGUudGVhbXFlZW1hdGVjaC5zaXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776783180),
('3IcGG9KmmT9VYOc4jHiZVO1OGc9bv6D8Lhbndc5T', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRVpLQmtEbkNLdUx6YVJJNko5dDBpc2JMSEtIa051TW5GNVUyY01LUSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('3Xl1JyvCAX4FVwT9KVT2Xn5ZJvM9xvJkOiewuxQQ', NULL, '88.151.32.220', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/136.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiazRRMnB2SDY4M2J0VmxtaFpZV0lPU0lxQ21tQk1HbmJGQ0EwaEhTYyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776523215),
('4wGPxPzOam0Kq2jVOn42Txv49zflW4jfwUEoRDH2', NULL, '146.190.37.60', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicFdHaEZPRm1tSFo4RUZKTmNpTE02dTNZbVlad2JvY0xXeE5BS2RnRyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL3d3dy5nZWVibGUudGVhbXFlZW1hdGVjaC5zaXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776478311),
('7eeAKRknK0abUScZAdjdTO08lXkDRJmMEXULVJnf', NULL, '167.94.146.58', 'Mozilla/5.0 (compatible; CensysInspect/1.1; +https://about.censys.io/)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidm81UDM5YWRIazUzZGRXZk92ajJ6dDQwQ1BpT1hZaE9jWlJRcURoYSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776946296),
('7q5GJvEompKmRiHQ7TAebq8zujLbctxrcFtCArE2', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRnFlMm1MRFhQR0NXN0JWc3daR2tNaTJIYWpYbVF1UFJNbnU5MXJyQyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478069),
('9HnsL71dw7BR0xNy2Hre5z8xZvOIsChgwhDDMOwM', NULL, '35.204.157.49', 'Scrapy/2.13.4 (+https://scrapy.org)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoicWtXb1hZU2t0YTZKZmVRT21xWFptOElOZlI4c0U3M1FvbW5NajJsUyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM3OiJodHRwczovL3d3dy5nZWVibGUudGVhbXFlZW1hdGVjaC5zaXRlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1776473037),
('akrdSsg07WBB6IFl2pDqfptKB8v1C4RNwxZiBKGN', NULL, '34.100.135.49', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoieGJlRXBhNW1VYjRFZklNSGRmVWxZRm5kS0lva2JrZGdZdVE3SDdnOCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776652156),
('B77JYc27l0t9SfETC8OgaKIkJs99BMXjPkOFTb7T', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTDBIMmlzWTV4RWVuZGRKQkZ4aG9UTWM5QW1FbUg1Yldtc3hVblk1NiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('BcXVJGV0LriIMW4wAfzVli9RBWHKPSOtBaJ5C4EH', NULL, '91.134.35.95', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_3) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/73.0.3683.103 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRlBUMUZtM09oMTRJcTVKMEZqODBvV2N2S2J5ZnJNc2FzQzVMM1NJUyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vZ2VlYmxlLnRlYW1xZWVtYXRlY2guc2l0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776497615),
('eLF77WPxvmM7bcja7D7EHUnsWUJayntWaquevQ8z', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSXJFN2t1aFdQOHVubmprd0VhSFlVZlJoNHNaRUNQZzd3Zmc3QnNuTyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478032),
('eqKPQY2QC6wOUSsAuQOXjQKf623RmOBUo2dZSxSl', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoib1JqNFJneFNkb3JwcEc3aGp0TmNLUmhzZXJQUE1Bcmg0RzBLdGdEZCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('EZlKOPQvUoj2HMVFEwz3sUFXpvOBgpyUgGDGbOKb', NULL, '34.100.135.49', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.0 Safari/605.1.15', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoianhjdHpPcmpNSHJXVTJ0YktsQTZyOWs0U0tYS0JrMHhLNWVxOWwwWiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vZ2VlYmxlLnRlYW1xZWVtYXRlY2guc2l0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776652157),
('gZsA5tx7Z7Q1UwTsend3wB9jU3kwiniErzy108fi', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVEg4V2ZHSmJobkhYV0dENWg5aTRxa1FqRzI1SGFUWHM2ckwwV1QyViI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478032),
('H3cNBpd64JQoccn0Ub4mOfXfOU0Q1jPCYi4Dl4lq', NULL, '32.194.215.91', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) HeadlessChrome/138.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQWlEVDRWOFdkUTRBT3VLa3pMS0R5SHNnOWlRRWpabGRzT21ZZDN3USI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vZ2VlYmxlLnRlYW1xZWVtYXRlY2guc2l0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776578117),
('hSr0AxakjszQhmyYPheONTrkFGOvUfwpxoBkJWzx', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZ3p6dWZiTkhmZWE5RE5VSXF0Z0xSbnhyNXpMTUdUT2gyWlBzTUJXbiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478069),
('i7IeqRRwR0Muai4gFEnbaeQ5il6KKtQoSD1bUlbd', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZjd3OXAzVEVJcmNJYjg3Z0hBRXB2V2lDcGk0VlFSOVBsNzA1eE1QdiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('iBRyVxJ60SlefWGrz592iz8vg5f7oUs6093uBoKN', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTlBXeVpWWVFtVExqd2M0bHFFdmZ5NzdNcjhUSnlUOUZJWm5oZE9oRSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478032),
('IDquCmm5rPIO7eNIXWEiz89qz6iihOPuTLYvtvDR', NULL, '34.141.147.241', 'Scrapy/2.13.4 (+https://scrapy.org)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoia0dmUmFON1czUnRNNXVBRjJSSVpoVURMWHlDeGg4bUViUEcwU2diVCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776473023),
('ij2WdPp0O7osTp1tnHXitYxHOVZ1QfVoxc3EYY1R', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVTZFNTVjNUEzR3hmTElTRTAyZ1B6U0dhRENiTk83b2RpOE41WnZnaCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478099),
('LuXWCetWdhyTIFmrSWpCoG2aakTL54SSlVGQcg2W', NULL, '193.143.1.112', 'Mozilla/5.0 (Linux; Android 10; VOG-L29) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Mobile Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiRTVBZnJsMnBRYW12ZmJUbkRma2xyNEhJZFB6dzBoYlY4MFRlb0QycCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776791443),
('MGjSgUGRoWV7SCoQXTERASGBa1wnYPaPHfzVW7kN', NULL, '193.32.248.161', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiY0xhSkVCQnFnQXFNUXFHMlppZVFmNjdlMk1MU1lxZnlJUExNNkpKNSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776783087),
('mLclasQekKTNuzwKZBqItyJXtCOMfdp8kYPR9zIU', NULL, '64.23.203.25', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiVWdpYnZYTlN0UmF3SkRTRjdsT1VpV1hwMjdiNld6dHlKdHRSa0FDSyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776474953),
('mTvgY1YxLbUSTyqPzr2ifjrEM0GIqECQjxx0GzMj', NULL, '74.125.184.183', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiNnJnMm80WFlZenNzM3Q5YXdXZmo4V1BFeEpzWkdYT2VHS3J1Y2dJNiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM5OiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776804883),
('OEEpVaW9mGRjchUeePFEuIrbCDcsfooHo5JSHBV5', NULL, '64.23.203.25', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiN2xyUVR3WGE3aWJHMVZYcDNGRzM2aFNvSGhmVVpiczd6YkkybnBOdiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMyOiJodHRwOi8vZ2VlYmxlLnRlYW1xZWVtYXRlY2guc2l0ZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1776474953),
('Oem1dbxyWxDKwpTunjN5OBfFYfIRO52TCd12rTTd', NULL, '41.41.107.23', 'WhatsApp/2.2613.101 W', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTmh1dWVNSElOWm1vcWoxbXM3S0Zydk9oblZqMFpyZmFrZTNGZHFzeCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM5OiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUvbG9naW4iO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776511838),
('SuQuJpswbpGbrCeImAIMhyBhOnpGPFCmFsi1hZs8', NULL, '146.190.37.60', 'Mozilla/5.0 (X11; Linux x86_64; rv:142.0) Gecko/20100101 Firefox/142.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQWczTmNKSHJoeHhxcjlsb1pqbEliVmFIQVNVTDFBamxwVEVEdGxWcSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM2OiJodHRwOi8vd3d3LmdlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478310),
('tLsCR2rI0OXiKVOs03BNpaOnkG34BVdjDeetXMkE', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTVNEbndlMFpuU29wNHhOTEF1bzBWNHdOMld2UHpGOThQR1dUYmJXNCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('uOutKtPVYDcTzjfhhwoigYBTrEM2rbRXhmzX3pnB', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTXVKNVpiWFI3a3ZLYjhqc3lsaE9NaXo1dXpyY0psM0E4dWR1eXM2UyI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070),
('X774MfQ1wB4xvYcVtsuT7YtcdG4ND8UAJ8YOKxZ1', NULL, '88.151.32.220', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:138.0) Gecko/20100101 Firefox/138.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiWGZHd21Hbm1nMTVQTWQ2cG9EWkFUa005VXg5dDFJUUx0U2dtN2FEUiI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776523214),
('YHj7hOWwiTHrEqXshj5Dcy5GusH86nLFrAGfXi8N', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiUkhwUWZNMGdYazFqTWY1aWROQWVVdmpOcHlYMlc1ZWhacEtTVUp5TSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478032),
('YoHKgiW206LVl78yiK8vx29U5jzOX6hf4IQpE2Y5', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ2Zqa1JiSUk3V1RINmtOalVtcVphbFhnU3RBbFc5cjR5cTA4WFMzNCI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478069),
('zUCNUay7CHEBILqVRMh55Q2KNuQGDnEY5e0OcxJA', NULL, '62.171.188.7', 'Mozilla/5.0 (compatible; VertexWP/1.0; +https://vertexwp.com/bot)', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZkxXcGtnNDV2UWI0MFZlRGxKWVQwZWdxTEhaOUtQSWR4M1RDQ21VaSI7czo2OiJsb2NhbGUiO3M6MjoiZW4iO3M6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjMzOiJodHRwczovL2dlZWJsZS50ZWFtcWVlbWF0ZWNoLnNpdGUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1776478070);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `key` varchar(255) NOT NULL,
  `value` text DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`, `type`, `created_at`, `updated_at`) VALUES
(1, 'app_name', 'GEEBLE', 'text', NULL, '2025-11-04 11:28:59'),
(2, 'app_logo', 'settings/8Nc503jJ3AumyERrQE27JoaVZNzQxbNnOTFDnnTK.png', 'image', NULL, '2026-02-23 15:14:56'),
(3, 'app_icon', 'settings/gaW4wfyfjCwmJxfh9wNkPfS92VPcPgH2F801X9hD.png', 'image', NULL, '2026-02-09 15:37:34'),
(4, 'invitation_discount_points', '100', 'text', NULL, NULL),
(5, 'shipping_cost', '10', 'text', NULL, '2025-09-29 07:33:32'),
(6, 'fb_link', 'https://www.facebook.com', 'text', NULL, '2025-09-29 07:33:32'),
(7, 'insta_link', 'ttps://www.instagram.com', 'text', NULL, '2025-09-29 07:33:32'),
(8, 'tik_tok_link', 'https://www.tiktok.com', 'text', NULL, '2025-09-29 07:33:32'),
(9, 'order_points_rate', '5', 'text', NULL, '2025-11-02 11:48:48'),
(10, 'inviter_order_points_rate', '2', 'text', NULL, '2025-11-02 11:48:48'),
(11, 'point_to_money_rate', '25', 'text', NULL, '2025-11-02 11:48:48'),
(12, 'allow_order_points', '1', 'text', NULL, '2025-11-02 12:27:33'),
(13, 'allow_inviter_order_points', '1', 'text', NULL, '2025-11-02 12:27:33'),
(14, 'max_points_discount_per_order', '100', 'text', NULL, NULL),
(15, 'allow_more_than_one_free_item', '1', 'text', NULL, NULL),
(16, 'allow_branch_admin_to_edit_stock', '1', 'text', '2025-12-09 12:12:36', '2025-12-11 13:45:57'),
(17, 'min_shipping_cost', '25', 'text', '2025-12-28 17:01:14', '2025-12-28 17:01:14'),
(18, 'delivery_man_calculation_method', 'percentage', 'text', '2025-12-28 17:01:14', '2025-12-28 17:01:14'),
(19, 'delivery_man_calculation_value', '2', 'text', '2025-12-28 17:01:14', '2025-12-28 17:01:14');

-- --------------------------------------------------------

--
-- Table structure for table `sliders`
--

CREATE TABLE `sliders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `image` varchar(255) NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sliders`
--

INSERT INTO `sliders` (`id`, `image`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'sliders/apMELqGyGrJC3qXDoBCZijNKtfo69W7I9PPkCVXB.jpg', 1, '2025-12-08 11:01:16', '2025-12-08 11:01:16'),
(2, 'sliders/wE03DtP5fh97zRO5Yf3IZ0bwUNV6PrIGUnd5lPbt.jpg', 1, '2025-12-08 11:01:24', '2025-12-08 11:01:24');

-- --------------------------------------------------------

--
-- Table structure for table `tickets`
--

CREATE TABLE `tickets` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `subject` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `attachments` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`attachments`)),
  `status` enum('open','solved','closed','hold') NOT NULL DEFAULT 'open',
  `ticket_from` enum('user','provider') NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tickets`
--

INSERT INTO `tickets` (`id`, `uuid`, `user_id`, `subject`, `description`, `attachments`, `status`, `ticket_from`, `created_at`, `updated_at`) VALUES
(7, '06ca8042-ced6-4841-ac61-93b9941c3518', 5, 'test', 'rrrrr', '[]', 'open', 'user', '2026-03-04 19:13:49', '2026-04-09 13:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_messages`
--

CREATE TABLE `ticket_messages` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `ticket_id` bigint(20) UNSIGNED NOT NULL,
  `sender_type` enum('user','provider','admin') NOT NULL,
  `sender_id` bigint(20) UNSIGNED NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `ticket_messages`
--

INSERT INTO `ticket_messages` (`id`, `ticket_id`, `sender_type`, `sender_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 7, 'admin', 1, 'jsldakjlsdlksa', '2026-04-08 13:26:48', '2026-04-08 13:26:48'),
(2, 7, 'admin', 1, 'انسايبلااشهمثتبلاشتهسلابليسلالش', '2026-04-09 13:24:53', '2026-04-09 13:24:53');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` char(36) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `payment_method` varchar(255) DEFAULT NULL,
  `amount` double NOT NULL,
  `currency` varchar(255) NOT NULL,
  `status` enum('pending','paid','faild') NOT NULL DEFAULT 'pending',
  `transaction_id` varchar(255) DEFAULT NULL,
  `reference_number` varchar(255) DEFAULT NULL,
  `raw_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`raw_response`)),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `uuid`, `user_id`, `order_id`, `payment_method`, `amount`, `currency`, `status`, `transaction_id`, `reference_number`, `raw_response`, `created_at`, `updated_at`, `deleted_at`) VALUES
(2, '909db4b3-1b5e-4ced-9e33-aa78fb82ab79', 5, 2, NULL, 205, 'EGP', 'paid', 'TRX123456', 'REF987654', NULL, '2025-12-09 07:42:03', '2025-12-10 10:36:41', NULL),
(3, '33ae43ce-7983-4ee8-ade4-8511e6926c24', 5, 3, NULL, 250, 'EGP', 'pending', NULL, NULL, NULL, '2025-12-09 07:45:25', '2025-12-09 07:45:25', NULL),
(5, '6fce1614-46b2-43d6-862a-a0fb1ae130cf', 5, 5, NULL, 690.06, 'EGP', 'pending', NULL, NULL, NULL, '2025-12-29 06:32:45', '2025-12-29 06:32:45', NULL),
(6, '240bafe1-5325-4350-84a0-14cb37d0cb21', 5, 6, NULL, 310.06, 'EGP', 'pending', NULL, NULL, NULL, '2025-12-29 07:07:07', '2025-12-29 07:07:07', NULL),
(7, '98f4b80c-9d88-4df3-a5bb-c815dd77e599', 5, 7, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2025-12-29 09:27:35', '2025-12-29 09:27:35', NULL),
(33, '26087020-aadb-4b7d-9020-3f4ce8a6ec23', 5, 33, NULL, 285.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-04 12:45:27', '2026-01-04 12:45:27', NULL),
(34, 'b4c6968b-c908-4b5b-a166-f5eab92f674c', 5, 34, NULL, 155.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-04 12:46:42', '2026-01-04 12:46:42', NULL),
(35, '97259e76-89d0-48ba-986e-9e5f7d24e02d', 5, 35, NULL, 155.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-04 12:48:55', '2026-01-04 12:48:55', NULL),
(36, 'e806c8fe-4e3f-4be5-810f-638ad5e0f1c2', 5, 36, NULL, 155.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-04 12:49:44', '2026-01-04 12:49:44', NULL),
(37, 'a3a2876d-454c-4311-8fad-f5b9a4de7390', 5, 37, NULL, 110.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-14 12:26:22', '2026-01-14 12:26:22', NULL),
(38, '7e037065-7d0f-4210-9ffb-54ba91b78f2b', 5, 38, NULL, 110.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-01-14 15:19:02', '2026-01-14 15:19:02', NULL),
(65, '108e612d-858c-4a47-aab3-734ee3d7b45e', 5, 65, NULL, 210.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-02 11:25:26', '2026-02-02 11:25:26', NULL),
(66, '6a7fefaa-d039-4c92-98b8-0510cc9916a0', 5, 66, NULL, 310.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-02 11:26:17', '2026-02-02 11:26:17', NULL),
(67, '34a22ba9-6042-49cf-887b-cbe85ab46853', 5, 67, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-02 11:26:26', '2026-02-02 11:26:26', NULL),
(68, '6b774efb-9e70-4d7d-8a57-4f10df66deb7', 5, 68, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-02 11:27:00', '2026-02-02 11:27:00', NULL),
(69, 'ff324cdc-8178-468f-a872-27645208ee3e', 5, 69, NULL, 470.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-03 13:38:54', '2026-02-03 13:38:54', NULL),
(70, '09271771-0f21-4c5a-ad2e-ba1b9127fbf3', 5, 70, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-03 13:40:28', '2026-02-03 13:40:28', NULL),
(72, 'b28601e7-b3c1-4424-9a75-13bfff3c23a3', 5, 72, NULL, 270.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 12:38:28', '2026-02-04 12:38:28', NULL),
(73, '2c11747c-1bef-4a11-9687-4c7427553b68', 5, 73, NULL, 47169.15, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 12:42:21', '2026-02-04 12:42:21', NULL),
(74, 'a21becdc-bd90-4f90-b18f-15d7cbee3896', 5, 74, NULL, 370.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 12:46:57', '2026-02-04 12:46:57', NULL),
(75, '1e7e29a6-720d-407c-b15e-d1f3ff140e6e', 5, 75, NULL, 90.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 12:56:14', '2026-02-04 12:56:14', NULL),
(76, '1e2e2aeb-6c03-4b11-b976-76f1bb734f72', 5, 76, NULL, 47175.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 13:26:41', '2026-02-04 13:26:41', NULL),
(77, 'd644bf78-ba3e-42a8-a142-b3781dda1e63', 19, 77, NULL, 47175.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 15:49:43', '2026-02-04 15:49:43', NULL),
(78, 'b5895cdf-5fa4-4ddd-889d-ed603a4916df', 5, 78, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 21:08:55', '2026-02-04 21:08:55', NULL),
(79, 'ce24ddde-e100-4496-876b-89e121a2375d', 5, 79, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 21:14:42', '2026-02-04 21:14:42', NULL),
(80, '8785e18b-805a-410c-a0f3-768a7ef6b007', 5, 80, NULL, 370.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-04 21:56:38', '2026-02-04 21:56:38', NULL),
(81, '20b66bb6-d13a-45d5-b155-ee32a04345d6', 5, 81, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-06 09:50:30', '2026-02-06 09:50:30', NULL),
(82, '5d0b08bb-78f4-4b4c-985b-964cf8967695', 5, 82, NULL, 110.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-06 20:42:25', '2026-02-06 20:42:25', NULL),
(98, '37682c42-ce7a-40d5-aa8c-f03a449644e4', 5, 98, NULL, 430.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:11:51', '2026-02-23 15:11:51', NULL),
(99, '91a1b9af-360b-4460-b10a-a0114fbd4bbd', 5, 99, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:12:10', '2026-02-23 15:12:10', NULL),
(100, '9c27f1ee-719e-4128-89c9-332106ced82d', 5, 100, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:12:22', '2026-02-23 15:12:22', NULL),
(101, 'b4f706cf-8fe8-40f0-8f93-17d0ff16cd8e', 5, 101, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:12:31', '2026-02-23 15:12:31', NULL),
(102, 'ccb5c614-3daa-419f-aaaf-1c3ea6da06c0', 5, 102, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:12:40', '2026-02-23 15:12:40', NULL),
(103, '11a0bd8a-6b83-4e98-8983-bdd86e1696be', 5, 103, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 15:12:50', '2026-02-23 15:12:50', NULL),
(104, 'b8659643-083d-4dc8-a6be-dce1ca264321', 20, 104, NULL, 135.88, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-23 18:18:45', '2026-02-23 18:18:45', NULL),
(105, '65caf1d5-d181-4b4f-93b1-41671457f088', 5, 105, NULL, 290.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-24 14:27:27', '2026-02-24 14:27:27', NULL),
(106, '4c437dcd-6617-482e-a726-4945151ed07c', 5, 106, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-24 14:27:33', '2026-02-24 14:27:33', NULL),
(107, '9eab21ea-7d30-4efa-a5a6-c44822b90cc4', 5, 107, NULL, 13562.18, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-25 19:03:28', '2026-02-25 19:03:28', NULL),
(108, '3170203b-a687-434c-a7de-80edd9d0bd0e', 5, 108, NULL, 270.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-25 19:42:41', '2026-02-25 19:42:41', NULL),
(109, '3bb4a4cf-1698-48c5-9241-3c22dde48ed3', 5, 109, NULL, 270.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-25 20:56:16', '2026-02-25 20:56:16', NULL),
(110, 'b24c13e3-bb54-4a11-8c37-4933b8eb0154', 5, 110, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 02:34:21', '2026-02-26 02:34:21', NULL),
(111, '88cb9f80-9478-4919-ade2-ecc7715f16aa', 5, 111, NULL, 270.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 09:51:48', '2026-02-26 09:51:48', NULL),
(112, 'a5b60fbb-ba3a-4b9b-b99d-c23fdf35e3fb', 5, 112, NULL, 150.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 10:06:12', '2026-02-26 10:06:12', NULL),
(113, '67f32037-09a8-4b2f-884e-703a78967012', 5, 113, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 13:01:37', '2026-02-26 13:01:37', NULL),
(114, '1e85f67f-ca9d-481a-b3ec-5f87bcb22c55', 5, 114, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 14:00:55', '2026-02-26 14:00:55', NULL),
(115, '2ea15229-a977-4652-94f4-7e669d36646c', 5, 115, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 14:01:02', '2026-02-26 14:01:02', NULL),
(116, 'cbd9f1a1-f0e0-4763-837f-76c6afcb4dda', 5, 116, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 14:01:11', '2026-02-26 14:01:11', NULL),
(117, 'f9b87dfa-fe43-4e21-b561-18f58b2ca198', 5, 117, NULL, 210.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 14:51:48', '2026-02-26 14:51:48', NULL),
(118, 'f379d6c4-bd3e-4466-a35e-458f1f00c570', 5, 118, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 22:35:41', '2026-02-26 22:35:41', NULL),
(119, '8cd7b22f-1197-497a-bb87-fda3a1f5e0e7', 5, 119, NULL, 110.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-26 23:06:17', '2026-02-26 23:06:17', NULL),
(120, 'ecab428e-379d-4044-8fae-6c42b81304d8', 5, 120, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-27 12:41:32', '2026-02-27 12:41:32', NULL),
(121, '5c40b8d8-2ccc-4091-9d55-cc08969a7251', 5, 121, NULL, 170.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-27 12:45:36', '2026-02-27 12:45:36', NULL),
(122, '4f1ce165-3c3d-4250-ab36-d2bff5ac422b', 5, 122, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-02-28 01:03:12', '2026-02-28 01:03:12', NULL),
(123, '17352ddf-1903-4063-95f1-1d3734a537dc', 5, 123, NULL, 310.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-02 22:16:08', '2026-03-02 22:16:08', NULL),
(124, '6e3557ff-aa1a-411c-a557-6b89b12426bf', 5, 124, NULL, 90.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-02 22:25:38', '2026-03-02 22:25:38', NULL),
(125, 'd4e7da9e-c2f4-4e8f-bca5-c3b43d87705e', 5, 125, NULL, 210.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-03 00:09:04', '2026-03-03 00:09:04', NULL),
(126, 'ff61e031-cba5-4fbf-80f3-483d7c92727c', 5, 126, NULL, 350.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-03 23:09:38', '2026-03-03 23:09:38', NULL),
(127, '3a09cd7a-fc6d-4a09-9f10-e2c89e58165a', 5, 127, NULL, 110.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-04 18:54:56', '2026-03-04 18:54:56', NULL),
(128, 'f14d0cc0-c3ed-45d7-b784-fea9afb80627', 5, 128, NULL, 90.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-04 19:11:26', '2026-03-04 19:11:26', NULL),
(129, '3c7bfe13-c768-4415-86bc-be19b653f7f1', 5, 129, NULL, 310.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-04 21:10:51', '2026-03-04 21:10:51', NULL),
(130, '7c06cb14-f653-497f-9c33-460d40d258ea', 5, 130, NULL, 330.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 01:04:58', '2026-03-05 01:04:58', NULL),
(131, '356a3561-5d37-492c-b06f-c31dbf271aac', 5, 131, NULL, 210.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 15:16:27', '2026-03-05 15:16:27', NULL),
(132, '717a723e-fa92-4959-a6de-d5505fe2e48d', 5, 132, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 15:16:34', '2026-03-05 15:16:34', NULL),
(133, '50a8e193-2343-4460-9f7d-c84725a95728', 5, 133, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 15:17:05', '2026-03-05 15:17:05', NULL),
(134, '37749b97-d05d-476c-801f-a90bc17ec777', 5, 134, NULL, 190.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 15:46:41', '2026-03-05 15:46:41', NULL),
(135, 'a5c0a046-b01b-40fd-85de-01e2dc68c2a0', 5, 135, NULL, 230.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 16:06:19', '2026-03-05 16:06:19', NULL),
(136, '6a751902-5237-4eb5-8d4b-832609f7f860', 5, 136, NULL, 370.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 16:31:57', '2026-03-05 16:31:57', NULL),
(137, 'c8489c20-bfc5-4500-b3f5-41102331abcc', 5, 137, NULL, 210.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-05 16:33:26', '2026-03-05 16:33:26', NULL),
(138, 'd5c7a69f-a36a-4877-9994-f9cfa055b4c7', 5, 138, NULL, 290.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-16 17:20:12', '2026-03-16 17:20:12', NULL),
(139, '19ec50ce-87d3-4eb0-be6c-a0131009b55f', 5, 139, NULL, 130.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-03-23 13:41:15', '2026-03-23 13:41:15', NULL),
(156, 'fbdce6bc-9403-4594-9be5-1df727fdf083', 5, 156, NULL, 87.86, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-14 12:45:57', '2026-04-14 12:45:57', NULL),
(157, '16e269fd-cad9-4d1e-a1d8-aae8cf157181', 5, 157, NULL, 90.06, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-15 07:50:00', '2026-04-15 07:50:00', NULL),
(162, '226c7667-582f-439a-9b91-00e2e03a7607', 31, 162, NULL, 47275.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-18 11:59:57', '2026-04-18 11:59:57', NULL),
(163, '16b9a230-371d-47c5-b3a6-93dcdaf53eed', 31, 163, NULL, 47095.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-18 13:00:32', '2026-04-18 13:00:32', NULL),
(164, '0bdbcd14-7269-4f85-85ac-88c2884af49e', 32, 164, NULL, 183.87, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-18 16:08:04', '2026-04-18 16:08:04', NULL),
(165, '789a1684-70ab-481f-a589-4d2f53a70041', 31, 165, NULL, 47495.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-18 16:14:36', '2026-04-18 16:14:36', NULL),
(166, '89e1550e-8842-4d8e-adf7-19afb2ec78db', 31, 166, NULL, 47135.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-19 15:47:22', '2026-04-19 15:47:22', NULL),
(167, '10554252-3ead-4c6e-b82d-f8a1e014a482', 31, 167, NULL, 47335.68, 'EGP', 'pending', NULL, NULL, NULL, '2026-04-19 15:55:22', '2026-04-19 15:55:22', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `code` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`code`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `code`, `is_active`, `created_at`, `updated_at`) VALUES
(1, '{\"ar\": \"قطعة\", \"en\": \"Piece\"}', '\"PC\"', 1, '2025-12-07 13:19:51', '2025-12-07 13:19:51'),
(2, '{\"ar\": \"كيلو جرام\", \"en\": \"Kilo Gram\"}', '\"KG\"', 1, '2025-12-07 13:20:10', '2025-12-07 13:20:10'),
(3, '{\"ar\": \"لتر\", \"en\": \"Liter\"}', '\"L\"', 1, '2025-12-07 13:20:31', '2025-12-07 13:20:31'),
(4, '{\"ar\": \"عبوة\", \"en\": \"Package\"}', '\"PKG\"', 1, '2025-12-07 13:20:56', '2025-12-07 13:20:56');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `gender` enum('male','female') DEFAULT NULL,
  `invitation_code` varchar(255) DEFAULT NULL,
  `invited_by` bigint(20) UNSIGNED DEFAULT NULL,
  `invited_count` int(11) NOT NULL DEFAULT 0,
  `has_invitation_discount` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `is_verified` tinyint(1) NOT NULL DEFAULT 1,
  `points` decimal(10,2) NOT NULL DEFAULT 0.00,
  `verification_code` varchar(255) DEFAULT NULL,
  `verification_code_expire` datetime DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `role` enum('user','admin','employee','delivery') NOT NULL DEFAULT 'user',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `fcm_token` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `gender`, `invitation_code`, `invited_by`, `invited_count`, `has_invitation_discount`, `is_active`, `is_verified`, `points`, `verification_code`, `verification_code_expire`, `image`, `role`, `branch_id`, `email_verified_at`, `fcm_token`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'Super Admin', 'admin@admin.com', '0123456789', '$2y$12$N1Gyjiut88XKfuSXZbjkdueNatLduRsL.o8TKA9G18SYeM4hLFHCq', 'male', NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'admin', NULL, NULL, NULL, NULL, '2025-09-23 04:07:04', '2025-09-23 04:07:04', NULL),
(2, 'Islam El Shater', 'Islam@geeble.com', '+96420111201101', '$2y$12$1MX35eBb/xYlF5ojhkVjK.Czg2dLVFA7.dhnbrFDo10lS/zBEqTJC', NULL, '368781', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'employee', 1, NULL, NULL, NULL, '2025-12-08 10:52:10', '2025-12-08 10:54:18', NULL),
(3, 'Salah Mohsen', 'salah@geeble.com', '+20155502202', '$2y$12$kTya/xs7Nx4jkXq.H8D8bO0j0yJHr.LVIS2RU/6btc9a/zCERieFm', NULL, '928598', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'employee', 2, NULL, NULL, NULL, '2025-12-08 10:53:16', '2025-12-08 10:53:16', NULL),
(4, 'Marwan Attia', 'marwan@geeble.com', '+2012002215', '$2y$12$vRI6zOfUZzHWYfNkUO.Jsu/tPxQ/eDiqrJHt9Yrm1/SFMbT5OIVxK', NULL, '451225', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'employee', 3, NULL, NULL, NULL, '2025-12-08 10:54:03', '2025-12-08 10:54:03', NULL),
(5, 'Ahmed dd', 'test@user.com', '+20123456789', '$2y$12$wZo3cNrwnbFOt..YN4Ru7OmC5.k206G8BRO5uugqMyDO1czYsJpqS', 'male', '600613', NULL, 0, 0, 1, 1, 350.00, NULL, NULL, 'uploads/users/OFyosSjpegF9mto58SbHNvNtXA6RTQ3sfuWgy9Jd.webp', 'user', NULL, NULL, NULL, NULL, '2025-12-08 12:01:17', '2026-04-15 07:50:00', NULL),
(6, 'Islam Mohareb', 'mohareb@geeble.com', '+20120355021', '$2y$12$VrV8HAjz7yfjWeTg/.bnKeOQI5BiV8xonYsj9jRzzU13dsTTYiXum', NULL, '736145', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, 'uploads/users/obYSyR0il1u8rRrdOwNu7p7XhBjOLV1JCThhVZrC.webp', 'employee', 1, NULL, NULL, NULL, '2025-12-09 12:18:06', '2025-12-09 12:18:06', NULL),
(9, 'Test Delivery d', 'test@delivery.com', '+0123012032', '$2y$12$zO2PFk/KH9D8bpBR4T9pZuf0VGOz6nDa4SqDBM6iOPsKLK81dF3YG', 'male', NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, 'uploads/users/gdWg8mnBRcKlA9z3i3IqvYlHtlFZCeXIcOBk8GRX.jpg', 'delivery', 1, NULL, NULL, NULL, '2025-12-28 12:49:21', '2026-01-14 11:41:42', NULL),
(10, 'Delivery General', 'delivery@general.com', '+0123012031', '$2y$12$EutwRfvMoLPjwFG5FCo2Y.5zNgKz0xDK/40FaULIWbHyDmf/YlciO', NULL, NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'delivery', NULL, NULL, NULL, NULL, '2025-12-28 13:38:23', '2025-12-28 13:38:23', NULL),
(11, 'Test Delivery 2', 'test@del2.com', '+20100210010', '$2y$12$wjZ2Mh13PT86RWmG7qzUIe3BT7G.HReQaXfeGElGNRmekiglGM8My', NULL, NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'delivery', 2, NULL, NULL, NULL, '2025-12-29 06:25:32', '2025-12-29 06:25:32', NULL),
(12, 'fsdfdfs', 'sdds@gjhdgfs.com', '1234567897', '$2y$12$36zpoCUuYNFe2fOAhZVir.Ezsxv5/KjnW5QlG2OfVcvst0bMZBua2', 'male', NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, 'uploads/users/Dg1Dm5wcc6xrAzNak666CEhwdNaysY3jWHkX6lli.jpg', 'delivery', 1, NULL, NULL, NULL, '2025-12-29 06:58:43', '2026-02-22 15:26:29', NULL),
(13, 'Test Del By Api2', 'test-del@api2.com', '+20103030202', '$2y$12$lctxx5Vx/axrXsPs9oqUVO/Kn/DlgKkCP9SNjmp3/SMyAoy3Z0T4K', NULL, NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'delivery', 1, NULL, NULL, NULL, '2025-12-29 07:02:06', '2025-12-29 07:02:06', NULL),
(14, 'delivry', 'delivery@gamil.com', '1234567891', '$2y$12$Q318mnEv9fAx9aZwGQAjk.u1MfL4mZpYuMyXVISij0EgPEGb0xJxC', NULL, NULL, NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'delivery', 1, NULL, NULL, NULL, '2026-02-02 09:58:27', '2026-02-02 09:58:27', NULL),
(15, 'test user', 'test@user.com4', '+20123456701', '$2y$12$Ycuj23OouW52YTrfics9Nu4veSPhMds.e6WC95aOMk/pb1y/hYefu', 'male', '231877', NULL, 0, 0, 0, 0, 0.00, '123456', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-02-04 15:36:07', '2026-02-04 15:36:07', NULL),
(16, 'test', 'test@test.com4', '12355465654', '$2y$12$AIy3DovlJogTAhTxLidRsO2jGV2jlUlyY2.ydexmqZDIvU3h4dY8.', NULL, '126274', NULL, 0, 0, 0, 0, 0.00, '123456', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-02-04 15:40:54', '2026-02-04 15:40:54', NULL),
(17, 'test', 'test@test.com41', '712355465654', '$2y$12$PNnb1z4/CoSk6ATMPx.TyO9gXLZykS9VkDkI1Ym9tOQjvl.WkJkBK', NULL, '109771', NULL, 0, 0, 0, 0, 0.00, '123456', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-02-04 15:45:07', '2026-02-04 15:45:07', NULL),
(18, 'fgdf', 'testfgf@jhghj.dsjd', '563635654', '$2y$12$x3/gvJCPkR092o4tQRYCwOS567hVjFfXHPWktb3nR8HhG9MOgnUC.', NULL, '454664', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-02-04 15:46:07', '2026-02-04 15:46:12', NULL),
(19, 'rtrter', 'ttt@ggfg.dkjd', '123456789', '$2y$12$ic3YUpRtlNX8DM/ALBUD..98RacpFM4Dsb17oy06n2biwu08uzfq.', NULL, '184584', NULL, 0, 0, 1, 1, 5.00, NULL, NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-02-04 15:48:43', '2026-02-04 15:49:44', NULL),
(20, 'ayat', 'ayatahmedd8@gmail.com', '010155252525', '$2y$12$Ozc.QTq8DGFM9qmdJGpbxObAkWMjb15qKmdSTueqqGqukPKDgpGcO', NULL, '143867', NULL, 0, 0, 1, 1, 1.00, NULL, NULL, 'uploads/users/RhlznM5rRGokbI4Kd5Atv5Sw8aIL3QmibZZBDbZO.jpg', 'user', NULL, NULL, NULL, NULL, '2026-02-23 17:21:05', '2026-02-23 18:18:45', NULL),
(22, 'test user', 'testt@user.com4', '+20123456707', '$2y$12$Cfzf3duWKk7tN9v25htwmOg/mFxKFzOK.nCmVgN4YB.MikfnAeVFO', 'male', '670405', NULL, 0, 0, 1, 0, 0.00, '271870', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-14 12:44:04', '2026-04-14 12:44:04', NULL),
(23, 'test user', NULL, '+20123456708', '$2y$12$THbAzcalllXJLEv2L7kFfu16VE5TJqEyGutA5/onfDtc8vgwWBIUO', 'male', '201743', NULL, 0, 0, 1, 0, 0.00, '763801', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 07:35:29', '2026-04-15 07:35:29', NULL),
(24, 'test', NULL, '0101234567', '$2y$12$PvhDEB6GDN6KpG1ApeVjQu3JLGoDYBSRYIZZel6F8h5Sl2/qvWmSq', NULL, '387962', NULL, 0, 0, 1, 0, 0.00, '518441', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 07:38:54', '2026-04-15 07:38:54', NULL),
(25, 'test', NULL, '01234567897', '$2y$12$r41DVb3YzzZt8Kv1T26JGu7Xc.4L36WILZBD6ftPG5nz6ToCqrK3G', NULL, '901377', NULL, 0, 0, 1, 0, 0.00, '372371', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 07:45:00', '2026-04-15 07:45:00', NULL),
(26, 'ttt', NULL, '01234567892', '$2y$12$tH4aUeEQ0ReI6iaXU/RjCe5Ms3pBB3UQhBXlK9UO1XUj6Ze9Zn1E2', NULL, '417714', NULL, 0, 0, 1, 0, 0.00, '689039', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 09:47:28', '2026-04-15 09:47:28', NULL),
(27, 'ttt', NULL, '07739004060', '$2y$12$bcoT8jtBg3C/aCwwuuS1sOgfLJQjwALgOCMgP4URD165FXkRFUyvG', NULL, '921942', NULL, 0, 0, 1, 0, 0.00, '269351', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 10:38:15', '2026-04-15 10:38:15', NULL),
(28, 'ttttt', NULL, '97407739004060', '$2y$12$00aGJULKEzu.f31SR0nUd.XsOoV4nyg6YdQS.0DyyRZ02HTIV0H8K', NULL, '653688', NULL, 0, 0, 1, 0, 0.00, '940375', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 11:51:27', '2026-04-15 11:51:27', NULL),
(29, 'tttt', NULL, '96407739004060', '$2y$12$fGbDxHTKn9lIQaBCbRQkdumsFQrJG/KbstxGrooR7CSThprnCcccO', NULL, '383854', NULL, 0, 0, 1, 0, 0.00, '776134', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 12:27:02', '2026-04-15 12:27:02', NULL),
(30, 'rttty', NULL, '+964077390040601', '$2y$12$Ok7we15pWa/tbXwzNpG2L.sS3.TNkqxAXGNx63iLzAVLg/Gy/JQN.', NULL, '140428', NULL, 0, 0, 1, 0, 0.00, '785685', NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 12:28:48', '2026-04-15 12:28:48', NULL),
(31, 'ttt', NULL, '+96407739004060', '$2y$12$7cKvFH5dWMf38dyUpMPa7OvCGrVAuX3xWQbig6/lgr0JrgFnn2LW2', NULL, '132756', NULL, 0, 0, 1, 1, 48.00, NULL, NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-15 13:01:58', '2026-04-19 15:55:22', NULL),
(32, 'علي', NULL, '++96407512148568', '$2y$12$lZxGlUw0hJbbRlImbY7n4.DpMYSB9aDmhIkLpg5F2VH9OVaDaF6PW', NULL, '921047', NULL, 0, 0, 1, 1, 7.00, NULL, NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-18 16:04:08', '2026-04-18 16:08:04', NULL),
(33, 'omar', NULL, '++96407739004060', '$2y$12$gSDD3Zgm6KRRZiDcOTVX7u6RPpYvaNRKeHKJWY00VgxiHPQmR4TwC', NULL, '630426', NULL, 0, 0, 1, 1, 0.00, NULL, NULL, NULL, 'user', NULL, NULL, NULL, NULL, '2026-04-18 16:23:56', '2026-04-18 16:24:12', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variants`
--

CREATE TABLE `variants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `type` enum('text','number','select','radio','checkbox') NOT NULL DEFAULT 'text',
  `is_required` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variants`
--

INSERT INTO `variants` (`id`, `name`, `type`, `is_required`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, '{\"ar\": \"المقاس\", \"en\": \"Size\"}', 'select', 1, 1, '2025-12-07 12:19:51', '2025-12-07 12:19:51', NULL),
(2, '{\"ar\": \"اللون\", \"en\": \"Color\"}', 'select', 0, 1, '2025-12-07 12:20:50', '2025-12-07 12:20:50', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variant_options`
--

CREATE TABLE `variant_options` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `variant_id` bigint(20) UNSIGNED NOT NULL,
  `name` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`name`)),
  `code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `variant_options`
--

INSERT INTO `variant_options` (`id`, `variant_id`, `name`, `code`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '{\"ar\": \"صغير\", \"en\": \"S\"}', 'S', '2025-12-07 12:19:51', '2025-12-07 12:19:51', NULL),
(2, 1, '{\"ar\": \"متوسط\", \"en\": \"M\"}', 'M', '2025-12-07 12:19:51', '2025-12-07 12:19:51', NULL),
(3, 1, '{\"ar\": \"كبير\", \"en\": \"L\"}', 'L', '2025-12-07 12:19:51', '2025-12-07 12:19:51', NULL),
(4, 1, '{\"ar\": \"كبير جدًا\", \"en\": \"XL\"}', 'XL', '2025-12-07 12:19:51', '2025-12-07 12:19:51', NULL),
(5, 2, '{\"ar\": \"أحمر\", \"en\": \"Red\"}', 'r', '2025-12-07 12:20:51', '2025-12-10 08:31:24', NULL),
(6, 2, '{\"ar\": \"أخضر\", \"en\": \"Green\"}', 'g', '2025-12-07 12:20:51', '2025-12-10 08:31:24', NULL),
(7, 2, '{\"ar\": \"أسود\", \"en\": \"Black\"}', 'b', '2025-12-07 12:20:51', '2025-12-10 08:31:24', NULL),
(10, 2, '{\"ar\": \"أصفر\", \"en\": \"Yellow\"}', 'y', '2025-12-10 08:33:39', '2025-12-10 08:33:39', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `zones`
--

CREATE TABLE `zones` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `polygon` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`polygon`)),
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `zones`
--

INSERT INTO `zones` (`id`, `name`, `polygon`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'مدينة نصر', '[{\"lat\":30.079098012570576,\"lng\":31.363465154159226},{\"lat\":30.079692188708584,\"lng\":31.388176259710782},{\"lat\":30.073750266733434,\"lng\":31.39023551850675},{\"lat\":30.023526782542312,\"lng\":31.34561824459423},{\"lat\":30.01668966540237,\"lng\":31.341156517202972},{\"lat\":30.029174480234655,\"lng\":31.311297264661494},{\"lat\":30.056813831474937,\"lng\":31.303403439276988},{\"lat\":30.06513384602442,\"lng\":31.32022071944399}]', 1, '2026-04-08 15:29:21', '2026-04-08 15:29:21'),
(2, 'القاهرة الجديدة', '[{\"lat\":30.09980711869981,\"lng\":31.585481872347913},{\"lat\":30.052865754681957,\"lng\":31.610879397498113},{\"lat\":29.92797672908815,\"lng\":31.527822626060942},{\"lat\":29.966649641822855,\"lng\":31.472909058168607},{\"lat\":29.952372163807098,\"lng\":31.437901658637234},{\"lat\":29.976166821071917,\"lng\":31.354844887200098},{\"lat\":30.033845195081867,\"lng\":31.40495351790186},{\"lat\":30.079607236447764,\"lng\":31.436528819439953},{\"lat\":30.096836806592247,\"lng\":31.481832512951105}]', 1, '2026-04-08 15:29:55', '2026-04-08 15:29:55');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `addresses`
--
ALTER TABLE `addresses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `addresses_user_id_foreign` (`user_id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `attributes_code_unique` (`code`);

--
-- Indexes for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD PRIMARY KEY (`id`),
  ADD KEY `attribute_options_attribute_id_foreign` (`attribute_id`);

--
-- Indexes for table `booking_lists`
--
ALTER TABLE `booking_lists`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_lists_product_id_foreign` (`product_id`),
  ADD KEY `booking_lists_user_id_foreign` (`user_id`);

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `branches_slug_unique` (`slug`);

--
-- Indexes for table `branch_product_stocks`
--
ALTER TABLE `branch_product_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_product_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `branch_product_stocks_product_id_foreign` (`product_id`);

--
-- Indexes for table `branch_stock_history`
--
ALTER TABLE `branch_stock_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_stock_history_branch_id_foreign` (`branch_id`),
  ADD KEY `branch_stock_history_product_id_foreign` (`product_id`),
  ADD KEY `branch_stock_history_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `branch_stock_history_order_id_foreign` (`order_id`),
  ADD KEY `branch_stock_history_user_id_foreign` (`user_id`);

--
-- Indexes for table `branch_variant_stocks`
--
ALTER TABLE `branch_variant_stocks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `branch_variant_stocks_branch_id_foreign` (`branch_id`),
  ADD KEY `branch_variant_stocks_product_variant_id_foreign` (`product_variant_id`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cart_items_user_id_product_id_unique` (`user_id`,`product_id`,`variant_id`) USING BTREE,
  ADD KEY `cart_items_product_id_foreign` (`product_id`),
  ADD KEY `cart_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `categories_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `category_product`
--
ALTER TABLE `category_product`
  ADD UNIQUE KEY `category_product_product_id_category_id_unique` (`product_id`,`category_id`),
  ADD KEY `category_product_category_id_foreign` (`category_id`);

--
-- Indexes for table `coupons`
--
ALTER TABLE `coupons`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `coupons_code_unique` (`code`);

--
-- Indexes for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_usages_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_usages_user_id_foreign` (`user_id`),
  ADD KEY `coupon_usages_order_id_foreign` (`order_id`);

--
-- Indexes for table `coupon_user`
--
ALTER TABLE `coupon_user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coupon_user_coupon_id_foreign` (`coupon_id`),
  ADD KEY `coupon_user_user_id_foreign` (`user_id`);

--
-- Indexes for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `deliveries_user_id_foreign` (`user_id`),
  ADD KEY `deliveries_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `delivery_wallet_history`
--
ALTER TABLE `delivery_wallet_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `delivery_wallet_history_delivery_id_foreign` (`delivery_id`),
  ADD KEY `delivery_wallet_history_order_id_foreign` (`order_id`);

--
-- Indexes for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `delivery_zone_delivery_id_zone_id_unique` (`delivery_id`,`zone_id`),
  ADD KEY `delivery_zone_zone_id_foreign` (`zone_id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `favorites`
--
ALTER TABLE `favorites`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `favorites_user_id_product_id_unique` (`user_id`,`product_id`),
  ADD KEY `favorites_product_id_foreign` (`product_id`);

--
-- Indexes for table `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Indexes for table `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`model_id`,`model_type`),
  ADD KEY `model_has_permissions_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD PRIMARY KEY (`role_id`,`model_id`,`model_type`),
  ADD KEY `model_has_roles_model_id_model_type_index` (`model_id`,`model_type`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notifications_notifiable_type_notifiable_id_index` (`notifiable_type`,`notifiable_id`);

--
-- Indexes for table `offers`
--
ALTER TABLE `offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `offers_type_index` (`type`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_uuid_unique` (`uuid`),
  ADD KEY `orders_user_id_foreign` (`user_id`),
  ADD KEY `orders_coupon_id_foreign` (`coupon_id`),
  ADD KEY `orders_shipping_address_id_foreign` (`shipping_address_id`),
  ADD KEY `orders_billing_address_id_foreign` (`billing_address_id`),
  ADD KEY `orders_offer_id_foreign` (`offer_id`),
  ADD KEY `orders_branch_id_foreign` (`branch_id`),
  ADD KEY `orders_delivery_id_foreign` (`delivery_id`);

--
-- Indexes for table `order_comments`
--
ALTER TABLE `order_comments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_comments_order_id_foreign` (`order_id`),
  ADD KEY `order_comments_user_id_foreign` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_items_order_id_foreign` (`order_id`),
  ADD KEY `order_items_product_id_foreign` (`product_id`),
  ADD KEY `order_items_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`phone`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`),
  ADD KEY `personal_access_tokens_expires_at_index` (`expires_at`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_slug_unique` (`slug`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD KEY `products_unit_id_foreign` (`unit_id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_attribute_values_product_id_foreign` (`product_id`),
  ADD KEY `product_attribute_values_attribute_id_foreign` (`attribute_id`),
  ADD KEY `product_attribute_values_attribute_option_id_foreign` (`attribute_option_id`);

--
-- Indexes for table `product_images`
--
ALTER TABLE `product_images`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_images_imageable_id_imageable_type_index` (`imageable_id`,`imageable_type`);

--
-- Indexes for table `product_relations`
--
ALTER TABLE `product_relations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_relations_product_id_foreign` (`product_id`),
  ADD KEY `product_relations_related_product_id_foreign` (`related_product_id`);

--
-- Indexes for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_variants_slug_unique` (`slug`),
  ADD UNIQUE KEY `product_variants_sku_unique` (`sku`),
  ADD KEY `product_variants_product_id_foreign` (`product_id`);

--
-- Indexes for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_variant_values_product_variant_id_foreign` (`product_variant_id`),
  ADD KEY `product_variant_values_variant_option_id_foreign` (`variant_option_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_product_id_foreign` (`product_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_guard_name_unique` (`name`,`guard_name`);

--
-- Indexes for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD PRIMARY KEY (`permission_id`,`role_id`),
  ADD KEY `role_has_permissions_role_id_foreign` (`role_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sliders`
--
ALTER TABLE `sliders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tickets`
--
ALTER TABLE `tickets`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tickets_uuid_unique` (`uuid`),
  ADD KEY `tickets_user_id_foreign` (`user_id`);

--
-- Indexes for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ticket_messages_ticket_id_foreign` (`ticket_id`),
  ADD KEY `ticket_messages_sender_id_foreign` (`sender_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `transactions_uuid_unique` (`uuid`),
  ADD KEY `transactions_user_id_foreign` (`user_id`),
  ADD KEY `transactions_order_id_foreign` (`order_id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD UNIQUE KEY `users_invitation_code_unique` (`invitation_code`),
  ADD KEY `users_invited_by_foreign` (`invited_by`),
  ADD KEY `users_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `variants`
--
ALTER TABLE `variants`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `variant_options_code_unique` (`code`),
  ADD KEY `variant_options_variant_id_foreign` (`variant_id`);

--
-- Indexes for table `zones`
--
ALTER TABLE `zones`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `addresses`
--
ALTER TABLE `addresses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `attribute_options`
--
ALTER TABLE `attribute_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `booking_lists`
--
ALTER TABLE `booking_lists`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `branch_product_stocks`
--
ALTER TABLE `branch_product_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `branch_stock_history`
--
ALTER TABLE `branch_stock_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- AUTO_INCREMENT for table `branch_variant_stocks`
--
ALTER TABLE `branch_variant_stocks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `coupons`
--
ALTER TABLE `coupons`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `coupon_user`
--
ALTER TABLE `coupon_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `deliveries`
--
ALTER TABLE `deliveries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `delivery_wallet_history`
--
ALTER TABLE `delivery_wallet_history`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `favorites`
--
ALTER TABLE `favorites`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=42;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=108;

--
-- AUTO_INCREMENT for table `offers`
--
ALTER TABLE `offers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `order_comments`
--
ALTER TABLE `order_comments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=274;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=343;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `product_images`
--
ALTER TABLE `product_images`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `product_relations`
--
ALTER TABLE `product_relations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_variants`
--
ALTER TABLE `product_variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `sliders`
--
ALTER TABLE `sliders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tickets`
--
ALTER TABLE `tickets`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=168;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `variants`
--
ALTER TABLE `variants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `variant_options`
--
ALTER TABLE `variant_options`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `zones`
--
ALTER TABLE `zones`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `addresses`
--
ALTER TABLE `addresses`
  ADD CONSTRAINT `addresses_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `attribute_options`
--
ALTER TABLE `attribute_options`
  ADD CONSTRAINT `attribute_options_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `booking_lists`
--
ALTER TABLE `booking_lists`
  ADD CONSTRAINT `booking_lists_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_lists_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_product_stocks`
--
ALTER TABLE `branch_product_stocks`
  ADD CONSTRAINT `branch_product_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_product_stocks_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `branch_stock_history`
--
ALTER TABLE `branch_stock_history`
  ADD CONSTRAINT `branch_stock_history_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_stock_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `branch_stock_history_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_stock_history_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_stock_history_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `branch_variant_stocks`
--
ALTER TABLE `branch_variant_stocks`
  ADD CONSTRAINT `branch_variant_stocks_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `branch_variant_stocks_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `cart_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cart_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `categories`
--
ALTER TABLE `categories`
  ADD CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `category_product`
--
ALTER TABLE `category_product`
  ADD CONSTRAINT `category_product_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `category_product_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_usages`
--
ALTER TABLE `coupon_usages`
  ADD CONSTRAINT `coupon_usages_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_usages_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `coupon_user`
--
ALTER TABLE `coupon_user`
  ADD CONSTRAINT `coupon_user_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `coupon_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `deliveries`
--
ALTER TABLE `deliveries`
  ADD CONSTRAINT `deliveries_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `deliveries_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `delivery_wallet_history`
--
ALTER TABLE `delivery_wallet_history`
  ADD CONSTRAINT `delivery_wallet_history_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_wallet_history_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `delivery_zone`
--
ALTER TABLE `delivery_zone`
  ADD CONSTRAINT `delivery_zone_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `delivery_zone_zone_id_foreign` FOREIGN KEY (`zone_id`) REFERENCES `zones` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `favorites`
--
ALTER TABLE `favorites`
  ADD CONSTRAINT `favorites_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `favorites_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_permissions`
--
ALTER TABLE `model_has_permissions`
  ADD CONSTRAINT `model_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `model_has_roles`
--
ALTER TABLE `model_has_roles`
  ADD CONSTRAINT `model_has_roles_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_billing_address_id_foreign` FOREIGN KEY (`billing_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_coupon_id_foreign` FOREIGN KEY (`coupon_id`) REFERENCES `coupons` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_delivery_id_foreign` FOREIGN KEY (`delivery_id`) REFERENCES `deliveries` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_offer_id_foreign` FOREIGN KEY (`offer_id`) REFERENCES `offers` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_shipping_address_id_foreign` FOREIGN KEY (`shipping_address_id`) REFERENCES `addresses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `orders_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_comments`
--
ALTER TABLE `order_comments`
  ADD CONSTRAINT `order_comments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_comments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `order_items_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_items_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `product_variants` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_unit_id_foreign` FOREIGN KEY (`unit_id`) REFERENCES `units` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
  ADD CONSTRAINT `product_attribute_values_attribute_id_foreign` FOREIGN KEY (`attribute_id`) REFERENCES `attributes` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_attribute_values_attribute_option_id_foreign` FOREIGN KEY (`attribute_option_id`) REFERENCES `attribute_options` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `product_attribute_values_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_relations`
--
ALTER TABLE `product_relations`
  ADD CONSTRAINT `product_relations_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_relations_related_product_id_foreign` FOREIGN KEY (`related_product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variants`
--
ALTER TABLE `product_variants`
  ADD CONSTRAINT `product_variants_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `product_variant_values`
--
ALTER TABLE `product_variant_values`
  ADD CONSTRAINT `product_variant_values_product_variant_id_foreign` FOREIGN KEY (`product_variant_id`) REFERENCES `product_variants` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `product_variant_values_variant_option_id_foreign` FOREIGN KEY (`variant_option_id`) REFERENCES `variant_options` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `tickets`
--
ALTER TABLE `tickets`
  ADD CONSTRAINT `tickets_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ticket_messages`
--
ALTER TABLE `ticket_messages`
  ADD CONSTRAINT `ticket_messages_sender_id_foreign` FOREIGN KEY (`sender_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `ticket_messages_ticket_id_foreign` FOREIGN KEY (`ticket_id`) REFERENCES `tickets` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `users_invited_by_foreign` FOREIGN KEY (`invited_by`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `variant_options`
--
ALTER TABLE `variant_options`
  ADD CONSTRAINT `variant_options_variant_id_foreign` FOREIGN KEY (`variant_id`) REFERENCES `variants` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
