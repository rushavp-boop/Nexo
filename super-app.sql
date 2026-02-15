-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: localhost    Database: super-app
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Current Database: `super-app`
--

CREATE DATABASE /*!32312 IF NOT EXISTS*/ `super-app` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;

USE `super-app`;

--
-- Table structure for table `cache`
--

DROP TABLE IF EXISTS `cache`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `value` mediumtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cache_locks` (
  `key` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `owner` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expiration` int NOT NULL,
  PRIMARY KEY (`key`)
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
-- Table structure for table `car_bookings`
--

DROP TABLE IF EXISTS `car_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `car_bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `car_id` bigint unsigned NOT NULL,
  `days` int NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `car_details` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `car_bookings_user_id_foreign` (`user_id`),
  KEY `car_bookings_car_id_foreign` (`car_id`),
  CONSTRAINT `car_bookings_car_id_foreign` FOREIGN KEY (`car_id`) REFERENCES `cars` (`id`) ON DELETE CASCADE,
  CONSTRAINT `car_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `car_bookings`
--

LOCK TABLES `car_bookings` WRITE;
/*!40000 ALTER TABLE `car_bookings` DISABLE KEYS */;
INSERT INTO `car_bookings` VALUES (1,2,3,1,4200.00,4200.00,'{\"name\": \"Creta\", \"type\": \"SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Creta.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 5}','2026-01-10 23:46:02','2026-01-10 23:46:02'),(2,2,3,1,4200.00,4200.00,'{\"name\": \"Creta\", \"type\": \"SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Creta.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 5}','2026-01-11 05:33:50','2026-01-11 05:33:50'),(3,2,6,1,9000.00,9000.00,'{\"name\": \"Land Cruiser\", \"type\": \"Luxury SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Land Cruiser.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 7}','2026-01-11 05:49:08','2026-01-11 05:49:08'),(4,3,10,1,4800.00,4800.00,'{\"name\": \"Scorpio\", \"type\": \"SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Scorpio.jpg\", \"transmission\": \"Manual\", \"seating_capacity\": 7}','2026-01-11 08:47:06','2026-01-11 08:47:06'),(5,3,2,1,4500.00,4500.00,'{\"name\": \"Compass\", \"type\": \"SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Compass.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 5}','2026-01-11 09:19:28','2026-01-11 09:19:28'),(6,3,2,1,4500.00,4500.00,'{\"name\": \"Compass\", \"type\": \"SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Compass.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 5}','2026-01-11 09:25:39','2026-01-11 09:25:39'),(7,4,6,2,9000.00,18000.00,'{\"name\": \"Land Cruiser\", \"type\": \"Luxury SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Land Cruiser.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 7}','2026-01-11 10:01:08','2026-01-11 10:01:08'),(8,3,1,10,1500.00,15000.00,'{\"name\": \"Alto\", \"type\": \"Hatchback\", \"fuel_type\": \"Petrol\", \"image_url\": \"cars/Alto.jpg\", \"transmission\": \"Manual\", \"seating_capacity\": 4}','2026-01-14 22:18:51','2026-01-14 22:18:51'),(9,5,6,1,9000.00,9000.00,'{\"name\": \"Land Cruiser\", \"type\": \"Luxury SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Land Cruiser.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 7}','2026-02-13 00:08:15','2026-02-13 00:08:15'),(10,5,6,5,9000.00,45000.00,'{\"name\": \"Land Cruiser\", \"type\": \"Luxury SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Land Cruiser.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 7}','2026-02-13 01:38:11','2026-02-13 01:38:11'),(11,5,6,10,9000.00,90000.00,'{\"name\": \"Land Cruiser\", \"type\": \"Luxury SUV\", \"fuel_type\": \"Diesel\", \"image_url\": \"cars/Land Cruiser.jpg\", \"transmission\": \"Automatic\", \"seating_capacity\": 7}','2026-02-13 08:50:44','2026-02-13 08:50:44'),(12,6,5,10,3800.00,38000.00,'{\"name\": \"Jimny\", \"type\": \"Compact SUV\", \"fuel_type\": \"Petrol\", \"image_url\": \"cars/Jimny.jpg\", \"transmission\": \"Manual\", \"seating_capacity\": 4}','2026-02-14 00:24:01','2026-02-14 00:24:01');
/*!40000 ALTER TABLE `car_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `cars`
--

