-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               5.7.33 - MySQL Community Server (GPL)
-- Server OS:                    Win64
-- HeidiSQL Version:             11.2.0.6213
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table zimfiles.directors
CREATE TABLE IF NOT EXISTS `directors` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `nas_dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `vps_dir` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `level` int(11) NOT NULL DEFAULT '1',
  `parent_id` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.directors: ~2 rows (approximately)
DELETE FROM `directors`;
/*!40000 ALTER TABLE `directors` DISABLE KEYS */;
INSERT INTO `directors` (`id`, `user_id`, `nas_dir`, `vps_dir`, `created_at`, `updated_at`, `level`, `parent_id`) VALUES
	(12, 1, '/dir_03092021/', '-', '2021-09-03 09:31:26', '2021-09-03 09:31:26', 1, 0),
	(15, 1, 'expert', '-', '2021-09-03 09:42:25', '2021-09-03 09:42:25', 2, 12);
/*!40000 ALTER TABLE `directors` ENABLE KEYS */;

-- Dumping structure for table zimfiles.files
CREATE TABLE IF NOT EXISTS `files` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `director_id` int(11) NOT NULL,
  `image` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `time_upload` timestamp NULL DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '0',
  `thumbnail` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.files: ~3 rows (approximately)
DELETE FROM `files`;
/*!40000 ALTER TABLE `files` DISABLE KEYS */;
INSERT INTO `files` (`id`, `director_id`, `image`, `time_upload`, `status`, `thumbnail`, `name`, `created_at`, `updated_at`) VALUES
	(2, 4, 'ava.jpg', '2021-07-08 04:01:53', 1, 'ava.jpg', 'ava.jpg', '2021-07-08 04:01:53', '2021-07-08 04:01:53'),
	(3, 4, 'avaHostingerAndMail.jpg', '2021-07-08 06:27:01', 1, 'avaHostingerAndMail.jpg', 'avaHostingerAndMail.jpg', '2021-07-08 06:27:01', '2021-07-08 06:27:01'),
	(7, 4, 'ava.jpg', '2021-07-09 03:48:02', 1, 'ava.jpg', 'ava.jpg', '2021-07-09 03:48:02', '2021-07-09 03:48:02'),
	(8, 4, 'avaHostingerAndMail.jpg', '2021-07-09 03:48:02', 1, 'avaHostingerAndMail.jpg', 'avaHostingerAndMail.jpg', '2021-07-09 03:48:02', '2021-07-09 03:48:02'),
	(9, 15, 'ava.jpg', '2021-09-04 14:37:20', 1, '-', 'Editor01', '2021-09-04 14:37:20', '2021-09-04 14:37:20');
/*!40000 ALTER TABLE `files` ENABLE KEYS */;

-- Dumping structure for table zimfiles.jobs
CREATE TABLE IF NOT EXISTS `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `director_id` int(11) NOT NULL,
  `file_id` int(11) DEFAULT NULL,
  `file_jobs` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT '1',
  `time_upload` timestamp NULL DEFAULT NULL,
  `time_confirm` timestamp NULL DEFAULT NULL,
  `time_done` timestamp NULL DEFAULT NULL,
  `type` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `editor_assign` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.jobs: ~3 rows (approximately)
DELETE FROM `jobs`;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
INSERT INTO `jobs` (`id`, `user_id`, `director_id`, `file_id`, `file_jobs`, `status`, `time_upload`, `time_confirm`, `time_done`, `type`, `created_at`, `updated_at`, `editor_assign`) VALUES
	(1, 1, 7, NULL, 'testJobsFile.txt', 2, '2021-09-06 02:19:49', '2021-09-06 02:19:49', NULL, 1, '2021-08-28 08:17:08', '2021-09-06 02:19:49', 5),
	(2, 1, 15, 9, 'cham cong.txt', 1, '2021-09-03 13:44:20', '2021-09-04 14:37:20', NULL, 1, '2021-09-03 13:44:20', '2021-09-04 14:37:20', 2),
	(3, 1, 15, NULL, 'cham cong.txt', 2, '2021-09-03 13:46:29', NULL, NULL, 1, '2021-09-03 13:46:29', '2021-09-05 07:44:59', 5),
	(4, 1, 15, NULL, 'cham cong.txt', 2, '2021-09-03 13:46:29', NULL, NULL, 1, '2021-09-03 13:46:29', '2021-09-06 03:59:48', 1);
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;

