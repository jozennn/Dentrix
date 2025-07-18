-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 08, 2025 at 04:22 PM
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
-- Database: `delubyo2`
--

-- --------------------------------------------------------

--
-- Table structure for table `about`
--

CREATE TABLE `about` (
  `about_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `about`
--

INSERT INTO `about` (`about_id`, `title`, `content`, `image`) VALUES
(1, 'ABOUT US ', 'Dr. Henry Cheung, a graduate of Centro Escolar University, has been delivering high-quality dental care since 2009. With over a decade of experience, he has built a reputation for his commitment to patient well-being and comprehensive dental services.\r\nFirst established in 2009, the clinic was originally located in Mahabang Parang, Angono, Rizal. In 2013, it moved to Dalig, Antipolo, Rizal, becoming a trusted destination for families seeking professional dental care.', NULL),
(1, 'ABOUT US ', 'Dr. Henry Cheung, a graduate of Centro Escolar University, has been delivering high-quality dental care since 2009. With over a decade of experience, he has built a reputation for his commitment to patient well-being and comprehensive dental services.\r\nFirst established in 2009, the clinic was originally located in Mahabang Parang, Angono, Rizal. In 2013, it moved to Dalig, Antipolo, Rizal, becoming a trusted destination for families seeking professional dental care.', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `payment_method` varchar(20) NOT NULL,
  `services` text NOT NULL,
  `date` varchar(50) NOT NULL,
  `time` varchar(50) NOT NULL,
  `status` enum('pending','booked','declined') DEFAULT 'pending',
  `time_slot_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `name`, `payment_method`, `services`, `date`, `time`, `status`, `time_slot_id`, `created_at`, `updated_at`) VALUES
(5, 'Rey', '', '{\"Tooth Extraction\":1}', '2025-05-23', '17:00:00', 'booked', 0, '2025-05-08 11:28:40', '2025-05-08 11:28:40'),
(6, 'Roy', 'GCash', '{\"Dental Check up\":1}', '2025-05-23', '14:00:00', 'booked', 0, '2025-05-08 11:50:53', '2025-05-08 11:50:53'),
(7, 'Justine Copa', '', '{\"Root Canal\":1}', '2025-05-27', '08:00:00', 'booked', 0, '2025-05-08 12:23:22', '2025-05-08 12:23:22'),
(8, 'Justine Copa', 'gcash', '{\"Root Canal\":1}', '2025-05-16', '15:00:00', 'booked', 0, '2025-05-08 12:34:09', '2025-05-08 12:34:09'),
(9, 'Justine Daniele Salamero', 'gcash', '{\"Tooth Restoration (Pasta)\":1}', '2025-05-23', '13:00:00', 'booked', 0, '2025-05-08 13:10:26', '2025-05-08 13:10:26'),
(10, 'JUSTINE DANIELE', 'in person', '\"{\\\"Dental Check up\\\":1}\"', '2025-05-27', '16:00:00', 'booked', 0, '2025-05-08 13:56:34', '2025-05-08 13:56:34'),
(11, 'JUSTINE DANIELE', 'in person', '\"{\\\"Dental Bridges\\\":1}\"', '2025-05-16', '11:00:00', 'booked', 0, '2025-05-08 14:19:20', '2025-05-08 14:19:20');

-- --------------------------------------------------------

--
-- Table structure for table `appointments_services`
--

