-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 20, 2025 at 04:47 AM
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
-- Database: `thesis`
--

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
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_name` varchar(255) NOT NULL,
  `is_available` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`id`, `file_name`, `is_available`, `created_at`, `updated_at`) VALUES
(1, 'Units Earned', 1, '2025-02-26 22:54:40', '2025-04-09 03:15:39'),
(2, 'Grades (per semester/term)', 1, '2025-03-25 08:38:45', '2025-04-09 03:16:10'),
(3, 'Grades (all terms attended)', 1, '2025-03-25 08:38:56', '2025-04-09 03:18:43'),
(4, 'General Weighted Average', 1, '2025-03-26 05:15:49', '2025-04-09 03:19:35'),
(5, 'Academic Completions', 1, '2025-03-26 05:16:02', '2025-04-09 03:20:00'),
(6, 'Graduation', 1, '2025-03-26 05:16:33', '2025-04-09 03:20:28'),
(7, 'As a candidate for Graduation', 1, '2025-04-09 03:20:46', '2025-04-09 03:20:52'),
(8, 'As Honor Graduation', 1, '2025-04-09 03:21:16', '2025-04-09 03:21:16'),
(9, 'Subjects Enrolled / Curriculum', 1, '2025-04-09 03:21:46', '2025-04-09 03:21:46'),
(10, 'Enrollment / Registration', 1, '2025-04-09 03:22:18', '2025-04-09 03:22:18'),
(11, 'English as a Medium of Instruction', 1, '2025-04-09 03:22:40', '2025-04-09 03:22:40'),
(12, 'Course Description (maximum of 5 per certification)', 1, '2025-04-09 03:23:07', '2025-04-09 03:23:07'),
(13, 'Transcript of Records (1st time Requests)', 1, '2025-04-09 03:23:26', '2025-04-09 08:33:00'),
(14, 'Original Diploma', 1, '2025-04-09 03:23:39', '2025-04-09 03:23:39'),
(15, 'Copy of Diploma', 1, '2025-04-09 03:23:49', '2025-04-09 03:23:49'),
(16, 'Form 137', 1, '2025-04-09 03:24:00', '2025-04-09 03:24:50'),
(17, 'Related Learning Experience', 1, '2025-04-09 03:24:18', '2025-04-09 03:24:18'),
(18, 'Certification / Authentication / Verification', 1, '2025-04-09 03:24:42', '2025-04-09 03:24:42'),
(19, 'Copy of Transcript of Records', 1, '2025-04-09 08:33:31', '2025-04-09 08:33:31'),
(20, 'Transfer Credentials (Honorable dismissal with Certificate of Grades)', 1, '2025-04-09 08:48:47', '2025-04-09 08:48:47');

-- --------------------------------------------------------

--
-- Table structure for table `file_requests`
--

CREATE TABLE `file_requests` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` bigint(20) UNSIGNED NOT NULL,
  `school_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `preferred_date` date NOT NULL,
  `preferred_time` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_request_items`
--

CREATE TABLE `file_request_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_request_id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `copies` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `file_requirements`
--

CREATE TABLE `file_requirements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `file_requirements`
--

