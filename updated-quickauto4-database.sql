-- phpMyAdmin SQL Dump
-- version 4.9.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Nov 22, 2023 at 09:15 AM
-- Server version: 10.4.10-MariaDB
-- PHP Version: 8.1.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `ebayproject`
--

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE IF NOT EXISTS `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `item_specifics`
--

DROP TABLE IF EXISTS `item_specifics`;
CREATE TABLE IF NOT EXISTS `item_specifics` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) DEFAULT NULL,
  `name1` varchar(255) DEFAULT NULL,
  `value1` varchar(255) DEFAULT NULL,
  `name2` varchar(255) DEFAULT NULL,
  `value2` varchar(255) DEFAULT NULL,
  `name3` varchar(255) DEFAULT NULL,
  `value3` varchar(255) DEFAULT NULL,
  `name4` varchar(255) DEFAULT NULL,
  `value4` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `item_specifics`
--

INSERT INTO `item_specifics` (`id`, `site_id`, `name1`, `value1`, `name2`, `value2`, `name3`, `value3`, `name4`, `value4`, `created_at`, `updated_at`) VALUES
(2, 101, 'Marca', '123', 'Dimensione schermo', '15 inches', 'Processore', 'Intel Core i5', 'Tipo', 'Mobile', '2023-11-16 08:51:48', '2023-11-16 08:51:48'),
(3, 71, 'Marque', '123', 'Taille de l\'écran', '15 inches', 'Processeur', 'Intel Core i5', 'produit', 'Mobile', '2023-11-16 08:51:48', '2023-11-16 08:51:48'),
(4, 77, 'Marke', '123', 'Bildschirmgröße', '15 inches', 'Prozessor', 'Intel Core i5', 'Produkt', 'Mobile', '2023-11-16 08:51:48', '2023-11-16 08:51:48');

-- --------------------------------------------------------

--
-- Table structure for table `marketplaces`
--

DROP TABLE IF EXISTS `marketplaces`;
CREATE TABLE IF NOT EXISTS `marketplaces` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) NOT NULL,
  `site_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `site_code` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_currency` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplaces`
--

