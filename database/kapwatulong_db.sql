-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 04, 2025 at 05:18 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kapwatulong_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `admin_id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`admin_id`, `username`, `password`, `email`, `created_at`) VALUES
(1, 'admin', '0192023a7bbd73250516f069df18b500', 'admin@kapwatulong.com', '2025-03-04 16:10:55');

-- --------------------------------------------------------

--
-- Stand-in structure for view `admin_dashboard_stats`
-- (See below for the actual view)
--
CREATE TABLE `admin_dashboard_stats` (
`total_campaigns` bigint(21)
,`pending_campaigns` decimal(22,0)
,`approved_campaigns` decimal(22,0)
,`rejected_campaigns` decimal(22,0)
,`completed_campaigns` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Table structure for table `campaigns`
--

CREATE TABLE `campaigns` (
  `campaign_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `city_province` varchar(100) NOT NULL,
  `zip_code` varchar(10) NOT NULL,
  `target_amount` decimal(10,2) NOT NULL,
  `current_amount` decimal(10,2) DEFAULT 0.00,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `end_date` date DEFAULT NULL,
  `admin_notes` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaigns`
--

INSERT INTO `campaigns` (`campaign_id`, `title`, `description`, `city_province`, `zip_code`, `target_amount`, `current_amount`, `status`, `created_by`, `created_at`, `updated_at`, `end_date`, `admin_notes`) VALUES
(1, 'Help Build a School in Rural Area', 'We are raising funds to build a new school in a rural community. This will provide education opportunities for over 200 children who currently have to walk 5km to the nearest school.', 'Bukidnon', '8700', 500000.00, 0.00, 'pending', 1, '2025-03-04 14:38:13', '2025-03-04 14:38:13', NULL, NULL),
(2, 'Medical Supplies for Local Hospital', 'Our local hospital is in urgent need of medical supplies. Funds will be used to purchase essential medical equipment and supplies to better serve our community.', 'Manila', '1000', 250000.00, 0.00, 'approved', 2, '2025-03-04 14:38:13', '2025-03-04 14:38:13', NULL, NULL),
(3, 'Disaster Relief for Typhoon Victims', 'Emergency relief fund for families affected by the recent typhoon. Funds will be used for food, water, and temporary shelter.', 'Leyte', '6500', 1000000.00, 0.00, 'rejected', 1, '2025-03-04 14:38:13', '2025-03-04 14:38:13', NULL, NULL),
(4, 'Help Build a School in Rural Area', 'We are raising funds to build a new school in a rural community. This will provide education opportunities for over 200 children who currently have to walk 5km to the nearest school.', 'Bukidnon', '8700', 500000.00, 0.00, 'pending', 1, '2025-03-04 14:38:40', '2025-03-04 14:38:40', NULL, NULL),
(5, 'Medical Supplies for Local Hospital', 'Our local hospital is in urgent need of medical supplies. Funds will be used to purchase essential medical equipment and supplies to better serve our community.', 'Manila', '1000', 250000.00, 0.00, 'approved', 2, '2025-03-04 14:38:40', '2025-03-04 14:38:40', NULL, NULL),
(6, 'Disaster Relief for Typhoon Victims', 'Emergency relief fund for families affected by the recent typhoon. Funds will be used for food, water, and temporary shelter.', 'Leyte', '6500', 1000000.00, 0.00, 'rejected', 1, '2025-03-04 14:38:40', '2025-03-04 14:38:40', NULL, NULL);

--
-- Triggers `campaigns`
--
DELIMITER $$
CREATE TRIGGER `check_campaign_completion` BEFORE UPDATE ON `campaigns` FOR EACH ROW BEGIN
    -- Check if the current amount has reached or exceeded the target amount
    -- and the campaign is approved
    IF NEW.current_amount >= NEW.target_amount AND NEW.status = 'approved' THEN
        SET NEW.status = 'completed';
        SET NEW.end_date = CURRENT_DATE();
    END IF;
END
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `campaign_categories`
--

CREATE TABLE `campaign_categories` (
  `category_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_categories`
--

INSERT INTO `campaign_categories` (`category_id`, `name`) VALUES
(4, 'Animal'),
(7, 'Children'),
(6, 'Community'),
(3, 'Disaster'),
(1, 'Education'),
(9, 'Elderly'),
(5, 'Environment'),
(2, 'Health'),
(11, 'Housing'),
(10, 'Hunger'),
(8, 'Mental Health');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_category_relations`
--