INSERT INTO `file_requirements` (`id`, `file_id`, `name`, `created_at`, `updated_at`) VALUES
(2, 13, '2x2 ID Picture (attire with collar, recent, white background)', '2025-04-09 08:34:47', '2025-04-09 08:34:47'),
(3, 13, 'Barangay Certification (first-time jobseeker)', '2025-04-09 08:35:16', '2025-04-09 08:35:16'),
(4, 13, 'Clearance Form (Form 10)', '2025-04-09 08:35:45', '2025-04-09 08:35:45'),
(5, 13, 'Photocopy of PSA Birth Certificate', '2025-04-09 08:37:05', '2025-04-09 08:37:05'),
(6, 13, 'Photocopy of PSA Marriage Certificate (for female married)', '2025-04-09 08:38:15', '2025-04-09 08:51:36'),
(7, 13, 'Form 137(if admitted as freshmen)', '2025-04-09 08:39:29', '2025-04-09 08:52:19'),
(8, 13, 'TOR with copy for PRMSU as remarks from last HEI', '2025-04-09 08:41:18', '2025-04-09 08:41:18'),
(9, 19, 'Photocopy of last TOR issued(if available)', '2025-04-09 08:42:14', '2025-04-09 08:42:14'),
(10, 15, 'Affidavit of Loss', '2025-04-09 08:43:27', '2025-04-09 08:43:49'),
(11, 14, 'Official Transcript of Records', '2025-04-09 08:44:04', '2025-04-09 08:44:04'),
(12, 15, 'PSA Birth Certificate', '2025-04-09 08:44:50', '2025-04-09 08:44:50'),
(13, 15, 'Official Transcript of Records', '2025-04-09 08:45:13', '2025-04-09 08:45:13'),
(14, 18, 'Original Transcript of Records', '2025-04-09 08:46:12', '2025-04-09 08:46:12'),
(15, 17, 'Photocopy of Transcript of Records', '2025-04-09 08:47:16', '2025-04-09 08:47:16'),
(16, 20, '2x2 ID Picture (attire with collar, recent, white background)', '2025-04-09 08:49:54', '2025-04-09 08:49:54'),
(17, 20, 'Clearance Form (Form 10)', '2025-04-09 08:50:21', '2025-04-09 08:50:21'),
(18, 20, 'Photocopy of PSA Birth Certificate', '2025-04-09 08:50:48', '2025-04-09 08:50:48'),
(19, 20, 'Photocopy of PSA Marriage Certificate (for female married)', '2025-04-09 08:51:54', '2025-04-09 08:51:54'),
(20, 20, 'Form 137(if admitted as freshmen)', '2025-04-09 08:52:38', '2025-04-09 08:52:38');

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
(4, '2025_02_08_164553_create_students_table', 1),
(8, '2025_02_10_094936_modify_preferred_time_column_in_schedules_table', 5),
(14, '2025_02_09_030156_add_is_admin_and_faculty_id_to_users_table', 6),
(15, '2025_02_09_060408_create_files_table', 6),
(16, '2025_02_10_085840_add_preferred_time_to_schedules_table', 6),
(17, '2025_02_11_060703_create_permission_tables', 6),
(18, '2025_02_24_054821_create_password_resets_table', 6),
(19, '2025_02_25_071336_add_reason_school_year_semester_to_schedules_table', 6),
(20, '2025_02_25_081736_create_school_years_table', 6),
(21, '2025_02_25_081817_create_semesters_table', 6),
(22, '2025_02_27_063825_add_school_semester_id_to_schedules', 7),
(23, '2025_02_27_064405_add_school_year_id_to_schedules', 7),
(24, '2025_03_25_171751_update_schedules_table', 8),
(25, '2025_03_26_030000_update_schedules_table', 9),
(26, '2025_03_26_121538_add_manual_fields_to_schedules', 10),
(27, '2025_03_26_132312_add_role_to_users_table', 11),
(28, '2025_04_09_094329_file_requirements_table', 12),
(29, '2025_04_09_094527_file_requests_table', 13),
(30, '2025_04_09_094707_file_request_items_table', 14),
(31, '2025_04_10_140551_add_reference_id_to_schedules_table', 15),
(32, '2025_04_10_150702_add_compliance_addressed_to_schedules_status_enum', 16),
(33, '2025_04_11_053052_add_completed_at_to_schedules_table', 17),
(34, '2025_04_13_162613_add_more_fields_to_students_table', 18);

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
(1, 'App\\Models\\User', 5);

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
(1, 'admin', 'web', '2025-03-26 06:09:59', '2025-03-26 06:09:59');

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
-- Table structure for table `schedules`
--