INSERT INTO `marketplaces` (`id`, `site_id`, `site_name`, `site_code`, `site_currency`, `created_at`, `updated_at`) VALUES
(1, 0, 'United States', 'USA', 'USD', NULL, NULL),
(2, 2, 'Canada (English)', 'CAN', 'CAD', NULL, NULL),
(3, 3, 'UK', 'GBR', 'GBP', NULL, NULL),
(4, 15, 'Australia', 'AUD', NULL, NULL, NULL),
(5, 16, 'Austria', NULL, NULL, NULL, NULL),
(6, 23, 'Belgium (French)', 'BEL', 'EUR', NULL, NULL),
(7, 71, 'France', 'FRA', 'EUR', NULL, NULL),
(8, 77, 'Germany', 'DEU', 'EUR', NULL, NULL),
(9, 186, 'Spain', 'ESP', 'EUR', NULL, NULL),
(10, 193, 'Switzerland', NULL, NULL, NULL, NULL),
(11, 101, 'Italy', 'ITA', 'EUR', NULL, NULL),
(12, 123, 'Belgium (Dutch)', NULL, NULL, NULL, NULL),
(13, 146, 'Netherlands', NULL, NULL, NULL, NULL),
(14, 201, 'Hong Kong', NULL, NULL, NULL, NULL),
(15, 203, 'India', NULL, NULL, NULL, NULL),
(16, 205, 'Ireland', NULL, NULL, NULL, NULL),
(17, 207, 'Malaysia', 'MYS', 'MYR', NULL, NULL),
(18, 210, 'Canada (French)', NULL, NULL, NULL, NULL),
(19, 211, 'Philippines', NULL, NULL, NULL, NULL),
(20, 212, 'Poland', NULL, NULL, NULL, NULL),
(21, 216, 'Singapore', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `marketplace_detail`
--

DROP TABLE IF EXISTS `marketplace_detail`;
CREATE TABLE IF NOT EXISTS `marketplace_detail` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `site_id` bigint(20) UNSIGNED DEFAULT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `shipping_service_cost` decimal(10,2) DEFAULT NULL,
  `free_shipping` tinyint(1) DEFAULT NULL,
  `shipping_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dispatch_time_max` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `returns_accepted_option` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `returns_accepted` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `marketplace_detail_site_id_foreign` (`site_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `marketplace_detail`
--

INSERT INTO `marketplace_detail` (`id`, `site_id`, `location`, `shipping_service`, `shipping_service_cost`, `free_shipping`, `shipping_type`, `dispatch_time_max`, `returns_accepted_option`, `returns_accepted`, `created_at`, `updated_at`) VALUES
(6, 71, '70123, Paris', 'FR_HomeDelivery', '0.00', 1, 'Flat', '3', 'ReturnsAccepted', 'ReturnsAccepted', '2023-11-20 04:28:38', '2023-11-20 04:28:38'),
(5, 101, '10024, Turin', 'IT_HomeDelivery', '0.00', 1, 'Flat', '3', 'ReturnsAccepted', 'ReturnsAccepted', '2023-11-20 04:30:13', '2023-11-20 04:30:13'),
(4, 77, '10115, Berlin', 'DE_HomeDelivery', '0.00', 1, 'Flat', '3', 'ReturnsAccepted', 'ReturnsAccepted', '2023-11-20 04:28:38', '2023-11-20 04:28:38');

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(8, '2014_10_12_000000_create_users_table', 1),
(9, '2014_10_12_100000_create_password_reset_tokens_table', 1),
(10, '2019_08_19_000000_create_failed_jobs_table', 1),
(11, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(12, '2023_10_21_111545_create_tiendas_table', 1),
(13, '2023_10_30_125013_create_source_stores_table', 1),
(14, '2023_11_02_113416_create_marketplaces_table', 2),
(15, '2023_11_02_113519_create_source_marketplaces_table', 2),
(16, '2023_11_02_113549_create_target_marketplaces_table', 2),
(17, '2023_11_14_120516_create_marketplace_detail_table', 3);

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE IF NOT EXISTS `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `personal_access_tokens`
--

DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE IF NOT EXISTS `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `source_marketplaces`
--

DROP TABLE IF EXISTS `source_marketplaces`;
CREATE TABLE IF NOT EXISTS `source_marketplaces` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `store_id` bigint(20) NOT NULL,
  `site_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `source_marketplaces`
--

INSERT INTO `source_marketplaces` (`id`, `store_id`, `site_id`, `created_at`, `updated_at`) VALUES
(17, 8, 186, '2023-11-20 04:27:21', '2023-11-20 04:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `target_marketplaces`
--

DROP TABLE IF EXISTS `target_marketplaces`;
CREATE TABLE IF NOT EXISTS `target_marketplaces` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `source_id` bigint(20) NOT NULL,
  `site_id` bigint(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=42 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `target_marketplaces`
--

INSERT INTO `target_marketplaces` (`id`, `source_id`, `site_id`, `created_at`, `updated_at`) VALUES
(40, 17, 101, '2023-11-20 04:27:21', '2023-11-20 04:27:21'),
(39, 17, 77, '2023-11-20 04:27:21', '2023-11-20 04:27:21'),
(41, 17, 71, '2023-11-20 04:27:21', '2023-11-20 04:27:21');

-- --------------------------------------------------------

--
-- Table structure for table `tiendas`
--

DROP TABLE IF EXISTS `tiendas`;
CREATE TABLE IF NOT EXISTS `tiendas` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `access_token` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `store_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marketplaces` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tiendas`
--

INSERT INTO `tiendas` (`id`, `access_token`, `store_name`, `language`, `marketplaces`, `created_at`, `updated_at`) VALUES
(8, 'v^1.1#i^1#f^0#I^3#p^3#r^1#t^Ul4xMF8xOjk4QTEwMzI0REVGNkI2QkZCRjcwRUQ1NTNFRjYxNDc3XzNfMSNFXjI2MA==', 'quickauto', NULL, 186, '2023-11-20 04:26:51', '2023-11-20 04:26:51');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