DROP TABLE IF EXISTS `cars`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `cars` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price_per_day` decimal(10,2) NOT NULL,
  `transmission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `seating_capacity` int NOT NULL,
  `fuel_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `features` text COLLATE utf8mb4_unicode_ci,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `cars`
--

LOCK TABLES `cars` WRITE;
/*!40000 ALTER TABLE `cars` DISABLE KEYS */;
INSERT INTO `cars` VALUES (1,'Alto','Hatchback',1500.00,'Manual',4,'Petrol','cars/Alto.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(2,'Compass','SUV',4500.00,'Automatic',5,'Diesel','cars/Compass.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(3,'Creta','SUV',4200.00,'Automatic',5,'Diesel','cars/Creta.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(4,'Fortuner','SUV',5500.00,'Automatic',7,'Diesel','cars/Fortuner.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(5,'Jimny','Compact SUV',3800.00,'Manual',4,'Petrol','cars/Jimny.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(6,'Land Cruiser','Luxury SUV',9000.00,'Automatic',7,'Diesel','cars/Land Cruiser.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(7,'Leaf','Hatchback',3000.00,'Automatic',5,'Electric','cars/Leaf.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(8,'Nexon','Compact SUV',3200.00,'Manual',5,'Diesel','cars/Nexon.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(9,'Rapid','Sedan',3500.00,'Automatic',5,'Petrol','cars/Rapid.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(10,'Scorpio','SUV',4800.00,'Manual',7,'Diesel','cars/Scorpio.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(11,'Sportage','SUV',5000.00,'Automatic',5,'Diesel','cars/Sportage.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(12,'Swift','Hatchback',1800.00,'Manual',5,'Petrol','cars/Swift.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09'),(13,'Touareg','Luxury SUV',6500.00,'Automatic',5,'Diesel','cars/Touareg.jpg',NULL,'2026-01-10 23:11:09','2026-01-10 23:11:09');
/*!40000 ALTER TABLE `cars` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `contacts`
--

DROP TABLE IF EXISTS `contacts`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `contacts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `subject` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `message` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `contacts`
--