CREATE TABLE `schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `school_year_id` bigint(20) UNSIGNED DEFAULT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `student_id` bigint(20) UNSIGNED DEFAULT NULL,
  `file_id` bigint(20) UNSIGNED NOT NULL,
  `preferred_date` date NOT NULL,
  `status` enum('pending','approved','rejected','completed','compliance_addressed') NOT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `reference_id` bigint(20) UNSIGNED DEFAULT NULL,
  `remarks` text DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `file_name` varchar(255) DEFAULT NULL,
  `preferred_time` enum('AM','PM') NOT NULL DEFAULT 'AM',
  `reason` text NOT NULL,
  `copies` int(11) NOT NULL DEFAULT 1,
  `school_year` varchar(255) DEFAULT NULL,
  `semester` varchar(255) DEFAULT NULL,
  `manual_school_year` varchar(255) DEFAULT NULL,
  `manual_semester` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `schedules`
--

INSERT INTO `schedules` (`id`, `school_year_id`, `semester_id`, `student_id`, `file_id`, `preferred_date`, `status`, `completed_at`, `reference_id`, `remarks`, `approved_at`, `created_at`, `updated_at`, `file_name`, `preferred_time`, `reason`, `copies`, `school_year`, `semester`, `manual_school_year`, `manual_semester`) VALUES
(50, 1, 2, 8, 12, '2025-04-18', 'compliance_addressed', NULL, NULL, 'test compliance', NULL, '2025-04-10 07:56:36', '2025-04-10 08:47:49', NULL, 'AM', 'test compliance', 1, NULL, NULL, NULL, NULL),
(55, 1, 2, 8, 12, '2025-04-16', 'compliance_addressed', NULL, NULL, 'test pending compliance', NULL, '2025-04-11 06:04:52', '2025-04-11 23:09:53', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(56, 1, 2, 8, 14, '2025-04-16', 'completed', '2025-04-11 22:30:30', NULL, NULL, NULL, '2025-04-11 06:05:40', '2025-04-11 22:30:30', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(59, 3, 7, 8, 14, '2025-04-22', 'approved', NULL, NULL, NULL, NULL, '2025-04-11 22:57:54', '2025-04-12 10:07:23', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(60, 3, 7, 8, 12, '2025-04-22', 'approved', NULL, NULL, NULL, NULL, '2025-04-11 23:08:43', '2025-04-12 10:01:07', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(61, 3, 7, 8, 18, '2025-04-18', 'rejected', NULL, NULL, 'test', NULL, '2025-04-11 23:09:06', '2025-04-11 23:12:28', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(62, 3, 7, 8, 12, '2025-04-17', 'approved', NULL, 55, NULL, NULL, '2025-04-11 23:09:53', '2025-04-11 23:12:03', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(63, 3, 7, 8, 12, '2025-04-22', 'approved', NULL, NULL, NULL, NULL, '2025-04-12 18:09:35', '2025-04-12 19:02:30', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(64, 3, 7, 8, 13, '2025-04-22', 'completed', '2025-04-12 19:24:04', NULL, NULL, NULL, '2025-04-12 18:09:36', '2025-04-12 19:24:04', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(65, 3, 7, 8, 12, '2025-04-22', 'rejected', NULL, NULL, 'test', NULL, '2025-04-12 19:30:19', '2025-04-12 19:30:58', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(66, 3, 7, 8, 12, '2025-04-24', 'pending', NULL, NULL, NULL, NULL, '2025-04-15 00:08:18', '2025-04-15 00:08:18', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL),
(67, 3, 7, 8, 12, '2025-04-24', 'pending', NULL, NULL, NULL, NULL, '2025-04-15 00:12:30', '2025-04-15 00:12:30', NULL, 'AM', 'test', 1, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `school_years`
--

CREATE TABLE `school_years` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `year` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `school_years`
--

INSERT INTO `school_years` (`id`, `year`, `created_at`, `updated_at`) VALUES
(1, '2024-2025', '2025-02-27 00:14:47', '2025-02-27 00:14:47'),
(2, '2023-2024', '2025-04-11 08:05:53', '2025-04-11 08:05:53'),
(3, '2025-2026', '2025-04-11 08:09:24', '2025-04-11 08:09:24');

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `school_year_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `name`, `school_year_id`, `created_at`, `updated_at`) VALUES
(1, '1st Semester', 1, '2025-02-27 00:15:05', '2025-02-27 00:15:05'),
(2, '2nd Semester', 1, '2025-03-25 08:31:58', '2025-03-25 08:31:58'),
(3, '1st Semester', 2, '2025-04-11 08:06:24', '2025-04-11 08:06:24'),
(6, '2nd Semester', 2, '2025-04-11 08:08:37', '2025-04-11 08:08:37'),
(7, '1st Semester', 3, '2025-04-11 08:09:34', '2025-04-11 08:09:34');

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
('aGfjNwtLYpFJkHCHFO1iilMFrTPV9iwpEqohGcjD', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiRUxyY080ekZJc0k3cEZOWjhBemdZWHJqT2wyVWtyWVoyVjdmbWN6MCI7czozOiJ1cmwiO2E6MDp7fXM6OToiX3ByZXZpb3VzIjthOjE6e3M6MzoidXJsIjtzOjM4OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvc3R1ZGVudC9zY2hlZHVsZSI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fXM6NTQ6ImxvZ2luX3N0dWRlbnRfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo1O30=', 1742982852),
('IC6SyGQy33EL7V2QdXVfvX6malYXHlSZ49rffyAC', 5, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiMFFHUk1wN1lETW9qMTNPYnBNcG16aWc5WTk3cW9FdGo0d1R3SUZQWCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzg6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdHVkZW50L3NjaGVkdWxlIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1NDoibG9naW5fc3R1ZGVudF81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjU7fQ==', 1742989310),
('vD8zcMvk5qazMLq4i2vzYlTVXq4xmngOZX3MEFzG', 2, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/134.0.0.0 Safari/537.36', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoieXdscW9uZ0d5NGg2Q01JeVVBcWJpYnl1R0tlTk1kVUE5M3dYM0E0SCI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToyO30=', 1744040513);

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `student_id` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `course` varchar(255) NOT NULL,
  `contact_number` varchar(255) NOT NULL,
  `home_address` text DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `sex` enum('Male','Female') DEFAULT NULL,
  `birthday` date DEFAULT NULL,
  `birthplace` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `student_id`, `first_name`, `last_name`, `middle_name`, `email`, `course`, `contact_number`, `home_address`, `password`, `created_at`, `updated_at`, `sex`, `birthday`, `birthplace`) VALUES
(8, '20-00255', 'Darius Kristoffer', 'Mora', 'Aquino', 'dariusanity122@gmail.com', 'BSCS', '09542816926', 'Nagbunga, San Marcelino, Zambales', '$2y$12$92CKZInwQxY607Qyt4Znt.y25eoLrOg9xvvMEbO4/4xqX/IoMbq1i', '2025-04-09 07:46:58', '2025-04-13 19:28:17', 'Male', '1993-09-18', 'San Marcelino');

--
-- Triggers `students`
--
DELIMITER $$
CREATE TRIGGER `prevent_student_id_update` BEFORE UPDATE ON `students` FOR EACH ROW BEGIN
    IF OLD.id != NEW.id THEN
        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Cannot change the student ID once it is in use';
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL DEFAULT 1,
  `faculty_id` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'admin'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `is_admin`, `faculty_id`, `role`) VALUES