-- Dumping structure for table zimfiles.migrations
CREATE TABLE IF NOT EXISTS `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.migrations: ~10 rows (approximately)
DELETE FROM `migrations`;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
	(8, '2014_10_12_000000_create_users_table', 1),
	(9, '2016_06_01_000001_create_oauth_auth_codes_table', 1),
	(10, '2016_06_01_000002_create_oauth_access_tokens_table', 1),
	(11, '2016_06_01_000003_create_oauth_refresh_tokens_table', 1),
	(12, '2016_06_01_000004_create_oauth_clients_table', 1),
	(13, '2016_06_01_000005_create_oauth_personal_access_clients_table', 1),
	(14, '2021_07_05_071443_files', 2),
	(15, '2021_07_07_024225_directors', 2),
	(16, '2021_08_28_075242_jobs', 3),
	(17, '2021_09_03_091924_add_column_dir', 4);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;

-- Dumping structure for table zimfiles.oauth_access_tokens
CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_access_tokens_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.oauth_access_tokens: ~1 rows (approximately)
DELETE FROM `oauth_access_tokens`;
/*!40000 ALTER TABLE `oauth_access_tokens` DISABLE KEYS */;
INSERT INTO `oauth_access_tokens` (`id`, `user_id`, `client_id`, `name`, `scopes`, `revoked`, `created_at`, `updated_at`, `expires_at`) VALUES
	('65a61cfaf3195393a88b20ba57e965b2f52de6674b118e74d242b85b8f04a0f7e5528ba20f9c846a', 1, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'user@gmail.com_1', '[]', 0, '2021-08-28 08:05:50', '2021-08-28 08:05:50', '2022-08-28 08:05:50'),
	('9d767b3e909ba0e358070316d576307e6bb60c7806580caa0094ca452e2ba6a56dee7c5b2b560952', 1, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'user@gmail.com_1', '[]', 0, '2021-08-29 07:41:48', '2021-08-29 07:41:48', '2022-08-29 07:41:48'),
	('a8a67094dc065cb13a7cb3f6b0abd4f65d3db261b5fbb83c47324080884af05cf0d52f8a38a3c9d3', 6, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'qc01@gmail.com_6', '[]', 0, '2021-09-05 08:53:50', '2021-09-05 08:53:50', '2022-09-05 08:53:50'),
	('be4acfaf8551f1b09c5712a4d6376dcca0c2266ad3e3ab80fce02483dcc0ad75a949bf4e0bf8ff04', 5, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'editor01@gmail.com_5', '[]', 0, '2021-09-04 07:53:19', '2021-09-04 07:53:19', '2022-09-04 07:53:19'),
	('c2a3bfd99200567b4c0ce8decd39317a9bc77dd2661ffcd5104e96e78840f0573c827e52021419e5', 2, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'admin@gmail.com_2', '[]', 0, '2021-07-12 09:44:25', '2021-07-12 09:44:25', '2022-07-12 09:44:25'),
	('dd57cf488e8f16696f773828c083bbb0e3f9b97e7bcff2f004a381e4a749a657d4c89dbd2c43afc6', 1, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', 'user@gmail.com_1', '[]', 0, '2021-08-27 13:35:23', '2021-08-27 13:35:23', '2022-08-27 13:35:23');
/*!40000 ALTER TABLE `oauth_access_tokens` ENABLE KEYS */;

-- Dumping structure for table zimfiles.oauth_auth_codes
CREATE TABLE IF NOT EXISTS `oauth_auth_codes` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned NOT NULL,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `scopes` text COLLATE utf8mb4_unicode_ci,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_auth_codes_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.oauth_auth_codes: ~0 rows (approximately)
DELETE FROM `oauth_auth_codes`;
/*!40000 ALTER TABLE `oauth_auth_codes` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_auth_codes` ENABLE KEYS */;

-- Dumping structure for table zimfiles.oauth_clients
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secret` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `provider` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `redirect` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `personal_access_client` tinyint(1) NOT NULL,
  `password_client` tinyint(1) NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_clients_user_id_index` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.oauth_clients: ~2 rows (approximately)
DELETE FROM `oauth_clients`;
/*!40000 ALTER TABLE `oauth_clients` DISABLE KEYS */;
INSERT INTO `oauth_clients` (`id`, `user_id`, `name`, `secret`, `provider`, `redirect`, `personal_access_client`, `password_client`, `revoked`, `created_at`, `updated_at`) VALUES
	('93d7fe54-a10e-4d05-9945-3a483a657099', NULL, 'Laravel Personal Access Client', '7avjcLLxUMVM7X1aRCNaxSN9tUsKTcJFz4p1asN9', NULL, 'http://localhost', 1, 0, 0, '2021-07-06 08:09:02', '2021-07-06 08:09:02'),
	('93d7fe54-a74a-4d87-a6ff-8a45e1b87f41', NULL, 'Laravel Password Grant Client', 'gy6O30uSnsZ5MfcxkiSeEyOLVzk5NX9XxqnDFXLg', 'users', 'http://localhost', 0, 1, 0, '2021-07-06 08:09:02', '2021-07-06 08:09:02'),
	('93d7fed2-c1fa-4477-b1ab-9da663d45c72', NULL, 'Laravel Personal Access Client', 'tPjMuLignVWyQhy7uyQYiXDlC3Td80Mvmsebwokt', NULL, 'http://localhost', 1, 0, 0, '2021-07-06 08:10:24', '2021-07-06 08:10:24');
