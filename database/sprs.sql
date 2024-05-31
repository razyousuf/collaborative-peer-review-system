-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 06, 2024 at 02:45 PM
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
-- Table structure for table `annotations`
--

CREATE TABLE `annotations` (
  `annotation_id` int(11) NOT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL,
  `share_id` int(11) DEFAULT NULL,
  `content` text DEFAULT NULL,
  `page` int(11) DEFAULT NULL,
  `type` tinyint(4) NOT NULL COMMENT '0 for comment, 1 for reply'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `annotations`
--

INSERT INTO `annotations` (`annotation_id`, `paper_id`, `reviewer_id`, `share_id`, `content`, `page`, `type`) VALUES
(80, 26, 19, 113, 'Hi', 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `annotation_comments`
--

CREATE TABLE `annotation_comments` (
  `comment_id` int(11) NOT NULL,
  `annotation_id` int(11) NOT NULL,
  `commenter_id` int(11) NOT NULL,
  `comment` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

-- --------------------------------------------------------

--
-- Table structure for table `annotation_likes`
--

CREATE TABLE `annotation_likes` (
  `like_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `liker_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `annotation_likes`
--

INSERT INTO `annotation_likes` (`like_id`, `comment_id`, `liker_id`) VALUES
(31, 80, 19);

-- --------------------------------------------------------

--
-- Table structure for table `annotation_replies`
--

CREATE TABLE `annotation_replies` (
  `reply_id` int(11) NOT NULL,
  `comment_id` int(11) NOT NULL,
  `replier_id` int(11) NOT NULL,
  `reply` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

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
-- Table structure for table `criteria_list`
--

CREATE TABLE `criteria_list` (
  `c_id` int(11) NOT NULL,
  `name` varchar(40) NOT NULL,
  `description` text NOT NULL,
  `grading_scheme` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

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
(185, 23, 17, 1, 5),
(186, 23, 17, 2, 4),
(187, 23, 17, 6, 5),
(188, 23, 17, 14, 4),
(189, 23, 17, 16, 3),
(190, 23, 19, 1, 4),
(191, 23, 19, 2, 4),
(192, 23, 19, 6, 3),
(193, 23, 19, 14, 5),
(194, 23, 19, 16, 2),
(195, 24, 19, 1, 5),
(196, 24, 19, 2, 4),
(197, 24, 19, 6, 4),
(198, 24, 19, 14, 3),
(199, 24, 19, 16, 4),
(200, 25, 17, 1, 5),
(201, 25, 17, 2, 4),
(202, 25, 17, 6, 2),
(203, 25, 17, 14, 4),
(204, 25, 17, 16, 3);

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `group_id` int(11) NOT NULL,
  `group_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`group_id`, `group_name`) VALUES
(3, 'Third Group-Test'),
(4, 'Second Group');

-- --------------------------------------------------------

--
-- Table structure for table `group_members`
--

CREATE TABLE `group_members` (
  `group_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `group_members`
--

INSERT INTO `group_members` (`group_id`, `user_id`) VALUES
(3, 12),
(3, 17),
(3, 19),
(3, 20),
(3, 22),
(4, 12),
(4, 17),
(4, 19);

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
  `share_type` enum('private','public') DEFAULT NULL,
  `submission_date` datetime NOT NULL,
  `author_id` int(11) NOT NULL,
  `review_deadline` datetime NOT NULL,
  `review_status` enum('In queue','Reviewed','Rejected') DEFAULT 'In queue',
  `size` bigint(20) DEFAULT NULL,
  `reviewer_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `papers`
--

INSERT INTO `papers` (`paper_id`, `title`, `abstract`, `paperurl`, `key_words`, `share_type`, `submission_date`, `author_id`, `review_deadline`, `review_status`, `size`, `reviewer_id`) VALUES
(21, 'One', 'One Abs', 'uploads/66379c1d30b0f4.93051405.pdf', NULL, NULL, '2024-05-05 15:47:57', 19, '0000-00-00 00:00:00', 'In queue', 0, NULL),
(22, 'Two', 'Second Abs', 'uploads/66379c3b77e4d8.13770825.pdf', NULL, NULL, '2024-05-05 15:48:27', 19, '0000-00-00 00:00:00', 'In queue', 1, NULL),
(23, 'Dddddddddd', 'dddddddddd', 'uploads/6637a0f36b0ce2.33709570.pdf', NULL, NULL, '2024-05-05 16:08:35', 17, '0000-00-00 00:00:00', 'In queue', 0, NULL),
(24, 'Second Paper', 'Absssssssssssssssssssssssss j ojeo     fsddddddddddddd', 'uploads/6637c0c2b176f0.96131271.pdf', NULL, NULL, '2024-05-05 18:24:18', 17, '0000-00-00 00:00:00', 'In queue', 1, NULL),
(25, 'Test10000000000000', 'ttttttttttttt', 'uploads/6637c3f30b1ad6.90695977.pdf', NULL, NULL, '2024-05-05 18:37:55', 20, '0000-00-00 00:00:00', 'In queue', 0, NULL),
(26, 'Afte Author test', 'basjfo jasjf bbbbbbbbbbbbbbbbbbb', 'uploads/66388dd217e723.44774648.pdf', NULL, NULL, '2024-05-06 08:59:14', 17, '0000-00-00 00:00:00', 'In queue', 1, NULL),
(27, 'Three', 'Nooooooooooooooooooo', 'uploads/66388ffc47b981.01992825.pdf', NULL, NULL, '2024-05-06 09:08:28', 19, '0000-00-00 00:00:00', 'In queue', 0, NULL),
(28, 'Individual Shares', 'hhhhhhhhhhhh', 'uploads/6638989cbfff92.04357486.pdf', NULL, NULL, '2024-05-06 09:45:16', 19, '0000-00-00 00:00:00', 'In queue', 0, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `paper_criteria`
--

CREATE TABLE `paper_criteria` (
  `paper_id` int(11) NOT NULL,
  `criteria_id` int(11) NOT NULL,
  `status` enum('met','not met') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Stand-in structure for view `paper_review_info`
-- (See below for the actual view)
--
CREATE TABLE `paper_review_info` (
`paper_id` int(11)
,`title` varchar(255)
,`paper_Status` enum('In queue','Reviewed','Rejected')
,`reviewer_name` varchar(401)
,`author_name` varchar(401)
,`reviewID` int(11)
,`status` enum('pending','accepted','rejected')
,`score` tinyint(4)
,`total_criteria` int(11)
,`matched_criteria_count` int(11)
);

-- --------------------------------------------------------

--
-- Table structure for table `paper_shares`
--

CREATE TABLE `paper_shares` (
  `share_id` int(11) NOT NULL,
  `paper_id` int(11) DEFAULT NULL,
  `share_type` enum('all','group','individual') DEFAULT NULL,
  `group_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_anonymous` tinyint(1) DEFAULT 0,
  `share_date` datetime DEFAULT current_timestamp(),
  `deadline` datetime DEFAULT NULL,
  `review_status` enum('In queue','Reviewed','Rejected') DEFAULT 'In queue',
  `is_collaborative` tinyint(1) NOT NULL DEFAULT 0,
  `author_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci;

--
-- Dumping data for table `paper_shares`
--

INSERT INTO `paper_shares` (`share_id`, `paper_id`, `share_type`, `group_id`, `user_id`, `is_anonymous`, `share_date`, `deadline`, `review_status`, `is_collaborative`, `author_id`) VALUES
(112, 23, 'group', 4, NULL, 1, '2024-05-06 09:05:42', '2024-05-16 09:05:00', 'Rejected', 0, 17),
(113, 26, 'group', 3, NULL, 1, '2024-05-06 09:06:01', '2024-05-16 09:05:00', 'In queue', 0, 17),
(114, 22, 'group', 3, NULL, 0, '2024-05-06 09:06:33', '2024-05-15 09:06:00', 'Reviewed', 0, 19),
(115, 28, 'individual', NULL, 12, 1, '2024-05-06 09:45:45', '2024-05-24 09:45:00', 'In queue', 0, 19),
(116, 28, 'individual', NULL, 17, 1, '2024-05-06 09:45:45', '2024-05-24 09:45:00', 'Reviewed', 0, 19),
(117, 28, 'individual', NULL, 20, 1, '2024-05-06 09:45:45', '2024-05-24 09:45:00', 'Reviewed', 0, 19),
(118, 28, 'individual', NULL, 22, 1, '2024-05-06 09:45:45', '2024-05-24 09:45:00', 'In queue', 0, 19);

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `reviewID` int(11) NOT NULL,
  `reviewerID` int(11) DEFAULT NULL,
  `comment` varchar(255) DEFAULT NULL,
  `status` enum('pending','accepted','rejected') DEFAULT 'pending',
  `creationDate` datetime DEFAULT NULL,
  `shareID` int(11) NOT NULL,
  `review_type` enum('anonymus','real') NOT NULL DEFAULT 'real'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `review_comment`
--

CREATE TABLE `review_comment` (
  `commentID` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `parentCommentID` int(11) DEFAULT NULL,
  `comment` text NOT NULL,
  `commentDate` datetime NOT NULL,
  `reviewID` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `review_comment_like`
--

CREATE TABLE `review_comment_like` (
  `commentID` int(11) NOT NULL,
  `userID` int(11) NOT NULL,
  `likeDate` datetime NOT NULL
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
  `score_date` datetime NOT NULL,
  `total_criteria` int(11) DEFAULT NULL,
  `matched_criteria_count` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

-- --------------------------------------------------------

--
-- Table structure for table `shared_paper`
--

CREATE TABLE `shared_paper` (
  `shareID` int(11) NOT NULL,
  `paper_id` int(11) NOT NULL,
  `userID` int(11) DEFAULT NULL,
  `authorComment` varchar(255) DEFAULT NULL,
  `shareType` enum('public','private') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_520_ci ROW_FORMAT=DYNAMIC;

--
-- Dumping data for table `shared_paper`
--

INSERT INTO `shared_paper` (`shareID`, `paper_id`, `userID`, `authorComment`, `shareType`) VALUES
(1, 1, 7, 'please check it', 'public');

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
(1, 'Student Peer Review System', 'https://www.linkedin.com/in/mjmerzaee', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

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
(12, 'Reviewer', '2', 'reviewer2@gmail.com', '202cb962ac59075b964b07152d234b70', '1713235920_WhatsApp Image 2024-04-16 at 00.57.37_e5b952c8.jpg', '2024-04-13 17:16:21', 'Active', 2, 'IT, Bla, bla'),
(15, 'raz', 'admin', 'admin@admin.com', '202cb962ac59075b964b07152d234b70', '1713087840_n00139960-b.jpg', '2024-04-14 13:48:02', 'Active', 1, 'Administration, Data Analysis, something '),
(16, 'دکتر جمعه', 'محمدی', 'juma@gmail.com', '38f629170ac3ab74b9d6d2cc411c2f3c', NULL, '2024-04-16 17:03:16', 'Active', 3, ''),
(17, 'raz', 'yousuf', 'raz@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '2024-04-17 06:31:20', 'Active', 2, ''),
(18, 'razrev', 'yousuf', 'razrev@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '2024-04-17 06:41:41', 'Active', 3, ''),
(19, 'Mirzaee', 'Qabila', 'mirzaee@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '2024-04-17 15:18:46', 'Active', 2, ''),
(20, 'Test', '10', 'test@gmail.com', '202cb962ac59075b964b07152d234b70', NULL, '2024-04-25 01:55:55', 'Active', 2, ''),
(21, 'Kapil', 'Sir', 'kapil@lhu.ac.uk', '202cb962ac59075b964b07152d234b70', NULL, '2024-05-01 14:03:30', 'Active', 1, 'IT, Data Science, Sport.'),
(22, 'Kapil', 'reviewer', 'kapilreviewer@lhu.ac.uk', '202cb962ac59075b964b07152d234b70', NULL, '2024-05-01 14:06:05', 'Active', 2, '');

-- --------------------------------------------------------

--
-- Stand-in structure for view `user_paper_info`
-- (See below for the actual view)
--
CREATE TABLE `user_paper_info` (
`id` int(11)
,`name` varchar(401)
,`user_type` tinyint(1)
,`knowledge_areas` varchar(255)
,`papers_shared` bigint(21)
,`reviews_made` bigint(21)
,`reviews_accepted` decimal(22,0)
);

-- --------------------------------------------------------

--
-- Structure for view `average_grades`
--
DROP TABLE IF EXISTS `average_grades`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `average_grades`  AS SELECT `rg`.`paper_id` AS `paper_id`, `p`.`title` AS `title`, avg(`rg`.`grade`) AS `average_grade` FROM (`grades` `rg` join `papers` `p` on(`rg`.`paper_id` = `p`.`paper_id`)) GROUP BY `rg`.`paper_id`, `p`.`title` ;

-- --------------------------------------------------------

--
-- Structure for view `paper_review_info`
--
DROP TABLE IF EXISTS `paper_review_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `paper_review_info`  AS SELECT `p`.`paper_id` AS `paper_id`, `p`.`title` AS `title`, `p`.`review_status` AS `paper_Status`, concat(`u`.`firstname`,' ',`u`.`lastname`) AS `reviewer_name`, concat(`u2`.`firstname`,' ',`u2`.`lastname`) AS `author_name`, `r`.`reviewID` AS `reviewID`, `r`.`status` AS `status`, `rs`.`score` AS `score`, `rs`.`total_criteria` AS `total_criteria`, `rs`.`matched_criteria_count` AS `matched_criteria_count` FROM (((((`papers` `p` join `shared_paper` `sp` on(`p`.`paper_id` = `sp`.`paper_id`)) left join `reviews` `r` on(`sp`.`shareID` = `r`.`shareID`)) left join `users` `u` on(`r`.`reviewerID` = `u`.`id`)) left join `users` `u2` on(`p`.`author_id` = `u2`.`id`)) left join `review_score` `rs` on(`r`.`reviewID` = `rs`.`reviewID`)) ;

-- --------------------------------------------------------

--
-- Structure for view `user_paper_info`
--
DROP TABLE IF EXISTS `user_paper_info`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `user_paper_info`  AS SELECT `u`.`id` AS `id`, concat(`u`.`firstname`,' ',`u`.`lastname`) AS `name`, `u`.`user_type` AS `user_type`, `u`.`knowledge_areas` AS `knowledge_areas`, count(distinct `sp`.`paper_id`) AS `papers_shared`, count(distinct `r`.`reviewID`) AS `reviews_made`, sum(case when `r`.`status` = 'accepted' then 1 else 0 end) AS `reviews_accepted` FROM ((`users` `u` left join `shared_paper` `sp` on(`u`.`id` = `sp`.`userID`)) left join `reviews` `r` on(`u`.`id` = `r`.`reviewerID`)) WHERE `u`.`user_type` in (2,3) GROUP BY `u`.`id` ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `annotations`
--
ALTER TABLE `annotations`
  ADD PRIMARY KEY (`annotation_id`),
  ADD KEY `paper_id` (`paper_id`),
  ADD KEY `reviewer_id` (`reviewer_id`),
  ADD KEY `share_id` (`share_id`);

--
-- Indexes for table `annotation_comments`
--
ALTER TABLE `annotation_comments`
  ADD PRIMARY KEY (`comment_id`),
  ADD KEY `annotation_id` (`annotation_id`),
  ADD KEY `commenter_id` (`commenter_id`);

--
-- Indexes for table `annotation_likes`
--
ALTER TABLE `annotation_likes`
  ADD PRIMARY KEY (`like_id`),
  ADD KEY `fk_comment_id` (`comment_id`),
  ADD KEY `fk_liker_id` (`liker_id`);

--
-- Indexes for table `annotation_replies`
--
ALTER TABLE `annotation_replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `fk_comment_id_reply` (`comment_id`),
  ADD KEY `fk_replier_id` (`replier_id`);

--
-- Indexes for table `criteria_list`
--
ALTER TABLE `criteria_list`
  ADD PRIMARY KEY (`c_id`) USING BTREE;

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
  ADD PRIMARY KEY (`range_id`) USING BTREE,
  ADD KEY `criteria_id` (`criteria_id`) USING BTREE;

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`group_id`);

--
-- Indexes for table `group_members`
--
ALTER TABLE `group_members`
  ADD PRIMARY KEY (`group_id`,`user_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `papers`
--
ALTER TABLE `papers`
  ADD PRIMARY KEY (`paper_id`) USING BTREE,
  ADD KEY `author_id` (`author_id`) USING BTREE;

--
-- Indexes for table `paper_criteria`
--
ALTER TABLE `paper_criteria`
  ADD PRIMARY KEY (`paper_id`,`criteria_id`) USING BTREE,
  ADD KEY `paper_criteria_ibfk_2` (`criteria_id`) USING BTREE;

--
-- Indexes for table `paper_shares`
--
ALTER TABLE `paper_shares`
  ADD PRIMARY KEY (`share_id`),
  ADD UNIQUE KEY `idx_paper_share` (`paper_id`,`user_id`),
  ADD UNIQUE KEY `idx_paper_share_group` (`paper_id`,`group_id`),
  ADD KEY `group_id` (`group_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `author_id` (`author_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`reviewID`) USING BTREE,
  ADD UNIQUE KEY `reviewerID` (`reviewerID`,`shareID`) USING BTREE,
  ADD KEY `shareID` (`shareID`) USING BTREE;

--
-- Indexes for table `review_comment`
--
ALTER TABLE `review_comment`
  ADD PRIMARY KEY (`commentID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE,
  ADD KEY `parentCommentID` (`parentCommentID`) USING BTREE,
  ADD KEY `reviewID` (`reviewID`) USING BTREE;

--
-- Indexes for table `review_comment_like`
--
ALTER TABLE `review_comment_like`
  ADD UNIQUE KEY `uniq_user_comment_like` (`commentID`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE;

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
-- Indexes for table `shared_paper`
--
ALTER TABLE `shared_paper`
  ADD PRIMARY KEY (`shareID`) USING BTREE,
  ADD UNIQUE KEY `unique_row` (`paper_id`,`userID`) USING BTREE,
  ADD KEY `userID` (`userID`) USING BTREE,
  ADD KEY `paper_id` (`paper_id`) USING BTREE;

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
-- AUTO_INCREMENT for table `annotations`
--
ALTER TABLE `annotations`
  MODIFY `annotation_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=81;

--
-- AUTO_INCREMENT for table `annotation_comments`
--
ALTER TABLE `annotation_comments`
  MODIFY `comment_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `annotation_likes`
--
ALTER TABLE `annotation_likes`
  MODIFY `like_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `annotation_replies`
--
ALTER TABLE `annotation_replies`
  MODIFY `reply_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `criteria_list`
--
ALTER TABLE `criteria_list`
  MODIFY `c_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `grades`
--
ALTER TABLE `grades`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=205;

--
-- AUTO_INCREMENT for table `grade_ranges`
--
ALTER TABLE `grade_ranges`
  MODIFY `range_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `group_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `papers`
--
ALTER TABLE `papers`
  MODIFY `paper_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `paper_shares`
--
ALTER TABLE `paper_shares`
  MODIFY `share_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `reviewID` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `review_comment`
--
ALTER TABLE `review_comment`
  MODIFY `commentID` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `annotations`
--
ALTER TABLE `annotations`
  ADD CONSTRAINT `annotations_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `annotations_ibfk_2` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `annotations_ibfk_3` FOREIGN KEY (`share_id`) REFERENCES `paper_shares` (`share_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `annotation_comments`
--
ALTER TABLE `annotation_comments`
  ADD CONSTRAINT `annotation_comments_ibfk_1` FOREIGN KEY (`annotation_id`) REFERENCES `annotations` (`annotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `annotation_comments_ibfk_2` FOREIGN KEY (`commenter_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `annotation_likes`
--
ALTER TABLE `annotation_likes`
  ADD CONSTRAINT `fk_comment_id` FOREIGN KEY (`comment_id`) REFERENCES `annotations` (`annotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_liker_id` FOREIGN KEY (`liker_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `annotation_replies`
--
ALTER TABLE `annotation_replies`
  ADD CONSTRAINT `fk_comment_id_reply` FOREIGN KEY (`comment_id`) REFERENCES `annotations` (`annotation_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_replier_id` FOREIGN KEY (`replier_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

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
-- Constraints for table `group_members`
--
ALTER TABLE `group_members`
  ADD CONSTRAINT `group_members_ibfk_1` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `group_members_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `papers`
--
ALTER TABLE `papers`
  ADD CONSTRAINT `fk_reviewer_id` FOREIGN KEY (`reviewer_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `papers_ibfk_1` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `paper_criteria`
--
ALTER TABLE `paper_criteria`
  ADD CONSTRAINT `paper_criteria_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `paper_criteria_ibfk_2` FOREIGN KEY (`criteria_id`) REFERENCES `criteria_list` (`c_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `paper_shares`
--
ALTER TABLE `paper_shares`
  ADD CONSTRAINT `paper_shares_ibfk_1` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`) ON DELETE CASCADE,
  ADD CONSTRAINT `paper_shares_ibfk_2` FOREIGN KEY (`group_id`) REFERENCES `groups` (`group_id`) ON DELETE SET NULL,
  ADD CONSTRAINT `paper_shares_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `paper_shares_ibfk_4` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`reviewerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`shareID`) REFERENCES `shared_paper` (`shareID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_comment`
--
ALTER TABLE `review_comment`
  ADD CONSTRAINT `review_comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_ibfk_4` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_ibfk_5` FOREIGN KEY (`parentCommentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_comment_like`
--
ALTER TABLE `review_comment_like`
  ADD CONSTRAINT `review_comment_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_comment_like_ibfk_3` FOREIGN KEY (`commentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_like`
--
ALTER TABLE `review_like`
  ADD CONSTRAINT `review_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_like_ibfk_3` FOREIGN KEY (`reviewid`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_notification`
--
ALTER TABLE `review_notification`
  ADD CONSTRAINT `review_notification_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_notification_ibfk_3` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `review_score`
--
ALTER TABLE `review_score`
  ADD CONSTRAINT `review_score_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `review_score_ibfk_3` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `shared_paper`
--
ALTER TABLE `shared_paper`
  ADD CONSTRAINT `shared_paper_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `shared_paper_ibfk_3` FOREIGN KEY (`paper_id`) REFERENCES `papers` (`paper_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