(1, 'Admin Test', 'prmsuadmintest@gmail.com', NULL, '$2y$12$GlDMOMiTuXs8ydHfTybRYOYiw3qK6WxW2PbT/O6jZz5e/NbBR9.2u', NULL, '2025-02-08 19:38:46', '2025-02-08 19:38:46', 1, '0000001', 'admin'),
(2, 'Admin Test2', 'prmsuadminhasrole@gmail.com', NULL, '$2y$12$jvhRSriIzHnqkYzs9cZ6v.DeVM9Wfi.iDco8oazGXzW9GTqM7uQ5i', 'Mnr8rfl31X9Hd4SCdM7mH1NTeBeRdMvoPImYM2u90DVjopFGocMVRoxCWOJp', '2025-02-10 22:27:58', '2025-02-10 22:27:58', 1, '0000002', 'admin'),
(3, 'Super Admin', 'superadmin@example.com', NULL, '$2y$12$RfpHoyrOlk0R2r8HICxj0ewzTa5lhNg4roHDz1RDipXhSXRIsQlqO', NULL, '2025-03-26 05:27:16', '2025-03-26 06:00:50', 1, 'FAC12345', 'superadmin'),
(4, 'faculty1', 'faculty1@gmail.com', NULL, '$2y$12$F6xu6GJhSNX.Qvh/cPnH7eP9zEyDhaLWwGLEkExNtLnpEezuDbEmW', NULL, '2025-03-26 06:08:45', '2025-03-26 06:08:45', 1, '12371236', 'admin'),
(5, 'test faculty', 'testfaculty@gmail.com', NULL, '$2y$12$31dHwciRYd7eFM5PFbI2TO1jrNP8W27ZMIcao3ldcjsXSY6Ek4HIS', NULL, '2025-03-26 06:16:38', '2025-03-26 06:16:38', 1, '236877', 'admin');

