-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 31, 2026 at 10:26 AM
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
-- Database: `timetablepro`
--

-- --------------------------------------------------------

--
-- Table structure for table `branches`
--

CREATE TABLE `branches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `branch_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `branches`
--

INSERT INTO `branches` (`id`, `branch_name`, `created_at`, `updated_at`) VALUES
(1, 'Unguja', '2026-04-24 06:47:47', '2026-04-24 06:47:47'),
(2, 'Pemba', '2026-04-24 06:47:47', '2026-04-24 06:47:47');

-- --------------------------------------------------------

--
-- Table structure for table `buildings`
--

CREATE TABLE `buildings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `building_name` varchar(255) NOT NULL,
  `building_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `buildings`
--

INSERT INTO `buildings` (`id`, `building_name`, `building_code`, `created_at`, `updated_at`) VALUES
(1, 'FLOOR 1', 'F/001', '2026-04-24 06:47:49', '2026-04-24 06:47:49'),
(2, 'FLOOR 2', 'F/002', '2026-04-24 06:47:49', '2026-04-24 06:47:49'),
(3, 'FLOOR 3', 'F/003', '2026-04-24 06:47:49', '2026-04-24 06:47:49');

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
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `courseName` varchar(255) NOT NULL,
  `course_code` varchar(255) NOT NULL,
  `short_name` varchar(255) DEFAULT NULL,
  `course_level` varchar(255) NOT NULL,
  `deptId` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `courseName`, `course_code`, `short_name`, `course_level`, `deptId`, `created_at`, `updated_at`, `building_id`, `username`, `password`) VALUES
(1, 'Business Information Technology', 'C001', 'BIT', 'Diploma', 1, '2026-04-24 07:16:40', '2026-04-24 07:16:40', 1, 'username1', '$2y$12$By5LmZs96p6YhEdvOwTar.q3rq0QVK/lmFSbX9wIKwNSITIkYa38a'),
(2, 'Business Management', 'C002', 'BM', 'Diploma', 1, '2026-04-24 07:16:40', '2026-04-24 07:16:40', 1, 'username2', '$2y$12$wuvoSp9QEEFqnysBgKOso.90G4FcGXgkMLiJe0hUdBuYqm87UUdg2'),
(3, 'Economics and Finance', 'C003', 'EF', 'Diploma', 1, '2026-04-24 07:16:40', '2026-04-24 07:16:40', 1, 'username3', '$2y$12$PsdC.iVOU0.BV19fZ/t92eWiU54nPUijgrVMjIJhkbCpaJjctQ/0S'),
(4, 'Procurement and Supplies', 'C004', 'PS', 'Diploma', 1, '2026-04-24 07:16:41', '2026-04-24 07:16:41', 1, 'username4', '$2y$12$/opthn9qD9Aha.z/MwLFNOfjo2/56g78Di2fRNh4O3/21TcREDCpK'),
(5, 'Records Management and Information', 'C005', 'RM', 'Diploma', 1, '2026-04-24 07:16:41', '2026-04-24 07:16:41', 1, 'username5', '$2y$12$uDsYKqC80o.JTewVWAKfb.6iph9cjv/wvC4uGL6zRsh4xRX7.w/3O'),
(6, 'Bachelor Degree in Records Management', 'C006', 'RM', 'Degree', 1, '2026-04-24 07:16:42', '2026-04-24 07:16:42', 1, 'username6', '$2y$12$PD40UA4wjRvlTh7LM.DioOp7WO6SUtYdjGDd7Ow7N3lM620vTqF5e'),
(7, 'Human Resource Management', 'C007', 'HRM', 'Diploma', 2, '2026-04-24 07:16:42', '2026-04-24 07:16:42', 1, 'username7', '$2y$12$k3w8n0O8trsZsdN12JL86.pjlNIFlRduTF4yAdB4yIrQGMY0/Uo5a'),
(8, 'Bachelor Degree in Human Resource Management', 'C008', 'HRM', 'Degree', 2, '2026-04-24 07:16:42', '2026-04-24 07:16:42', 1, 'username8', '$2y$12$d34hc1Aj8unmZEbXK5AyyuepIB60Eu8ngc2Zx9M7sTF3TTTi4QSHC'),
(9, 'Secretarial Studies', 'C009', 'SS', 'Diploma', 2, '2026-04-24 07:16:43', '2026-04-24 07:16:43', 1, 'username9', '$2y$12$lv7t7XWRN64CqPmrWayDmOmPOmipM4RiBOofRZm2n8G8AKMoF.IZC'),
(10, 'International Relations and Diplomacy', 'C010', 'IRD', 'Diploma', 2, '2026-04-24 07:16:43', '2026-04-24 07:16:43', 2, 'username10', '$2y$12$JxPy07efodTjxZUZyAAGleF8nTrLTKzssWJs50.r2fztznT95H.yq'),
(11, 'Public Administration', 'C011', 'PA', 'Diploma', 2, '2026-04-24 07:16:43', '2026-04-24 07:16:43', 3, 'username11', '$2y$12$5I6NEmvN8xVurzAK9LmIZ.Fg6dc6D9b/MYWQMDFTOfLhmDiBT4opq'),
(12, 'Public Relations', 'C012', 'PR', 'Diploma', 1, '2026-04-24 07:16:44', '2026-04-24 07:16:44', 3, 'username12', '$2y$12$nzM044G1LsglitGJRBKCbOCh52NCC7pBD46530LhF3oV3I3fK7mbG'),
(13, 'Development Planning', 'C013', 'DP', 'Diploma', 1, '2026-04-24 07:16:44', '2026-04-24 07:16:44', 3, 'username13', '$2y$12$DZqMVf3gt81tmitCkjm.a.0nPcASAHB/P37lSgC/9QZqUMQOAzE9S'),
(14, 'Bachelor Degree in Development Planning', 'C014', 'DP', 'Degree', 1, '2026-04-24 07:16:45', '2026-04-24 07:16:45', 2, 'username14', '$2y$12$WXNuJd.Pwam15qdit3uJqe.Fz4QTi91u.1/IeOo1ERRnm2iXu/TT6'),
(15, 'Bachelor Degree in International Relations', 'C015', 'IRD', 'Degree', 1, '2026-04-24 07:16:45', '2026-04-28 14:27:26', 2, 'username15', '$2y$12$lT/T8VW6h4xSSUGX/QF9guv9CW45nn7T1AqE4fkVWXXZF6tm4ROWy');

-- --------------------------------------------------------

--
-- Table structure for table `course_rooms`
--

CREATE TABLE `course_rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `nta_level` varchar(255) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `total_students` int(11) DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `course_rooms`
--

INSERT INTO `course_rooms` (`id`, `course_id`, `nta_level`, `group_name`, `room_id`, `created_at`, `updated_at`, `total_students`, `branch_id`) VALUES
(51, 1, 'NTA-4', NULL, 1, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 40, 1),
(52, 1, 'NTA-5', NULL, 2, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 44, 1),
(53, 1, 'NTA-6', NULL, 3, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 30, 1),
(54, 2, 'NTA-4', NULL, 4, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 25, 1),
(55, 2, 'NTA-5', NULL, 5, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 24, 1),
(56, 2, 'NTA-6', NULL, 6, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 20, 1),
(57, 3, 'NTA-4', NULL, 7, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 38, 1),
(58, 3, 'NTA-5', NULL, 8, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 46, 1),
(59, 3, 'NTA-6', NULL, 9, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 32, 1),
(60, 4, 'NTA-4', NULL, 10, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 45, 1),
(61, 4, 'NTA-5', NULL, 11, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 67, 1),
(62, 4, 'NTA-6', NULL, 12, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 34, 1),
(63, 5, 'NTA-4', NULL, 13, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 56, 1),
(64, 5, 'NTA-5', NULL, 14, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 34, 1),
(65, 5, 'NTA-6', NULL, 12, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 24, 1),
(68, 7, 'NTA-4', NULL, 7, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 54, 1),
(69, 7, 'NTA-5', NULL, 3, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 32, 1),
(70, 7, 'NTA-6', NULL, 6, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 34, 1),
(73, 9, 'NTA-4', NULL, 11, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 24, 1),
(74, 9, 'NTA-5', NULL, 10, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 36, 1),
(75, 9, 'NTA-6', NULL, 14, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 45, 1),
(76, 10, 'NTA-4', NULL, 13, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 31, 1),
(77, 10, 'NTA-5', NULL, 2, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 20, 1),
(78, 10, 'NTA-6', NULL, 7, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 34, 1),
(79, 11, 'NTA-4', NULL, 4, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 25, 1),
(80, 11, 'NTA-5', NULL, 9, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 36, 1),
(81, 11, 'NTA-6', NULL, 13, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 64, 1),
(82, 12, 'NTA-4', NULL, 14, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 34, 1),
(83, 12, 'NTA-5', NULL, 8, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 30, 1),
(84, 12, 'NTA-6', NULL, 6, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 36, 1),
(85, 13, 'NTA-4', NULL, 3, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 45, 1),
(86, 13, 'NTA-5', NULL, 12, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 23, 1),
(87, 13, 'NTA-6', NULL, 14, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 45, 1),
(88, 14, 'NTA-7', NULL, 15, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 30, 1),
(89, 14, 'NTA-8', NULL, 16, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 27, 1),
(90, 15, 'NTA-7', NULL, 15, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 14, 1),
(91, 15, 'NTA-8', NULL, 16, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 14, 1),
(92, 8, 'NTA-7', NULL, 15, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 40, 1),
(93, 8, 'NTA-8', NULL, 16, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 50, 1),
(94, 6, 'NTA-7', NULL, 15, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 50, 1),
(95, 6, 'NTA-8', NULL, 16, '2026-04-25 21:06:20', '2026-04-25 21:06:20', 40, 1),
(96, 7, 'NTA-6', NULL, 20, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(97, 5, 'NTA-6', NULL, 21, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(98, 4, 'NTA-6', NULL, 3, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(99, 7, 'NTA-5', NULL, 4, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(100, 5, 'NTA-5', NULL, 5, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(101, 4, 'NTA-5', NULL, 6, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(102, 7, 'NTA-4', NULL, 3, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(103, 5, 'NTA-4', NULL, 4, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2),
(104, 4, 'NTA-4', NULL, 20, '2026-04-27 00:19:06', '2026-04-27 00:19:06', 50, 2);

-- --------------------------------------------------------

--
-- Table structure for table `cr_info`
--

CREATE TABLE `cr_info` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `semester_id` bigint(20) UNSIGNED DEFAULT NULL,
  `nta` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cr_info`
--

INSERT INTO `cr_info` (`id`, `firstname`, `middlename`, `lastname`, `mobile`, `email`, `password`, `course_id`, `semester_id`, `nta`, `created_at`, `updated_at`, `branch_id`) VALUES
(2, 'ALI', 'SILIMA', 'BAKARI', '07783748', 'ali7@gmail.com', '$2y$12$Ybl4vxHfnR0NRpk.TjRuV.By6RI.6O53vZp4tPmEzq0gZ9wjPLMPW', 14, 4, 'NTA-7', '2026-04-26 07:36:26', '2026-04-26 07:36:26', 1),
(3, 'SALHA', 'JUMA', 'MOHD', '07734939', 'sal8@gmail.com', '$2y$12$AFVUlyh8uPTTQrlSIAg98uXyhSoyKppi3D36HBIkja.S6PWmi4TfG', 11, 4, 'NTA-4', '2026-04-26 07:36:26', '2026-04-26 10:56:28', 1),
(4, 'AISHA', 'MBARUK', 'JUMA', '07384937', 'aish87@gmail.com', '$2y$12$qdr2erShDz4zlRcpmtkcgOXONn7PUDAEWMtXYr.L.JfwYMuN42Dde', 14, 3, 'NTA-7', '2026-04-26 07:36:26', '2026-04-26 22:57:43', 1),
(5, 'PANDU', 'JECHA', 'BAKARI', '07738493', 'pandu9@gmail.com', '$2y$12$fajiaZvGz8ocuCTmZ43w3eE25uo6g.CfN51NW7vM4lHTAVIuOTHfq', 14, 3, 'NTA-7', '2026-04-26 07:36:27', '2026-04-26 07:36:27', 1);

-- --------------------------------------------------------

--
-- Table structure for table `days`
--

CREATE TABLE `days` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `days`
--

