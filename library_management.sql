-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 24, 2026 at 06:09 PM
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
-- Database: `library_management`
--

-- --------------------------------------------------------

--
-- Table structure for table `books`
--

CREATE TABLE `books` (
  `book_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `author` varchar(150) DEFAULT NULL,
  `category_id` int(11) DEFAULT NULL,
  `image_url` varchar(255) DEFAULT NULL,
  `Description` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `books`
--

INSERT INTO `books` (`book_id`, `title`, `author`, `category_id`, `image_url`, `Description`) VALUES
(1, 'Clean Code', 'Robert C. Martin', 1, 'CleanCode.png', 'Hướng dẫn các quy tắc và thực hành tốt nhất để viết mã nguồn sạch, dễ bảo trì cho lập trình viên.'),
(2, 'Design Patterns', 'Gang of Four', 1, 'DesignPatterns.png', 'Tổng hợp 23 mẫu thiết kế kinh điển giúp giải quyết các vấn đề phổ biến trong thiết kế phần mềm.'),
(3, 'Đắc Nhân Tâm', 'Dale Carnegie', 4, 'DacNhanTam.png', 'Cuốn sách nghệ thuật ứng xử nổi tiếng nhất thế giới, giúp bạn thu phục lòng người.'),
(4, 'Nhà Giả Kim', 'Paulo Coelho', 3, 'NhaGiaKim.png', 'Hành trình theo đuổi vận mệnh của chàng chăn cừu Santiago, một tác phẩm đầy tính nhân văn.'),
(5, 'Cha Giàu Cha Nghèo', 'Robert Kiyosaki', 2, 'Chagiauchangheo.png', 'Chia sẻ về tư duy tài chính, sự khác biệt giữa tài sản và tiêu sản để đạt được tự do tài chính.'),
(6, 'Lập trình PHP MVC', 'John Smith', 1, 'LapTrinhPHP MVC.png', 'Hướng dẫn xây dựng ứng dụng web bằng ngôn ngữ PHP dựa trên mô hình Model-View-Controller.'),
(7, 'Dế Mèn Phiêu Lưu Ký', 'Tô Hoài', 3, 'Dế Mèn Phiêu Lưu Ký.png', 'Tác phẩm văn học thiếu nhi kinh điển kể về những chuyến phiêu lưu và bài học trưởng thành.'),
(8, 'Tư duy nhanh chậm', 'Daniel Kahneman', 4, 'Tư duy nhanh chậm.png', 'Khám phá hai hệ thống tư duy chi phối cách con người suy nghĩ và đưa ra quyết định.'),
(9, 'Kinh tế học vĩ mô', 'Paul Samuelson', 2, 'Kinh tế học vĩ mô.png', 'Giáo trình kinh điển cung cấp kiến thức nền tảng về vận hành của nền kinh tế toàn cầu.'),
(10, 'Giải thuật cơ bản', 'Lê Văn A', 1, 'Giải thuật cơ bản.png', 'Trình bày các thuật toán nền tảng trong khoa học máy tính như sắp xếp và cấu trúc dữ liệu.'),
(11, 'Vũ trụ sơ khai', 'Stephen Hawking', 5, 'Vũ trụ sơ khai.png', 'Giải thích các lý thuyết vật lý hiện đại về nguồn gốc của vũ trụ và thời gian.'),
(12, 'Lịch sử vạn vật', 'Bill Bryson', 5, 'Lịch sử vạn vật.png', 'Một hành trình khám phá khoa học từ vụ nổ Big Bang đến sự hình thành văn minh nhân loại.'),
(13, 'Quản trị kinh doanh', 'Trần Văn B', 2, 'Quản trị kinh doanh.png', 'Kiến thức tổng quan về quản lý doanh nghiệp, nhân sự và chiến lược kinh doanh.'),
(14, 'Kỹ năng giao tiếp', 'Nguyễn Thị C', 4, 'Kỹ năng giao tiếp.png', 'Các phương pháp giúp cải thiện khả năng truyền đạt thông tin và lắng nghe hiệu quả.'),
(15, 'Suối nguồn', 'Ayn Rand', 3, 'Suối nguồn.png', 'Cuốn tiểu thuyết về sự tôn thờ cá nhân và cuộc đấu tranh bảo vệ cái tôi sáng tạo.'),
(16, 'Mắt biếc', 'Nguyễn Nhật Ánh', 3, 'Mắt biếc.png', 'Một chuyện tình buồn, trong sáng của Ngạn dành cho Hà Lan - cô gái có đôi mắt đẹp hút hồn.'),
(17, 'Tôi thấy hoa vàng', 'Nguyễn Nhật Ánh', 3, 'Tôi thấy hoa vàng.png', 'Những lát cắt tuổi thơ hồn nhiên ở làng quê nghèo với tình anh em và sự đố kỵ trẻ con.'),
(18, 'Lập trình Python', 'Guido van Rossum', 1, 'Lập trình Python.png', 'Tài liệu hướng dẫn từ cơ bản đến nâng cao về Python – ngôn ngữ lập trình phổ biến nhất.'),
(19, 'Trí tuệ nhân tạo', 'Andrew Ng', 1, 'Trí tuệ nhân tạo.png', 'Giới thiệu các khái niệm cốt lõi của AI, Machine Learning và tầm ảnh hưởng của chúng.'),
(20, 'Dữ liệu lớn', 'Hoàng Văn D', 1, 'Dữ liệu lớn.png', 'Phân tích cách dữ liệu lớn đang thay đổi thế giới từ kinh doanh đến đời sống.');

-- --------------------------------------------------------

--
-- Table structure for table `book_copies`
--

CREATE TABLE `book_copies` (
  `book_copy_id` int(11) NOT NULL,
  `book_id` int(11) DEFAULT NULL,
  `status` enum('available','borrowed') DEFAULT 'available',
  `barcode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `book_copies`
--

INSERT INTO `book_copies` (`book_copy_id`, `book_id`, `status`, `barcode`) VALUES
(1, 1, 'available', 'BC-1-1'),
(2, 1, 'available', 'BC-1-2'),
(3, 1, 'available', 'BC-1-3'),
(4, 2, 'available', 'BC-2-1'),
(5, 2, 'available', 'BC-2-2'),
(6, 2, 'available', 'BC-2-3'),
(7, 6, 'available', 'BC-6-1'),
(8, 6, 'available', 'BC-6-2'),
(9, 6, 'available', 'BC-6-3'),
(10, 10, 'available', 'BC-10-1'),
(11, 10, 'available', 'BC-10-2'),
(12, 10, 'available', 'BC-10-3'),
(13, 18, 'available', 'BC-18-1'),
(14, 18, 'available', 'BC-18-2'),
(15, 18, 'available', 'BC-18-3'),
(16, 19, 'available', 'BC-19-1'),
(17, 19, 'available', 'BC-19-2'),
(18, 19, 'available', 'BC-19-3'),
(19, 20, 'available', 'BC-20-1'),
(20, 20, 'available', 'BC-20-2'),
(21, 20, 'available', 'BC-20-3'),
(22, 5, 'available', 'BC-5-1'),
(23, 5, 'available', 'BC-5-2'),
(24, 5, 'available', 'BC-5-3'),
(25, 9, 'available', 'BC-9-1'),
(26, 9, 'available', 'BC-9-2'),
(27, 9, 'available', 'BC-9-3'),
(28, 13, 'available', 'BC-13-1'),
(29, 13, 'available', 'BC-13-2'),
(30, 13, 'available', 'BC-13-3'),
(31, 4, 'available', 'BC-4-1'),
(32, 4, 'available', 'BC-4-2'),
(33, 4, 'available', 'BC-4-3'),
(34, 7, 'available', 'BC-7-1'),
(35, 7, 'available', 'BC-7-2'),
(36, 7, 'available', 'BC-7-3'),
(37, 15, 'available', 'BC-15-1'),
(38, 15, 'available', 'BC-15-2'),
(39, 15, 'available', 'BC-15-3'),
(40, 16, 'available', 'BC-16-1'),
(41, 16, 'available', 'BC-16-2'),
(42, 16, 'available', 'BC-16-3'),
(43, 17, 'available', 'BC-17-1'),
(44, 17, 'available', 'BC-17-2'),
(45, 17, 'available', 'BC-17-3'),
(46, 3, 'available', 'BC-3-1'),
(47, 3, 'available', 'BC-3-2'),
(48, 3, 'available', 'BC-3-3'),
(49, 8, 'available', 'BC-8-1'),
(50, 8, 'available', 'BC-8-2'),
(51, 8, 'available', 'BC-8-3'),
(52, 14, 'available', 'BC-14-1'),
(53, 14, 'available', 'BC-14-2'),
(54, 14, 'available', 'BC-14-3'),
(55, 11, 'available', 'BC-11-1'),
(56, 11, 'available', 'BC-11-2'),
(57, 11, 'available', 'BC-11-3'),
(58, 12, 'available', 'BC-12-1'),
(59, 12, 'available', 'BC-12-2'),
(60, 12, 'available', 'BC-12-3');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_records`
--

CREATE TABLE `borrow_records` (
  `borrow_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `borrow_date` date NOT NULL,
  `due_date` date NOT NULL,
  `return_date` date DEFAULT NULL,
  `status` enum('borrowed','returned','overdue') DEFAULT 'borrowed'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_records`
--

INSERT INTO `borrow_records` (`borrow_id`, `user_id`, `borrow_date`, `due_date`, `return_date`, `status`) VALUES
(1, 2, '2026-01-01', '2026-01-15', NULL, 'overdue'),
(2, 3, '2026-01-10', '2026-01-24', NULL, 'borrowed');

-- --------------------------------------------------------

--
-- Table structure for table `borrow_records_book_copies`
--

CREATE TABLE `borrow_records_book_copies` (
  `borrow_detail_id` int(11) NOT NULL,
  `borrow_id` int(11) DEFAULT NULL,
  `book_copy_id` int(11) DEFAULT NULL,
  `is_returned` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `borrow_records_book_copies`
--

INSERT INTO `borrow_records_book_copies` (`borrow_detail_id`, `borrow_id`, `book_copy_id`, `is_returned`) VALUES
(1, 1, 2, 0),
(2, 2, 5, 0);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `category_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`category_id`, `category_name`) VALUES
(1, 'Công nghệ thông tin'),
(2, 'Kinh tế'),
(3, 'Văn học'),
(4, 'Kỹ năng'),
(5, 'Khoa học');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `notification_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `type` enum('register','reminder','resetpassword') DEFAULT NULL,
  `sent_at` datetime DEFAULT current_timestamp(),
  `status` enum('sent','failed') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`notification_id`, `user_id`, `type`, `sent_at`, `status`) VALUES
(1, 4, 'register', '2026-01-19 01:44:38', 'sent'),
(2, 5, 'register', '2026-01-19 01:56:11', 'sent'),
(3, 6, 'register', '2026-01-19 02:02:11', 'sent'),
(4, 7, 'register', '2026-01-19 02:04:07', 'sent'),
(5, 8, 'register', '2026-01-19 02:04:43', 'sent'),
(6, 9, 'register', '2026-01-19 02:09:08', 'sent'),
(7, 10, 'register', '2026-01-19 02:29:07', 'sent'),
(8, 11, 'register', '2026-01-19 02:36:00', 'sent'),
(9, 12, 'register', '2026-01-19 03:35:35', 'sent'),
(10, 13, 'register', '2026-01-24 19:02:32', 'sent');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `full_name` varchar(150) DEFAULT NULL,
  `email` varchar(150) NOT NULL,
  `role` enum('admin','member') DEFAULT 'member',
  `status` enum('active','locked') DEFAULT 'active'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `password`, `full_name`, `email`, `role`, `status`) VALUES
(1, 'admin', '$2y$10$8WkXF.p5LpZ7A8M/BvY.EuS/K0yG1Z4v7rG6oG9x1zM/BvY.EuS/', 'Admin System', 'admin@library.com', 'admin', 'active'),
(2, 'member01', '$2y$10$8WkXF.p5LpZ7A8M/BvY.EuS/K0yG1Z4v7rG6oG9x1zM/BvY.EuS/', 'Nguyễn Văn Thành', 'thanh@gmail.com', 'member', 'active'),
(3, 'member02', '$2y$10$8WkXF.p5LpZ7A8M/BvY.EuS/K0yG1Z4v7rG6oG9x1zM/BvY.EuS/', 'Lê Thị Hoa', 'hoa@gmail.com', 'member', 'active'),
(4, 'trang09', '$2y$10$XETgGW8Le4zwrImrAnoPmuRZ5yiIk3UJrfIdXAoOMOavmtBxLJ0bG', 'trangnguyen', 'trang123@gmail.com', 'member', 'active'),
(5, 'khue09', '$2y$10$P69deUjgy8I7zoC6iBz0/exsXOBLH.KP.aKUGx4iVhUaWDVuuuzq6', 'khuenguyen', 'khue123@gmail.com', 'member', 'active'),
(6, 'lac123', '$2y$10$KuFodtutH3dvRDI7YskPvOgyTEdq6/JVq0uH8AHZGiIjCGTRi6MPW', 'tienlac', 'lac123@gmail.com', 'member', 'active'),
(7, 'anh123', '$2y$10$8qapcI/LFVXIlo21jE/Px.XG5sBzt1AqzttzEFNAlWG66tX54urey', 'ngocanh', 'anh123@gmail.com', 'member', 'active'),
(8, 'thuytrang', '$2y$10$1hXkDx0eSNuyQrF0TINoaOSrxQEYNL9j.wdgYQ12ygJyt0ZxFnuoK', 'ttrang123', 'ttrang123@gmail.com', 'member', 'active'),
(9, 'thuy', '$2y$10$UBf5Nfpj4qBVWkpRqsPZw.BPWX56pQhWn553Ss06XTQN2qVTgZwQG', 'thuy123', 'thuy123@gmail.com', 'member', 'active'),
(10, 'tu123123', '$2y$10$DFwRPImvBbtzqsZ67ja1GedcdqS1USUV/.pA36ID7gwWOMn/WJRie', 'tunguyen', 'tu123@gmail.com', 'member', 'active'),
(11, 'viet123', '$2y$10$aURLLxk5WrFaiW1DmQGAHep1WhHDDjErNAUaA43UOAbnJDVFsM5hq', 'vietnguyen', 'viet123@gmail.com', 'member', 'active'),
(12, 'ThuTrang', '$2y$10$8MF2E/FqdBc0zXOQEkgqYO4PunMQ.08w70dV5tFqrfwMRjm9iSI16', 'Nguyễn Thu Trang', 'trang1234@gmail.com', 'member', 'active'),
(13, 'mintu', '$2y$10$wggEpV6qVtu93up76EO78u0EZSkexyNj6dNEQu1I4HTvd4i0Y0fqG', 'Tu Ngo Minh', 'minhtu080906@gmail.com', 'member', 'active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `books`
--
ALTER TABLE `books`
  ADD PRIMARY KEY (`book_id`),
  ADD KEY `category_id` (`category_id`);

--
-- Indexes for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD PRIMARY KEY (`book_copy_id`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `book_id` (`book_id`);

--
-- Indexes for table `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD PRIMARY KEY (`borrow_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `borrow_records_book_copies`
--
ALTER TABLE `borrow_records_book_copies`
  ADD PRIMARY KEY (`borrow_detail_id`),
  ADD KEY `borrow_id` (`borrow_id`),
  ADD KEY `book_copy_id` (`book_copy_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`category_id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `books`
--
ALTER TABLE `books`
  MODIFY `book_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `book_copies`
--
ALTER TABLE `book_copies`
  MODIFY `book_copy_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `borrow_records`
--
ALTER TABLE `borrow_records`
  MODIFY `borrow_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `borrow_records_book_copies`
--
ALTER TABLE `borrow_records_book_copies`
  MODIFY `borrow_detail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `notification_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `books`
--
ALTER TABLE `books`
  ADD CONSTRAINT `books_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `categories` (`category_id`) ON DELETE SET NULL;

--
-- Constraints for table `book_copies`
--
ALTER TABLE `book_copies`
  ADD CONSTRAINT `book_copies_ibfk_1` FOREIGN KEY (`book_id`) REFERENCES `books` (`book_id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_records`
--
ALTER TABLE `borrow_records`
  ADD CONSTRAINT `borrow_records_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `borrow_records_book_copies`
--
ALTER TABLE `borrow_records_book_copies`
  ADD CONSTRAINT `borrow_records_book_copies_ibfk_1` FOREIGN KEY (`borrow_id`) REFERENCES `borrow_records` (`borrow_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `borrow_records_book_copies_ibfk_2` FOREIGN KEY (`book_copy_id`) REFERENCES `book_copies` (`book_copy_id`) ON DELETE CASCADE;

--
-- Constraints for table `notifications`
--
ALTER TABLE `notifications`
  ADD CONSTRAINT `notifications_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
