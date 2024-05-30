/*
 Navicat Premium Data Transfer

 Source Server         : MySQL
 Source Server Type    : MySQL
 Source Server Version : 80300
 Source Host           : localhost:3306
 Source Schema         : sprs

 Target Server Type    : MySQL
 Target Server Version : 80300
 File Encoding         : 65001

 Date: 14/04/2024 00:01:56
 
 default user:
	email: admin@admin.com
	password: admin
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for criteria
-- ----------------------------
DROP TABLE IF EXISTS `criteria`;
CREATE TABLE `criteria`  (
  `criteria1` bit(1) NOT NULL DEFAULT b'0',
  `criteria2` bit(1) NOT NULL,
  `criteria3` bit(1) NULL DEFAULT NULL,
  `criteria4` bit(1) NULL DEFAULT NULL,
  `criteria5` bit(1) NULL DEFAULT NULL
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of criteria
-- ----------------------------

-- ----------------------------
-- Table structure for files
-- ----------------------------
DROP TABLE IF EXISTS `files`;
CREATE TABLE `files`  (
  `fileID` int NOT NULL AUTO_INCREMENT,
  `userID` int NULL DEFAULT NULL,
  `Filename` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `uploadDate` datetime NULL DEFAULT NULL,
  `fileURL` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `key_words` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` enum('public','private') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`fileID`) USING BTREE,
  INDEX `userID`(`userID`) USING BTREE,
  CONSTRAINT `files_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of files
-- ----------------------------
INSERT INTO `files` VALUES (1, 14, 'thesis', '2024-04-12 23:34:43', NULL, NULL, 'public');

-- ----------------------------
-- Table structure for fileshare
-- ----------------------------
DROP TABLE IF EXISTS `fileshare`;
CREATE TABLE `fileshare`  (
  `shareID` int NOT NULL AUTO_INCREMENT,
  `fileID` int NOT NULL,
  `userID` int NULL DEFAULT NULL,
  `firstComment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`shareID`) USING BTREE,
  INDEX `userID`(`userID`) USING BTREE,
  INDEX `fileID`(`fileID`) USING BTREE,
  CONSTRAINT `fileshare_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `fileshare_ibfk_2` FOREIGN KEY (`fileID`) REFERENCES `files` (`fileID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of fileshare
-- ----------------------------
INSERT INTO `fileshare` VALUES (1, 1, 7, 'please check it');

-- ----------------------------
-- Table structure for review_comment
-- ----------------------------
DROP TABLE IF EXISTS `review_comment`;
CREATE TABLE `review_comment`  (
  `commentID` int NOT NULL,
  `reviewID` int NOT NULL,
  `userID` int NULL DEFAULT NULL,
  `parentCommentID` int NULL DEFAULT NULL,
  `comment` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `commentDate` datetime NOT NULL,
  PRIMARY KEY (`commentID`) USING BTREE,
  INDEX `reviewID`(`reviewID`) USING BTREE,
  INDEX `userID`(`userID`) USING BTREE,
  INDEX `parentCommentID`(`parentCommentID`) USING BTREE,
  CONSTRAINT `review_comment_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_comment_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `review_comment_ibfk_3` FOREIGN KEY (`parentCommentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_comment
-- ----------------------------
INSERT INTO `review_comment` VALUES (1, 1, 12, NULL, 'you are rigth', '2024-04-13 23:12:15');

-- ----------------------------
-- Table structure for review_comment_like
-- ----------------------------
DROP TABLE IF EXISTS `review_comment_like`;
CREATE TABLE `review_comment_like`  (
  `commentID` int NOT NULL,
  `userID` int NOT NULL,
  `likeDate` datetime NOT NULL,
  INDEX `userID`(`userID`) USING BTREE,
  UNIQUE INDEX `uniq_user_comment_like`(`commentID`, `userID`) USING BTREE,
  CONSTRAINT `review_comment_like_ibfk_1` FOREIGN KEY (`commentID`) REFERENCES `review_comment` (`commentID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_comment_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_comment_like
-- ----------------------------
INSERT INTO `review_comment_like` VALUES (1, 7, '2024-04-13 23:17:11');

-- ----------------------------
-- Table structure for review_detail
-- ----------------------------
DROP TABLE IF EXISTS `review_detail`;
CREATE TABLE `review_detail`  (
  `reviewID` int NOT NULL,
  `reviewerID` int NOT NULL,
  `text` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE INDEX `reviewID`(`reviewID`, `reviewerID`) USING BTREE,
  INDEX `reviewerID`(`reviewerID`) USING BTREE,
  CONSTRAINT `review_detail_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_detail_ibfk_2` FOREIGN KEY (`reviewerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_detail
-- ----------------------------

-- ----------------------------
-- Table structure for review_like
-- ----------------------------
DROP TABLE IF EXISTS `review_like`;
CREATE TABLE `review_like`  (
  `reviewid` int NOT NULL,
  `userID` int NOT NULL,
  `likeDate` datetime NULL DEFAULT NULL,
  INDEX `userID`(`userID`) USING BTREE,
  UNIQUE INDEX `uniq_user_review_like`(`reviewid`, `userID`) USING BTREE,
  CONSTRAINT `review_like_ibfk_1` FOREIGN KEY (`reviewid`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_like_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_like
-- ----------------------------

-- ----------------------------
-- Table structure for review_notification
-- ----------------------------
DROP TABLE IF EXISTS `review_notification`;
CREATE TABLE `review_notification`  (
  `reviewID` int NOT NULL,
  `userID` int NOT NULL,
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `status` enum('pending','read') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `datetime` datetime NOT NULL,
  UNIQUE INDEX `reviewID`(`reviewID`, `userID`) USING BTREE,
  INDEX `userID`(`userID`) USING BTREE,
  CONSTRAINT `review_notification_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_notification_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_notification
-- ----------------------------

-- ----------------------------
-- Table structure for review_score
-- ----------------------------
DROP TABLE IF EXISTS `review_score`;
CREATE TABLE `review_score`  (
  `reviewID` int NOT NULL,
  `userID` int NOT NULL,
  `score` tinyint NOT NULL,
  `score_date` datetime NOT NULL,
  UNIQUE INDEX `reviewID`(`reviewID`, `userID`) USING BTREE,
  INDEX `userID`(`userID`) USING BTREE,
  CONSTRAINT `review_score_ibfk_1` FOREIGN KEY (`reviewID`) REFERENCES `reviews` (`reviewID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `review_score_ibfk_2` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of review_score
-- ----------------------------

-- ----------------------------
-- Table structure for reviews
-- ----------------------------
DROP TABLE IF EXISTS `reviews`;
CREATE TABLE `reviews`  (
  `reviewID` int NOT NULL,
  `shareID` int NULL DEFAULT NULL,
  `reviewerID` int NULL DEFAULT NULL,
  `comment` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  `status` enum('pending','accepted','rejected') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT 'pending',
  `creationDate` datetime NULL DEFAULT NULL,
  `ownderID` int NULL DEFAULT NULL,
  PRIMARY KEY (`reviewID`) USING BTREE,
  INDEX `shareID`(`shareID`) USING BTREE,
  INDEX `reviewerID`(`reviewerID`) USING BTREE,
  INDEX `ownderID`(`ownderID`) USING BTREE,
  CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`shareID`) REFERENCES `fileshare` (`shareID`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`reviewerID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `reviews_ibfk_3` FOREIGN KEY (`ownderID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of reviews
-- ----------------------------
INSERT INTO `reviews` VALUES (1, 1, 7, 'its ok', 'pending', '2024-04-13 23:15:25', 14);

-- ----------------------------
-- Table structure for share_notofication
-- ----------------------------
DROP TABLE IF EXISTS `share_notofication`;
CREATE TABLE `share_notofication`  (
  `text` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `userID` int NOT NULL COMMENT 'who will recieve this notification?',
  `status` enum('New','Read') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `shareID` int NULL DEFAULT NULL,
  UNIQUE INDEX `userID`(`userID`, `shareID`) USING BTREE,
  INDEX `share_notofication_ibfk_2`(`shareID`) USING BTREE,
  CONSTRAINT `share_notofication_ibfk_1` FOREIGN KEY (`userID`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `share_notofication_ibfk_2` FOREIGN KEY (`shareID`) REFERENCES `fileshare` (`shareID`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of share_notofication
-- ----------------------------

-- ----------------------------
-- Table structure for system_settings
-- ----------------------------
DROP TABLE IF EXISTS `system_settings`;
CREATE TABLE `system_settings`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `contact` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `address` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `cover_img` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 2 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of system_settings
-- ----------------------------
INSERT INTO `system_settings` VALUES (1, 'Student Peer Review System', 'info@sample.comm', '+6948 8542 623', '2102  Caldwell Road, Rochester, New York, 14608', '');

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` int NOT NULL AUTO_INCREMENT,
  `firstname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `lastname` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `password` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `avatar` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL,
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('Active','Deactive') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `user_type` tinyint(1) NOT NULL DEFAULT 3,
  `knowledge_areas` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 15 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (7, 'Amir', 'Amiri', 'ahmadi@gmail.com', 'ac627ab1ccbdb62ec96e702f07f6425b', NULL, '2024-04-12 19:45:01', 'Active', 3, 'IS and IT, Science and technology');
INSERT INTO `users` VALUES (12, 'Reviewer2', 'of the faculty', 'reviewer2@gmail.com', 'f899139df5e1059396431415e770c6dd', NULL, '2024-04-13 17:16:21', 'Active', 2, '');
INSERT INTO `users` VALUES (14, 'Admin', '', 'admin@admin.com', '21232f297a57a5a743894a0e4a801fc3', NULL, '2024-04-13 18:17:10', 'Active', 1, 'Administration');

SET FOREIGN_KEY_CHECKS = 1;