INSERT INTO `days` (`id`, `day_name`, `created_at`, `updated_at`) VALUES
(1, 'Monday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(2, 'Tuesday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(3, 'Wednesday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(4, 'Thursday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(5, 'Friday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(6, 'Saturday', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(7, 'Sunday', '2026-04-24 06:47:48', '2026-04-24 06:47:48');

-- --------------------------------------------------------

--
-- Table structure for table `departments`
--

CREATE TABLE `departments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `deptName` varchar(255) NOT NULL,
  `dept_code` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `departments`
--

INSERT INTO `departments` (`id`, `deptName`, `dept_code`, `created_at`, `updated_at`) VALUES
(1, 'DEPARTMENT OF ART AND SOCIAL SCIENCE', 'D001', '2026-04-24 06:47:47', '2026-04-24 06:47:47'),
(2, 'DEPARTMENT OF BUSINESS MANAGEMENT AND ICT', 'D002', '2026-04-24 06:47:47', '2026-04-28 14:28:16'),
(3, 'DEPARTMNET OF PEMBA', 'D003', '2026-04-24 06:47:47', '2026-04-24 06:47:47');

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
-- Table structure for table `holidays`
--

CREATE TABLE `holidays` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `date` datetime NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `date`, `created_at`, `updated_at`) VALUES
(1, 'New Year', '2026-01-01 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(2, 'Mapinduzi Day', '2026-01-12 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(3, 'Karume Day', '2026-04-07 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(4, 'Union Day', '2026-04-26 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(5, 'Workers Day', '2026-05-01 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(6, 'Saba Saba', '2026-07-07 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(7, 'Nyerere Day', '2026-10-14 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(8, 'Independence Day', '2026-12-09 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(9, 'Christmas Day', '2026-12-25 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(10, 'Boxing Day', '2026-12-26 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(11, 'Good Friday', '2026-04-03 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(12, 'Easter Monday', '2026-04-06 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(13, 'Eid El-Fitr', '2026-03-20 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(14, 'Eid El-Hajj', '2026-05-27 00:00:00', '2026-04-24 06:47:48', '2026-04-24 06:47:48'),
(15, 'Maulid Day', '2026-09-15 00:00:00', '2026-04-24 06:47:49', '2026-04-24 06:47:49');

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
-- Table structure for table `loggins`
--

CREATE TABLE `loggins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `action` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `loggins`
--

INSERT INTO `loggins` (`id`, `title`, `action`, `created_at`, `updated_at`) VALUES
(1, 'New Registration', 'New Excell of Teachers is recorded', '2026-04-24 06:49:15', '2026-04-24 06:49:15'),
(2, 'New Class Registration', 'New classrooms are registered', '2026-04-24 06:49:34', '2026-04-24 06:49:34'),
(3, 'New Courses Registration', 'New courses are registered', '2026-04-24 07:16:45', '2026-04-24 07:16:45'),
(4, 'New Registration', 'New Teacher FAUZIA  HASSAN is registered', '2026-04-24 13:16:32', '2026-04-24 13:16:32'),
(5, 'New Registration', 'New Teacher KHADIJA  KHAMIS is registered', '2026-04-24 13:20:53', '2026-04-24 13:20:53'),
(6, 'New Registration', 'New Teacher OMAR  HAMAD is registered', '2026-04-24 13:34:19', '2026-04-24 13:34:19'),
(7, 'New Registration', 'New Teacher SHAYMA  -- is registered', '2026-04-24 13:39:36', '2026-04-24 13:39:36'),
(8, 'New Registration', 'New Teacher RUKIYA  -- is registered', '2026-04-24 13:41:34', '2026-04-24 13:41:34'),
(9, 'New Registration', 'New Teacher LUTTFA  -- is registered', '2026-04-24 13:42:23', '2026-04-24 13:42:23'),
(10, 'New Registration', 'New Excell of Teachers is recorded', '2026-04-24 14:59:07', '2026-04-24 14:59:07'),
(11, 'New Registration', 'New Teacher MOHAMED  ALMASI is registered', '2026-04-25 07:02:14', '2026-04-25 07:02:14'),
(12, 'New Registration', 'New Teacher NURU  ABDALLAH is registered', '2026-04-25 11:35:35', '2026-04-25 11:35:35'),
(13, 'New Registration', 'New Teacher ABDUL-KARIM  -- is registered', '2026-04-25 11:37:55', '2026-04-25 11:37:55'),
(14, 'New Registration', 'New Teacher MAKAME  ALI is registered', '2026-04-25 11:41:47', '2026-04-25 11:41:47'),
(15, 'New Registration', 'New Teacher MOHAMED  MOHAMED is registered', '2026-04-25 11:49:57', '2026-04-25 11:49:57'),
(16, 'New Registration', 'New Teacher AMOUR  ABDI is registered', '2026-04-25 11:53:55', '2026-04-25 11:53:55'),
(17, 'New Registration', 'New Teacher ABDALLAH  ALI is registered', '2026-04-25 11:57:18', '2026-04-25 11:57:18'),
(18, 'New Updating', 'Teacher MARKNOT1  -- is updated someinfo', '2026-04-25 12:01:35', '2026-04-25 12:01:35'),
(19, 'New Updating', 'Teacher BANDIA1  -- is updated someinfo', '2026-04-25 12:42:23', '2026-04-25 12:42:23'),
(20, 'New Registration', 'New Teacher BANDIA2  -- is registered', '2026-04-25 12:43:03', '2026-04-25 12:43:03'),
(21, 'New Registration', 'New Teacher BANDIA3  -- is registered', '2026-04-25 12:49:27', '2026-04-25 12:49:27'),
(22, 'New Registration', 'New Teacher BANDIA4  -- is registered', '2026-04-25 12:58:04', '2026-04-25 12:58:04'),
(23, 'New Registration', 'New Teacher BANDIA5  -- is registered', '2026-04-25 13:25:13', '2026-04-25 13:25:13'),
(24, 'New Updating', 'Teacher MAHMOUD  ALI is updated someinfo', '2026-04-26 07:30:01', '2026-04-26 07:30:01'),
(25, 'New Registration', 'New Excel of Students is recorded', '2026-04-26 07:36:27', '2026-04-26 07:36:27'),
(26, 'New Updating', 'Teacher SAID  SHEHE is updated someinfo', '2026-04-26 14:06:12', '2026-04-26 14:06:12'),
(27, 'New Updating', 'Teacher HASSAN  HASSAN is updated someinfo', '2026-04-27 08:19:23', '2026-04-27 08:19:23'),
(28, 'New Registration', 'New Teacher ALHAJI  JECHA is registered', '2026-04-27 08:26:24', '2026-04-27 08:26:24'),
(29, 'New Updating', 'Teacher MAKAME  ALI is updated someinfo', '2026-04-27 08:42:06', '2026-04-27 08:42:06'),
(30, 'New Registration', 'New Teacher MAKAME  SILIMA is registered', '2026-04-27 08:45:08', '2026-04-27 08:45:08'),
(31, 'New Updating', 'Teacher MAKAME  SILIMA is updated someinfo', '2026-04-27 08:45:43', '2026-04-27 08:45:43'),
(32, 'New Registration', 'New Teacher KIBWANA  KONDO is registered', '2026-04-27 09:12:36', '2026-04-27 09:12:36'),
(33, 'New Registration', 'New Teacher ISMAIL  KHAMIS is registered', '2026-04-27 09:18:28', '2026-04-27 09:18:28'),
(34, 'New Registration', 'New Teacher HUMOUD  HUMOUD is registered', '2026-04-27 09:32:18', '2026-04-27 09:32:18'),
(35, 'New Registration', 'New Teacher MOHAMED  KASSIM is registered', '2026-04-27 09:42:13', '2026-04-27 09:42:13'),
(36, 'New Updating', 'Teacher MAHIRA  HAMAD is updated someinfo', '2026-04-28 11:59:28', '2026-04-28 11:59:28'),
(37, 'New Department Updates', 'The DEPARTMENT OF BUSINESS MANAGEMENT AND ICT is updating', '2026-04-28 14:28:16', '2026-04-28 14:28:16'),
(38, 'New Registration', 'New Teacher MARIYAM  SALE is registered', '2026-04-28 21:04:06', '2026-04-28 21:04:06'),
(39, 'New Updating', 'Teacher SHAMIS  -- is updated someinfo', '2026-04-28 21:15:46', '2026-04-28 21:15:46'),
(40, 'New Updating', 'Teacher SHAMIS  -- is updated someinfo', '2026-04-28 21:16:11', '2026-04-28 21:16:11'),
(41, 'New Updating', 'Teacher SHAMIS  -- is updated someinfo', '2026-04-28 21:16:38', '2026-04-28 21:16:38'),
(42, 'New Updating', 'Teacher SHAMIS  -- is updated someinfo', '2026-04-28 21:16:59', '2026-04-28 21:16:59'),
(43, 'New Updating', 'Teacher SHAMIS  -- is updated someinfo', '2026-04-28 21:17:13', '2026-04-28 21:17:13'),
(44, 'New Updating', 'Teacher DK AMIRI  -- is updated someinfo', '2026-04-30 21:58:09', '2026-04-30 21:58:09'),
(45, 'New Updating', 'Teacher DK KHADIJA  KASSIM is updated someinfo', '2026-04-30 21:59:18', '2026-04-30 21:59:18'),
(46, 'New Updating', 'Teacher DK OMAR  ALI is updated someinfo', '2026-04-30 21:59:39', '2026-04-30 21:59:39'),
(47, 'New Updating', 'Teacher DK KHAMIS  ALI is updated someinfo', '2026-04-30 22:00:03', '2026-04-30 22:00:03'),
(48, 'New Updating', 'Teacher DK HAJI  KHAMIS is updated someinfo', '2026-04-30 22:00:24', '2026-04-30 22:00:24'),
(49, 'New Updating', 'Teacher ABDUL-HAMID  ALI is updated someinfo', '2026-05-03 10:09:55', '2026-05-03 10:09:55'),
(50, 'New Registration', 'New Teacher HAJI  HAJI is registered', '2026-05-03 10:13:12', '2026-05-03 10:13:12'),
(51, 'New Registration', 'New Teacher SAADA  MOHAMED is registered', '2026-05-03 22:05:38', '2026-05-03 22:05:38'),
(52, 'New Registration', 'New Teacher SUMAIYA  HUSSEIN is registered', '2026-05-03 22:07:10', '2026-05-03 22:07:10'),
(53, 'New Updating', 'Teacher SHAYMA  ABDALLAH is updated someinfo', '2026-05-03 22:12:23', '2026-05-03 22:12:23'),
(54, 'New Registration', 'New Teacher TATU  MTUMWA is registered', '2026-05-04 10:49:03', '2026-05-04 10:49:03'),
(55, 'New Updating', 'Teacher KIBWANA  KOMBO is updated someinfo', '2026-05-05 00:44:37', '2026-05-05 00:44:37'),
(56, 'New Registration', 'New Teacher ALI  KHAMIS is registered', '2026-05-16 02:37:08', '2026-05-16 02:37:08'),
(57, 'New Registration', 'New Teacher HEMED  SALIM is registered', '2026-05-16 02:42:36', '2026-05-16 02:42:36');

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
(4, '2025_09_03_192901_create_teachers_table', 1),
(5, '2025_09_05_050920_add_user_level_to_teachers_table', 1),
(6, '2025_09_09_083053_create_rooms_table', 1),
(7, '2025_09_09_120922_create_departments_table', 1),
(8, '2025_09_09_130039_create_semesters_table', 1),
(9, '2025_09_10_134719_add_teacher_code_to_teachers_table', 1),
(10, '2025_09_10_140321_add_sem_code_to_semesters_table', 1),
(11, '2025_09_11_122736_add_practical_type_to_rooms_table', 1),
(12, '2025_09_12_125141_create_timeslots_table', 1),
(13, '2025_09_12_133050_create_days_table', 1),
(14, '2025_09_13_085128_create_courses_table', 1),
(15, '2025_09_13_085335_create_subjects_table', 1),
(16, '2025_09_13_095714_create_timetables_table', 1),
(17, '2025_09_13_134420_add_credit_hours_to_subjects_table', 1),
(18, '2025_09_15_111940_add_shared_group_to_subjects_table', 1),
(19, '2025_09_15_123015_add_status_to_timeslots_table', 1),
(20, '2025_09_17_113128_create_buildings_table', 1),
(21, '2025_09_17_120338_add_building_id_to_rooms_table', 1),
(22, '2025_09_17_121021_add_building_id_to_courses_table', 1),
(23, '2025_10_19_121742_create_course_rooms_table', 1),
(24, '2025_11_03_090123_add_status_to_teachers_table', 1),
(25, '2025_11_03_113701_add_group_name_to_timetables_table', 1),
(26, '2025_11_04_011737_add_semester_id_to_timetables_table', 1),
(27, '2025_11_08_042241_add_teacher_id_to_timetables_table', 1),
(28, '2025_11_15_093821_add_course_level_to_courses_table', 1),
(29, '2025_11_28_065904_create_loggins_table', 1),
(30, '2026_01_24_060353_add_middlename_to_teachers_table', 1),
(31, '2026_02_19_125441_add_role_to_teachers_table', 1),
(32, '2026_02_19_132227_add_username_to_courses_table', 1),
(33, '2026_02_19_132259_add_password_to_courses_table', 1),
(34, '2026_02_19_231726_create_cr_info_table', 1),
(35, '2026_02_20_013318_create_teacher_attendances_table', 1),
(36, '2026_02_20_020318_add_course_id_to_teacher_attendances_table', 1),
(37, '2026_02_25_061631_add_short_name_to_courses_table', 1),
(38, '2026_03_08_120228_add_group_name_to_subjects_table', 1),
(39, '2026_03_12_064546_add_semester_id_to_cr_info_table', 1),
(40, '2026_03_12_090757_add_status2_to_teacher_attendances_table', 1),
(41, '2026_04_21_091116_make_column_nullable', 1),
(42, '2026_04_21_091351_change_column_type_on_rooms', 1),
(43, '2026_04_21_092351_add_total_students_to_course_rooms_table', 1),
(44, '2026_04_22_044953_create_system_timetables_table', 1),
(45, '2026_04_22_095944_create_holidays_table', 1),
(46, '2026_04_23_035414_modify_status_column_in_teacher_attendances_table', 1),
(47, '2026_04_23_203439_modify_semester_id_nullable_in_timetables_table', 1),
(48, '2026_04_24_032612_create_branches_table', 1),
(49, '2026_04_24_033803_add_branch_id_to_teachers_table', 1),
(50, '2026_04_24_034601_add_branch_id_to_rooms_table', 1),
(51, '2026_04_24_034949_add_branch_id_to_subjects_table', 1),
(52, '2026_04_24_035133_add_branch_id_to_cr_info_table', 1),
(53, '2026_04_24_042219_add_dept_id_on_teachers_table', 1),
(54, '2026_04_24_061153_add_branch_id_to_timetables_table', 1),
(55, '2026_04_24_084400_add_branch_id_to_course_rooms_table', 2);

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
-- Table structure for table `rooms`
--

CREATE TABLE `rooms` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `capacity` int(11) NOT NULL,
  `type` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `practical_type` varchar(255) NOT NULL,
  `building_id` bigint(20) UNSIGNED NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `rooms`
--

INSERT INTO `rooms` (`id`, `name`, `capacity`, `type`, `status`, `created_at`, `updated_at`, `practical_type`, `building_id`, `branch_id`) VALUES
(1, 'ROOM 1', 35, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(2, 'ROOM 2', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(3, 'ROOM 3', 56, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(4, 'ROOM 4', 45, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(5, 'ROOM 5', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(6, 'ROOM 6', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(7, 'ROOM 7', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 1, 1),
(8, 'ROOM 8', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(9, 'ROOM 9', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(10, 'ROOM 10', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(11, 'ROOM 11', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(12, 'ROOM 12', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(13, 'ROOM 13', 25, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(14, 'ROOM 14', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(15, 'HALL 1', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 3, 1),
(16, 'HALL 2', 50, 'Normal', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Normal', 2, 1),
(17, 'COMPUTER LAB 1', 50, 'Lab', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Computer', 1, 1),
(18, 'COMPUTER LAB 2', 50, 'Lab', 'active', '2026-04-24 06:49:34', '2026-04-24 06:49:34', 'Computer', 2, 1),
(19, 'ROOM 1', 50, 'Normal', 'active', '2026-04-25 01:36:54', '2026-04-25 01:36:54', 'Normal', 1, NULL),
(20, 'ROOM1', 50, 'Normal', 'active', '2026-04-25 01:39:11', '2026-04-25 01:39:11', 'Normal', 1, 2),
(21, 'ROO2', 60, 'Normal', 'active', '2026-04-25 01:39:35', '2026-04-25 01:39:35', 'Normal', 1, 2),
(22, 'ROOM 3', 40, 'Normal', 'active', '2026-04-25 01:39:56', '2026-04-25 01:39:56', 'Normal', 2, 2),
(23, 'ROOM 4', 50, 'Normal', 'active', '2026-04-25 01:40:14', '2026-04-25 01:40:14', 'Normal', 3, 2),
(24, 'ROOM 5', 40, 'Normal', 'active', '2026-04-25 01:40:32', '2026-04-25 01:40:32', 'Normal', 1, 2),
(25, 'ROOM 6', 46, 'Normal', 'active', '2026-04-25 01:41:26', '2026-04-25 01:41:26', 'Normal', 2, 2),
(26, 'COMPUTER LAB', 50, 'Lab', 'active', '2026-04-25 01:41:56', '2026-04-25 01:41:56', 'Computer', 1, 2),
(27, 'TYPING ROOM', 50, 'Lab', 'active', '2026-04-27 13:37:03', '2026-04-27 13:37:03', 'Typing Room', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `semesters`
--

CREATE TABLE `semesters` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `semName` varchar(255) DEFAULT NULL,
  `academic_year` varchar(255) DEFAULT NULL,
  `start_date` varchar(255) DEFAULT NULL,
  `end_date` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'InActive',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `semCode` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `semesters`
--

INSERT INTO `semesters` (`id`, `semName`, `academic_year`, `start_date`, `end_date`, `status`, `created_at`, `updated_at`, `semCode`) VALUES
(3, 'SEMESTER 2', '2026/2027', '2026-04-24', '2026-08-24', 'Active', '2026-04-24 12:40:13', '2026-04-24 12:41:24', 'S2/2025-2026'),
(4, 'SEMESTER 1', '2026/2027', '2026-04-24', '2026-08-24', 'Active', '2026-04-24 12:40:44', '2026-04-24 12:41:28', 'S1/2025-2026'),
(5, 'SEMESTER 4', '2026/2027', '2026-04-24', '2026-08-24', 'Active', '2026-04-24 12:41:19', '2026-04-24 12:41:33', 'S4/2025-2026');

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
('EZLRbBjA0AxQZ1jynxpD6UtA7Yzy1xu6EO9D1bnw', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/148.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWVh6UWFZUEd6VjVxV0NmRVpXd0lZT0NVNUpFdjZ6OHhmQWZjaHl5dyI7czo5OiJfcHJldmlvdXMiO2E6MTp7czozOiJ1cmwiO3M6NTk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC90aW1ldGFibGUvZ2VuZXJhdGU/Y291cnNlPTEmbnRhPU5UQS01Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319czo1NDoibG9naW5fdGVhY2hlcl81OWJhMzZhZGRjMmIyZjk0MDE1ODBmMDE0YzdmNThlYTRlMzA5ODlkIjtpOjE7czoxODoibGFzdF90aW1ldGFibGVfdXJsIjtzOjU5OiJodHRwOi8vMTI3LjAuMC4xOjgwMDAvdGltZXRhYmxlL2dlbmVyYXRlP2NvdXJzZT0xJm50YT1OVEEtNSI7fQ==', 1779683358);

-- --------------------------------------------------------

--
-- Table structure for table `subjects`
--

CREATE TABLE `subjects` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `subjectName` varchar(255) NOT NULL,
  `subjectCode` varchar(255) NOT NULL,
  `subject_type` varchar(255) NOT NULL,
  `required_lab` varchar(255) NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `nta_level` varchar(255) NOT NULL,
  `shared_group` varchar(255) DEFAULT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `credit_hour` int(11) NOT NULL,
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `subjects`
--

INSERT INTO `subjects` (`id`, `subjectName`, `subjectCode`, `subject_type`, `required_lab`, `teacher_id`, `course_id`, `nta_level`, `shared_group`, `semester_id`, `group_name`, `created_at`, `updated_at`, `credit_hour`, `branch_id`) VALUES
(771, 'COMMUNICATION SKILLS', 'BIT04101', 'Theory', 'Theory', 53, 1, 'NTA-4', NULL, 4, 'COMMUNICATION SKILLS', '2026-04-25 08:07:35', '2026-05-03 10:25:58', 3, 1),
(772, 'PRINCIPLES OF BOOK KEEPING', 'BIT04102', 'Theory', 'Theory', 36, 1, 'NTA-4', NULL, 4, 'PRINCIPLES OF BOOK KEEPING', '2026-04-25 08:07:35', '2026-04-26 04:58:34', 3, 1),
(773, 'PROCUREMENT AND SUPPLY CHAIN MANAGEMENT', 'BIT04103', 'Theory', 'Theory', 40, 1, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(774, 'BUSINESS MATHEMATICS AND STATISTICS', 'BIT04104', 'Theory', 'Theory', 100, 1, 'NTA-4', NULL, 4, 'BUSINESS MATHEMATICS AND STATISTICS2', '2026-04-25 08:07:35', '2026-05-03 22:23:32', 3, 1),
(775, 'COMPUTER SYSTEM AND APPLICATIONS', 'BIT04105', 'Practical', 'Computer', 46, 1, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(776, 'BASIC OF DATA COMMUNICATIONS AND NETWORKS', 'BIT04106', 'Theory', 'Theory', 45, 1, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(777, 'MARKETING MANAGEMENT', 'BIT04207', 'Theory', 'Theory', 44, 1, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(778, 'COMPUTER SYSTEM MAINTANACE AND REPAIR', 'BIT04208', 'Theory', 'Theory', 43, 1, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(779, 'ENTREPREURSHIP', 'BIT04209', 'Theory', 'Theory', 24, 1, 'NTA-4', NULL, 3, 'ENTREPREURSHIP', '2026-04-25 08:07:35', '2026-04-26 05:28:21', 3, 1),
(780, 'INFORMATION TECHNOLOGY SOFTWARE AND APPLICATION', 'BIT04210', 'Theory', 'Theory', 46, 1, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(781, 'WEB DEVELOPMENT', 'BIT04211', 'Theory', 'Theory', 46, 1, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(782, 'MICROECONOMICS', 'BIT04212', 'Theory', 'Theory', 50, 1, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(783, 'BUSINESS COMMUNICATION SKILLS', 'BIT05101', 'Theory', 'Theory', 53, 1, 'NTA-5', NULL, 4, 'BUSINESS COMMUNICATION SKILLS1', '2026-04-25 08:07:35', '2026-05-03 10:35:49', 3, 1),
(784, 'ACCOUNTING PRINCIPLES', 'BIT05102', 'Theory', 'Theory', 47, 1, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(785, 'DATABASE MANAGEMENT SYSTEM', 'BIT05103', 'Practical', 'Computer', 46, 1, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-29 05:07:01', 3, 1),
(786, 'BUSINESS MATHEMATICS AND STATISTICS', 'BIT05104', 'Theory', 'Theory', 122, 1, 'NTA-5', NULL, 4, 'BUSINESS MATHEMATICS AND STATISTICS3', '2026-04-25 08:07:35', '2026-05-25 01:17:00', 3, 1),
(787, 'COMPUTER APPLICATIONS AND WEBTECHNOLOGY', 'BIT05105', 'Practical', 'Computer', 41, 1, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(788, 'COMPUTER PROGRAMING', 'BIT05106', 'Theory', 'Theory', 41, 1, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(789, 'MANAGEMENT PRINCIPLES', 'BIT05207', 'Theory', 'Theory', 48, 1, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(790, 'PROCUREMENT AND SUPPLY', 'BIT05208', 'Theory', 'Theory', 68, 1, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-28 13:51:20', 3, 1),
(791, 'REPRENEURSHIP AND MARKETING PRINCIPLES', 'GST05209', 'Theory', 'Theory', 6, 1, 'NTA-5', NULL, 3, 'ENTREPRENEURSHIP SKILLS3', '2026-04-25 08:07:35', '2026-05-04 20:16:50', 3, 1),
(792, 'COMPUTER NETWORKING', 'BIT05210', 'Theory', 'Theory', 54, 1, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(793, 'COMPUTER SYSTEM MAINTENANCE AND REPAIR', 'BIT05211', 'Theory', 'Theory', 43, 1, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(794, 'PRINCIPLES OF MARKETING MANAGEMENT', 'BIT06101', 'Theory', 'Theory', 36, 1, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-05-04 10:53:26', 3, 1),
(795, 'SALES MANAGEMENT', 'BIT06106', 'Theory', 'Theory', 48, 1, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-05-03 22:10:57', 3, 1),
(796, 'PRINCIPLES OF FINANCE MANAGEMENT', 'BIT06102', 'Theory', 'Theory', 47, 1, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-05-04 20:22:22', 3, 1),
(797, 'OBJECT ORIENTED PROGRAMMING', 'BIT06103', 'Theory', 'Theory', 41, 1, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-05-03 10:17:10', 3, 1),
(798, 'MANAGEMENT INFORMATIONS SYSTEM IPO 2', 'GST06105', 'Theory', 'Theory', 46, 1, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-29 23:18:42', 3, 1),
(799, 'RESEARCH METHODS FOR BUSINESS', 'BIT06105', 'Theory', 'Theory', 17, 1, 'NTA-6', NULL, 4, 'RESEARCH METHODS FOR BUSINESS', '2026-04-25 08:07:35', '2026-05-04 20:10:11', 3, 1),
(801, 'PRINCIPLES OF E-BUSINESS', 'BIT06208', 'Theory', 'Theory', 35, 1, 'NTA-6', NULL, 3, 'PRINCIPLES OF E-BUSINESS', '2026-04-25 08:07:35', '2026-05-03 10:48:26', 3, 1),
(802, 'BUSINESS LAW AND ETHICS', 'GST06209', 'Theory', 'Theory', 12, 1, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(803, ' BUSINESS APPLICATION PACKAGES', 'BIT06210', 'Theory', 'Theory', 41, 1, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:35', '2026-04-25 08:07:35', 3, 1),
(804, 'CUSTOMER RELATIONSHIP MANAGEMENT', 'GST06211', 'Theory', 'Theory', 35, 1, 'NTA-6', NULL, 3, 'CUSTOMER RELATIONSHIP MANAGEMENT', '2026-04-25 08:07:35', '2026-05-03 10:50:09', 3, 1),
(805, 'BASIC STORE ADMINISTRATION', 'PST04101', 'Theory', 'Theory', 67, 4, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:35', '2026-05-18 09:50:11', 3, 1),
(806, 'BASIC BUSINESS MATHEMATICS AND STSTISTICS', 'GST04102', 'Theory', 'Theory', 62, 4, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(807, 'BASIC BUSINESS COMMUNICATION SKILLS', 'GST04103', 'Theory', 'Theory', 22, 4, 'NTA-4', NULL, 4, 'BASIC BUSINESS COMMUNICATION', '2026-04-25 08:07:36', '2026-04-26 04:49:10', 3, 1),
(808, 'ELEMENTS OF ENTREPRENEURSHIP', 'GST04104', 'Theory', 'Theory', 42, 4, 'NTA-4', NULL, 4, 'ELEMENTS OF ENTREPRENEURSHIP', '2026-04-25 08:07:36', '2026-04-26 05:33:04', 3, 1),
(809, 'BASIC COMPUTER APPLICATIONS', 'GST04105', 'Practical', 'Computer', 41, 4, 'NTA-4', NULL, 4, 'BASIC COMPUTER APPLICATIONS', '2026-04-25 08:07:36', '2026-05-02 04:31:21', 3, 1),
(810, 'BASIC PROCUREMENT PRINCIPLES', 'PST04106', 'Theory', 'Theory', 40, 4, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(811, 'ELEMENTS OF PUBLIC PROCUREMENT', 'PST04207', 'Theory', 'Theory', 40, 4, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(812, 'BASIC BOOK KEEPING', 'GST04208', 'Theory', 'Theory', 36, 4, 'NTA-4', NULL, 3, 'PRINCIPLES OF BOOK KEEPING', '2026-04-25 08:07:36', '2026-05-03 11:01:00', 3, 1),
(813, 'BASICS OF CLEARING AND FORWARDING', 'PST04209', 'Theory', 'Theory', 42, 4, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(814, 'ELEMENTS OF MARKETING', 'GST04210', 'Theory', 'Theory', 44, 4, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-10 05:31:36', 3, 1),
(815, 'BASIC STOCK CONTROL', 'PST04211', 'Theory', 'Theory', 67, 4, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 08:48:55', 3, 1),
(816, 'PRINCIPLES OF MARKETING AND CUSTOMER CARE', 'GST05207', 'Theory', 'Theory', 44, 4, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(817, 'PRINCIPLES OF PROCUREMENT', 'PST05208', 'Theory', 'Theory', 112, 4, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-16 02:33:14', 3, 1),
(818, 'FREIGHT CLEARING AND FORWARDING', 'PST05209', 'Theory', 'Theory', 42, 4, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(819, 'FUNDAMENTALS OF COST ACCOUNTING', 'PST05210', 'Theory', 'Theory', 52, 4, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(820, 'FINANCIAL ACCOUNTING', 'PST05211', 'Theory', 'Theory', 62, 4, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-18 03:25:24', 3, 1),
(821, 'PRINCIPLES OF PUBLIC PROCUREMENT', 'PST06207', 'Theory', 'Theory', 40, 4, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(822, 'PRINCIPLES OF INVENTORY MANAGEMENT', 'PST06208', 'Theory', 'Theory', 42, 4, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(823, 'BUSINESS LAW', 'GST06209', 'Theory', 'Theory', 12, 4, 'NTA-6', NULL, 3, 'BUSINESS LAW', '2026-04-25 08:07:36', '2026-05-03 10:01:03', 3, 1),
(824, 'PRINCIPLES OF LOGISTIC MANAGEMENT', 'PST06210', 'Theory', 'Theory', 68, 4, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 08:41:15', 3, 1),
(825, 'RESEARCH METHODOLOGY', 'GST06211', 'Theory', 'Theory', 32, 4, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(826, 'BASIC BUSINESS COMMUNICATION', 'BMT04101', 'Theory', 'Theory', 22, 2, 'NTA-4', NULL, 4, 'BASIC BUSINESS COMMUNICATION', '2026-04-25 08:07:36', '2026-04-26 04:48:45', 3, 1),
(827, 'ELEMENTS OF ENTREPRENEURSHIP', 'BMT04102', 'Theory', 'Theory', 24, 2, 'NTA-4', NULL, 4, 'ENTREPREURSHIP', '2026-04-25 08:07:36', '2026-05-03 10:04:40', 3, 1),
(828, 'PRINCIPLES OF BOOK KEEPING', 'BMT04103', 'Theory', 'Theory', 36, 2, 'NTA-4', NULL, 4, 'PRINCIPLES OF BOOK KEEPING', '2026-04-25 08:07:36', '2026-04-26 04:59:06', 3, 1),
(829, 'BASIC BUSINESS MATHEMATICS', 'BMT04104', 'Theory', 'Theory', 37, 2, 'NTA-4', NULL, 4, 'BASIC BUSINESS MATHEMATICS', '2026-04-25 08:07:36', '2026-04-26 04:53:12', 3, 1),
(830, 'BASIC COMPUTER APPLICATIONS', 'BMT04105', 'Practical', 'Computer', 41, 2, 'NTA-4', NULL, 4, 'BASIC COMPUTER APPLICATIONS', '2026-04-25 08:07:36', '2026-05-02 04:30:54', 3, 1),
(831, 'FUNDAMENTALS OF BUSINESS', 'BMT04106', 'Theory', 'Theory', 48, 2, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-28 21:21:09', 3, 1),
(832, 'ELEMENTS OF MARKETING', 'BMT04207', 'Theory', 'Theory', 35, 2, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 10:59:21', 3, 1),
(833, 'BASIC PROCUREMENT AND SUPPLY', 'BMT04208', 'Theory', 'Theory', 42, 2, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(834, 'ELEMENT OF COMMERCE', 'BMT04209', 'Theory', 'Theory', 47, 2, 'NTA-4', NULL, 3, 'ELEMENT OF COMMERCE', '2026-04-25 08:07:36', '2026-05-03 11:05:45', 3, 1),
(835, 'FUNDAMENTALS OF SALESMANSHIP', 'BMT04210', 'Theory', 'Theory', 36, 2, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(836, 'BASICS OF BUSINESS MANAGEMENT', 'BMT04211', 'Theory', 'Theory', 48, 2, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 22:20:02', 3, 1),
(838, 'PRINCIPLES OF MARKETING', 'BMT05207', 'Theory', 'Theory', 100, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 12:03:55', 3, 1),
(839, 'PRINCIPLES OF PROCUREMENT AND SUPPLY', 'BMT05208', 'Theory', 'Theory', 40, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(840, 'ECONOMICS', 'BMT05209', 'Theory', 'Theory', 50, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(841, 'PRINCIPLES OF SALES MANAGEMENT', 'BMT05210', 'Theory', 'Theory', 48, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 10:44:20', 3, 1),
(842, 'CLEARING AND FORWARDING', 'BMT05211', 'Theory', 'Theory', 42, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(843, 'COMPUTERIZED ACCOUNTING', 'BMT05212', 'Theory', 'Theory', 47, 2, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(844, 'PRINCIPLES OF E-BUSINESS', 'BMT06207', 'Theory', 'Theory', 35, 2, 'NTA-6', NULL, 3, 'PRINCIPLES OF E-BUSINESS', '2026-04-25 08:07:36', '2026-05-03 10:48:07', 3, 1),
(845, 'PRODUCTION MANAGEMENT', 'BMT06208', 'Theory', 'Theory', 36, 2, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 10:58:53', 3, 1),
(846, 'BUSINESS RESEARCH METHODS', 'BMT06209', 'Theory', 'Theory', 17, 2, 'NTA-6', NULL, 3, 'RESEARCH METHODS FOR BUSINESS', '2026-04-25 08:07:36', '2026-04-27 09:27:12', 3, 1),
(847, 'COST ACCOUNTING', 'BMT06210', 'Theory', 'Theory', 52, 2, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(848, 'BASIC BUSINESS COMMUNICATION SKILLS', 'EFT04101', 'Theory', 'Theory', 22, 3, 'NTA-4', NULL, 4, 'BASIC BUSINESS COMMUNICATION', '2026-04-25 08:07:36', '2026-05-03 10:39:33', 3, 1),
(849, 'ELEMENTS OF ECONOMICS', 'EFT04102', 'Theory', 'Theory', 55, 3, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-05-06 08:08:27', 3, 1),
(850, 'ELEMENTS OF FINANCE', 'EFT04103', 'Theory', 'Theory', 47, 3, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(851, 'ELEMENTS OF BUSINESS MATHEMATICS', 'GST04104', 'Theory', 'Theory', 37, 3, 'NTA-4', NULL, 4, 'BASIC BUSINESS MATHEMATICS', '2026-04-25 08:07:36', '2026-04-26 04:53:42', 3, 1),
(852, 'BASICS COMPUTER APPLICATIONS', 'GST04105', 'Practical', 'Computer', 41, 3, 'NTA-4', NULL, 4, 'BASIC COMPUTER APPLICATIONS', '2026-04-25 08:07:36', '2026-05-02 04:44:53', 3, 1),
(853, 'ELEMENTS OF COMMERCE', 'GST04106', 'Theory', 'Theory', 47, 3, 'NTA-4', NULL, 4, 'ELEMENT OF COMMERCE', '2026-04-25 08:07:36', '2026-05-03 11:06:03', 3, 1),
(854, 'FUNDAMENTLS OF MONEY AND BANKING', 'EFT04207', 'Theory', 'Theory', 38, 3, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(855, 'FUNDAMENTS OF TAXATION', 'EFT04208', 'Theory', 'Theory', 56, 3, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(856, 'ELEMENTS OF ENTREPRENEURSHIP', 'EFT04209', 'Theory', 'Theory', 24, 3, 'NTA-4', NULL, 3, 'ENTREPREURSHIP', '2026-04-25 08:07:36', '2026-04-26 05:29:01', 3, 1),
(857, 'PRINCIPLES OF BOOK KEEPING ', 'EFT04210', 'Theory', 'Theory', 47, 3, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(858, 'INFORMATION AND COMMUNICATION TECHNOLOGY', 'EFT05207', 'Theory', 'Theory', 72, 3, 'NTA-5', NULL, 3, 'INFORMATION AND COMMUNICATION TECHNOLOGY1', '2026-04-25 08:07:36', '2026-05-04 11:23:33', 3, 1),
(859, 'FINANCIAL MANAGEMENT SKILLS', 'EFT05208', 'Theory', 'Theory', 56, 3, 'NTA-5', NULL, 3, 'FINANCIAL MANAGEMENT SKILLS', '2026-04-25 08:07:36', '2026-04-26 04:51:11', 3, 1),
(860, 'ENTREPRENEURSHIP SKILLS', 'EFT05209', 'Theory', 'Theory', 6, 3, 'NTA-5', NULL, 3, 'ENTREPRENEURSHIP SKILLS3', '2026-04-25 08:07:36', '2026-05-04 20:16:30', 3, 1),
(861, 'BURGET PLANNING AND ANALYSIS', 'EFT05210', 'Theory', 'Theory', 101, 3, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 11:36:12', 3, 1),
(862, 'MACROECONOMICS', 'EFT05211', 'Theory', 'Theory', 50, 3, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(863, 'PUBLIC FINANCE', 'EFT05212', 'Theory', 'Theory', 55, 3, 'NTA-5', NULL, 3, 'PUBLIC FINANCE', '2026-04-25 08:07:36', '2026-04-26 04:55:34', 3, 1),
(864, 'QUANTATIVE METHODS', 'EFT06101', 'Theory', 'Theory', 52, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-05-04 20:24:20', 3, 1),
(865, 'TAXATION THEORY AND PRACTICE', 'EFT06102', 'Theory', 'Theory', 56, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(866, 'PROJECT PLANNING AND MANAGEMENT', 'EFT06103', 'Theory', 'Theory', 61, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(867, 'ACCOUNTING SOFTWARE', 'EFT06104', 'Theory', 'Theory', 56, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-05-04 10:57:44', 3, 1),
(868, 'ISLAMIC FINANCE', 'EFT06105', 'Theory', 'Theory', 38, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(869, 'PRINCIPLES OF MICROECONOMICS', 'EFT06106', 'Theory', 'Theory', 50, 3, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(870, 'ECONOMICS OF INTERNATIONAL TRADE', 'EFT06207', 'Theory', 'Theory', 38, 3, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(871, 'FINANCIAL MARKETS AND INSTITUTIONS', 'EFT06208', 'Theory', 'Theory', 36, 3, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(872, 'BUSINESS LAW', 'GST06209', 'Theory', 'Theory', 12, 3, 'NTA-6', NULL, 3, 'BUSINESS LAW', '2026-04-25 08:07:36', '2026-05-03 10:01:22', 3, 1),
(873, 'BASICS OF MONETARY POLICY', 'EFT06210', 'Theory', 'Theory', 38, 3, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(874, 'RESEARCH METHODS FOR BUSINESS', 'GST06211', 'Theory', 'Theory', 17, 3, 'NTA-6', NULL, 3, 'RESEARCH METHODS FOR BUSINESS', '2026-04-25 08:07:36', '2026-05-03 22:18:19', 3, 1),
(875, 'BASIC DIPLOMATIC COMMUNICATION SKILLS', 'GST04101', 'Theory', 'Theory', 59, 10, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:20:16', 3, 1),
(876, 'ELEMENTS OF INTERNATIONAL RELATIONS', 'IRT04102', 'Theory', 'Theory', 27, 10, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:16:22', 3, 1),
(877, 'ELEMENTS OF FOREIGN LANGUAGES', 'GST04103', 'Theory', 'Theory', 58, 10, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(878, 'BASIC COMPUTER APPLICATIONS', 'GST04104', 'Practical', 'Computer', 43, 10, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(879, 'ELEMENTS OF PROTOCOL AND ETIQUETTES', 'IRT04105', 'Theory', 'Theory', 26, 10, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(880, 'BASICS OF INTERNATIONAL POLITICAL ECONOMY', 'IRT04210', 'Theory', 'Theory', 114, 10, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 22:10:24', 3, 1),
(881, 'BASICS OF ENTREPRENEURSHIP', 'GST04206', 'Theory', 'Theory', 42, 10, 'NTA-4', NULL, 3, 'ELEMENTS OF ENTREPRENEURSHIP', '2026-04-25 08:07:36', '2026-04-26 05:34:34', 3, 1),
(882, 'ELEMENTS OF GLOBAL GOVERNANCE AND POLITICS', 'IRT04208', 'Theory', 'Theory', 102, 10, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 11:40:42', 3, 1),
(883, 'ELEMENTS OF ECONOMICS', 'GST04209', 'Theory', 'Theory', 50, 10, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-06 08:07:50', 3, 1),
(884, 'FUNDAMENTAL OF POLITICAL SCIENCE', 'IRT04207', 'Theory', 'Theory', 29, 10, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(885, 'DIPLOMATIC COMMUNICATION SKILLS', 'GST05101', 'Theory', 'Theory', 53, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(886, 'PRINCIPLES OF INTERNATIONAL RELATIONS', 'IRT05102', 'Theory', 'Theory', 27, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:17:36', 3, 1),
(887, 'DIPLOMATIC PROTOCOL AND ETIQUETTES', 'IRT05103', 'Theory', 'Theory', 26, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(888, 'COMPUTER APPLICATIONS', 'GST05104', 'Practical', 'Computer', 43, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(889, 'FOREIGN LANGUAGES', 'GST05105', 'Theory', 'Theory', 103, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 11:42:44', 3, 1),
(890, 'PRINCIPLES OF ECONOMICS', 'GST05106', 'Theory', 'Theory', 55, 10, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(891, 'ENTREPRENEURSHIP AND SMALL BUSINESS MANAGEMENT', 'GST05207', 'Theory', 'Theory', 53, 10, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 10:37:26', 3, 1),
(892, 'ETHICS IN INTERNATIONAL RELATIONS AND DIPLOMACY', 'IRT05208', 'Theory', 'Theory', 102, 10, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 09:38:05', 3, 1),
(893, 'POLITICAL SCIENCE', 'IRT05209', 'Theory', 'Theory', 29, 10, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(894, 'INTERNATIONAL POLITICAL ECONOMY', 'IRT05210', 'Theory', 'Theory', 59, 10, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(895, 'NATIONAL COHENSION AND SOCIAL INTERGRATION', 'GST05211', 'Theory', 'Theory', 27, 10, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 08:15:27', 3, 1),
(896, 'MANAGEMENT IN INTERNATIONAL RELATIONS AND DIPLOMACY', 'GST06101', 'Theory', 'Theory', 59, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(897, 'PUBLIC INTERNATIONAL LAW', 'IRT06102', 'Theory', 'Theory', 12, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:08:55', 3, 1),
(898, 'INTERNATIONAL ORGANIZATION & REGIONAL INTEGRATION', 'IRT06103', 'Theory', 'Theory', 27, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:14:53', 3, 1),
(899, 'RESEARCH METHODOLOGY', 'GST06104', 'Theory', 'Theory', 32, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 08:08:16', 3, 1),
(900, 'NEGOTIATION SKILLS', 'GST06105', 'Theory', 'Theory', 26, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(901, 'ECONOMIC DIPLOMACY', 'IRT06106', 'Theory', 'Theory', 53, 10, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(902, 'CONTEMPORARY ISSUES IN INTERNATIONAL RELATIONS AND DIPLOMACY', 'IRT06207', 'Theory', 'Theory', 105, 10, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 11:54:42', 3, 1),
(903, 'INTERNATIONAL TRADE & INVESTMENTS', 'IRT06208', 'Theory', 'Theory', 52, 10, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(904, 'COMPARATIVE FOREIGN POLICY', 'IRT06209', 'Theory', 'Theory', 59, 10, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(905, 'CONFLICT MANAGEMENT AND RESOLUTION', 'IRT06210', 'Theory', 'Theory', 114, 10, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 09:18:55', 3, 1),
(907, 'FOUNDATION OF EFFECTIVE COMMUNICATIONS', 'PAT04101', 'Theory', 'Theory', 104, 11, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 11:52:11', 3, 1),
(908, 'INTRODUCTION TO ADMINISTRATIVE SYSTEMS', 'PAT04102', 'Theory', 'Theory', 23, 11, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(909, 'FUNDAMENTALS OF BOOK KEEPING AND ACCOUNTING PRINCIPLES', 'PAT04103', 'Theory', 'Theory', 47, 11, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(910, 'ESSENTIALS OF CUSTOMER SERVICES', 'PAT04104', 'Theory', 'Theory', 104, 11, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-25 11:53:04', 3, 1),
(911, 'INTRODUCTION TO COMPUTER LITERACY', 'PAT04105', 'Practical', 'Computer', 37, 11, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:36', '2026-04-27 13:50:34', 3, 1),
(913, 'BASICS OF ENTREPRENEURSHIP', 'PAT04207', 'Theory', 'Theory', 42, 11, 'NTA-4', NULL, 3, 'ELEMENTS OF ENTREPRENEURSHIP', '2026-04-25 08:07:36', '2026-04-26 05:35:16', 3, 1),
(914, 'FUNDAMENTALS OF MANAGEMENT PRINCIPLES', 'PAT04208', 'Theory', 'Theory', 15, 11, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-08 09:13:17', 3, 1),
(915, 'FUNDAMENTALS OF PUBLIC ENTERPRISES', 'PAT04209', 'Theory', 'Theory', 12, 11, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-28 21:24:02', 3, 1),
(916, 'ELEMETS OF GOOD GOVERNSNCE', 'PAT04210', 'Theory', 'Theory', 8, 11, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(917, 'FUNDAMENTALS OF LOCAL GOVERNMENT ADMINISTRATION', 'PAT04211', 'Theory', 'Theory', 29, 11, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(919, 'CONTEMPORARY ISSUES IN PUBLIC ADMINISTRATION', 'PAT05207', 'Theory', 'Theory', 23, 11, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(920, 'PUBLIC-PRIVATE PARTNERSHIP AND COLLABORATIVE GOVERNANCE', 'PAT05208', 'Theory', 'Theory', 29, 11, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(921, 'CUSTOMER CARE AND CODE OF ETHICS', 'PAT05209', 'Theory', 'Theory', 21, 11, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-05-03 22:27:37', 3, 1),
(922, 'PERFORMANCE MANAGEMENT', 'PAT05210', 'Theory', 'Theory', 69, 11, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-28 21:27:00', 3, 1),
(923, 'ORGANIZATION BEHAVIOUR', 'PAT05211', 'Theory', 'Theory', 3, 11, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-25 08:07:36', 3, 1),
(924, 'PUBLIC FINANCIAL MANAGEMENT', 'PAT05212', 'Theory', 'Theory', 56, 11, 'NTA-5', NULL, 3, 'FINANCIAL MANAGEMENT SKILLS', '2026-04-25 08:07:36', '2026-04-26 04:51:39', 3, 1),
(925, 'PRINCIPLES OF PUBLIC PROCUREMENT', 'PCT06207', 'Theory', 'Theory', 68, 11, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:36', '2026-04-27 09:07:19', 3, 1),
(926, 'CUSTOMER RELATIONS MANAGEMENT', 'PCT06208', 'Theory', 'Theory', 35, 11, 'NTA-6', NULL, 3, 'CUSTOMER RELATIONSHIP MANAGEMENT', '2026-04-25 08:07:36', '2026-05-03 10:50:41', 3, 1),
(927, 'BASICS OF PUBLIC POLICY', 'PCT06209', 'Theory', 'Theory', 29, 11, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(928, 'FUNDAMENTALS OF PUBLIC FINANCE', 'PCT06210', 'Theory', 'Theory', 55, 11, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(929, 'RESEARCH METHODOLOGY', 'GST06211', 'Theory', 'Theory', 32, 11, 'NTA-6', NULL, 3, 'RESEARCH METHODOLOGY1', '2026-04-25 08:07:37', '2026-05-04 11:09:25', 3, 1),
(930, 'EFFECTIVE COMMUNICATIONS STRATEGIES', 'PRT04101', 'Theory', 'Theory', 21, 12, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(931, 'FUNDAMENTALS OF MARKETING', 'PRT04102', 'Theory', 'Theory', 21, 12, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-16 02:34:40', 3, 1),
(932, 'PROFICIENT PUBLIC RELATIONS TECHNIQUES', 'PRT04103', 'Theory', 'Theory', 113, 12, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-27 09:13:19', 3, 1),
(933, 'FUNDAMENTLS CUSTOMER CARE PRACTICE', 'PRT04104', 'Theory', 'Theory', 104, 12, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-16 02:36:02', 3, 1),
(934, 'ESSENTIAL COMPUTER APPLICATIONS', 'PRT04105', 'Practical', 'Computer', 41, 12, 'NTA-4', NULL, 4, 'BASIC COMPUTER APPLICATIONS', '2026-04-25 08:07:37', '2026-05-03 10:19:10', 3, 1),
(935, ' EVENT COORDINATION AND PROTOCOL MANAGEMENT', 'PRT04206', 'Theory', 'Theory', 26, 12, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(936, 'FUNDAMENTALS OF ADVERTISING', 'PRT04207', 'Theory', 'Theory', 44, 12, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(937, 'INTRODUCTION TO MASS COMMUNICATION', 'PRT04208', 'Theory', 'Theory', 21, 12, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(938, 'PUBLIC RELATIONS WRITING AND PRESENTATION TECHNIQUES', 'PRT04209', 'Theory', 'Theory', 44, 12, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(939, 'PRACTICAL TRAINING IN PUBLIC RELATIONS FIELDWORK', 'PRT04210', 'Theory', 'Theory', 29, 12, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-04 11:39:51', 3, 1),
(940, 'ESSENTIALS OF ENTREPRENEURIAL SKILLS', 'PRT04211', 'Theory', 'Theory', 24, 12, 'NTA-4', NULL, 3, 'ENTREPREURSHIP', '2026-04-25 08:07:37', '2026-04-26 05:29:53', 3, 1),
(941, 'FUNDAMENTALS OF RECORDS MANAGEMNET', 'RMGT04101', 'Theory', 'Theory', 30, 5, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(942, 'OFFICE ORGANIZATION', 'RMGT04102', 'Theory', 'Theory', 30, 5, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-04 11:49:10', 3, 1),
(943, 'BASIC MATHEMATICS AND STATISTICS', 'RMGT04103', 'Theory', 'Theory', 100, 5, 'NTA-4', NULL, 4, 'BUSINESS MATHEMATICS AND STATISTICS2', '2026-04-25 08:07:37', '2026-05-03 22:24:15', 3, 1),
(944, 'COMMUNICATION SKILLS', 'RMGT04104', 'Theory', 'Theory', 53, 5, 'NTA-4', NULL, 4, 'COMMUNICATION SKILLS', '2026-04-25 08:07:37', '2026-05-03 10:26:34', 3, 1),
(945, 'BASIC LEGAL RECORDS MANAGEMENT', 'RMGT04105', 'Theory', 'Theory', 6, 5, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(946, 'FUNDAMENTALS OF ELECTRONIC RECORDS MANAGEMENT', 'RMGT04106', 'Theory', 'Theory', 18, 5, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(947, 'RECORDS OFFICE PROCEDURES AND PRACTICES', 'RMGT04201', 'Theory', 'Theory', 33, 5, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(948, 'FUNDAMENTALS OF PERSONAL AND INTERPERSONAL SKILLS', 'RMGT04202', 'Theory', 'Theory', 21, 5, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(949, 'BASIC COMPUTER APPLICATIONS', 'RMGT04203', 'Practical', 'Computer', 45, 5, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(950, 'FUNDAMENTALS OF ARCHIVES MANAGEMENT', 'RMGT04204', 'Theory', 'Theory', 18, 5, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(951, 'FUNDAMENTALS OF CONSERVATION OF RECORDS AND ARCHIVAL MATERIALS', 'RMGT04205', 'Theory', 'Theory', 30, 5, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(952, 'PRINCIPLES AND FUNCTIONS OF MANAGEMENT', 'RMT05101', 'Theory', 'Theory', 23, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-08 09:12:26', 3, 1),
(953, 'COMMUNICATIONS SKILLS', 'RMT05102', 'Theory', 'Theory', 23, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(954, 'BASIC COMPUTER APPLICATIONS', 'GST05105', 'Practical', 'Computer', 45, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(955, 'ENTERPREURSHIP IN RECORDS AND INFORMATIONS WORKS', 'RMT05104', 'Theory', 'Theory', 6, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(956, 'DEVELOPMENT STUDIES', 'RMT05105', 'Theory', 'Theory', 8, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(957, 'FUNDAMENTALS OF RECORDS AND ARCHIVES MANAGEMENT', 'RMT05106', 'Theory', 'Theory', 30, 5, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(958, 'RECORDS OFFICE PROCEDURES AND PRACTICES.', 'RMT05201', 'Theory', 'Theory', 24, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(959, 'ARCHIVES MANAGEMENT PRINCIPLES AND PRACTICES', 'RMT05202', 'Theory', 'Theory', 18, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-27 09:08:45', 3, 1),
(960, 'CONSERVATION OF RECORDS AND ARCHIVAL MATERIALS', 'RMT05203', 'Theory', 'Theory', 18, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(961, 'COMPUTER APPLICATION', 'RMT05204', 'Practical', 'Computer', 45, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(962, 'FUNDAMENTAL OF MATHEMATICS AND STATISTICS.', 'RMT05205', 'Theory', 'Theory', 62, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(963, 'PRINCIPLES AND PROCEDURE OF ACCESS TO RECORDS AND ARCHIVES', 'RMT05206', 'Theory', 'Theory', 33, 5, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-04 11:49:54', 3, 1),
(964, 'ELECTRONIC RECORDS MANAGEMENT', 'RMGT06101', 'Theory', 'Theory', 33, 5, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(965, 'MANAGING RECORDS AND INFORMATIONS CENTRE', 'RMGT06102', 'Theory', 'Theory', 31, 5, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(966, 'ICT WITH RECORDS MANAGEMENT', 'RMGT06103', 'Theory', 'Theory', 54, 5, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(967, 'DATA BASE MANAGEMENT', 'RMGT06104', 'Theory', 'Theory', 54, 5, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(968, 'MANAGEMENT PRNCIPLES AND PRACTICES', 'RMGT06105', 'Theory', 'Theory', 120, 5, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-04 11:19:14', 3, 1),
(969, 'RESEARCH METHODOLOGY', 'RMGT06106', 'Theory', 'Theory', 32, 5, 'NTA-6', NULL, 4, 'RESEARCH METHODOLOGY1', '2026-04-25 08:07:37', '2026-05-04 11:10:40', 3, 1),
(970, 'LAND RECORDS', 'RMGT06201', 'Theory', 'Theory', 19, 5, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(971, 'LEGAL RECORDS', 'RMGT06202', 'Theory', 'Theory', 6, 5, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(972, 'MEDICAL RECORDS', 'RMGT06203', 'Theory', 'Theory', 33, 5, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(973, 'AUTOMATION OF RECORDS AND ARCHIVAL MATERIALS.', 'GST06204', 'Theory', 'Theory', 30, 5, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-27 09:03:46', 3, 1),
(974, 'OFFICE MANAGEMENT', 'GST06205', 'Theory', 'Theory', 18, 5, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-04 11:51:17', 3, 1),
(975, 'BASIC COMMUNICATION SKILLS', 'GST04101', 'Theory', 'Theory', 22, 7, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(976, 'BASIC PRINCIPLES OF EMPLOYEE RELATIONS', 'HRT04102', 'Theory', 'Theory', 13, 7, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(977, 'BASIC PUBLIC ADMINISTRATION', 'GST04103', 'Theory', 'Theory', 23, 7, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(978, 'BASICS OF ENTREPRENEURSHIP', 'GST04104', 'Theory', 'Theory', 42, 7, 'NTA-4', NULL, 4, 'ELEMENTS OF ENTREPRENEURSHIP', '2026-04-25 08:07:37', '2026-04-27 09:02:41', 3, 1),
(979, 'BASIC COMPUTER APPPLICATIONS', 'GST04105', 'Practical', 'Computer', 45, 7, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(980, 'FUNDAMENTALS OF OFFICE PRACTICE AND RECORDS MANAGEMENT', 'HRT04106', 'Theory', 'Theory', 18, 7, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-27 09:10:07', 3, 1),
(981, 'BASIC HUMAN RESOURCE MANAGEMENT', 'HRT04204', 'Theory', 'Theory', 13, 7, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(982, 'ELEMENTS OF GOOD GOVERNANCE', 'GST04201', 'Theory', 'Theory', 22, 7, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(983, 'BASIC PRINCIPLES OF MANAGEMENT', 'GST04203', 'Theory', 'Theory', 7, 7, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(984, 'FUNDAMENTALS OF LABOUR LAW', 'HRT04202', 'Theory', 'Theory', 12, 7, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(985, 'BASICS OF PERFORMANCE MANAGEMENT', 'HRT04205', 'Theory', 'Theory', 7, 7, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(986, 'BUSINESS COMMUNICATION SKILLS', 'GST05101', 'Theory', 'Theory', 53, 7, 'NTA-5', NULL, 4, 'BUSINESS COMMUNICATION SKILLS1', '2026-04-25 08:07:37', '2026-05-03 10:36:07', 3, 1),
(987, 'COMPUTER APPLICATION', 'GST05102', 'Practical', 'Computer', 37, 7, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-04 09:31:25', 3, 1),
(988, 'PRINCIPLES OF MANAGEMENT', 'HRT05103', 'Theory', 'Theory', 3, 7, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(989, 'PRINCIPLES OF HUMAN RESOURCE MANAGEMENT', 'HRT05104', 'Theory', 'Theory', 13, 7, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(990, 'ORGANIZATION BEHAVIOUR', 'HRT05105', 'Theory', 'Theory', 15, 7, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(991, 'ENTERPRENEURSHIP AND SMALL BUSINESS', 'GST05106', 'Theory', 'Theory', 16, 7, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(992, 'LEADERSHIP AND SUPERVISION SKILLS', 'HRT05201', 'Theory', 'Theory', 3, 7, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(993, 'STRATEGIC HUMAN RESOURCE MANAGEMENT', 'HRT05202', 'Theory', 'Theory', 15, 7, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(994, 'HR INFORMATION SYSTEMS', 'HRT05203', 'Theory', 'Theory', 54, 7, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(995, 'PERFORMANCE MANAGEMENT', 'HRT05204', 'Theory', 'Theory', 22, 7, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(996, 'OFFICE ORGANIZATION AND RECORDS MANAGEMENT', 'GST05205', 'Theory', 'Theory', 33, 7, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(997, 'HUMAN RESOURCE APPRAISAL AND DEVELOPMENT', 'HRT06101', 'Theory', 'Theory', 63, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-28 21:22:04', 3, 1),
(998, 'HUMAN RESOURCE PLANNING', 'HRT06102', 'Theory', 'Theory', 15, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(999, 'ENTERPRENEURSHIP MANAGEMENT', 'GST06103', 'Theory', 'Theory', 16, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1000, 'MANAGING ORGANIZATIONS', 'GST06104', 'Theory', 'Theory', 13, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-28 21:26:00', 3, 1),
(1001, 'EMPLOYEE STAFFING', 'HRT06105', 'Theory', 'Theory', 5, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1002, 'PERSONNEL RECORDS MANAGEMENT', 'HRT06106', 'Theory', 'Theory', 19, 7, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1003, 'INDUSTRIAL RELATIONS AND LABOUR LAW', 'HRT06201', 'Theory', 'Theory', 12, 7, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-28 21:23:05', 3, 1),
(1004, 'EMPLOYEE MOTIVATION', 'HRT06202', 'Theory', 'Theory', 5, 7, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1005, 'COMPENSATION AND BENEFIT ADMINISTRATION', 'HRT06203', 'Theory', 'Theory', 7, 7, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1006, 'MANAGING ORGANIZATION CULTURE', 'GST06204', 'Theory', 'Theory', 120, 7, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-03 22:07:40', 3, 1),
(1007, 'ESSENTIAL OF IT', 'SST04103', 'Theory', 'Theory', 41, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-02 04:34:24', 3, 1),
(1008, 'PRINCIPLES OF BUSINESS ENGLISH', 'SST0414', 'Theory', 'Theory', 21, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1009, 'OFFICE PRACTICES', 'SST04101', 'Theory', 'Theory', 24, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1010, 'LIFE SKILLS PRINCIPLES', 'SST04106', 'Theory', 'Theory', 4, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1011, 'PRINCIPLES OF SECRERIAL DUTIES', 'SST04105', 'Theory', 'Theory', 28, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1012, 'TYPING SKILLS PRINCIPLES', 'SST04102', 'Practical', 'Typing Room', 14, 9, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-27 13:40:34', 6, 1),
(1013, 'MSINGI WA HATIMKATO NADHARIA', 'SST04203', 'Theory', 'Theory', 24, 9, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-03 09:54:06', 6, 1),
(1014, 'OFFICE COMPUTER APPLICATIONS', 'SST04202', 'Practical', 'Computer', 25, 9, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1015, 'SHORTHAND THEORY PRINCIPLES', 'SST04204', 'Practical', 'Typing Room', 34, 9, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-28 00:23:58', 6, 1),
(1016, 'PUBLIC SERVICE ETHICS AND PATRIOTISM', 'SST04205', 'Theory', 'Theory', 25, 9, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1017, 'TYPEWRITING SKILLS STAGE 1 (30WPM)', 'SST04201', 'Practical', 'Typing Room', 28, 9, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-27 13:41:42', 6, 1),
(1018, 'HATI MKATO NADHARIA', 'SST05101', 'Theory', 'Theory', 24, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-03 09:53:23', 6, 1),
(1019, 'SHORT HAND THEORY', 'SST05102', 'Practical', 'Typing Room', 28, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-28 00:24:34', 6, 1),
(1020, 'TYPING STAGE II (40 WPM)', 'SST05103', 'Practical', 'Typing Room', 25, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-27 13:43:34', 6, 1),
(1021, 'IT AND WORD PROCESING APPLICATIONS PRACTICES', 'SST05104', 'Theory', 'Theory', 54, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1022, 'SECRETARIAL DUTIES AND PROFESSIONAL ETIQUETTES', 'SST05105', 'Theory', 'Theory', 34, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1023, 'COMMUNICATIONS AND INTERPERSONAL SKILLS', 'SST05106', 'Theory', 'Theory', 19, 9, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1024, 'HATIMKATO KASI MANENO 80 MKD', 'SST05201', 'Theory', 'Theory', 14, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-28 00:27:42', 6, 1),
(1025, 'SHORTHAND 80 WPM', 'SST05202', 'Practical', 'Typing Room', 25, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-28 00:25:04', 6, 1),
(1026, 'TYPING STAGE III 50 WPM', 'SST05203', 'Practical', 'Typing Room', 34, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-27 13:44:42', 6, 1),
(1027, 'MEETING PROCEDURES AND PRACTICE', 'SST05204', 'Theory', 'Theory', 34, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1028, 'COMPUTER SPREAD SHEET AND PRESENTATION APPLICATIONS', 'SST05205', 'Practical', 'Computer', 37, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-27 13:51:37', 3, 1),
(1029, 'SMALL BUSINESS DEVELOPMENT SKILLS', 'SST05206', 'Theory', 'Theory', 36, 9, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1030, 'SHORTHAND 100 WPM', 'SST06101', 'Theory', 'Theory', 28, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-06 01:08:31', 6, 1),
(1031, 'HATIMKATO 100 MKD', 'SST06102', 'Theory', 'Theory', 14, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-28 00:26:59', 6, 1),
(1032, 'DATABASE COMPUTER APPLICATIONS', 'SST06103', 'Practical', 'Computer', 54, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-28 21:25:00', 3, 1),
(1033, 'PRINCIPLES OF RECORDS AND INFORMATION MANAGEMENT', 'SST06104', 'Theory', 'Theory', 19, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1034, 'PRINCIPLES AND PRACTICE OF MANAGEMENT', 'SST06105', 'Theory', 'Theory', 34, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1035, 'RESEARCH METHODOLOGY', 'SST06106', 'Theory', 'Theory', 32, 9, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1036, 'DESKTOP PUBLISHING COMPUTER APPLICATIONS', 'SST06201', 'Practical', 'Computer', 37, 9, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1037, 'HUMAN RESOURCE MANAGEMENT PRINCIPLES', 'SST06202', 'Theory', 'Theory', 13, 9, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1038, 'PRINCIPLES OF PUBLIC RELATIONS AND PROTOCOL', 'SST06203', 'Theory', 'Theory', 105, 9, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 12:05:35', 3, 1),
(1039, 'DEVELOPMENT STUDIES AND GOOD GOVERNANCE', 'SST06204', 'Theory', 'Theory', 8, 9, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1040, 'BOOKKEEPING AND ACCOUNTING PRINCIPLES', 'SST06205', 'Theory', 'Theory', 36, 9, 'NTA-6', NULL, 3, 'PRINCIPLES OF BOOK KEEPING', '2026-04-25 08:07:37', '2026-05-04 20:20:53', 3, 1),
(1041, 'PROJECT RESEARCH PAPER', 'SST06206', 'Theory', 'Theory', 32, 9, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-05-03 22:04:32', 3, 1),
(1042, 'FUNDAMENTALS OF RURAL DEVELOPMENT PLANNING', 'DPB04101', 'Theory', 'Theory', 8, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1043, 'BASIC COMMUNICATION SKILLS', 'DPB04102', 'Theory', 'Theory', 23, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1044, 'FUNDAMNTALS OF DEVELOPMENT PLANNING ', 'DPB04103', 'Theory', 'Theory', 8, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1045, 'CROSS CUTTING ISSUES', 'DPB04104', 'Theory', 'Theory', 23, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1046, 'BASIC MATHEMATICS', 'DPB04105', 'Theory', 'Theory', 37, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-03 22:15:43', 3, 1),
(1047, 'BASIC COMPUTER APPLICATION', 'DPB04106', 'Practical', 'Computer', 72, 13, 'NTA-4', NULL, 4, NULL, '2026-04-25 08:07:37', '2026-05-04 11:22:44', 3, 1),
(1048, 'FUNDAMENTAL OF URBAN DEVELOPMENT PLANNING', 'DPB04201', 'Theory', 'Theory', 70, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-28 11:57:09', 3, 1),
(1049, 'BASICS OF ENTREPRENEURSHIP AND BUSINESS MANAGEMENT', 'DPB04202', 'Theory', 'Theory', 16, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1050, 'BASIC OF PROJECT PLANNING AND DEVELOPMENT', 'DPB04203', 'Theory', 'Theory', 61, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:37', '2026-04-25 08:07:37', 3, 1),
(1051, 'ELEMENTS OF SOCIOLOGY', 'DPB04204', 'Theory', 'Theory', 4, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1052, 'PRINCIPLES OF ECONOMICS DEVELOPMENT', 'DPB04205', 'Theory', 'Theory', 55, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-05-06 00:35:33', 3, 1),
(1053, 'BASIC OF ENVIRONMENTAL MANAGEMENT AND PLANNING', 'DPB04206', 'Theory', 'Theory', 22, 13, 'NTA-4', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1054, 'COMMUNICATIONS SKILLS', 'DPT05101', 'Theory', 'Theory', 53, 13, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1055, 'RURAL DEVELOPMENT PLANNING', 'DPT05102', 'Theory', 'Theory', 8, 13, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1056, 'BASIC MATHEMATICS AND STATISTICS', 'DPT05103', 'Theory', 'Theory', 122, 13, 'NTA-5', NULL, 4, 'BUSINESS MATHEMATICS AND STATISTICS3', '2026-04-25 08:07:38', '2026-05-25 01:17:24', 3, 1),
(1057, 'DEVELOPMENT ECONOMICS', 'DPT05104', 'Theory', 'Theory', 55, 13, 'NTA-5', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1058, 'INFORMATION AND COMMUNICATIONS TECHNOLOGY', 'DPT05105', 'Theory', 'Theory', 72, 13, 'NTA-5', NULL, 4, 'INFORMATION AND COMMUNICATION TECHNOLOGY1', '2026-04-25 08:07:38', '2026-05-04 11:24:09', 3, 1),
(1059, 'PUBLIC FINANCE', 'DPT05106', 'Theory', 'Theory', 55, 13, 'NTA-5', NULL, 4, 'PUBLIC FINANCE', '2026-04-25 08:07:38', '2026-04-26 04:57:22', 3, 1),
(1060, 'PRINCIPLES OF COMMUNITY DEVELOPMENT', 'INCIPLESOFCOMMUNITYDEVELOPMENT', 'Theory', 'Theory', 8, 13, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1061, 'URBAN DEVELOPMENT PLANNING', 'BANDEVELOPMENTPLANNING', 'Theory', 'Theory', 16, 13, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1062, 'PROJECT PLANNING AND MANAGEMENT', 'OJECTPLANNINGANDMANAGEMENT', 'Theory', 'Theory', 119, 13, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-05-03 22:06:06', 3, 1),
(1063, 'DEVELOPMENT STUDIES', 'VELOPMENTSTUDIES', 'Theory', 'Theory', 21, 13, 'NTA-5', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1064, 'PLANNING MANAGEMENT', 'DPT06101', 'Theory', 'Theory', 4, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1065, 'E-GOVERNANCE MANAGEMENT PLANNING', 'DPT06102', 'Theory', 'Theory', 70, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-05-03 22:11:53', 3, 1),
(1066, 'BASIC ISSUES IN DEVELOPMENT PLANNING', 'DPT06103', 'Theory', 'Theory', 20, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1067, 'POPULATION AND DEVELOPMENT PLANNING', 'DPT06104', 'Theory', 'Theory', 4, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1068, 'PARTICIPATORY PLANNING FOR DEVELOPMENT    ', 'DPT06105', 'Theory', 'Theory', 16, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1069, 'INTRODUCTION TO BLUE ECONOMY', 'DPT06106', 'Theory', 'Theory', 17, 13, 'NTA-6', NULL, 4, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1070, 'ENVIRONMENT AND DEVELOPMENT', 'DPT06207', 'Theory', 'Theory', 22, 13, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-05-03 11:03:45', 3, 1),
(1071, 'INDUSTRIAL DEVELOPMENT PLANNING', 'DPT06208', 'Theory', 'Theory', 17, 13, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1072, 'ENTREPRENEURSHIP AND BUSINESS PLANNING', 'DPT06209', 'Theory', 'Theory', 16, 13, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1073, 'PROJECT MONITORING AND EVALUATION', 'DPT06210', 'Theory', 'Theory', 61, 13, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1074, 'LAND USE PLANNING AND MANAGEMENT', 'DPT06211', 'Theory', 'Theory', 20, 13, 'NTA-6', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1075, 'MULTILATERAL CONFERENCE DIPLOMACY', 'IRU07208', 'Theory', 'Theory', 26, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1076, 'APPROACHES TO INTERNATIONAL PEACE AND SECURITY', 'IRU07209', 'Theory', 'Theory', 27, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1077, 'MACRO-ECONOMICS', 'IRU07210', 'Theory', 'Theory', 38, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-28 21:34:00', 3, 1),
(1078, 'FOREIGN LANGUAGE INTERMEDIATE LEVEL', 'IRU07211', 'Theory', 'Theory', 103, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 11:43:52', 3, 1),
(1079, 'INTERNATIONAL ORGANIZATIONS', 'IRU07212', 'Theory', 'Theory', 59, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:19:00', 3, 1),
(1080, 'ENTREPRENEURSHIP', 'IRU07213', 'Theory', 'Theory', 9, 15, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:22:13', 3, 1),
(1081, 'AFRICAN INTERNATIONAL RELATIONS AND DIPLOMACY', 'IRU08204', 'Theory', 'Theory', 27, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1082, 'LEADERSHIP SKILLS AND MANAGEMENT OF INTERNATIONAL ORGANIZATIONS', 'IRU08205', 'Theory', 'Theory', 9, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1083, 'TRADE AND INVESTMENT RELATION', 'IRU08208', 'Theory', 'Theory', 111, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:27:00', 3, 1),
(1084, 'FOREIGN MISSION MANAGEMENT', 'IRU08209', 'Theory', 'Theory', 64, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:23:39', 3, 1),
(1085, 'FOREIGN POLICY', 'IRU08211', 'Theory', 'Theory', 59, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1086, 'INTERNATIONAL NEGOTIATION', 'IRU08212', 'Theory', 'Theory', 26, 15, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1087, 'EMPLOYEE STAFFING', 'HRU07207', 'Theory', 'Theory', 13, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1);
INSERT INTO `subjects` (`id`, `subjectName`, `subjectCode`, `subject_type`, `required_lab`, `teacher_id`, `course_id`, `nta_level`, `shared_group`, `semester_id`, `group_name`, `created_at`, `updated_at`, `credit_hour`, `branch_id`) VALUES
(1088, 'PRINCIPLES OF  ENTREPRENEURSHIP', 'HRU07209', 'Theory', 'Theory', 9, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1089, 'COMPENSATION AND PERFORMANCE MANAGEMENT', 'HRU07210', 'Theory', 'Theory', 15, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1090, 'ORGANIZATIONAL BEHAVIOUR', 'HRU07211', 'Theory', 'Theory', 5, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1091, 'PUBLIC RELATIONS AND CUSTOMER CARE', 'HRU07212', 'Theory', 'Theory', 44, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1092, 'LEADERSHIP SKILLS', 'HRU07313', 'Theory', 'Theory', 5, 8, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1093, 'MANAGING GENDER AND DIVERSITY IN EMPLOYMENT ISSUES', 'HRU07419', 'Theory', 'Theory', 22, 8, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1094, 'INTERNATIONAL BUSINESS MANAGEMENT', 'HRU07420', 'Theory', 'Theory', 36, 8, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1095, 'EMPLOYMENT LAW', 'HRU07421', 'Theory', 'Theory', 115, 8, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-27 09:33:04', 3, 1),
(1096, 'LABOUR  RELATIONS', 'HRU07422', 'Theory', 'Theory', 15, 8, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1097, 'STRATEGIC HUMAN RESOURCE MANAGEMENT', 'HRU08206', 'Theory', 'Theory', 15, 8, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1098, 'TRAINING AND DEVELOPMENT', 'HRU08202', 'Theory', 'Theory', 13, 8, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1099, 'STAFF RETENTION', 'HRU08203', 'Theory', 'Theory', 5, 8, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1100, 'CONFLICT MANAGEMENT IN ORGANIZATIONS', 'HRU08201', 'Theory', 'Theory', 11, 8, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1102, 'INFORMATION SOURCES AND SERVICES.', 'RMU07207', 'Theory', 'Theory', 6, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1103, 'CONSERVATION AND PRESERVATION OF RECORDS.', 'RMU07208', 'Theory', 'Theory', 31, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1104, 'PRINCIPLES OF  ENTREPRENEURSHIP.', 'GSU07209', 'Theory', 'Theory', 42, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 09:44:32', 3, 1),
(1105, 'RECORDS APPRAISAL SYSTEMS.', 'RMU07210', 'Theory', 'Theory', 11, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:31:43', 3, 1),
(1106, 'ARCHIVAL MANAGEMENT  PRINCIPLES AND TECHNIQUES.', 'RMU07211', 'Theory', 'Theory', 33, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-27 08:32:14', 3, 1),
(1107, 'CUSTOMER CARE AND BUSINESS ETHICS.', 'RMU07212', 'Theory', 'Theory', 53, 6, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1108, 'LEGAL RECORDS MANAGEMENT', 'RMU07419', 'Theory', 'Theory', 19, 6, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1109, 'HEALTH RECORDS MANAGEMENT', 'RMU07420', 'Theory', 'Theory', 19, 6, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1110, 'CARE AND COLLECTIONS IN MUSEUMS', 'RMU07421', 'Theory', 'Theory', 11, 6, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1111, 'LAND RECORDS MANAGEMENT', 'RMU07422', 'Theory', 'Theory', 117, 6, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-28 21:04:59', 3, 1),
(1112, 'PROJECT PLANNING AND MANAGEMENTs', 'RMU07423', 'Theory', 'Theory', 61, 6, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 11:19:07', 3, 1),
(1113, 'DIGITALIZATION OF RECORDS AND ARCHIVES MANAGEMENT SYSTEM.', 'RMU08207', 'Theory', 'Theory', 31, 6, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1114, 'FUNDAMENTALS OF LIBRARY RECORDS MANAGEMENT.', 'RMU08208', 'Theory', 'Theory', 6, 6, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1115, 'PROMOTING RECORDS AND ARCHIVES PROGRAMMES.', 'RMU08209', 'Theory', 'Theory', 11, 6, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1116, 'MANAGEMENT OF HEALTH ARCHIVES.', 'RMU08210', 'Theory', 'Theory', 19, 6, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1117, 'MANAGEMENT OF MUSEUM AND ARCHAEOLOGICAL RECORDS.', 'RMU08211', 'Theory', 'Theory', 31, 6, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1119, 'ECONOMICS', 'DPU07207', 'Theory', 'Theory', 50, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1120, 'HOUSE PLANNING', 'DPU07208', 'Theory', 'Theory', 17, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1121, 'POPULATION AND DEMOGRAPHY', 'DPU07209', 'Theory', 'Theory', 4, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1122, 'URBAN DEVELOPMENT PLANNING', 'DPU07210', 'Theory', 'Theory', 20, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1123, 'TRANSPORTATION PLANNING', 'DPU07211', 'Theory', 'Theory', 119, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-05-03 22:13:05', 3, 1),
(1124, 'UTILITIES SERVICES PLANNING', 'DPU07212', 'Theory', 'Theory', 20, 14, 'NTA-7', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1125, 'GEOGRAPHICAL INFORMATION SYSTEMS (GIS)', 'DPU07419', 'Theory', 'Theory', 123, 14, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-05-16 02:43:59', 3, 1),
(1126, 'COMPUTER AIDED DESIGN (CAD)', 'DPU07420', 'Theory', 'Theory', 54, 14, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1127, 'FINANCIAL ACCOUNTING', 'DPU07421', 'Theory', 'Theory', 52, 14, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1128, 'APPLIED MATHEMATICS AND STATISTICS', 'DPU07422', 'Theory', 'Theory', 122, 14, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-05-16 02:37:59', 3, 1),
(1129, 'PRINCIPLES OF ENTREPRENEURSHIP', 'DPU07423', 'Theory', 'Theory', 11, 14, 'NTA-7', NULL, 5, NULL, '2026-04-25 08:07:38', '2026-04-27 08:57:11', 3, 1),
(1131, 'SOCIAL IMPACT ASSESSMENT', 'DPU0829', 'Theory', 'Theory', 20, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1132, 'ENVIRONMENTAL IMPACT ASSESSMENT', 'DPU08210', 'Theory', 'Theory', 20, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1133, 'STRATEGIC PLANNING SKILLS ', 'DPU08104', 'Theory', 'Theory', 11, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1134, 'DEVELOPMENT POVERTY ANALYSIS', 'DPU08211', 'Theory', 'Theory', 17, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1135, 'FOOD SECURITY AND NUTRITION', 'DPU08212', 'Theory', 'Theory', 17, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1136, 'BUDGET PLANNING AND MANAGEMENT', 'DPU08213', 'Theory', 'Theory', 52, 14, 'NTA-8', NULL, 3, NULL, '2026-04-25 08:07:38', '2026-04-25 08:07:38', 3, 1),
(1137, 'FUNDAMENTAL OF RECORDS MANEGEMENT', 'RMGT O4101', 'Theory', 'Theory', 93, 5, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1138, 'OFFICE  ORGANIZATIONS', 'RMGT041012', 'Theory', 'Theory', 84, 5, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1139, 'BASIC MATHEMATICS AND STATISTIC', 'RMGT O04103', 'Theory', 'Theory', 94, 5, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-28 12:22:17', 3, 2),
(1140, 'COMMUNICATION SKILLS', 'RMGT 04104', 'Theory', 'Theory', 85, 5, 'NTA-4', NULL, 4, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1141, 'BASIC LEGAL RECORDS MANAGEMAENT', 'RMGT 04105', 'Theory', 'Theory', 93, 5, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1142, 'FUNDAMENTAL OF ELECTRONIC RECORDS MANAGEMENT', 'RMGT 04106', 'Theory', 'Theory', 86, 5, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1143, 'RECORDS OFFICE PROCEDURES AND PRACTICE', 'RMGT 04201', 'Theory', 'Theory', 93, 5, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1144, ' FUNDAMENTAL OF PERSONAL AND INTERPERSONALSKILLS', 'RMGT 04202', 'Theory', 'Theory', 91, 5, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1145, 'BASIC COMPUTER APPLICATION', 'RMGT 04203', 'Practical', 'Computer', 82, 5, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1146, 'FUNDAMENTAL OF ARCHIVIES MANAGEMENT', 'RMGT 04204', 'Theory', 'Theory', 86, 5, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1147, 'FUNDAMENTAL OF CONVERSATION OF RECORD ARCHIVAL  MATERIALS', 'RMGT 04205', 'Theory', 'Theory', 93, 5, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1148, 'PRINCIPLE AND FUNCTIONS OF MANAGEMENT CLASSIFICATION CREDIT', 'RMT 05101', 'Theory', 'Theory', 88, 5, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1149, 'COMMUNICATION SKILLS', 'RMT 05102', 'Theory', 'Theory', 79, 5, 'NTA-5', NULL, 4, 'COMMUNICATION SKILLS', '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1150, 'BASIC COMPUTER APPLICATION', 'RMT 05 103', 'Practical', 'Computer', 82, 5, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1151, 'ENTEREPRENEURSHIP IN RECORDS AND INFORMATION  WORK', 'RMT 05104', 'Theory', 'Theory', 80, 5, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-28 12:12:47', 3, 2),
(1152, 'DEVELOPMENT STUDIES', 'RMT 05105', 'Theory', 'Theory', 78, 5, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-28 12:20:58', 3, 2),
(1153, 'FUNDAMENTAL OF RECORDS AND ARCHIVES  MANAGEMENT', 'RMT 05106', 'Theory', 'Theory', 86, 5, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1154, 'RECORDS OFFICE PROCEDURES AND PRACTICE', 'RMT 05201', 'Theory', 'Theory', 86, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-28 12:00:54', 3, 2),
(1155, 'ARCHIVES MANAGEMENT PRINCIPLE AND PRACTICES', 'RMT 05202', 'Theory', 'Theory', 86, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1156, 'CONSERVATION OF RECORDS AND ARCHIVAL MATERIALS', 'RMT 05203', 'Theory', 'Theory', 93, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1157, 'COMPUTER APPLICATION', 'RMT 05204', 'Practical', 'Computer', 82, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1158, 'FUNDAMENTAL MATHEMATICS AND STATISTIC', 'RMT 05205', 'Theory', 'Theory', 94, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1159, ' PRNCIPLE AND PROCEDURE  OF ACCESS TO RECORDS AND ARCHIVIES', 'RMT 05206', 'Theory', 'Theory', 93, 5, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1160, 'LAND RECORDS', 'RMGT 06201', 'Theory', 'Theory', 83, 5, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1161, 'LEGAL RECORDS', 'RMGT 06202', 'Theory', 'Theory', 84, 5, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1162, 'MEDICAL RECORDS', 'RMGT 06203', 'Theory', 'Theory', 86, 5, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1163, 'AUTOMATION OF RECORDS AND ARCHIVAL MATERIAL', 'RMGT 06204', 'Theory', 'Theory', 84, 5, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1164, 'OFFICE MANAGEMENT', 'RMGT 06205', 'Theory', 'Theory', 85, 5, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1165, 'BASIC COMMUNIVATION SKILLS', 'GST04103', 'Theory', 'Theory', 85, 4, 'NTA-4', NULL, 4, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1166, 'BASIC STORE ADMIMISTRATION', 'PST04101', 'Theory', 'Theory', 87, 4, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1167, 'BASIC PRECUREMENT PRINCIPLE', 'PST04106', 'Theory', 'Theory', 87, 4, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1168, 'BASIC BUSINESS MATH AND STATISTICS', 'GST04102', 'Theory', 'Theory', 97, 4, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1169, 'ELEMENT OF ENTERPRENEURHSIP', 'GST0414', 'Theory', 'Theory', 97, 4, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1170, 'BASIC COMPUTER APPLICATION', 'GST04105', 'Practical', 'Computer', 82, 4, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1171, 'BASIC COMMUNIVATION SKILLS', 'GST04101', 'Theory', 'Theory', 85, 7, 'NTA-4', NULL, 4, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1172, 'FUNDAMENTAL OF OFFICE PRACTISE AND RECORD MANAGEMENT', 'HRT04106', 'Theory', 'Theory', 93, 7, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1173, 'BASIC PUBLIC ADMINISTRATION', 'GST04103', 'Theory', 'Theory', 91, 7, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1174, 'BASIC COMPUTER APPLICATION', 'GST04105', 'Practical', 'Computer', 82, 7, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1175, 'BASIC PRINCIPLE OF EMPLOYEE RELATION', 'HRT04102', 'Theory', 'Theory', 90, 7, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1176, 'BASIC OF ENTERPRENEOURSHIP', 'GST04104', 'Theory', 'Theory', 78, 7, 'NTA-4', NULL, 4, NULL, '2026-04-27 00:19:53', '2026-04-27 00:19:53', 3, 2),
(1177, 'BASIC OF CLEARING AND FORWARDING', 'PST04209', 'Theory', 'Theory', 87, 4, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1178, 'BASIC STOCK CONTROL', 'PST04211', 'Theory', 'Theory', 87, 4, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1179, 'ELEMENTS OF PUBLIC PRECUREMENTS', 'PST04207', 'Theory', 'Theory', 98, 4, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1180, 'BASIC BOOKKIPING', 'GST04208', 'Theory', 'Theory', 97, 4, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1181, 'ELEMET OF MARKETING', 'GST04210', 'Theory', 'Theory', 89, 4, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1182, 'ELEMENT OF GOOD GOVERNANCE', 'GST04201', 'Theory', 'Theory', 78, 7, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1183, 'BASIC PERFORMANCE MANAGEMENT', 'HRT04205', 'Theory', 'Theory', 79, 7, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1184, 'BASIC HUMAN RESURCE MANAGEMENT', 'HRT04204', 'Theory', 'Theory', 91, 7, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1185, 'BASIC PRINCIPLE OF MANAGEMENT', 'GST04203', 'Theory', 'Theory', 92, 7, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1186, 'FUNDAMENTAL OF LABOURLAW', 'HRT04202', 'Theory', 'Theory', 81, 7, 'NTA-4', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1187, 'COMMUNICATION SKILLS', 'PST05101', 'Theory', 'Theory', 79, 4, 'NTA-5', NULL, 4, 'COMMUNICATION SKILLS', '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1188, 'PRINCIPLE OF ECONOMICS', 'PST05102', 'Theory', 'Theory', 97, 4, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1189, 'INFORMATION AND COMMMUNICATION TECHNOOGY', 'PST05103', 'Theory', 'Theory', 82, 4, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1190, 'BASIC BUSINESS MATH AND STATISTICS', 'PST05104', 'Theory', 'Theory', 97, 4, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1191, 'ENTERPRENEOURSHIP', 'PST05105', 'Theory', 'Theory', 80, 4, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-05-08 05:09:57', 3, 2),
(1192, 'STORE AND ADMINISTRATION', 'PST05106', 'Theory', 'Theory', 95, 4, 'NTA-5', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1193, 'PRINCIPLE OF PRECUREMENTS', 'PST05201', 'Theory', 'Theory', 95, 4, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1194, 'FREIGHT CLEARING AND FORWARDING', 'PST05202', 'Theory', 'Theory', 96, 4, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1195, 'FUNDAMENTAL OF COST ACCOUNTING', 'PST05203', 'Theory', 'Theory', 96, 4, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1196, 'FINANCIAL ACCOUNTING', 'PST05204', 'Theory', 'Theory', 97, 4, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1197, 'PRINCIPLES OF MARKETING AND CUSTOMER CARE', 'PST05205', 'Theory', 'Theory', 98, 4, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1198, 'PERFORMANCE MANAGEMENT', 'HRT05204', 'Theory', 'Theory', 79, 7, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1199, 'HUMAN RESOURCE INFORMATION', 'HRT05203', 'Theory', 'Theory', 91, 7, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-05-08 05:10:38', 3, 2),
(1200, 'STRATEGIC HUMAN RESOURCE MANAGEMENT', 'HRT05202', 'Theory', 'Theory', 91, 7, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1201, 'LEADERSHIP AND SUPERVISORY SKILLS', 'HRT05201', 'Theory', 'Theory', 92, 7, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1202, 'OFFICE ORGANIZATION AND RECORD MANAGEMENT', 'GST05205', 'Theory', 'Theory', 83, 7, 'NTA-5', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1203, 'HUMAN RESOURCE PLANNING', 'HRT06102', 'Theory', 'Theory', 79, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1204, 'MANAGING ORGANIZATION', 'GST06104', 'Theory', 'Theory', 79, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1205, 'PERSONAL RECORD MANAGEMENT', 'HRT06106', 'Theory', 'Theory', 90, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1206, 'EMPLOYEE STAFFING', 'HRT06105', 'Theory', 'Theory', 78, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1207, 'HR APRAISALENTERPRENEOURSHIP MANAGEMENT', 'HRT06101', 'Theory', 'Theory', 91, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1208, 'ENTERPRENOURSHIP MANAGEMENT', 'GST06103', 'Theory', 'Theory', 78, 7, 'NTA-6', NULL, 4, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1209, 'RESEARCH METHODOLOGY', 'PST06201', 'Theory', 'Theory', 85, 4, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1210, 'PRINCIPLE OF INVENTORY MANAGEMENT', 'PST06202', 'Theory', 'Theory', 87, 4, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1211, 'PRINCIPLE OF LOGISTIC MANAGEMENT', 'PST06203', 'Theory', 'Theory', 87, 4, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1212, 'BUSINESS LAW', 'PST06204', 'Theory', 'Theory', 88, 4, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1213, 'PRINCIPLE OF PUBLIC PRECUREMENTS', 'PST06205', 'Theory', 'Theory', 89, 4, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1214, 'EMPLOYEE MOTIVATION', 'HRT06202', 'Theory', 'Theory', 78, 7, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1215, 'MANAGING ORGANIZATION CULTURE', 'GST06204', 'Theory', 'Theory', 79, 7, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1216, 'PERFORMANCE MANAGEMENT', 'HRT06203', 'Theory', 'Theory', 91, 7, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-28 12:15:02', 3, 2),
(1217, 'INDUSTRIAL RELATION AND LABOUR LAW', 'HRT06201', 'Theory', 'Theory', 81, 7, 'NTA-6', NULL, 3, NULL, '2026-04-27 00:19:54', '2026-04-27 00:19:54', 3, 2),
(1218, 'SYSTEM ANALYSIS AND DESIGN', 'GST06104', 'Theory', 'Theory', 41, 1, 'NTA-6', NULL, 4, NULL, '2026-05-04 20:11:47', '2026-05-04 20:11:47', 3, 1),
(1219, 'FUNDAMENTAL OF SOCIAL PHYSICOLOGY', 'PAT 04106', 'Theory', 'Theory', 16, 11, 'NTA-4', NULL, 4, NULL, '2026-05-18 03:22:24', '2026-05-18 03:23:59', 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `system_timetables`
--

CREATE TABLE `system_timetables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'not_created',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `system_timetables`
--

INSERT INTO `system_timetables` (`id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'maintenance', '2026-04-24 06:47:49', '2026-04-28 14:22:14');

-- --------------------------------------------------------

--
-- Table structure for table `teachers`
--

CREATE TABLE `teachers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `middlename` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `gender` varchar(255) NOT NULL,
  `mobile` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `user_level` varchar(255) NOT NULL DEFAULT 'teacher',
  `teacher_code` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'Active',
  `role` varchar(255) DEFAULT 'teacher',
  `branch_id` bigint(20) UNSIGNED DEFAULT NULL,
  `deptId` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teachers`
--

INSERT INTO `teachers` (`id`, `firstname`, `middlename`, `lastname`, `gender`, `mobile`, `email`, `password`, `created_at`, `updated_at`, `user_level`, `teacher_code`, `status`, `role`, `branch_id`, `deptId`) VALUES
(1, 'SHAMIS', 'NASSOR', 'ALI', 'Male', '0795371212', 'shamsay70@gmail.com', '$2y$12$0eWL4eSb4DNsWOFWqBriM.Gxn9ZEJpJlCcYKthOiTaLJeararXTBi', '2026-04-24 06:47:48', '2026-04-24 06:47:48', 'admin', 'T00001', 'Active', 'teacher', 1, 1),
(2, 'AHMED', 'MOHD', 'JUMA', 'Male', '0712345678', 'pemba@gmail.com', '$2y$12$ikJ72QATOStdqSJ3jFB8x./uXcrSOE.EWZ7HgR6grLZx9J12tytkW', '2026-04-24 06:47:48', '2026-04-24 06:47:48', 'admin', 'T00002', 'Active', 'teacher', 2, 3),
(3, 'KAUYE', 'ALI', 'SENDARO', 'Female', '0774703989', 'kauye.sendaro@ipa.ac.tz', '$2y$12$9Zy6onU4Yu6D2y96dMym..xcyQCBpFU.baT8ZD1PT6QWGLW1GQfXu', '2026-04-24 06:48:50', '2026-04-24 06:48:50', 'teacher', 'T002', 'Active', 'teacher', 1, 1),
(4, 'HINDI', 'KASSIM', 'KHAMIS', 'Female', '0777845944', 'bintykassim@gmail.com', '$2y$12$96R99pP.HOxZ9j9FjdydHO6/GN57cngGq6D47xTFZODMYyhfasb2G', '2026-04-24 06:48:51', '2026-04-24 06:48:51', 'teacher', 'T003', 'Active', 'teacher', 1, 1),
(5, 'SAID', 'HAMAD', 'SHEHE', 'Male', '0777239070', 'jsaidbakari@gmail.com', '$2y$12$yNPcFG1lxBtfYgLjIrG6Cu1B4fe70jZRQ8fgFJ4oHZ9qado9qIDCm', '2026-04-24 06:48:51', '2026-04-26 14:06:12', 'teacher', 'T004', 'Active', 'teacher', 1, 1),
(6, 'ABTWAHIYU', 'SULEIMAN', 'JAHA', 'Male', '0776034916', 'absuleypj@gmail.com', '$2y$12$QnqTcsQnIAutQ3fvpCmGP.xmEaQG9fXKUqQE7z9yO5rwV2a.oUwXS', '2026-04-24 06:48:52', '2026-04-24 06:48:52', 'teacher', 'T005', 'Active', 'teacher', 1, 1),
(7, 'ABDULLA', 'MOHAMED', 'ABDULLA', 'Male', '0777480751', 'mahadutu@gmail.com', '$2y$12$kntgPIMRZEgGNdLOz4TJ0uwVNBMqpFG3QtS98OHdm5CI.om0ASoF6', '2026-04-24 06:48:52', '2026-04-24 06:48:52', 'teacher', 'T006', 'Active', 'teacher', 1, 1),
(8, 'ABDALLA', 'MAULID', 'MAKAME', 'Male', '0777857962', 'abdullamakame7@gmail.com', '$2y$12$W0MfbQOYg0PqStnBVo0ekOK1iJuN/Fd9R5ohCozRD2hDsj.7f/KJm', '2026-04-24 06:48:52', '2026-04-24 06:48:52', 'teacher', 'T007', 'Active', 'teacher', 1, 1),
(9, 'ABDALLA', 'JUMA', 'RAMADHAN', 'Male', '0777410529', 'abdallah.ramadhan@ipa.ac.tz', '$2y$12$G4ULT0VXCebOUA4EyzgflOmGfLG8aIwOGDV5/beoYPeImnKbODBHW', '2026-04-24 06:48:53', '2026-04-24 06:48:53', 'teacher', 'T008', 'Active', 'teacher', 1, 1),
(10, 'AISHA', 'MOHAMED', 'ADAM', 'Female', '0652467635', 'ishaadam093@gmail.com', '$2y$12$IbS97QQMJVlzSt0VqxNYFuVF7HVys0XCDL.fosk4mAq2No/T9ERYm', '2026-04-24 06:48:53', '2026-04-24 06:48:53', 'teacher', 'T009', 'Active', 'teacher', 1, 1),
(11, 'HAJI', 'MDUNGI', 'HAJI', 'Male', '0673554222', 'hajimdungi524@gmail.com', '$2y$12$aW9CbVcY0BjAcGyRHI.JJePxzCBskgQRYK2.1x1gl5svAQ4xwh0oO', '2026-04-24 06:48:54', '2026-04-24 06:48:54', 'teacher', 'T010', 'Active', 'teacher', 1, 1),
(12, 'HASSAN', 'JUMA', 'ISSA', 'Male', '0777150250', 'hassjuma2018@gmail.com', '$2y$12$xkJz.HxsED3gYTmL7fOFveM.rSUDSvAPggABCkih9jLILfJr4tCUe', '2026-04-24 06:48:54', '2026-04-24 06:48:54', 'teacher', 'T011', 'Active', 'teacher', 1, 1),
(13, 'LATIFA ', 'ABEID', 'KHAMIS', 'Female', '0773932724', 'bintabeid83@gmail.com', '$2y$12$aIQ9CaXqqqA.tJ4OFFX.xuBlzseWPR1LUyv.MIknuMFkMfhO8WJO.', '2026-04-24 06:48:54', '2026-04-24 06:48:54', 'teacher', 'T012', 'Active', 'teacher', 1, 1),
(14, 'MARYAM', 'KHAMIS', 'HAMAD', 'Female', '0777427363', 'hamadmaryam1969@gmail.com', '$2y$12$mzevrzZNvguECx6HEg1BouPsaIvAWJyv5p5CfPocYRJT.35miuVHK', '2026-04-24 06:48:55', '2026-04-24 06:48:55', 'teacher', 'T013', 'Active', 'teacher', 1, 1),
(15, 'MATTAR', 'BAKAR', 'HAJI', 'Male', '0777879018', 'mattaralhaj@gmail.com', '$2y$12$RHI0W3OtOBBmMwNKlM6bZeg0tMaJm5qyMEJ9hObdm1mGJlqAclIKK', '2026-04-24 06:48:55', '2026-04-24 06:48:55', 'teacher', 'T014', 'Active', 'teacher', 1, 1),
(16, 'MOHAMED', 'ABDALLA', 'ALI', 'Male', '0777968657', 'mohdcdo@yahoo.com', '$2y$12$RM2XOl3HM0gnI2ZtBS4uB.PyoWjrTvWZgLuitkC8SqnKYkeuphiWa', '2026-04-24 06:48:56', '2026-04-24 06:48:56', 'teacher', 'T015', 'Active', 'teacher', 1, 1),
(17, 'MOHD', 'NASSOR', 'ALI', 'Male', '0729227521', 'mohdnassorali@gmail.com', '$2y$12$RqOe4BtkL7N2uS.SEVGIDOe7r4UddubNL5FNocHzKHuM0OdvmNt3e', '2026-04-24 06:48:56', '2026-04-24 06:48:56', 'teacher', 'T016', 'Active', 'teacher', 1, 1),
(18, 'MWANAASHA', 'MWADINI', 'PANDU', 'Female', '0778845175', 'mwana1asha@gmail.com', '$2y$12$8PoYJfeugruVXpCgi7CAKOD81c7gq7wLzBlDglzXlKDfNZ4ah9T2K', '2026-04-24 06:48:56', '2026-04-24 06:48:56', 'teacher', 'T017', 'Active', 'teacher', 1, 1),
(19, 'MZEE', 'JUMA', 'HAJI', 'Male', '0777484896', 'mzeemuislaam@gmail.com', '$2y$12$d43QP6fN4lBL64Z62PXhnu9QEFbSVjx7hAPjG5OKWJLhX605mPOuO', '2026-04-24 06:48:57', '2026-04-24 06:48:57', 'teacher', 'T018', 'Active', 'teacher', 1, 1),
(20, 'NASSIR', 'MASOUD', 'NASSOR', 'Male', '0777601501', 'nassreema@gmail.com', '$2y$12$mN3Bw9v/CgXrED4qFeJ7xeYPKKjpYYmBIWtxlX5eSuju1chudxnv.', '2026-04-24 06:48:57', '2026-04-24 06:48:57', 'teacher', 'T019', 'Active', 'teacher', 1, 1),
(21, 'NYEZUMA', 'HASSAN', 'JUMA', 'Female', '0773066606', 'neyhassan2000@gmail.com', '$2y$12$6fd/2d87PY8bbBHEfQAalupZGQECXEthuKltdcyw8glHurj/4XNgW', '2026-04-24 06:48:58', '2026-04-24 06:48:58', 'teacher', 'T020', 'Active', 'teacher', 1, 1),
(22, 'ZAKIA', 'DAUD', 'KHAMIS', 'Female', '0715423628', 'zakiakhamis67@gmail.com', '$2y$12$3kR2xl3xULXOPajX97MBVuuG9DHjTndIG/KNOGuI4qYUHpxMJTevC', '2026-04-24 06:48:58', '2026-04-24 06:48:58', 'teacher', 'T021', 'Active', 'teacher', 1, 1),
(23, 'ZUWEINA', 'HASSAN', 'SULEIMAN', 'Female', '0773202281', 'queenzu83@gmail.com', '$2y$12$kzSx9GI8Rbn3WH9MzhdwcueKHw./r2CEvCQPHUn0AFepeQLwQfpD.', '2026-04-24 06:48:58', '2026-04-24 06:48:58', 'teacher', 'T022', 'Active', 'teacher', 1, 1),
(24, 'AHMADA', 'ALI', 'AHMADA', 'Male', '0123456789', 'ahmada1.ahmada@ipa.ac.tz', '$2y$12$uGQvxJoMHFOl/5LZCfrAv.xDGa69aevf3EH3B8jMMOHH6FkhnefRy', '2026-04-24 06:48:59', '2026-04-24 06:48:59', 'teacher', 'T024', 'Active', 'teacher', 1, 1),
(25, 'GHANIYA', 'JAFFAR', 'SUWEID', 'Female', '0777472549', 'ghajafaar@gmail.com', '$2y$12$.zmcUBi0EnVrZygwYnFGZePFqGzvCVjco53yoH8L.YHv3WeYDKiIi', '2026-04-24 06:48:59', '2026-04-24 06:48:59', 'teacher', 'T025', 'Active', 'teacher', 1, 1),
(26, 'MUHSIN', 'JUMA', 'ALI', 'Male', '0773319161', 'alhajjmoyo11@gmail.com', '$2y$12$YpBwVK9GralO03a794baBOsLYdSoKWAUWcr97YK7jiGmVkRyy/6qS', '2026-04-24 06:48:59', '2026-04-24 06:48:59', 'teacher', 'T026', 'Active', 'teacher', 1, 1),
(27, 'MUSTAFA', 'MOHD', 'TWALIB', 'Male', '0776456911', 'mustaphatwalib26@gmail.com', '$2y$12$MsgAgF/M4gpZ381cQ0Qy5OCs2sqFnP5bgpOgMjize3Xlln0fyTWvW', '2026-04-24 06:49:00', '2026-04-24 06:49:00', 'teacher', 'T027', 'Active', 'teacher', 1, 1),
(28, 'BAHATI', 'JULIUS', 'KALILI', 'Male', '0776456911', 'bahatikalili@gmail.com', '$2y$12$AhehnU5M.XY8bor3p4sc8u//80moFZbWMILn0hmREe2Ltkp2jyXDu', '2026-04-24 06:49:00', '2026-04-24 06:49:00', 'teacher', 'T028', 'Active', 'teacher', 1, 1),
(29, 'DK HAJI', 'SALUM', 'KHAMIS', 'Male', '0777742745', 'hajisalim2015@yahoo.com', '$2y$12$UA3BPv8hwMYro5ta/4L7vuhavMJLcGzxrJp2VC.tN9apSPfHXuXES', '2026-04-24 06:49:01', '2026-04-30 22:00:24', 'teacher', 'T029', 'Active', 'teacher', 1, 1),
(30, 'MWATIMA', 'JUMA', 'KHAMIS', 'Female', '0777030613', 'mwatima.khamis@ipa.ac.tz', '$2y$12$LxVaFT9zIGfjoXOnkTTSnehL7OUU4Jg/iARsEimFSh4fBSDrKhrrq', '2026-04-24 06:49:01', '2026-04-24 06:49:01', 'teacher', 'T030', 'Active', 'teacher', 1, 1),
(31, 'FATMA', 'ABDALLA', 'KHAMIS', 'Female', '0773088578', 'fatma.khamis@ipa.ac.tz', '$2y$12$hZFACUb1AqmQRVyfkh4E.easxK0I8BI2lUO8BnhnVbLlrCbUZKIUK', '2026-04-24 06:49:01', '2026-04-24 06:49:01', 'teacher', 'T031', 'Active', 'teacher', 1, 1),
(32, 'DK KHADIJA', 'SAID', 'KASSIM', 'Female', '0773204585', 'didachidi84@gmail.com', '$2y$12$DYNIRT55vvoONachmtWpW.g1R/ih2FLuyswC4HEAVeNPvVmvkfKRC', '2026-04-24 06:49:02', '2026-04-30 21:59:18', 'teacher', 'T032', 'Active', 'teacher', 1, 1),
(33, 'KHADIJA', 'JUMA', 'KHAMIS', 'Female', '0718922226', 'khadijajumakh100@gmail.com', '$2y$12$99kpGk92JihvE3QvyDGRiupogD0KF6cp29u6lPiIHquqZbNC16mqC', '2026-04-24 06:49:02', '2026-04-24 06:49:02', 'teacher', 'T033', 'Active', 'teacher', 1, 1),
(34, 'MWANAKOMBO', 'MRISHO', 'HAJI', 'Female', '0777042837', 'tmwana94@gmail.com', '$2y$12$UpSo6glbKqu58V02fDXgLuMRMyDt24vv6m5m/NrJ/usYfJacVSsqK', '2026-04-24 06:49:03', '2026-04-24 06:49:03', 'teacher', 'T034', 'Active', 'teacher', 1, 1),
(35, 'MAHMOUD', 'MAKAME', 'ALI', 'Male', '0777863067', 'teachermudy@gmail.com', '$2y$12$eQ/O.WZarOtwI1aPV4Qwc.NzbSZ3zBW0vcScQnoLKLd4Wfw.DA4wa', '2026-04-24 06:49:03', '2026-04-26 07:30:01', 'teacher', 'T035', 'Active', 'Supervisor', 1, 2),
(36, 'NURU', 'ABBAS', 'MAKAME', 'Female', '0779861027', 'sweetzaki@gmail.com', '$2y$12$oC8dtQCh/ap7aqXoPcjTHODuiMdX1pfRnHcx5NPepCW/Pby5NGQIi', '2026-04-24 06:49:03', '2026-04-24 06:49:03', 'teacher', 'T036', 'Active', 'teacher', 1, 2),
(37, 'SULEIMAN', 'JUMA', 'SULEIMAN', 'Male', '0774444426', 'sumilo2020@gmail.com', '$2y$12$K.uHPGqU7v4bEwGhXe3M4ewZ7IA/pXbyXPLxuPPQKQd2VTFvYwU4y', '2026-04-24 06:49:04', '2026-04-24 06:49:04', 'teacher', 'T037', 'Active', 'teacher', 1, 2),
(38, 'DK OMAR', 'SALIM', 'ALI', 'Male', '0776646393', 'salimzanzibar003@gmail.com', '$2y$12$fkCDSRpnacvyNbC0diGkse5xRXzOn9.BV5PZWNbjShTwiQR9eWpYa', '2026-04-24 06:49:04', '2026-04-30 21:59:39', 'teacher', 'T038', 'Active', 'teacher', 1, 2),
(39, 'ASHA', 'SAID', 'SEIF', 'Female', '0779240524', 'asha.seif@ipa.ac.tz', '$2y$12$KYliA3oKhA..tkdbzBM7JeGo/DopSr9nJJgB1/5ldpBLU6Mvhn/yC', '2026-04-24 06:49:05', '2026-04-24 06:49:05', 'teacher', 'T039', 'Active', 'teacher', 1, 2),
(40, 'SHAMSIA', 'MIKIDADI', 'MOHAMED', 'Female', '0777779083', 'shamisamohamed@ipa.ac.tz', '$2y$12$0okbJYXoR3yR7tFk6tsNeuX.C7vMqpCjrAugtV75XNGTCXp7bWomS', '2026-04-24 06:49:05', '2026-04-24 06:49:05', 'teacher', 'T040', 'Active', 'teacher', 1, 2),
(41, 'HAMID', 'MKUJA', 'SULEIMAN', 'Male', '0722762069', 'hamidsuleiman@ipa.ac.tz', '$2y$12$8xL9nIvnytJQPLto7XP2IOpAaa5/wdVHbywDAmpCO8pZgukLE69Hi', '2026-04-24 06:49:05', '2026-04-24 06:49:05', 'teacher', 'T041', 'Active', 'teacher', 1, 2),
(42, 'ABDALLA', 'A.', 'OMAR', 'Male', '0778381060', 'abdalomar09@gmail.com', '$2y$12$KxS7AHRdXhoICy3agrjso.I8JhxCefixxYbKfBJaWIWXQn9PCwT3u', '2026-04-24 06:49:06', '2026-04-24 06:49:06', 'teacher', 'T042', 'Active', 'teacher', 1, 2),
(43, 'ALI', 'M.', 'ALI', 'Male', '0772748151', 'a.shufaa@gmail.com', '$2y$12$h2HhB.DwURLYs8QE/eFXD.2ZnaNkHRDWA6Z1WuPZZgfXfImE37g2C', '2026-04-24 06:49:06', '2026-04-24 06:49:06', 'teacher', 'T043', 'Active', 'teacher', 1, 2),
(44, 'HILAL', 'M.', 'AME', 'Male', '0773877782', 'hilalmaabad@gmail.com', '$2y$12$ypR7CmT9/kodawmwdPTpVuizN4kS1Kgro174f05C1.tKKPA60KyL2', '2026-04-24 06:49:06', '2026-04-24 06:49:06', 'teacher', 'T044', 'Active', 'teacher', 1, 2),
(45, 'NURU', 'ISSA', 'SULEIMAN', 'Female', '0778548687', 'nuruisa2018@gmail.com', '$2y$12$5LYge4EUTnqMq4C9Ajc48u5SYL2ShlvwHavDiplOyaz/bCypa2Tvq', '2026-04-24 06:49:07', '2026-04-24 06:49:07', 'teacher', 'T045', 'Active', 'teacher', 1, 2),
(46, 'RADHIYA', 'SALEH', 'ABUUBAKAR', 'Female', '0777890939', 'radhiya.abubakar@ipa.ac.tz', '$2y$12$0e2q7syq2asHvuNWKRCsjuXld3Xmxam/OCFg36l3EXVOUG2FwsF9q', '2026-04-24 06:49:07', '2026-04-24 06:49:07', 'teacher', 'T046', 'Active', 'teacher', 1, 2),
(47, 'RASHID', 'MOHD', 'KASSIM', 'Male', '0773031546', 'varroke1975@gmail.com', '$2y$12$RSYC8gJKLtOdnYNRpYiSE.ejq8rn5myNTamyTzRC59H8Qrd30m.9m', '2026-04-24 06:49:08', '2026-04-24 06:49:08', 'teacher', 'T047', 'Active', 'teacher', 1, 2),
(48, 'JUMA', 'OMAR', 'JUMA', 'Male', '0777428328', 'juoju@hotmail.com', '$2y$12$7Ip5elAf5B6LCPgaEu/3E.vGAf2EaJqwphb00KTNFTCFRhYwLYb9m', '2026-04-24 06:49:08', '2026-04-24 06:49:08', 'teacher', 'T048', 'Active', 'teacher', 1, 2),
(49, 'AISHA', 'SAID', 'YAHYA', 'Female', '0000000', 'emailsiyo1@gmail.com', '$2y$12$8LT22m2BjcjdCGTG2uYOu..82zaW590M4F9MMEJOjqwlAcfFFiJ.O', '2026-04-24 06:49:08', '2026-04-24 06:49:08', 'teacher', 'T049', 'Active', 'teacher', 1, 2),
(50, 'KHAMIS', 'MSELEM', 'KHAMIS', 'Male', '000000000', 'emailsiyo2@gmail.com', '$2y$12$2g6ulyZIK78xI4mo.RyV/Oc8kVYvlf9HIQfNQMi8py/AWNt6fe5ia', '2026-04-24 06:49:09', '2026-04-24 06:49:09', 'teacher', 'T050', 'Active', 'teacher', 1, 2),
(51, 'ASHA', 'ALI', 'JUMA', 'Female', '000000', 'emailsiyo3@gmail.com', '$2y$12$5ck9ssvC020QbN4oJDJR.uNTmgP4ovmwCdSdaF8kQ0MtWpOxYAR8y', '2026-04-24 06:49:09', '2026-04-24 06:49:09', 'teacher', 'T051', 'Active', 'teacher', 1, 2),
(52, 'FAKIH', 'ALI', 'HAMAD', 'Male', '00000', 'emailsiyo4@gmail.com', '$2y$12$rlEaUGgWtZEA4luFcQG8leekNZkbvnUN3p/hJy0cKnm/117sLh/be', '2026-04-24 06:49:10', '2026-04-24 06:49:10', 'teacher', 'T052', 'Active', 'teacher', 1, 2),
(53, 'DK AMIRI', 'MDOE', '--', 'Male', '0000000', 'emailsiyo5@gmail.com', '$2y$12$SMSMF22DsAA9grcEDkGWkul6AMbMjTkD2XcpiPrVtkw3BB4T6McqS', '2026-04-24 06:49:10', '2026-04-30 21:58:09', 'teacher', 'T053', 'Active', 'teacher', 1, 2),
(54, 'AMANA', 'KHAMIS', 'AMANA', 'Male', '0000000', 'emailsiyo6@gmail.com', '$2y$12$KF.22TRrza1om43t8IBaBuH0wdXDHjVmA9TsoGydslvVKR85b61U2', '2026-04-24 06:49:10', '2026-04-24 06:49:10', 'teacher', 'T054', 'Active', 'teacher', 1, 2),
(55, 'AHMADA', 'HASSAN', 'AHMADA', 'Male', '0000000', 'emailsiyo7@gmail.com', '$2y$12$5stVH52CORrtp36G2fWrR.scud0hc0k2k9.LDK7HoJO48G7Mujbj2', '2026-04-24 06:49:11', '2026-04-24 06:49:11', 'teacher', 'T055', 'Active', 'teacher', 1, 2),
(56, 'DK KHAMIS', 'HAMAD', 'ALI', 'Male', '000000', 'emailsiyo8@gmail.com', '$2y$12$s0Fm4dVX6bPyn8rFYNF44uZwsD5y.yE7MdJp3Jr/MsreYYTQ5CSdq', '2026-04-24 06:49:11', '2026-04-30 22:00:03', 'teacher', 'T056', 'Active', 'teacher', 1, 2),
(57, 'OMAR', 'ALI', 'SALIM', 'Male', '00000', 'emailsiyo9@gmail.com', '$2y$12$Pg.qnlKhmqtqfp1jFq8osu9RDz3XLSzjFk06GS9AdKmdiQr76RPvS', '2026-04-24 06:49:11', '2026-04-24 06:49:11', 'teacher', 'T057', 'Active', 'teacher', 1, 2),
(58, 'ABDULLA', 'HAMAD', 'ALI', 'Male', '0000000', 'emailsiyo10@gmail.com', '$2y$12$952/qfeRexwvuwsxA9yxfuhXjdCkZFgfFCpu2ZUQPS4LGi5UwpHsS', '2026-04-24 06:49:12', '2026-04-24 06:49:12', 'teacher', 'T058', 'Active', 'teacher', 1, 2),
(59, 'HASSAN', 'YAKOUT', 'HASSAN', 'Male', '00000', 'emailsiyo11@gmail.com', '$2y$12$ru8tHSLaaewDM4N9fVekn.3tSOVwPrU7lc/27a0E1XnUc2bR5ZFaS', '2026-04-24 06:49:12', '2026-04-27 08:19:23', 'teacher', 'T059', 'Active', 'teacher', 1, 2),
(60, 'MOHAMED', 'NJERU', '-', 'Male', '00000', 'emailsiyo12@gmail.com', '$2y$12$F51umetJPg7TGRTujd0Yqu2dVF1zdXk2NYnAnlySVsTWdymPNtQge', '2026-04-24 06:49:13', '2026-04-24 06:49:13', 'teacher', 'T060', 'Active', 'teacher', 1, 2),
(61, 'PATIMA', 'KHAMIS', 'MUSSA', 'Female', '00000', 'emailsiyo13@gmail.com', '$2y$12$iJRTMw3DVHCApBe4FgKVGO00glkXwb0mOtctdqV9asGQ228yuLPvu', '2026-04-24 06:49:13', '2026-04-24 06:49:13', 'teacher', 'T061', 'Active', 'teacher', 1, 2),
(62, 'MOHAMED', 'KHAMIS', 'MTUMWA', 'Male', '000000', 'emailsiyo14@gmail.com', '$2y$12$Sz5Kw3J.In2ndneoGXCuRuNVvHqMHnL0GS9aXx1bfvrvcLYeHSsEm', '2026-04-24 06:49:13', '2026-04-24 06:49:13', 'teacher', 'T062', 'Active', 'teacher', 1, 2),
(63, 'ABDUL-HAMID', 'MOHAMED', 'ALI', 'Male', '000000', 'emailsiyo15@gmail.com', '$2y$12$bLhYkVsJtWx4bRrkQmthO.q4NVqfUC1viSjL4j36kgt1JUq.1zJZm', '2026-04-24 06:49:14', '2026-05-03 10:09:55', 'teacher', 'T063', 'Active', 'teacher', 1, 2),
(64, 'KHAMIS', 'JUMA', 'KHAMIS', 'Male', '00000', 'emailsiyo16@gmail.com', '$2y$12$GSjZVwZ3hFx8xm3LuC/DsO14tz8ZyU9WS/GwExFB.NI42fM6KBimG', '2026-04-24 06:49:14', '2026-04-24 06:49:14', 'teacher', 'T064', 'Active', 'teacher', 1, 2),
(65, 'HAMAD', 'KHAMIS', 'SAID', 'Male', '000000', 'emailsiyo17@gmail.com', '$2y$12$JN/9ki./EomCdeMUCgBjB.3Laqi5ITOAd3.rIxhsrtJOUy3WdvtRa', '2026-04-24 06:49:15', '2026-04-24 06:49:15', 'teacher', 'T065', 'Active', 'teacher', 1, 2),
(66, 'SALUM', 'RASHID', 'MOHAMED', 'Male', '000000', 'emailsiyo18@gmail.com', '$2y$12$qy/dkZrL3HYCXWk4UjeNv.GDgtpe4I/3n/48VGtGPFwyVlbMKq6tS', '2026-04-24 06:49:15', '2026-04-24 06:49:15', 'teacher', 'T066', 'Active', 'teacher', 1, 2),
(67, 'FAUZIA', 'SHAURI', 'HASSAN', 'Female', '00000000', 'fausio@gmail.com', '$2y$12$BpHlFFrNqHBkXOqsvcWrCuDTQFYFn2rzD4LNFb88nQk51pJgdGeHy', '2026-04-24 13:16:32', '2026-04-24 13:16:32', 'teacher', 'T070', 'Active', 'teacher', 1, 2),
(68, 'KHADIJA', 'SALUM', 'KHAMIS', 'Female', '000000', 'khadsio@gmail.com', '$2y$12$AzSvawPITeVNLLlgpbb.Me23zCklqEzUrhVuEkhZvDorcLjQ1HDAa', '2026-04-24 13:20:53', '2026-04-24 13:20:53', 'teacher', 'T067', 'Active', 'teacher', 1, 2),
(69, 'OMAR', 'ABEID', 'HAMAD', 'Male', '000000', 'omysio@gmail.com', '$2y$12$ast3m3oqQ.9Jm8GCL.A5MO0PWmq8uVdDUXJV9mZCcbQv7TZrydC6m', '2026-04-24 13:34:19', '2026-04-24 13:34:19', 'teacher', 'T068', 'Active', 'teacher', 1, 2),
(70, 'SHAYMA', 'HAFIDH', 'ABDALLAH', 'Female', '00000', 'shay@gmail.com', '$2y$12$yvkwAh.ILZfG.VhXAFhj6.NUrQ2a2YLnHsDZvKtyQL2WJzvLzY5c6', '2026-04-24 13:39:36', '2026-05-03 22:12:23', 'teacher', 'T069', 'Active', 'teacher', 1, 2),
(71, 'RUKIYA', '--', '--', 'Female', '000000', 'ruk@gmail.com', '$2y$12$DHrmEf5JSFxFEVyBasVllu4nUDXJo9E.aAoSDZCbtqONgp2CGXUVW', '2026-04-24 13:41:34', '2026-04-24 13:41:34', 'teacher', 'T071', 'Active', 'teacher', 1, 2),
(72, 'LUTTFA', '--', '--', 'Female', '0000000', 'lut@gmail.cin', '$2y$12$lRVZl5vHDF7krhc5RjN4COuBSwoszdjNDkuOGbGocH3n34dvFWeCy', '2026-04-24 13:42:23', '2026-04-24 13:42:23', 'teacher', 'T072', 'Active', 'teacher', 1, 2),
(78, 'ABUBAKAR', 'ALI', 'NUHU', 'Male', '000000', 'abubakar.nuhu@ipa.ac.tz', '$2y$12$Bboe5YeE3WCuHu.fDelRNuACL0Ulp/mklJO1oY/AsvYZ5yFZK41Fe', '2026-04-24 14:58:59', '2026-04-24 14:58:59', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(79, 'HAFIDH', 'HAJI ', 'HIMID', 'Male', '000000', 'badilisha1@gmail.com', '$2y$12$Q6YarSMkVPFVUMYWc8Z3WeHZgo5SNjRhbjN92gRhzcd5uymg05hN6', '2026-04-24 14:59:00', '2026-04-24 14:59:00', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(80, 'ALI', 'SALIM', 'ALI \"B\"', 'Male', '000000', 'badilisha2@gmail.com', '$2y$12$o7CqWH2qVBzFYhBuQsKCEeBeiJ8Dtqao5JoRsPTxlH2L.a/70LOJC', '2026-04-24 14:59:00', '2026-04-24 14:59:00', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(81, 'JECHA', 'VUAI', 'JECHA', 'Male', '000000', 'badilisha3@gmail.com', '$2y$12$E3napIpUj2V97W0ghCrBseyWNyP0Tm9Dz2MeoI16KqvZLbUvp.6t6', '2026-04-24 14:59:00', '2026-04-24 14:59:00', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(82, 'SABRINA ', 'MAKAME', 'KHAMIS', 'Male', '000000', 'badilisha4@gmail.com', '$2y$12$jMi4hV/q5yDcsEoDf0gNLuosZfK.xKQb4odXfIj5rAZKkjeRLBvxa', '2026-04-24 14:59:01', '2026-04-24 14:59:01', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(83, 'MWANAISHA', 'MOHD', 'HAJI', 'Female', '000000', 'badilisha5@gmail.com', '$2y$12$QwDx51ilUPlSaBEazG.5T.qwbaeqknK2AklX/keMfxmRUmhWOUKKe', '2026-04-24 14:59:01', '2026-04-24 14:59:01', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(84, 'OMAR', 'KHAMIS ', 'ALI', 'Male', '000000', 'badilisha6@gmail.com', '$2y$12$cTI56OldIDnsS5bmgYQO7eJ0OhcD5l4zslvBaZ.h1rP7lEfS/Ggoq', '2026-04-24 14:59:02', '2026-04-24 14:59:02', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(85, 'JUMA', 'HAJI ', 'JUMA', 'Male', '000000', 'badilisha7@gmail.com', '$2y$12$J1ObS6utFF2JT96DFqGEyuM3WWUEWd4FoN6ipkVgUR2smG4yfr8ay', '2026-04-24 14:59:02', '2026-04-24 14:59:02', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(86, 'ASILA', 'OTHAMAN ', 'JUMA', 'Female', '000000', 'badilisha8@gmail.com', '$2y$12$ENNNKiSDxXma15bhjgsSUuSLVcohQl4ISWWbSAmWxhBKdVpTz.k4K', '2026-04-24 14:59:02', '2026-04-24 14:59:02', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(87, 'KHAMIS', 'NASSOR', 'SALUM', 'Male', '000000', 'badilisha9@gmail.com', '$2y$12$3T2is2uj51Ya3QgkvCfEk.enrvBk68E/DcDbn/o.UZRQdVM5DgmPW', '2026-04-24 14:59:03', '2026-04-24 14:59:03', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(88, 'SEIF ', 'OMAR', 'SEIF', 'Male', '000000', 'badilisha10@gmail.com', '$2y$12$JeHnuemg.fOD20017de6Heo1L5h4TGrRaYCqjVEhZj8TF5pLnvXpK', '2026-04-24 14:59:03', '2026-04-24 14:59:03', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(89, 'SAADE', 'HASSAN', 'JINALAMWISHI', 'Female', '000000', 'badilisha11@gmail.com', '$2y$12$SXhh6nryIvUdAlfu8.eYxeYOdR.CegBgR4igieXRQmgYW/6NHVFXu', '2026-04-24 14:59:03', '2026-04-24 14:59:03', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(90, 'RASHID', 'HAJI ', 'JUMA', 'Male', '000000', 'badilisha12@gmail.com', '$2y$12$EDKu/HMYdEkJVTy.hI60u.aq.j1LEmQYcLeWC/AlFAyzPV6h6lTYy', '2026-04-24 14:59:04', '2026-04-24 14:59:04', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(91, 'ISSA', 'ALI', 'MATLUB', 'Male', '000000', 'issa.matlub@ipa.ac.tz', '$2y$12$SglAqkp.LrG3sIR7h4CJ8epIy9fVqO8ViLmVJNjKG72z3CsIBRsSG', '2026-04-24 14:59:04', '2026-04-24 14:59:04', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(92, 'SULEIMAN', 'ALI', 'JINALAMWISHI', 'Male', '000000', 'badilisha13@gmail.com', '$2y$12$JEXL2es.4.Shcdk7AxyGnuyntqCeE7WObovRz9wwswM7pZD7XseVi', '2026-04-24 14:59:05', '2026-04-24 14:59:05', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(93, 'KHAYRA', 'SEIF', 'ALI', 'Female', '000000', 'khayra.ali@ipa.ac.tz', '$2y$12$JjQLFGyCDb09Uw0jE2YGruBABct.NMzW7wQniChrOLfH3F3PblNSW', '2026-04-24 14:59:05', '2026-04-24 14:59:05', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(94, 'MOHAMMED', 'OMAR', 'MOHAMMED', 'Male', '000000', 'mohamed@ipa.ac.tz', '$2y$12$oF0bsXvA.EGh5wajE6iO5eEZDlwWql7LTVvxGa/teUmoQ.3954pU2', '2026-04-24 14:59:05', '2026-04-24 14:59:05', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(95, 'ABDALLAH', 'MAJID', 'JINALAMWISHI', 'Male', '000000', 'badilisha15@gmail.com', '$2y$12$/czRs4ETZN7KFFd51OQ0D.bpFuLTBIsXbFHv9P6N/hXSsBb1PF2yK', '2026-04-24 14:59:06', '2026-04-24 14:59:06', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(96, 'ALI', 'SALIM', 'ALI \"A\"', 'Male', '000000', 'badilisha16@gmail.com', '$2y$12$8gQhelt7ilCLGk4OksSEqufND3nXcbMwW.bQ3c5s.dhxUkmVI.NDe', '2026-04-24 14:59:06', '2026-04-24 14:59:06', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(97, 'MAHIRA', 'BARAKA', 'HAMAD', 'Female', '000000', 'badilisha17@gmail.com', '$2y$12$PNr7fp.qxz4RF3tX5emRmO5eAlpkpJWB/bZ0SvltTg/xg63br6mgG', '2026-04-24 14:59:07', '2026-04-28 11:59:28', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(98, 'SAID', 'JUMA', 'HASSAN', 'Male', '000000', 'badilisha18@gmail.com', '$2y$12$upqMs1c/dQC4Z/cAVWw5debsf2cV.RsPaDb5bJymaEE7LGxgt0EB.', '2026-04-24 14:59:07', '2026-04-24 14:59:07', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(99, 'ALI', 'NUHU', 'NUHU', 'Male', '000000', 'badilisha19@gmail.com', '$2y$12$kOuLbaqWfjsybDgw9uvdfujGM1Kx0yik3e.aitUKnpv7fBuNMjKfO', '2026-04-24 14:59:07', '2026-04-24 14:59:07', 'teacher', 'T073', 'Active', 'teacher', 2, 3),
(100, 'MOHAMED', 'MASOUD', 'ALMASI', 'Male', '000000', 'emailsiyo70@gmail.com', '$2y$12$sNnQgNUAlOicz5Yb/5aLFO/zWfT6MiUmtAD3sNwyUrbRhtA50.ZPy', '2026-04-25 07:02:14', '2026-04-25 07:02:14', 'teacher', 'T080', 'Active', 'teacher', 1, 1),
(101, 'NURU', 'SEIF', 'ABDALLAH', 'Female', '07738384', 'nuru1siyo@gmail.com', '$2y$12$8UGkxQrigcpTKcMCNdDRRuOKPnsUN9IWVkmsSVxLVbWk2FkGdfkaC', '2026-04-25 11:35:35', '2026-04-25 11:35:35', 'teacher', 'T081', 'Active', 'teacher', 1, 2),
(102, 'ABDUL-KARIM', '--', '--', 'Male', '000000', 'abdsiyo@gmail.com', '$2y$12$SeUQr8W3h4x1T3zpwnYUWO7ubgu0o5etpRlkCbZ4lgsUo00Ekk2gm', '2026-04-25 11:37:55', '2026-04-25 11:37:55', 'teacher', 'T082', 'Active', 'teacher', 1, 2),
(103, 'MAKAME', 'SALIM', 'ALI', 'Male', '00000', 'makesiyo@gmail.com', '$2y$12$7lSo1OTkCSL4HBApb6TOK.545eo2KLLagar1Tnc8OS8V7Rh6f4AMS', '2026-04-25 11:41:47', '2026-04-27 08:42:06', 'teacher', 'T084', 'Active', 'teacher', 1, 2),
(104, 'MOHAMED', 'SAID', 'MOHAMED', 'Male', '0000000', 'mohsiyo1@gmail.com', '$2y$12$7Fw4npXFU3GkJzdSa7qhK.hSzxdeENmfNiGwvbsFAbl0WNrDNvZku', '2026-04-25 11:49:57', '2026-04-25 11:49:57', 'teacher', 'T084', 'Active', 'teacher', 1, 1),
(105, 'AMOUR', 'SALIM', 'ABDI', 'Male', '00000', 'amousiyo@gmail.com', '$2y$12$z18SEzrtB0qeBBxK/kSGn.6p2HLsjWECDXeusAFjCZJjE3qcJTlu6', '2026-04-25 11:53:55', '2026-04-25 11:53:55', 'teacher', 'T085', 'Active', 'teacher', 1, 2),
(106, 'SHAMIS', '--', '--', 'Male', '000000', 'abdsiyo32@gmail.com', '$2y$12$WxkgmmW9kP6.RWEtSrHsGOMcl3uK08Ls7DKqQMFwXVCLagu8lwm/2', '2026-04-25 11:57:18', '2026-04-28 21:15:46', 'teacher', 'T089', 'Active', 'teacher', 1, 2),
(107, 'SHAMIS', '--', '--', 'Male', '--', 'bandiasiyo2@gmail.com', '$2y$12$TqQcQVIbg1Fd1L/IZrSrpuPgjD9aedsPKNcXYn8z2DLUZe6mUK1ie', '2026-04-25 12:43:03', '2026-04-28 21:16:11', 'teacher', 't089', 'Active', 'teacher', 1, 2),
(108, 'SHAMIS', '--', '--', 'Male', '--', 'bandiasiyo3@gmail.com', '$2y$12$l14ey1L.mdwjYR86/w.PH.p69qjWh4UBu8kiWIVfO5tPxaNAI2i6S', '2026-04-25 12:49:27', '2026-04-28 21:16:38', 'teacher', 'T099', 'Active', 'teacher', 1, 2),
(109, 'SHAMIS', '--', '--', 'Male', '--', 'bandiya4siyo@gmail.com', '$2y$12$hGoqzohrwUrvc4oWnXMZOOZJCn8FUi5Y9eOwG7u/oDocyEKLABXD6', '2026-04-25 12:58:04', '2026-04-28 21:16:59', 'teacher', 'T091', 'Active', 'teacher', 1, 2),
(110, 'SHAMIS', '--', '--', 'Male', '--', 'banusiyo@gmail.com', '$2y$12$RUzR2vMSBsuA7G3wHlPKWevSSsdxAtTtyWn.VNhvd8RSGlHh9q7US', '2026-04-25 13:25:13', '2026-04-28 21:17:13', 'teacher', 'T093', 'Active', 'teacher', 1, 1),
(111, 'ALHAJI', 'MTUMWA', 'JECHA', 'Male', '00000', 'emailsiyo55@gmail.com', '$2y$12$hxZ80Rs.LmWYiAU20tpDIeOYTNClp6C8mJoB42JwnI8XDydjKACjm', '2026-04-27 08:26:23', '2026-04-27 08:26:23', 'teacher', 'T100', 'Active', 'teacher', 1, 1),
(112, 'MAKAME', 'BAKILI', 'SILIMA', 'Male', '0773848727', 'makame.silima@ipa.ac.tz', '$2y$12$h/WdK8SJsFHeLr5Nq7Mn6./ZGxvP6OhFO9G4zHnGCwUjwTrqC8sjq', '2026-04-27 08:45:08', '2026-04-27 08:45:43', 'teacher', 'T102', 'Active', 'teacher', 1, 1),
(113, 'KIBWANA', 'MWINYI', 'KOMBO', 'Male', '0788108012', 'emailkibwan@gmail.com', '$2y$12$Bnle.ZgqieS1UvGDv/z/Cu7EVbntn4T4Bi0Bf5e2kp03oIIEoFTe2', '2026-04-27 09:12:36', '2026-05-05 00:44:37', 'teacher', 'T1928', 'Active', 'teacher', 1, 2),
(114, 'ISMAIL', 'FAKI', 'KHAMIS', 'Male', '0768773322', 'ismail.khamis@ipa.ac.tz', '$2y$12$SbbZ5cxBbz14YSZcDbioTOCsQYwdxkpkYBinupIhamytPyH4THf/.', '2026-04-27 09:18:28', '2026-04-27 09:18:28', 'teacher', 'T1002', 'Active', 'teacher', 1, 1),
(115, 'HUMOUD', 'SAID', 'HUMOUD', 'Male', '0777574290', 'humoud.humoud@ipa.ac.tz', '$2y$12$RFKXE2dhqZOXKQ9D5GG/3efASgAkKl9jMWnHEIiEJ13G4iIQvjVx.', '2026-04-27 09:32:18', '2026-04-27 09:32:18', 'teacher', 'T283', 'Active', 'teacher', 1, 1),
(116, 'MOHAMED', 'SEIF', 'KASSIM', 'Male', '0776814994', 'mohsiyo89@gmail.com', '$2y$12$fzUf7F5syvOPQH1h4uaFRuB5vCP1KNsKR9qpzDkCl.4h7/1BPvEfG', '2026-04-27 09:42:13', '2026-04-27 09:42:13', 'teacher', 'T183', 'Active', 'teacher', 1, 1),
(117, 'MARIYAM', 'ALI', 'SALE', 'Female', '0000000', 'mariyamsaleh100@gmail.com', '$2y$12$JmtjLIeW7b6HLY.gA6Wfm.nwMrYqlSnH0/MDmL4MEDygUykqhA68C', '2026-04-28 21:04:06', '2026-04-28 21:04:06', 'teacher', 'T239', 'Active', 'teacher', 1, 2),
(118, 'HAJI', 'MWITA', 'HAJI', 'Male', '07738399', 'emailsiyo67@gmail.com', '$2y$12$w4tRyngX/VoY6h96oUoia.O1vV/pqLimsU5IxM10PKhYZ9l.rzpCm', '2026-05-03 10:13:12', '2026-05-03 10:13:12', 'teacher', 'T2828', 'Active', 'teacher', 1, 2),
(119, 'SAADA', 'HAMID', 'MOHAMED', 'Female', '0000000', 'saad@gmail.com', '$2y$12$f4Nf1TCY3T92za0Ht/.lReNyMrEYjLsobuDSdFzEDlA9fZ.Dhcq5e', '2026-05-03 22:05:38', '2026-05-03 22:05:38', 'teacher', 'T0889', 'Active', 'teacher', 1, 2),
(120, 'SUMAIYA', 'RASHID', 'HUSSEIN', 'Female', '000000', 'sumr@gmail.com', '$2y$12$33fZdgpZ1/ugtU2JUflhgOX0Aa4aV9EPVY478Z9fYgI/pOBeHQE4C', '2026-05-03 22:07:10', '2026-05-03 22:07:10', 'teacher', 'T09283', 'Active', 'teacher', 1, 2),
(121, 'TATU', 'ALI', 'MTUMWA', 'Female', '0714543132', 'tatusal@yahoo.com', '$2y$12$CMRZp8NCte0SxePXSsl83OtZcBt9KImQq5k4e/bW08lMPmUhSEIHS', '2026-05-04 10:49:03', '2026-05-04 10:49:03', 'teacher', 'T2388', 'Active', 'teacher', 1, 1),
(122, 'ALI', 'JUMA', 'KHAMIS', 'Male', '0000000', 'alisiyo7@gmail.com', '$2y$12$1DkVYIZYG3gHjWQVnEQnOeocIUwh7SHlh3wGw3DHD0dHNLaJaMudC', '2026-05-16 02:37:07', '2026-05-16 02:37:07', 'teacher', 'T08392', 'Active', 'teacher', 1, 2),
(123, 'HEMED', 'OMAR', 'SALIM', 'Male', '000000', 'hemedomar48@gmail.com', '$2y$12$8JTicoYwZNvThbs9h.3SIek8.ayyrHGBDJxbt4xEsaWZH0Z2vRrWC', '2026-05-16 02:42:36', '2026-05-16 02:42:36', 'teacher', 'T02838', 'Active', 'teacher', 1, 2);

-- --------------------------------------------------------

--
-- Table structure for table `teacher_attendances`
--

CREATE TABLE `teacher_attendances` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `timetable_id` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `status` enum('present','absent','emergency') NOT NULL,
  `status2` enum('Active','Paused') NOT NULL DEFAULT 'Active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `course_id` bigint(20) UNSIGNED DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `teacher_attendances`
--

INSERT INTO `teacher_attendances` (`id`, `teacher_id`, `subject_id`, `timetable_id`, `date`, `status`, `status2`, `created_at`, `updated_at`, `course_id`) VALUES
(1, 104, 907, 2895, '2026-04-27', 'absent', 'Active', '2026-04-27 06:07:02', '2026-04-27 06:07:02', NULL),
(2, 104, 907, 2896, '2026-04-27', 'present', 'Active', '2026-04-27 06:07:02', '2026-04-27 06:11:19', NULL),
(3, 23, 908, 2898, '2026-04-27', 'absent', 'Active', '2026-04-27 06:07:02', '2026-04-27 06:07:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `timeslots`
--

CREATE TABLE `timeslots` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `status` varchar(255) DEFAULT 'Normal'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timeslots`
--

INSERT INTO `timeslots` (`id`, `start_time`, `end_time`, `created_at`, `updated_at`, `status`) VALUES
(1, '08:00:00', '09:00:00', '2026-04-25 01:23:14', '2026-04-25 01:23:14', 'Normal'),
(2, '09:00:00', '10:00:00', '2026-04-25 01:23:30', '2026-04-25 01:23:30', 'Normal'),
(3, '10:00:00', '11:00:00', '2026-04-25 01:23:45', '2026-04-25 01:23:45', 'Normal'),
(4, '11:00:00', '12:00:00', '2026-04-25 01:23:59', '2026-04-25 01:23:59', 'Normal'),
(5, '12:00:00', '13:00:00', '2026-04-25 01:24:15', '2026-04-25 01:24:15', 'Normal'),
(6, '13:00:00', '14:00:00', '2026-04-25 01:24:30', '2026-04-25 01:24:30', 'Normal'),
(7, '14:00:00', '15:00:00', '2026-04-25 01:24:43', '2026-04-25 01:24:43', 'Normal'),
(8, '15:00:00', '16:00:00', '2026-04-25 01:24:56', '2026-04-25 01:24:56', 'Normal'),
(9, '16:00:00', '17:00:00', '2026-04-25 01:25:11', '2026-04-25 01:25:11', 'Normal'),
(10, '17:00:00', '18:00:00', '2026-04-25 01:25:41', '2026-04-25 01:25:41', 'Normal'),
(11, '18:00:00', '19:00:00', '2026-04-25 01:26:01', '2026-04-25 01:26:01', 'Normal'),
(12, '19:00:00', '20:00:00', '2026-04-25 01:26:44', '2026-04-25 01:26:44', 'Normal');

-- --------------------------------------------------------

--
-- Table structure for table `timetables`
--

CREATE TABLE `timetables` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `day_id` bigint(20) UNSIGNED NOT NULL,
  `subject_id` bigint(20) UNSIGNED NOT NULL,
  `timeslot_id` bigint(20) UNSIGNED NOT NULL,
  `room_id` bigint(20) UNSIGNED NOT NULL,
  `group_name` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `semester_id` bigint(20) UNSIGNED NOT NULL,
  `teacher_id` bigint(20) UNSIGNED DEFAULT NULL,
  `branch_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `timetables`
--

INSERT INTO `timetables` (`id`, `day_id`, `subject_id`, `timeslot_id`, `room_id`, `group_name`, `created_at`, `updated_at`, `semester_id`, `teacher_id`, `branch_id`) VALUES
(2529, 5, 771, 3, 2, NULL, '2026-04-25 21:11:04', '2026-04-26 06:49:52', 3, 53, 1),
(2530, 5, 771, 4, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 23:51:23', 3, 53, 1),
(2531, 1, 771, 1, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 53, 1),
(2532, 2, 772, 8, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 23:15:07', 3, 36, 1),
(2533, 3, 772, 2, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 21:30:08', 3, 36, 1),
(2534, 2, 772, 9, 9, NULL, '2026-04-25 21:11:04', '2026-05-04 21:04:48', 3, 36, 1),
(2535, 1, 773, 2, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2536, 1, 773, 3, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2537, 4, 773, 3, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2538, 2, 774, 1, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2539, 2, 774, 2, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2540, 1, 774, 4, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2541, 3, 775, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:13:50', 3, 46, 1),
(2542, 3, 775, 5, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:13:50', 3, 46, 1),
(2543, 2, 775, 3, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:13:50', 3, 46, 1),
(2544, 4, 776, 4, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 45, 1),
(2545, 4, 776, 5, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 45, 1),
(2546, 2, 776, 4, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 45, 1),
(2547, 2, 777, 5, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2548, 2, 777, 6, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2549, 4, 777, 6, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2550, 5, 778, 1, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2551, 5, 778, 2, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2552, 2, 778, 7, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2553, 1, 779, 6, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:34:27', 3, 24, 1),
(2554, 1, 779, 5, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 15:33:54', 3, 24, 1),
(2555, 3, 779, 1, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:35:19', 3, 24, 1),
(2556, 3, 780, 6, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2557, 3, 780, 7, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2558, 1, 780, 7, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2559, 4, 781, 7, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2560, 4, 781, 8, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2561, 1, 781, 8, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 46, 1),
(2562, 4, 782, 4, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 06:47:50', 3, 50, 1),
(2563, 4, 782, 5, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 06:48:19', 3, 50, 1),
(2564, 2, 782, 9, 12, NULL, '2026-04-25 21:11:04', '2026-04-27 22:08:01', 3, 50, 1),
(2565, 5, 783, 1, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 53, 1),
(2566, 5, 783, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 53, 1),
(2567, 4, 783, 9, 1, NULL, '2026-04-25 21:11:04', '2026-05-03 21:09:04', 3, 53, 1),
(2568, 1, 784, 1, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2569, 1, 784, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2570, 3, 784, 7, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 20:46:46', 3, 47, 1),
(2571, 2, 785, 7, 18, NULL, '2026-04-25 21:11:04', '2026-04-30 00:08:47', 3, 46, 1),
(2572, 3, 785, 8, 18, NULL, '2026-04-25 21:11:04', '2026-04-29 05:11:05', 3, 46, 1),
(2573, 3, 785, 9, 18, NULL, '2026-04-25 21:11:04', '2026-04-29 05:10:45', 3, 46, 1),
(2574, 2, 786, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2575, 2, 786, 3, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2576, 1, 786, 3, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2577, 3, 787, 4, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 21:13:53', 3, 41, 1),
(2578, 3, 787, 5, 18, NULL, '2026-04-25 21:11:04', '2026-04-26 07:52:55', 3, 41, 1),
(2579, 2, 787, 8, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 23:57:39', 3, 41, 1),
(2580, 5, 788, 4, 5, NULL, '2026-04-25 21:11:04', '2026-04-26 06:53:56', 3, 41, 1),
(2581, 2, 788, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 23:59:11', 3, 41, 1),
(2582, 3, 788, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 41, 1),
(2583, 5, 789, 3, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 23:54:06', 3, 48, 1),
(2584, 3, 789, 5, 1, NULL, '2026-04-25 21:11:04', '2026-04-26 06:56:01', 3, 48, 1),
(2585, 3, 789, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 48, 1),
(2586, 1, 790, 3, 16, NULL, '2026-04-25 21:11:04', '2026-04-30 22:13:17', 3, 1, 1),
(2587, 1, 790, 4, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2588, 3, 790, 7, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2589, 1, 791, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 107, 1),
(2590, 1, 791, 7, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 107, 1),
(2591, 4, 791, 4, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 23:53:01', 3, 107, 1),
(2592, 5, 792, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2593, 5, 792, 7, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2594, 3, 792, 8, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2595, 4, 793, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2596, 4, 793, 3, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2597, 5, 793, 8, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2598, 5, 794, 8, 4, NULL, '2026-04-25 21:11:04', '2026-04-30 01:26:01', 3, 48, 1),
(2599, 3, 794, 2, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 48, 1),
(2600, 5, 794, 9, 3, NULL, '2026-04-25 21:11:04', '2026-04-30 01:26:45', 3, 48, 1),
(2601, 3, 795, 4, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 08:05:48', 3, 106, 1),
(2602, 3, 795, 3, 9, NULL, '2026-04-25 21:11:04', '2026-05-05 00:40:07', 3, 106, 1),
(2603, 4, 795, 2, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2604, 5, 796, 5, 7, NULL, '2026-04-25 21:11:04', '2026-04-26 00:05:49', 3, 109, 1),
(2605, 2, 796, 2, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 109, 1),
(2606, 2, 796, 1, 3, NULL, '2026-04-25 21:11:04', '2026-04-26 00:03:25', 3, 109, 1),
(2607, 4, 797, 1, 1, NULL, '2026-04-25 21:11:04', '2026-05-03 22:38:41', 3, 54, 1),
(2608, 2, 797, 5, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 08:05:04', 3, 54, 1),
(2609, 2, 797, 3, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2610, 1, 798, 3, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2611, 1, 798, 4, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2612, 2, 798, 4, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 43, 1),
(2613, 3, 799, 9, 12, NULL, '2026-04-25 21:11:04', '2026-04-29 14:12:32', 3, 107, 1),
(2614, 3, 799, 10, 10, NULL, '2026-04-25 21:11:04', '2026-04-29 14:13:16', 3, 107, 1),
(2615, 1, 799, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-29 14:11:44', 3, 107, 1),
(2619, 3, 801, 4, 3, NULL, '2026-04-25 21:11:04', '2026-04-26 07:56:48', 3, 35, 1),
(2620, 3, 801, 5, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2621, 4, 801, 4, 13, NULL, '2026-04-25 21:11:04', '2026-04-26 07:57:28', 3, 35, 1),
(2622, 5, 802, 7, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2623, 5, 802, 8, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2624, 1, 802, 8, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2625, 3, 803, 3, 1, NULL, '2026-04-25 21:11:04', '2026-04-26 08:01:11', 3, 41, 1),
(2626, 4, 803, 5, 3, NULL, '2026-04-25 21:11:04', '2026-05-03 22:38:16', 3, 41, 1),
(2627, 2, 803, 1, 10, NULL, '2026-04-25 21:11:04', '2026-05-03 22:34:40', 3, 41, 1),
(2628, 2, 804, 6, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2629, 2, 804, 7, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2630, 3, 804, 6, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2631, 4, 805, 9, 6, NULL, '2026-04-25 21:11:04', '2026-04-28 03:13:36', 3, 106, 1),
(2632, 1, 805, 5, 1, NULL, '2026-04-25 21:11:04', '2026-05-19 02:04:09', 3, 106, 1),
(2633, 4, 805, 10, 10, NULL, '2026-04-25 21:11:04', '2026-04-28 03:14:26', 3, 106, 1),
(2634, 5, 806, 1, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2635, 5, 806, 2, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2636, 1, 806, 1, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 62, 1),
(2637, 1, 807, 2, 15, NULL, '2026-04-25 21:11:04', '2026-05-03 21:35:50', 3, 22, 1),
(2638, 2, 807, 1, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 11:32:11', 3, 22, 1),
(2639, 4, 807, 8, 6, NULL, '2026-04-25 21:11:04', '2026-04-28 22:05:49', 3, 22, 1),
(2640, 3, 808, 4, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:24:39', 3, 42, 1),
(2641, 3, 808, 6, 15, NULL, '2026-04-25 21:11:04', '2026-04-28 00:11:00', 3, 42, 1),
(2642, 5, 808, 5, 16, NULL, '2026-04-25 21:11:04', '2026-04-28 00:12:36', 3, 42, 1),
(2643, 1, 809, 8, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 21:34:21', 3, 43, 1),
(2644, 1, 809, 7, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 11:29:31', 3, 43, 1),
(2645, 2, 809, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-29 23:29:32', 3, 43, 1),
(2646, 2, 810, 2, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2647, 2, 810, 3, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2648, 1, 810, 4, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2649, 5, 811, 6, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2650, 5, 811, 7, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2651, 2, 811, 4, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2652, 2, 812, 8, 16, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2653, 2, 812, 9, 9, NULL, '2026-04-25 21:11:04', '2026-05-04 21:04:48', 3, 36, 1),
(2654, 3, 812, 2, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 21:30:08', 3, 36, 1),
(2655, 5, 813, 9, 14, NULL, '2026-04-25 21:11:04', '2026-04-26 00:32:15', 3, 42, 1),
(2656, 3, 813, 1, 13, NULL, '2026-04-25 21:11:04', '2026-05-03 21:31:54', 3, 42, 1),
(2657, 5, 813, 8, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2658, 1, 814, 6, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 21:27:48', 3, 44, 1),
(2659, 2, 814, 3, 6, NULL, '2026-04-25 21:11:04', '2026-04-27 14:00:01', 3, 44, 1),
(2660, 3, 814, 3, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 21:30:24', 3, 44, 1),
(2661, 1, 815, 7, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2662, 1, 815, 8, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2663, 2, 815, 6, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2664, 2, 816, 1, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2665, 2, 816, 2, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2666, 3, 816, 1, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2667, 5, 817, 1, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2668, 5, 817, 2, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2669, 3, 817, 2, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2670, 1, 818, 7, 8, NULL, '2026-04-25 21:11:04', '2026-04-26 00:37:54', 3, 42, 1),
(2671, 1, 818, 6, 11, NULL, '2026-04-25 21:11:04', '2026-04-26 00:38:20', 3, 42, 1),
(2672, 3, 818, 3, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2673, 5, 819, 7, 11, NULL, '2026-04-25 21:11:04', '2026-05-03 23:39:11', 3, 52, 1),
(2674, 5, 819, 8, 8, NULL, '2026-04-25 21:11:04', '2026-05-03 23:39:49', 3, 52, 1),
(2675, 1, 819, 1, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 52, 1),
(2676, 1, 820, 5, 11, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2677, 5, 820, 6, 11, NULL, '2026-04-25 21:11:04', '2026-05-03 20:36:30', 3, 56, 1),
(2678, 5, 820, 5, 14, NULL, '2026-04-25 21:11:04', '2026-05-03 20:37:02', 3, 56, 1),
(2679, 2, 821, 5, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2680, 2, 821, 6, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2681, 3, 821, 1, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2682, 2, 822, 1, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2683, 2, 822, 2, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2684, 3, 822, 2, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 21:28:20', 3, 42, 1),
(2685, 4, 823, 1, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2686, 4, 823, 2, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2687, 1, 823, 4, 15, NULL, '2026-04-25 21:11:04', '2026-04-26 00:40:03', 3, 12, 1),
(2688, 2, 824, 3, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2689, 2, 824, 4, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2690, 4, 824, 3, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 110, 1),
(2691, 4, 825, 4, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2692, 4, 825, 5, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2693, 1, 825, 9, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 21:26:45', 3, 32, 1),
(2694, 1, 826, 2, 15, NULL, '2026-04-25 21:11:04', '2026-05-03 21:35:50', 3, 22, 1),
(2695, 4, 826, 8, 6, NULL, '2026-04-25 21:11:04', '2026-04-28 22:05:49', 3, 22, 1),
(2696, 2, 826, 1, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 11:32:11', 3, 22, 1),
(2697, 1, 827, 5, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 23:39:53', 3, 21, 1),
(2698, 3, 827, 1, 16, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2699, 1, 827, 6, 16, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2700, 2, 828, 8, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 23:15:07', 3, 36, 1),
(2701, 2, 828, 9, 9, NULL, '2026-04-25 21:11:04', '2026-05-04 21:04:48', 3, 36, 1),
(2702, 3, 828, 2, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 21:30:08', 3, 36, 1),
(2703, 4, 829, 9, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 09:41:54', 3, 37, 1),
(2704, 5, 829, 1, 15, NULL, '2026-04-25 21:11:04', '2026-05-08 09:48:45', 3, 37, 1),
(2705, 4, 829, 10, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 09:42:27', 3, 37, 1),
(2706, 1, 830, 8, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 21:34:21', 3, 43, 1),
(2707, 1, 830, 7, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 11:29:31', 3, 43, 1),
(2708, 2, 830, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-29 23:29:32', 3, 43, 1),
(2709, 5, 831, 5, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2710, 4, 831, 7, 4, NULL, '2026-04-25 21:11:04', '2026-04-29 04:57:15', 3, 1, 1),
(2711, 5, 831, 4, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2712, 2, 832, 3, 16, NULL, '2026-04-25 21:11:04', '2026-04-28 22:32:27', 3, 36, 1),
(2713, 4, 832, 1, 14, NULL, '2026-04-25 21:11:04', '2026-05-05 00:46:09', 3, 36, 1),
(2714, 2, 832, 4, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2715, 3, 833, 7, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2716, 3, 833, 8, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2717, 4, 833, 3, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2718, 5, 834, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2719, 5, 834, 7, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2720, 2, 834, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2721, 5, 835, 4, 8, NULL, '2026-04-25 21:11:04', '2026-04-26 08:13:23', 3, 36, 1),
(2722, 5, 835, 3, 15, NULL, '2026-04-25 21:11:04', '2026-04-26 08:11:39', 3, 36, 1),
(2723, 3, 835, 5, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2724, 2, 836, 7, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2725, 2, 836, 8, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2726, 4, 836, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2730, 2, 838, 3, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2731, 2, 838, 4, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2732, 4, 838, 1, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 100, 1),
(2733, 3, 839, 3, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2734, 3, 839, 4, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2735, 1, 839, 1, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 40, 1),
(2736, 4, 840, 2, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2737, 4, 840, 3, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2738, 2, 840, 1, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2739, 5, 841, 1, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2740, 5, 841, 2, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2741, 2, 841, 2, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2742, 1, 842, 4, 14, NULL, '2026-04-25 21:11:04', '2026-04-26 00:17:07', 3, 42, 1),
(2743, 2, 842, 7, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2744, 5, 842, 6, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2745, 3, 843, 1, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2746, 3, 843, 2, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2747, 1, 843, 3, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2748, 3, 844, 4, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2749, 3, 844, 5, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2750, 4, 844, 4, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 35, 1),
(2751, 5, 845, 1, 6, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2752, 5, 845, 2, 6, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2753, 4, 845, 2, 6, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2754, 1, 846, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-29 14:11:44', 3, 17, 1),
(2755, 3, 846, 9, 12, NULL, '2026-04-25 21:11:04', '2026-04-29 14:12:32', 3, 17, 1),
(2756, 3, 846, 10, 10, NULL, '2026-04-25 21:11:04', '2026-04-29 14:13:16', 3, 17, 1),
(2757, 5, 847, 3, 6, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 52, 1),
(2758, 5, 847, 4, 6, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 52, 1),
(2759, 1, 847, 2, 5, NULL, '2026-04-25 21:11:04', '2026-04-29 05:26:15', 3, 52, 1),
(2760, 2, 848, 1, 16, NULL, '2026-04-25 21:11:04', '2026-05-03 11:32:11', 3, 48, 1),
(2761, 1, 848, 2, 15, NULL, '2026-04-25 21:11:04', '2026-05-03 21:35:50', 3, 48, 1),
(2762, 4, 848, 8, 6, NULL, '2026-04-25 21:11:04', '2026-04-29 13:55:43', 3, 48, 1),
(2763, 5, 849, 3, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2764, 5, 849, 4, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2765, 3, 849, 1, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2766, 1, 850, 4, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2767, 1, 850, 5, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2768, 3, 850, 3, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2769, 5, 851, 1, 15, NULL, '2026-04-25 21:11:04', '2026-05-08 09:48:45', 3, 37, 1),
(2770, 4, 851, 9, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 09:41:54', 3, 37, 1),
(2771, 4, 851, 10, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 09:42:27', 3, 37, 1),
(2772, 1, 852, 7, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 11:29:31', 3, 41, 1),
(2773, 2, 852, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:22', 3, 41, 1),
(2774, 1, 852, 8, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 21:34:21', 3, 41, 1),
(2775, 5, 853, 7, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 00:26:53', 3, 55, 1),
(2776, 5, 853, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2777, 2, 853, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 08:35:22', 3, 55, 1),
(2778, 1, 854, 9, 7, NULL, '2026-04-25 21:11:04', '2026-04-26 05:40:09', 3, 38, 1),
(2779, 2, 854, 3, 1, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:30', 3, 38, 1),
(2780, 2, 854, 2, 16, NULL, '2026-04-25 21:11:04', '2026-04-26 00:20:11', 3, 38, 1),
(2781, 1, 855, 7, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2782, 1, 855, 8, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2783, 3, 855, 6, 8, NULL, '2026-04-25 21:11:04', '2026-04-26 08:31:40', 3, 56, 1),
(2784, 1, 856, 5, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 15:33:54', 3, 24, 1),
(2785, 3, 856, 1, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:35:19', 3, 24, 1),
(2786, 1, 856, 6, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:34:27', 3, 24, 1),
(2787, 2, 857, 7, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2788, 2, 857, 8, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2789, 3, 857, 5, 15, NULL, '2026-04-25 21:11:04', '2026-04-26 00:23:54', 3, 47, 1),
(2790, 5, 858, 1, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 72, 1),
(2791, 5, 858, 2, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 72, 1),
(2792, 1, 858, 1, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 72, 1),
(2793, 1, 859, 10, 9, NULL, '2026-04-25 21:11:04', '2026-05-03 12:15:18', 3, 56, 1),
(2794, 3, 859, 4, 9, NULL, '2026-04-25 21:11:04', '2026-05-03 12:13:34', 3, 56, 1),
(2795, 3, 859, 3, 14, NULL, '2026-04-25 21:11:04', '2026-05-03 12:12:52', 3, 56, 1),
(2796, 4, 860, 4, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2797, 1, 860, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2798, 1, 860, 7, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 42, 1),
(2799, 1, 861, 2, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 101, 1),
(2800, 1, 861, 3, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 101, 1),
(2801, 2, 861, 3, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 101, 1),
(2802, 2, 862, 4, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2803, 2, 862, 5, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2804, 4, 862, 7, 5, NULL, '2026-04-25 21:11:04', '2026-04-27 21:56:57', 3, 50, 1),
(2805, 3, 863, 9, 3, NULL, '2026-04-25 21:11:04', '2026-05-04 23:12:19', 3, 55, 1),
(2806, 2, 863, 6, 7, NULL, '2026-04-25 21:11:04', '2026-05-03 22:45:20', 3, 55, 1),
(2807, 3, 863, 10, 11, NULL, '2026-04-25 21:11:04', '2026-04-27 14:00:58', 3, 55, 1),
(2808, 3, 864, 1, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2809, 3, 864, 2, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2810, 1, 864, 5, 8, NULL, '2026-04-25 21:11:04', '2026-05-04 20:49:38', 3, 71, 1),
(2811, 2, 865, 3, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2812, 2, 865, 4, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2813, 5, 865, 1, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 56, 1),
(2814, 4, 866, 1, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 61, 1),
(2815, 4, 866, 2, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 61, 1),
(2816, 5, 866, 2, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 61, 1),
(2817, 2, 867, 1, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2818, 2, 867, 2, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2819, 4, 867, 3, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2820, 4, 868, 4, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2821, 4, 868, 5, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2822, 2, 868, 5, 15, NULL, '2026-04-25 21:11:04', '2026-05-05 00:31:47', 3, 38, 1),
(2823, 1, 869, 1, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2824, 1, 869, 2, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 50, 1),
(2825, 4, 869, 8, 11, NULL, '2026-04-25 21:11:04', '2026-04-27 21:57:55', 3, 50, 1),
(2826, 2, 870, 7, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2827, 2, 870, 8, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2828, 1, 870, 3, 7, NULL, '2026-04-25 21:11:04', '2026-04-26 00:28:50', 3, 38, 1),
(2829, 3, 871, 6, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2830, 3, 871, 7, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2831, 2, 871, 6, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 36, 1),
(2832, 4, 872, 1, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2833, 1, 872, 4, 15, NULL, '2026-04-25 21:11:04', '2026-04-29 00:03:28', 3, 12, 1),
(2834, 4, 872, 2, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 12, 1),
(2835, 4, 873, 7, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2836, 4, 873, 8, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 38, 1),
(2837, 1, 873, 5, 9, NULL, '2026-04-25 21:11:04', '2026-05-03 22:47:08', 3, 38, 1),
(2838, 1, 874, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2839, 3, 874, 10, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2840, 3, 874, 9, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 71, 1),
(2841, 4, 875, 8, 10, NULL, '2026-04-25 21:11:04', '2026-04-27 21:22:26', 3, 107, 1),
(2842, 4, 875, 7, 2, NULL, '2026-04-25 21:11:04', '2026-04-27 21:23:16', 3, 107, 1),
(2843, 5, 875, 1, 13, NULL, '2026-04-25 21:11:04', '2026-04-27 21:25:01', 3, 107, 1),
(2844, 1, 876, 6, 8, NULL, '2026-04-25 21:11:04', '2026-05-03 21:52:54', 3, 59, 1),
(2845, 2, 876, 8, 5, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:37', 3, 59, 1),
(2846, 2, 876, 9, 11, NULL, '2026-04-25 21:11:04', '2026-04-27 21:14:39', 3, 59, 1),
(2847, 4, 877, 1, 4, NULL, '2026-04-25 21:11:04', '2026-05-05 00:27:16', 3, 58, 1),
(2848, 4, 877, 2, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 58, 1),
(2849, 5, 877, 3, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 58, 1),
(2850, 3, 878, 2, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:39', 3, 43, 1),
(2851, 3, 878, 3, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:39', 3, 43, 1),
(2852, 5, 878, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:39', 3, 43, 1),
(2853, 3, 879, 4, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 26, 1),
(2854, 3, 879, 5, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 26, 1),
(2855, 1, 879, 4, 11, NULL, '2026-04-25 21:11:04', '2026-04-27 22:47:32', 3, 26, 1),
(2856, 5, 880, 7, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 107, 1),
(2857, 5, 880, 8, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 107, 1),
(2858, 4, 880, 3, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 107, 1),
(2859, 3, 881, 4, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 14:01:09', 3, 42, 1),
(2860, 5, 881, 5, 16, NULL, '2026-04-25 21:11:04', '2026-04-28 00:12:36', 3, 42, 1),
(2861, 3, 881, 6, 15, NULL, '2026-04-25 21:11:04', '2026-04-28 00:11:00', 3, 42, 1),
(2862, 4, 882, 5, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 102, 1),
(2863, 4, 882, 6, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 102, 1),
(2864, 2, 882, 9, 7, NULL, '2026-04-25 21:11:04', '2026-05-06 08:24:19', 3, 102, 1),
(2865, 1, 883, 8, 11, NULL, '2026-04-25 21:11:04', '2026-05-06 08:11:50', 3, 50, 1),
(2866, 2, 883, 8, 2, NULL, '2026-04-25 21:11:04', '2026-05-06 08:23:28', 3, 50, 1),
(2867, 1, 883, 9, 11, NULL, '2026-04-25 21:11:04', '2026-05-06 08:12:23', 3, 50, 1),
(2868, 2, 884, 6, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 29, 1),
(2869, 2, 884, 7, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 29, 1),
(2870, 1, 884, 6, 3, NULL, '2026-04-25 21:11:04', '2026-04-27 16:30:12', 3, 29, 1),
(2871, 4, 885, 4, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 53, 1),
(2872, 4, 885, 5, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 53, 1),
(2873, 5, 885, 6, 1, NULL, '2026-04-25 21:11:04', '2026-04-26 06:58:25', 3, 53, 1),
(2874, 4, 886, 6, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 109, 1),
(2875, 2, 886, 11, 9, NULL, '2026-04-25 21:11:04', '2026-04-27 14:01:21', 3, 109, 1),
(2876, 2, 886, 10, 3, NULL, '2026-04-25 21:11:04', '2026-04-27 22:55:34', 3, 109, 1),
(2877, 4, 887, 1, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 26, 1),
(2878, 2, 888, 5, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:40', 3, 43, 1),
(2879, 2, 888, 6, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:40', 3, 43, 1),
(2880, 5, 888, 3, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:40', 3, 43, 1),
(2881, 4, 889, 8, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 103, 1),
(2882, 3, 890, 5, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2883, 4, 896, 3, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 59, 1),
(2884, 4, 896, 4, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 59, 1),
(2885, 7, 896, 4, 7, NULL, '2026-04-25 21:11:04', '2026-04-26 11:45:18', 3, 59, 1),
(2886, 7, 897, 5, 12, NULL, '2026-04-25 21:11:04', '2026-04-27 21:18:38', 3, 27, 1),
(2887, 7, 897, 6, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 21:34:36', 3, 27, 1),
(2888, 4, 897, 5, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 27, 1),
(2889, 4, 898, 7, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2890, 4, 898, 8, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2891, 1, 898, 1, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2892, 4, 899, 6, 7, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 27, 1),
(2893, 1, 900, 5, 13, NULL, '2026-04-25 21:11:04', '2026-04-30 22:05:16', 3, 26, 1),
(2894, 1, 901, 2, 7, NULL, '2026-04-25 21:11:04', '2026-04-30 22:05:41', 3, 53, 1),
(2895, 1, 907, 4, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 104, 1),
(2896, 1, 907, 5, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 104, 1),
(2897, 3, 907, 3, 4, NULL, '2026-04-25 21:11:04', '2026-05-10 05:47:35', 3, 104, 1),
(2898, 5, 908, 5, 9, NULL, '2026-04-25 21:11:04', '2026-04-29 05:41:40', 3, 23, 1),
(2899, 3, 909, 6, 4, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 47, 1),
(2900, 3, 910, 9, 4, NULL, '2026-04-25 21:11:04', '2026-05-10 05:46:55', 3, 104, 1),
(2901, 5, 919, 7, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2902, 5, 919, 8, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2903, 1, 919, 3, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2904, 1, 920, 7, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 29, 1),
(2905, 1, 920, 8, 9, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 29, 1),
(2906, 5, 920, 9, 9, NULL, '2026-04-25 21:11:04', '2026-04-26 10:21:51', 3, 29, 1),
(2907, 4, 925, 7, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2908, 4, 925, 8, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2909, 1, 925, 2, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2910, 2, 926, 6, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2911, 3, 926, 6, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2912, 2, 926, 7, 3, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 1, 1),
(2913, 5, 927, 1, 3, NULL, '2026-04-25 21:11:04', '2026-04-27 16:34:20', 3, 29, 1),
(2914, 1, 928, 7, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2915, 1, 928, 8, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2916, 3, 928, 8, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 55, 1),
(2917, 1, 929, 1, 13, NULL, '2026-04-25 21:11:04', '2026-04-27 14:01:25', 3, 68, 1),
(2918, 2, 930, 9, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 10:11:26', 3, 21, 1),
(2919, 1, 930, 9, 3, NULL, '2026-04-25 21:11:04', '2026-05-03 21:00:39', 3, 21, 1),
(2920, 2, 930, 8, 13, NULL, '2026-04-25 21:11:04', '2026-05-03 20:58:25', 3, 21, 1),
(2921, 2, 931, 1, 7, NULL, '2026-04-25 21:11:04', '2026-05-10 05:38:19', 3, 44, 1),
(2922, 2, 931, 2, 14, NULL, '2026-04-25 21:11:04', '2026-05-10 05:35:48', 3, 44, 1),
(2923, 4, 931, 1, 3, NULL, '2026-04-25 21:11:04', '2026-05-10 05:36:58', 3, 44, 1),
(2924, 1, 932, 3, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2925, 3, 932, 5, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2926, 1, 932, 2, 16, NULL, '2026-04-25 21:11:04', '2026-05-05 00:44:06', 3, 44, 1),
(2927, 4, 933, 3, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2928, 4, 933, 4, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2929, 2, 933, 5, 7, NULL, '2026-04-25 21:11:04', '2026-05-03 20:59:49', 3, 21, 1),
(2930, 2, 934, 4, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:49', 3, 41, 1),
(2931, 1, 934, 7, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 11:29:31', 3, 41, 1),
(2932, 1, 934, 8, 17, NULL, '2026-04-25 21:11:04', '2026-05-03 21:34:21', 3, 41, 1),
(2933, 3, 935, 3, 6, NULL, '2026-04-25 21:11:04', '2026-05-04 23:16:32', 3, 26, 1),
(2934, 2, 935, 5, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:14:54', 3, 26, 1),
(2935, 1, 935, 1, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 26, 1),
(2936, 1, 936, 2, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2937, 2, 936, 9, 8, NULL, '2026-04-25 21:11:04', '2026-04-26 10:08:36', 3, 44, 1),
(2938, 2, 936, 8, 8, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:00', 3, 44, 1),
(2939, 2, 937, 3, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2940, 2, 937, 4, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 21, 1),
(2941, 5, 937, 5, 11, NULL, '2026-04-25 21:11:04', '2026-05-04 23:18:19', 3, 21, 1),
(2942, 1, 938, 7, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2943, 1, 938, 8, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 44, 1),
(2944, 3, 938, 2, 1, NULL, '2026-04-25 21:11:04', '2026-05-04 23:15:58', 3, 44, 1),
(2945, 4, 939, 5, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2946, 4, 939, 6, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 106, 1),
(2947, 5, 939, 4, 14, NULL, '2026-04-25 21:11:04', '2026-05-04 23:17:10', 3, 106, 1),
(2948, 1, 940, 5, 15, NULL, '2026-04-25 21:11:04', '2026-04-27 15:33:54', 3, 24, 1),
(2949, 1, 940, 6, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:34:27', 3, 24, 1),
(2950, 3, 940, 1, 16, NULL, '2026-04-25 21:11:04', '2026-04-27 15:35:19', 3, 24, 1),
(2951, 5, 941, 5, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 30, 1),
(2952, 5, 949, 1, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2953, 5, 949, 2, 17, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2954, 4, 949, 1, 17, NULL, '2026-04-25 21:11:04', '2026-05-04 23:26:55', 3, 45, 1),
(2955, 2, 952, 7, 12, NULL, '2026-04-25 21:11:04', '2026-05-08 09:17:40', 3, 16, 1),
(2956, 3, 952, 4, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 16, 1),
(2957, 2, 952, 8, 14, NULL, '2026-04-25 21:11:04', '2026-05-08 09:18:12', 3, 16, 1),
(2958, 2, 953, 5, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2959, 2, 953, 6, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2960, 1, 953, 5, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 23, 1),
(2961, 1, 954, 2, 17, NULL, '2026-04-25 21:11:04', '2026-05-08 09:42:29', 3, 45, 1),
(2962, 4, 954, 2, 17, NULL, '2026-04-25 21:11:04', '2026-05-08 09:42:04', 3, 45, 1),
(2963, 4, 954, 3, 17, NULL, '2026-04-25 21:11:04', '2026-04-27 15:15:54', 3, 45, 1),
(2964, 1, 955, 6, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 6, 1),
(2965, 3, 961, 3, 18, NULL, '2026-04-25 21:11:04', '2026-04-26 01:02:04', 3, 45, 1),
(2966, 5, 961, 3, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2967, 3, 961, 2, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2968, 1, 964, 2, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 33, 1),
(2969, 1, 964, 3, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 33, 1),
(2970, 4, 964, 9, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 20:37:22', 3, 33, 1),
(2971, 3, 965, 3, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 31, 1),
(2972, 3, 965, 4, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 31, 1),
(2973, 4, 965, 6, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 31, 1),
(2974, 1, 966, 4, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 20:38:06', 3, 54, 1),
(2975, 1, 966, 5, 12, NULL, '2026-04-25 21:11:04', '2026-05-04 20:38:29', 3, 54, 1),
(2976, 3, 966, 1, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 00:55:18', 3, 54, 1),
(2977, 4, 967, 7, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2978, 4, 967, 8, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 54, 1),
(2979, 3, 967, 2, 4, NULL, '2026-04-25 21:11:04', '2026-04-26 00:54:46', 3, 54, 1),
(2980, 3, 968, 5, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 16, 1),
(2981, 5, 968, 7, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 16, 1),
(2982, 5, 968, 8, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 16, 1),
(2983, 1, 969, 1, 13, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2984, 5, 969, 2, 14, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2985, 5, 969, 3, 10, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 32, 1),
(2986, 1, 970, 6, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 19, 1),
(2987, 1, 970, 7, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 19, 1),
(2988, 2, 970, 2, 4, NULL, '2026-04-25 21:11:04', '2026-05-03 23:21:41', 3, 19, 1),
(2989, 1, 971, 8, 12, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 6, 1),
(2990, 4, 972, 5, 5, NULL, '2026-04-25 21:11:04', '2026-04-26 12:45:47', 3, 33, 1),
(2991, 2, 973, 5, 16, NULL, '2026-04-25 21:11:04', '2026-05-06 01:57:14', 3, 18, 1),
(2992, 2, 979, 5, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2993, 2, 979, 6, 18, NULL, '2026-04-25 21:11:04', '2026-04-25 21:15:06', 3, 45, 1),
(2994, 1, 979, 5, 18, NULL, '2026-04-25 21:11:04', '2026-04-26 02:54:02', 3, 45, 1),
(2995, 5, 986, 1, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 48, 1),
(2996, 5, 986, 2, 2, NULL, '2026-04-25 21:11:04', '2026-04-25 21:11:04', 3, 48, 1),
(2997, 4, 986, 9, 1, NULL, '2026-04-25 21:11:04', '2026-05-03 21:09:04', 3, 48, 1),
(2998, 4, 987, 2, 18, NULL, '2026-04-25 21:11:05', '2026-05-08 09:44:17', 3, 37, 1),
(2999, 3, 987, 1, 18, NULL, '2026-04-25 21:11:05', '2026-04-27 22:27:55', 3, 37, 1),
(3000, 4, 987, 3, 18, NULL, '2026-04-25 21:11:05', '2026-05-08 09:44:39', 3, 37, 1),
(3001, 4, 988, 6, 3, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 3, 1),
(3002, 4, 988, 7, 3, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 3, 1),
(3003, 4, 989, 8, 3, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 13, 1),
(3004, 2, 997, 5, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3005, 2, 997, 6, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3006, 4, 997, 5, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3007, 4, 998, 6, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3008, 4, 998, 7, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3009, 2, 998, 7, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3010, 3, 999, 1, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 16, 1),
(3011, 3, 999, 2, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 16, 1),
(3012, 2, 999, 8, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 16, 1),
(3013, 1, 1000, 3, 6, NULL, '2026-04-25 21:11:05', '2026-04-30 00:59:48', 3, 108, 1),
(3014, 1, 1000, 4, 6, NULL, '2026-04-25 21:11:05', '2026-04-30 01:00:06', 3, 108, 1),
(3015, 2, 1000, 4, 2, NULL, '2026-04-25 21:11:05', '2026-05-03 20:26:41', 3, 108, 1),
(3016, 5, 1001, 9, 5, NULL, '2026-04-25 21:11:05', '2026-04-30 00:58:53', 3, 5, 1),
(3017, 5, 1001, 10, 5, NULL, '2026-04-25 21:11:05', '2026-04-30 00:59:23', 3, 5, 1),
(3018, 1, 1001, 1, 4, NULL, '2026-04-25 21:11:05', '2026-05-05 00:20:27', 3, 5, 1),
(3019, 5, 1002, 7, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 19, 1),
(3020, 5, 1002, 8, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 19, 1),
(3021, 1, 1002, 5, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 19, 1),
(3022, 1, 1003, 6, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3023, 1, 1003, 7, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3024, 3, 1003, 1, 15, NULL, '2026-04-25 21:11:05', '2026-04-28 21:42:58', 3, 108, 1),
(3025, 3, 1004, 4, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3026, 3, 1004, 5, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3027, 1, 1004, 8, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3028, 3, 1005, 6, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 7, 1),
(3029, 3, 1005, 7, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 7, 1),
(3030, 3, 1006, 8, 6, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 108, 1),
(3031, 5, 1007, 8, 11, NULL, '2026-04-25 21:11:05', '2026-04-27 14:46:35', 3, 46, 1),
(3032, 4, 1007, 3, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 46, 1),
(3033, 5, 1007, 9, 8, NULL, '2026-04-25 21:11:05', '2026-04-27 14:48:11', 3, 46, 1),
(3034, 4, 1008, 5, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 21, 1),
(3035, 4, 1008, 6, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 21, 1),
(3036, 2, 1008, 5, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 21, 1),
(3037, 2, 1009, 3, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3038, 2, 1009, 4, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3039, 4, 1009, 4, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3040, 3, 1010, 6, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 4, 1),
(3041, 3, 1010, 7, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 4, 1),
(3042, 2, 1010, 6, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 4, 1),
(3043, 4, 1011, 7, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 28, 1),
(3044, 3, 1011, 5, 9, NULL, '2026-04-25 21:11:05', '2026-04-27 14:37:49', 3, 28, 1),
(3045, 3, 1011, 4, 10, NULL, '2026-04-25 21:11:05', '2026-04-27 14:37:05', 3, 28, 1),
(3046, 2, 1012, 7, 27, NULL, '2026-04-25 21:11:05', '2026-05-03 19:43:46', 3, 14, 1),
(3047, 1, 1012, 3, 27, NULL, '2026-04-25 21:11:05', '2026-04-28 00:33:28', 3, 14, 1),
(3048, 5, 1012, 4, 27, NULL, '2026-04-25 21:11:05', '2026-04-27 14:01:34', 3, 14, 1),
(3049, 3, 1013, 7, 3, NULL, '2026-04-25 21:11:05', '2026-05-03 11:59:39', 3, 24, 1),
(3050, 3, 1013, 8, 3, NULL, '2026-04-25 21:11:05', '2026-05-03 12:00:08', 3, 24, 1),
(3051, 1, 1013, 2, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3052, 1, 1014, 3, 17, NULL, '2026-04-25 21:11:05', '2026-04-25 21:15:07', 3, 25, 1),
(3053, 1, 1014, 4, 17, NULL, '2026-04-25 21:11:05', '2026-04-25 21:15:07', 3, 25, 1),
(3054, 2, 1014, 7, 17, NULL, '2026-04-25 21:11:05', '2026-05-03 19:30:53', 3, 25, 1),
(3055, 2, 1015, 4, 27, NULL, '2026-04-25 21:11:05', '2026-04-28 02:54:36', 3, 34, 1),
(3056, 5, 1016, 4, 3, NULL, '2026-04-25 21:11:05', '2026-04-27 14:34:35', 3, 25, 1),
(3057, 4, 1018, 2, 10, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3058, 4, 1018, 3, 10, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 24, 1),
(3059, 2, 1018, 7, 8, NULL, '2026-04-25 21:11:05', '2026-05-04 03:45:13', 3, 24, 1),
(3060, 4, 1019, 4, 27, NULL, '2026-04-25 21:11:05', '2026-04-27 14:01:34', 3, 28, 1),
(3061, 4, 1019, 5, 27, NULL, '2026-04-25 21:11:05', '2026-04-27 14:01:34', 3, 28, 1),
(3062, 3, 1019, 8, 27, NULL, '2026-04-25 21:11:05', '2026-04-28 01:18:07', 3, 28, 1),
(3063, 1, 1020, 1, 27, NULL, '2026-04-25 21:11:05', '2026-04-28 01:14:40', 3, 25, 1),
(3064, 1, 1020, 2, 27, NULL, '2026-04-25 21:11:05', '2026-04-28 01:15:53', 3, 25, 1),
(3065, 2, 1020, 2, 27, NULL, '2026-04-25 21:11:05', '2026-05-03 19:55:09', 3, 25, 1),
(3066, 7, 1030, 2, 11, NULL, '2026-04-25 21:11:05', '2026-05-06 01:23:50', 3, 28, 1),
(3067, 3, 1031, 5, 8, NULL, '2026-04-25 21:11:05', '2026-04-28 01:40:13', 3, 14, 1),
(3068, 4, 1032, 4, 18, NULL, '2026-04-25 21:11:05', '2026-04-28 22:24:17', 3, 108, 1),
(3069, 4, 1032, 5, 18, NULL, '2026-04-25 21:11:05', '2026-04-28 22:24:58', 3, 108, 1),
(3070, 3, 1032, 7, 18, NULL, '2026-04-25 21:11:05', '2026-05-03 20:09:59', 3, 108, 1),
(3071, 4, 1036, 5, 17, NULL, '2026-04-25 21:11:05', '2026-04-29 08:48:55', 3, 37, 1),
(3072, 3, 1036, 7, 17, NULL, '2026-04-25 21:11:05', '2026-04-25 21:15:07', 3, 37, 1),
(3073, 4, 1036, 4, 17, NULL, '2026-04-25 21:11:05', '2026-04-29 08:49:16', 3, 37, 1),
(3074, 1, 1047, 3, 18, NULL, '2026-04-25 21:11:05', '2026-05-05 23:20:35', 3, 37, 1),
(3075, 4, 1047, 6, 18, NULL, '2026-04-25 21:11:05', '2026-05-05 23:21:36', 3, 37, 1),
(3076, 4, 1047, 7, 18, NULL, '2026-04-25 21:11:05', '2026-05-05 23:21:56', 3, 37, 1),
(3077, 6, 1075, 1, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 26, 1),
(3078, 6, 1075, 2, 13, NULL, '2026-04-25 21:11:05', '2026-04-25 21:56:00', 3, 26, 1),
(3079, 1, 1075, 9, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 26, 1),
(3080, 7, 1076, 1, 15, NULL, '2026-04-25 21:11:05', '2026-05-04 04:11:38', 3, 27, 1),
(3081, 7, 1076, 2, 14, NULL, '2026-04-25 21:11:05', '2026-04-25 21:55:25', 3, 27, 1),
(3082, 2, 1076, 6, 15, NULL, '2026-04-25 21:11:05', '2026-04-27 22:54:22', 3, 27, 1),
(3083, 2, 1077, 9, 14, NULL, '2026-04-25 21:11:05', '2026-04-25 21:59:10', 3, 109, 1),
(3084, 2, 1077, 10, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 109, 1),
(3085, 6, 1077, 3, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 109, 1),
(3086, 3, 1078, 10, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 103, 1),
(3087, 3, 1078, 11, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 103, 1),
(3088, 1, 1078, 10, 14, NULL, '2026-04-25 21:11:05', '2026-04-25 21:54:51', 3, 103, 1),
(3089, 6, 1079, 4, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3090, 6, 1079, 5, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3091, 1, 1079, 12, 9, NULL, '2026-04-25 21:11:05', '2026-04-27 14:01:40', 3, 9, 1),
(3092, 7, 1080, 4, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 109, 1),
(3093, 7, 1080, 5, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 109, 1),
(3094, 1, 1080, 11, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 109, 1),
(3095, 7, 1081, 3, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 27, 1),
(3096, 7, 1081, 4, 11, NULL, '2026-04-25 21:11:05', '2026-04-25 21:51:02', 3, 27, 1),
(3097, 6, 1081, 1, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 27, 1),
(3098, 7, 1082, 1, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3099, 7, 1082, 2, 12, NULL, '2026-04-25 21:11:05', '2026-04-25 21:51:32', 3, 9, 1),
(3100, 5, 1082, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3101, 6, 1083, 2, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 1, 1),
(3102, 6, 1083, 3, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 1, 1),
(3103, 7, 1083, 8, 10, NULL, '2026-04-25 21:11:05', '2026-04-25 21:50:00', 3, 1, 1),
(3104, 5, 1084, 10, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 33, 1),
(3105, 5, 1084, 11, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 33, 1),
(3106, 7, 1084, 5, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 33, 1),
(3107, 7, 1085, 6, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 59, 1),
(3108, 7, 1085, 7, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 59, 1),
(3109, 1, 1085, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 59, 1),
(3110, 1, 1086, 10, 10, NULL, '2026-04-25 21:11:05', '2026-04-25 21:53:47', 3, 26, 1),
(3111, 1, 1086, 11, 10, NULL, '2026-04-25 21:11:05', '2026-04-25 21:49:03', 3, 26, 1),
(3112, 6, 1086, 4, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 26, 1),
(3113, 5, 1087, 9, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 13, 1),
(3114, 5, 1087, 10, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 13, 1),
(3115, 1, 1087, 10, 16, NULL, '2026-04-25 21:11:05', '2026-04-26 05:16:18', 3, 13, 1),
(3116, 7, 1088, 6, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3117, 7, 1088, 7, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 9, 1),
(3118, 3, 1088, 8, 12, NULL, '2026-04-25 21:11:05', '2026-04-27 21:35:54', 3, 9, 1),
(3119, 6, 1089, 7, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3120, 6, 1089, 8, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3121, 5, 1089, 11, 13, NULL, '2026-04-25 21:11:05', '2026-04-29 06:22:55', 3, 15, 1),
(3122, 7, 1090, 8, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3123, 7, 1090, 9, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3124, 1, 1090, 11, 12, NULL, '2026-04-25 21:11:05', '2026-04-30 02:25:50', 3, 5, 1),
(3125, 5, 1091, 4, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 44, 1),
(3126, 3, 1091, 7, 15, NULL, '2026-04-25 21:11:05', '2026-04-27 21:34:20', 3, 44, 1),
(3127, 5, 1091, 6, 16, NULL, '2026-04-25 21:11:05', '2026-04-27 21:35:16', 3, 44, 1),
(3128, 7, 1092, 4, 14, NULL, '2026-04-25 21:11:05', '2026-04-25 21:21:35', 3, 5, 1),
(3129, 7, 1092, 5, 14, NULL, '2026-04-25 21:11:05', '2026-04-25 21:22:03', 3, 5, 1),
(3130, 1, 1092, 9, 9, NULL, '2026-04-25 21:11:05', '2026-04-25 21:19:42', 3, 5, 1),
(3131, 2, 1093, 12, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:36:05', 3, 22, 1),
(3132, 6, 1094, 4, 10, NULL, '2026-04-25 21:11:05', '2026-04-26 11:31:52', 3, 36, 1),
(3133, 1, 1095, 12, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 1, 1),
(3134, 4, 1096, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-27 22:15:50', 3, 15, 1),
(3135, 7, 1097, 8, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1);
INSERT INTO `timetables` (`id`, `day_id`, `subject_id`, `timeslot_id`, `room_id`, `group_name`, `created_at`, `updated_at`, `semester_id`, `teacher_id`, `branch_id`) VALUES
(3136, 7, 1097, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3137, 2, 1097, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 15, 1),
(3138, 3, 1098, 7, 16, NULL, '2026-04-25 21:11:05', '2026-04-28 02:14:07', 3, 13, 1),
(3139, 2, 1098, 11, 10, NULL, '2026-04-25 21:11:05', '2026-04-28 02:16:02', 3, 13, 1),
(3140, 2, 1098, 10, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 13, 1),
(3141, 3, 1099, 9, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 5, 1),
(3142, 3, 1099, 10, 13, NULL, '2026-04-25 21:11:05', '2026-05-04 22:47:52', 3, 5, 1),
(3143, 7, 1099, 6, 12, NULL, '2026-04-25 21:11:05', '2026-04-25 21:45:00', 3, 5, 1),
(3144, 6, 1100, 5, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 11, 1),
(3145, 6, 1100, 6, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 11, 1),
(3146, 2, 1100, 7, 14, NULL, '2026-04-25 21:11:05', '2026-04-28 02:17:17', 3, 11, 1),
(3150, 4, 1102, 9, 15, NULL, '2026-04-25 21:11:05', '2026-04-26 05:14:10', 3, 6, 1),
(3151, 3, 1103, 12, 15, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 31, 1),
(3152, 5, 1104, 7, 15, NULL, '2026-04-25 21:11:05', '2026-04-27 15:47:28', 3, 106, 1),
(3153, 2, 1105, 8, 10, NULL, '2026-04-25 21:11:05', '2026-04-27 14:04:11', 3, 10, 1),
(3154, 6, 1113, 1, 12, NULL, '2026-04-25 21:11:05', '2026-05-04 22:24:49', 3, 31, 1),
(3155, 6, 1113, 2, 10, NULL, '2026-04-25 21:11:05', '2026-05-04 22:25:22', 3, 31, 1),
(3156, 4, 1113, 10, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 31, 1),
(3157, 6, 1114, 8, 11, NULL, '2026-04-25 21:11:05', '2026-05-04 22:26:19', 3, 6, 1),
(3158, 6, 1114, 7, 11, NULL, '2026-04-25 21:11:05', '2026-05-04 22:25:54', 3, 6, 1),
(3159, 3, 1114, 10, 6, NULL, '2026-04-25 21:11:05', '2026-04-30 00:35:10', 3, 6, 1),
(3160, 3, 1115, 9, 5, NULL, '2026-04-25 21:11:05', '2026-05-04 22:01:10', 3, 11, 1),
(3161, 3, 1115, 8, 16, NULL, '2026-04-25 21:11:05', '2026-05-04 22:00:11', 3, 11, 1),
(3162, 4, 1115, 9, 3, NULL, '2026-04-25 21:11:05', '2026-05-04 22:03:21', 3, 11, 1),
(3163, 2, 1116, 11, 13, NULL, '2026-04-25 21:11:05', '2026-05-04 21:55:47', 3, 19, 1),
(3164, 2, 1116, 12, 16, NULL, '2026-04-25 21:11:05', '2026-04-25 21:11:05', 3, 19, 1),
(3165, 2, 887, 3, 13, NULL, '2026-04-25 21:11:23', '2026-04-26 07:00:06', 3, NULL, 1),
(3166, 2, 887, 4, 13, NULL, '2026-04-25 21:11:23', '2026-04-26 06:59:35', 3, NULL, 1),
(3167, 3, 889, 4, 2, NULL, '2026-04-25 21:11:23', '2026-04-26 07:02:24', 3, NULL, 1),
(3168, 3, 889, 3, 2, NULL, '2026-04-25 21:11:23', '2026-04-26 07:02:48', 3, NULL, 1),
(3169, 5, 890, 2, 12, NULL, '2026-04-25 21:11:23', '2026-04-26 07:00:54', 3, NULL, 1),
(3170, 5, 890, 1, 12, NULL, '2026-04-25 21:11:23', '2026-04-26 07:01:26', 3, NULL, 1),
(3171, 5, 891, 7, 14, NULL, '2026-04-25 21:11:23', '2026-05-03 22:43:18', 3, NULL, 1),
(3172, 7, 891, 7, 1, NULL, '2026-04-25 21:11:23', '2026-04-25 21:11:23', 3, NULL, 1),
(3173, 7, 891, 6, 13, NULL, '2026-04-25 21:11:25', '2026-04-27 23:26:07', 3, NULL, 1),
(3174, 7, 892, 1, 1, NULL, '2026-04-25 21:11:25', '2026-05-06 09:26:13', 3, NULL, 1),
(3175, 7, 892, 2, 1, NULL, '2026-04-25 21:11:25', '2026-04-25 21:11:25', 3, NULL, 1),
(3176, 5, 892, 9, 1, NULL, '2026-04-25 21:11:27', '2026-04-25 21:11:27', 3, NULL, 1),
(3177, 6, 893, 1, 13, NULL, '2026-04-25 21:11:27', '2026-04-26 10:26:06', 3, NULL, 1),
(3178, 6, 893, 2, 5, NULL, '2026-04-25 21:11:27', '2026-04-26 10:27:10', 3, NULL, 1),
(3179, 5, 893, 5, 1, NULL, '2026-04-25 21:11:28', '2026-04-25 21:11:28', 3, NULL, 1),
(3180, 6, 894, 7, 1, NULL, '2026-04-25 21:11:28', '2026-04-27 22:52:06', 3, NULL, 1),
(3181, 7, 894, 3, 1, NULL, '2026-04-25 21:11:28', '2026-04-25 21:11:28', 3, NULL, 1),
(3182, 5, 894, 6, 8, NULL, '2026-04-25 21:11:30', '2026-04-25 21:11:30', 3, NULL, 1),
(3183, 6, 895, 9, 1, NULL, '2026-04-25 21:11:30', '2026-04-25 21:11:30', 3, NULL, 1),
(3184, 7, 895, 5, 10, NULL, '2026-04-25 21:11:30', '2026-05-04 04:12:55', 3, NULL, 1),
(3185, 6, 895, 8, 1, NULL, '2026-04-25 21:11:32', '2026-04-27 22:52:44', 3, NULL, 1),
(3186, 1, 899, 6, 13, NULL, '2026-04-25 21:11:32', '2026-05-04 21:33:40', 3, NULL, 1),
(3187, 1, 899, 7, 11, NULL, '2026-04-25 21:11:32', '2026-04-30 22:03:00', 3, NULL, 1),
(3188, 4, 900, 9, 7, NULL, '2026-04-25 21:11:32', '2026-04-26 11:51:54', 3, NULL, 1),
(3189, 4, 900, 10, 14, NULL, '2026-04-25 21:11:32', '2026-04-27 22:49:35', 3, NULL, 1),
(3190, 7, 901, 2, 13, NULL, '2026-04-25 21:11:32', '2026-04-26 10:29:54', 3, NULL, 1),
(3191, 7, 901, 1, 2, NULL, '2026-04-25 21:11:32', '2026-04-25 21:11:32', 3, NULL, 1),
(3192, 6, 902, 4, 2, NULL, '2026-04-25 21:11:32', '2026-04-28 09:57:59', 3, NULL, 1),
(3193, 6, 902, 5, 10, NULL, '2026-04-25 21:11:32', '2026-04-28 09:58:55', 3, NULL, 1),
(3194, 5, 902, 3, 5, NULL, '2026-04-25 21:11:33', '2026-04-25 21:11:33', 3, NULL, 1),
(3195, 4, 903, 1, 8, NULL, '2026-04-25 21:11:33', '2026-05-04 20:59:34', 3, NULL, 1),
(3196, 4, 903, 2, 8, NULL, '2026-04-25 21:11:33', '2026-05-04 20:59:58', 3, NULL, 1),
(3197, 6, 903, 3, 8, NULL, '2026-04-25 21:11:35', '2026-05-04 23:46:10', 3, NULL, 1),
(3198, 7, 904, 2, 2, NULL, '2026-04-25 21:11:35', '2026-04-28 09:57:31', 3, NULL, 1),
(3199, 7, 904, 1, 4, NULL, '2026-04-25 21:11:35', '2026-04-26 10:32:42', 3, NULL, 1),
(3200, 5, 904, 4, 12, NULL, '2026-04-25 21:11:36', '2026-05-03 23:54:55', 3, NULL, 1),
(3201, 7, 905, 4, 13, NULL, '2026-04-25 21:11:36', '2026-04-28 09:56:41', 3, NULL, 1),
(3202, 7, 905, 3, 2, NULL, '2026-04-25 21:11:36', '2026-04-25 21:11:36', 3, NULL, 1),
(3203, 5, 905, 5, 12, NULL, '2026-04-25 21:11:38', '2026-05-03 22:41:32', 3, NULL, 1),
(3207, 5, 908, 6, 9, NULL, '2026-04-25 21:11:39', '2026-04-29 05:42:18', 3, NULL, 1),
(3208, 3, 908, 7, 10, NULL, '2026-04-25 21:11:39', '2026-04-27 23:54:49', 3, NULL, 1),
(3209, 5, 909, 3, 8, NULL, '2026-04-25 21:11:39', '2026-04-28 09:32:48', 3, NULL, 1),
(3210, 5, 909, 4, 9, NULL, '2026-04-25 21:11:40', '2026-04-28 09:33:25', 3, NULL, 1),
(3211, 3, 910, 8, 14, NULL, '2026-04-25 21:11:40', '2026-05-10 05:45:51', 3, NULL, 1),
(3212, 1, 910, 1, 15, NULL, '2026-04-25 21:11:40', '2026-05-10 05:48:49', 3, NULL, 1),
(3213, 2, 911, 3, 18, NULL, '2026-04-25 21:11:40', '2026-05-03 21:50:14', 3, NULL, 1),
(3214, 2, 911, 4, 18, NULL, '2026-04-25 21:11:40', '2026-05-03 21:51:00', 3, NULL, 1),
(3215, 5, 911, 8, 18, NULL, '2026-04-25 21:11:41', '2026-05-03 21:52:08', 3, NULL, 1),
(3219, 3, 913, 4, 16, NULL, '2026-04-25 21:11:43', '2026-04-27 14:04:19', 3, NULL, 1),
(3220, 3, 913, 6, 15, NULL, '2026-04-25 21:11:43', '2026-04-28 00:11:00', 3, NULL, 1),
(3221, 5, 913, 5, 16, NULL, '2026-04-25 21:11:45', '2026-04-28 00:12:36', 3, NULL, 1),
(3222, 6, 914, 1, 1, NULL, '2026-04-25 21:11:45', '2026-04-25 21:11:45', 3, NULL, 1),
(3223, 6, 914, 2, 1, NULL, '2026-04-25 21:11:45', '2026-05-05 00:22:46', 3, NULL, 1),
(3224, 5, 914, 6, 12, NULL, '2026-04-25 21:11:47', '2026-05-08 09:53:10', 3, NULL, 1),
(3225, 7, 915, 2, 3, NULL, '2026-04-25 21:11:47', '2026-04-28 21:47:27', 3, NULL, 1),
(3226, 7, 915, 3, 3, NULL, '2026-04-25 21:11:47', '2026-04-25 21:11:47', 3, NULL, 1),
(3227, 4, 915, 3, 6, NULL, '2026-04-25 21:11:48', '2026-05-18 03:35:25', 3, NULL, 1),
(3228, 3, 916, 2, 13, NULL, '2026-04-25 21:11:49', '2026-05-03 21:43:34', 3, NULL, 1),
(3229, 3, 916, 1, 14, NULL, '2026-04-25 21:11:49', '2026-05-03 21:42:22', 3, NULL, 1),
(3230, 4, 916, 6, 5, NULL, '2026-04-25 21:11:50', '2026-05-08 09:53:49', 3, NULL, 1),
(3231, 6, 917, 3, 3, NULL, '2026-04-25 21:11:50', '2026-04-25 21:11:50', 3, NULL, 1),
(3232, 5, 917, 3, 11, NULL, '2026-04-25 21:11:50', '2026-04-27 16:40:05', 3, NULL, 1),
(3233, 5, 917, 2, 3, NULL, '2026-04-25 21:11:52', '2026-04-26 05:45:18', 3, NULL, 1),
(3234, 6, 921, 8, 3, NULL, '2026-04-25 21:11:52', '2026-04-25 21:11:52', 3, NULL, 1),
(3235, 6, 921, 7, 12, NULL, '2026-04-25 21:11:52', '2026-04-26 10:19:26', 3, NULL, 1),
(3236, 3, 921, 5, 7, NULL, '2026-04-25 21:11:53', '2026-05-03 22:33:05', 3, NULL, 1),
(3237, 6, 922, 5, 4, NULL, '2026-04-25 21:11:53', '2026-04-25 21:11:53', 3, NULL, 1),
(3238, 1, 922, 9, 1, NULL, '2026-04-25 21:11:53', '2026-04-26 05:22:23', 3, NULL, 1),
(3239, 6, 922, 4, 6, NULL, '2026-04-25 21:11:55', '2026-04-26 10:21:03', 3, NULL, 1),
(3240, 3, 923, 2, 8, NULL, '2026-04-25 21:11:55', '2026-05-04 21:39:36', 3, NULL, 1),
(3241, 3, 923, 1, 10, NULL, '2026-04-25 21:11:55', '2026-05-04 21:39:08', 3, NULL, 1),
(3242, 5, 923, 6, 15, NULL, '2026-04-25 21:11:56', '2026-04-25 21:11:56', 3, NULL, 1),
(3243, 1, 924, 10, 9, NULL, '2026-04-25 21:11:57', '2026-05-03 12:15:18', 3, NULL, 1),
(3244, 3, 924, 4, 9, NULL, '2026-04-25 21:11:57', '2026-05-03 12:13:34', 3, NULL, 1),
(3245, 3, 924, 3, 14, NULL, '2026-04-25 21:11:58', '2026-05-03 12:12:52', 3, NULL, 1),
(3246, 4, 927, 3, 16, NULL, '2026-04-25 21:11:58', '2026-05-04 21:13:45', 3, NULL, 1),
(3247, 4, 927, 4, 6, NULL, '2026-04-25 21:11:58', '2026-05-04 21:14:18', 3, NULL, 1),
(3248, 5, 929, 3, 10, NULL, '2026-04-25 21:11:58', '2026-04-27 16:34:56', 3, NULL, 1),
(3249, 5, 929, 2, 14, NULL, '2026-04-25 21:11:58', '2026-04-27 16:35:24', 3, NULL, 1),
(3250, 6, 941, 3, 4, NULL, '2026-04-25 21:11:58', '2026-04-25 21:11:58', 3, NULL, 1),
(3251, 6, 941, 4, 3, NULL, '2026-04-25 21:11:58', '2026-04-26 01:43:55', 3, NULL, 1),
(3252, 5, 942, 7, 7, NULL, '2026-04-25 21:11:58', '2026-05-04 20:34:33', 3, NULL, 1),
(3253, 5, 942, 6, 14, NULL, '2026-04-25 21:11:58', '2026-04-30 02:11:30', 3, NULL, 1),
(3254, 3, 942, 1, 3, NULL, '2026-04-25 21:12:00', '2026-04-30 02:10:21', 3, NULL, 1),
(3255, 2, 943, 1, 1, NULL, '2026-04-25 21:12:00', '2026-04-30 02:19:26', 3, NULL, 1),
(3256, 2, 943, 2, 1, NULL, '2026-04-25 21:12:00', '2026-04-26 01:51:40', 3, NULL, 1),
(3257, 1, 943, 4, 1, NULL, '2026-04-25 21:12:01', '2026-04-30 02:15:20', 3, NULL, 1),
(3258, 1, 944, 1, 1, NULL, '2026-04-25 21:12:01', '2026-04-25 21:12:01', 3, NULL, 1),
(3259, 5, 944, 4, 1, NULL, '2026-04-25 21:12:01', '2026-04-26 01:48:50', 3, NULL, 1),
(3260, 5, 944, 3, 2, NULL, '2026-04-25 21:12:03', '2026-04-25 21:12:03', 3, NULL, 1),
(3261, 6, 945, 1, 3, NULL, '2026-04-25 21:12:03', '2026-04-25 21:12:03', 3, NULL, 1),
(3262, 3, 945, 3, 8, NULL, '2026-04-25 21:12:03', '2026-04-27 15:55:36', 3, NULL, 1),
(3263, 6, 945, 2, 2, NULL, '2026-04-25 21:12:04', '2026-04-26 01:43:13', 3, NULL, 1),
(3264, 6, 946, 6, 3, NULL, '2026-04-25 21:12:04', '2026-04-25 21:12:04', 3, NULL, 1),
(3265, 6, 946, 5, 5, NULL, '2026-04-25 21:12:04', '2026-04-26 01:44:34', 3, NULL, 1),
(3266, 3, 946, 4, 1, NULL, '2026-04-25 21:12:05', '2026-04-26 01:47:26', 3, NULL, 1),
(3267, 3, 947, 4, 7, NULL, '2026-04-25 21:12:05', '2026-05-04 23:25:15', 3, NULL, 1),
(3268, 2, 947, 2, 8, NULL, '2026-04-25 21:12:05', '2026-04-27 15:52:58', 3, NULL, 1),
(3269, 3, 947, 5, 11, NULL, '2026-04-25 21:12:07', '2026-05-04 23:26:03', 3, NULL, 1),
(3270, 1, 948, 4, 16, NULL, '2026-04-25 21:12:07', '2026-05-16 02:51:31', 3, NULL, 1),
(3271, 1, 948, 3, 10, NULL, '2026-04-25 21:12:07', '2026-04-26 01:39:33', 3, NULL, 1),
(3272, 3, 948, 3, 3, NULL, '2026-04-25 21:12:08', '2026-05-03 21:40:18', 3, NULL, 1),
(3273, 1, 950, 6, 5, NULL, '2026-04-25 21:12:08', '2026-04-26 01:29:47', 3, NULL, 1),
(3274, 1, 950, 5, 3, NULL, '2026-04-25 21:12:09', '2026-04-27 15:51:07', 3, NULL, 1),
(3275, 3, 950, 6, 16, NULL, '2026-04-25 21:12:10', '2026-04-26 01:25:11', 3, NULL, 1),
(3276, 3, 951, 7, 14, NULL, '2026-04-25 21:12:10', '2026-04-26 01:23:13', 3, NULL, 1),
(3277, 4, 951, 3, 8, NULL, '2026-04-25 21:12:10', '2026-04-27 15:54:00', 3, NULL, 1),
(3278, 3, 951, 8, 5, NULL, '2026-04-25 21:12:11', '2026-04-25 21:12:11', 3, NULL, 1),
(3279, 3, 955, 5, 10, NULL, '2026-04-25 21:12:11', '2026-04-26 01:18:04', 3, NULL, 1),
(3280, 3, 955, 6, 14, NULL, '2026-04-25 21:12:11', '2026-04-26 01:18:46', 3, NULL, 1),
(3281, 4, 956, 5, 10, NULL, '2026-04-25 21:12:11', '2026-04-27 15:15:21', 3, NULL, 1),
(3282, 4, 956, 4, 10, NULL, '2026-04-25 21:12:11', '2026-04-27 15:14:21', 3, NULL, 1),
(3283, 1, 956, 1, 3, NULL, '2026-04-25 21:12:13', '2026-04-26 01:14:38', 3, NULL, 1),
(3284, 5, 957, 9, 7, NULL, '2026-04-25 21:12:13', '2026-04-26 12:40:30', 3, NULL, 1),
(3285, 2, 957, 4, 7, NULL, '2026-04-25 21:12:13', '2026-04-30 02:35:30', 3, NULL, 1),
(3286, 5, 957, 10, 4, NULL, '2026-04-25 21:12:14', '2026-04-25 21:12:14', 3, NULL, 1),
(3287, 4, 958, 6, 8, NULL, '2026-04-25 21:12:14', '2026-04-26 01:06:29', 3, NULL, 1),
(3288, 1, 958, 10, 2, NULL, '2026-04-25 21:12:14', '2026-04-27 15:21:41', 3, NULL, 1),
(3289, 1, 958, 9, 2, NULL, '2026-04-25 21:12:15', '2026-04-26 00:59:07', 3, NULL, 1),
(3290, 1, 959, 8, 4, NULL, '2026-04-25 21:12:15', '2026-04-26 00:58:33', 3, NULL, 1),
(3291, 4, 959, 10, 2, NULL, '2026-04-25 21:12:15', '2026-04-26 01:08:07', 3, NULL, 1),
(3292, 4, 959, 9, 8, NULL, '2026-04-25 21:12:17', '2026-04-26 12:38:32', 3, NULL, 1),
(3293, 4, 960, 4, 16, NULL, '2026-04-25 21:12:17', '2026-04-26 01:07:08', 3, NULL, 1),
(3294, 1, 960, 4, 5, NULL, '2026-04-25 21:12:17', '2026-04-26 12:37:27', 3, NULL, 1),
(3295, 3, 960, 1, 1, NULL, '2026-04-25 21:12:18', '2026-04-26 01:02:25', 3, NULL, 1),
(3296, 4, 962, 2, 7, NULL, '2026-04-25 21:12:18', '2026-04-26 01:03:14', 3, NULL, 1),
(3297, 5, 962, 8, 1, NULL, '2026-04-25 21:12:18', '2026-05-18 03:52:43', 3, NULL, 1),
(3298, 4, 962, 1, 6, NULL, '2026-04-25 21:12:19', '2026-05-03 11:37:46', 3, NULL, 1),
(3299, 5, 963, 2, 13, NULL, '2026-04-25 21:12:19', '2026-04-26 01:00:51', 3, NULL, 1),
(3300, 3, 963, 6, 12, NULL, '2026-04-25 21:12:19', '2026-04-27 15:21:08', 3, NULL, 1),
(3301, 5, 963, 1, 16, NULL, '2026-04-25 21:12:21', '2026-04-25 21:12:21', 3, NULL, 1),
(3302, 2, 971, 6, 5, NULL, '2026-04-25 21:12:21', '2026-04-26 00:49:04', 3, NULL, 1),
(3303, 2, 971, 7, 11, NULL, '2026-04-25 21:12:21', '2026-04-26 00:49:40', 3, NULL, 1),
(3304, 4, 972, 4, 15, NULL, '2026-04-25 21:12:21', '2026-04-26 12:46:23', 3, NULL, 1),
(3305, 2, 972, 3, 4, NULL, '2026-04-25 21:12:21', '2026-04-26 00:47:37', 3, NULL, 1),
(3306, 5, 973, 1, 4, NULL, '2026-04-25 21:12:21', '2026-05-06 01:59:23', 3, NULL, 1),
(3307, 5, 973, 2, 16, NULL, '2026-04-25 21:12:21', '2026-05-06 01:59:51', 3, NULL, 1),
(3308, 2, 974, 1, 4, NULL, '2026-04-25 21:12:21', '2026-05-06 01:56:22', 3, NULL, 1),
(3309, 4, 974, 2, 15, NULL, '2026-04-25 21:12:21', '2026-05-06 01:53:01', 3, NULL, 1),
(3310, 4, 974, 1, 13, NULL, '2026-04-25 21:12:22', '2026-04-30 02:32:41', 3, NULL, 1),
(3311, 2, 975, 2, 7, NULL, '2026-04-25 21:12:22', '2026-04-26 02:36:12', 3, NULL, 1),
(3312, 2, 975, 9, 5, NULL, '2026-04-25 21:12:22', '2026-04-26 02:46:16', 3, NULL, 1),
(3313, 1, 975, 6, 10, NULL, '2026-04-25 21:12:24', '2026-04-27 15:29:54', 3, NULL, 1),
(3314, 5, 976, 7, 5, NULL, '2026-04-25 21:12:24', '2026-05-03 20:29:43', 3, NULL, 1),
(3315, 1, 976, 7, 5, NULL, '2026-04-25 21:12:24', '2026-05-04 23:37:24', 3, NULL, 1),
(3316, 5, 976, 6, 6, NULL, '2026-04-25 21:12:25', '2026-05-03 20:29:11', 3, NULL, 1),
(3317, 4, 977, 5, 16, NULL, '2026-04-25 21:12:25', '2026-05-03 23:08:17', 3, NULL, 1),
(3318, 4, 977, 4, 3, NULL, '2026-04-25 21:12:25', '2026-05-03 23:07:46', 3, NULL, 1),
(3319, 2, 977, 1, 8, NULL, '2026-04-25 21:12:26', '2026-04-27 15:30:43', 3, NULL, 1),
(3320, 3, 978, 4, 16, NULL, '2026-04-25 21:12:26', '2026-04-26 02:47:42', 3, NULL, 1),
(3321, 3, 978, 6, 15, NULL, '2026-04-25 21:12:26', '2026-04-28 00:11:00', 3, NULL, 1),
(3322, 5, 978, 5, 16, NULL, '2026-04-25 21:12:28', '2026-04-28 00:12:36', 3, NULL, 1),
(3323, 1, 980, 2, 3, NULL, '2026-04-25 21:12:28', '2026-04-25 21:12:28', 3, NULL, 1),
(3324, 3, 980, 8, 9, NULL, '2026-04-25 21:12:28', '2026-05-04 21:51:12', 3, NULL, 1),
(3325, 3, 980, 9, 11, NULL, '2026-04-25 21:12:29', '2026-04-26 12:31:21', 3, NULL, 1),
(3326, 1, 981, 5, 10, NULL, '2026-04-25 21:12:29', '2026-04-27 15:30:17', 3, NULL, 1),
(3327, 4, 981, 10, 3, NULL, '2026-04-25 21:12:29', '2026-04-26 12:27:58', 3, NULL, 1),
(3328, 4, 981, 9, 4, NULL, '2026-04-25 21:12:30', '2026-04-26 12:27:30', 3, NULL, 1),
(3329, 2, 982, 3, 7, NULL, '2026-04-25 21:12:30', '2026-04-25 21:12:30', 3, NULL, 1),
(3330, 3, 982, 7, 8, NULL, '2026-04-25 21:12:30', '2026-04-26 02:28:41', 3, NULL, 1),
(3331, 3, 982, 8, 8, NULL, '2026-04-25 21:12:32', '2026-04-25 21:12:32', 3, NULL, 1),
(3332, 1, 983, 8, 5, NULL, '2026-04-25 21:12:32', '2026-04-25 21:12:32', 3, NULL, 1),
(3333, 1, 983, 9, 14, NULL, '2026-04-25 21:12:32', '2026-05-05 00:03:47', 3, NULL, 1),
(3334, 3, 983, 5, 16, NULL, '2026-04-25 21:12:33', '2026-04-26 02:31:10', 3, NULL, 1),
(3335, 3, 984, 2, 15, NULL, '2026-04-25 21:12:33', '2026-04-26 12:27:00', 3, NULL, 1),
(3336, 4, 984, 7, 8, NULL, '2026-04-25 21:12:33', '2026-05-04 23:21:08', 3, NULL, 1),
(3337, 1, 984, 2, 4, NULL, '2026-04-25 21:12:34', '2026-05-05 00:04:20', 3, NULL, 1),
(3338, 2, 985, 2, 6, NULL, '2026-04-25 21:12:34', '2026-05-03 20:32:21', 3, NULL, 1),
(3339, 2, 985, 1, 6, NULL, '2026-04-25 21:12:34', '2026-05-03 20:31:55', 3, NULL, 1),
(3340, 1, 985, 7, 3, NULL, '2026-04-25 21:12:36', '2026-04-26 02:25:28', 3, NULL, 1),
(3341, 2, 988, 1, 2, NULL, '2026-04-25 21:12:36', '2026-05-04 21:37:25', 3, NULL, 1),
(3342, 3, 989, 10, 7, NULL, '2026-04-25 21:12:36', '2026-04-26 03:19:41', 3, NULL, 1),
(3343, 2, 989, 7, 2, NULL, '2026-04-25 21:12:36', '2026-04-28 02:35:48', 3, NULL, 1),
(3344, 6, 990, 5, 7, NULL, '2026-04-25 21:12:36', '2026-05-08 09:36:59', 3, NULL, 1),
(3345, 6, 990, 4, 7, NULL, '2026-04-25 21:12:36', '2026-05-08 09:36:30', 3, NULL, 1),
(3346, 2, 990, 4, 15, NULL, '2026-04-25 21:12:38', '2026-04-25 21:12:38', 3, NULL, 1),
(3347, 6, 991, 8, 14, NULL, '2026-04-25 21:12:38', '2026-04-27 22:27:01', 3, NULL, 1),
(3348, 6, 991, 7, 5, NULL, '2026-04-25 21:12:38', '2026-04-26 03:18:05', 3, NULL, 1),
(3349, 3, 991, 9, 2, NULL, '2026-04-25 21:12:40', '2026-04-26 03:13:47', 3, NULL, 1),
(3350, 2, 992, 3, 15, NULL, '2026-04-25 21:12:40', '2026-05-04 21:42:28', 3, NULL, 1),
(3351, 5, 992, 4, 16, NULL, '2026-04-25 21:12:40', '2026-04-26 03:12:12', 3, NULL, 1),
(3352, 5, 992, 3, 12, NULL, '2026-04-25 21:12:41', '2026-05-04 21:32:02', 3, NULL, 1),
(3353, 4, 993, 5, 8, NULL, '2026-04-25 21:12:41', '2026-04-26 03:09:03', 3, NULL, 1),
(3354, 5, 993, 8, 15, NULL, '2026-04-25 21:12:41', '2026-04-26 03:02:25', 3, NULL, 1),
(3355, 5, 993, 7, 8, NULL, '2026-04-25 21:12:43', '2026-04-25 21:12:43', 3, NULL, 1),
(3356, 5, 994, 5, 15, NULL, '2026-04-25 21:12:43', '2026-04-26 02:58:49', 3, NULL, 1),
(3357, 1, 994, 2, 10, NULL, '2026-04-25 21:12:43', '2026-04-27 16:02:13', 3, NULL, 1),
(3358, 1, 994, 3, 11, NULL, '2026-04-25 21:12:45', '2026-04-26 03:00:14', 3, NULL, 1),
(3359, 4, 995, 2, 16, NULL, '2026-04-25 21:12:45', '2026-04-26 03:06:39', 3, NULL, 1),
(3360, 5, 995, 10, 11, NULL, '2026-04-25 21:12:46', '2026-05-04 03:43:35', 3, NULL, 1),
(3361, 4, 995, 1, 11, NULL, '2026-04-25 21:12:47', '2026-05-04 03:44:05', 3, NULL, 1),
(3362, 1, 996, 5, 5, NULL, '2026-04-25 21:12:47', '2026-04-26 03:10:45', 3, NULL, 1),
(3363, 2, 996, 5, 3, NULL, '2026-04-25 21:12:47', '2026-05-04 03:41:15', 3, NULL, 1),
(3364, 2, 996, 4, 16, NULL, '2026-04-25 21:12:48', '2026-05-04 03:40:22', 3, NULL, 1),
(3365, 4, 1005, 2, 14, NULL, '2026-04-25 21:12:48', '2026-04-26 03:24:47', 3, NULL, 1),
(3366, 1, 1006, 9, 10, NULL, '2026-04-25 21:12:48', '2026-04-26 03:20:56', 3, NULL, 1),
(3367, 1, 1006, 10, 8, NULL, '2026-04-25 21:12:48', '2026-04-26 03:21:35', 3, NULL, 1),
(3368, 4, 1015, 6, 27, NULL, '2026-04-25 21:12:48', '2026-04-27 14:24:00', 3, NULL, 1),
(3369, 2, 1015, 3, 27, NULL, '2026-04-25 21:12:48', '2026-04-28 02:54:56', 3, NULL, 1),
(3370, 5, 1016, 3, 3, NULL, '2026-04-25 21:12:48', '2026-04-27 14:34:06', 3, NULL, 1),
(3371, 1, 1016, 6, 7, NULL, '2026-04-25 21:12:49', '2026-05-03 19:45:11', 3, NULL, 1),
(3372, 6, 1017, 7, 27, NULL, '2026-04-25 21:12:49', '2026-05-03 19:57:25', 3, NULL, 1),
(3373, 6, 1017, 8, 27, NULL, '2026-04-25 21:12:49', '2026-05-03 19:57:51', 3, NULL, 1),
(3374, 5, 1017, 2, 27, NULL, '2026-04-25 21:12:50', '2026-04-27 14:22:31', 3, NULL, 1),
(3375, 7, 1021, 3, 11, NULL, '2026-04-25 21:12:50', '2026-05-03 20:03:50', 3, NULL, 1),
(3376, 3, 1021, 9, 8, NULL, '2026-04-25 21:12:50', '2026-04-26 03:33:35', 3, NULL, 1),
(3377, 7, 1021, 4, 10, NULL, '2026-04-25 21:12:51', '2026-05-03 20:04:33', 3, NULL, 1),
(3378, 7, 1022, 7, 9, NULL, '2026-04-25 21:12:51', '2026-04-28 02:57:27', 3, NULL, 1),
(3379, 7, 1022, 6, 8, NULL, '2026-04-25 21:12:51', '2026-04-28 02:56:56', 3, NULL, 1),
(3380, 4, 1022, 1, 15, NULL, '2026-04-25 21:12:52', '2026-05-03 19:37:07', 3, NULL, 1),
(3381, 4, 1023, 6, 10, NULL, '2026-04-25 21:12:52', '2026-05-03 20:06:32', 3, NULL, 1),
(3382, 2, 1023, 5, 9, NULL, '2026-04-25 21:12:52', '2026-05-03 23:23:16', 3, NULL, 1),
(3383, 4, 1023, 7, 10, NULL, '2026-04-25 21:12:53', '2026-05-03 20:07:22', 3, NULL, 1),
(3384, 3, 1024, 8, 1, NULL, '2026-04-25 21:12:53', '2026-04-28 01:28:52', 3, NULL, 1),
(3385, 6, 1024, 7, 8, NULL, '2026-04-25 21:12:53', '2026-05-04 03:54:06', 3, NULL, 1),
(3386, 3, 1024, 7, 13, NULL, '2026-04-25 21:12:55', '2026-04-28 01:28:14', 3, NULL, 1),
(3387, 1, 1025, 7, 27, NULL, '2026-04-25 21:12:55', '2026-04-27 14:04:42', 3, NULL, 1),
(3388, 3, 1025, 6, 27, NULL, '2026-04-25 21:12:55', '2026-04-28 01:22:24', 3, NULL, 1),
(3389, 1, 1025, 8, 27, NULL, '2026-04-25 21:12:56', '2026-04-28 01:24:20', 3, NULL, 1),
(3390, 6, 1026, 1, 27, NULL, '2026-04-25 21:12:56', '2026-04-28 00:40:41', 3, NULL, 1),
(3391, 7, 1026, 2, 27, NULL, '2026-04-25 21:12:56', '2026-04-28 00:39:28', 3, NULL, 1),
(3392, 7, 1026, 1, 27, NULL, '2026-04-25 21:12:57', '2026-04-28 00:39:13', 3, NULL, 1),
(3393, 6, 1027, 5, 9, NULL, '2026-04-25 21:12:57', '2026-04-25 21:12:57', 3, NULL, 1),
(3394, 7, 1027, 5, 9, NULL, '2026-04-25 21:12:57', '2026-04-28 00:40:09', 3, NULL, 1),
(3395, 1, 1027, 3, 15, NULL, '2026-04-25 21:12:58', '2026-04-25 21:12:58', 3, NULL, 1),
(3396, 5, 1028, 7, 18, NULL, '2026-04-25 21:12:58', '2026-04-27 23:51:02', 3, NULL, 1),
(3397, 2, 1028, 2, 18, NULL, '2026-04-25 21:12:58', '2026-04-28 01:23:46', 3, NULL, 1),
(3398, 5, 1028, 6, 18, NULL, '2026-04-25 21:12:59', '2026-04-27 14:04:43', 3, NULL, 1),
(3399, 6, 1029, 8, 8, NULL, '2026-04-25 21:12:59', '2026-04-25 21:12:59', 3, NULL, 1),
(3400, 6, 1029, 9, 12, NULL, '2026-04-25 21:12:59', '2026-04-30 02:27:59', 3, NULL, 1),
(3401, 2, 1029, 7, 10, NULL, '2026-04-25 21:13:00', '2026-05-04 20:41:17', 3, NULL, 1),
(3402, 6, 1030, 3, 9, NULL, '2026-04-25 21:13:00', '2026-04-25 21:13:00', 3, NULL, 1),
(3403, 3, 1030, 10, 12, NULL, '2026-04-25 21:13:00', '2026-05-06 01:49:18', 3, NULL, 1),
(3404, 6, 1031, 2, 6, NULL, '2026-04-25 21:13:00', '2026-04-25 21:13:00', 3, NULL, 1),
(3405, 6, 1031, 1, 2, NULL, '2026-04-25 21:13:01', '2026-04-28 01:32:44', 3, NULL, 1),
(3406, 6, 1033, 7, 10, NULL, '2026-04-25 21:13:01', '2026-04-25 21:13:01', 3, NULL, 1),
(3407, 6, 1033, 8, 5, NULL, '2026-04-25 21:13:01', '2026-04-28 22:27:25', 3, NULL, 1),
(3408, 2, 1033, 6, 8, NULL, '2026-04-25 21:13:02', '2026-05-06 01:18:10', 3, NULL, 1),
(3409, 7, 1034, 3, 6, NULL, '2026-04-25 21:13:02', '2026-04-28 01:31:48', 3, NULL, 1),
(3410, 7, 1034, 4, 8, NULL, '2026-04-25 21:13:02', '2026-04-25 21:13:02', 3, NULL, 1),
(3411, 3, 1034, 2, 14, NULL, '2026-04-25 21:13:03', '2026-05-06 01:17:30', 3, NULL, 1),
(3412, 7, 1035, 6, 7, NULL, '2026-04-25 21:13:03', '2026-04-29 04:01:32', 3, NULL, 1),
(3413, 4, 1035, 1, 7, NULL, '2026-04-25 21:13:03', '2026-05-03 23:04:46', 3, NULL, 1),
(3414, 7, 1035, 5, 7, NULL, '2026-04-25 21:13:04', '2026-05-03 23:00:49', 3, NULL, 1),
(3415, 5, 1037, 4, 10, NULL, '2026-04-25 21:13:04', '2026-04-28 02:22:31', 3, NULL, 1),
(3416, 5, 1037, 3, 9, NULL, '2026-04-25 21:13:04', '2026-05-04 21:02:23', 3, NULL, 1),
(3417, 2, 1037, 5, 10, NULL, '2026-04-25 21:13:05', '2026-05-04 21:05:47', 3, NULL, 1),
(3418, 6, 1038, 7, 9, NULL, '2026-04-25 21:13:05', '2026-04-28 09:47:31', 3, NULL, 1),
(3419, 6, 1038, 6, 6, NULL, '2026-04-25 21:13:05', '2026-04-28 09:46:55', 3, NULL, 1),
(3420, 3, 1038, 6, 7, NULL, '2026-04-25 21:13:06', '2026-05-04 23:30:21', 3, NULL, 1),
(3421, 5, 1039, 8, 14, NULL, '2026-04-25 21:13:06', '2026-05-04 23:31:32', 3, NULL, 1),
(3422, 5, 1039, 9, 6, NULL, '2026-04-25 21:13:06', '2026-04-28 02:23:34', 3, NULL, 1),
(3423, 4, 1039, 8, 5, NULL, '2026-04-25 21:13:07', '2026-04-25 21:13:07', 3, NULL, 1),
(3424, 2, 1040, 8, 16, NULL, '2026-04-25 21:13:07', '2026-04-28 09:44:06', 3, NULL, 1),
(3425, 3, 1040, 2, 16, NULL, '2026-04-25 21:13:07', '2026-04-28 09:42:43', 3, NULL, 1),
(3426, 2, 1040, 9, 9, NULL, '2026-04-25 21:13:08', '2026-05-04 21:04:48', 3, NULL, 1),
(3427, 6, 1041, 9, 13, NULL, '2026-04-25 21:13:08', '2026-04-28 09:48:28', 3, NULL, 1),
(3428, 6, 1041, 8, 4, NULL, '2026-04-25 21:13:08', '2026-05-03 23:01:47', 3, NULL, 1),
(3429, 4, 1041, 3, 3, NULL, '2026-04-25 21:13:09', '2026-05-03 22:53:44', 3, NULL, 1),
(3430, 3, 1042, 7, 5, NULL, '2026-04-25 21:13:09', '2026-04-26 03:48:47', 3, NULL, 1),
(3431, 1, 1042, 6, 9, NULL, '2026-04-25 21:13:09', '2026-05-03 23:11:58', 3, NULL, 1),
(3432, 3, 1042, 8, 7, NULL, '2026-04-25 21:13:10', '2026-04-30 00:31:47', 3, NULL, 1),
(3433, 1, 1043, 2, 6, NULL, '2026-04-25 21:13:11', '2026-04-30 01:11:31', 3, NULL, 1),
(3434, 1, 1043, 1, 6, NULL, '2026-04-25 21:13:11', '2026-04-30 01:11:00', 3, NULL, 1),
(3435, 3, 1043, 5, 5, NULL, '2026-04-25 21:13:12', '2026-04-25 21:13:12', 3, NULL, 1),
(3436, 4, 1044, 2, 4, NULL, '2026-04-25 21:13:12', '2026-04-26 09:48:18', 3, NULL, 1),
(3437, 3, 1044, 9, 10, NULL, '2026-04-25 21:13:12', '2026-04-26 03:50:16', 3, NULL, 1),
(3438, 4, 1044, 1, 16, NULL, '2026-04-25 21:13:13', '2026-04-25 21:13:13', 3, NULL, 1),
(3439, 3, 1045, 1, 2, NULL, '2026-04-25 21:13:13', '2026-04-29 08:58:31', 3, NULL, 1),
(3440, 1, 1045, 4, 9, NULL, '2026-04-25 21:13:13', '2026-05-03 23:17:12', 3, NULL, 1),
(3441, 3, 1045, 2, 7, NULL, '2026-04-25 21:13:14', '2026-05-03 23:16:13', 3, NULL, 1),
(3442, 5, 1046, 3, 14, NULL, '2026-04-25 21:13:14', '2026-05-03 23:17:37', 3, NULL, 1),
(3443, 3, 1046, 6, 10, NULL, '2026-04-25 21:13:14', '2026-04-26 09:49:21', 3, NULL, 1),
(3444, 5, 1046, 4, 13, NULL, '2026-04-25 21:13:15', '2026-05-03 23:18:07', 3, NULL, 1),
(3445, 6, 1048, 2, 7, NULL, '2026-04-25 21:13:15', '2026-04-25 21:13:15', 3, NULL, 1),
(3446, 2, 1048, 8, 12, NULL, '2026-04-25 21:13:15', '2026-04-26 03:41:24', 3, NULL, 1),
(3447, 2, 1048, 9, 6, NULL, '2026-04-25 21:13:16', '2026-04-26 03:55:25', 3, NULL, 1),
(3448, 6, 1049, 5, 8, NULL, '2026-04-25 21:13:16', '2026-04-29 14:25:49', 3, NULL, 1),
(3449, 3, 1049, 8, 11, NULL, '2026-04-25 21:13:16', '2026-04-27 22:23:43', 3, NULL, 1),
(3450, 4, 1049, 7, 14, NULL, '2026-04-25 21:13:17', '2026-05-04 23:19:55', 3, NULL, 1),
(3451, 3, 1050, 6, 5, NULL, '2026-04-25 21:13:17', '2026-04-26 08:59:25', 3, NULL, 1),
(3452, 3, 1050, 7, 7, NULL, '2026-04-25 21:13:17', '2026-04-26 09:00:00', 3, NULL, 1),
(3453, 4, 1050, 8, 8, NULL, '2026-04-25 21:13:18', '2026-04-25 21:13:18', 3, NULL, 1),
(3454, 6, 1051, 4, 8, NULL, '2026-04-25 21:13:18', '2026-04-25 21:13:18', 3, NULL, 1),
(3455, 5, 1051, 2, 7, NULL, '2026-04-25 21:13:18', '2026-05-03 20:18:05', 3, NULL, 1),
(3456, 5, 1051, 3, 4, NULL, '2026-04-25 21:13:19', '2026-05-03 20:17:33', 3, NULL, 1),
(3457, 2, 1052, 2, 13, NULL, '2026-04-25 21:13:19', '2026-05-03 23:25:48', 3, NULL, 1),
(3458, 1, 1052, 4, 13, NULL, '2026-04-25 21:13:19', '2026-04-29 14:25:21', 3, NULL, 1),
(3459, 1, 1052, 3, 13, NULL, '2026-04-25 21:13:20', '2026-04-29 14:26:53', 3, NULL, 1),
(3460, 2, 1053, 5, 13, NULL, '2026-04-25 21:13:20', '2026-04-29 23:45:04', 3, NULL, 1),
(3461, 3, 1053, 9, 9, NULL, '2026-04-25 21:13:20', '2026-04-26 09:45:06', 3, NULL, 1),
(3462, 2, 1053, 4, 6, NULL, '2026-04-25 21:13:21', '2026-04-27 22:21:50', 3, NULL, 1),
(3463, 5, 1054, 8, 7, NULL, '2026-04-25 21:13:21', '2026-05-04 23:10:23', 3, NULL, 1),
(3464, 4, 1054, 2, 11, NULL, '2026-04-25 21:13:21', '2026-04-26 07:19:37', 3, NULL, 1),
(3465, 4, 1054, 6, 16, NULL, '2026-04-25 21:13:22', '2026-04-26 07:06:29', 3, NULL, 1),
(3466, 1, 1055, 8, 2, NULL, '2026-04-25 21:13:22', '2026-04-26 07:11:54', 3, NULL, 1),
(3467, 1, 1055, 9, 13, NULL, '2026-04-25 21:13:22', '2026-04-26 07:12:32', 3, NULL, 1),
(3468, 4, 1055, 9, 2, NULL, '2026-04-25 21:13:23', '2026-04-25 21:13:23', 3, NULL, 1),
(3469, 2, 1056, 2, 2, NULL, '2026-04-25 21:13:23', '2026-04-26 07:17:43', 3, NULL, 1),
(3470, 2, 1056, 3, 2, NULL, '2026-04-25 21:13:23', '2026-04-26 07:14:11', 3, NULL, 1),
(3471, 1, 1056, 3, 2, NULL, '2026-04-25 21:13:23', '2026-04-26 07:10:02', 3, NULL, 1),
(3472, 5, 1057, 6, 3, NULL, '2026-04-25 21:13:23', '2026-04-26 07:15:57', 3, NULL, 1),
(3473, 1, 1057, 6, 1, NULL, '2026-04-25 21:13:23', '2026-04-26 07:10:55', 3, NULL, 1),
(3474, 1, 1057, 5, 16, NULL, '2026-04-25 21:13:23', '2026-04-25 21:13:23', 3, NULL, 1),
(3475, 5, 1058, 1, 8, NULL, '2026-04-25 21:13:23', '2026-04-25 21:13:23', 3, NULL, 1),
(3476, 1, 1058, 1, 8, NULL, '2026-04-25 21:13:23', '2026-04-26 07:08:05', 3, NULL, 1),
(3477, 5, 1058, 2, 8, NULL, '2026-04-25 21:13:23', '2026-04-26 07:07:08', 3, NULL, 1),
(3478, 2, 1059, 6, 7, NULL, '2026-04-25 21:13:23', '2026-05-03 22:45:20', 3, NULL, 1),
(3479, 3, 1059, 10, 11, NULL, '2026-04-25 21:13:24', '2026-04-25 21:13:24', 3, NULL, 1),
(3480, 3, 1059, 9, 3, NULL, '2026-04-25 21:13:24', '2026-05-04 23:12:19', 3, NULL, 1),
(3481, 1, 1060, 4, 8, NULL, '2026-04-25 21:13:24', '2026-04-25 21:13:24', 3, NULL, 1),
(3482, 1, 1060, 3, 4, NULL, '2026-04-25 21:13:24', '2026-04-27 15:37:03', 3, NULL, 1),
(3483, 3, 1060, 4, 8, NULL, '2026-04-25 21:13:24', '2026-04-25 21:13:24', 3, NULL, 1),
(3484, 5, 1061, 10, 8, NULL, '2026-04-25 21:13:24', '2026-04-27 15:41:07', 3, NULL, 1),
(3485, 5, 1061, 9, 12, NULL, '2026-04-25 21:13:24', '2026-04-26 04:03:52', 3, NULL, 1),
(3486, 2, 1061, 10, 9, NULL, '2026-04-25 21:13:24', '2026-04-26 03:57:26', 3, NULL, 1),
(3487, 5, 1062, 8, 5, NULL, '2026-04-25 21:13:24', '2026-04-27 15:40:08', 3, NULL, 1),
(3488, 2, 1062, 8, 11, NULL, '2026-04-25 21:13:24', '2026-04-27 15:37:42', 3, NULL, 1),
(3489, 2, 1062, 9, 1, NULL, '2026-04-25 21:13:24', '2026-04-25 21:13:24', 3, NULL, 1),
(3490, 3, 1063, 1, 8, NULL, '2026-04-25 21:13:24', '2026-04-27 15:41:44', 3, NULL, 1),
(3491, 5, 1063, 6, 7, NULL, '2026-04-25 21:13:24', '2026-04-27 15:38:46', 3, NULL, 1),
(3492, 5, 1063, 4, 11, NULL, '2026-04-25 21:13:24', '2026-04-27 15:39:45', 3, NULL, 1),
(3493, 6, 1064, 3, 12, NULL, '2026-04-25 21:13:24', '2026-04-25 21:13:24', 3, NULL, 1),
(3494, 6, 1064, 2, 9, NULL, '2026-04-25 21:13:25', '2026-04-26 10:07:30', 3, NULL, 1),
(3495, 2, 1064, 9, 10, NULL, '2026-04-25 21:13:25', '2026-04-27 21:49:21', 3, NULL, 1),
(3496, 3, 1065, 3, 16, NULL, '2026-04-25 21:13:25', '2026-05-05 00:56:03', 3, NULL, 1),
(3497, 3, 1065, 4, 15, NULL, '2026-04-25 21:13:25', '2026-04-25 21:13:25', 3, NULL, 1),
(3498, 4, 1065, 4, 5, NULL, '2026-04-25 21:13:25', '2026-04-25 21:13:25', 3, NULL, 1),
(3499, 5, 1066, 4, 2, NULL, '2026-04-25 21:13:25', '2026-04-26 10:04:15', 3, NULL, 1),
(3500, 6, 1066, 7, 14, NULL, '2026-04-25 21:13:25', '2026-04-26 10:05:04', 3, NULL, 1),
(3501, 5, 1066, 7, 1, NULL, '2026-04-25 21:13:25', '2026-04-26 10:05:53', 3, NULL, 1),
(3502, 4, 1067, 6, 9, NULL, '2026-04-25 21:13:25', '2026-05-03 20:21:10', 3, NULL, 1),
(3503, 3, 1067, 9, 14, NULL, '2026-04-25 21:13:25', '2026-04-27 23:38:07', 3, NULL, 1),
(3504, 3, 1067, 8, 10, NULL, '2026-04-25 21:13:25', '2026-04-27 23:37:31', 3, NULL, 1),
(3505, 4, 1068, 8, 4, NULL, '2026-04-25 21:13:25', '2026-04-30 00:37:57', 3, NULL, 1),
(3506, 5, 1068, 3, 16, NULL, '2026-04-25 21:13:25', '2026-04-25 21:13:25', 3, NULL, 1),
(3507, 6, 1068, 1, 14, NULL, '2026-04-25 21:13:25', '2026-04-26 10:06:53', 3, NULL, 1),
(3508, 1, 1069, 9, 8, NULL, '2026-04-25 21:13:25', '2026-04-26 10:00:33', 3, NULL, 1),
(3509, 1, 1069, 10, 7, NULL, '2026-04-25 21:13:25', '2026-05-03 20:23:16', 3, NULL, 1),
(3510, 2, 1069, 11, 2, NULL, '2026-04-25 21:13:25', '2026-04-25 21:13:25', 3, NULL, 1),
(3511, 1, 1070, 7, 4, NULL, '2026-04-25 21:13:25', '2026-05-03 21:17:13', 3, NULL, 1),
(3512, 4, 1070, 9, 10, NULL, '2026-04-25 21:13:25', '2026-04-26 04:05:36', 3, NULL, 1),
(3513, 5, 1070, 9, 4, NULL, '2026-04-25 21:13:25', '2026-04-25 21:13:25', 3, NULL, 1),
(3514, 3, 1071, 3, 10, NULL, '2026-04-25 21:13:25', '2026-05-03 21:23:43', 3, NULL, 1),
(3515, 2, 1071, 5, 5, NULL, '2026-04-25 21:13:25', '2026-05-03 21:26:08', 3, NULL, 1),
(3516, 2, 1071, 6, 16, NULL, '2026-04-25 21:13:25', '2026-05-03 21:27:07', 3, NULL, 1),
(3517, 4, 1072, 1, 10, NULL, '2026-04-25 21:13:25', '2026-05-03 21:18:41', 3, NULL, 1),
(3518, 3, 1072, 10, 8, NULL, '2026-04-25 21:13:25', '2026-05-03 21:20:33', 3, NULL, 1),
(3519, 4, 1072, 2, 1, NULL, '2026-04-25 21:13:26', '2026-05-03 21:19:37', 3, NULL, 1),
(3520, 5, 1073, 7, 16, NULL, '2026-04-25 21:13:26', '2026-04-25 21:13:26', 3, NULL, 1),
(3521, 5, 1073, 10, 14, NULL, '2026-04-25 21:13:26', '2026-04-26 09:58:20', 3, NULL, 1),
(3522, 3, 1073, 9, 13, NULL, '2026-04-25 21:13:26', '2026-04-26 04:12:22', 3, NULL, 1),
(3523, 1, 1074, 9, 4, NULL, '2026-04-25 21:13:26', '2026-04-25 21:13:26', 3, NULL, 1),
(3524, 1, 1074, 10, 3, NULL, '2026-04-25 21:13:26', '2026-05-03 20:22:31', 3, NULL, 1),
(3525, 5, 1074, 6, 13, NULL, '2026-04-25 21:13:26', '2026-04-27 23:08:51', 3, NULL, 1),
(3526, 1, 1093, 11, 13, NULL, '2026-04-25 21:13:26', '2026-04-25 21:31:28', 3, NULL, 1),
(3527, 4, 1093, 10, 13, NULL, '2026-04-25 21:13:26', '2026-04-25 21:32:22', 3, NULL, 1),
(3528, 6, 1094, 5, 13, NULL, '2026-04-25 21:13:26', '2026-04-25 21:13:26', 3, NULL, 1),
(3529, 4, 1094, 8, 14, NULL, '2026-04-25 21:13:26', '2026-04-27 22:16:52', 3, NULL, 1),
(3530, 2, 1095, 11, 15, NULL, '2026-04-25 21:13:27', '2026-04-25 21:28:18', 3, NULL, 1),
(3531, 2, 1095, 10, 13, NULL, '2026-04-25 21:13:27', '2026-04-25 21:28:56', 3, NULL, 1),
(3532, 6, 1096, 6, 15, NULL, '2026-04-25 21:13:27', '2026-04-28 14:05:58', 3, NULL, 1),
(3533, 2, 1096, 8, 15, NULL, '2026-04-25 21:13:27', '2026-04-25 21:30:21', 3, NULL, 1),
(3534, 4, 1102, 8, 15, NULL, '2026-04-25 21:13:27', '2026-04-27 15:45:43', 3, NULL, 1),
(3535, 6, 1102, 4, 5, NULL, '2026-04-25 21:13:27', '2026-04-26 12:48:59', 3, NULL, 1),
(3536, 1, 1103, 11, 14, NULL, '2026-04-25 21:13:27', '2026-04-25 23:08:51', 3, NULL, 1),
(3537, 1, 1103, 10, 1, NULL, '2026-04-25 21:13:27', '2026-04-25 21:13:27', 3, NULL, 1),
(3538, 3, 1104, 11, 14, NULL, '2026-04-25 21:13:27', '2026-04-25 22:52:54', 3, NULL, 1),
(3539, 3, 1104, 10, 3, NULL, '2026-04-25 21:13:27', '2026-04-25 21:13:27', 3, NULL, 1),
(3540, 6, 1105, 7, 7, NULL, '2026-04-25 21:13:28', '2026-04-26 12:48:23', 3, NULL, 1),
(3541, 2, 1105, 9, 15, NULL, '2026-04-25 21:13:28', '2026-04-27 15:48:21', 3, NULL, 1),
(3542, 2, 1106, 7, 15, NULL, '2026-04-25 21:13:28', '2026-04-27 15:48:57', 3, NULL, 1),
(3543, 5, 1106, 10, 13, NULL, '2026-04-25 21:13:29', '2026-04-30 02:06:39', 3, NULL, 1),
(3544, 5, 1106, 11, 14, NULL, '2026-04-25 21:13:29', '2026-04-30 02:07:12', 3, NULL, 1),
(3545, 6, 1107, 2, 14, NULL, '2026-04-25 21:13:29', '2026-04-30 22:18:11', 3, NULL, 1),
(3546, 6, 1107, 3, 13, NULL, '2026-04-25 21:13:29', '2026-04-25 23:10:57', 3, NULL, 1),
(3547, 4, 1107, 11, 4, NULL, '2026-04-25 21:13:29', '2026-04-25 21:13:29', 3, NULL, 1),
(3548, 2, 1108, 10, 6, NULL, '2026-04-25 21:13:29', '2026-04-25 21:13:29', 3, NULL, 1),
(3549, 1, 1108, 10, 13, NULL, '2026-04-25 21:13:29', '2026-04-27 23:03:30', 3, NULL, 1),
(3550, 6, 1108, 9, 14, NULL, '2026-04-25 21:13:29', '2026-04-25 23:24:47', 3, NULL, 1),
(3551, 1, 1109, 8, 8, NULL, '2026-04-25 21:13:29', '2026-04-25 21:13:29', 3, NULL, 1),
(3552, 2, 1109, 7, 16, NULL, '2026-04-25 21:13:29', '2026-04-25 21:13:29', 3, NULL, 1),
(3553, 1, 1109, 9, 5, NULL, '2026-04-25 21:13:29', '2026-04-25 23:21:04', 3, NULL, 1),
(3554, 5, 1110, 9, 13, NULL, '2026-04-25 21:13:29', '2026-04-25 23:28:41', 3, NULL, 1),
(3555, 5, 1110, 10, 12, NULL, '2026-04-25 21:13:29', '2026-04-25 23:26:33', 3, NULL, 1),
(3556, 6, 1110, 11, 15, NULL, '2026-04-25 21:13:29', '2026-04-25 23:25:10', 3, NULL, 1),
(3557, 4, 1111, 11, 6, NULL, '2026-04-25 21:13:29', '2026-05-05 00:48:44', 3, NULL, 1),
(3558, 4, 1111, 10, 5, NULL, '2026-04-25 21:13:30', '2026-04-25 23:20:12', 3, NULL, 1),
(3559, 2, 1111, 8, 1, NULL, '2026-04-25 21:13:30', '2026-04-25 21:13:30', 3, NULL, 1),
(3560, 1, 1112, 7, 16, NULL, '2026-04-25 21:13:30', '2026-04-26 01:56:09', 3, NULL, 1),
(3561, 4, 1112, 9, 14, NULL, '2026-04-25 21:13:30', '2026-04-30 22:19:16', 3, NULL, 1),
(3562, 6, 1112, 8, 12, NULL, '2026-04-25 21:13:30', '2026-04-25 23:27:35', 3, NULL, 1),
(3563, 6, 1116, 6, 10, NULL, '2026-04-25 21:13:30', '2026-04-25 21:13:30', 3, NULL, 1),
(3564, 6, 1117, 5, 14, NULL, '2026-04-25 21:13:30', '2026-04-25 23:31:40', 3, NULL, 1),
(3565, 2, 1117, 10, 14, NULL, '2026-04-25 21:13:30', '2026-04-25 23:34:44', 3, NULL, 1),
(3566, 2, 1117, 9, 3, NULL, '2026-04-25 21:13:30', '2026-04-25 21:13:30', 3, NULL, 1),
(3570, 4, 1119, 11, 16, NULL, '2026-04-25 21:13:30', '2026-05-04 22:14:15', 3, NULL, 1),
(3571, 6, 1119, 7, 16, NULL, '2026-04-25 21:13:30', '2026-05-04 23:02:57', 3, NULL, 1),
(3572, 4, 1119, 12, 16, NULL, '2026-04-25 21:13:30', '2026-05-04 22:13:32', 3, NULL, 1),
(3573, 6, 1120, 2, 15, NULL, '2026-04-25 21:13:31', '2026-04-26 05:15:28', 3, NULL, 1),
(3574, 7, 1120, 5, 11, NULL, '2026-04-25 21:13:31', '2026-04-25 22:16:18', 3, NULL, 1),
(3575, 7, 1120, 4, 16, NULL, '2026-04-25 21:13:31', '2026-04-25 22:15:47', 3, NULL, 1),
(3576, 5, 1121, 9, 10, NULL, '2026-04-25 21:13:31', '2026-05-04 22:42:28', 3, NULL, 1),
(3577, 5, 1121, 8, 16, NULL, '2026-04-25 21:13:31', '2026-04-27 21:41:21', 3, NULL, 1),
(3578, 4, 1121, 7, 15, NULL, '2026-04-25 21:13:31', '2026-04-27 21:45:17', 3, NULL, 1),
(3579, 1, 1122, 7, 15, NULL, '2026-04-25 21:13:31', '2026-05-04 22:11:39', 3, NULL, 1),
(3580, 1, 1122, 8, 15, NULL, '2026-04-25 21:13:31', '2026-05-04 22:16:57', 3, NULL, 1),
(3581, 4, 1122, 5, 15, NULL, '2026-04-25 21:13:31', '2026-04-27 21:47:38', 3, NULL, 1),
(3582, 7, 1123, 2, 15, NULL, '2026-04-25 21:13:31', '2026-05-04 23:06:40', 3, NULL, 1),
(3583, 6, 1123, 10, 15, NULL, '2026-04-25 21:13:31', '2026-05-04 22:39:53', 3, NULL, 1),
(3584, 6, 1123, 9, 15, NULL, '2026-04-25 21:13:31', '2026-05-04 22:40:40', 3, NULL, 1),
(3585, 7, 1124, 11, 16, NULL, '2026-04-25 21:13:31', '2026-04-29 05:33:05', 3, NULL, 1),
(3586, 6, 1124, 8, 16, NULL, '2026-04-25 21:13:31', '2026-05-04 22:18:46', 3, NULL, 1),
(3587, 7, 1124, 10, 16, NULL, '2026-04-25 21:13:31', '2026-04-29 05:34:09', 3, NULL, 1),
(3588, 1, 1125, 12, 16, NULL, '2026-04-25 21:13:31', '2026-04-25 22:34:59', 3, NULL, 1),
(3589, 4, 1125, 11, 15, NULL, '2026-04-25 21:13:32', '2026-04-28 13:58:44', 3, NULL, 1),
(3590, 4, 1125, 10, 7, NULL, '2026-04-25 21:13:32', '2026-04-25 22:30:59', 3, NULL, 1),
(3591, 5, 1126, 10, 10, NULL, '2026-04-25 21:13:32', '2026-04-25 21:13:32', 3, NULL, 1),
(3592, 5, 1126, 11, 12, NULL, '2026-04-25 21:13:32', '2026-04-25 22:33:10', 3, NULL, 1),
(3593, 3, 1126, 12, 16, NULL, '2026-04-25 21:13:32', '2026-05-04 22:52:46', 3, NULL, 1),
(3594, 1, 1127, 10, 15, NULL, '2026-04-25 21:13:32', '2026-05-04 22:30:12', 3, NULL, 1),
(3595, 3, 1127, 10, 16, NULL, '2026-04-25 21:13:32', '2026-05-04 22:48:25', 3, NULL, 1),
(3596, 3, 1127, 11, 16, NULL, '2026-04-25 21:13:32', '2026-04-27 16:17:12', 3, NULL, 1),
(3597, 3, 1128, 9, 15, NULL, '2026-04-25 21:13:32', '2026-04-25 22:27:02', 3, NULL, 1),
(3598, 3, 1128, 8, 15, NULL, '2026-04-25 21:13:32', '2026-04-30 00:30:51', 3, NULL, 1),
(3599, 4, 1128, 9, 13, NULL, '2026-04-25 21:13:32', '2026-04-26 06:40:55', 3, NULL, 1),
(3600, 1, 1129, 11, 16, NULL, '2026-04-25 21:13:32', '2026-04-25 22:19:52', 3, NULL, 1),
(3601, 2, 1129, 10, 11, NULL, '2026-04-25 21:13:32', '2026-05-04 22:50:21', 3, NULL, 1),
(3602, 2, 1129, 11, 16, NULL, '2026-04-25 21:13:32', '2026-05-04 22:50:47', 3, NULL, 1),
(3606, 5, 1131, 12, 15, NULL, '2026-04-25 21:13:33', '2026-04-29 06:12:28', 3, NULL, 1),
(3607, 5, 1131, 11, 15, NULL, '2026-04-25 21:13:33', '2026-04-29 06:23:50', 3, NULL, 1),
(3608, 7, 1131, 5, 13, NULL, '2026-04-25 21:13:33', '2026-04-25 22:46:39', 3, NULL, 1),
(3609, 7, 1132, 6, 14, NULL, '2026-04-25 21:13:33', '2026-05-04 22:57:57', 3, NULL, 1),
(3610, 4, 1132, 10, 15, NULL, '2026-04-25 21:13:33', '2026-04-29 06:08:27', 3, NULL, 1),
(3611, 4, 1132, 12, 15, NULL, '2026-04-25 21:13:33', '2026-04-29 06:05:38', 3, NULL, 1),
(3612, 4, 1133, 6, 15, NULL, '2026-04-25 21:13:33', '2026-04-29 06:09:34', 3, NULL, 1),
(3613, 1, 1133, 10, 12, NULL, '2026-04-25 21:13:33', '2026-04-25 22:37:48', 3, NULL, 1),
(3614, 1, 1133, 9, 6, NULL, '2026-04-25 21:13:33', '2026-04-25 21:13:33', 3, NULL, 1),
(3615, 4, 1134, 8, 16, NULL, '2026-04-25 21:13:33', '2026-04-30 00:41:18', 3, NULL, 1),
(3616, 7, 1134, 3, 15, NULL, '2026-04-25 21:13:33', '2026-04-27 22:43:54', 3, NULL, 1),
(3617, 4, 1134, 7, 16, NULL, '2026-04-25 21:13:33', '2026-04-25 22:48:14', 3, NULL, 1),
(3618, 6, 1135, 8, 13, NULL, '2026-04-25 21:13:33', '2026-04-25 22:38:44', 3, NULL, 1),
(3619, 6, 1135, 7, 13, NULL, '2026-04-25 21:13:33', '2026-04-25 21:13:33', 3, NULL, 1),
(3620, 7, 1135, 2, 16, NULL, '2026-04-25 21:13:33', '2026-04-25 22:45:43', 3, NULL, 1),
(3621, 1, 1136, 8, 16, NULL, '2026-04-25 21:13:33', '2026-04-25 21:13:33', 3, NULL, 1),
(3622, 6, 1136, 10, 16, NULL, '2026-04-25 21:13:33', '2026-05-04 22:55:42', 3, NULL, 1),
(3623, 6, 1136, 9, 16, NULL, '2026-04-25 21:13:34', '2026-05-04 22:56:23', 3, NULL, 1),
(3624, 1, 1160, 7, 24, NULL, '2026-04-27 00:21:57', '2026-04-27 02:53:54', 3, 83, 2),
(3625, 4, 1160, 2, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 83, 2),
(3626, 4, 1160, 1, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 83, 2),
(3627, 1, 1161, 1, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 84, 2),
(3628, 5, 1161, 1, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 84, 2),
(3629, 5, 1161, 2, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 84, 2),
(3630, 4, 1162, 3, 22, NULL, '2026-04-27 00:21:57', '2026-04-27 02:54:50', 3, 86, 2),
(3631, 1, 1162, 3, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 86, 2),
(3632, 2, 1162, 1, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 86, 2),
(3633, 2, 1163, 2, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 84, 2),
(3634, 4, 1163, 4, 23, NULL, '2026-04-27 00:21:57', '2026-04-28 20:39:27', 3, 84, 2),
(3635, 2, 1163, 3, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 84, 2),
(3636, 2, 1164, 4, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 85, 2),
(3637, 1, 1164, 4, 21, NULL, '2026-04-27 00:21:57', '2026-04-28 20:39:57', 3, 85, 2),
(3638, 1, 1164, 5, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 85, 2),
(3639, 2, 1165, 10, 24, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 85, 2),
(3640, 2, 1165, 9, 24, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 85, 2),
(3641, 3, 1165, 1, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 85, 2),
(3642, 1, 1166, 2, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3643, 5, 1166, 1, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3644, 1, 1166, 3, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3645, 5, 1167, 4, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 02:18:27', 3, 87, 2),
(3646, 4, 1167, 4, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 02:19:08', 3, 87, 2),
(3647, 3, 1167, 2, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3648, 4, 1168, 1, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3649, 4, 1168, 2, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 02:17:34', 3, 97, 2),
(3650, 1, 1168, 4, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3651, 2, 1169, 1, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3652, 3, 1169, 3, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3653, 3, 1169, 4, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3654, 3, 1177, 6, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3655, 2, 1177, 3, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3656, 4, 1177, 3, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3657, 3, 1178, 7, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 02:21:04', 3, 87, 2),
(3658, 2, 1178, 2, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 87, 2),
(3659, 1, 1178, 5, 24, NULL, '2026-04-27 00:21:57', '2026-04-27 01:00:13', 3, 87, 2),
(3660, 2, 1179, 4, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 98, 2),
(3661, 4, 1179, 5, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 98, 2),
(3662, 2, 1179, 5, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 02:31:43', 3, 98, 2),
(3663, 2, 1180, 6, 23, NULL, '2026-04-27 00:21:57', '2026-04-27 02:29:04', 3, 97, 2),
(3664, 1, 1180, 6, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3665, 1, 1180, 7, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 97, 2),
(3666, 3, 1181, 9, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 89, 2),
(3667, 3, 1181, 10, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 89, 2),
(3668, 4, 1181, 6, 25, NULL, '2026-04-27 00:21:57', '2026-04-27 01:02:02', 3, 89, 2),
(3669, 1, 1203, 5, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 79, 2),
(3670, 2, 1203, 7, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 79, 2),
(3671, 2, 1203, 8, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 79, 2),
(3672, 4, 1204, 8, 20, NULL, '2026-04-27 00:21:57', '2026-05-08 05:17:35', 3, 79, 2),
(3673, 3, 1204, 7, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 79, 2),
(3674, 3, 1204, 8, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 79, 2),
(3675, 3, 1205, 6, 21, NULL, '2026-04-27 00:21:57', '2026-04-27 02:58:39', 3, 90, 2),
(3676, 4, 1205, 7, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 90, 2),
(3677, 4, 1205, 6, 20, NULL, '2026-04-27 00:21:57', '2026-04-27 00:21:57', 3, 90, 2),
(3678, 1, 1137, 1, 23, NULL, '2026-04-27 00:22:29', '2026-04-27 02:45:23', 3, NULL, 2),
(3679, 1, 1137, 2, 24, NULL, '2026-04-27 00:22:30', '2026-04-27 02:45:48', 3, NULL, 2),
(3680, 3, 1137, 4, 21, NULL, '2026-04-27 00:22:30', '2026-04-27 02:42:52', 3, NULL, 2),
(3681, 1, 1138, 6, 24, NULL, '2026-04-27 00:22:30', '2026-04-27 01:45:43', 3, NULL, 2),
(3682, 5, 1138, 7, 24, NULL, '2026-04-27 00:22:30', '2026-04-27 02:39:06', 3, NULL, 2),
(3683, 5, 1138, 8, 25, NULL, '2026-04-27 00:22:31', '2026-04-27 01:39:47', 3, NULL, 2),
(3684, 2, 1139, 8, 22, NULL, '2026-04-27 00:22:31', '2026-05-08 05:37:40', 3, NULL, 2),
(3685, 5, 1139, 3, 25, NULL, '2026-04-27 00:22:31', '2026-04-27 01:45:07', 3, NULL, 2),
(3686, 2, 1139, 7, 22, NULL, '2026-04-27 00:22:32', '2026-05-08 05:36:35', 3, NULL, 2),
(3687, 2, 1140, 10, 24, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:32', '2026-04-27 01:42:55', 3, NULL, 2),
(3688, 2, 1140, 9, 24, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:32', '2026-04-27 01:42:06', 3, NULL, 2),
(3689, 3, 1140, 1, 21, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:32', '2026-04-27 00:22:32', 3, NULL, 2),
(3690, 5, 1141, 1, 25, NULL, '2026-04-27 00:22:33', '2026-04-27 02:48:41', 3, NULL, 2),
(3691, 3, 1141, 5, 20, NULL, '2026-04-27 00:22:33', '2026-05-08 05:40:29', 3, NULL, 2),
(3692, 5, 1141, 2, 24, NULL, '2026-04-27 00:22:33', '2026-04-27 02:48:21', 3, NULL, 2),
(3693, 1, 1142, 4, 22, NULL, '2026-04-27 00:22:33', '2026-04-27 02:46:11', 3, NULL, 2),
(3694, 5, 1142, 4, 25, NULL, '2026-04-27 00:22:33', '2026-04-27 02:39:36', 3, NULL, 2),
(3695, 1, 1142, 5, 23, NULL, '2026-04-27 00:22:34', '2026-04-27 02:46:38', 3, NULL, 2),
(3696, 4, 1143, 8, 25, NULL, '2026-04-27 00:22:34', '2026-04-27 01:32:49', 3, NULL, 2),
(3697, 2, 1143, 3, 23, NULL, '2026-04-27 00:22:34', '2026-04-27 01:38:47', 3, NULL, 2),
(3698, 4, 1143, 9, 20, NULL, '2026-04-27 00:22:34', '2026-04-27 01:32:19', 3, NULL, 2),
(3699, 3, 1144, 2, 25, NULL, '2026-04-27 00:22:35', '2026-04-27 02:35:02', 3, NULL, 2),
(3700, 2, 1144, 7, 24, NULL, '2026-04-27 00:22:35', '2026-04-27 01:38:13', 3, NULL, 2),
(3701, 2, 1144, 8, 25, NULL, '2026-04-27 00:22:35', '2026-05-08 05:39:20', 3, NULL, 2),
(3702, 1, 1145, 1, 26, NULL, '2026-04-27 00:22:35', '2026-04-27 01:35:02', 3, NULL, 2),
(3703, 4, 1145, 7, 26, NULL, '2026-04-27 00:22:35', '2026-04-27 01:35:44', 3, NULL, 2),
(3704, 1, 1145, 2, 26, NULL, '2026-04-27 00:22:35', '2026-04-27 01:34:45', 3, NULL, 2),
(3705, 3, 1146, 4, 25, NULL, '2026-04-27 00:22:35', '2026-04-27 02:35:29', 3, NULL, 2),
(3706, 4, 1146, 5, 22, NULL, '2026-04-27 00:22:35', '2026-04-27 02:37:15', 3, NULL, 2),
(3707, 3, 1146, 3, 25, NULL, '2026-04-27 00:22:36', '2026-04-27 01:31:35', 3, NULL, 2),
(3708, 2, 1147, 1, 22, NULL, '2026-04-27 00:22:36', '2026-05-08 05:39:01', 3, NULL, 2),
(3709, 2, 1147, 6, 25, NULL, '2026-04-27 00:22:36', '2026-04-27 01:37:30', 3, NULL, 2),
(3710, 4, 1147, 3, 23, NULL, '2026-04-27 00:22:37', '2026-04-27 01:33:12', 3, NULL, 2),
(3711, 5, 1148, 5, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 02:02:11', 3, NULL, 2),
(3712, 1, 1148, 1, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 02:49:29', 3, NULL, 2),
(3713, 5, 1148, 4, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3714, 3, 1149, 3, 22, 'COMMUNICATION SKILLS', '2026-04-27 00:22:37', '2026-04-27 02:00:16', 3, NULL, 2),
(3715, 4, 1149, 3, 24, 'COMMUNICATION SKILLS', '2026-04-27 00:22:37', '2026-04-27 02:00:55', 3, NULL, 2),
(3716, 2, 1149, 9, 22, 'COMMUNICATION SKILLS', '2026-04-27 00:22:37', '2026-04-27 02:01:28', 3, NULL, 2),
(3717, 4, 1150, 4, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 02:52:45', 3, NULL, 2),
(3718, 5, 1150, 1, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3719, 4, 1150, 5, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 02:52:28', 3, NULL, 2),
(3720, 2, 1151, 7, 23, NULL, '2026-04-27 00:22:37', '2026-05-08 05:48:46', 3, NULL, 2),
(3721, 2, 1151, 8, 21, NULL, '2026-04-27 00:22:37', '2026-04-27 02:03:27', 3, NULL, 2),
(3722, 1, 1151, 3, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3723, 3, 1152, 4, 23, NULL, '2026-04-27 00:22:37', '2026-04-28 20:25:17', 3, NULL, 2),
(3724, 3, 1152, 5, 22, NULL, '2026-04-27 00:22:37', '2026-04-28 20:25:42', 3, NULL, 2),
(3725, 4, 1152, 2, 25, NULL, '2026-04-27 00:22:37', '2026-04-27 02:53:13', 3, NULL, 2),
(3726, 2, 1153, 6, 21, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3727, 2, 1153, 5, 21, NULL, '2026-04-27 00:22:37', '2026-05-08 05:30:05', 3, NULL, 2),
(3728, 1, 1153, 2, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 02:49:52', 3, NULL, 2),
(3729, 3, 1154, 5, 25, NULL, '2026-04-27 00:22:37', '2026-04-27 01:53:45', 3, NULL, 2),
(3730, 4, 1154, 1, 22, NULL, '2026-04-27 00:22:37', '2026-05-08 05:43:10', 3, NULL, 2),
(3731, 4, 1154, 2, 22, NULL, '2026-04-27 00:22:37', '2026-05-08 05:43:31', 3, NULL, 2),
(3732, 5, 1155, 9, 21, NULL, '2026-04-27 00:22:37', '2026-05-08 05:42:06', 3, NULL, 2),
(3733, 1, 1155, 1, 20, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2);
INSERT INTO `timetables` (`id`, `day_id`, `subject_id`, `timeslot_id`, `room_id`, `group_name`, `created_at`, `updated_at`, `semester_id`, `teacher_id`, `branch_id`) VALUES
(3734, 1, 1155, 8, 24, NULL, '2026-04-27 00:22:37', '2026-04-27 01:57:48', 3, NULL, 2),
(3735, 3, 1156, 6, 25, NULL, '2026-04-27 00:22:37', '2026-04-27 01:59:04', 3, NULL, 2),
(3736, 1, 1156, 5, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3737, 2, 1156, 4, 23, NULL, '2026-04-27 00:22:37', '2026-04-27 02:23:00', 3, NULL, 2),
(3738, 2, 1157, 2, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3739, 2, 1157, 3, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3740, 3, 1157, 4, 26, NULL, '2026-04-27 00:22:37', '2026-04-27 01:56:03', 3, NULL, 2),
(3741, 5, 1158, 7, 25, NULL, '2026-04-27 00:22:37', '2026-04-27 01:57:02', 3, NULL, 2),
(3742, 5, 1158, 8, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 01:58:36', 3, NULL, 2),
(3743, 1, 1158, 7, 22, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3744, 2, 1159, 7, 21, NULL, '2026-04-27 00:22:37', '2026-04-27 00:22:37', 3, NULL, 2),
(3745, 3, 1159, 2, 21, NULL, '2026-04-27 00:22:38', '2026-04-27 00:22:38', 3, NULL, 2),
(3746, 5, 1159, 6, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 02:31:12', 3, NULL, 2),
(3747, 1, 1170, 8, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 01:02:32', 3, NULL, 2),
(3748, 1, 1170, 9, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 01:03:22', 3, NULL, 2),
(3749, 2, 1170, 7, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 01:03:52', 3, NULL, 2),
(3750, 2, 1171, 10, 24, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 00:22:38', 3, NULL, 2),
(3751, 3, 1171, 1, 21, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 00:37:05', 3, NULL, 2),
(3752, 2, 1171, 9, 24, 'BASIC COMMUNIVATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 00:39:25', 3, NULL, 2),
(3753, 4, 1172, 4, 21, NULL, '2026-04-27 00:22:38', '2026-04-27 00:40:38', 3, NULL, 2),
(3754, 4, 1172, 5, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:40:17', 3, NULL, 2),
(3755, 3, 1172, 3, 21, NULL, '2026-04-27 00:22:38', '2026-04-27 00:43:05', 3, NULL, 2),
(3756, 1, 1173, 6, 22, NULL, '2026-04-27 00:22:38', '2026-04-27 00:36:18', 3, NULL, 2),
(3757, 5, 1173, 8, 21, NULL, '2026-04-27 00:22:38', '2026-04-27 03:08:12', 3, NULL, 2),
(3758, 5, 1173, 6, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 03:07:18', 3, NULL, 2),
(3759, 1, 1174, 7, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 00:38:40', 3, NULL, 2),
(3760, 5, 1174, 2, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 03:04:13', 3, NULL, 2),
(3761, 5, 1174, 3, 26, NULL, '2026-04-27 00:22:38', '2026-04-27 03:04:52', 3, NULL, 2),
(3762, 2, 1175, 7, 25, NULL, '2026-04-27 00:22:38', '2026-04-27 00:35:38', 3, NULL, 2),
(3763, 1, 1175, 4, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 03:02:01', 3, NULL, 2),
(3764, 2, 1175, 6, 22, NULL, '2026-04-27 00:22:38', '2026-04-27 00:22:38', 3, NULL, 2),
(3765, 5, 1176, 1, 22, NULL, '2026-04-27 00:22:38', '2026-04-27 03:05:37', 3, NULL, 2),
(3766, 4, 1176, 1, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:41:47', 3, NULL, 2),
(3767, 5, 1176, 4, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 03:09:11', 3, NULL, 2),
(3768, 2, 1182, 6, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 02:26:06', 3, NULL, 2),
(3769, 5, 1182, 2, 22, NULL, '2026-04-27 00:22:38', '2026-04-27 00:32:23', 3, NULL, 2),
(3770, 5, 1182, 3, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 00:34:00', 3, NULL, 2),
(3771, 3, 1183, 5, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 00:25:31', 3, NULL, 2),
(3772, 2, 1183, 1, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:22:38', 3, NULL, 2),
(3773, 2, 1183, 2, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:27:34', 3, NULL, 2),
(3774, 2, 1184, 5, 25, NULL, '2026-04-27 00:22:38', '2026-04-27 02:22:12', 3, NULL, 2),
(3775, 3, 1184, 3, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 02:25:15', 3, NULL, 2),
(3776, 5, 1184, 1, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:31:53', 3, NULL, 2),
(3777, 3, 1185, 6, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 00:33:07', 3, NULL, 2),
(3778, 2, 1185, 4, 25, NULL, '2026-04-27 00:22:38', '2026-04-27 00:29:19', 3, NULL, 2),
(3779, 3, 1185, 8, 24, NULL, '2026-04-27 00:22:38', '2026-04-27 00:28:00', 3, NULL, 2),
(3780, 3, 1186, 10, 22, NULL, '2026-04-27 00:22:38', '2026-04-27 00:29:36', 3, NULL, 2),
(3781, 3, 1186, 9, 23, NULL, '2026-04-27 00:22:38', '2026-04-27 00:30:00', 3, NULL, 2),
(3782, 5, 1186, 6, 21, NULL, '2026-04-27 00:22:38', '2026-04-27 00:22:38', 3, NULL, 2),
(3783, 3, 1187, 3, 22, 'COMMUNICATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 01:11:58', 3, NULL, 2),
(3784, 4, 1187, 3, 24, 'COMMUNICATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 01:12:29', 3, NULL, 2),
(3785, 2, 1187, 9, 22, 'COMMUNICATION SKILLS', '2026-04-27 00:22:38', '2026-04-27 01:11:12', 3, NULL, 2),
(3786, 2, 1188, 5, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 01:13:05', 3, NULL, 2),
(3787, 1, 1188, 2, 25, NULL, '2026-04-27 00:22:39', '2026-04-27 01:16:27', 3, NULL, 2),
(3788, 4, 1188, 6, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 01:17:33', 3, NULL, 2),
(3789, 4, 1189, 1, 24, NULL, '2026-04-27 00:22:39', '2026-05-08 05:25:15', 3, NULL, 2),
(3790, 2, 1189, 8, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 03:13:39', 3, NULL, 2),
(3791, 4, 1189, 2, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 03:11:37', 3, NULL, 2),
(3792, 5, 1190, 6, 20, NULL, '2026-04-27 00:22:39', '2026-04-27 03:15:41', 3, NULL, 2),
(3793, 2, 1190, 4, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3794, 3, 1190, 6, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3795, 3, 1191, 7, 22, NULL, '2026-04-27 00:22:39', '2026-04-28 20:34:07', 3, NULL, 2),
(3796, 3, 1191, 8, 22, NULL, '2026-04-27 00:22:39', '2026-04-28 20:34:30', 3, NULL, 2),
(3797, 4, 1191, 4, 25, NULL, '2026-04-27 00:22:39', '2026-04-27 03:12:15', 3, NULL, 2),
(3798, 1, 1192, 3, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 01:19:57', 3, NULL, 2),
(3799, 5, 1192, 2, 20, NULL, '2026-04-27 00:22:39', '2026-04-27 03:14:31', 3, NULL, 2),
(3800, 5, 1192, 3, 20, NULL, '2026-04-27 00:22:39', '2026-05-08 05:24:19', 3, NULL, 2),
(3801, 2, 1193, 1, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3802, 5, 1193, 5, 23, NULL, '2026-04-27 00:22:39', '2026-05-08 05:21:19', 3, NULL, 2),
(3803, 5, 1193, 4, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3804, 5, 1194, 1, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 02:28:13', 3, NULL, 2),
(3805, 2, 1194, 3, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 01:06:37', 3, NULL, 2),
(3806, 1, 1194, 4, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3807, 2, 1195, 8, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 01:09:32', 3, NULL, 2),
(3808, 1, 1195, 7, 21, NULL, '2026-04-27 00:22:39', '2026-05-08 05:51:59', 3, NULL, 2),
(3809, 1, 1195, 8, 20, NULL, '2026-04-27 00:22:39', '2026-05-08 05:52:33', 3, NULL, 2),
(3810, 5, 1196, 2, 25, NULL, '2026-04-27 00:22:39', '2026-04-27 02:21:38', 3, NULL, 2),
(3811, 3, 1196, 1, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 01:08:32', 3, NULL, 2),
(3812, 5, 1196, 3, 23, NULL, '2026-04-27 00:22:39', '2026-05-08 05:20:24', 3, NULL, 2),
(3813, 2, 1197, 2, 25, NULL, '2026-04-27 00:22:39', '2026-04-27 01:04:33', 3, NULL, 2),
(3814, 1, 1197, 5, 25, NULL, '2026-04-27 00:22:39', '2026-04-27 01:04:59', 3, NULL, 2),
(3815, 3, 1197, 2, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3816, 4, 1198, 4, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3817, 1, 1198, 3, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 02:25:47', 3, NULL, 2),
(3818, 3, 1198, 2, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 00:50:31', 3, NULL, 2),
(3819, 3, 1199, 4, 22, NULL, '2026-04-27 00:22:39', '2026-04-27 00:48:31', 3, NULL, 2),
(3820, 2, 1199, 2, 24, NULL, '2026-04-27 00:22:39', '2026-04-27 00:46:00', 3, NULL, 2),
(3821, 3, 1199, 5, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 00:46:59', 3, NULL, 2),
(3822, 4, 1200, 5, 21, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3823, 1, 1200, 7, 23, NULL, '2026-04-27 00:22:39', '2026-04-27 00:22:39', 3, NULL, 2),
(3824, 3, 1200, 1, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 00:49:09', 3, NULL, 2),
(3825, 1, 1201, 1, 25, NULL, '2026-04-27 00:22:40', '2026-04-27 00:34:29', 3, NULL, 2),
(3826, 2, 1201, 5, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 00:51:09', 3, NULL, 2),
(3827, 1, 1201, 2, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 00:44:13', 3, NULL, 2),
(3828, 2, 1202, 4, 22, NULL, '2026-04-27 00:22:40', '2026-04-27 00:22:40', 3, NULL, 2),
(3829, 1, 1202, 6, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 00:47:59', 3, NULL, 2),
(3830, 2, 1202, 3, 22, NULL, '2026-04-27 00:22:40', '2026-04-27 00:22:40', 3, NULL, 2),
(3831, 2, 1206, 1, 25, NULL, '2026-04-27 00:22:40', '2026-04-27 02:57:38', 3, NULL, 2),
(3832, 5, 1206, 6, 22, NULL, '2026-04-27 00:22:40', '2026-04-27 00:57:28', 3, NULL, 2),
(3833, 2, 1206, 2, 22, NULL, '2026-04-27 00:22:40', '2026-04-27 00:58:16', 3, NULL, 2),
(3834, 1, 1207, 8, 21, NULL, '2026-04-27 00:22:40', '2026-04-27 02:56:02', 3, NULL, 2),
(3835, 1, 1207, 9, 21, NULL, '2026-04-27 00:22:40', '2026-05-08 05:15:58', 3, NULL, 2),
(3836, 5, 1207, 7, 20, NULL, '2026-04-27 00:22:40', '2026-04-27 02:59:56', 3, NULL, 2),
(3837, 5, 1208, 8, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 00:56:27', 3, NULL, 2),
(3838, 5, 1208, 9, 22, NULL, '2026-04-27 00:22:40', '2026-04-27 00:58:43', 3, NULL, 2),
(3839, 1, 1208, 6, 21, NULL, '2026-04-27 00:22:40', '2026-04-27 00:56:57', 3, NULL, 2),
(3840, 3, 1209, 7, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 01:28:29', 3, NULL, 2),
(3841, 1, 1209, 9, 23, NULL, '2026-04-27 00:22:40', '2026-04-28 12:04:56', 3, NULL, 2),
(3842, 1, 1209, 8, 23, NULL, '2026-04-27 00:22:40', '2026-04-28 12:04:19', 3, NULL, 2),
(3843, 3, 1210, 3, 24, NULL, '2026-04-27 00:22:40', '2026-04-28 12:06:53', 3, NULL, 2),
(3844, 4, 1210, 6, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 00:22:40', 3, NULL, 2),
(3845, 4, 1210, 7, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 01:21:37', 3, NULL, 2),
(3846, 3, 1211, 4, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 01:20:49', 3, NULL, 2),
(3847, 5, 1211, 8, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 01:26:41', 3, NULL, 2),
(3848, 4, 1211, 5, 25, NULL, '2026-04-27 00:22:40', '2026-04-27 01:25:22', 3, NULL, 2),
(3849, 4, 1212, 9, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 01:23:59', 3, NULL, 2),
(3850, 3, 1212, 6, 23, NULL, '2026-04-27 00:22:40', '2026-04-27 01:28:51', 3, NULL, 2),
(3851, 4, 1212, 8, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 01:22:07', 3, NULL, 2),
(3852, 5, 1213, 7, 21, NULL, '2026-04-27 00:22:40', '2026-04-27 00:22:40', 3, NULL, 2),
(3853, 5, 1213, 6, 25, NULL, '2026-04-27 00:22:40', '2026-04-27 01:25:54', 3, NULL, 2),
(3854, 3, 1213, 1, 22, NULL, '2026-04-27 00:22:40', '2026-04-28 12:05:52', 3, NULL, 2),
(3855, 2, 1214, 5, 24, NULL, '2026-04-27 00:22:40', '2026-04-27 00:22:40', 3, NULL, 2),
(3856, 1, 1214, 4, 25, NULL, '2026-04-27 00:22:40', '2026-04-27 00:52:01', 3, NULL, 2),
(3857, 1, 1214, 2, 21, NULL, '2026-04-27 00:22:41', '2026-04-27 02:27:31', 3, NULL, 2),
(3858, 2, 1215, 6, 20, NULL, '2026-04-27 00:22:41', '2026-04-27 02:26:52', 3, NULL, 2),
(3859, 1, 1215, 6, 25, NULL, '2026-04-27 00:22:41', '2026-04-27 02:55:29', 3, NULL, 2),
(3860, 1, 1215, 7, 25, NULL, '2026-04-27 00:22:41', '2026-04-27 00:54:38', 3, NULL, 2),
(3861, 4, 1216, 3, 21, NULL, '2026-04-27 00:22:41', '2026-04-27 00:54:08', 3, NULL, 2),
(3862, 4, 1216, 2, 23, NULL, '2026-04-27 00:22:41', '2026-04-27 00:22:41', 3, NULL, 2),
(3863, 1, 1216, 1, 24, NULL, '2026-04-27 00:22:41', '2026-04-27 00:52:26', 3, NULL, 2),
(3864, 2, 1217, 3, 25, NULL, '2026-04-27 00:22:41', '2026-04-27 02:20:20', 3, NULL, 2),
(3865, 4, 1217, 5, 24, NULL, '2026-04-27 00:22:41', '2026-04-27 00:53:00', 3, NULL, 2),
(3866, 4, 1217, 4, 24, NULL, '2026-04-27 00:22:41', '2026-04-27 00:53:51', 3, NULL, 2),
(3867, 1, 1012, 5, 27, NULL, '2026-04-27 12:04:46', '2026-04-28 00:34:07', 3, NULL, 1),
(3868, 5, 1012, 3, 27, NULL, '2026-04-27 12:04:47', '2026-04-27 14:48:33', 3, NULL, 1),
(3869, 2, 1012, 8, 27, NULL, '2026-04-27 12:04:48', '2026-05-03 19:44:20', 3, NULL, 1),
(3870, 3, 1015, 5, 27, NULL, '2026-04-27 12:04:48', '2026-04-27 14:28:31', 3, NULL, 1),
(3871, 4, 1015, 7, 27, NULL, '2026-04-27 12:04:50', '2026-04-27 14:24:21', 3, NULL, 1),
(3872, 3, 1015, 4, 27, NULL, '2026-04-27 12:04:51', '2026-04-27 14:28:05', 3, NULL, 1),
(3873, 5, 1017, 1, 27, NULL, '2026-04-27 12:04:51', '2026-04-27 14:22:52', 3, NULL, 1),
(3874, 4, 1017, 1, 27, NULL, '2026-04-27 12:04:52', '2026-04-27 14:17:51', 3, NULL, 1),
(3875, 4, 1017, 2, 27, NULL, '2026-04-27 12:04:54', '2026-04-27 14:21:25', 3, NULL, 1),
(3876, 5, 1019, 6, 27, NULL, '2026-04-27 12:04:54', '2026-04-28 01:02:53', 3, NULL, 1),
(3877, 3, 1019, 7, 27, NULL, '2026-04-27 12:04:54', '2026-04-28 01:17:18', 3, NULL, 1),
(3878, 5, 1019, 7, 27, NULL, '2026-04-27 12:04:55', '2026-04-28 01:02:08', 3, NULL, 1),
(3879, 3, 1020, 2, 27, NULL, '2026-04-27 12:04:55', '2026-05-03 19:38:53', 3, NULL, 1),
(3880, 3, 1020, 1, 27, NULL, '2026-04-27 12:04:56', '2026-05-03 19:38:33', 3, NULL, 1),
(3881, 2, 1020, 1, 27, NULL, '2026-04-27 12:04:57', '2026-05-03 19:54:50', 3, NULL, 1),
(3882, 2, 1025, 6, 27, NULL, '2026-04-27 12:04:58', '2026-04-28 01:19:08', 3, NULL, 1),
(3883, 2, 1025, 5, 27, NULL, '2026-04-27 12:04:59', '2026-04-30 02:41:32', 3, NULL, 1),
(3884, 3, 1025, 3, 27, NULL, '2026-04-27 12:05:01', '2026-04-27 14:04:50', 3, NULL, 1),
(3885, 1, 1026, 4, 27, NULL, '2026-04-27 12:05:02', '2026-04-27 14:04:50', 3, NULL, 1),
(3886, 6, 1026, 2, 27, NULL, '2026-04-27 12:05:03', '2026-04-28 00:41:06', 3, NULL, 1),
(3887, 1, 1026, 6, 27, NULL, '2026-04-27 12:05:05', '2026-04-28 01:08:20', 3, NULL, 1),
(3888, 6, 1030, 4, 9, NULL, '2026-04-27 12:05:06', '2026-05-06 01:19:33', 3, NULL, 1),
(3889, 3, 1030, 9, 7, NULL, '2026-04-27 12:05:07', '2026-05-06 01:48:42', 3, NULL, 1),
(3890, 7, 1030, 1, 11, NULL, '2026-04-27 12:05:09', '2026-05-06 01:23:29', 3, NULL, 1),
(3891, 5, 1024, 2, 15, NULL, '2026-04-28 00:28:02', '2026-04-28 00:28:02', 3, NULL, 1),
(3892, 5, 1024, 1, 14, NULL, '2026-04-28 00:28:04', '2026-04-28 00:37:17', 3, NULL, 1),
(3893, 6, 1024, 6, 8, NULL, '2026-04-28 00:28:06', '2026-05-04 03:54:42', 3, NULL, 1),
(3894, 2, 1031, 1, 13, NULL, '2026-04-28 00:28:07', '2026-05-04 03:58:38', 3, NULL, 1),
(3895, 2, 1031, 2, 15, NULL, '2026-04-28 00:28:08', '2026-05-04 03:59:47', 3, NULL, 1),
(3896, 3, 1031, 6, 13, NULL, '2026-04-28 00:28:11', '2026-04-28 01:40:52', 3, NULL, 1),
(3897, 1, 1013, 1, 16, NULL, '2026-05-03 09:54:20', '2026-05-03 11:56:49', 3, NULL, 1),
(3898, 6, 1013, 5, 2, NULL, '2026-05-03 09:54:20', '2026-05-03 09:54:20', 3, NULL, 1),
(3899, 6, 1013, 6, 1, NULL, '2026-05-03 09:54:23', '2026-05-03 11:58:49', 3, NULL, 1),
(3900, 2, 1018, 8, 3, NULL, '2026-05-03 09:54:23', '2026-05-04 03:46:04', 3, NULL, 1),
(3901, 3, 1018, 4, 11, NULL, '2026-05-03 09:54:23', '2026-05-03 23:44:56', 3, NULL, 1),
(3902, 3, 1018, 3, 13, NULL, '2026-05-03 09:54:25', '2026-05-03 12:06:16', 3, NULL, 1),
(3903, 5, 1218, 2, 4, NULL, '2026-05-04 20:31:45', '2026-05-04 21:47:33', 3, NULL, 1),
(3904, 5, 1218, 1, 7, NULL, '2026-05-04 20:31:46', '2026-05-04 21:47:10', 3, NULL, 1),
(3905, 1, 1218, 1, 12, NULL, '2026-05-04 20:31:47', '2026-05-04 21:45:27', 3, NULL, 1),
(3906, 4, 1219, 10, 9, NULL, '2026-05-18 03:29:33', '2026-05-18 03:44:36', 3, NULL, 1),
(3907, 4, 1219, 9, 9, NULL, '2026-05-18 03:29:33', '2026-05-18 03:44:02', 3, NULL, 1),
(3908, 2, 1219, 1, 14, NULL, '2026-05-18 03:29:35', '2026-05-18 03:38:48', 3, NULL, 1);

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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `branches`
--
ALTER TABLE `branches`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buildings`
--
ALTER TABLE `buildings`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `courses_deptid_foreign` (`deptId`),
  ADD KEY `courses_building_id_foreign` (`building_id`);

--
-- Indexes for table `course_rooms`
--
ALTER TABLE `course_rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_rooms_course_id_foreign` (`course_id`),
  ADD KEY `course_rooms_room_id_foreign` (`room_id`),
  ADD KEY `course_rooms_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `cr_info`
--
ALTER TABLE `cr_info`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `cr_info_email_unique` (`email`),
  ADD KEY `cr_info_course_id_foreign` (`course_id`),
  ADD KEY `cr_info_semester_id_foreign` (`semester_id`),
  ADD KEY `cr_info_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `days`
--
ALTER TABLE `days`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`);

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
-- Indexes for table `loggins`
--
ALTER TABLE `loggins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Indexes for table `rooms`
--
ALTER TABLE `rooms`
  ADD PRIMARY KEY (`id`),
  ADD KEY `rooms_building_id_foreign` (`building_id`),
  ADD KEY `rooms_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `semesters`
--
ALTER TABLE `semesters`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `subjects`
--
ALTER TABLE `subjects`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subjects_teacher_id_foreign` (`teacher_id`),
  ADD KEY `subjects_course_id_foreign` (`course_id`),
  ADD KEY `subjects_semester_id_foreign` (`semester_id`),
  ADD KEY `subjects_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `system_timetables`
--
ALTER TABLE `system_timetables`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `teachers`
--
ALTER TABLE `teachers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `teachers_branch_id_foreign` (`branch_id`),
  ADD KEY `teachers_deptid_foreign` (`deptId`);

--
-- Indexes for table `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `teacher_attendances_teacher_id_timetable_id_date_unique` (`teacher_id`,`timetable_id`,`date`),
  ADD KEY `teacher_attendances_subject_id_foreign` (`subject_id`),
  ADD KEY `teacher_attendances_timetable_id_foreign` (`timetable_id`),
  ADD KEY `teacher_attendances_course_id_foreign` (`course_id`);

--
-- Indexes for table `timeslots`
--
ALTER TABLE `timeslots`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `timetables`
--
ALTER TABLE `timetables`
  ADD PRIMARY KEY (`id`),
  ADD KEY `timetables_day_id_foreign` (`day_id`),
  ADD KEY `timetables_subject_id_foreign` (`subject_id`),
  ADD KEY `timetables_timeslot_id_foreign` (`timeslot_id`),
  ADD KEY `timetables_room_id_foreign` (`room_id`),
  ADD KEY `timetables_semester_id_foreign` (`semester_id`),
  ADD KEY `timetables_teacher_id_foreign` (`teacher_id`),
  ADD KEY `timetables_branch_id_foreign` (`branch_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `branches`
--
ALTER TABLE `branches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `buildings`
--
ALTER TABLE `buildings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `course_rooms`
--
ALTER TABLE `course_rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=105;

--
-- AUTO_INCREMENT for table `cr_info`
--
ALTER TABLE `cr_info`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `days`
--
ALTER TABLE `days`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `departments`
--
ALTER TABLE `departments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loggins`
--
ALTER TABLE `loggins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=58;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=56;

--
-- AUTO_INCREMENT for table `rooms`
--
ALTER TABLE `rooms`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `semesters`
--
ALTER TABLE `semesters`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `subjects`
--
ALTER TABLE `subjects`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1220;

--
-- AUTO_INCREMENT for table `system_timetables`
--
ALTER TABLE `system_timetables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `teachers`
--
ALTER TABLE `teachers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=124;

--
-- AUTO_INCREMENT for table `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `timeslots`
--
ALTER TABLE `timeslots`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `timetables`
--
ALTER TABLE `timetables`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3909;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `courses_deptid_foreign` FOREIGN KEY (`deptId`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `course_rooms`
--
ALTER TABLE `course_rooms`
  ADD CONSTRAINT `course_rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_rooms_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_rooms_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `cr_info`
--
ALTER TABLE `cr_info`
  ADD CONSTRAINT `cr_info_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cr_info_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `cr_info_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `rooms`
--
ALTER TABLE `rooms`
  ADD CONSTRAINT `rooms_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `rooms_building_id_foreign` FOREIGN KEY (`building_id`) REFERENCES `buildings` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `subjects`
--
ALTER TABLE `subjects`
  ADD CONSTRAINT `subjects_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `subjects_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teachers`
--
ALTER TABLE `teachers`
  ADD CONSTRAINT `teachers_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teachers_deptid_foreign` FOREIGN KEY (`deptId`) REFERENCES `departments` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `teacher_attendances`
--
ALTER TABLE `teacher_attendances`
  ADD CONSTRAINT `teacher_attendances_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `teacher_attendances_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_attendances_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `teacher_attendances_timetable_id_foreign` FOREIGN KEY (`timetable_id`) REFERENCES `timetables` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `timetables`
--
ALTER TABLE `timetables`
  ADD CONSTRAINT `timetables_branch_id_foreign` FOREIGN KEY (`branch_id`) REFERENCES `branches` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_day_id_foreign` FOREIGN KEY (`day_id`) REFERENCES `days` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_room_id_foreign` FOREIGN KEY (`room_id`) REFERENCES `rooms` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_semester_id_foreign` FOREIGN KEY (`semester_id`) REFERENCES `semesters` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_subject_id_foreign` FOREIGN KEY (`subject_id`) REFERENCES `subjects` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_teacher_id_foreign` FOREIGN KEY (`teacher_id`) REFERENCES `teachers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `timetables_timeslot_id_foreign` FOREIGN KEY (`timeslot_id`) REFERENCES `timeslots` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