CREATE TABLE `appointments_services` (
  `appointment_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `audit_actions`
--

CREATE TABLE `audit_actions` (
  `action_id` int(11) NOT NULL,
  `action_name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_actions`
--

INSERT INTO `audit_actions` (`action_id`, `action_name`) VALUES
(3, 'Create'),
(5, 'Delete'),
(1, 'Login'),
(2, 'Logout'),
(4, 'Update'),
(6, 'View'),
(3, 'Create'),
(5, 'Delete'),
(1, 'Login'),
(2, 'Logout'),
(4, 'Update'),
(6, 'View');

-- --------------------------------------------------------

--
-- Table structure for table `audit_trail`
--

CREATE TABLE `audit_trail` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `action_id` int(11) NOT NULL,
  `details` text DEFAULT NULL,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_trail`
--

INSERT INTO `audit_trail` (`id`, `user_id`, `action_id`, `details`, `created_at`) VALUES
(0, 0, 2, 'User logged out with Email: jdlarkin20@gmail.com', '2025-05-05 11:55:27'),
(0, 1, 1, 'User logged in with Email: justinecopa08@gmail.com', '2025-05-05 11:55:37'),
(0, 1, 4, 'Edited account with ID: 0, Name: KOPAL COPA SALAMERO', '2025-05-05 12:01:23'),
(0, 3, 2, 'User logged out with Email: kingkurtu@gmail.com', '2025-05-05 17:30:14'),
(0, 2, 1, 'User logged in with Email: kingkurt06unidad6@gmail.com', '2025-05-05 17:31:28'),
(0, 2, 1, 'User logged in with Email: kingkurt06unidad6@gmail.com', '2025-05-05 17:53:25'),
(0, 0, 1, 'User logged in with Email: jdlarkin20@gmail.com', '2025-05-05 21:30:24'),
(0, 0, 1, 'User logged in with Email: jdlarkin20@gmail.com', '2025-05-05 23:10:37'),
(0, 28, 1, 'User logged in with Email: lamefofu@dreamclarify.org', '2025-05-06 13:53:59'),
(0, 28, 2, 'User logged out with Email: lamefofu@dreamclarify.org', '2025-05-06 13:54:50'),
(0, 4, 1, 'User logged in with Email: gebok26185@javbing.com', '2025-05-06 16:05:56'),
(0, 4, 2, 'User logged out with Email: gebok26185@javbing.com', '2025-05-06 16:06:35'),
(0, 2, 1, 'User logged in with Email: kingkurt06unidad6@gmail.com', '2025-05-06 16:06:58'),
(0, 52, 1, 'User logged in with Email: jdlarkin20@gmail.com', '2025-05-08 17:30:20');

-- --------------------------------------------------------

--
-- Table structure for table `carousel`
--

CREATE TABLE `carousel` (
  `carousel_id` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `order` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `carousel`
--

INSERT INTO `carousel` (`carousel_id`, `image`, `title`, `description`, `order`) VALUES
(6, 'img3.png', 'slider 1', 'for informatio', 0);

-- --------------------------------------------------------

--
-- Table structure for table `inquiries`
--

CREATE TABLE `inquiries` (
  `inquiry_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','replied') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `inquiries`
--

INSERT INTO `inquiries` (`inquiry_id`, `name`, `email`, `phone`, `message`, `created_at`, `status`) VALUES
(1, 'chandra mae medina micole', 'kingkurt06unidad6@gmail.com', '098445541425', 'asds', '2025-05-03 15:09:11', 'pending'),
(2, '', '', NULL, 'dskfks', '2025-05-06 08:04:17', 'pending'),
(3, '', '', NULL, 'kupal kaba boss?', '2025-05-06 08:06:16', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `patients`
--

CREATE TABLE `patients` (
  `patient_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `schedule` date NOT NULL,
  `time_slot` time NOT NULL,
  `payment_method` varchar(100) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `created_at` datetime(6) DEFAULT current_timestamp(6),
  `gcash_name` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `services` text DEFAULT NULL,
  `time` time DEFAULT NULL,
  `gcash_qr` varchar(255) DEFAULT NULL,
  `date` date DEFAULT NULL,
  `payment_method` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `created_at`, `gcash_name`, `amount`, `services`, `time`, `gcash_qr`, `date`, `payment_method`) VALUES
(18, NULL, 'Justine Copa', 1300.00, '{\"Tooth Restoration (Pasta)\":1}', '16:00:00', '123134', '2025-05-23', ''),
(20, '2025-05-08 20:01:44.832173', '', 500.00, '{\"Tooth Restoration (Pasta)\":1}', '15:00:00', '32425345', '2025-05-29', 'GCash'),
(31, '2025-05-08 21:57:23.570098', 'JUSTINE DANIELE', 500.00, '\"{\\\"Root Canal\\\":1,\\\"Dental Bridges\\\":1}\"', '08:00:00', '464656', '2025-05-23', 'in person');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `review_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `rating` int(1) NOT NULL,
  `review` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`review_id`, `user_id`, `rating`, `review`, `created_at`) VALUES
(2, 0, 5, 'sdasdsad', '2025-05-06 07:06:00'),
(3, 0, 5, 'lking', '2025-05-06 07:20:29');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `price`, `image`) VALUES
(0, 'Dentures', 5000.00, 'Dentures.png'),
(1, 'Oral Prophylaxis', 2800.00, 'oral.png'),
(2, 'Tooth Extraction', 1000.00, 'tooth.jpg'),
(3, 'Dentures', 20000.00, 'dentures.jpg'),
(4, 'Root Canal', 7000.00, 'root.png'),
(5, 'Tooth Restoration (Pasta)', 800.00, 'pasta.png'),
(6, 'Dental Bridges', 18000.00, 'bridges.png'),
(7, 'Dental Check up', 700.00, 'check.png');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `otp_enabled` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `otp_enabled`) VALUES
(1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `time_slots`
--

CREATE TABLE `time_slots` (
  `id` int(11) NOT NULL,
  `date` date NOT NULL,
  `time_slot` time NOT NULL,
  `status` enum('available','pending','booked') DEFAULT 'available'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `time_slots`
--

INSERT INTO `time_slots` (`id`, `date`, `time_slot`, `status`) VALUES
(0, '2025-05-08', '10:00:00', 'booked'),
(0, '2025-05-02', '15:00:00', 'booked'),
(0, '2025-05-09', '11:00:00', 'booked'),
(0, '2025-05-14', '14:00:00', 'booked'),
(0, '2025-05-02', '13:00:00', 'booked'),
(0, '2025-05-09', '10:00:00', 'booked'),
(0, '2025-05-09', '13:00:00', 'booked'),
(0, '2025-05-09', '09:00:00', 'booked'),
(0, '2025-05-16', '11:00:00', 'booked'),
(0, '2025-05-16', '16:00:00', 'booked'),
(0, '2025-05-23', '14:00:00', 'booked'),
(0, '2025-05-30', '09:00:00', 'booked'),
(0, '2025-05-30', '17:00:00', 'booked'),
(0, '2025-05-23', '17:00:00', 'booked'),
(0, '2025-05-23', '16:00:00', 'booked'),
(0, '2025-05-29', '15:00:00', 'booked'),
(0, '2025-05-27', '08:00:00', 'booked'),
(0, '2025-05-15', '16:00:00', 'booked'),
(0, '2025-05-19', '15:00:00', 'booked'),
(0, '2025-05-16', '15:00:00', 'booked'),
(0, '2025-05-22', '09:00:00', 'booked'),
(0, '2025-05-15', '15:00:00', 'booked'),
(0, '2025-05-22', '15:00:00', 'booked'),
(0, '2025-05-23', '15:00:00', 'booked'),
(0, '2025-05-23', '13:00:00', 'booked'),
(0, '2025-05-21', '15:00:00', 'booked'),
(0, '2025-05-20', '13:00:00', 'booked'),
(0, '2025-05-12', '16:00:00', 'booked'),
(0, '2025-05-22', '11:00:00', 'booked'),
(0, '2025-05-15', '17:00:00', 'booked'),
(0, '2025-05-27', '16:00:00', 'booked'),
(0, '2025-05-23', '08:00:00', 'booked'),
(0, '2025-05-16', '14:00:00', 'booked');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(20) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `verify_status` enum('Inactive','Active') DEFAULT 'Inactive',
  `type` enum('admin','customer') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `password`, `otp`, `verify_status`, `type`, `created_at`) VALUES
(1, 'JUSTINE DANIELE COPA SALAMERO', 'justinecopa08@gmail.com', '09955351627', '$2y$10$PINJ1AKsDOx2azShdXr5qOIvb3zAcAygRV5.0UnBZuN3vYWBbWPPS', NULL, 'Active', 'admin', '2025-05-05 02:11:11'),
(2, 'chandra mae medina micole', 'kingkurt06unidad6@gmail.com', '098445541425', '$2y$10$xnFAHpvJRK0QMWihPZNp9.Y5BZhnD99rS7l7HrFONblCVbmjWQEjS', NULL, 'Active', 'admin', '2025-05-03 15:01:04'),
(3, 'Daniel Zarandin', 'hakdog69005@gmail.com', '69696969', '$2y$10$J65wpTYdiUhC7nZULQHZvecOncBEXPR5JfaGI/NAguluQ7ePgll1y', NULL, 'Active', 'customer', '2025-05-04 13:44:24'),
(4, 'Taylor Swift', 'gebok26185@javbing.com', '09293283223', '$2y$10$bPCxe.EvJ6GJ0/wnOjp9eug6kzZy0wj6Zmj0WbNr9yWP227lTVwvS', NULL, 'Active', 'customer', '2025-05-06 04:50:11'),
(5, 'Dwayne Johnson', 'gagey31388@javbing.com', '0977236263232', '$2y$10$pwIffY9k/jFBzyFgJ4hfT.75BkXYSBfX8Gk5ZBLlji6chDtRQUZQS', NULL, 'Active', 'customer', '2025-05-06 04:54:11'),
(6, 'Beyoncé', 'xalek68535@nutrv.com', '09872362323', '$2y$10$/fj/9fMemn1UqOKTL/lleOv3QKK/W4e/SOp8QoY8SocaQqd5EhlSK', NULL, 'Active', 'customer', '2025-05-06 04:55:23'),
(7, 'Tom Cruise', 'bikid34850@nutrv.com', '09238237232', '$2y$10$O7un08gPY3Z1.zroYbWSZOyve1cOMI2IFY2G/Ngo3Kn2K1Ybn51Ea', NULL, 'Active', 'customer', '2025-05-06 04:56:33'),
(8, 'Kim Kardashian', 'demorac759@javbing.com', '09293923232', '$2y$10$8PUx7DVnxTmqPgLlWCDu3uptFZuohW2Yu7iE13NRgHOXSQCkDN2Ru', NULL, 'Active', 'customer', '2025-05-06 04:57:50'),
(9, 'Lionel Messi', 'viyikap366@javbing.com', '0923232232', '$2y$10$MnVCQfjUYojn2a7BAv30/.yu86dq8X1aC/H2YmtwAPFSGBfUlIBce', NULL, 'Active', 'customer', '2025-05-06 04:59:16'),
(10, 'Rihanna', 'vejebew102@nutrv.com', '09923243435', '$2y$10$fMoMcdFE8vrEnGPEwGQ5DuN.ZIm/hsq.WOTuss6QfMpIxfb11hE8W', NULL, 'Active', 'customer', '2025-05-06 05:00:23'),
(11, 'Cristiano Ronaldo', 'firaki5146@javbing.com', '09232131223', '$2y$10$9NqBGrNFWf6jKFBXtGiuRutzm1r4KIJyTTib9ccs6vLarrwLjQ0.O', NULL, 'Active', 'customer', '2025-05-06 05:01:32'),
(12, 'Jennifer Lawrence', 'rokahi9755@javbing.com', '09232343432', '$2y$10$883nDa/SNqVyDOsSWR0Gv.HtZ9tiU9xVgiJ.LVx95yQNa1tpJDBRm', NULL, 'Active', 'customer', '2025-05-06 05:02:34'),
(13, 'LeBron James', 'yiwod12532@nutrv.com', '09232434343', '$2y$10$nC5JLPm4R5HxP9ewmcE83OYiPbvjWvsALKudmUWnxMNC3ZjiAW.jK', NULL, 'Active', 'customer', '2025-05-06 05:03:33'),
(14, 'Billie Eilish', 'xerod10154@nutrv.com', '09876345465', '$2y$10$TkKqI2SGluh.36Vd0GCCcOr5gC37dy5JELH4/3GVyh0.mpAAFDjsC', NULL, 'Active', 'customer', '2025-05-06 05:04:39'),
(15, '800796', 'jivaye5609@nutrv.com', '098765432112', '$2y$10$XOfOmDH/.oPTEV9oXNXAS.ntor1kH3zwAFU7euI.0DGVvHmZPpa42', NULL, 'Active', 'customer', '2025-05-06 05:05:53'),
(16, 'Selena Gomez', 'locolew779@nutrv.com', '098765456786', '$2y$10$K7PTs5BOygLTwHWJrsX6X.OLFMwUOXtEOdvxAiSu.E.9kkXP/QFjC', NULL, 'Active', 'customer', '2025-05-06 05:07:00'),
(17, 'Keanu Reeves', 'yodat16499@nutrv.com', '0912345666', '$2y$10$b5Pz3xRzDHx1H7Ac4.DJXuGrFL47mTCkQHl60fWATi1v/JLLR/AM6', NULL, 'Active', 'customer', '2025-05-06 05:08:03'),
(18, 'Sharon Cuneta', 'lopiho9420@nutrv.com', '091234234', '$2y$10$OKDeIT3zx2ta5ITvmR2W1.BxaAlJBIEvVxuvculggJn4Uc7OAMPuO', NULL, 'Active', 'customer', '2025-05-06 05:09:37'),
(19, 'Enchong Dee', 'loxolah507@javbing.com', '09123423424', '$2y$10$zmS9X1sp5aLjqxi.fB7hVe3J042jEmdJTJXQdckByXolo.RFzP4fO', NULL, 'Active', 'customer', '2025-05-06 05:11:20'),
(20, 'Maris Racal', 'ratinuno@azuretechtalk.net', '0912331233', '$2y$10$S3KqX4xeFQx2OhwWi/3fEu98ieKpv3KV5AxoN8GnmA1fVC0UZHUBG', NULL, 'Active', 'customer', '2025-05-06 05:13:43'),
(21, 'Rico Blanco', 'sowasoje@asciibinder.net', '0912313232', '$2y$10$CfKPt1MyLpMjkNH9o9cFbeTQGqPo65TWhygFR4Qlqpt2icB57DDny', NULL, 'Active', 'customer', '2025-05-06 05:14:44'),
(22, 'Vic Sotto', 'wuvejuqu@asciibinder.net', '091233213', '$2y$10$F4YTr8ODaciae56To3geC.mECNltJ1vBINkcBB9CZRGNCTNB8V/lG', NULL, 'Active', 'customer', '2025-05-06 05:15:38'),
(23, 'Bong Revilla', 'kyrypa@asciibinder.net', '091231223', '$2y$10$1zqanPEylM87I1NkxxAMjuCxnlUH0jOGao6CY9e9oNmgDjOx2jqR.', NULL, 'Active', 'customer', '2025-05-06 05:16:35'),
(24, 'Joey De Leon', 'mugepa@dreamclarify.org', '091342524', '$2y$10$HtFGtN4lp74ZbmzuaFgGjO73G7Xi4nH8BSuHvJovvjSryyMlbkjYS', NULL, 'Active', 'customer', '2025-05-06 05:17:29'),
(25, 'Tito Sotto', 'cubanahe@asciibinder.net', '092141423', '$2y$10$8C51Iq6FTp8hJjsKwelGb.iQN/nUN5WLJbQJAXev1VzDMESbeExsu', NULL, 'Active', 'customer', '2025-05-06 05:18:43'),
(26, 'Bong Bong Marcos', 'wumele@polkaroad.net', '0923423424', '$2y$10$rK81T4OldxNtTbt7iYHH6uDqeYa/0WY16I8RElgzn8jS2pVcLDiLy', NULL, 'Active', 'customer', '2025-05-06 05:19:49'),
(27, 'Imee Marcos', 'ximilera@dreamclarify.org', '0942342343', '$2y$10$X5pSU/8YkNkCFB5.dA7u3OfVlw2JC.Rsv18XzQ4Q.lemE2GNW0rmi', NULL, 'Active', 'customer', '2025-05-06 05:20:47'),
(28, 'Rodrigo Duterte', 'lamefofu@dreamclarify.org', '09131234', '$2y$10$WaS5/g3ktFUiZAt3Hm442evcKHkfyir3o7Q48pA6GY05tcsndW/bm', '797921', '', 'customer', '2025-05-06 05:21:57'),
(29, 'Bong Go', 'xyvyzato@logsmarter.net', '094235242', '$2y$10$tXSyL6yKCZwmxuRMj9N5IefWBuP/WVJ6PcmQn3u3VJLQVWUbm/oEe', NULL, 'Active', 'customer', '2025-05-06 05:23:49'),
(30, 'Maine Mendoza', 'jupetako@azuretechtalk.net', '092342342', '$2y$10$AwXoSBsnxvxY7MlRImkFz.pwmUNH9O1FP6nZ0zDA0b1rtJoky93Sq', NULL, 'Active', 'customer', '2025-05-06 05:24:45'),
(31, 'Mar Roxas', 'jaqoty@thetechnext.net', '0952785274', '$2y$10$q3N8wX.mLGvFBs37/oTVveM/ONHWUv7cgDeJ.4XfJhvf6fcBHGLHi', NULL, 'Active', 'customer', '2025-05-06 05:25:41'),
(32, 'Manny Pacquiao', 'golyfylu@thetechnext.net', '093423472', '$2y$10$ucXQ/i52Le3hLdC7CMLP6.2yrhEjqNoCn.8hBr5Gz5huqIMaVgKwK', NULL, 'Active', 'customer', '2025-05-06 05:27:06'),
(33, 'Stephen Curry', 'zotociqi@azuretechtalk.net', '09242342', '$2y$10$wrBg8mRo2hb7.NkBqJJl6.1Zqa3HGxuo8hsiPjkuG669gmCSM3zVW', NULL, 'Active', 'customer', '2025-05-06 05:28:29'),
(34, 'Shay Julius', 'xubicuku@asciibinder.net', '092342847', '$2y$10$kyS77j3MaEcvTAYMZNvOaO8QABfzJv/swTH4wHimXC9uslttg.Ole', NULL, 'Active', 'customer', '2025-05-06 05:29:47'),
(35, 'Tralalelo Tralala', 'tezero@azuretechtalk.net', '09438274827', '$2y$10$DYBaQztNAA/ZcsHB50KkSeRFWzHtd6tAsO/sP4WIcq8MRHXkV7M8q', NULL, 'Active', 'customer', '2025-05-06 05:30:42'),
(36, 'Jake Tandog', 'ruhylipi@thetechnext.net', '0928572857', '$2y$10$ioHrDpbTfDTRQxXPTueMtO6SNqNEZdxWuuj1Q8EN.MIfVW/KwUztu', NULL, 'Active', 'customer', '2025-05-06 05:31:28'),
(37, 'Apple Deap', 'haluqexi@asciibinder.net', '092478274', '$2y$10$LHS/aaZ8zIECChO85o8p3eCMOVy3fiWdjKxLYTCC1Ctu2No5qLMYy', '713084', '', 'customer', '2025-05-06 05:32:18'),
(38, 'John Daniel Padilla', 'nugurozy@asciibinder.net', '091482572', '$2y$10$Xy5flGoWwvdEZKhUK8UXK.HWZ.0r.Yxio8zNdUCSNAUXmFd8z5HEq', '549003', '', 'customer', '2025-05-06 05:33:38'),
(39, 'Herlene Budol', 'Kaia20@tenull.com', '09423478274', '$2y$10$f16zI.rnNssIHiEDnMn6aOES.E.D1rvu2g/hOliHAZanxIqbHPVeC', NULL, 'Active', 'customer', '2025-05-06 05:36:41'),
(40, 'Joanna Duran', 'Kaia20234@tenull.com', '09423748', '$2y$10$UKIMfBdCwcZ3BTaU4cyRqOvL8qpKnXAhZvOoGi60oFA2EoikqksyW', NULL, 'Active', 'customer', '2025-05-06 05:37:56'),
(41, 'Michael Jordan', 'sahufsasf@tenull.com', '09423748', '$2y$10$vhaW2SS6lthrXynhu9840.Bv6KcLo0kYYv4.XEYFUWgY9ImamKsDG', NULL, 'Active', 'customer', '2025-05-06 05:39:16'),
(42, 'Enrique Gil', 'gjaiog@tenull.com', '09248723', '$2y$10$on5adhnRbJqi0HPBhw.VDeowVaKyw40oMQ9W3t6ke9wYoWMIIwDdG', NULL, 'Active', 'customer', '2025-05-06 05:40:20'),
(43, 'Kathryn Bernardo', 'zmnvj@tenull.com', '095982371', '$2y$10$tSoxgfo3WX5u9aBQcMYgSORJhaDyXOm/3TQDrV88IV0FdutCZmcXK', NULL, 'Active', 'customer', '2025-05-06 05:41:49'),
(44, 'Mary Grace Gonzaga', 'adqwrq@tenull.com', '094243728', '$2y$10$pR3tC4OjhfoL5CVcyKWXCOFHm2mMT/1xGEQk.gsw8F87spH3hUNlq', NULL, 'Active', 'customer', '2025-05-06 05:43:13'),
(45, 'Roselle Mae Llena', 'sdfsdf@tenull.com', '094237482741', '$2y$10$Vex9AOWFdTGna6aMn4UW2eJ8i.BnPudElxCz/GioEGISzQxHkQts2', NULL, 'Active', 'customer', '2025-05-06 05:44:24'),
(46, 'Daniella Mae Liquigan', 'fafaj@tenull.com', '09423782', '$2y$10$pYgUj2Z4ifAkHuC4Sb3Bju3WD5MS4uTLHB3EEdVDZ7Xzye/BJ0Ej2', NULL, 'Active', 'customer', '2025-05-06 05:45:33'),
(47, 'John Renzo Ponilas', 'vlsjfaf@tenull.com', '0949241', '$2y$10$qDYeJ6XkKD/4DiZMHZsTC.GVCyiGM0vRwfq/zAVhoWSOg7S2rHyH.', NULL, 'Active', 'customer', '2025-05-06 05:46:56'),
(48, 'Jeremias Cula', 'fsifiosdf@tenull.com', '092348919', '$2y$10$N6VsJKpRtI/GmzeaWqkWMO2H1f.x2T4mD8.fZ8wBlTja/ZyTYm/fq', NULL, 'Active', 'customer', '2025-05-06 05:48:55'),
(49, 'Ayra Mariano', 'fafhww@tenull.com', '0923467861', '$2y$10$meRRIkAF1ocAybzfOMuEFOlX0XaaCTr/OjVoExqbYP7hxtbMm58e.', NULL, 'Active', 'customer', '2025-05-06 05:51:10'),
(50, 'Xander Ford', 'tarub@tenull.com', '09696969669', '$2y$10$6yZyKChYnQ3F92c10hMLge2bQSZV9Bibb9.ucU3p4hJCV4QkT9BM6', NULL, 'Active', 'customer', '2025-05-06 05:52:33'),
(52, 'JUSTINE DANIELE', 'jdlarkin20@gmail.com', '09955351627', '$2y$10$Ogsa3SV/9WUxxOun1FInx.UMVw12c.Jbkj.eyqzsfzfwDM0Wwj.uq', NULL, 'Active', 'customer', '2025-05-08 09:29:13');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `time_slot_id` (`time_slot_id`);

--
-- Indexes for table `appointments_services`
--
ALTER TABLE `appointments_services`
  ADD PRIMARY KEY (`appointment_id`,`service_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `carousel`
--
ALTER TABLE `carousel`
  ADD PRIMARY KEY (`carousel_id`);

--
-- Indexes for table `inquiries`
--
ALTER TABLE `inquiries`
  ADD PRIMARY KEY (`inquiry_id`);

--
-- Indexes for table `patients`
--
ALTER TABLE `patients`
  ADD PRIMARY KEY (`patient_id`),
  ADD UNIQUE KEY `unique_schedule` (`schedule`,`time_slot`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `carousel`
--
ALTER TABLE `carousel`
  MODIFY `carousel_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `inquiries`
--
ALTER TABLE `inquiries`
  MODIFY `inquiry_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `patients`
--
ALTER TABLE `patients`
  MODIFY `patient_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=53;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