LOCK TABLES `contacts` WRITE;
/*!40000 ALTER TABLE `contacts` DISABLE KEYS */;
INSERT INTO `contacts` VALUES (1,'ansh subba','anshsubba04@gmail.com','Loan Letter','I need a loan of a trillion dollars','2026-01-11 09:53:05','2026-01-11 09:53:05'),(2,'Aksharaa Hackathon','aksharaahackathon@s.aksharaaschool.edu.np','gu khau','this is good','2026-02-14 00:08:50','2026-02-14 00:08:50');
/*!40000 ALTER TABLE `contacts` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `failed_jobs`
--

DROP TABLE IF EXISTS `failed_jobs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
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
-- Table structure for table `hotel_bookings`
--

DROP TABLE IF EXISTS `hotel_bookings`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `hotel_bookings` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `hotel_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `location` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nights` int NOT NULL,
  `price_per_night` decimal(10,2) NOT NULL,
  `total_amount` decimal(10,2) NOT NULL,
  `rating` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `amenities` json DEFAULT NULL,
  `image_url` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `hotel_details` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `hotel_bookings_user_id_foreign` (`user_id`),
  CONSTRAINT `hotel_bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `hotel_bookings`
--

LOCK TABLES `hotel_bookings` WRITE;
/*!40000 ALTER TABLE `hotel_bookings` DISABLE KEYS */;
INSERT INTO `hotel_bookings` VALUES (1,2,'Dwarika\'s Hotel','Battisputali',1,12000.00,12000.00,'4.8','[\"Spa\", \"Swimming Pool\", \"Gourmet Restaurant\"]',NULL,'{\"name\": \"Dwarika\'s Hotel\", \"rating\": 4.8, \"location\": \"Battisputali\", \"amenities\": [\"Spa\", \"Swimming Pool\", \"Gourmet Restaurant\"], \"pricePerNight\": 12000}','2026-01-10 23:45:43','2026-01-10 23:45:43'),(2,2,'Dwarika\'s Hotel','Battisputali',1,12000.00,12000.00,'4.7','[\"Spa\", \"Swimming Pool\", \"Gourmet Dining\"]',NULL,'{\"name\": \"Dwarika\'s Hotel\", \"rating\": 4.7, \"location\": \"Battisputali\", \"amenities\": [\"Spa\", \"Swimming Pool\", \"Gourmet Dining\"], \"pricePerNight\": 12000}','2026-01-11 05:33:06','2026-01-11 05:33:06'),(3,3,'Yeti Mountain Home','Namche Bazaar',1,10000.00,10000.00,'4.5','[\"Restaurant\", \"Spa\", \"Bar\", \"WiFi\"]',NULL,'{\"name\": \"Yeti Mountain Home\", \"rating\": 4.5, \"location\": \"Namche Bazaar\", \"amenities\": [\"Restaurant\", \"Spa\", \"Bar\", \"WiFi\"], \"pricePerNight\": 10000}','2026-01-11 08:46:47','2026-01-11 08:46:47'),(4,3,'Temple Tree Resort & Spa','Lakeside',1,9000.00,9000.00,'4.5','[\"Swimming Pool\", \"Spa\", \"Restaurant\", \"Gym\"]',NULL,'{\"name\": \"Temple Tree Resort & Spa\", \"rating\": 4.5, \"location\": \"Lakeside\", \"amenities\": [\"Swimming Pool\", \"Spa\", \"Restaurant\", \"Gym\"], \"pricePerNight\": 9000}','2026-01-11 09:47:16','2026-01-11 09:47:16'),(5,4,'Himalayan View Lodge','Gangapurna Lake',3,6000.00,18000.00,'4.2','[\"Hot Tub\", \"Tour Desk\", \"Mountain Views\"]',NULL,'{\"name\": \"Himalayan View Lodge\", \"rating\": 4.2, \"location\": \"Gangapurna Lake\", \"amenities\": [\"Hot Tub\", \"Tour Desk\", \"Mountain Views\"], \"pricePerNight\": 6000}','2026-01-11 09:59:42','2026-01-11 09:59:42'),(6,3,'Hotel Everest View','Syangboche',10,6000.00,60000.00,'4.2','[\"Panoramic View\", \"Garden\", \"Library\"]',NULL,'{\"name\": \"Hotel Everest View\", \"rating\": 4.2, \"location\": \"Syangboche\", \"amenities\": [\"Panoramic View\", \"Garden\", \"Library\"], \"pricePerNight\": 6000}','2026-01-14 22:22:50','2026-01-14 22:22:50'),(7,5,'Dharma Style Hotel','Ghasa',6,15000.00,90000.00,'5','[\"Outdoor Pool\", \"Spa\", \"Bar\", \"Restaurant\", \"Free WiFi\"]',NULL,'{\"name\": \"Dharma Style Hotel\", \"rating\": 5, \"location\": \"Ghasa\", \"amenities\": [\"Outdoor Pool\", \"Spa\", \"Bar\", \"Restaurant\", \"Free WiFi\"], \"pricePerNight\": 15000}','2026-02-13 00:04:57','2026-02-13 00:04:57'),(8,5,'Yak & Yeti Hotel','Durbar Marg',1,8000.00,8000.00,'4.1','[\"Casino\", \"Gym\", \"Pool\"]',NULL,'{\"name\": \"Yak & Yeti Hotel\", \"rating\": 4.1, \"location\": \"Durbar Marg\", \"amenities\": [\"Casino\", \"Gym\", \"Pool\"], \"pricePerNight\": 8000}','2026-02-13 03:49:25','2026-02-13 03:49:25'),(9,6,'Himalaya Inn','Lo Manthang',5,12000.00,60000.00,'4.5','[\"Free Wi-Fi\", \"Spa\", \"Restaurant\", \"Fitness Center\"]',NULL,'{\"name\": \"Himalaya Inn\", \"rating\": 4.5, \"location\": \"Lo Manthang\", \"amenities\": [\"Free Wi-Fi\", \"Spa\", \"Restaurant\", \"Fitness Center\"], \"pricePerNight\": 12000}','2026-02-14 00:21:32','2026-02-14 00:21:32'),(10,6,'Barahi Jungle Lodge','Madi',1,18000.00,18000.00,'4.2','[\"Safari Tours\", \"Yoga Classes\", \"Library\"]',NULL,'{\"name\": \"Barahi Jungle Lodge\", \"rating\": 4.2, \"location\": \"Madi\", \"amenities\": [\"Safari Tours\", \"Yoga Classes\", \"Library\"], \"pricePerNight\": 18000}','2026-02-14 01:45:45','2026-02-14 01:45:45'),(11,6,'Hyatt Regency Kathmandu','Boudha',3,20500.00,61500.00,'4.5','[\"Swimming Pool\", \"Spa\", \"Fitness Center\"]',NULL,'{\"name\": \"Hyatt Regency Kathmandu\", \"rating\": 4.5, \"location\": \"Boudha\", \"amenities\": [\"Swimming Pool\", \"Spa\", \"Fitness Center\"], \"pricePerNight\": 20500}','2026-02-14 02:26:30','2026-02-14 02:26:30'),(12,6,'Temple Tree Lakeside Pokhara','Lakeside, Pokhara',1,18000.00,18000.00,'4.8','[\"Lakeview Rooms\", \"Swimming Pool\", \"Restaurant\"]',NULL,'{\"name\": \"Temple Tree Lakeside Pokhara\", \"rating\": 4.8, \"location\": \"Lakeside, Pokhara\", \"amenities\": [\"Lakeview Rooms\", \"Swimming Pool\", \"Restaurant\"], \"pricePerNight\": 18000}','2026-02-14 09:16:24','2026-02-14 09:16:24');
/*!40000 ALTER TABLE `hotel_bookings` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `job_batches`
--

DROP TABLE IF EXISTS `job_batches`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `job_batches` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `total_jobs` int NOT NULL,
  `pending_jobs` int NOT NULL,
  `failed_jobs` int NOT NULL,
  `failed_job_ids` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `options` mediumtext COLLATE utf8mb4_unicode_ci,
  `cancelled_at` int DEFAULT NULL,
  `created_at` int NOT NULL,
  `finished_at` int DEFAULT NULL,
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
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `attempts` tinyint unsigned NOT NULL,
  `reserved_at` int unsigned DEFAULT NULL,
  `available_at` int unsigned NOT NULL,
  `created_at` int unsigned NOT NULL,
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
-- Table structure for table `migrations`
--

DROP TABLE IF EXISTS `migrations`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `migrations` (
  `id` int unsigned NOT NULL AUTO_INCREMENT,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `migrations`
--

LOCK TABLES `migrations` WRITE;
/*!40000 ALTER TABLE `migrations` DISABLE KEYS */;
INSERT INTO `migrations` VALUES (1,'0001_01_01_000000_create_users_table',1),(2,'0001_01_01_000001_create_cache_table',1),(3,'0001_01_01_000002_create_jobs_table',1),(4,'2026_01_09_074249_create_contacts_table',1),(5,'2026_01_09_134755_create_cars_table',1),(6,'2026_01_09_160424_create_car_bookings_table',1),(7,'2026_01_09_160428_create_hotel_bookings_table',1),(8,'2026_01_10_033721_add_nexo_paisa_to_users_table',1),(9,'2026_01_10_034508_create_nexo_paisa_transactions_table',1),(10,'2026_01_10_165447_create_password_resets_table',1);
/*!40000 ALTER TABLE `migrations` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `nexo_paisa_transactions`
--

DROP TABLE IF EXISTS `nexo_paisa_transactions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `nexo_paisa_transactions` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `user_id` bigint unsigned NOT NULL,
  `type` enum('load','spend') COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `metadata` json DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `nexo_paisa_transactions_user_id_foreign` (`user_id`),
  CONSTRAINT `nexo_paisa_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `nexo_paisa_transactions`
--

LOCK TABLES `nexo_paisa_transactions` WRITE;
/*!40000 ALTER TABLE `nexo_paisa_transactions` DISABLE KEYS */;
INSERT INTO `nexo_paisa_transactions` VALUES (1,2,'load',100000.00,'Loaded Nexo Paisa from Global IME Bank','{\"bank\": \"Global IME Bank\", \"method\": \"bank_transfer\"}','2026-01-10 23:45:00','2026-01-10 23:45:00'),(2,2,'spend',12000.00,'Hotel booking payment - Dwarika\'s Hotel','{\"nights\": 1, \"location\": \"Battisputali\", \"hotel_name\": \"Dwarika\'s Hotel\", \"booking_type\": \"hotel\"}','2026-01-10 23:45:43','2026-01-10 23:45:43'),(3,2,'spend',4200.00,'Car booking payment - Creta','{\"days\": 1, \"car_id\": 3, \"car_name\": \"Creta\", \"booking_type\": \"car\"}','2026-01-10 23:46:02','2026-01-10 23:46:02'),(4,2,'spend',12000.00,'Hotel booking payment - Dwarika\'s Hotel','{\"nights\": 1, \"location\": \"Battisputali\", \"hotel_name\": \"Dwarika\'s Hotel\", \"booking_type\": \"hotel\"}','2026-01-11 05:33:06','2026-01-11 05:33:06'),(5,2,'spend',4200.00,'Car booking payment - Creta','{\"days\": 1, \"car_id\": 3, \"car_name\": \"Creta\", \"booking_type\": \"car\"}','2026-01-11 05:33:50','2026-01-11 05:33:50'),(6,2,'spend',9000.00,'Car booking payment - Land Cruiser','{\"days\": 1, \"car_id\": 6, \"car_name\": \"Land Cruiser\", \"booking_type\": \"car\"}','2026-01-11 05:49:08','2026-01-11 05:49:08'),(7,3,'load',100000.00,'Loaded Nexo Paisa from Himalayan Bank','{\"bank\": \"Himalayan Bank\", \"method\": \"bank_transfer\"}','2026-01-11 08:14:52','2026-01-11 08:14:52'),(8,3,'spend',10000.00,'Hotel booking payment - Yeti Mountain Home','{\"nights\": 1, \"location\": \"Namche Bazaar\", \"hotel_name\": \"Yeti Mountain Home\", \"booking_type\": \"hotel\"}','2026-01-11 08:46:47','2026-01-11 08:46:47'),(9,3,'spend',4800.00,'Car booking payment - Scorpio','{\"days\": 1, \"car_id\": 10, \"car_name\": \"Scorpio\", \"booking_type\": \"car\"}','2026-01-11 08:47:06','2026-01-11 08:47:06'),(10,3,'spend',4500.00,'Car booking payment - Compass','{\"days\": 1, \"car_id\": 2, \"car_name\": \"Compass\", \"booking_type\": \"car\"}','2026-01-11 09:19:28','2026-01-11 09:19:28'),(11,3,'spend',4500.00,'Car booking payment - Compass','{\"days\": 1, \"car_id\": 2, \"car_name\": \"Compass\", \"booking_type\": \"car\"}','2026-01-11 09:25:39','2026-01-11 09:25:39'),(12,3,'spend',9000.00,'Hotel booking payment - Temple Tree Resort & Spa','{\"nights\": 1, \"location\": \"Lakeside\", \"hotel_name\": \"Temple Tree Resort & Spa\", \"booking_type\": \"hotel\"}','2026-01-11 09:47:16','2026-01-11 09:47:16'),(13,4,'load',100000.00,'Loaded Nexo Paisa from NMB Bank','{\"bank\": \"NMB Bank\", \"method\": \"bank_transfer\"}','2026-01-11 09:55:19','2026-01-11 09:55:19'),(14,4,'spend',18000.00,'Hotel booking payment - Himalayan View Lodge','{\"nights\": 3, \"location\": \"Gangapurna Lake\", \"hotel_name\": \"Himalayan View Lodge\", \"booking_type\": \"hotel\"}','2026-01-11 09:59:42','2026-01-11 09:59:42'),(15,4,'spend',18000.00,'Car booking payment - Land Cruiser','{\"days\": 2, \"car_id\": 6, \"car_name\": \"Land Cruiser\", \"booking_type\": \"car\"}','2026-01-11 10:01:08','2026-01-11 10:01:08'),(16,4,'load',100000.00,'Loaded Nexo Paisa from NMB Bank','{\"bank\": \"NMB Bank\", \"method\": \"bank_transfer\"}','2026-01-11 10:04:51','2026-01-11 10:04:51'),(17,3,'spend',15000.00,'Car booking payment - Alto','{\"days\": 10, \"car_id\": 1, \"car_name\": \"Alto\", \"booking_type\": \"car\"}','2026-01-14 22:18:52','2026-01-14 22:18:52'),(18,3,'load',100000.00,'Loaded Nexo Paisa from Citizens Bank International','{\"bank\": \"Citizens Bank International\", \"method\": \"bank_transfer\"}','2026-01-14 22:22:16','2026-01-14 22:22:16'),(19,3,'spend',60000.00,'Hotel booking payment - Hotel Everest View','{\"nights\": 10, \"location\": \"Syangboche\", \"hotel_name\": \"Hotel Everest View\", \"booking_type\": \"hotel\"}','2026-01-14 22:22:50','2026-01-14 22:22:50'),(20,5,'load',100000.00,'Loaded Nexo Paisa from NIC Asia Bank','{\"bank\": \"NIC Asia Bank\", \"method\": \"bank_transfer\"}','2026-02-13 00:04:17','2026-02-13 00:04:17'),(21,5,'spend',90000.00,'Hotel booking payment - Dharma Style Hotel','{\"nights\": 6, \"location\": \"Ghasa\", \"hotel_name\": \"Dharma Style Hotel\", \"booking_type\": \"hotel\"}','2026-02-13 00:04:57','2026-02-13 00:04:57'),(22,5,'spend',9000.00,'Car booking payment - Land Cruiser','{\"days\": 1, \"car_id\": 6, \"car_name\": \"Land Cruiser\", \"booking_type\": \"car\"}','2026-02-13 00:08:15','2026-02-13 00:08:15'),(23,5,'load',100000.00,'Loaded Nexo Paisa from Prabhu Bank','{\"bank\": \"Prabhu Bank\", \"method\": \"bank_transfer\"}','2026-02-13 01:06:09','2026-02-13 01:06:09'),(24,5,'spend',45000.00,'Car booking payment - Land Cruiser','{\"days\": 5, \"car_id\": 6, \"car_name\": \"Land Cruiser\", \"booking_type\": \"car\"}','2026-02-13 01:38:11','2026-02-13 01:38:11'),(25,5,'spend',8000.00,'Hotel booking payment - Yak & Yeti Hotel','{\"nights\": 1, \"location\": \"Durbar Marg\", \"hotel_name\": \"Yak & Yeti Hotel\", \"booking_type\": \"hotel\"}','2026-02-13 03:49:25','2026-02-13 03:49:25'),(26,5,'load',100000.00,'Loaded Nexo Paisa from Machhapuchchhre Bank','{\"bank\": \"Machhapuchchhre Bank\", \"method\": \"bank_transfer\"}','2026-02-13 08:48:33','2026-02-13 08:48:33'),(27,5,'spend',90000.00,'Car booking payment - Land Cruiser','{\"days\": 10, \"car_id\": 6, \"car_name\": \"Land Cruiser\", \"booking_type\": \"car\"}','2026-02-13 08:50:44','2026-02-13 08:50:44'),(28,6,'load',100000.00,'Loaded Nexo Paisa from NMB Bank','{\"bank\": \"NMB Bank\", \"method\": \"bank_transfer\"}','2026-02-14 00:18:17','2026-02-14 00:18:17'),(29,6,'spend',60000.00,'Hotel booking payment - Himalaya Inn','{\"nights\": 5, \"location\": \"Lo Manthang\", \"hotel_name\": \"Himalaya Inn\", \"booking_type\": \"hotel\"}','2026-02-14 00:21:32','2026-02-14 00:21:32'),(30,6,'spend',38000.00,'Car booking payment - Jimny','{\"days\": 10, \"car_id\": 5, \"car_name\": \"Jimny\", \"booking_type\": \"car\"}','2026-02-14 00:24:01','2026-02-14 00:24:01'),(31,6,'load',100000.00,'Loaded Nexo Paisa from Nabil Bank','{\"bank\": \"Nabil Bank\", \"method\": \"bank_transfer\"}','2026-02-14 01:44:59','2026-02-14 01:44:59'),(32,6,'spend',18000.00,'Hotel booking payment - Barahi Jungle Lodge','{\"nights\": 1, \"location\": \"Madi\", \"hotel_name\": \"Barahi Jungle Lodge\", \"booking_type\": \"hotel\"}','2026-02-14 01:45:45','2026-02-14 01:45:45'),(33,6,'spend',61500.00,'Hotel booking payment - Hyatt Regency Kathmandu','{\"nights\": 3, \"location\": \"Boudha\", \"hotel_name\": \"Hyatt Regency Kathmandu\", \"booking_type\": \"hotel\"}','2026-02-14 02:26:30','2026-02-14 02:26:30'),(34,6,'load',100000.00,'Loaded Nexo Paisa from Century Commercial Bank','{\"bank\": \"Century Commercial Bank\", \"method\": \"bank_transfer\"}','2026-02-14 08:54:11','2026-02-14 08:54:11'),(35,6,'spend',18000.00,'Hotel booking payment - Temple Tree Lakeside Pokhara','{\"nights\": 1, \"location\": \"Lakeside, Pokhara\", \"hotel_name\": \"Temple Tree Lakeside Pokhara\", \"booking_type\": \"hotel\"}','2026-02-14 09:16:24','2026-02-14 09:16:24'),(36,6,'load',100000.00,'Loaded Nexo Paisa from NIC Asia Bank','{\"bank\": \"NIC Asia Bank\", \"method\": \"bank_transfer\"}','2026-02-14 09:20:36','2026-02-14 09:20:36');
/*!40000 ALTER TABLE `nexo_paisa_transactions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `password_reset_tokens`
--

DROP TABLE IF EXISTS `password_reset_tokens`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
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
-- Table structure for table `password_resets`
--

DROP TABLE IF EXISTS `password_resets`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  KEY `password_resets_email_index` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `password_resets`
--

LOCK TABLES `password_resets` WRITE;
/*!40000 ALTER TABLE `password_resets` DISABLE KEYS */;
INSERT INTO `password_resets` VALUES ('anshsubba04@gmail.com','$2y$12$TlmixhjRkyL.1Vcy5HEyjezeuIziktgxRkOmbfLSF1wcxnp0G2jbe','2026-02-13 02:02:20');
/*!40000 ALTER TABLE `password_resets` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sessions`
--

DROP TABLE IF EXISTS `sessions`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `sessions` (
  `id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `user_id` bigint unsigned DEFAULT NULL,
  `ip_address` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_agent` text COLLATE utf8mb4_unicode_ci,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `last_activity` int NOT NULL,
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
INSERT INTO `sessions` VALUES ('yoJamp9TRlxNc3QsWNAJTcNewiIS6IGfljLN8WHr',6,'127.0.0.1','Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/144.0.0.0 Safari/537.36','YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZHdEZFdUNlNIWTRIMnVYdWJ3SzNYRmhnbVRUcVRhandTSlkyaWVpZSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC91c2VyL2Rhc2hib2FyZCI7czo1OiJyb3V0ZSI7czoxNDoidXNlci5kYXNoYm9hcmQiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX1zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aTo2O30=',1771082509);
/*!40000 ALTER TABLE `sessions` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `users`
--

DROP TABLE IF EXISTS `users`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `contact` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'user',
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `nexo_paisa` decimal(15,2) NOT NULL DEFAULT '0.00',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `users`
--

LOCK TABLES `users` WRITE;
/*!40000 ALTER TABLE `users` DISABLE KEYS */;
INSERT INTO `users` VALUES (1,'Admin User','admin@example.com',NULL,'0987654321','$2y$12$9mN5mR9lyqvvOWSD8J2.wuv0QdYLQ.ncJFXHiv0RSvovI90ff3Aie','admin',NULL,'2026-01-10 23:02:49','2026-01-10 23:02:49',0.00),(2,'Regular User','user@example.com',NULL,'1234567890','$2y$12$5vULXLYmbXruEAzh5M5e/eXyZz/ArfN2DhAoeXh1l4LP8GO9IOnjy','user',NULL,'2026-01-10 23:02:50','2026-01-11 05:49:08',58600.00),(3,'rushav','panerurushav@gmail.com',NULL,'9864484540','$2y$12$DJC6n53xArud6NPN1DygLuhqVvSCKY.PUJTQ5eWSsPKmUpcYi6HMe','user',NULL,'2026-01-11 08:11:23','2026-01-14 22:22:50',92200.00),(4,'Ansh','anshsubba04@gmail.com',NULL,'1234567890','$2y$12$pXXHJb0ATwGb3HT8BFkUUesE2CYkmyzaTUR4JwEJtQAisFe2vyJ/W','user',NULL,'2026-01-11 09:54:35','2026-01-11 10:04:51',164000.00),(5,'Aksharaa Hackathon','aksharaahackathon@s.aksharaaschool.edu.np',NULL,'1234567890','$2y$12$hkV8Q1WoPYhuKZoOQGVZ3eWmN9iogtyHK5.HD7nmGYFxp8wf.7HrW','user','HxG2Qho0UqyID33VCRS2e1iCisgIfXkfoEA96Yfm9bEHrtjzBPVfQZThSgrs','2026-02-13 00:00:12','2026-02-13 08:50:44',58000.00),(6,'Avinash Singh','avinash.s@aksharaaschool.edu.np',NULL,'1234567890','$2y$12$K4WDUo5mqxtFEUa7kMtBAuRXOSK7sez2CI1GIolne7GdBfrhmGrne','user','Si4Os37ZtPCZ12zY4AoK9MI4ukOmFIlk3jpNHKnxc0jjRLEZvUpovgoNVblm','2026-02-14 00:12:03','2026-02-14 09:20:36',204500.00);
/*!40000 ALTER TABLE `users` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Dumping routines for database 'super-app'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-02-14 21:46:26