/*!40000 ALTER TABLE `oauth_clients` ENABLE KEYS */;

-- Dumping structure for table zimfiles.oauth_personal_access_clients
CREATE TABLE IF NOT EXISTS `oauth_personal_access_clients` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `client_id` char(36) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.oauth_personal_access_clients: ~2 rows (approximately)
DELETE FROM `oauth_personal_access_clients`;
/*!40000 ALTER TABLE `oauth_personal_access_clients` DISABLE KEYS */;
INSERT INTO `oauth_personal_access_clients` (`id`, `client_id`, `created_at`, `updated_at`) VALUES
	(1, '93d7fe54-a10e-4d05-9945-3a483a657099', '2021-07-06 08:09:02', '2021-07-06 08:09:02'),
	(2, '93d7fed2-c1fa-4477-b1ab-9da663d45c72', '2021-07-06 08:10:24', '2021-07-06 08:10:24');
/*!40000 ALTER TABLE `oauth_personal_access_clients` ENABLE KEYS */;

-- Dumping structure for table zimfiles.oauth_refresh_tokens
CREATE TABLE IF NOT EXISTS `oauth_refresh_tokens` (
  `id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token_id` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `revoked` tinyint(1) NOT NULL,
  `expires_at` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `oauth_refresh_tokens_access_token_id_index` (`access_token_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.oauth_refresh_tokens: ~0 rows (approximately)
DELETE FROM `oauth_refresh_tokens`;
/*!40000 ALTER TABLE `oauth_refresh_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `oauth_refresh_tokens` ENABLE KEYS */;

-- Dumping structure for table zimfiles.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `verified_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `total_file` int(11) NOT NULL DEFAULT '0',
  `base_path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `role` int(11) NOT NULL DEFAULT '1',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dumping data for table zimfiles.users: ~6 rows (approximately)
DELETE FROM `users`;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `verified_token`, `address`, `phone`, `status`, `total_file`, `base_path`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
	(1, 'LongNguyen3', 'nguyenlongit95@gmail.com', '2021-07-06 08:08:41', '$2y$10$05qgE/CByUXVOwmuoNBVIORzxE93sR5tcixgaipT55xW82e24RED.', '$2y$10$Yj8CuelnFbwVn20.KCpPn.3sdw.LyIiQzwZuW7BdJTFDfnM1UFXKe', 'no 08, 98, Ngoc Truc Stress, Dai Mo, Nam Tu Liem, HN', '0393803548', 1, 0, '/files/user/', 1, 'asdwqdibuidig', NULL, '2021-07-13 09:53:40'),
	(2, 'admin', 'admin@gmail.com', '2021-07-06 08:08:41', '$2y$10$9zkw50ouqFNrj4ANO3dHzOLiiYUZhNWGc.K/..2Nd5KktxiESwcb.', '$2y$10$WU8yaY9aEhoHfZmotTr0vOTnLTGxM.75ZzdiQtywcXV4dXMQiFTai', 'Ha Noi', '0123456789103', 1, 0, '/files/user/', 0, 'asdwqdibuidig', NULL, NULL),
	(3, 'User01', 'user02@gmail.com', '2021-07-13 03:31:13', '$2y$10$gQbgnF/Rn4zb7uoZOzoCAeO6jINUruiGTogjrHxywoWhzdebEAFpy', '$2y$10$M4Fj4E5Hrgg5dE1Cb6wP7Osgzhp7aKoW6VSY0PrBZD/nzZWKNnVuG', 'Ha Noi', '0123456789102', 1, 0, '/files/user/', 1, 'asdwqdibuidig', NULL, NULL),
	(4, 'NguyenLongIT95', 'nguyenlongit95@gmail.com', NULL, '$2y$10$/1jSmh6WX3QDG5oHGJvMyOXVILdLTyO9RxyuKYR37Lkd2QiAQKGNG', '$2y$10$/1jSmh6WX3QDG5oHGJvMyOXVILdLTyO9RxyuKYR37Lkd2QiAQKGNG', 'Ha Noi', '0393803548', 1, 0, '/files/user/', 1, NULL, '2021-07-13 07:40:26', '2021-07-13 07:40:26'),
	(5, 'Editor01', 'editor01@gmail.com', NULL, '$2y$10$zOhFTPP/lHi3Pjk5dSVA.uMdl/w53WBXcCWGPAAcXNba1.ULiQiX2', NULL, 'Ha Noi', '123456789', 1, 0, NULL, 2, NULL, NULL, NULL),
	(6, 'QC01', 'qc01@gmail.com', NULL, '$2y$10$zOhFTPP/lHi3Pjk5dSVA.uMdl/w53WBXcCWGPAAcXNba1.ULiQiX2', NULL, 'Ha Noi', '1234567890', 1, 0, NULL, 3, NULL, NULL, NULL);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
