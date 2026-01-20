-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 18, 2026 at 08:51 AM
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
-- Database: `maindb`
--

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'Programming'),
(2, 'Web Development'),
(3, 'Mobile App Development'),
(4, 'Data Science'),
(5, 'Artificial Intelligence'),
(6, 'Machine Learning'),
(7, 'Cyber Security'),
(8, 'Cloud Computing'),
(9, 'Database Management'),
(10, 'Software Engineering');

-- --------------------------------------------------------

--
-- Table structure for table `certificates`
--

CREATE TABLE `certificates` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `issue_date` date DEFAULT NULL,
  `certificate_url` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `certificates`
--

INSERT INTO `certificates` (`id`, `user_id`, `course_id`, `issue_date`, `certificate_url`) VALUES
(1, 13, 5, '2026-01-18', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `courses`
--

CREATE TABLE `courses` (
  `id` int(11) NOT NULL,
  `title` varchar(200) NOT NULL,
  `description` text DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `instructor_id` int(11) DEFAULT NULL,
  `course_image` varchar(255) DEFAULT 'default-course.png',
  `difficulty` enum('Beginner','Intermediate','Advanced') DEFAULT NULL,
  `duration` varchar(50) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT 0.00,
  `rating` float DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `courses`
--

INSERT INTO `courses` (`id`, `title`, `description`, `category_id`, `instructor_id`, `course_image`, `difficulty`, `duration`, `price`, `rating`, `created_at`) VALUES
(5, 'Programming with C++', 'Basic to advanced C++ programming', 1, 3, 'default.png', 'Advanced', '2 Months', 5.00, 0, '2025-12-30 10:16:56'),
(6, 'Advance in Python', 'this couse is made for professional , not for beginners.', 1, NULL, 'course_695adc8dd3916.jpg', 'Advanced', '2', 50.00, 4.2, '2026-01-04 21:33:01'),
(7, 'Mang', 'all about mang', 7, NULL, 'course_695be1658a672.jpg', 'Advanced', '5 Month', 200.00, 5, '2026-01-05 16:05:57');

-- --------------------------------------------------------

--
-- Table structure for table `enrollments`
--

CREATE TABLE `enrollments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `payment_status` enum('free','paid') DEFAULT 'free',
  `enrolled_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `enrollments`
--

INSERT INTO `enrollments` (`id`, `user_id`, `course_id`, `payment_status`, `enrolled_at`) VALUES
(1, 2, 5, 'free', '2026-01-17 13:42:04'),
(2, 13, 5, 'free', '2026-01-18 05:58:31'),
(3, 13, 6, 'free', '2026-01-18 07:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `faqs`
--

CREATE TABLE `faqs` (
  `id` int(11) NOT NULL,
  `category` varchar(100) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `answer` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `forum_posts`
--

CREATE TABLE `forum_posts` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `instructors`
--

CREATE TABLE `instructors` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `bio` text DEFAULT NULL,
  `expertise` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `lessons`
--

CREATE TABLE `lessons` (
  `id` int(11) NOT NULL,
  `course_id` int(11) NOT NULL,
  `title` varchar(200) DEFAULT NULL,
  `video_url` varchar(255) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `lesson_order` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessons`
--

INSERT INTO `lessons` (`id`, `course_id`, `title`, `video_url`, `content`, `lesson_order`) VALUES
(1, 5, 'Introduction to C++', 'https://example.com/cpp-intro', 'Overview of C++ programming, history, and applications.', 1),
(2, 5, 'C++ Syntax and Variables', 'https://example.com/cpp-syntax', 'Learn basic syntax, data types, and variable declaration in C++.', 2),
(3, 5, 'Control Statements', 'https://example.com/cpp-control', 'If-else, switch-case, and looping structures in C++.', 3),
(4, 5, 'Functions and Arrays', 'https://example.com/cpp-functions', 'User-defined functions, arrays, and parameter passing.', 4),
(5, 5, 'Object-Oriented Programming', 'https://example.com/cpp-oop', 'Classes, objects, inheritance, polymorphism, and encapsulation.', 5),
(6, 6, 'Advanced Python Overview', 'https://example.com/python-advanced-intro', 'Course overview and expectations for advanced Python developers.', 1),
(7, 6, 'Python OOP Deep Dive', 'https://example.com/python-oop', 'Advanced object-oriented programming concepts in Python.', 2),
(8, 6, 'File Handling and Exception Handling', 'https://example.com/python-files', 'Working with files and handling runtime errors properly.', 3),
(9, 6, 'Modules, Packages & Virtual Environments', 'https://example.com/python-modules', 'Using modules, creating packages, and managing environments.', 4),
(10, 6, 'Python for Real Projects', 'https://example.com/python-projects', 'Best practices and real-world project structure.', 5),
(11, 7, 'Introduction to Cyber Security', 'https://example.com/cyber-intro', 'Fundamentals of cyber security and threat landscape.', 1),
(12, 7, 'Malware and Attacks', 'https://example.com/cyber-malware', 'Understanding malware, viruses, ransomware, and attacks.', 2),
(13, 7, 'Network Security Basics', 'https://example.com/cyber-network', 'Firewalls, IDS, IPS, and network defense techniques.', 3),
(14, 7, 'Ethical Hacking Overview', 'https://example.com/cyber-hacking', 'Introduction to ethical hacking and penetration testing.', 4),
(15, 7, 'Cyber Security Tools', 'https://example.com/cyber-tools', 'Popular security tools used by professionals.', 5);

-- --------------------------------------------------------

--
-- Table structure for table `payments`
--

CREATE TABLE `payments` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `payment_method` varchar(50) DEFAULT NULL,
  `payment_status` enum('success','failed') DEFAULT NULL,
  `paid_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `payments`
--

INSERT INTO `payments` (`id`, `user_id`, `course_id`, `amount`, `payment_method`, `payment_status`, `paid_at`) VALUES
(1, 2, 5, 5.00, 'card', 'success', '2026-01-17 13:42:04'),
(2, 13, 5, 5.00, 'card', 'success', '2026-01-18 05:58:31'),
(3, 13, 6, 50.00, 'card', 'success', '2026-01-18 07:36:59');

-- --------------------------------------------------------

--
-- Table structure for table `progress`
--

CREATE TABLE `progress` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `completed_percentage` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `progress`
--

INSERT INTO `progress` (`id`, `user_id`, `course_id`, `completed_percentage`) VALUES
(1, 2, 5, 0),
(2, 13, 5, 100),
(3, 13, 6, 0);

-- --------------------------------------------------------

--
-- Table structure for table `quizzes`
--

CREATE TABLE `quizzes` (
  `id` int(11) NOT NULL,
  `course_id` int(11) DEFAULT NULL,
  `question` text DEFAULT NULL,
  `option_a` varchar(255) DEFAULT NULL,
  `option_b` varchar(255) DEFAULT NULL,
  `option_c` varchar(255) DEFAULT NULL,
  `option_d` varchar(255) DEFAULT NULL,
  `correct_option` char(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quizzes`
--

INSERT INTO `quizzes` (`id`, `course_id`, `question`, `option_a`, `option_b`, `option_c`, `option_d`, `correct_option`) VALUES
(1, 5, 'Which of the following is a correct C++ variable declaration?', 'int x;', 'x int;', 'integer x;', 'declare x;', 'A'),
(2, 5, 'Which concept allows data hiding in C++?', 'Inheritance', 'Encapsulation', 'Polymorphism', 'Abstraction', 'B'),
(3, 5, 'Which loop executes at least once in C++?', 'for loop', 'while loop', 'do-while loop', 'foreach loop', 'C'),
(4, 5, 'What is the correct syntax to define a class in C++?', 'class MyClass {}', 'MyClass class {}', 'define class MyClass', 'class = MyClass', 'A'),
(5, 5, 'Which symbol is used to access class members through object?', '.', '->', '::', '*', 'A'),
(6, 6, 'Which keyword is used to create a class in Python?', 'function', 'def', 'class', 'object', 'C'),
(7, 6, 'Which method is called automatically when an object is created?', '__start__()', '__init__()', '__create__()', '__main__()', 'B'),
(8, 6, 'Which keyword is used for exception handling?', 'try', 'catch', 'error', 'handle', 'A'),
(9, 6, 'Which file is used to manage Python virtual environments?', 'requirements.txt', 'env.txt', 'packages.json', 'virtual.txt', 'A'),
(10, 6, 'Which module is commonly used for file handling?', 'os', 'file', 'open', 'io', 'D'),
(11, 7, 'What is the primary goal of cyber security?', 'Increase internet speed', 'Protect systems and data', 'Create software', 'Design networks', 'B'),
(12, 7, 'Which type of malware encrypts files and demands payment?', 'Virus', 'Worm', 'Ransomware', 'Trojan', 'C'),
(13, 7, 'What does a firewall do?', 'Stores data', 'Blocks unauthorized access', 'Encrypts passwords', 'Detects viruses', 'B'),
(14, 7, 'Ethical hacking is also known as?', 'Black hat hacking', 'Illegal hacking', 'White hat hacking', 'Grey hacking', 'C'),
(15, 7, 'Which tool is commonly used for penetration testing?', 'Photoshop', 'Wireshark', 'MS Word', 'Excel', 'B');

-- --------------------------------------------------------

--
-- Table structure for table `quiz_results`
--

CREATE TABLE `quiz_results` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `score` int(11) DEFAULT NULL,
  `taken_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `quiz_results`
--

INSERT INTO `quiz_results` (`id`, `user_id`, `course_id`, `score`, `taken_at`) VALUES
(1, 13, 5, 0, '2026-01-18 07:08:05'),
(2, 13, 5, 0, '2026-01-18 07:08:41'),
(3, 13, 5, 20, '2026-01-18 07:20:46'),
(4, 13, 5, 20, '2026-01-18 07:25:00'),
(5, 13, 5, 80, '2026-01-18 07:27:58'),
(6, 13, 5, 20, '2026-01-18 07:28:40'),
(7, 13, 5, 20, '2026-01-18 07:29:03'),
(8, 13, 5, 20, '2026-01-18 07:29:29'),
(9, 13, 5, 40, '2026-01-18 07:29:57'),
(10, 13, 5, 80, '2026-01-18 07:30:33'),
(11, 13, 5, 0, '2026-01-18 07:31:37'),
(12, 13, 5, 60, '2026-01-18 07:33:22'),
(13, 13, 5, 80, '2026-01-18 07:33:45'),
(14, 13, 5, 100, '2026-01-18 07:34:08'),
(15, 13, 5, 100, '2026-01-18 07:43:30');

-- --------------------------------------------------------

--
-- Table structure for table `ratings`
--

CREATE TABLE `ratings` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `course_id` int(11) DEFAULT NULL,
  `stars` int(11) DEFAULT NULL CHECK (`stars` between 1 and 5),
  `review` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `full_name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `avatar` varchar(255) DEFAULT 'default.png',
  `role` enum('student','instructor','admin') DEFAULT 'student',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `full_name`, `email`, `password`, `avatar`, `role`, `created_at`) VALUES
(1, 'Mahim', 'mahim@cc.com', '55229900', 'avatar_695ada17e85d0.jpg', 'admin', '2025-12-28 20:14:01'),
(2, 'Aminul Islam Mahim', 'mahim@gmail.com', '55229900', '2_1768657592.jpg', 'student', '2025-12-29 22:13:11'),
(3, 'Aminul Islam Mahim', 'mahim55@gmail.com', '55229900', 'default.png', 'instructor', '2025-12-29 22:19:46'),
(4, 'meraz', 'meraz@gmail.com', '55229900', 'avatar_695ba3eef2b91.HEIC', 'instructor', '2025-12-29 22:26:32'),
(5, 'Meraz', 'meraz55@gmail.com', '55229900', 'default.png', 'student', '2025-12-30 08:54:09'),
(6, 'Fahim', 'fahim@gmail.com', '00000000', 'default.png', 'instructor', '2025-12-31 11:34:15'),
(7, 'Meraz', 'mim@gmail.com', '55229900', 'avatar_69576d7d3b668.jpg', 'student', '2026-01-02 07:02:21'),
(8, 'pinik', 'pinik@gmail.com', '55229900', 'avatar_695772ca1e955.jpg', 'student', '2026-01-02 07:24:58'),
(9, 'John Doe', 'johndoe@example.com', 'password123', 'default.png', 'student', '2026-01-04 20:32:20'),
(10, 'Sarah Johnson', 'sarah.johnson@codecraft.com', 'instructor2024', 'default.png', 'instructor', '2026-01-04 20:32:41'),
(12, 'Aminul Islam Mahim', 'mahim@yahoo.com', '55229900', 'default.png', 'student', '2026-01-05 20:07:51'),
(13, 'boishakhi', 'tamannawasifa@gmail.com', '$2y$10$UpRrIdmy5RQOIZvY2LXm1eYHfBnLJfIzZGJhrxBq/N6JbALgfUDgK', 'default.png', 'student', '2026-01-18 05:57:40');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `certificates`
--
ALTER TABLE `certificates`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `instructor_id` (`instructor_id`);

--
-- Indexes for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `faqs`
--
ALTER TABLE `faqs`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forum_posts`
--
ALTER TABLE `forum_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `instructors`
--
ALTER TABLE `instructors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `lessons`
--
ALTER TABLE `lessons`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `progress`
--
ALTER TABLE `progress`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `quizzes`
--
ALTER TABLE `quizzes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `quiz_results`
--
ALTER TABLE `quiz_results`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `ratings`
--
ALTER TABLE `ratings`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `course_id` (`course_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `certificates`
--
ALTER TABLE `certificates`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `courses`
--
ALTER TABLE `courses`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `enrollments`
--
ALTER TABLE `enrollments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `faqs`
--
ALTER TABLE `faqs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `forum_posts`
--
ALTER TABLE `forum_posts`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `instructors`
--
ALTER TABLE `instructors`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `lessons`
--
ALTER TABLE `lessons`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `payments`
--
ALTER TABLE `payments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `progress`
--
ALTER TABLE `progress`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `quizzes`
--
ALTER TABLE `quizzes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `quiz_results`
--
ALTER TABLE `quiz_results`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `ratings`
--
ALTER TABLE `ratings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificates`
--
ALTER TABLE `certificates`
  ADD CONSTRAINT `certificates_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `certificates_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);

--
-- Constraints for table `enrollments`
--
ALTER TABLE `enrollments`
  ADD CONSTRAINT `enrollments_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `enrollments_ibfk_2` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