CREATE TABLE `campaign_category_relations` (
  `campaign_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_category_relations`
--

INSERT INTO `campaign_category_relations` (`campaign_id`, `category_id`) VALUES
(1, 1),
(1, 6),
(2, 2),
(2, 6),
(3, 3),
(3, 6);

-- --------------------------------------------------------

--
-- Stand-in structure for view `campaign_details`
-- (See below for the actual view)
--
CREATE TABLE `campaign_details` (
`campaign_id` int(11)
,`title` varchar(255)
,`description` text
,`city_province` varchar(100)
,`zip_code` varchar(10)
,`target_amount` decimal(10,2)
,`current_amount` decimal(10,2)
,`status` enum('pending','approved','rejected')
,`created_by` int(11)
,`created_at` timestamp
,`updated_at` timestamp
,`end_date` date
,`admin_notes` text
,`categories` mediumtext
,`images` mediumtext
,`creator_name` varchar(50)
);

-- --------------------------------------------------------

--
-- Table structure for table `campaign_images`
--

CREATE TABLE `campaign_images` (
  `image_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `image_path` varchar(255) NOT NULL,
  `is_primary` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_images`
--

INSERT INTO `campaign_images` (`image_id`, `campaign_id`, `image_path`, `is_primary`, `created_at`) VALUES
(1, 1, 'uploads/campaigns/school_campaign.jpg', 1, '2025-03-04 14:38:40'),
(2, 2, 'uploads/campaigns/hospital_campaign.jpg', 1, '2025-03-04 14:38:40'),
(3, 3, 'uploads/campaigns/typhoon_campaign.jpg', 1, '2025-03-04 14:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `campaign_reviews`
--

CREATE TABLE `campaign_reviews` (
  `review_id` int(11) NOT NULL,
  `campaign_id` int(11) NOT NULL,
  `reviewed_by` int(11) NOT NULL,
  `status` enum('pending','approved','rejected') NOT NULL,
  `comments` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `campaign_reviews`
--

INSERT INTO `campaign_reviews` (`review_id`, `campaign_id`, `reviewed_by`, `status`, `comments`, `created_at`) VALUES
(1, 1, 3, 'pending', NULL, '2025-03-04 14:38:40'),
(2, 2, 3, 'approved', 'Campaign meets all requirements and guidelines.', '2025-03-04 14:38:40'),
(3, 3, 3, 'rejected', 'Please provide more detailed information about fund allocation and timeline.', '2025-03-04 14:38:40');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(32) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `username`, `email`, `password`) VALUES
(1, 'CodieOamil', 'codieoamil03@gmail.com', '678de37a15a9a6224b23f953ec15777c'),
(2, 'Juliane', 'canahkrendiepalad@gmail.com', '678de37a15a9a6224b23f953ec15777c'),
(3, 'testuser1', 'test1@example.com', '482c811da5d5b4bc6d497ffa98491e38'),
(4, 'testuser2', 'test2@example.com', '482c811da5d5b4bc6d497ffa98491e38'),
(5, 'admin1', 'admin1@example.com', '0192023a7bbd73250516f069df18b500'),
(6, 'testuser1', 'test1@example.com', '482c811da5d5b4bc6d497ffa98491e38'),
(7, 'testuser2', 'test2@example.com', '482c811da5d5b4bc6d497ffa98491e38'),
(8, 'admin1', 'admin1@example.com', '0192023a7bbd73250516f069df18b500');

-- --------------------------------------------------------

--
-- Structure for view `admin_dashboard_stats`
--
DROP TABLE IF EXISTS `admin_dashboard_stats`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `admin_dashboard_stats`  AS SELECT count(0) AS `total_campaigns`, sum(case when `campaigns`.`status` = 'pending' then 1 else 0 end) AS `pending_campaigns`, sum(case when `campaigns`.`status` = 'approved' then 1 else 0 end) AS `approved_campaigns`, sum(case when `campaigns`.`status` = 'rejected' then 1 else 0 end) AS `rejected_campaigns`, sum(case when `campaigns`.`status` = 'completed' then 1 else 0 end) AS `completed_campaigns` FROM `campaigns` ;

-- --------------------------------------------------------

--
-- Structure for view `campaign_details`
--
DROP TABLE IF EXISTS `campaign_details`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `campaign_details`  AS SELECT `c`.`campaign_id` AS `campaign_id`, `c`.`title` AS `title`, `c`.`description` AS `description`, `c`.`city_province` AS `city_province`, `c`.`zip_code` AS `zip_code`, `c`.`target_amount` AS `target_amount`, `c`.`current_amount` AS `current_amount`, `c`.`status` AS `status`, `c`.`created_by` AS `created_by`, `c`.`created_at` AS `created_at`, `c`.`updated_at` AS `updated_at`, `c`.`end_date` AS `end_date`, `c`.`admin_notes` AS `admin_notes`, group_concat(distinct `cc`.`name` separator ',') AS `categories`, group_concat(distinct `ci`.`image_path` separator ',') AS `images`, `u`.`username` AS `creator_name` FROM ((((`campaigns` `c` left join `campaign_category_relations` `ccr` on(`c`.`campaign_id` = `ccr`.`campaign_id`)) left join `campaign_categories` `cc` on(`ccr`.`category_id` = `cc`.`category_id`)) left join `campaign_images` `ci` on(`c`.`campaign_id` = `ci`.`campaign_id`)) left join `users` `u` on(`c`.`created_by` = `u`.`Id`)) GROUP BY `c`.`campaign_id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`admin_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`campaign_id`),
  ADD KEY `idx_campaign_status` (`status`),
  ADD KEY `idx_campaign_created_by` (`created_by`),
  ADD KEY `idx_campaign_created_at` (`created_at`);

--
-- Indexes for table `campaign_categories`
--
ALTER TABLE `campaign_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Indexes for table `campaign_category_relations`
--
ALTER TABLE `campaign_category_relations`
  ADD PRIMARY KEY (`campaign_id`,`category_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `campaign_images`
--
ALTER TABLE `campaign_images`
  ADD PRIMARY KEY (`image_id`),
  ADD KEY `idx_campaign_images_campaign_id` (`campaign_id`);

--
-- Indexes for table `campaign_reviews`
--
ALTER TABLE `campaign_reviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `campaign_id` (`campaign_id`),
  ADD KEY `reviewed_by` (`reviewed_by`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `admin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `campaigns`
--
ALTER TABLE `campaigns`
  MODIFY `campaign_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `campaign_categories`
--
ALTER TABLE `campaign_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT for table `campaign_images`
--
ALTER TABLE `campaign_images`
  MODIFY `image_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `campaign_reviews`
--
ALTER TABLE `campaign_reviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `campaigns`
--
ALTER TABLE `campaigns`
  ADD CONSTRAINT `campaigns_ibfk_1` FOREIGN KEY (`created_by`) REFERENCES `users` (`Id`);

--
-- Constraints for table `campaign_category_relations`
--
ALTER TABLE `campaign_category_relations`
  ADD CONSTRAINT `campaign_category_relations_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_category_relations_ibfk_2` FOREIGN KEY (`category_id`) REFERENCES `campaign_categories` (`category_id`);

--
-- Constraints for table `campaign_images`
--
ALTER TABLE `campaign_images`
  ADD CONSTRAINT `campaign_images_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE;

--
-- Constraints for table `campaign_reviews`
--
ALTER TABLE `campaign_reviews`
  ADD CONSTRAINT `campaign_reviews_ibfk_1` FOREIGN KEY (`campaign_id`) REFERENCES `campaigns` (`campaign_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `campaign_reviews_ibfk_2` FOREIGN KEY (`reviewed_by`) REFERENCES `users` (`Id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
