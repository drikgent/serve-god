-- MariaDB dump 10.19  Distrib 10.4.32-MariaDB, for Win64 (AMD64)
--
-- Host: localhost    Database: serve_god_db
-- ------------------------------------------------------
-- Server version	10.4.32-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache`
--

LOCK TABLES `cache` WRITE;
/*!40000 ALTER TABLE `cache` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cache_locks`
--

DROP TABLE IF EXISTS `cache_locks`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL,
  PRIMARY KEY (`key`),
  KEY `cache_locks_expiration_index` (`expiration`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cache_locks`
--

LOCK TABLES `cache_locks` WRITE;
/*!40000 ALTER TABLE `cache_locks` DISABLE KEYS */;
/*!40000 ALTER TABLE `cache_locks` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categories`
--

DROP TABLE IF EXISTS `categories`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `categories` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `description` text DEFAULT NULL,
  `accent_color` varchar(255) DEFAULT NULL,
  `cover_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `categories_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categories`
--

LOCK TABLES `categories` WRITE;
/*!40000 ALTER TABLE `categories` DISABLE KEYS */;
INSERT INTO `categories` VALUES (1,'Travel','travel',NULL,NULL,NULL,'2026-04-12 04:56:19','2026-04-12 05:38:43'),(2,'People','people',NULL,NULL,NULL,'2026-04-12 04:56:19','2026-04-12 05:38:43'),(3,'Moments','moments',NULL,NULL,NULL,'2026-04-12 04:56:19','2026-04-12 05:38:43'),(4,'Places','places',NULL,NULL,NULL,'2026-04-12 04:56:19','2026-04-12 05:38:43');
/*!40000 ALTER TABLE `categories` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `failed_jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `failed_jobs`
--

LOCK TABLES `failed_jobs` WRITE;
/*!40000 ALTER TABLE `failed_jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `failed_jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `job_batches`
--

LOCK TABLES `job_batches` WRITE;
/*!40000 ALTER TABLE `job_batches` DISABLE KEYS */;
/*!40000 ALTER TABLE `job_batches` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `jobs`
--

DROP TABLE IF EXISTS `jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `jobs` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `jobs_queue_index` (`queue`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `jobs`
--

LOCK TABLES `jobs` WRITE;
/*!40000 ALTER TABLE `jobs` DISABLE KEYS */;
/*!40000 ALTER TABLE `jobs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `media`
--

DROP TABLE IF EXISTS `media`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `media` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `uploader_id` bigint(20) unsigned DEFAULT NULL,
  `type` varchar(255) NOT NULL,
  `title` varchar(255) DEFAULT NULL,
  `file_path` varchar(255) NOT NULL,
  `thumbnail_path` varchar(255) DEFAULT NULL,
  `alt_text` varchar(255) DEFAULT NULL,
  `sort_order` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `source` varchar(255) NOT NULL DEFAULT 'upload',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `media_post_id_foreign` (`post_id`),
  KEY `media_uploader_id_foreign` (`uploader_id`),
  CONSTRAINT `media_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `media_uploader_id_foreign` FOREIGN KEY (`uploader_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `media`
--

LOCK TABLES `media` WRITE;
/*!40000 ALTER TABLE `media` DISABLE KEYS */;
INSERT INTO `media` VALUES (1,1,1,'image','Nag yoyosi si Coqs 1','/uploads/images/6c52a964-e71e-4b98-8a75-364ec55413a5.jpg',NULL,'Nag yoyosi si Coqs',0,1,'upload','2026-04-12 05:17:52','2026-04-12 05:17:52'),(2,2,1,'image','Detective Mouri 1','uploads/images/3ed3da57-272e-495e-b0ec-3f77628a0519.jpg',NULL,'Detective Mouri',0,1,'upload','2026-04-12 05:36:53','2026-04-12 05:36:53'),(3,3,1,'video','Kwento ni Coqs 1','uploads/videos/99bb66d5-72f4-46e5-9322-dfea14115d9b.mp4','uploads/thumbnails/99bb66d5-72f4-46e5-9322-dfea14115d9b-O5vo9fUx.jpg','Kwento ni Coqs',0,1,'upload','2026-04-12 05:56:45','2026-04-12 09:48:09'),(4,4,1,'image','Rizzal 1','uploads/images/83cc2239-9b87-46ff-aeb3-6f6027099384.png',NULL,'Rizzal',0,1,'upload','2026-04-12 06:24:42','2026-04-12 06:24:42'),(5,5,1,'image','Antenna sa Ilong 1','uploads/images/4f4ffa68-6b2b-44d0-ac8a-7b164c41160c.jpg',NULL,'Antenna sa Ilong',0,1,'upload','2026-04-12 06:31:48','2026-04-12 06:31:48'),(6,6,1,'image','SadBai 1','uploads/images/1ca84fd3-51f1-4ff7-8834-844d3a0a24e3.jpg',NULL,'SadBai',0,1,'upload','2026-04-12 06:44:27','2026-04-12 06:44:27'),(7,7,1,'image','Post Malone 1','uploads/images/0a624e13-0e51-45e2-af86-6c84544a3575.jpg',NULL,'Post Malone',0,1,'upload','2026-04-12 06:48:38','2026-04-12 06:48:38'),(8,8,1,'image','Alucard 1','uploads/images/26381b70-7b15-4701-9c58-c890b653afd1.jpg',NULL,'Alucard',0,1,'upload','2026-04-12 07:00:48','2026-04-12 07:00:48'),(9,9,1,'image','Fredrin 1','uploads/images/31ebae46-fdf6-4468-ae7a-3b63186824b0.jpg',NULL,'Fredrin',0,1,'upload','2026-04-12 07:01:12','2026-04-12 07:01:12'),(10,10,1,'image','John weak 1','uploads/images/6ea6df72-502c-48a9-b4e5-8f1c63f75271.jpg',NULL,'John weak',0,1,'upload','2026-04-12 08:00:27','2026-04-12 08:00:27'),(11,11,1,'image','... 1','uploads/images/8540b82e-6a46-41c3-9ad4-d4f1151fbe34.jpg',NULL,'...',0,1,'upload','2026-04-12 10:23:37','2026-04-12 10:23:37'),(12,12,1,'image','Lowkey pain 1','uploads/images/a4c2f0a9-046b-4a38-a3b6-86553db2e32b.jpg',NULL,'Lowkey pain',0,1,'upload','2026-04-12 10:47:10','2026-04-12 10:47:10'),(13,13,1,'video','HBD Coqs 1','uploads/videos/65599a75-212e-4270-9450-2b1394c4e628.mp4','uploads/thumbnails/65599a75-212e-4270-9450-2b1394c4e628-ebKnEEtw.jpg','HBD Coqs',0,1,'upload','2026-04-12 10:49:40','2026-04-12 10:49:40');
/*!40000 ALTER TABLE `media` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `migrations` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_04_12_200000_add_admin_fields_to_users_table',1),(5,'2026_04_12_200100_create_categories_table',1),(6,'2026_04_12_200200_create_tags_table',1),(7,'2026_04_12_200300_create_posts_table',1),(8,'2026_04_12_200400_create_media_table',1),(9,'2026_04_12_200500_create_post_tag_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_reset_tokens`
--

LOCK TABLES `password_reset_tokens` WRITE;
/*!40000 ALTER TABLE `password_reset_tokens` DISABLE KEYS */;
/*!40000 ALTER TABLE `password_reset_tokens` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `post_tag`
--

DROP TABLE IF EXISTS `post_tag`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `post_tag` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `post_id` bigint(20) unsigned NOT NULL,
  `tag_id` bigint(20) unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `post_tag_post_id_tag_id_unique` (`post_id`,`tag_id`),
  KEY `post_tag_tag_id_foreign` (`tag_id`),
  CONSTRAINT `post_tag_post_id_foreign` FOREIGN KEY (`post_id`) REFERENCES `posts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `post_tag_tag_id_foreign` FOREIGN KEY (`tag_id`) REFERENCES `tags` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `post_tag`
--

LOCK TABLES `post_tag` WRITE;
/*!40000 ALTER TABLE `post_tag` DISABLE KEYS */;
INSERT INTO `post_tag` VALUES (8,11,12,'2026-04-12 10:23:37','2026-04-12 10:23:37'),(9,12,12,'2026-04-12 10:47:10','2026-04-12 10:47:10'),(10,13,9,'2026-04-12 10:49:40','2026-04-12 10:49:40');
/*!40000 ALTER TABLE `post_tag` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `posts`
--

DROP TABLE IF EXISTS `posts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `posts` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `author_id` bigint(20) unsigned NOT NULL,
  `category_id` bigint(20) unsigned DEFAULT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `excerpt` varchar(240) DEFAULT NULL,
  `caption` text DEFAULT NULL,
  `body` longtext DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'draft',
  `content_type` varchar(255) NOT NULL DEFAULT 'image',
  `featured_media_url` varchar(255) DEFAULT NULL,
  `featured_media_type` varchar(255) DEFAULT NULL,
  `published_at` timestamp NULL DEFAULT NULL,
  `view_count` int(10) unsigned NOT NULL DEFAULT 0,
  `like_count` int(10) unsigned NOT NULL DEFAULT 0,
  `save_count` int(10) unsigned NOT NULL DEFAULT 0,
  `share_count` int(10) unsigned NOT NULL DEFAULT 0,
  `is_featured` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `posts_slug_unique` (`slug`),
  KEY `posts_author_id_foreign` (`author_id`),
  KEY `posts_category_id_foreign` (`category_id`),
  CONSTRAINT `posts_author_id_foreign` FOREIGN KEY (`author_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  CONSTRAINT `posts_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `posts`
--

LOCK TABLES `posts` WRITE;
/*!40000 ALTER TABLE `posts` DISABLE KEYS */;
INSERT INTO `posts` VALUES (1,1,3,'Nag yoyosi si Coqs','nag-yoyosi-si-coqs',NULL,'Unang yosi ni Coqs',NULL,'published','image','/uploads/images/6c52a964-e71e-4b98-8a75-364ec55413a5.jpg','image','2026-04-12 05:17:52',2,0,0,0,0,'2026-04-12 05:17:52','2026-04-12 08:02:32'),(2,1,3,'Detective Mouri','detective-mouri',NULL,'Detective Mouri nasobrahan sa tulog',NULL,'published','image','uploads/images/3ed3da57-272e-495e-b0ec-3f77628a0519.jpg','image','2026-04-12 05:36:53',0,0,0,0,0,'2026-04-12 05:36:53','2026-04-12 08:02:17'),(3,1,3,'Kwento ni Coqs','kwento-ni-coqs',NULL,'Nag away daw sila',NULL,'published','video','uploads/thumbnails/99bb66d5-72f4-46e5-9322-dfea14115d9b-O5vo9fUx.jpg','video','2026-04-12 05:56:45',2,0,0,0,0,'2026-04-12 05:56:45','2026-04-12 10:38:59'),(4,1,2,'Rizzal','rizzal',NULL,'geh',NULL,'published','image','uploads/images/83cc2239-9b87-46ff-aeb3-6f6027099384.png','image','2026-04-12 06:24:42',2,0,0,0,0,'2026-04-12 06:24:42','2026-04-12 09:54:14'),(5,1,2,'Antenna sa Ilong','antenna-sa-ilong',NULL,NULL,NULL,'published','image','uploads/images/4f4ffa68-6b2b-44d0-ac8a-7b164c41160c.jpg','image','2026-04-12 06:31:48',0,0,0,0,0,'2026-04-12 06:31:48','2026-04-12 06:31:48'),(6,1,3,'SadBai','sadbai',NULL,'Can we go back to the days when our love was strong? tayo ft. paul https://youtu.be/91ECA5NUZ5k?si=o7CUmDN8RG7sTnM0',NULL,'published','image','uploads/images/1ca84fd3-51f1-4ff7-8834-844d3a0a24e3.jpg','image','2026-04-12 06:44:27',2,0,0,0,0,'2026-04-12 06:44:27','2026-04-12 07:05:07'),(7,1,2,'Post Malone','post-malone',NULL,'Psycho ft. paul \r\nhttps://youtu.be/lnIlIjeRALg?si=oJe9tiHhUU_Soamj',NULL,'published','image','uploads/images/0a624e13-0e51-45e2-af86-6c84544a3575.jpg','image','2026-04-12 06:48:38',1,0,0,0,0,'2026-04-12 06:48:38','2026-04-12 08:01:46'),(8,1,2,'Alucard','alucard',NULL,NULL,NULL,'published','image','uploads/images/26381b70-7b15-4701-9c58-c890b653afd1.jpg','image','2026-04-12 07:00:47',0,0,0,0,0,'2026-04-12 07:00:47','2026-04-12 07:00:48'),(9,1,2,'Fredrin','fredrin',NULL,NULL,NULL,'published','image','uploads/images/31ebae46-fdf6-4468-ae7a-3b63186824b0.jpg','image','2026-04-12 07:01:12',1,0,0,0,0,'2026-04-12 07:01:12','2026-04-12 07:02:01'),(10,1,2,'John weak','john-weak',NULL,NULL,NULL,'published','image','uploads/images/6ea6df72-502c-48a9-b4e5-8f1c63f75271.jpg','image','2026-04-12 08:00:27',3,0,0,0,0,'2026-04-12 08:00:27','2026-04-12 09:54:22'),(11,1,2,'...','post-11',NULL,NULL,NULL,'published','image','uploads/images/8540b82e-6a46-41c3-9ad4-d4f1151fbe34.jpg','image','2026-04-12 10:23:37',1,0,0,0,0,'2026-04-12 10:23:37','2026-04-12 10:38:55'),(12,1,2,'Lowkey pain','lowkey-pain',NULL,NULL,NULL,'published','image','uploads/images/a4c2f0a9-046b-4a38-a3b6-86553db2e32b.jpg','image','2026-04-12 10:47:10',0,0,0,0,0,'2026-04-12 10:47:10','2026-04-12 10:47:10'),(13,1,3,'HBD Coqs','hbd-coqs',NULL,NULL,NULL,'published','video','uploads/thumbnails/65599a75-212e-4270-9450-2b1394c4e628-ebKnEEtw.jpg','video','2026-04-12 10:49:40',0,0,0,0,0,'2026-04-12 10:49:40','2026-04-12 10:49:40');
/*!40000 ALTER TABLE `posts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `sessions_user_id_index` (`user_id`),
  KEY `sessions_last_activity_index` (`last_activity`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sessions`
--

LOCK TABLES `sessions` WRITE;
/*!40000 ALTER TABLE `sessions` DISABLE KEYS */;
INSERT INTO `sessions` VALUES ('R9beKSKweZKOaxzBtMOaF5NGgmyuevSxBQoL95oK',NULL,'::1','Mozilla/5.0 (Windows NT; Windows NT 10.0; en-PH) WindowsPowerShell/5.1.26100.7920','YTozOntzOjY6Il90b2tlbiI7czo0MDoiV2NjVDNNM1dNZ1FQMnpIb1Q1TjBTOVVlY3VzZWdOWHRiYnljbDViaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly9sb2NhbGhvc3Qvc2VydmUtZ29kL3B1YmxpYyI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==',1776020481);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `tags`
--

DROP TABLE IF EXISTS `tags`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `tags` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `tags_slug_unique` (`slug`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `tags`
--

LOCK TABLES `tags` WRITE;
/*!40000 ALTER TABLE `tags` DISABLE KEYS */;
INSERT INTO `tags` VALUES (7,'People','people','2026-04-12 10:18:35','2026-04-12 10:18:35'),(8,'Daily','daily','2026-04-12 10:18:35','2026-04-12 10:18:35'),(9,'Moments','moments','2026-04-12 10:18:35','2026-04-12 10:18:35'),(10,'Mood','mood','2026-04-12 10:18:35','2026-04-12 10:18:35'),(11,'Vibes','vibes','2026-04-12 10:18:35','2026-04-12 10:18:35'),(12,'Story','story','2026-04-12 10:18:35','2026-04-12 10:18:35'),(13,'Random','random','2026-04-12 10:18:35','2026-04-12 10:18:35'),(14,'Photos','photos','2026-04-12 10:18:35','2026-04-12 10:18:35'),(15,'Clips','clips','2026-04-12 10:18:35','2026-04-12 10:18:35');
/*!40000 ALTER TABLE `tags` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `users` (
  `id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'editor',
  `bio` text DEFAULT NULL,
  `avatar_url` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`),
  UNIQUE KEY `users_username_unique` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'kenneth','ava','admin@servegod.test',NULL,'$2y$12$q9sRXZcYKREqhyJniMkM3.3ix0rETmjJnLBZzO7aJuu527RAh4Lyu','super_admin','Founder and visual storyteller.',NULL,1,NULL,'2026-04-12 04:56:19','2026-04-12 10:34:04'),(2,'Kenneth','noah','editor@servegod.test',NULL,'$2y$12$NR1Z7cmeH0AvqlyhjpHMeu85cqBXPc/6bhemaGsHqPoShDz.y7uaG','editor','Editorial curator for the daily feed.',NULL,1,NULL,'2026-04-12 04:56:19','2026-04-12 10:33:56');
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-04-13  3:28:19
