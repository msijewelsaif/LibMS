-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 18, 2024 at 09:51 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `library_ms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin_users`
--

CREATE TABLE `admin_users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `admin_name` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin_users`
--

INSERT INTO `admin_users` (`id`, `username`, `admin_name`, `password`, `created_at`) VALUES
(1, 'saiful', 'Md Saiful Islam', '123456', '2024-09-25 18:51:26');

-- --------------------------------------------------------

--
-- Table structure for table `book_reviews`
--

CREATE TABLE `book_reviews` (
  `id` int(11) NOT NULL,
  `book_id` int(11) NOT NULL,
  `username` varchar(110) NOT NULL,
  `review` text NOT NULL,
  `rating` int(1) NOT NULL,
  `review_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_reviews`
--

INSERT INTO `book_reviews` (`id`, `book_id`, `username`, `review`, `rating`, `review_date`) VALUES
(1, 1, '2002047', 'This Is a good book', 4, '2024-11-27 18:33:58'),
(2, 1, '2002047', 'This Is a good book', 4, '2024-11-27 18:38:20');

-- --------------------------------------------------------

--
-- Table structure for table `borrowed_books`
--

CREATE TABLE `borrowed_books` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `mobile` varchar(20) NOT NULL,
  `book_id` int(11) NOT NULL,
  `borrowed_date` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` enum('pending','approved','returned') DEFAULT 'pending',
  `return_date` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrowed_books`
--

INSERT INTO `borrowed_books` (`id`, `user_id`, `email`, `mobile`, `book_id`, `borrowed_date`, `status`, `return_date`) VALUES
(1, 2002047, '2002047@icte.bdu.ac.bd', '01884785314', 3, '2024-11-26 16:55:17', 'returned', '2024-12-06'),
(2, 2002047, '2002047@icte.bdu.ac.bd', '01884785314', 3, '2024-12-09 09:39:23', 'returned', '2024-12-19'),
(4, 2002045, '2002045@icte.bdu.ac.bd', '01887489978', 7, '2024-12-18 06:04:01', 'approved', '2024-12-28');

-- --------------------------------------------------------

--
-- Table structure for table `complaints`
--

CREATE TABLE `complaints` (
  `complaint_id` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `complaint_text` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `status` varchar(20) DEFAULT 'pending',
  `resolved_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `complaints`
--

INSERT INTO `complaints` (`complaint_id`, `username`, `complaint_text`, `created_at`, `status`, `resolved_at`) VALUES
(1, 'saiful ', 'Book Missing ', '2024-09-28 07:47:10', 'rejected', '0000-00-00 00:00:00'),
(2, '2002047', 'Machine Learning Book is Missing', '2024-11-26 06:28:53', 'rejected', '0000-00-00 00:00:00'),
(3, '2002047', 'Book Missing', '2024-11-26 06:38:39', 'pending', NULL),
(4, '2002047', 'Book Missing', '2024-11-26 07:15:44', 'pending', NULL),
(5, '2002047', 'gkjjj', '2024-12-03 08:18:45', 'rejected', '0000-00-00 00:00:00'),
(6, '2002047', 'Please, Add a book named Hands On Machine Learning. Its a important book which usually needs for each of edtech students.', '2024-12-09 09:44:46', 'pending', NULL),
(7, '2002047', 'Book Missing', '2024-12-18 04:27:13', 'resolved', '2024-12-17 23:28:45'),
(8, '2002047', 'Needs Hands On Machine Learning BOOK', '2024-12-18 04:43:48', 'pending', NULL),
(9, '2002045', 'Please Fix the chairs in Library.', '2024-12-18 06:04:41', 'resolved', '2024-12-18 01:42:59'),
(10, '2002047', 'Table are broken', '2024-12-18 06:47:42', 'pending', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `number` varchar(20) DEFAULT NULL,
  `email` varchar(100) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `user_id`, `name`, `number`, `email`, `message`, `created_at`) VALUES
(2, 2002046, 'Md Safanur Islam ', '019856444556', '2002049@icte.bdu.ac.bd', 'Due Exam ', '2024-11-26 07:00:07'),
(3, 2002047, 'Md Saiful Islam', '01884785314', '2002047@icte.bdu.ac.bd', 'Due to having exam, I cant return the book on the deadline. I would Request you to increase deadline.', '2024-12-09 09:46:23'),
(4, 2002045, 'Jahid Hasan Himel', '01887489978', '2002045@icte.bdu.ac.bd', 'Due to Preparatory Leave, I would be not able to provide you the book on the time.', '2024-12-18 06:07:06');

-- --------------------------------------------------------

--
-- Table structure for table `offline_books`
--

CREATE TABLE `offline_books` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `image` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `offline_books`
--

INSERT INTO `offline_books` (`id`, `name`, `author`, `quantity`, `image`, `created_at`, `updated_at`) VALUES
(3, 'Introduction to Data Science', 'Mohammed Ali', 5, '1.jpg', '2024-09-26 16:51:00', '2024-12-18 06:42:15'),
(5, 'Data Science Boot Camp', 'Leonard Apetlsm', 29, '2.jpg', '2024-12-09 08:25:27', '2024-12-09 09:53:30'),
(6, 'Deep Learning for Natural Learning Process', 'Stephen Raiijmaker', 15, '3.jpg', '2024-12-09 08:28:51', '2024-12-09 08:28:51'),
(7, 'High Performance Python', 'Micho Goerilick', 8, '4.png', '2024-12-09 08:32:12', '2024-12-18 06:42:21'),
(8, 'Python-for-DevOps-What-is-a-DevOp-Engineer', 'Noah Gift', 50, '5.jpg', '2024-12-09 08:34:03', '2024-12-09 08:34:03'),
(9, 'The Everything Accounting Book', 'Michele Cagan', 70, '6.jpg', '2024-12-09 09:42:35', '2024-12-09 09:42:35');

-- --------------------------------------------------------

--
-- Table structure for table `online_books`
--

CREATE TABLE `online_books` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `author` varchar(255) NOT NULL,
  `pdf` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `online_books`
--

INSERT INTO `online_books` (`id`, `name`, `author`, `pdf`, `created_at`, `updated_at`) VALUES
(1, 'C++', 'Dellanoy C.', 'C++ pour les programmeurs C.pdf', '2024-12-09 08:53:46', '2024-12-09 08:53:46'),
(3, 'Competitive Programmer’s Handbook', 'Antti Laaksonen', 'Competitive Programming.pdf', '2024-12-09 08:57:54', '2024-12-09 08:57:54'),
(4, ' Introduction to Computer Programming with Python', 'Harris Wang', 'OER-202301_Wang_2023-Introduction-to-Computer-Programming-with-Python.pdf', '2024-12-09 09:07:19', '2024-12-09 09:07:19'),
(5, 'The 48 Laws of Power PDF', ' Robert Greene', '48 Laws of Power, The - Robert Greene.pdf', '2024-12-09 09:11:57', '2024-12-09 09:11:57'),
(6, 'The Art of UNIX Programming', 'Eric Steven Raymond', 'the_art_of_unix_programming (1).pdf', '2024-12-09 09:16:43', '2024-12-09 09:16:43'),
(7, 'The Story Of Doctor Dollitle', 'Hugh Lofting', 'the-story-of-doctor-dolittle.pdf', '2024-12-18 06:41:41', '2024-12-18 06:41:41');

-- --------------------------------------------------------

--
-- Table structure for table `students`
--

CREATE TABLE `students` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `student_id` varchar(20) NOT NULL,
  `dept` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `otp` varchar(6) DEFAULT NULL,
  `is_verified` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `students`
--

INSERT INTO `students` (`id`, `name`, `username`, `student_id`, `dept`, `email`, `phone`, `password`, `otp`, `is_verified`, `created_at`) VALUES
(2, 'Md Saiful Islam', '2002047', '2002047', 'Edtech', '2002047@icte.bdu.ac.bd', '0188478531', '$2y$10$siEJtRPrVM.u.mKovNcFruj3rAPswGzLjNnNla5KAhg1WlJMk0sGe', '784914', 1, '2024-09-23 19:42:54'),
(3, 'Md Jahid Hasan Himel', '2002045', '2002045', 'EdTE', '2002045@icte.bdu.ac.bd', '01887489978', '$2y$10$MVQnZqelrLhue81tMqavIOwdWUWifhiavekvlrrKrlCXmLlSCBFKm', '989363', 1, '2024-12-18 06:03:22'),
(4, 'MD SAFANUR ISLAM', '2002046', '2002046', 'EdTE', '2002046@icte.bdu.ac.bd', '01884785314', '$2y$10$1pGL9lrgSgDwKhooGxMEB.M/GM9cfjVA0OUKFgu6RasMFgZiAqD.O', '622825', 1, '2024-12-18 06:46:21');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin_users`
--
ALTER TABLE `admin_users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `book_reviews`
--
ALTER TABLE `book_reviews`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD PRIMARY KEY (`id`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `complaints`
--
ALTER TABLE `complaints`
  ADD PRIMARY KEY (`complaint_id`);

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `offline_books`
--
ALTER TABLE `offline_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `online_books`
--
ALTER TABLE `online_books`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin_users`
--
ALTER TABLE `admin_users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `book_reviews`
--
ALTER TABLE `book_reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `complaints`
--
ALTER TABLE `complaints`
  MODIFY `complaint_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `offline_books`
--
ALTER TABLE `offline_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `online_books`
--
ALTER TABLE `online_books`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `students`
--
ALTER TABLE `students`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `borrowed_books`
--
ALTER TABLE `borrowed_books`
  ADD CONSTRAINT `borrowed_books_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `offline_books` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
