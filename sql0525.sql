-- MariaDB dump 10.19  Distrib 10.4.21-MariaDB, for osx10.10 (x86_64)
--
-- Host: localhost    Database: sharehouse_db
-- ------------------------------------------------------
-- Server version	10.4.21-MariaDB

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
-- Table structure for table `admin`
--

DROP TABLE IF EXISTS `admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `admin`
--

LOCK TABLES `admin` WRITE;
/*!40000 ALTER TABLE `admin` DISABLE KEYS */;
INSERT INTO `admin` VALUES (1,'管理者','admin@admin.com','$2y$10$dpANk1k32IVfYWN9MIPhCeUP9ta2vTwGRytT/tvq6KZ/EOE1x7X1y','2022-05-13 04:50:15');
/*!40000 ALTER TABLE `admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `bbs`
--

DROP TABLE IF EXISTS `bbs`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `bbs` (
  `id` int(255) NOT NULL AUTO_INCREMENT,
  `userId` int(255) DEFAULT NULL,
  `houseId` int(255) DEFAULT NULL,
  `name` varchar(50) DEFAULT '名無し',
  `msg` varchar(50) DEFAULT NULL,
  `date` datetime DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=134 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `bbs`
--

LOCK TABLES `bbs` WRITE;
/*!40000 ALTER TABLE `bbs` DISABLE KEYS */;
INSERT INTO `bbs` VALUES (133,2,1,'test2','こんにちは','2022-05-27 18:31:30');
/*!40000 ALTER TABLE `bbs` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facility`
--

DROP TABLE IF EXISTS `facility`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facility` (
  `facilityId` int(11) NOT NULL AUTO_INCREMENT,
  `facilityName` varchar(50) DEFAULT NULL,
  `houseId` int(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`facilityId`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facility`
--

LOCK TABLES `facility` WRITE;
/*!40000 ALTER TABLE `facility` DISABLE KEYS */;
INSERT INTO `facility` VALUES (1,'浴 室',1,'2022-05-13 05:53:37'),(2,'洗濯機',1,'2022-05-13 05:53:45'),(3,'浴 室',2,'2022-05-13 05:53:58'),(4,'洗濯機',2,'2022-05-13 05:54:49'),(5,'浴 室',3,'2022-05-13 05:55:32'),(6,'洗濯機',3,'2022-05-13 05:55:33');
/*!40000 ALTER TABLE `facility` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `facilityTimetable`
--

DROP TABLE IF EXISTS `facilityTimetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `facilityTimetable` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userId` varchar(255) DEFAULT NULL,
  `roomNumber` varchar(255) DEFAULT NULL,
  `facilityId` varchar(255) DEFAULT NULL,
  `timeStart` datetime DEFAULT NULL,
  `timeEnd` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=100 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `facilityTimetable`
--

LOCK TABLES `facilityTimetable` WRITE;
/*!40000 ALTER TABLE `facilityTimetable` DISABLE KEYS */;
INSERT INTO `facilityTimetable` VALUES (99,'1','1','1','2022-05-27 21:00:00','2022-05-27 21:30:00','テストの備考欄への入力です。','2022-05-27 09:28:57');
/*!40000 ALTER TABLE `facilityTimetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `garbageDisposal`
--

DROP TABLE IF EXISTS `garbageDisposal`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `garbageDisposal` (
  `roomNumber` int(255) DEFAULT NULL,
  `day` int(255) DEFAULT NULL,
  `houseId` int(255) DEFAULT NULL,
  `userId` int(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `garbageDisposal`
--

LOCK TABLES `garbageDisposal` WRITE;
/*!40000 ALTER TABLE `garbageDisposal` DISABLE KEYS */;
INSERT INTO `garbageDisposal` VALUES (1,22,2,11,'2022-05-21 17:04:37'),(5,23,2,13,'2022-05-22 05:41:00'),(5,25,2,13,'2022-05-22 05:51:27'),(5,25,2,13,'2022-05-22 05:57:04'),(5,26,2,13,'2022-05-22 05:57:07'),(1,24,1,1,'2022-05-22 06:10:01'),(1,19,1,1,'2022-05-22 06:29:34'),(1,25,1,1,'2022-05-22 06:29:54'),(1,22,1,1,'2022-05-22 06:52:05'),(5,23,1,10,'2022-05-23 11:32:19'),(4,24,2,19,'2022-05-24 11:16:07'),(4,25,2,19,'2022-05-24 13:42:40'),(2,27,1,2,'2022-05-27 09:31:37'),(2,29,1,2,'2022-05-27 09:31:43');
/*!40000 ALTER TABLE `garbageDisposal` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `house`
--

DROP TABLE IF EXISTS `house`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `house` (
  `houseId` int(11) NOT NULL AUTO_INCREMENT,
  `houseName` varchar(255) DEFAULT NULL,
  `zip` varchar(8) DEFAULT NULL,
  `address` text DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`houseId`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `house`
--

LOCK TABLES `house` WRITE;
/*!40000 ALTER TABLE `house` DISABLE KEYS */;
INSERT INTO `house` VALUES (1,'新宿ハウス','160-0022','東京都新宿区新宿○○○','2022-05-13 04:39:41'),(2,'渋谷ハウス','150-0002','東京都渋谷区渋谷○○○','2022-05-13 04:40:04'),(3,'池袋ハウス','170-0011','東京都豊島区池袋本町○○○','2022-05-13 04:40:24');
/*!40000 ALTER TABLE `house` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `houseData`
--

DROP TABLE IF EXISTS `houseData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `houseData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `housename` varchar(255) DEFAULT NULL,
  `roomnumber` varchar(255) DEFAULT NULL,
  `roompass` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `houseData`
--

LOCK TABLES `houseData` WRITE;
/*!40000 ALTER TABLE `houseData` DISABLE KEYS */;
INSERT INTO `houseData` VALUES (1,'新宿ハウス','1','1111','2022-05-05 15:25:27'),(2,'新宿ハウス','2','2222','2022-05-05 15:25:31'),(3,'新宿ハウス','3','3333','2022-05-05 15:27:44'),(4,'新宿ハウス','4','4444','2022-05-05 15:27:53'),(5,'新宿ハウス','5','5555','2022-05-05 15:29:27'),(6,'池袋ハウス','1','1111','2022-05-05 15:30:11'),(7,'池袋ハウス','2','2222','2022-05-05 15:30:40'),(8,'池袋ハウス','3','3333','2022-05-05 15:30:54'),(9,'池袋ハウス','4','4444','2022-05-05 15:31:10'),(10,'池袋ハウス','5','5555','2022-05-05 15:31:22'),(11,'渋谷ハウス','1','1111','2022-05-05 15:31:36'),(12,'渋谷ハウス','2','2222','2022-05-05 15:31:50'),(13,'渋谷ハウス','3','3333','2022-05-05 15:32:01'),(14,'渋谷ハウス','4','4444','2022-05-05 15:32:21'),(15,'渋谷ハウス','5','5555','2022-05-05 15:32:43');
/*!40000 ALTER TABLE `houseData` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `member`
--

DROP TABLE IF EXISTS `member`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `member` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(8) NOT NULL,
  `roompass` varchar(8) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `member`
--

LOCK TABLES `member` WRITE;
/*!40000 ALTER TABLE `member` DISABLE KEYS */;
INSERT INTO `member` VALUES (1,'sasaki','ayana','0000'),(2,'user1','pass1','1111'),(3,'user2','pass2','2222');
/*!40000 ALTER TABLE `member` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `preuser`
--

DROP TABLE IF EXISTS `preuser`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `preuser` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `roomPass` varchar(255) DEFAULT NULL,
  `linkPass` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `preuser`
--

LOCK TABLES `preuser` WRITE;
/*!40000 ALTER TABLE `preuser` DISABLE KEYS */;
INSERT INTO `preuser` VALUES (21,'sasa','sasaki.test.mail.stm@gmail.com','$2y$10$7qvkIRshaitgHUYMgw.DCOg2O6J5I/0sk/2XSChsOZz37Yx.SGG.2','$2y$10$VzVUi9QlCO1qjriH2VG7pe6JT1mR1eHnJe98ycBuG.4GuOqKK.7W.','a722ac8fde006321c556826135a195d715e2b65d60337af9b6b507e8ef21276e','2022-05-25 09:26:00');
/*!40000 ALTER TABLE `preuser` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `room`
--

DROP TABLE IF EXISTS `room`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `room` (
  `roomId` int(11) NOT NULL AUTO_INCREMENT,
  `roomNumber` int(255) DEFAULT NULL,
  `houseId` int(255) DEFAULT NULL,
  `roomPass` varchar(255) DEFAULT NULL,
  `userId` int(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`roomId`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `room`
--

LOCK TABLES `room` WRITE;
/*!40000 ALTER TABLE `room` DISABLE KEYS */;
INSERT INTO `room` VALUES (1,1,1,'$2y$10$PEvpjSRkDl7b2fMDUSoBZOpuZH41q1Vp3wXb9Xk7gPRc3ZeFNjDmi',1,'2022-05-13 05:13:19'),(2,2,1,'$2y$10$aG6PofsiQWCZm439YwWhjOrcAO3LkukGH43P5tVAfvBtrjYQdU0kO',2,'2022-05-13 05:13:29'),(3,3,1,'$2y$10$1zPYuRWv6rxsX7JpNkd3qO60zuZGGho1XpDybbhwD586nuOL.Oe4u',7,'2022-05-13 05:13:29'),(4,4,1,'$2y$10$.3wLp56jt/FzXChCeD5KtuMW2brh8eLLwY1o0f5A5YC4qCfoiu7I.',8,'2022-05-13 05:13:29'),(5,5,1,'$2y$10$aSY4zUsxQPs3oMdR.WhZtOQpGnG4vdUneuxi50g4SjBPqCCYSPCiq',10,'2022-05-13 05:13:29'),(6,1,2,'$2y$10$Lu50k6Y4VF246/DM9fQ01eJ48kZmOo0cUHVMPCKuAwjzv2WpUdOq6',11,'2022-05-13 05:19:12'),(7,2,2,'$2y$10$TRbC.3bUMEpJHiO9r2oJPODHhw.RZHBeEcScEZ5aNTYLq5jTronrW',NULL,'2022-05-13 05:19:12'),(8,3,2,'$2y$10$pEJk9GARONTDOwFAm2J8B.JQdUBPfBIFoLEfO/oYubT8Ow.I09qvO',12,'2022-05-13 05:19:12'),(9,4,2,'$2y$10$wOzsZDqRZWUupHhc78ss8OuBWOaQOXigT4nfrfxMxHHsnKZGxYNLC',19,'2022-05-13 05:19:12'),(10,5,2,'$2y$10$/puxStb5plR60aao6XaegeRQ9MbvCc4U1ByvqVRnPCcMh8NWZguqa',13,'2022-05-13 05:19:14'),(11,1,3,'$2y$10$1i3yyOY5/i0s/Z8wXTcsce8UnL.UkYcYhNsCMHh7xaSw4Ea36tDvS',NULL,'2022-05-13 05:19:53'),(12,2,3,'$2y$10$eLUhWYnc2tKePzxMGpVq4.RSjLVyeGrYs6uhNWx7oKv4//5FNrnuu',20,'2022-05-13 05:19:53'),(13,3,3,'$2y$10$i./aiwlMrNt3bja22R2A6.pWfotKtGLCz/2zSZuaCDKJ/6g4qHIlq',NULL,'2022-05-13 05:19:53'),(14,4,3,'$2y$10$VzVUi9QlCO1qjriH2VG7pe6JT1mR1eHnJe98ycBuG.4GuOqKK.7W.',NULL,'2022-05-13 05:19:53'),(15,5,3,'$2y$10$KQuq1GVxSiZiyQa/8f4YGuDkogKSPNoFFVeZzc/L/P9k1GMm9dI9O',NULL,'2022-05-13 05:19:53');
/*!40000 ALTER TABLE `room` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `sharehouse_timetable`
--

DROP TABLE IF EXISTS `sharehouse_timetable`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `sharehouse_timetable` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `item_name` varchar(255) NOT NULL,
  `time_start` datetime NOT NULL,
  `time_end` datetime NOT NULL,
  `notes` varchar(255) NOT NULL,
  `delete_key` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `sharehouse_timetable`
--

LOCK TABLES `sharehouse_timetable` WRITE;
/*!40000 ALTER TABLE `sharehouse_timetable` DISABLE KEYS */;
INSERT INTO `sharehouse_timetable` VALUES (16,'sasaki','お風呂','2022-05-02 07:30:00','2022-05-02 08:00:00','','aaaa1111'),(18,'sasaki','お風呂','2022-05-02 00:00:00','2022-05-02 00:30:00','','aaaa1111'),(19,'sasaki','お風呂','2022-05-02 03:00:00','2022-05-02 03:30:00','','aaaa1111'),(20,'sasaki','洗濯機','2022-05-02 06:30:00','2022-05-02 07:00:00','','aaaa1111'),(25,'sasaki','洗濯機','2022-05-03 04:00:00','2022-05-03 04:30:00','','aaaa1111'),(26,'sasaki','お風呂','2022-05-03 00:00:00','2022-05-03 00:30:00','','aaaa1111'),(40,'test0','洗濯機','2022-05-03 01:00:00','2022-05-03 01:30:00','','testtest0'),(41,'管理者','定期清掃','2022-05-05 07:00:00','2022-05-05 09:00:00','','admin999'),(42,'管理者','定期清掃','2022-05-05 02:00:00','2022-05-05 04:30:00','定期清掃です。','admin999'),(43,'管理者','お風呂','2022-05-06 00:30:00','2022-05-06 01:00:00','','admin999');
/*!40000 ALTER TABLE `sharehouse_timetable` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable_house1`
--

DROP TABLE IF EXISTS `timetable_house1`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_house1` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `housename` varchar(255) DEFAULT NULL,
  `roomnumber` varchar(255) DEFAULT NULL,
  `unique_sign` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable_house1`
--

LOCK TABLES `timetable_house1` WRITE;
/*!40000 ALTER TABLE `timetable_house1` DISABLE KEYS */;
/*!40000 ALTER TABLE `timetable_house1` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable_house2`
--

DROP TABLE IF EXISTS `timetable_house2`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_house2` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `housename` varchar(255) DEFAULT NULL,
  `roomnumber` varchar(255) DEFAULT NULL,
  `unique_sign` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable_house2`
--

LOCK TABLES `timetable_house2` WRITE;
/*!40000 ALTER TABLE `timetable_house2` DISABLE KEYS */;
INSERT INTO `timetable_house2` VALUES (9,'test8','洗濯機','2022-05-12 00:00:00','2022-05-12 00:30:00','','渋谷ハウス','5','渋谷ハウス5','2022-05-12 06:42:27'),(10,'test8','洗濯機','2022-05-12 03:00:00','2022-05-12 03:30:00','','渋谷ハウス','5','渋谷ハウス5','2022-05-12 10:06:19');
/*!40000 ALTER TABLE `timetable_house2` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `timetable_house3`
--

DROP TABLE IF EXISTS `timetable_house3`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `timetable_house3` (
  `id` int(50) NOT NULL AUTO_INCREMENT,
  `name` varchar(255) DEFAULT NULL,
  `item_name` varchar(255) DEFAULT NULL,
  `time_start` datetime DEFAULT NULL,
  `time_end` datetime DEFAULT NULL,
  `notes` varchar(255) DEFAULT NULL,
  `housename` varchar(255) DEFAULT NULL,
  `roomnumber` varchar(255) DEFAULT NULL,
  `unique_sign` varchar(50) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `timetable_house3`
--

LOCK TABLES `timetable_house3` WRITE;
/*!40000 ALTER TABLE `timetable_house3` DISABLE KEYS */;
/*!40000 ALTER TABLE `timetable_house3` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `user`
--

DROP TABLE IF EXISTS `user`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `user` (
  `userId` int(11) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`userId`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `user`
--

LOCK TABLES `user` WRITE;
/*!40000 ALTER TABLE `user` DISABLE KEYS */;
INSERT INTO `user` VALUES (1,'test1','test1@test.com','$2y$10$Upbu.jfjjczMSrgxNVqoFultXTkXPEYK8006fTdRpm0RaiYgK.Vny','2022-05-13 11:17:36'),(2,'test2','test2@test.com','$2y$10$1Ya/8SNfcBpqCTbo.vvllOJDSSv54ohTTMtgGW1wG.uzZX97E99cC','2022-05-15 06:35:42'),(7,'test3','test3@test.com','$2y$10$GlapZs/ld0c6komydb/xxO8NwAAgnWKm2XyXhSBFO1Zrs3vL/YpMW','2022-05-15 06:48:33'),(8,'test4','test4@test.com','$2y$10$DNEq0NHrfycQDJ8r4HXODO/syTlIWx.aa40g5K5h8eO0jCdXfLDW2','2022-05-15 06:52:15'),(10,'test5','test5@test.com','$2y$10$8UQTNMSxNw.cZwTxAGJiuebDzUA0eJiH0Bk9KIWh.Esz0a5DVUytW','2022-05-19 06:24:58'),(11,'test6','test6@test.com','$2y$10$v6GJRhGh4k8IR8QZ9FgtJOLObMwqhXc9NE/A1XllYTSeOSYj7Ajpq','2022-05-19 06:29:03'),(12,'test7','test7@test.com','$2y$10$Re2Qz1Mhp4Q7W5fOWGRAjuI5VI/Bj8IG98R8iurNYWo9L8ontEbnq','2022-05-22 05:08:20'),(13,'test8','test8@test.com','$2y$10$2fVV1QLqiq74p7HGeVp5rO.bmtFG2oyOB//R0oQ4V9qPDXo..kRZW','2022-05-22 05:37:33'),(19,'sasaki','sasaki.test.mail.stm@gmail.com','$2y$10$p3UdbgA4V.cgfCWlbhLP1OVKJ7vStauR0etqRZ8SQyHoK3K3.2rJm','2022-05-24 11:13:39'),(20,'kiki','snow.milk.sugar.salt.27@gmail.com','$2y$10$aOu2G2Mm6LeAvFaY2EVIN.snOlxFUvq3DSPoHlho83KxeYeQ/esoy','2022-05-24 16:50:09');
/*!40000 ALTER TABLE `user` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `userData`
--

DROP TABLE IF EXISTS `userData`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `userData` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `housename` varchar(50) NOT NULL,
  `roomnumber` varchar(50) NOT NULL,
  `roompass` varchar(50) NOT NULL,
  `unique_sign` varchar(50) NOT NULL,
  `created` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `userData`
--

LOCK TABLES `userData` WRITE;
/*!40000 ALTER TABLE `userData` DISABLE KEYS */;
INSERT INTO `userData` VALUES (1,'sasaki','example@example.com','$2y$10$zSgfbz0igkKvC.7Aqmoz9.F3mJ.4K1BVueC0dfQSYt4Js7Zi7LXZW','新宿ハウス','2','2222','新宿ハウス2','2022-04-30 10:30:49'),(6,'test0','test0@test.com','$2y$10$NiT6dhSkwEQMgqy6RCp5s.0j6N65g6EXCPQhTULpLmBmPyDZ3oIl6','池袋ハウス','1','1111','池袋ハウス1','2022-05-02 14:00:25'),(8,'管理者','admin@admin.com','$2y$10$Iw.1iMkGEfUJr7RIUj.x.eqYcTX2UfTlZnkr6M2N0pLH7BXZR4K3u','','','','','2022-05-05 07:56:47'),(12,'test1','test1@test.com','$2y$10$oF7x67LsConjLEplOcM9.u4Y7JhKa0hMPfY1XlSpTVak18YRn.cmG','渋谷ハウス','3','3333','渋谷ハウス3','2022-05-05 13:27:07'),(15,'test4','test4@test.com','$2y$10$VM2T2i3vkJhxaqjluzoDQusnctC0Tz.LkgOaKnSyA3ep5F3PtFiK2','池袋ハウス','5','5555','池袋ハウス5','2022-05-05 18:23:38'),(16,'test5','test5@test.com','$2y$10$mvEBDtUwtMinJ.qn3mUAm.TcUaRdLgVLXBqco.spgFmmIydxcMwsG','新宿ハウス','1','1111','新宿ハウス1','2022-05-06 04:00:49'),(17,'test6','test6@test.com','$2y$10$/VrjBSeeeigWNFMgtIUoUO6taUwBXtPifGgysrMhYJpXCw4snTbxq','渋谷ハウス','2','2222','渋谷ハウス2','2022-05-06 04:03:17'),(18,'test2','test2@test.com','$2y$10$KHxCTDSFmlGFvt9hhzjLQ.nwvnyPVLf14bp13n44LbPzObCl6eqdq','新宿ハウス','3','3333','新宿ハウス3','2022-05-06 04:06:55'),(24,'test8','test8@test.com','$2y$10$HfQRkoKsI42QGqvWD.XM7eUMSoPoa4TZUpIW0ySglo3X5M9eEVlJy','渋谷ハウス','5','5555','渋谷ハウス5','2022-05-08 21:39:36');
/*!40000 ALTER TABLE `userData` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2022-05-27 18:50:24
