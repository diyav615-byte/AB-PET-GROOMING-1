-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 07, 2026 at 01:43 PM
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
-- Database: `ab_pet_grooming`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(100) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `password`) VALUES
(1, 'diyavarma', 'varma123');

-- --------------------------------------------------------

--
-- Table structure for table `appointments`
--

CREATE TABLE `appointments` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `pet_name` varchar(50) DEFAULT NULL,
  `pet_category` varchar(10) DEFAULT NULL,
  `breed` varchar(50) DEFAULT NULL,
  `pet_size` varchar(50) DEFAULT NULL,
  `pet_count` int(11) DEFAULT NULL,
  `multi_pet_note` text DEFAULT NULL,
  `main_service` varchar(100) DEFAULT NULL,
  `addons` text DEFAULT NULL,
  `appointment_date` date DEFAULT NULL,
  `appointment_time` time DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) NOT NULL DEFAULT 'Pending',
  `payment_method` varchar(20) NOT NULL DEFAULT 'cash',
  `payment_status` varchar(20) NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `appointments`
--

INSERT INTO `appointments` (`id`, `owner_name`, `email`, `phone`, `pet_name`, `pet_category`, `breed`, `pet_size`, `pet_count`, `multi_pet_note`, `main_service`, `addons`, `appointment_date`, `appointment_time`, `notes`, `created_at`, `status`, `payment_method`, `payment_status`) VALUES
(4, 'jiyaa', 'diyav615@gmail.com', '8795793795', 'husky', 'Dog', 'hssfjreh', 'Giant', 3, 'Est minima quis rep', 'Dog Ab\'s Special Package', 'Ear Cleaning, Sanitary Trimming, Face Trimming', '2026-04-29', '15:04:00', 'kgdfjkvkdf', '2026-04-25 09:34:14', 'Rejected', 'cash', 'pending'),
(8, 'hkxhuriuef', 'diyav615@gmail.com', '7984976458', 'hkshfk', 'Dog', 'nbmsd', 'Giant', 1, 'NA', 'Dog Puppy Hair Cut', 'Sanitary Trimming', '2026-05-27', '17:56:00', 'bcsdjhcsh', '2026-05-11 11:26:27', 'Pending', 'cash', 'pending'),
(9, 'kxfkj', 'diyav615@gmail.com', '3464383656', 'hgvjdhfs', 'Dog', 'hjhf', 'Puppy', 1, 'dnbfhwgjwe', 'Dog Ab\'s Special Package', 'Sanitary Trimming', '2026-05-22', '17:17:00', 'fbkfjkr', '2026-05-11 11:48:02', 'Pending', 'online', 'paid'),
(11, 'oju', 'diyav615@gmail.com', '7947747345', 'djhghjeg', 'Dog', 'dnkferjk', 'Large', 3, 'fbrjbf', 'Dog Puppy Full Basic Puppy Package', 'Face Trimming', '2026-06-07', '10:30:00', ' bxghld', '2026-06-05 16:14:32', 'Pending', 'cash', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `boarding`
--

CREATE TABLE `boarding` (
  `id` int(11) NOT NULL,
  `owner_name` varchar(100) DEFAULT NULL,
  `phone` varchar(15) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `city` varchar(100) DEFAULT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `pet_type` varchar(50) DEFAULT NULL,
  `plan` varchar(100) DEFAULT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` varchar(10) DEFAULT NULL,
  `gender` varchar(20) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `boarding_type` varchar(50) DEFAULT NULL,
  `emergency_contact` varchar(15) DEFAULT NULL,
  `checkin_date` date DEFAULT NULL,
  `checkout_date` date DEFAULT NULL,
  `vaccinated_confirm` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'active',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `boarding`
--

INSERT INTO `boarding` (`id`, `owner_name`, `phone`, `email`, `city`, `pet_name`, `pet_type`, `plan`, `breed`, `age`, `gender`, `notes`, `boarding_type`, `emergency_contact`, `checkin_date`, `checkout_date`, `vaccinated_confirm`, `created_at`, `status`, `payment_method`, `payment_status`) VALUES
(1, 'Diya Varma', '8591258062', 'diyav615@gmail.com', 'Chembur', 'mili', 'Dog', 'Diamond Plan', 'husky', '10', 'Male', 'allergy is of fruit', 'Day Boarding', '4785347586', '2026-05-22', '2026-07-23', 'Yes', '2026-04-16 00:58:13', 'completed', NULL, NULL),
(3, 'nitin', '9702715726', 'nitin@bmncollege.com', 'navi mumbai', 'abc', 'Dog', 'Luxury Room', 'husky', '2', 'Male', 'ab', 'Day Boarding', '9702715726', '2026-04-16', '2026-04-16', 'Yes', '2026-04-16 11:13:58', 'completed', NULL, NULL),
(8, 'Dewangi Verma', '8591258062', 'debu@gmail.com', 'indore', 'bruno', 'Dog', 'Diamond Plan', 'hgvhf', '4', 'Female', 'vjxzvgcsgcgccgcweuggy', 'Day Boarding', '8376678687', '2026-05-29', '2026-05-31', 'Yes', '2026-05-11 11:11:45', 'active', 'cash', 'pending');

-- --------------------------------------------------------

--
-- Table structure for table `bookings`
--

CREATE TABLE `bookings` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `pet_id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `booking_date` date NOT NULL,
  `start_time` time DEFAULT NULL,
  `end_time` time DEFAULT NULL,
  `status` enum('pending','confirmed','completed','cancelled') DEFAULT 'pending',
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` varchar(50) DEFAULT NULL,
  `total_price` decimal(10,2) DEFAULT NULL,
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `customer_name` varchar(100) DEFAULT NULL,
  `customer_phone` varchar(20) DEFAULT NULL,
  `pet_name` varchar(100) DEFAULT NULL,
  `pet_type` varchar(20) DEFAULT NULL,
  `service_name` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `contact_messages`
--

CREATE TABLE `contact_messages` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `subject` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `contact_messages`
--

INSERT INTO `contact_messages` (`id`, `name`, `email`, `phone`, `subject`, `message`, `created_at`) VALUES
(2, '261 Diya Varma', 'diyav615@gmail.com', '1535714876', '87y7878mnnkyhi', 'bjvvg', '2026-04-30 17:50:02'),
(4, 'trdtftdr', 'diyav615@gmail.com', '6t6fr75r6r7', 'gf8y7r', 'hbhyfets4qr6er436t', '2026-05-09 07:19:55');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `city` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `status` enum('active','inactive') DEFAULT 'active',
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pets`
--

CREATE TABLE `pets` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `breed` varchar(100) DEFAULT NULL,
  `age` int(11) DEFAULT NULL,
  `weight` decimal(5,2) DEFAULT NULL,
  `color` varchar(100) DEFAULT NULL,
  `medical_notes` text DEFAULT NULL,
  `allergies` text DEFAULT NULL,
  `vaccinated` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `pet_type` varchar(20) DEFAULT NULL,
  `pet_size` varchar(20) DEFAULT NULL,
  `special_notes` text DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pet_boarding`
--

CREATE TABLE `pet_boarding` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `type` enum('dog','cat') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pet_boarding`
--

INSERT INTO `pet_boarding` (`id`, `name`, `price`, `type`) VALUES
(5, 'Day Boarding (12 hrs/day)', 800.00, 'dog'),
(6, 'Per Day (24 hrs/day)', 1200.00, 'dog'),
(7, 'Luxury Room (per day)', 1500.00, 'dog'),
(8, 'Playing (6 hrs/day)', 500.00, 'dog'),
(9, 'Giant Breed (per day)', 1500.00, 'dog'),
(10, 'Per Day (Without Food)', 300.00, 'cat'),
(11, 'Silver Plan (10 days/year)', 10000.00, 'dog'),
(12, 'Gold Plan (20 days/year)', 20000.00, 'dog'),
(13, 'Diamond Plan (30 days/year)', 32000.00, 'dog'),
(14, 'Platinum Plan (60 days/year)', 60000.00, 'dog'),
(15, 'Long Term Plan (365 days/1 Year +)', 39999.00, 'dog');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `rating` int(11) DEFAULT NULL,
  `message` text DEFAULT NULL,
  `status` enum('pending','approved') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `name`, `rating`, `message`, `status`, `created_at`) VALUES
(8, 'NKREJ', 4, 'VERY GOOODDD', '', '2026-05-11 12:36:23'),
(9, 'diya', 5, 'befcwvehgcvshgwhe', 'approved', '2026-06-05 15:43:45');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `title` varchar(100) DEFAULT NULL,
  `price` varchar(50) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(50) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `is_active` tinyint(1) DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_cards`
--

CREATE TABLE `service_cards` (
  `id` int(11) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `category` enum('dog','cat') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_cards`
--

INSERT INTO `service_cards` (`id`, `title`, `category`) VALUES
(1, 'Essential Package', 'dog'),
(2, 'Classic Package', 'dog'),
(3, 'Ab\'s Special Package', 'dog'),
(4, 'Add-On Services', 'dog'),
(5, 'HairCut', 'dog'),
(6, 'Puppies( Younger than 6 months)', 'dog'),
(7, 'Essential Package', 'cat'),
(8, 'Classic Package', 'cat'),
(9, 'Ab\'s Special Package', 'cat'),
(10, 'Add-On Services', 'cat'),
(11, 'Only Haircut { Cats above six months will consider as an adult}', 'cat');

-- --------------------------------------------------------

--
-- Table structure for table `service_card_items`
--

CREATE TABLE `service_card_items` (
  `id` int(11) NOT NULL,
  `service_id` int(11) DEFAULT NULL,
  `type` enum('item','breed') DEFAULT NULL,
  `name` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_card_items`
--

INSERT INTO `service_card_items` (`id`, `service_id`, `type`, `name`, `price`) VALUES
(17, 3, 'item', 'Bath', NULL),
(18, 3, 'item', 'Blow Dry', NULL),
(19, 3, 'item', 'Ear Cleaning', NULL),
(20, 3, 'item', 'Nail Clipping and Grinding', NULL),
(21, 3, 'item', 'Hair cut', NULL),
(22, 3, 'item', 'Sanitary Trimming', NULL),
(23, 3, 'breed', 'Small', 2000.00),
(24, 3, 'breed', 'Large', 2250.00),
(25, 3, 'breed', 'Giant', 2700.00),
(26, 4, 'item', 'Ear Cleaning', 150.00),
(27, 4, 'item', 'Nail Clipping and Grinding', 200.00),
(28, 4, 'item', 'Sanitary Trimming', 350.00),
(29, 4, 'item', ' Teeth Brushing', 150.00),
(30, 4, 'item', 'Face Trimming', 300.00),
(31, 4, 'item', 'Medicated Bath', 350.00),
(32, 4, 'item', 'Deshedding', 500.00),
(33, 5, 'breed', 'Small Breed ', 1000.00),
(34, 5, 'breed', 'Large Breed', 1200.00),
(35, 5, 'breed', 'Giant Breed', 2000.00),
(36, 6, 'item', 'Bath + Blow Dry', 350.00),
(37, 6, 'item', 'Bath + Blow Dry + Ear Cleaning + Nail Clipping and Grinding', 500.00),
(38, 6, 'item', 'HairCut', 500.00),
(43, 8, 'item', 'Bath ', NULL),
(44, 8, 'item', 'Blow Dry', NULL),
(45, 8, 'item', 'Nail Clipping and Grinding', NULL),
(46, 8, 'item', 'Ear Cleaning', NULL),
(47, 8, 'item', 'Sanitary Trimming', NULL),
(48, 8, 'breed', 'Adult', 1000.00),
(49, 8, 'breed', 'Kitten', 700.00),
(50, 7, 'item', 'Bath', NULL),
(51, 7, 'item', 'Blow Dry', NULL),
(52, 7, 'breed', 'Adult', 800.00),
(53, 7, 'breed', 'Kitten', 500.00),
(62, 9, 'item', 'Bath', NULL),
(63, 9, 'item', 'Blow Dry', NULL),
(64, 9, 'item', 'Ear Cleaning', NULL),
(65, 9, 'item', 'Nail Clipping and Grinding', NULL),
(66, 9, 'item', 'Haircut', NULL),
(67, 9, 'item', 'Sanitary Trimming', NULL),
(68, 9, 'breed', 'Adult', 1650.00),
(69, 9, 'breed', 'Kitten', 1000.00),
(82, 1, 'item', 'Bath', NULL),
(83, 1, 'item', 'Blow Dry', NULL),
(84, 1, 'item', 'Fragrance', NULL),
(85, 1, 'breed', 'Small', 750.00),
(86, 1, 'breed', 'Large', 950.00),
(87, 1, 'breed', 'Giant', 1200.00),
(88, 2, 'item', 'Bath', NULL),
(89, 2, 'item', 'Blow Dry', NULL),
(90, 2, 'item', 'Ear Cleaning', NULL),
(91, 2, 'item', 'Nail Clipping and Grinding', NULL),
(92, 2, 'item', 'Sanitary Trimming', NULL),
(93, 2, 'breed', 'Small', 1000.00),
(94, 2, 'breed', 'Large', 1200.00),
(95, 2, 'breed', 'Giant', 1650.00),
(96, 10, 'item', 'Ear Cleaning', 150.00),
(97, 10, 'item', 'Nail Clipping and Grinding', 200.00),
(98, 10, 'item', 'Sanitary Trimming', 350.00),
(99, 10, 'item', 'Medicated Bath', 300.00),
(100, 10, 'item', 'Deshedding', 200.00),
(101, 11, 'breed', 'Adult', 1000.00),
(102, 11, 'breed', 'Kitten', 600.00);

-- --------------------------------------------------------

--
-- Table structure for table `settings`
--

CREATE TABLE `settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` varchar(20) DEFAULT 'string',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'site_name', 'AB Pet Grooming', 'string', 1, '2026-04-24 17:37:21', '2026-04-24 17:37:21'),
(2, 'site_email', 'admin@petgrooming.com', 'string', 1, '2026-04-24 17:37:21', '2026-04-24 17:37:21'),
(3, 'site_phone', '+918828719786', 'string', 1, '2026-04-24 17:37:21', '2026-04-24 17:37:21'),
(4, 'daily_slot_limit', '10', 'number', 1, '2026-04-24 17:37:21', '2026-04-24 17:37:21'),
(5, 'opening_hours', '10:30 AM - 7:00 PM', 'string', 1, '2026-04-24 17:37:21', '2026-04-24 17:37:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `appointments`
--
ALTER TABLE `appointments`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `boarding`
--
ALTER TABLE `boarding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`),
  ADD KEY `pet_id` (`pet_id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `contact_messages`
--
ALTER TABLE `contact_messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_customers_status` (`is_active`);

--
-- Indexes for table `pets`
--
ALTER TABLE `pets`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- Indexes for table `pet_boarding`
--
ALTER TABLE `pet_boarding`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_cards`
--
ALTER TABLE `service_cards`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `service_card_items`
--
ALTER TABLE `service_card_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`);

--
-- Indexes for table `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `appointments`
--
ALTER TABLE `appointments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `boarding`
--
ALTER TABLE `boarding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `contact_messages`
--
ALTER TABLE `contact_messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pets`
--
ALTER TABLE `pets`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pet_boarding`
--
ALTER TABLE `pet_boarding`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_cards`
--
ALTER TABLE `service_cards`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `service_card_items`
--
ALTER TABLE `service_card_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_2` FOREIGN KEY (`pet_id`) REFERENCES `pets` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_ibfk_3` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`);

--
-- Constraints for table `pets`
--
ALTER TABLE `pets`
  ADD CONSTRAINT `pets_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `service_card_items`
--
ALTER TABLE `service_card_items`
  ADD CONSTRAINT `service_card_items_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `service_cards` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
