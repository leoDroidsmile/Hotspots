/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : MySQL
 Source Server Version : 100427 (10.4.27-MariaDB)
 Source Host           : localhost:3306
 Source Schema         : hotspots

 Target Server Type    : MySQL
 Target Server Version : 100427 (10.4.27-MariaDB)
 File Encoding         : 65001

 Date: 26/02/2023 13:01:31
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for daily_earnings
-- ----------------------------
DROP TABLE IF EXISTS `daily_earnings`;
CREATE TABLE `daily_earnings`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of daily_earnings
-- ----------------------------
INSERT INTO `daily_earnings` VALUES (1, '1', '2023-02-23', '0.06728498');
INSERT INTO `daily_earnings` VALUES (2, '1', '2023-02-22', '0.08528498');
INSERT INTO `daily_earnings` VALUES (3, '1', '2023-02-24', '0.05728498');
INSERT INTO `daily_earnings` VALUES (4, '3', '2023-02-21', '0.02864249');
INSERT INTO `daily_earnings` VALUES (5, '3', '2023-02-24', '0.02864249');

-- ----------------------------
-- Table structure for hotspots
-- ----------------------------
DROP TABLE IF EXISTS `hotspots`;
CREATE TABLE `hotspots`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `state` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner_id` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `percentage` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `daily_earning` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `monthly_earning` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 23 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of hotspots
-- ----------------------------
INSERT INTO `hotspots` VALUES (21, 'long-pickle-scallop', 'Goodwood', 'Nova Scotia', 'Canada', '112EyGwBYLtsLu7msoGBhDb5fjNLpQ9fVW62AKRnsPfTsmCkyMJK', '3', '50', 'online', '0.02864249', '4.88851983', '2023-02-24 09:47:17', '2023-02-24 09:52:11');
INSERT INTO `hotspots` VALUES (22, 'long-pickle-scallop', 'Goodwood', 'Nova Scotia', 'Canada', '112EyGwBYLtsLu7msoGBhDb5fjNLpQ9fVW62AKRnsPfTsmCkyMJK', '3', '50', 'online', '0.02864249', '4.88851983', '2023-02-24 09:52:03', '2023-02-24 09:52:12');

-- ----------------------------
-- Table structure for migrations
-- ----------------------------
DROP TABLE IF EXISTS `migrations`;
CREATE TABLE `migrations`  (
  `id` int UNSIGNED NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 7 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of migrations
-- ----------------------------
INSERT INTO `migrations` VALUES (1, '2014_10_12_000000_create_hotspots_table', 1);
INSERT INTO `migrations` VALUES (2, '2014_10_12_000000_create_payments_table', 1);
INSERT INTO `migrations` VALUES (3, '2014_10_12_000000_create_users_table', 1);
INSERT INTO `migrations` VALUES (4, '2019_12_14_000001_create_personal_access_tokens_table', 1);
INSERT INTO `migrations` VALUES (5, '2014_10_12_000000_create_monthly_earnings_table', 2);
INSERT INTO `migrations` VALUES (6, '2014_10_12_000000_create_daily_earnings_table', 3);

-- ----------------------------
-- Table structure for payments
-- ----------------------------
DROP TABLE IF EXISTS `payments`;
CREATE TABLE `payments`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` int UNSIGNED NOT NULL,
  `during` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `random` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_id` int UNSIGNED NOT NULL,
  `paid_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 3 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of payments
-- ----------------------------
INSERT INTO `payments` VALUES (1, 1, '2023-1', '51.7269483', 'MfyJL8', 1, NULL);
INSERT INTO `payments` VALUES (2, 3, '2023-1', '34.4846322', '50SfOd', 2, '2023-02-21 09:16:25');

-- ----------------------------
-- Table structure for personal_access_tokens
-- ----------------------------
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `personal_access_tokens_token_unique`(`token` ASC) USING BTREE,
  INDEX `personal_access_tokens_tokenable_type_tokenable_id_index`(`tokenable_type` ASC, `tokenable_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of personal_access_tokens
-- ----------------------------

-- ----------------------------
-- Table structure for users
-- ----------------------------
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `users_email_unique`(`email` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 6 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of users
-- ----------------------------
INSERT INTO `users` VALUES (1, 'Jake ', 'jake@gmail.com', NULL, 1, '$2y$10$dCEtuk8ldnW08r1.Obz5V.pOlMjmaFzT33YpQi/b1TmZhJSlKsVd2', 'h1eHzhHiclPHcrFQB330Ds7oHDbtq98NLQDwSD2PQ43MPQgjhdcIHnJhBZXk', NULL, NULL);
INSERT INTO `users` VALUES (3, 'Jake Name1', 'jake11@gmail.com', '2023-02-21 07:54:45', 0, '$2y$10$CvfOQ0r6tbPghN1aG.N5gOvLX/58zVzXniCo7Q14KNIxmgI0NDVHa', 'bH1IT31VAvcrgP2kSjQXuiM3Hilk5yRRnBvYGy6hJY0blTZyx9KGose63eUj', '2023-02-21 07:54:45', '2023-02-21 07:54:45');
INSERT INTO `users` VALUES (4, 'Jake Name2', 'jake22@gmail.com', '2023-02-21 07:55:00', 0, '$2y$10$wLquPXbQgx4bFJr7uE4l1eUcw4cxGeoCUgDl5RhuohOqj2tzN9xE2', '9ekBgbxx3ka5Gk75kXPk72Cr1yQMU15Baws2ARkHLL65WnJY1cqDrWaMtOg8', '2023-02-21 07:55:01', '2023-02-21 07:55:01');
INSERT INTO `users` VALUES (5, 'Jake Name3', 'jake33@gmail.com', '2023-02-22 05:39:40', 0, '$2y$10$dCEtuk8ldnW08r1.Obz5V.pOlMjmaFzT33YpQi/b1TmZhJSlKsVd2', NULL, '2023-02-22 05:39:40', '2023-02-22 05:39:40');

SET FOREIGN_KEY_CHECKS = 1;
