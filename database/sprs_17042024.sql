-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 17, 2024 at 06:12 AM
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
-- Database: `sprs`
--

-- --------------------------------------------------------

--
-- Stand-in structure for view `average_grades`
-- (See below for the actual view)
--
CREATE TABLE `average_grades` (
`paper_id` int(11)
,`title` varchar(255)
,`average_grade` decimal(14,4)
);

-- --------------------------------------------------------

--
-- Table structure for table `criteria`
--

CREATE TABLE `criteria` (
  `criteria1` bit(1) NOT NULL DEFAULT b'0',
  `criteria2` bit(1) NOT NULL,
  `criteria3` bit(1) DEFAULT NULL,
  `criteria4` bit(1) DEFAULT NULL,
  `criteria5` bit(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `c_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `grading_scheme` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `criteria_list`
--

INSERT INTO `criteria_list` (`c_id`, `name`, `description`, `grading_scheme`) VALUES
(1, 'Criteria one', 'bla bla bla', '1. do this\r\n2. do this\r\n3. dont do this'),
(2, 'Criteria Two', 'Second Criteria...', '1. do this\r\n2. do this\r\n3. dont do this'),
(6, 'Edited one. also, prevent SQL injection!', 'No SQL Injection allowed to be submitted...!', '1. do this\r\n2. do this\r\n3. dont do this'),
(14, 'sfsfds', 'rgrdhhthr', '1. do this\r\n2. do this\r\n3. dont do this'),
(16, 'sdfs', 'sfasf', '1. do this\r\n2. do this\r\n3. dont do this');

-- --------------------------------------------------------

--
-- Table structure for table `files`
--

CREATE TABLE `files` (
  `fileID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `Filename` varchar(255) DEFAULT NULL,
  `uploadDate` datetime DEFAULT NULL,
  `fileURL` text DEFAULT NULL,
  `key_words` varchar(255) DEFAULT NULL,
  `status` enum('public','private') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `files`
--

INSERT INTO `files` (`fileID`, `userID`, `Filename`, `uploadDate`, `fileURL`, `key_words`, `status`) VALUES
(1, 14, 'thesis', '2024-04-12 23:34:43', NULL, NULL, 'public');

-- --------------------------------------------------------

--
-- Table structure for table `fileshare`
--

CREATE TABLE `fileshare` (
  `shareID` int(11) NOT NULL,
  `fileID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `firstComment` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `fileshare`
--

INSERT INTO `fileshare` (`shareID`, `fileID`, `userID`, `firstComment`) VALUES
(1, 1, 7, 'please check it');

-- --------------------------------------------------------

--
-- Table structure for table `grades`
--

CREATE TABLE `grades` (
  `id` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `reviewer_id` int(11) NOT NULL,
  `c_id` int(11) NOT NULL,
  `grade` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `grades`
--

INSERT INTO `grades` (`id`, `paper_id`, `reviewer_id`, `c_id`, `grade`) VALUES
(50, 1, 16, 1, 4),
(51, 1, 16, 2, 4),
(52, 1, 16, 6, 3),
(53, 1, 16, 14, 2),
(54, 1, 16, 16, 1),
(55, 1, 16, 16, 3),
(56, 1, 16, 14, 2);

-- --------------------------------------------------------

--
-- Table structure for table `grade_ranges`
--

CREATE TABLE `grade_ranges` (
  `range_id` int(11) NOT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `grade_label` char(3) DEFAULT NULL,
  `range_min` int(11) DEFAULT NULL,
  `range_max` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `grade_ranges`
--

INSERT INTO `grade_ranges` (`range_id`, `criteria_id`, `grade_label`, `range_min`, `range_max`) VALUES
(1, 1, 'A', 80, 100),
(2, 1, 'B', 60, 79),
(3, 1, 'C', 40, 59),
(4, 1, 'D', 20, 39),
(5, 1, 'F', 0, 19),
(6, 2, 'A', 85, 100),
(7, 2, 'B', 70, 84),
(8, 2, 'C', 55, 69),
(9, 2, 'D', 40, 54),
(10, 2, 'F', 0, 39);

-- --------------------------------------------------------

--
-- Table structure for table `papers`
--

CREATE TABLE `papers` (
  `paper_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `abstract` text DEFAULT NULL,
  `paperurl` text DEFAULT NULL,
  `key_words` varchar(200) DEFAULT NULL,
  `status` enum('private','public') DEFAULT NULL,
  `submission_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `review_deadline` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`paper_id`, `title`, `abstract`, `paperurl`, `key_words`, `status`, `submission_date`, `author_id`, `review_deadline`) VALUES
(1, 'This One', 'Abstact is this one...', 'thereisnourlnow', 'me, you, they', 'private', '2024-04-15 21:08:10', 7, '2024-04-15 21:08:10');

-- --------------------------------------------------------

--
-- Table structure for table `razreviews`
--

CREATE TABLE `razreviews` (
  `review_id` int(11) NOT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `criteria_id` int(11) DEFAULT NULL,
  `mark` char(3) DEFAULT NULL,
  `comment` text DEFAULT NULL,
  `review_status` enum('pending','accepted','rejected') DEFAULT NULL,
  `review_date` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `shareID` int(11) DEFAULT NULL,
  `reviewerID` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `creationDate` datetime DEFAULT NULL,
  `ownderID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`reviewID`, `shareID`, `reviewerID`, `comment`, `status`, `creationDate`, `ownderID`) VALUES
(1, 1, 7, 'its ok', 'pending', '2024-04-13 23:15:25', 14);

-- --------------------------------------------------------

--
-- Table structure for table `review_comment`
--

CREATE TABLE `review_comment` (
  `commentID` int(11) NOT NULL,
  `reviewID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `parentCommentID` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `commentDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `review_comment`
--

INSERT INTO `review_comment` (`commentID`, `reviewID`, `userID`, `parentCommentID`, `comment`, `commentDate`) VALUES
(1, 1, 12, NULL, 'you are rigth', '2024-04-13 23:12:15');

-- --------------------------------------------------------

--
-- Table structure for table `review_comment_like`
--

CREATE TABLE `review_comment_like` (
  `commentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `likeDate` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `review_comment_like`
--

INSERT INTO `review_comment_like` (`commentID`, `userID`, `likeDate`) VALUES
(1, 7, '2024-04-13 23:17:11');

-- --------------------------------------------------------

--
-- Table structure for table `review_detail`
--

CREATE TABLE `review_detail` (
  `reviewID` int(11) NOT NULL,
  `reviewerID` int(11) NOT NULL,
  `text` text NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `review_like`
--

CREATE TABLE `review_like` (
  `reviewid` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `likeDate` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `review_notification`
--

CREATE TABLE `review_notification` (
  `reviewID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `text` varchar(255) NOT NULL,
  `status` enum('pending','read') NOT NULL,
  `datetime` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `review_score`
--

CREATE TABLE `review_score` (
  `reviewID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `score` tinyint(4) NOT NULL,
  `score_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `share_notofication`
--

CREATE TABLE `share_notofication` (
  `text` varchar(255) NOT NULL,
  `userID` int(11) NOT NULL COMMENT 'who will recieve this notification?',
  `status` enum('New','Read') NOT NULL,
  `shareID` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `email` varchar(200) NOT NULL,
  `contact` varchar(20) NOT NULL,
  `address` text NOT NULL,
  `cover_img` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `system_settings`
--

INSERT INTO `system_settings` (`id`, `name`, `email`, `contact`, `address`, `cover_img`) VALUES
(1, 'Student Peer Review System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `firstname` varchar(200) NOT NULL,
  `lastname` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `password` text NOT NULL,
  `avatar` text DEFAULT NULL,
  `date_created` datetime NOT NULL DEFAULT current_timestamp(),
  `status` enum('Active','Deactive') NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT 3,
  `knowledge_areas` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `firstname`, `lastname`, `email`, `password`, `avatar`, `date_created`, `status`, `user_type`, `knowledge_areas`) VALUES
(7, 'Amir', 'Amiri', 'ahmadi@gmail.com', 'ac627ab1ccbdb62ec96e702f07f6425b', NULL, '2024-04-12 19:45:01', 'Active', 3, 'IS and IT, Science and technology'),
(12, 'Reviewer2', 'of the faculty', 'reviewer2@gmail.com', 'f899139df5e1059396431415e770c6dd', NULL, '2024-04-13 17:16:21', 'Active', 2, ''),
(14, 'Admin', '', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', NULL, '2024-04-13 18:17:10', 'Active', 1, 'Administration'),
(16, 'Raz', 'sss', 'raz@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '2024-04-15 13:16:45', 'Active', 2, ''),
(17, 'Raz', 'y', 'raz1@gmail.com', 'raz123', NULL, '2024-04-15 20:03:06', 'Active', 1, 'Any');

-- --------------------------------------------------------

--
-- Structure for view `average_grades`
--
DROP TABLE IF EXISTS `average_grades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `average_grades`  AS SELECT `rg`.`paper_id` AS `paper_id`, `p`.`title` AS `title`, avg(`rg`.`grade`) AS `average_grade` FROM (`grades` `rg` join `papers` `p` on(`rg`.`paper_id` = `p`.`paper_id`)) GROUP BY `rg`.`paper_id`, `p`.`title` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`c_id`);

--
-- Indexes for table `files`
--
ALTER TABLE `files`
  ADD PRIMARY KEY (`fileID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `fileshare`
--
ALTER TABLE `fileshare`
  ADD PRIMARY KEY (`shareID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE,
  ADD KEY `fileID` (`fileID`) USING BTREE;

--
-- Indexes for table `grades`
--
ALTER TABLE `grades`
  ADD PRIMARY KEY (`id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `c_id` (`c_id`);

--
-- Indexes for table `grade_ranges`
--
ALTER TABLE `grade_ranges`
  ADD PRIMARY KEY (`range_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paper_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `razreviews`
--
ALTER TABLE `razreviews`
  ADD PRIMARY KEY (`review_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `criteria_id` (`criteria_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`) USING BTREE,
  ADD KEY `shareID` (`shareID`) USING BTREE,
  ADD KEY `reviewerID` (`reviewerID`) USING BTREE,
  ADD KEY `ownderID` (`ownderID`) USING BTREE;

--
-- Indexes for table `review_comment`
--
ALTER TABLE `review_comment`
  ADD PRIMARY KEY (`commentID`) USING BTREE,
  ADD KEY `reviewID` (`reviewID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE,
  ADD KEY `parentCommentID` (`parentCommentID`) USING BTREE;

--
-- Indexes for table `review_comment_like`
--
ALTER TABLE `review_comment_like`
  ADD UNIQUE KEY `uniq_user_comment_like` (`commentID`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `review_detail`
--
ALTER TABLE `review_detail`
  ADD UNIQUE KEY `reviewID` (`reviewID`,`reviewerID`) USING BTREE,
  ADD KEY `reviewerID` (`reviewerID`) USING BTREE;

--
-- Indexes for table `review_like`
--
ALTER TABLE `review_like`
  ADD UNIQUE KEY `uniq_user_review_like` (`reviewid`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `review_notification`
--
ALTER TABLE `review_notification`
  ADD UNIQUE KEY `reviewID` (`reviewID`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `review_score`
--
ALTER TABLE `review_score`
  ADD UNIQUE KEY `reviewID` (`reviewID`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

--
-- Indexes for table `share_notofication`
--
ALTER TABLE `share_notofication`
  ADD UNIQUE KEY `userID` (`userID`,`shareID`) USING BTREE,
  ADD KEY `share_notofication_ibfk_2` (`shareID`) USING BTREE;

--
-- Indexes for table `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`) USING BTREE;

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `files`
--
ALTER TABLE `files`
  MODIFY `fileID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `fileshare`
--
ALTER TABLE `fileshare`
  MODIFY `shareID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=57;

--
-- AUTO_INCREMENT for table `grade_ranges`
--
ALTER TABLE `grade_ranges`
  MODIFY `range_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `razreviews`
--
ALTER TABLE `razreviews`
  MODIFY `review_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `files`
--
ALTER TABLE `files`
  ADD CONSTRAINT `files_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `fileshare`
--
ALTER TABLE `fileshare`
  ADD CONSTRAINT `fileshare_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fileshare_ibfk_2` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `grades`
--
ALTER TABLE `grades`
  ADD CONSTRAINT `grades_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `grades_ibfk_3` FOREIGN KEY (`c_id`) REFERENCES `criteria_list` (`c_id`) ON DELETE CASCADE;

--
-- Constraints for table `grade_ranges`
--
ALTER TABLE `grade_ranges`
  ADD CONSTRAINT `grade_ranges_ibfk_1` FOREIGN KEY (`criteria_id`) REFERENCES `criteria_list` (`c_id`);

--
-- Constraints for table `papers`
--
ALTER TABLE `papers`
  ADD CONSTRAINT `papers_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `razreviews`
--
ALTER TABLE `razreviews`
  ADD CONSTRAINT `razreviews_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`),
  ADD CONSTRAINT `razreviews_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `razreviews_ibfk_3` FOREIGN KEY (`criteria_id`) REFERENCES `criteria_list` (`c_id`);

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`shareID`) REFERENCES `fileshare` (`shareID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`ownderID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_comment`
--
ALTER TABLE `review_comment`
  ADD CONSTRAINT `review_comment_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_ibfk_3` FOREIGN KEY (`parentCommentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_comment_like`
--
ALTER TABLE `review_comment_like`
  ADD CONSTRAINT `review_comment_like_ibfk_1` FOREIGN KEY (`commentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_detail`
--
ALTER TABLE `review_detail`
  ADD CONSTRAINT `review_detail_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_detail_ibfk_2` FOREIGN KEY (`reviewerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_like`
--
ALTER TABLE `review_like`
  ADD CONSTRAINT `review_like_ibfk_1` FOREIGN KEY (`reviewid`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_notification`
--
ALTER TABLE `review_notification`
  ADD CONSTRAINT `review_notification_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_notification_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_score`
--
ALTER TABLE `review_score`
  ADD CONSTRAINT `review_score_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_score_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `share_notofication`
--
ALTER TABLE `share_notofication`
  ADD CONSTRAINT `share_notofication_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `share_notofication_ibfk_2` FOREIGN KEY (`shareID`) REFERENCES `fileshare` (`shareID`) ON DELETE SET NULL ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