--
-- Indexes for dumped tables
--

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
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `file_requests`
--
ALTER TABLE `file_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_requests_student_id_foreign` (`student_id`),
  ADD KEY `file_requests_school_year_id_foreign` (`school_year_id`),
  ADD KEY `file_requests_semester_id_foreign` (`semester_id`);

--
-- Indexes for table `file_request_items`
--
ALTER TABLE `file_request_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_request_items_file_request_id_foreign` (`file_request_id`),
  ADD KEY `file_request_items_file_id_foreign` (`file_id`);

--
-- Indexes for table `file_requirements`
--
ALTER TABLE `file_requirements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `file_requirements_file_id_foreign` (`file_id`);

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
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `permissions`
--
ALTER TABLE `permissions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `permissions_name_guard_name_unique` (`name`,`guard_name`);

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
-- Indexes for table `schedules`
--
ALTER TABLE `schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `schedules_student_id_foreign` (`student_id`),
  ADD KEY `schedules_school_year_id_foreign` (`school_year_id`),
  ADD KEY `schedules_semester_id_foreign` (`semester_id`),
  ADD KEY `schedules_reference_id_foreign` (`reference_id`);

--
-- Indexes for table `school_years`
--
ALTER TABLE `school_years`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `school_years_year_unique` (`year`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`),
  ADD KEY `semesters_school_year_id_foreign` (`school_year_id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `students_student_id_unique` (`student_id`),
  ADD UNIQUE KEY `students_email_unique` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_faculty_id_unique` (`faculty_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `file_requests`
--
ALTER TABLE `file_requests`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_request_items`
--
ALTER TABLE `file_request_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `file_requirements`
--
ALTER TABLE `file_requirements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT for table `permissions`
--
ALTER TABLE `permissions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `schedules`
--
ALTER TABLE `schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `school_years`
--
ALTER TABLE `school_years`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `file_requests`
--
ALTER TABLE `file_requests`
  ADD CONSTRAINT `file_requests_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `file_requests_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `file_requests_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_request_items`
--
ALTER TABLE `file_request_items`
  ADD CONSTRAINT `file_request_items_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `file_request_items_file_request_id_foreign` FOREIGN KEY (`file_request_id`) REFERENCES `file_requests` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `file_requirements`
--
ALTER TABLE `file_requirements`
  ADD CONSTRAINT `file_requirements_file_id_foreign` FOREIGN KEY (`file_id`) REFERENCES `files` (`id`) ON DELETE CASCADE;

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
-- Constraints for table `role_has_permissions`
--
ALTER TABLE `role_has_permissions`
  ADD CONSTRAINT `role_has_permissions_permission_id_foreign` FOREIGN KEY (`permission_id`) REFERENCES `permissions` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_has_permissions_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `schedules`
--
ALTER TABLE `schedules`
  ADD CONSTRAINT `schedules_reference_id_foreign` FOREIGN KEY (`reference_id`) REFERENCES `schedules` (`id`),
  ADD CONSTRAINT `schedules_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `schedules_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedules_student_id_foreign` FOREIGN KEY (`student_id`) REFERENCES `students` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `semesters`
--
ALTER TABLE `semesters`
  ADD CONSTRAINT `semesters_school_year_id_foreign` FOREIGN KEY (`school_year_id`) REFERENCES `school_years` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
