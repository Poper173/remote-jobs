-- phpMyAdmin SQL Dump
-- version 5.2.2deb1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 16, 2025 at 01:33 PM
-- Server version: 11.8.1-MariaDB-2
-- PHP Version: 8.4.5

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `job_search`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alerts`
--

CREATE TABLE `alerts` (
  `alert_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('job','system') DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `alerts`
--

INSERT INTO `alerts` (`alert_id`, `user_id`, `type`, `content`, `created_at`) VALUES
(1, 4, 'job', 'check and see new jobs categories', '2025-05-08 08:55:50');

-- --------------------------------------------------------

--
-- Table structure for table `api_keys`
--

CREATE TABLE `api_keys` (
  `api_key_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `api_key` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `expires_at` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applicants_view_log`
--

CREATE TABLE `applicants_view_log` (
  `log_id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `viewed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `applications`
--

CREATE TABLE `applications` (
  `application_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `jobseeker_id` int(11) NOT NULL,
  `cover_letter` text DEFAULT NULL,
  `applied_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('pending','accepted','rejected') DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `applications`
--

INSERT INTO `applications` (`application_id`, `user_id`, `job_id`, `jobseeker_id`, `cover_letter`, `applied_at`, `status`) VALUES
(1, 5, 11, 5, 'ccccccccccccccc', '2025-05-12 20:42:35', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) DEFAULT NULL,
  `log_time` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`log_id`, `user_id`, `action`, `log_time`) VALUES
(1, 4, 'Assigned role ID 2 to user ID 1', '2025-05-08 10:16:06'),
(2, 4, 'Assigned role ID 2 to user ID 1', '2025-05-08 10:16:14'),
(3, 4, 'Assigned role ID 2 to user ID 1', '2025-05-08 10:18:19'),
(4, 4, 'Assigned role ID 2 to user ID 3', '2025-05-15 15:15:12');

-- --------------------------------------------------------

--
-- Table structure for table `badges`
--

CREATE TABLE `badges` (
  `badge_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `bookmarks`
--

CREATE TABLE `bookmarks` (
  `user_id` int(11) NOT NULL,
  `job_id` int(11) NOT NULL,
  `bookmarked_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `name`) VALUES
(2, 'Health and environmental science'),
(1, 'Information technology'),
(3, 'maisha plus bongo');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `certificate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(100) NOT NULL,
  `issuer` varchar(100) NOT NULL,
  `issued_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`certificate_id`, `user_id`, `title`, `issuer`, `issued_date`) VALUES
(1, 5, 'degree in BA', 'CBE', '2025-05-28'),
(2, 5, 'degree in BA', 'UDSM', '2025-05-22');

-- --------------------------------------------------------

--
-- Table structure for table `companies`
--

CREATE TABLE `companies` (
  `company_id` int(11) NOT NULL,
  `name` varchar(150) NOT NULL,
  `industry` varchar(100) DEFAULT NULL,
  `website` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `created_at` date NOT NULL,
  `verified` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `companies`
--

INSERT INTO `companies` (`company_id`, `name`, `industry`, `website`, `description`, `created_at`, `verified`) VALUES
(1, 'Collage of business education', 'education ', 'www.jobportal.com', 'come here', '0000-00-00', 0),
(10, 'Sample Company', NULL, NULL, NULL, '2025-05-03', 0),
(24, 'Your Company Name', NULL, NULL, NULL, '2025-05-05', 0),
(25, 'dooh', 'wdwww', 'http://localhost/jobsearch/dashboards/company_dashboard.php', 'hgggggggggggggggg', '2025-05-05', 0),
(31, 'vvvvvvvvvvvv', NULL, NULL, 'hhhh', '2025-05-12', 0);

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `user_id`, `message`, `created_at`) VALUES
(1, 5, 'i dont know where to', '2025-05-15 16:58:43'),
(2, 5, 'i dont know where to', '2025-05-15 16:59:40'),
(3, 5, 'i dont know where to', '2025-05-15 16:59:57'),
(4, 8, 'nsnnsnsn', '2025-05-16 11:25:03');

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `user_id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 5, 'prosper mwasile', 'popermwasile173@gmail.com', 'jajjajajja', '2025-05-16 12:40:47'),
(2, 22, 'jay dan', 'jaydanjohn79@gmail.com', 'hey yooooh am tired', '2025-05-16 12:50:04'),
(4, 22, 'jay dan', 'popermwasile173@gmail.com', '1234hahah', '2025-05-16 13:05:38'),
(5, 22, 'jay dan', 'jaydanjohn79@gmail.com', 'masaula', '2025-05-16 13:26:16');

-- --------------------------------------------------------

--
-- Table structure for table `education`
--

CREATE TABLE `education` (
  `education_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `level` varchar(100) NOT NULL,
  `institution` varchar(150) NOT NULL,
  `start_year` year(4) NOT NULL,
  `end_year` year(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `education`
--

INSERT INTO `education` (`education_id`, `user_id`, `level`, `institution`, `start_year`, `end_year`) VALUES
(1, 5, 'mwmwmw', 'mwmmwmw', '2003', '2007'),
(2, 5, 'gzgzgz', 'zgzgzg', '2024', '2037'),
(3, 5, 'dddd', 'sssss', '2018', '2022');

-- --------------------------------------------------------

--
-- Table structure for table `employer_profiles`
--

CREATE TABLE `employer_profiles` (
  `employer_id` int(11) NOT NULL,
  `employer_name` varchar(28) NOT NULL,
  `company_id` int(11) NOT NULL,
  `contact_number` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created_at` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `employer_profiles`
--

INSERT INTO `employer_profiles` (`employer_id`, `employer_name`, `company_id`, `contact_number`, `address`, `created_at`) VALUES
(8, 'vvvvvvvvvvvv', 31, NULL, NULL, '2025-05-12'),
(9, 'dooh', 25, '0759567894', 'nnggfff', '2025-05-05');

-- --------------------------------------------------------

--
-- Table structure for table `employer_reviews`
--

CREATE TABLE `employer_reviews` (
  `review_id` int(11) NOT NULL,
  `employer_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` between 1 and 5),
  `comment` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `experience`
--

CREATE TABLE `experience` (
  `experience_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `job_title` varchar(100) DEFAULT NULL,
  `company_name` varchar(100) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `experience`
--

INSERT INTO `experience` (`experience_id`, `user_id`, `job_title`, `company_name`, `start_date`, `end_date`, `description`) VALUES
(1, 5, 'mw', 'aaaa', '2025-05-07', '2025-05-13', 'aaaaaaaaaaaaaaaa\r\naaaaaaaaaaaaaaaaa'),
(2, 5, 'eeee', 'eee', '2025-05-22', '2025-05-23', 'eeeeeeeeeeeeeeeeee');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `faq_id` int(11) NOT NULL,
  `question` text NOT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `feedback`
--

CREATE TABLE `feedback` (
  `feedback_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `rating` int(11) DEFAULT NULL CHECK (`rating` >= 1 and `rating` <= 5),
  `submitted_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `interviews`
--

CREATE TABLE `interviews` (
  `interview_id` int(11) NOT NULL,
  `application_id` int(11) DEFAULT NULL,
  `scheduled_time` datetime DEFAULT NULL,
  `mode` enum('online','in-person') NOT NULL,
  `location` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `interviews`
--

INSERT INTO `interviews` (`interview_id`, `application_id`, `scheduled_time`, `mode`, `location`) VALUES
(1, 1, '2025-05-23 07:45:00', 'online', 'hshhshshhs');

-- --------------------------------------------------------

--
-- Table structure for table `job_locations`
--

CREATE TABLE `job_locations` (
  `job_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_posts`
--

CREATE TABLE `job_posts` (
  `job_id` int(11) NOT NULL,
  `employer_id` int(11) NOT NULL,
  `employer_name` varchar(40) NOT NULL DEFAULT '',
  `category_id` int(255) NOT NULL,
  `title` varchar(150) NOT NULL,
  `post_number` int(200) NOT NULL,
  `qualifications` varchar(500) NOT NULL,
  `salary_range` varchar(50) NOT NULL,
  `company_id` int(11) NOT NULL,
  `location_id` int(11) NOT NULL,
  `application_start` date NOT NULL,
  `duties` varchar(255) NOT NULL,
  `application_end` date NOT NULL,
  `posted_at` timestamp NULL DEFAULT current_timestamp(),
  `status` enum('active','closed') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `job_posts`
--

INSERT INTO `job_posts` (`job_id`, `employer_id`, `employer_name`, `category_id`, `title`, `post_number`, `qualifications`, `salary_range`, `company_id`, `location_id`, `application_start`, `duties`, `application_end`, `posted_at`, `status`) VALUES
(11, 8, 'Teacher', 1, 'Teacher', 34, 'dddddddddddddddd', '45', 24, 43, '2025-05-13', 'eeeeeeeeeeeeeeee', '2025-05-12', '2025-05-05 20:17:04', 'active'),
(13, 8, 'mpya', 1, 'ualimu', 300, 'Master degree in engineering science, IT and all related information from recognized institution.', '345', 24, 46, '2025-05-09', 'Kufundisha \r\nku control\r\nku guide', '2025-05-23', '2025-05-09 12:54:47', 'active'),
(17, 8, 'mpya', 2, 'aahhahha', 34, 'dhhhhhhhhhhhhhhhhhh', '234', 24, 50, '2025-05-13', 'shsssssssssssssssss', '2025-05-20', '2025-05-12 12:30:40', 'active'),
(18, 8, '', 3, 'gggggggggggg', 34, 'hhhhhhhhh', '567', 31, 56, '2025-05-12', 'hhhhhhhhh', '2025-06-11', '2025-05-12 15:10:59', 'active');

-- --------------------------------------------------------

--
-- Table structure for table `job_promotions`
--

CREATE TABLE `job_promotions` (
  `job_id` int(11) NOT NULL,
  `promotion_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `job_skills`
--

CREATE TABLE `job_skills` (
  `job_id` int(11) NOT NULL,
  `skill_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `job_skills`
--

INSERT INTO `job_skills` (`job_id`, `skill_id`) VALUES
(18, 3);

-- --------------------------------------------------------

--
-- Table structure for table `job_views`
--

CREATE TABLE `job_views` (
  `job_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `viewed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `job_views`
--

INSERT INTO `job_views` (`job_id`, `user_id`, `viewed_at`) VALUES
(11, 5, '2025-05-15 14:47:09');

-- --------------------------------------------------------

--
-- Table structure for table `languages`
--

CREATE TABLE `languages` (
  `language_id` int(11) NOT NULL,
  `language_name` varchar(50) NOT NULL,
  `user_id` int(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `languages`
--

INSERT INTO `languages` (`language_id`, `language_name`, `user_id`) VALUES
(1, 'swahili', 5),
(2, 'English', 5);

-- --------------------------------------------------------

--
-- Table structure for table `locations`
--

CREATE TABLE `locations` (
  `location_id` int(11) NOT NULL,
  `city` varchar(100) DEFAULT NULL,
  `region` varchar(100) DEFAULT NULL,
  `country` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `locations`
--

INSERT INTO `locations` (`location_id`, `city`, `region`, `country`) VALUES
(13, 'mmmm', NULL, NULL),
(39, 'vvfff', NULL, NULL),
(40, 'mwanza', NULL, NULL),
(41, 'ddd', 'eee', 'eeee'),
(42, 'mbeya', 'mbweni', 'tanzania'),
(43, 'daes salaam', 'temeke', 'tanzania'),
(46, 'mwanza', 'kisulu', 'Tanzania'),
(50, 'hshshshhsh', 'gdgdggdg', 'tanzania'),
(56, 'gdgdgdg', 'hhfhfhhf', 'tanzania');

-- --------------------------------------------------------

--
-- Table structure for table `login_logs`
--

CREATE TABLE `login_logs` (
  `log_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `login_time` timestamp NULL DEFAULT current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `login_logs`
--

INSERT INTO `login_logs` (`log_id`, `user_id`, `login_time`, `ip_address`) VALUES
(1, 5, '2025-05-08 08:08:05', '::1'),
(2, 5, '2025-05-08 08:49:43', '::1'),
(3, 4, '2025-05-08 08:55:02', '::1'),
(4, 5, '2025-05-08 08:56:16', '::1'),
(5, 8, '2025-05-08 09:00:05', '::1'),
(6, 3, '2025-05-08 09:00:12', '::1'),
(7, 5, '2025-05-08 09:01:34', '::1'),
(8, 4, '2025-05-08 09:01:44', '::1'),
(9, 5, '2025-05-08 09:25:17', '::1'),
(10, 5, '2025-05-08 09:33:24', '::1'),
(11, 4, '2025-05-08 10:13:56', '::1'),
(12, 4, '2025-05-08 10:18:34', '::1'),
(13, 5, '2025-05-08 10:31:14', '::1'),
(14, 4, '2025-05-08 10:32:34', '::1'),
(15, 8, '2025-05-08 12:06:01', '::1'),
(16, 5, '2025-05-08 12:06:15', '::1'),
(17, 4, '2025-05-08 12:16:58', '::1'),
(18, 4, '2025-05-08 12:37:43', '::1'),
(19, 4, '2025-05-08 13:12:49', '::1'),
(20, 4, '2025-05-08 13:36:59', '::1'),
(21, 4, '2025-05-08 13:47:50', '::1'),
(22, 5, '2025-05-09 12:02:11', '::1'),
(23, 4, '2025-05-09 12:06:20', '::1'),
(24, 5, '2025-05-09 12:21:33', '::1'),
(25, 8, '2025-05-09 12:32:07', '::1'),
(26, 5, '2025-05-09 12:56:32', '::1'),
(27, 5, '2025-05-09 13:25:59', '::1'),
(28, 8, '2025-05-09 13:28:49', '::1'),
(29, 4, '2025-05-09 14:05:29', '::1'),
(30, 5, '2025-05-09 14:11:09', '::1'),
(31, 8, '2025-05-09 14:11:19', '::1'),
(32, 4, '2025-05-09 14:11:29', '::1'),
(33, 4, '2025-05-09 14:14:04', '::1'),
(34, 8, '2025-05-09 14:16:25', '::1'),
(35, 5, '2025-05-09 14:32:51', '::1'),
(36, 4, '2025-05-09 14:33:00', '::1'),
(37, 8, '2025-05-09 14:49:29', '::1'),
(38, 5, '2025-05-09 14:57:22', '::1'),
(39, 5, '2025-05-09 15:10:16', '::1'),
(40, 4, '2025-05-09 15:10:25', '::1'),
(41, 5, '2025-05-09 15:22:23', '::1'),
(42, 5, '2025-05-09 15:28:27', '::1'),
(43, 5, '2025-05-09 15:57:24', '::1'),
(44, 5, '2025-05-09 16:06:21', '::1'),
(45, 5, '2025-05-09 16:13:32', '::1'),
(46, 17, '2025-05-09 16:16:43', '::1'),
(47, 5, '2025-05-09 16:22:08', '::1'),
(48, 5, '2025-05-11 13:56:15', '::1'),
(49, 5, '2025-05-11 14:23:50', '::1'),
(50, 5, '2025-05-11 14:25:18', '::1'),
(51, 5, '2025-05-11 14:26:10', '::1'),
(52, 5, '2025-05-11 14:28:25', '::1'),
(53, 5, '2025-05-11 14:29:45', '::1'),
(54, 5, '2025-05-11 14:30:31', '::1'),
(55, 5, '2025-05-11 14:31:48', '::1'),
(56, 5, '2025-05-11 14:44:02', '::1'),
(57, 5, '2025-05-11 14:46:17', '::1'),
(58, 5, '2025-05-11 14:46:28', '::1'),
(59, 5, '2025-05-11 15:01:46', '::1'),
(60, 5, '2025-05-11 15:09:54', '::1'),
(61, 5, '2025-05-11 15:49:03', '::1'),
(62, 5, '2025-05-11 15:58:07', '::1'),
(63, 4, '2025-05-11 16:22:27', '::1'),
(64, 5, '2025-05-11 16:32:01', '::1'),
(65, 5, '2025-05-11 16:33:24', '::1'),
(66, 12, '2025-05-11 16:40:35', '::1'),
(67, 5, '2025-05-11 16:54:37', '::1'),
(68, 17, '2025-05-11 16:56:06', '::1'),
(69, 5, '2025-05-11 16:56:24', '::1'),
(70, 12, '2025-05-11 17:10:30', '::1'),
(71, 5, '2025-05-11 18:31:27', '::1'),
(72, 5, '2025-05-11 19:10:53', '::1'),
(73, 5, '2025-05-11 19:11:18', '::1'),
(74, 8, '2025-05-11 19:33:53', '::1'),
(75, 5, '2025-05-11 20:12:34', '::1'),
(76, 8, '2025-05-11 20:12:43', '::1'),
(77, 5, '2025-05-12 11:57:28', '::1'),
(78, 5, '2025-05-12 12:25:45', '::1'),
(79, 3, '2025-05-12 12:29:13', '::1'),
(80, 8, '2025-05-12 12:29:22', '::1'),
(81, 17, '2025-05-12 13:58:07', '::1'),
(82, 8, '2025-05-12 13:58:19', '::1'),
(83, 8, '2025-05-12 14:20:23', '::1'),
(84, 5, '2025-05-12 15:13:05', '::1'),
(85, 8, '2025-05-12 15:30:44', '::1'),
(86, 8, '2025-05-12 15:43:13', '::1'),
(87, 5, '2025-05-12 15:45:29', '::1'),
(88, 5, '2025-05-12 16:08:37', '::1'),
(89, 5, '2025-05-12 16:34:04', '::1'),
(90, 5, '2025-05-12 16:49:04', '::1'),
(91, 5, '2025-05-12 19:26:01', '::1'),
(92, 5, '2025-05-12 20:19:54', '::1'),
(93, 5, '2025-05-12 20:32:07', '::1'),
(94, 8, '2025-05-12 20:51:41', '::1'),
(95, 5, '2025-05-12 21:01:27', '::1'),
(96, 8, '2025-05-12 21:02:42', '::1'),
(97, 8, '2025-05-12 21:02:54', '::1'),
(98, 8, '2025-05-12 21:03:07', '::1'),
(99, 5, '2025-05-12 21:10:41', '::1'),
(100, 8, '2025-05-12 21:14:54', '::1'),
(101, 8, '2025-05-12 21:22:19', '::1'),
(102, 5, '2025-05-12 21:22:29', '::1'),
(103, 5, '2025-05-12 21:23:42', '::1'),
(104, 5, '2025-05-12 21:24:56', '::1'),
(105, 5, '2025-05-12 21:26:35', '::1'),
(106, 5, '2025-05-12 21:30:50', '::1'),
(107, 5, '2025-05-12 21:31:56', '::1'),
(108, 8, '2025-05-12 21:38:21', '::1'),
(109, 5, '2025-05-12 21:38:46', '::1'),
(110, 5, '2025-05-13 04:30:50', '::1'),
(111, 5, '2025-05-15 11:42:15', '::1'),
(112, 5, '2025-05-15 12:54:07', '::1'),
(113, 5, '2025-05-15 13:20:01', '::1'),
(114, 5, '2025-05-15 13:26:43', '::1'),
(115, 5, '2025-05-15 13:30:09', '::1'),
(116, 5, '2025-05-15 13:56:31', '::1'),
(117, 18, '2025-05-15 14:09:03', '::1'),
(118, 5, '2025-05-15 14:26:24', '::1'),
(119, 5, '2025-05-15 14:36:45', '::1'),
(120, 5, '2025-05-15 14:47:04', '::1'),
(121, 8, '2025-05-15 14:49:03', '::1'),
(122, 5, '2025-05-15 14:51:15', '::1'),
(123, 8, '2025-05-15 15:01:48', '::1'),
(124, 3, '2025-05-15 15:12:25', '::1'),
(125, 4, '2025-05-15 15:12:40', '::1'),
(126, 3, '2025-05-15 15:14:00', '::1'),
(127, 17, '2025-05-15 15:14:44', '::1'),
(128, 4, '2025-05-15 15:15:02', '::1'),
(129, 8, '2025-05-15 15:15:24', '::1'),
(130, 12, '2025-05-15 15:17:40', '::1'),
(131, 5, '2025-05-15 15:17:59', '::1'),
(132, 8, '2025-05-15 15:22:23', '::1'),
(133, 8, '2025-05-15 15:46:15', '::1'),
(134, 8, '2025-05-15 16:02:53', '::1'),
(135, 8, '2025-05-15 16:03:01', '::1'),
(136, 8, '2025-05-15 16:17:55', '::1'),
(137, 5, '2025-05-15 16:23:07', '::1'),
(138, 8, '2025-05-15 16:31:54', '::1'),
(139, 5, '2025-05-15 16:34:43', '::1'),
(140, 5, '2025-05-15 16:40:08', '::1'),
(141, 8, '2025-05-15 16:42:26', '::1'),
(142, 5, '2025-05-15 16:43:00', '::1'),
(143, 4, '2025-05-15 17:06:40', '::1'),
(144, 5, '2025-05-16 11:12:52', '::1'),
(145, 8, '2025-05-16 11:24:33', '::1'),
(146, 8, '2025-05-16 11:24:46', '::1'),
(147, 4, '2025-05-16 11:25:39', '::1'),
(148, 5, '2025-05-16 11:58:57', '::1'),
(149, 5, '2025-05-16 11:59:26', '::1'),
(150, 5, '2025-05-16 11:59:52', '::1'),
(151, 5, '2025-05-16 12:03:04', '::1'),
(152, 5, '2025-05-16 12:23:28', '::1'),
(153, 5, '2025-05-16 12:25:28', '::1'),
(154, 5, '2025-05-16 12:25:48', '::1'),
(155, 5, '2025-05-16 12:27:58', '::1'),
(156, 5, '2025-05-16 12:28:08', '::1'),
(157, 5, '2025-05-16 12:29:11', '::1'),
(158, 22, '2025-05-16 12:48:50', '::1');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `message_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL DEFAULT 4,
  `message` varchar(50) NOT NULL,
  `subject` varchar(500) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`message_id`, `user_id`, `receiver_id`, `message`, `subject`, `created_at`) VALUES
(1, 5, 4, 'ffffffffffffffffffff', 'hhhhhhhhhhhh', '2025-05-12 12:27:20');

-- --------------------------------------------------------

--
-- Table structure for table `newsletters`
--

CREATE TABLE `newsletters` (
  `newsletter_id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `sent_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `newsletter_subscribers`
--

CREATE TABLE `newsletter_subscribers` (
  `user_id` int(11) NOT NULL,
  `subscribed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `transaction_id` int(11) DEFAULT NULL,
  `method` enum('card','paypal','bank') DEFAULT NULL,
  `paid_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privacy_policy`
--

CREATE TABLE `privacy_policy` (
  `policy_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `effective_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `privacy_policy`
--

INSERT INTO `privacy_policy` (`policy_id`, `content`, `effective_date`) VALUES
(1, '🔒 Privacy & Application Policy: Your application will be treated with strict confidentiality. Do not forge any documents or submit false information. Ensure all details are true and verifiable. Violations may result in disqualification and permanent ban from the platform.', '2025-05-11');

-- --------------------------------------------------------

--
-- Table structure for table `profile_views`
--

CREATE TABLE `profile_views` (
  `viewer_id` int(11) NOT NULL,
  `viewed_id` int(11) NOT NULL,
  `viewed_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `profile_views`
--

INSERT INTO `profile_views` (`viewer_id`, `viewed_id`, `viewed_at`) VALUES
(8, 5, '2025-05-15 16:42:52');

-- --------------------------------------------------------

--
-- Table structure for table `promotions`
--

CREATE TABLE `promotions` (
  `promotion_id` int(11) NOT NULL,
  `title` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referral_id` int(11) NOT NULL,
  `referred_user_id` int(11) DEFAULT NULL,
  `referrer_id` int(11) DEFAULT NULL,
  `joined_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referral_links`
--

CREATE TABLE `referral_links` (
  `referral_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `referral_code` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reports`
--

CREATE TABLE `reports` (
  `report_id` int(11) NOT NULL,
  `reporter_id` int(11) DEFAULT NULL,
  `reported_user_id` int(11) DEFAULT NULL,
  `reason` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `resumes`
--

CREATE TABLE `resumes` (
  `resume_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `resume_url` varchar(255) NOT NULL,
  `uploaded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `resumes`
--

INSERT INTO `resumes` (`resume_id`, `user_id`, `resume_url`, `uploaded_at`) VALUES
(1, 5, '../uploads/resumes/681b828c25abd_certificates.pdf', '2025-05-07 15:55:56'),
(2, 5, '../uploads/resumes/681b929939d9f_certificates.pdf', '2025-05-07 17:04:25');

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int(11) NOT NULL,
  `role_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(2, 'company'),
(3, 'jobseeker'),
(1, 'super');

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `setting_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `notification_emails` tinyint(1) DEFAULT 1,
  `dark_mode` tinyint(1) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`setting_id`, `user_id`, `notification_emails`, `dark_mode`) VALUES
(1, 4, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `skills`
--

CREATE TABLE `skills` (
  `skill_id` int(11) NOT NULL,
  `skill_name` varchar(100) NOT NULL,
  `user_id` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `skills`
--

INSERT INTO `skills` (`skill_id`, `skill_name`, `user_id`) VALUES
(1, 'mmmmmmmmmmmm', 5),
(2, 'hhhh', 5),
(3, 'JavaScript', 8);

-- --------------------------------------------------------

--
-- Table structure for table `subscriptions`
--

CREATE TABLE `subscriptions` (
  `subscription_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `plan` varchar(50) DEFAULT NULL,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `support_tickets`
--

CREATE TABLE `support_tickets` (
  `ticket_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `subject` varchar(100) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('open','resolved','closed') DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `terms_conditions`
--

CREATE TABLE `terms_conditions` (
  `version_id` int(11) NOT NULL,
  `content` text NOT NULL,
  `effective_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `terms_conditions`
--

INSERT INTO `terms_conditions` (`version_id`, `content`, `effective_date`) VALUES
(3, '<h2><span class=\"marker\"><strong>1. Introduction</strong></span></h2>\r\n\r\n<p>Welcome to our Remote Job Search Portal. This platform is designed to connect job seekers with legitimate remote work opportunities offered by verified employers. By accessing or using this portal, you agree to comply with these Terms and Conditions. Please read them carefully.</p>\r\n\r\n<h2><span class=\"marker\"><strong>2. Our Mission</strong></span></h2>\r\n\r\n<p>Our goal is to empower individuals by providing a trustworthy platform that facilitates access to genuine remote job opportunities. We are committed to fostering transparency, integrity, and professionalism in the job search process.</p>\r\n\r\n<h2><span class=\"marker\"><strong>3. Truthfulness of Information (IMPORTANT)</strong></span></h2>\r\n\r\n<p>We strongly emphasize that all users must provide <strong>accurate, complete, and honest</strong> information at all times. Submitting false information, impersonating someone else, or misrepresenting qualifications may result in the immediate <strong>suspension or termination</strong> of your account without prior notice.</p>\r\n\r\n<h3><span class=\"marker\"><strong>Examples of information that must be truthful:</strong></span></h3>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Full legal name</p>\r\n	</li>\r\n	<li>\r\n	<p>Educational background</p>\r\n	</li>\r\n	<li>\r\n	<p>Work experience</p>\r\n	</li>\r\n	<li>\r\n	<p>Skills and certifications</p>\r\n	</li>\r\n	<li>\r\n	<p>Contact details</p>\r\n	</li>\r\n	<li>\r\n	<p>Uploaded documents (e.g., CVs, diplomas)</p>\r\n	</li>\r\n</ul>\r\n\r\n<h2><span class=\"marker\"><strong>4. User Responsibilities</strong></span></h2>\r\n\r\n<p>As a user of this portal, you agree to:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Maintain the confidentiality of your login credentials.</p>\r\n	</li>\r\n	<li>\r\n	<p>Use the platform <strong>only</strong> for lawful and professional purposes.</p>\r\n	</li>\r\n	<li>\r\n	<p>Avoid posting or submitting misleading, inappropriate, or fraudulent content.</p>\r\n	</li>\r\n	<li>\r\n	<p>Take full responsibility for all actions carried out under your account.</p>\r\n	</li>\r\n</ul>\r\n\r\n<h2><span class=\"marker\"><strong>5. Platform Rights</strong></span></h2>\r\n\r\n<p>We reserve the right to:</p>\r\n\r\n<ul>\r\n	<li>\r\n	<p>Review and verify user-submitted information to ensure authenticity.</p>\r\n	</li>\r\n	<li>\r\n	<p>Suspend or delete accounts that violate these terms.</p>\r\n	</li>\r\n	<li>\r\n	<p>Modify or discontinue parts of the service at our discretion.</p>\r\n	</li>\r\n	<li>\r\n	<p>Protect user data in accordance with our Privacy Policy.</p>\r\n	</li>\r\n</ul>\r\n\r\n<h2><span class=\"marker\"><strong>6. Communication and Notifications</strong></span></h2>\r\n\r\n<p>By registering with our portal, you consent to receive important communications via the email address or phone number you provided. These may include job alerts, updates from employers, system changes, or important notifications.</p>\r\n\r\n<h2><span class=\"marker\"><strong>7. Modifications to Terms</strong></span></h2>\r\n\r\n<p>We may revise these Terms and Conditions from time to time. Users will be notified of significant changes, and continued use of the platform after such updates constitutes acceptance of the new terms.</p>\r\n\r\n<h2><span class=\"marker\"><strong>8. Final Agreement</strong></span></h2>\r\n\r\n<p>By using this platform, you acknowledge that you have <strong>read, understood, and agreed</strong> to be bound by these Terms and Conditions. We strive to maintain a <strong>reliable and efficient</strong> platform that promotes genuine opportunities and professional growth.</p>', '2025-05-08');

-- --------------------------------------------------------

--
-- Table structure for table `ticket_responses`
--

CREATE TABLE `ticket_responses` (
  `response_id` int(11) NOT NULL,
  `ticket_id` int(11) DEFAULT NULL,
  `responder_id` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `responded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `status` enum('pending','completed','failed') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `uploaded_certificates`
--

CREATE TABLE `uploaded_certificates` (
  `certificate_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `certificate_title` varchar(100) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `uploaded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `uploaded_certificates`
--

INSERT INTO `uploaded_certificates` (`certificate_id`, `user_id`, `certificate_title`, `file_path`, `uploaded_at`) VALUES
(1, 5, 'degree in BA', '../uploads/certificates/681b713a76c74_certificates.pdf', '2025-05-07 14:42:02');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `role_id` int(11) NOT NULL,
  `profile_picture` varchar(255) NOT NULL DEFAULT '../uploads/default_profile.png',
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `full_name`, `email`, `password_hash`, `role_id`, `profile_picture`, `created_at`) VALUES
(2, 'mpya mtu', 'mtuwanguvu@gmail.com', '$2y$12$xG0Nj/egnUlh8BRIRP5HvuX93d3AP/sCqlqKa4pmfQRJrMzzumfM2', 3, '../uploads/default_profile.png', '2025-05-02 14:56:34'),
(3, 'new user', 'newuser@gmail.com', '$2y$12$12qKhTGBsz6x.vncKRUL4e1O.bXug2oXBnmmmPVWRa1isz7Jm53H6', 2, '../uploads/profile_pictures/681b9d98deca1.png', '2025-05-03 19:37:12'),
(4, 'prosper mwasile', 'popermwasile173@gmail.com', '$2y$12$BCg.xBflLfdKFYTHNBvMzem8hM1wY5wxs8OaJjz1RyxgTlaciWm1m', 1, '../uploads/default_profile.png', '2025-05-03 19:43:09'),
(5, 'database', 'database@gmail.com', '$2y$12$lNe5J0FmtpqYYZDW0UEgxuNY1HI8sQdOmEWwBZalWjKItkcOL6woS', 3, '../uploads/profile_pictures/681b9e2303f5e.png', '2025-05-03 20:27:13'),
(6, 'Test Company', 'company@example.com', '$2y$12$examplehash', 2, '../uploads/default_profile.png', '2025-05-03 20:39:26'),
(7, 'mwamba', 'mwamba@gmail.com', '$2y$12$s7xgyw185NHTwJtmCNCmC.TMlyTpx.W/O5UiLD.Xpk173kgVhlXze', 3, '../uploads/default_profile.png', '2025-05-03 21:57:36'),
(8, 'mpya', 'mpya@gmail.com', '$2y$12$UmbFcwcWz39wxV5us9Xs1.HsVrtsG9cEImaFZyz10o93sEfo53ni.', 2, '../uploads/default_profile.png', '2025-05-03 22:57:45'),
(9, 'company', 'company@company.com', '$2y$12$Ldqsc7EovloVk58BSnA5BOzJvdStO3zamf65eAZf5wlw198mi6YES', 2, '../uploads/default_profile.png', '2025-05-05 17:47:55'),
(10, 'mduwile', 'mduwile@gmail.com', '$2y$12$ue2ECL6qrVHi4kXaJkjiZeat7PAvXA4O837Wg2P1sApedUtHlS4K6', 3, '../uploads/default_profile.png', '2025-05-06 17:09:57'),
(11, '', '', '$2y$12$5oeAw8pDK52bipHbBZxUEOAjeUGm/RXFHEO2jo2ScOHAowm7GpuRC', 3, '../uploads/default_profile.png', '2025-05-08 13:20:49'),
(12, 'granny', 'granny@gmail.com', '$2y$12$AhUuaLUgq5tHPoMsTwETYOsxpVEM7lp4Qsm7PZB6mohuOPNX2wB/C', 3, '../uploads/default_profile.png', '2025-05-08 13:29:57'),
(13, 'mwingine', 'mwingine@gmail.com', '$2y$12$qroMYVf9j3tobN2Hvm2BfOuAIQ0o5q6el/lk6Ieiu3Ln6cVIZYefa', 3, '../uploads/default_profile.png', '2025-05-09 14:57:56'),
(14, 'alwatan abdull', 'alwatan@gmail.com', '$2y$12$JNhrYR6D4nJBHdePQDpHO.Y23Q3Cwu3fA3VpcGyUFCip1HNHJN9UC', 3, '../uploads/default_profile.png', '2025-05-09 15:10:07'),
(15, 'mkuu', 'mkuuu@gmail.com', '$2y$12$GczVbBxCo2mJL6VVeEEEgeNXErZlMdB/i8GEbeSwG9ED.wSXY4Jne', 3, '../uploads/default_profile.png', '2025-05-09 15:22:11'),
(16, 'mwasile ginii', 'tuma@gmail.com', '$2y$12$E6F0j150ifx9u9u2GqDdAOPLtF66rhc6O8/IA7EhFbiL0vWRwg6jO', 3, '../uploads/default_profile.png', '2025-05-09 15:58:07'),
(17, 'mmmammama', 'mkubwa@gmail.com', '$2y$12$Ok.GTpZjo2A24Ppt88I0iubH/w15WrFDA0Ojzr7E87byHqP4884Nm', 3, '../uploads/default_profile.png', '2025-05-09 16:16:22'),
(18, 'new user', 'mimi@gmail.com', '$2y$12$zdt0kPJRgDWHtN6YxPXu..i7fpAaXp7i59YBK1RG/Julxu4VlD8Pi', 3, '../uploads/default_profile.png', '2025-05-11 21:05:21'),
(19, 'testa', 'testa@gmail.com', '$2y$12$IzFJzquYvr.Jx75wlrbUw.KouXO9VDfSyVPQ08iQ31Ummf8joNNH2', 3, '../uploads/default_profile.png', '2025-05-12 11:31:27'),
(20, 'new user', 'jaydan79@gmail.com', '$2y$12$nAzx93ys/eYuXvFaL/p0JuU3pvWpQMDbXaQqe752whFl9TOsl.kD.', 3, '../uploads/default_profile.png', '2025-05-12 11:39:20'),
(21, 'terable', 'mistake@gmail.com', '$2y$12$a6RMc8MMHzggvKzuRpYOEOynL9IirO.KsANL3KGGIAcevgr1w.gju', 3, '../uploads/default_profile.png', '2025-05-12 11:42:08'),
(22, 'jay dan', 'jaydanjohn79@gmail.com', '$2y$12$IQep./4BwHgMnbz1To5yiO6Un7f13aVsGk1PC1XKAQEbsXRULGV66', 3, '../uploads/default_profile.png', '2025-05-16 12:47:11');

-- --------------------------------------------------------

--
-- Table structure for table `user_badges`
--

CREATE TABLE `user_badges` (
  `user_id` int(11) NOT NULL,
  `badge_id` int(11) NOT NULL,
  `awarded_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_languages`
--

CREATE TABLE `user_languages` (
  `user_language_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `proficiency_level` enum('basic','conversational','fluent','native') DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_locations`
--

CREATE TABLE `user_locations` (
  `user_id` int(11) NOT NULL,
  `location_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_preferences`
--

CREATE TABLE `user_preferences` (
  `user_id` int(11) NOT NULL,
  `preferred_location_id` int(11) DEFAULT NULL,
  `preferred_category_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `alerts`
--
ALTER TABLE `alerts`
  ADD PRIMARY KEY (`alert_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD PRIMARY KEY (`api_key_id`),
  ADD UNIQUE KEY `api_key` (`api_key`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `applicants_view_log`
--
ALTER TABLE `applicants_view_log`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `admin_id` (`admin_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `applications`
--
ALTER TABLE `applications`
  ADD PRIMARY KEY (`application_id`),
  ADD KEY `job_id` (`job_id`),
  ADD KEY `jobseeker_id` (`jobseeker_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `badges`
--
ALTER TABLE `badges`
  ADD PRIMARY KEY (`badge_id`);

--
-- Indexes for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD PRIMARY KEY (`user_id`,`job_id`),
  ADD KEY `job_id` (`job_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `fk_certificates_user` (`user_id`);

--
-- Indexes for table `companies`
--
ALTER TABLE `companies`
  ADD PRIMARY KEY (`company_id`),
  ADD KEY `company_id` (`company_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `education`
--
ALTER TABLE `education`
  ADD PRIMARY KEY (`education_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `level` (`level`);

--
-- Indexes for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  ADD PRIMARY KEY (`employer_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `name` (`employer_name`);

--
-- Indexes for table `employer_reviews`
--
ALTER TABLE `employer_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `reviewer_id` (`reviewer_id`);

--
-- Indexes for table `experience`
--
ALTER TABLE `experience`
  ADD PRIMARY KEY (`experience_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`faq_id`);

--
-- Indexes for table `feedback`
--
ALTER TABLE `feedback`
  ADD PRIMARY KEY (`feedback_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `interviews`
--
ALTER TABLE `interviews`
  ADD PRIMARY KEY (`interview_id`),
  ADD KEY `application_id` (`application_id`);

--
-- Indexes for table `job_locations`
--
ALTER TABLE `job_locations`
  ADD PRIMARY KEY (`job_id`,`location_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD PRIMARY KEY (`job_id`),
  ADD KEY `employer_id` (`employer_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `idx_company_id` (`company_id`),
  ADD KEY `company_id` (`company_id`),
  ADD KEY `company_id_2` (`company_id`),
  ADD KEY `title` (`title`),
  ADD KEY `post_number` (`post_number`),
  ADD KEY `employer_name` (`employer_name`),
  ADD KEY `application` (`application_end`),
  ADD KEY `application_end` (`application_end`),
  ADD KEY `application_start` (`application_start`),
  ADD KEY `salary_range` (`salary_range`),
  ADD KEY `duties` (`duties`),
  ADD KEY `qualifications` (`qualifications`);

--
-- Indexes for table `job_promotions`
--
ALTER TABLE `job_promotions`
  ADD PRIMARY KEY (`job_id`,`promotion_id`),
  ADD KEY `promotion_id` (`promotion_id`);

--
-- Indexes for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD PRIMARY KEY (`job_id`,`skill_id`),
  ADD KEY `skill_id` (`skill_id`);

--
-- Indexes for table `job_views`
--
ALTER TABLE `job_views`
  ADD PRIMARY KEY (`job_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`language_id`),
  ADD UNIQUE KEY `language_name` (`language_name`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `locations`
--
ALTER TABLE `locations`
  ADD PRIMARY KEY (`location_id`);

--
-- Indexes for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD PRIMARY KEY (`log_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`message_id`),
  ADD KEY `receiver_id` (`receiver_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `newsletters`
--
ALTER TABLE `newsletters`
  ADD PRIMARY KEY (`newsletter_id`);

--
-- Indexes for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `transaction_id` (`transaction_id`);

--
-- Indexes for table `privacy_policy`
--
ALTER TABLE `privacy_policy`
  ADD PRIMARY KEY (`policy_id`);

--
-- Indexes for table `profile_views`
--
ALTER TABLE `profile_views`
  ADD PRIMARY KEY (`viewer_id`,`viewed_id`),
  ADD KEY `viewed_id` (`viewed_id`);

--
-- Indexes for table `promotions`
--
ALTER TABLE `promotions`
  ADD PRIMARY KEY (`promotion_id`);

--
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
  ADD PRIMARY KEY (`referral_id`),
  ADD KEY `referred_user_id` (`referred_user_id`),
  ADD KEY `referrer_id` (`referrer_id`);

--
-- Indexes for table `referral_links`
--
ALTER TABLE `referral_links`
  ADD PRIMARY KEY (`referral_id`),
  ADD UNIQUE KEY `referral_code` (`referral_code`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reports`
--
ALTER TABLE `reports`
  ADD PRIMARY KEY (`report_id`),
  ADD KEY `reporter_id` (`reporter_id`),
  ADD KEY `reported_user_id` (`reported_user_id`);

--
-- Indexes for table `resumes`
--
ALTER TABLE `resumes`
  ADD PRIMARY KEY (`resume_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`setting_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `skills`
--
ALTER TABLE `skills`
  ADD PRIMARY KEY (`skill_id`),
  ADD UNIQUE KEY `skill_name` (`skill_name`);

--
-- Indexes for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD PRIMARY KEY (`subscription_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD PRIMARY KEY (`ticket_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  ADD PRIMARY KEY (`version_id`);

--
-- Indexes for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  ADD PRIMARY KEY (`response_id`),
  ADD KEY `ticket_id` (`ticket_id`),
  ADD KEY `responder_id` (`responder_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `uploaded_certificates`
--
ALTER TABLE `uploaded_certificates`
  ADD PRIMARY KEY (`certificate_id`),
  ADD KEY `fk_uploaded_certificates_user` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `fk_role_id` (`role_id`),
  ADD KEY `profile_picture` (`profile_picture`);

--
-- Indexes for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD PRIMARY KEY (`user_id`,`badge_id`),
  ADD KEY `badge_id` (`badge_id`);

--
-- Indexes for table `user_languages`
--
ALTER TABLE `user_languages`
  ADD PRIMARY KEY (`user_language_id`),
  ADD UNIQUE KEY `unique_user_language` (`user_id`,`language_id`),
  ADD KEY `language_id` (`language_id`);

--
-- Indexes for table `user_locations`
--
ALTER TABLE `user_locations`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `location_id` (`location_id`);

--
-- Indexes for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `preferred_location_id` (`preferred_location_id`),
  ADD KEY `preferred_category_id` (`preferred_category_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alerts`
--
ALTER TABLE `alerts`
  MODIFY `alert_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `api_keys`
--
ALTER TABLE `api_keys`
  MODIFY `api_key_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applicants_view_log`
--
ALTER TABLE `applicants_view_log`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `applications`
--
ALTER TABLE `applications`
  MODIFY `application_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `badges`
--
ALTER TABLE `badges`
  MODIFY `badge_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `companies`
--
ALTER TABLE `companies`
  MODIFY `company_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `education`
--
ALTER TABLE `education`
  MODIFY `education_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  MODIFY `employer_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `employer_reviews`
--
ALTER TABLE `employer_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `experience`
--
ALTER TABLE `experience`
  MODIFY `experience_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `faq_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `feedback`
--
ALTER TABLE `feedback`
  MODIFY `feedback_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `interviews`
--
ALTER TABLE `interviews`
  MODIFY `interview_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `job_posts`
--
ALTER TABLE `job_posts`
  MODIFY `job_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `languages`
--
ALTER TABLE `languages`
  MODIFY `language_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `locations`
--
ALTER TABLE `locations`
  MODIFY `location_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=59;

--
-- AUTO_INCREMENT for table `login_logs`
--
ALTER TABLE `login_logs`
  MODIFY `log_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=159;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `message_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `newsletters`
--
ALTER TABLE `newsletters`
  MODIFY `newsletter_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privacy_policy`
--
ALTER TABLE `privacy_policy`
  MODIFY `policy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `promotions`
--
ALTER TABLE `promotions`
  MODIFY `promotion_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `referral_links`
--
ALTER TABLE `referral_links`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `reports`
--
ALTER TABLE `reports`
  MODIFY `report_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `resumes`
--
ALTER TABLE `resumes`
  MODIFY `resume_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `setting_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `skills`
--
ALTER TABLE `skills`
  MODIFY `skill_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `subscriptions`
--
ALTER TABLE `subscriptions`
  MODIFY `subscription_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `support_tickets`
--
ALTER TABLE `support_tickets`
  MODIFY `ticket_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `terms_conditions`
--
ALTER TABLE `terms_conditions`
  MODIFY `version_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  MODIFY `response_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `uploaded_certificates`
--
ALTER TABLE `uploaded_certificates`
  MODIFY `certificate_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `user_languages`
--
ALTER TABLE `user_languages`
  MODIFY `user_language_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alerts`
--
ALTER TABLE `alerts`
  ADD CONSTRAINT `alerts_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `api_keys`
--
ALTER TABLE `api_keys`
  ADD CONSTRAINT `api_keys_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `applicants_view_log`
--
ALTER TABLE `applicants_view_log`
  ADD CONSTRAINT `applicants_view_log_ibfk_1` FOREIGN KEY (`admin_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `applicants_view_log_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`);

--
-- Constraints for table `applications`
--
ALTER TABLE `applications`
  ADD CONSTRAINT `applications_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`),
  ADD CONSTRAINT `applications_ibfk_2` FOREIGN KEY (`jobseeker_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `bookmarks`
--
ALTER TABLE `bookmarks`
  ADD CONSTRAINT `bookmarks_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `bookmarks_ibfk_2` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`);

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `fk_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `complaints`
--
ALTER TABLE `complaints`
  ADD CONSTRAINT `complaints_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD CONSTRAINT `contact_messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `education`
--
ALTER TABLE `education`
  ADD CONSTRAINT `education_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `employer_profiles`
--
ALTER TABLE `employer_profiles`
  ADD CONSTRAINT `employer_profiles_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `employer_profiles_ibfk_2` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`);

--
-- Constraints for table `employer_reviews`
--
ALTER TABLE `employer_reviews`
  ADD CONSTRAINT `employer_reviews_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `employer_reviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `experience`
--
ALTER TABLE `experience`
  ADD CONSTRAINT `experience_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `feedback`
--
ALTER TABLE `feedback`
  ADD CONSTRAINT `feedback_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `interviews`
--
ALTER TABLE `interviews`
  ADD CONSTRAINT `interviews_ibfk_1` FOREIGN KEY (`application_id`) REFERENCES `applications` (`application_id`);

--
-- Constraints for table `job_locations`
--
ALTER TABLE `job_locations`
  ADD CONSTRAINT `job_locations_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`),
  ADD CONSTRAINT `job_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `job_posts`
--
ALTER TABLE `job_posts`
  ADD CONSTRAINT `fk_company` FOREIGN KEY (`company_id`) REFERENCES `companies` (`company_id`) ON UPDATE CASCADE,
  ADD CONSTRAINT `job_posts_ibfk_1` FOREIGN KEY (`employer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `job_posts_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`);

--
-- Constraints for table `job_promotions`
--
ALTER TABLE `job_promotions`
  ADD CONSTRAINT `job_promotions_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`),
  ADD CONSTRAINT `job_promotions_ibfk_2` FOREIGN KEY (`promotion_id`) REFERENCES `promotions` (`promotion_id`);

--
-- Constraints for table `job_skills`
--
ALTER TABLE `job_skills`
  ADD CONSTRAINT `job_skills_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`),
  ADD CONSTRAINT `job_skills_ibfk_2` FOREIGN KEY (`skill_id`) REFERENCES `skills` (`skill_id`);

--
-- Constraints for table `job_views`
--
ALTER TABLE `job_views`
  ADD CONSTRAINT `job_views_ibfk_1` FOREIGN KEY (`job_id`) REFERENCES `job_posts` (`job_id`),
  ADD CONSTRAINT `job_views_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `languages`
--
ALTER TABLE `languages`
  ADD CONSTRAINT `fk_languages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `login_logs`
--
ALTER TABLE `login_logs`
  ADD CONSTRAINT `login_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `messages`
--
ALTER TABLE `messages`
  ADD CONSTRAINT `messages_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `messages_ibfk_2` FOREIGN KEY (`receiver_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `newsletter_subscribers`
--
ALTER TABLE `newsletter_subscribers`
  ADD CONSTRAINT `newsletter_subscribers_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`transaction_id`) REFERENCES `transactions` (`transaction_id`);

--
-- Constraints for table `profile_views`
--
ALTER TABLE `profile_views`
  ADD CONSTRAINT `profile_views_ibfk_1` FOREIGN KEY (`viewer_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `profile_views_ibfk_2` FOREIGN KEY (`viewed_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `referrals`
--
ALTER TABLE `referrals`
  ADD CONSTRAINT `referrals_ibfk_1` FOREIGN KEY (`referred_user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `referrals_ibfk_2` FOREIGN KEY (`referrer_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `referral_links`
--
ALTER TABLE `referral_links`
  ADD CONSTRAINT `referral_links_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `reports`
--
ALTER TABLE `reports`
  ADD CONSTRAINT `reports_ibfk_1` FOREIGN KEY (`reporter_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `reports_ibfk_2` FOREIGN KEY (`reported_user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `resumes`
--
ALTER TABLE `resumes`
  ADD CONSTRAINT `resumes_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `settings`
--
ALTER TABLE `settings`
  ADD CONSTRAINT `settings_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `subscriptions`
--
ALTER TABLE `subscriptions`
  ADD CONSTRAINT `subscriptions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `support_tickets`
--
ALTER TABLE `support_tickets`
  ADD CONSTRAINT `support_tickets_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `ticket_responses`
--
ALTER TABLE `ticket_responses`
  ADD CONSTRAINT `ticket_responses_ibfk_1` FOREIGN KEY (`ticket_id`) REFERENCES `support_tickets` (`ticket_id`),
  ADD CONSTRAINT `ticket_responses_ibfk_2` FOREIGN KEY (`responder_id`) REFERENCES `admin_users` (`admin_id`);

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`);

--
-- Constraints for table `uploaded_certificates`
--
ALTER TABLE `uploaded_certificates`
  ADD CONSTRAINT `fk_uploaded_certificates_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `fk_role_id` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`);

--
-- Constraints for table `user_badges`
--
ALTER TABLE `user_badges`
  ADD CONSTRAINT `user_badges_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_badges_ibfk_2` FOREIGN KEY (`badge_id`) REFERENCES `badges` (`badge_id`);

--
-- Constraints for table `user_languages`
--
ALTER TABLE `user_languages`
  ADD CONSTRAINT `fk_user_languages_language` FOREIGN KEY (`language_id`) REFERENCES `languages` (`language_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_user_languages_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `user_locations`
--
ALTER TABLE `user_locations`
  ADD CONSTRAINT `user_locations_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_locations_ibfk_2` FOREIGN KEY (`location_id`) REFERENCES `locations` (`location_id`);

--
-- Constraints for table `user_preferences`
--
ALTER TABLE `user_preferences`
  ADD CONSTRAINT `user_preferences_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`),
  ADD CONSTRAINT `user_preferences_ibfk_2` FOREIGN KEY (`preferred_location_id`) REFERENCES `locations` (`location_id`),
  ADD CONSTRAINT `user_preferences_ibfk_3` FOREIGN KEY (`preferred_category_id`) REFERENCES `categories` (`category_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;