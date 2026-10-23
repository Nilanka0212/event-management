-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Oct 23, 2026 at 05:51 AM
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
-- Database: `event_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `banks`
--

CREATE TABLE `banks` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `bank_name` varchar(255) NOT NULL,
  `account_name` varchar(255) NOT NULL,
  `account_number` varchar(255) NOT NULL,
  `branch` varchar(255) DEFAULT NULL,
  `swift_code` varchar(50) DEFAULT NULL,
  `contact_number` varchar(50) DEFAULT NULL,
  `opening_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `current_balance` decimal(15,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `banks`
--

INSERT INTO `banks` (`id`, `bank_name`, `account_name`, `account_number`, `branch`, `swift_code`, `contact_number`, `opening_balance`, `current_balance`, `is_active`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'Commercial Bank of Ceylon', 'Pubudu Ishantha', '8910023', 'Kandy', NULL, NULL, 10000.00, 10000.00, 1, NULL, '2026-10-21 01:34:33', '2026-10-21 01:44:00');

-- --------------------------------------------------------

--
-- Table structure for table `bank_transactions`
--

CREATE TABLE `bank_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_no` varchar(255) NOT NULL,
  `type` enum('income','expense') NOT NULL,
  `transaction_method` enum('cash','cheque','bank_transfer') NOT NULL,
  `account_type` varchar(255) NOT NULL DEFAULT 'cash',
  `amount` decimal(12,2) NOT NULL,
  `description` varchar(255) NOT NULL,
  `reference_no` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `transaction_date` date NOT NULL,
  `running_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `bank_transactions`
--

INSERT INTO `bank_transactions` (`id`, `transaction_no`, `type`, `transaction_method`, `account_type`, `amount`, `description`, `reference_no`, `cheque_no`, `bank_name`, `transaction_date`, `running_balance`, `notes`, `created_at`, `updated_at`) VALUES
(1, 'TXN-000001', 'income', 'cash', 'cash', 50000.00, 'Opening Cash Book Balance', 'OP-CASH-001', NULL, NULL, '2026-10-01', 0.00, NULL, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 'TXN-000002', 'income', 'bank_transfer', 'Commercial Bank', 250000.00, 'Opening Bank Balance', 'OP-BANK-001', NULL, 'Commercial Bank of Ceylon', '2026-10-01', 0.00, NULL, '2026-10-16 03:59:00', '2026-10-16 03:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `company_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `phone2` varchar(255) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(255) DEFAULT NULL,
  `postal_code` varchar(255) DEFAULT NULL,
  `nic` varchar(255) DEFAULT NULL,
  `dob` date DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `lead_source` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `customers`
--

INSERT INTO `customers` (`id`, `name`, `company_name`, `email`, `phone`, `phone2`, `address`, `city`, `postal_code`, `nic`, `dob`, `notes`, `lead_source`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Kasun Perera', NULL, 'kasun.p@example.com', '+94 77 555 1234', NULL, 'No 45, Galle Road, Colombo 03', NULL, NULL, '199214502841', NULL, NULL, NULL, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 'Nadeeka Senanayake', NULL, 'nadeeka.s@example.com', '+94 71 888 4321', NULL, '12/B Kandy Road, Kiribathgoda', NULL, NULL, '198865401923', NULL, NULL, NULL, 1, '2026-10-16 03:59:00', '2026-10-21 00:33:21');

-- --------------------------------------------------------

--
-- Table structure for table `events`
--

CREATE TABLE `events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `event_sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `event_name` varchar(255) NOT NULL,
  `event_date` date NOT NULL,
  `today_date` date NOT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `package_cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `package_sell_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `additional_services_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `other_charges_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sub_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `advance_payment` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  `notification_7_sent` tinyint(1) NOT NULL DEFAULT 0,
  `notification_3_sent` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `events`
--

INSERT INTO `events` (`id`, `event_no`, `customer_id`, `event_sub_category_id`, `event_name`, `event_date`, `today_date`, `venue`, `remarks`, `package_cost_price`, `package_sell_price`, `additional_services_total`, `other_charges_total`, `sub_total`, `discount`, `total_amount`, `advance_payment`, `balance`, `status`, `notification_7_sent`, `notification_3_sent`, `created_at`, `updated_at`) VALUES
(1, 'EV-000001', 1, 1, 'sd', '2026-10-19', '2026-10-16', 'kandy', NULL, 125000.00, 180000.00, 40000.00, 1000.00, 221000.00, 0.00, 221000.00, 0.00, 221000.00, 'confirmed', 0, 0, '2026-10-16 05:46:01', '2026-10-16 05:46:01'),
(2, 'EV-000002', 2, 3, 'new Event', '2026-10-20', '2026-10-16', NULL, NULL, 90000.00, 135000.00, 15000.00, 0.00, 150000.00, 0.00, 150000.00, 0.00, 150000.00, 'pending', 0, 0, '2026-10-16 06:16:20', '2026-10-16 06:16:20'),
(3, 'EV-000003', 1, 3, 'PARTY NEW', '2026-10-21', '2026-10-16', NULL, NULL, 90000.00, 135000.00, 0.00, 0.00, 135000.00, 0.00, 135000.00, 0.00, 135000.00, 'confirmed', 0, 0, '2026-10-16 06:17:13', '2026-10-16 06:17:13'),
(4, 'EV-000004', 1, 3, 'kjkjjkk', '2026-10-27', '2026-10-16', 'fgh', NULL, 90000.00, 135000.00, 0.00, 0.00, 135000.00, 0.00, 135000.00, 0.00, 135000.00, 'confirmed', 0, 0, '2026-10-16 06:18:20', '2026-10-16 06:18:20'),
(5, 'EV-000005', 2, 3, 'f', '2026-10-30', '2026-10-16', NULL, NULL, 90000.00, 135000.00, 0.00, 0.00, 135000.00, 0.00, 135000.00, 0.00, 135000.00, 'confirmed', 0, 0, '2026-10-16 06:24:37', '2026-10-16 06:24:37');

-- --------------------------------------------------------

--
-- Table structure for table `event_categories`
--

CREATE TABLE `event_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_categories`
--

INSERT INTO `event_categories` (`id`, `name`, `description`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Wedding', 'Weddings, Receptions and Engagements', 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 'Birthday Party', 'Milestone birthdays, kids parties and celebrations', 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(3, 'Corporate Event', 'Annual general meetings, launches, gala dinners', 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `event_other_charges`
--

CREATE TABLE `event_other_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_other_charges`
--

INSERT INTO `event_other_charges` (`id`, `event_id`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'transport', 1000.00, '2026-10-16 05:46:01', '2026-10-16 05:46:01');

-- --------------------------------------------------------

--
-- Table structure for table `event_services`
--

CREATE TABLE `event_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_services`
--

INSERT INTO `event_services` (`id`, `event_id`, `service_id`, `description`, `qty`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 'LED Wall & Dynamic Lighting', 1, 40000.00, 40000.00, '2026-10-16 05:46:01', '2026-10-16 05:46:01'),
(2, 2, 1, 'Welcome Drink', 1, 15000.00, 15000.00, '2026-10-16 06:16:20', '2026-10-16 06:16:20');

-- --------------------------------------------------------

--
-- Table structure for table `event_sub_categories`
--

CREATE TABLE `event_sub_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_category_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `sell_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_sub_categories`
--

INSERT INTO `event_sub_categories` (`id`, `event_category_id`, `name`, `description`, `cost_price`, `sell_price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'Package 01 - Gold Wedding', 'Complete elegant wedding package with welcoming and traditional performances.', 125000.00, 180000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 1, 'Package 02 - Platinum Luxury Wedding', 'Ultra luxury all-inclusive wedding package with photo/video and LED screen.', 210000.00, 320000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(3, 2, 'Package 01 - Standard Birthday', 'Fun birthday celebration package with sound, decor and drinks.', 90000.00, 135000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `event_sub_category_services`
--

CREATE TABLE `event_sub_category_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `event_sub_category_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED NOT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `qty` int(11) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `event_sub_category_services`
--

INSERT INTO `event_sub_category_services` (`id`, `event_sub_category_id`, `service_id`, `cost_price`, `qty`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 15000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 1, 2, 35000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(3, 1, 3, 25000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(4, 1, 5, 50000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(5, 2, 1, 15000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(6, 2, 2, 35000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(7, 2, 3, 25000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(8, 2, 5, 50000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(9, 2, 4, 45000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(10, 2, 6, 40000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(11, 3, 1, 15000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(12, 3, 3, 25000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(13, 3, 5, 50000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00');

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `expense_no` varchar(255) NOT NULL,
  `type` enum('direct','normal') NOT NULL,
  `category` varchar(255) DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `bank_name` varchar(255) DEFAULT NULL,
  `cheque_no` varchar(255) DEFAULT NULL,
  `expense_date` date NOT NULL,
  `event_id` bigint(20) UNSIGNED DEFAULT NULL,
  `order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reference` varchar(255) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expense_categories`
--

CREATE TABLE `expense_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `status` enum('active','inactive') NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `expense_categories`
--

INSERT INTO `expense_categories` (`id`, `name`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Office Rent', 'Office space lease and building rent', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(2, 'Electricity & Water', 'Monthly utilities bills', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(3, 'Transport & Fuel', 'Vehicle fuel and logistics', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(4, 'Staff Wages & Overtime', 'Operational staff daily/weekly payouts', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(5, 'Food & Refreshments', 'Staff tea, snacks, and meals', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(6, 'Stationery & Printing', 'Paper, toner, and office stationery', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(7, 'Equipment Maintenance', 'Repair and servicing of equipment', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(8, 'Advertising & Marketing', 'Social media promotions and printing ads', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(9, 'Internet & Phone', 'Broadband and mobile phone charges', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22'),
(10, 'Miscellaneous', 'General minor overhead expenses', 'active', '2026-10-21 03:51:22', '2026-10-21 03:51:22');

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
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_10_15_060806_create_services_table', 1),
(5, '2026_10_15_060807_create_event_categories_table', 1),
(6, '2026_10_15_060807_create_event_sub_categories_table', 1),
(7, '2026_10_15_060808_create_customers_table', 1),
(8, '2026_10_15_060808_create_event_sub_category_services_table', 1),
(9, '2026_10_15_060809_create_events_table', 1),
(10, '2026_10_15_060810_create_event_services_table', 1),
(11, '2026_10_15_060811_create_event_other_charges_table', 1),
(12, '2026_10_15_060821_create_orders_table', 1),
(13, '2026_10_15_060822_create_order_other_charges_table', 1),
(14, '2026_10_15_060822_create_order_services_table', 1),
(15, '2026_10_15_060822_create_payments_table', 1),
(16, '2026_10_15_060823_create_quotations_table', 1),
(17, '2026_10_15_060824_create_quotation_services_table', 1),
(18, '2026_10_15_060825_create_quotation_other_charges_table', 1),
(19, '2026_10_15_060826_create_expenses_table', 1),
(20, '2026_10_15_060827_create_bank_transactions_table', 1),
(21, '2026_10_16_000001_add_role_and_phone_to_users_table', 2),
(22, '2026_10_16_000002_add_username_to_users_table', 3),
(23, '2026_10_21_054052_add_details_to_customers_table', 4),
(24, '2026_10_21_065903_create_banks_table', 5),
(25, '2026_10_21_144600_create_expense_categories_table', 6),
(26, '2026_10_21_144700_add_order_and_payment_to_expenses_table', 6);

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_no` varchar(255) NOT NULL,
  `event_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `order_date` date NOT NULL,
  `sub_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `paid_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `payment_status` enum('pending','partial','paid') NOT NULL DEFAULT 'pending',
  `notes` text DEFAULT NULL,
  `is_auto_created` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `order_no`, `event_id`, `customer_id`, `order_date`, `sub_total`, `discount`, `total_amount`, `paid_amount`, `balance`, `payment_status`, `notes`, `is_auto_created`, `created_at`, `updated_at`) VALUES
(1, 'ORD-000001', 1, 1, '2026-10-19', 221000.00, 0.00, 221000.00, 0.00, 221000.00, 'pending', NULL, 0, '2026-10-19 01:45:11', '2026-10-19 01:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_other_charges`
--

CREATE TABLE `order_other_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_other_charges`
--

INSERT INTO `order_other_charges` (`id`, `order_id`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'transport', 1000.00, '2026-10-19 01:45:11', '2026-10-19 01:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `order_services`
--

CREATE TABLE `order_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `order_services`
--

INSERT INTO `order_services` (`id`, `order_id`, `service_id`, `description`, `qty`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'Package 01 - Gold Wedding (Base Event Package)', 1, 180000.00, 180000.00, '2026-10-19 01:45:11', '2026-10-19 01:45:11'),
(2, 1, 6, 'LED Wall & Dynamic Lighting', 1, 40000.00, 40000.00, '2026-10-19 01:45:11', '2026-10-19 01:45:11');

-- --------------------------------------------------------

--
-- Table structure for table `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_no` varchar(255) NOT NULL,
  `order_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `payment_type` enum('advance','partial','full') NOT NULL,
  `payment_method` enum('cash','cheque','bank_transfer') NOT NULL DEFAULT 'cash',
  `cheque_no` varchar(255) DEFAULT NULL,
  `bank_name` varchar(255) DEFAULT NULL,
  `payment_date` date NOT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `quotations`
--

CREATE TABLE `quotations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_no` varchar(255) NOT NULL,
  `customer_id` bigint(20) UNSIGNED NOT NULL,
  `event_sub_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `quotation_date` date NOT NULL,
  `valid_until` date DEFAULT NULL,
  `event_name` varchar(255) DEFAULT NULL,
  `event_date` date DEFAULT NULL,
  `venue` varchar(255) DEFAULT NULL,
  `sub_total` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `status` enum('draft','sent','accepted','rejected') NOT NULL DEFAULT 'draft',
  `remarks` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotations`
--

INSERT INTO `quotations` (`id`, `quotation_no`, `customer_id`, `event_sub_category_id`, `quotation_date`, `valid_until`, `event_name`, `event_date`, `venue`, `sub_total`, `discount`, `total_amount`, `status`, `remarks`, `created_at`, `updated_at`) VALUES
(1, 'QUO-000001', 2, 3, '2026-10-20', '2026-11-03', 'Birthday Party in Kandy', '2026-11-07', 'Kandy Hotel', 92000.00, 200.00, 91800.00, 'accepted', 'this is new', '2026-10-20 00:14:13', '2026-10-20 00:37:08');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_other_charges`
--

CREATE TABLE `quotation_other_charges` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `description` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_other_charges`
--

INSERT INTO `quotation_other_charges` (`id`, `quotation_id`, `description`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 'For Transport Products', 2000.00, '2026-10-20 00:14:13', '2026-10-20 00:14:13');

-- --------------------------------------------------------

--
-- Table structure for table `quotation_services`
--

CREATE TABLE `quotation_services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `quotation_id` bigint(20) UNSIGNED NOT NULL,
  `service_id` bigint(20) UNSIGNED DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `qty` int(11) NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `quotation_services`
--

INSERT INTO `quotation_services` (`id`, `quotation_id`, `service_id`, `description`, `qty`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
(1, 1, 3, 'DJ & Sound System', 1, 25000.00, 25000.00, '2026-10-20 00:14:13', '2026-10-20 00:14:13'),
(2, 1, 5, 'Floral Stage Decoration', 1, 50000.00, 50000.00, '2026-10-20 00:14:13', '2026-10-20 00:14:13'),
(3, 1, 1, 'Welcome Drink', 1, 15000.00, 15000.00, '2026-10-20 00:14:13', '2026-10-20 00:14:13');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `description`, `cost_price`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'Welcome Drink', 'Fresh fruit juices and welcome mocktails (100 Pax)', 15000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(2, 'Traditional Dance Crew', '6-member traditional dancing troupe for grand entrance', 35000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(3, 'DJ & Sound System', 'Pro sound equipment, microphones and experienced DJ', 25000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(4, 'Photography & Videography', '2 photographers, 1 videographer with drone shots', 45000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(5, 'Floral Stage Decoration', 'Fresh flowers backdrop, entrance arch and table centerpieces', 50000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(6, 'LED Wall & Dynamic Lighting', '12x8 LED backdrop screen with moving heads lighting', 40000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00'),
(7, 'Buffet Catering Service', '3-course buffet with desserts and beverage counter (100 Pax)', 120000.00, 1, '2026-10-16 03:59:00', '2026-10-16 03:59:00');

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
('4QzJzZx7tfFIWl0nyHgaJ5H3pMDwzgM7YMMleTcn', NULL, '127.0.0.1', 'curl/8.21.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoidUtiTEhQR2FudjhKVDFzS1pwd3dMQmdLb0g4SnA5dlp5TGZxc2hxaSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3JlcG9ydHMvZXhwZW5zZXMiO31zOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozODoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL3JlcG9ydHMvZXhwZW5zZXMiO3M6NToicm91dGUiO3M6MTY6InJlcG9ydHMuZXhwZW5zZXMiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792574505),
('7KGxKPMg9lH4UBcBcDvj39dvfmA0zEVpfSIj8rrk', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:158.0) Gecko/20100101 Firefox/158.0', 'YTo4OntzOjY6Il90b2tlbiI7czo0MDoiUUZHZVV4UE9pYTg4VFNWekY0emdvM25QaE4wVWFrMU1rZVp3TGlKYSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MTE5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvYXBpL2NhbGVuZGFyL2V2ZW50cz9lbmQ9MjAyNi0xMS0wOFQwMCUzQTAwJTNBMDAlMkIwNSUzQTMwJnN0YXJ0PTIwMjYtMDktMjdUMDAlM0EwMCUzQTAwJTJCMDUlM0EzMCI7czo1OiJyb3V0ZSI7czoxOToiYXBpLmNhbGVuZGFyLmV2ZW50cyI7fXM6NTA6ImxvZ2luX3dlYl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxNzoicGFzc3dvcmRfaGFzaF93ZWIiO3M6NjQ6ImJjZjYyNGY2YzhjZmQwNjM5YWMzNGJlZmM1MzNlYTc5NmQ5YjQ0NjkxZjdiZGUwNWU1NjIwNzI4NjU0NDZkYTUiO3M6ODoibG9naW5faXAiO3M6OToiMTI3LjAuMC4xIjtzOjE2OiJsb2dpbl91c2VyX2FnZW50IjtzOjgwOiJNb3ppbGxhLzUuMCAoV2luZG93cyBOVCAxMC4wOyBXaW42NDsgeDY0OyBydjoxNTguMCkgR2Vja28vMjAxMDAxMDEgRmlyZWZveC8xNTguMCI7czoxODoibGFzdF9hY3Rpdml0eV90aW1lIjtpOjE3OTI2NDA0ODI7fQ==', 1792640482),
('enLPpN96MO2tQeBEUP3XU0ux03t2L9ZTL4RlTiP2', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:158.0) Gecko/20100101 Firefox/158.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiTzNtTXBCNnR6U2daYXdDdGNoQzNtY2xiTFZaS29PamhMaGVDVXJYTCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czozOToiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2JhbmstdHJhbnNhY3Rpb25zIjtzOjU6InJvdXRlIjtzOjIzOiJiYW5rLXRyYW5zYWN0aW9ucy5pbmRleCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1792583222),
('nUomZF5S5iWLKX7KU0uy05qWqsJECD4ChF4ZDe8m', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:158.0) Gecko/20100101 Firefox/158.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiM3dVQnY1NTZqQ1o0QThNcG5jUjlmRGRmSmRGMmRFMllpaVlQMGN2NCI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE4OiJsYXN0X2FjdGl2aXR5X3RpbWUiO2k6MTc5MjY1MzgxNjtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2FsZW5kYXIvZXZlbnRzP2VuZD0yMDI2LTExLTA4VDAwJTNBMDAlM0EwMCUyQjA1JTNBMzAmc3RhcnQ9MjAyNi0wOS0yN1QwMCUzQTAwJTNBMDAlMkIwNSUzQTMwIjtzOjU6InJvdXRlIjtzOjE5OiJhcGkuY2FsZW5kYXIuZXZlbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1792653817),
('o66NhGPX1b5zK0o74EX2e6mlatwsNZdbHNS9uoSa', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:158.0) Gecko/20100101 Firefox/158.0', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiMkhhOUJRY2E1VmljODA2ZXpObzJ5bUpnWU1jY1c0TWUxQzVreFJzdiI7czo1MDoibG9naW5fd2ViXzU5YmEzNmFkZGMyYjJmOTQwMTU4MGYwMTRjN2Y1OGVhNGUzMDk4OWQiO2k6MTtzOjE4OiJsYXN0X2FjdGl2aXR5X3RpbWUiO2k6MTc5MjY2ODI2ODtzOjk6Il9wcmV2aW91cyI7YToyOntzOjM6InVybCI7czoxMTk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hcGkvY2FsZW5kYXIvZXZlbnRzP2VuZD0yMDI2LTExLTA4VDAwJTNBMDAlM0EwMCUyQjA1JTNBMzAmc3RhcnQ9MjAyNi0wOS0yN1QwMCUzQTAwJTNBMDAlMkIwNSUzQTMwIjtzOjU6InJvdXRlIjtzOjE5OiJhcGkuY2FsZW5kYXIuZXZlbnRzIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1792668268),
('ZrZLs8XL3lnmffwROVaYroZR9hnbAZ0yoYqTo57G', NULL, '127.0.0.1', 'curl/8.21.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiSnplYXlHbjNIa09TenRpUE5ta1Bnd3RvM2F6WTQ1RG9jVENmczl2RSI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMDoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL2V4cGVuc2VzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBlbnNlcyI7czo1OiJyb3V0ZSI7czoxNDoiZXhwZW5zZXMuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1792574474);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'Staff',
  `phone` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `username`, `email`, `role`, `phone`, `is_active`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'System Administrator', 'admin', 'admin@eventpro.com', 'Admin', '+94 77 123 4567', 1, NULL, '$2y$12$8XDBMJmlB520CgCxisPq6.b5LVBsDyCG9dYcXycceJ8Rc5VkyKLSu', '7vPCuL3OPQV9E1YCZ6jNnXGKJDMn3dNESwRPe0FxQbH0VVsydd3d6cKp2UYn', '2026-10-16 03:58:08', '2026-10-16 03:58:08'),
(2, 'Event Manager', 'manager', 'manager@eventpro.com', 'Manager', '+94 71 987 6543', 0, NULL, '$2y$12$.XeQs96EpuXTMiKm8bqEu.Ii/pYuqyfOirYUB9Dku5NuuS..kwujq', NULL, '2026-10-16 03:58:09', '2026-10-19 00:36:31');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `banks`
--
ALTER TABLE `banks`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bank_transactions_transaction_no_unique` (`transaction_no`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Indexes for table `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `events`
--
ALTER TABLE `events`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `events_event_no_unique` (`event_no`),
  ADD KEY `events_customer_id_foreign` (`customer_id`),
  ADD KEY `events_event_sub_category_id_foreign` (`event_sub_category_id`);

--
-- Indexes for table `event_categories`
--
ALTER TABLE `event_categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `event_other_charges`
--
ALTER TABLE `event_other_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_other_charges_event_id_foreign` (`event_id`);

--
-- Indexes for table `event_services`
--
ALTER TABLE `event_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_services_event_id_foreign` (`event_id`),
  ADD KEY `event_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `event_sub_categories`
--
ALTER TABLE `event_sub_categories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_sub_categories_event_category_id_foreign` (`event_category_id`);

--
-- Indexes for table `event_sub_category_services`
--
ALTER TABLE `event_sub_category_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `event_sub_category_services_event_sub_category_id_foreign` (`event_sub_category_id`),
  ADD KEY `event_sub_category_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expenses_expense_no_unique` (`expense_no`),
  ADD KEY `expenses_event_id_foreign` (`event_id`),
  ADD KEY `expenses_order_id_foreign` (`order_id`);

--
-- Indexes for table `expense_categories`
--
ALTER TABLE `expense_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `expense_categories_name_unique` (`name`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

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
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `orders_order_no_unique` (`order_no`),
  ADD KEY `orders_event_id_foreign` (`event_id`),
  ADD KEY `orders_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `order_other_charges`
--
ALTER TABLE `order_other_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_other_charges_order_id_foreign` (`order_id`);

--
-- Indexes for table `order_services`
--
ALTER TABLE `order_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `order_services_order_id_foreign` (`order_id`),
  ADD KEY `order_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payments_payment_no_unique` (`payment_no`),
  ADD KEY `payments_order_id_foreign` (`order_id`),
  ADD KEY `payments_customer_id_foreign` (`customer_id`);

--
-- Indexes for table `quotations`
--
ALTER TABLE `quotations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `quotations_quotation_no_unique` (`quotation_no`),
  ADD KEY `quotations_customer_id_foreign` (`customer_id`),
  ADD KEY `quotations_event_sub_category_id_foreign` (`event_sub_category_id`);

--
-- Indexes for table `quotation_other_charges`
--
ALTER TABLE `quotation_other_charges`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_other_charges_quotation_id_foreign` (`quotation_id`);

--
-- Indexes for table `quotation_services`
--
ALTER TABLE `quotation_services`
  ADD PRIMARY KEY (`id`),
  ADD KEY `quotation_services_quotation_id_foreign` (`quotation_id`),
  ADD KEY `quotation_services_service_id_foreign` (`service_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_username_unique` (`username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `banks`
--
ALTER TABLE `banks`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `bank_transactions`
--
ALTER TABLE `bank_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `events`
--
ALTER TABLE `events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `event_categories`
--
ALTER TABLE `event_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_other_charges`
--
ALTER TABLE `event_other_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `event_services`
--
ALTER TABLE `event_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `event_sub_categories`
--
ALTER TABLE `event_sub_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `event_sub_category_services`
--
ALTER TABLE `event_sub_category_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expense_categories`
--
ALTER TABLE `expense_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_other_charges`
--
ALTER TABLE `order_other_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `order_services`
--
ALTER TABLE `order_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `quotations`
--
ALTER TABLE `quotations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quotation_other_charges`
--
ALTER TABLE `quotation_other_charges`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `quotation_services`
--
ALTER TABLE `quotation_services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `events`
--
ALTER TABLE `events`
  ADD CONSTRAINT `events_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `events_event_sub_category_id_foreign` FOREIGN KEY (`event_sub_category_id`) REFERENCES `event_sub_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_other_charges`
--
ALTER TABLE `event_other_charges`
  ADD CONSTRAINT `event_other_charges_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_services`
--
ALTER TABLE `event_services`
  ADD CONSTRAINT `event_services_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `event_sub_categories`
--
ALTER TABLE `event_sub_categories`
  ADD CONSTRAINT `event_sub_categories_event_category_id_foreign` FOREIGN KEY (`event_category_id`) REFERENCES `event_categories` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `event_sub_category_services`
--
ALTER TABLE `event_sub_category_services`
  ADD CONSTRAINT `event_sub_category_services_event_sub_category_id_foreign` FOREIGN KEY (`event_sub_category_id`) REFERENCES `event_sub_categories` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `event_sub_category_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `expenses`
--
ALTER TABLE `expenses`
  ADD CONSTRAINT `expenses_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `expenses_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `orders_event_id_foreign` FOREIGN KEY (`event_id`) REFERENCES `events` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_other_charges`
--
ALTER TABLE `order_other_charges`
  ADD CONSTRAINT `order_other_charges_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `order_services`
--
ALTER TABLE `order_services`
  ADD CONSTRAINT `order_services_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `order_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `payments_order_id_foreign` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotations`
--
ALTER TABLE `quotations`
  ADD CONSTRAINT `quotations_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotations_event_sub_category_id_foreign` FOREIGN KEY (`event_sub_category_id`) REFERENCES `event_sub_categories` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `quotation_other_charges`
--
ALTER TABLE `quotation_other_charges`
  ADD CONSTRAINT `quotation_other_charges_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `quotation_services`
--
ALTER TABLE `quotation_services`
  ADD CONSTRAINT `quotation_services_quotation_id_foreign` FOREIGN KEY (`quotation_id`) REFERENCES `quotations` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `quotation_services_service_id_foreign` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
