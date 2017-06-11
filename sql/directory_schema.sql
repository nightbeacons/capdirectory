-- MySQL dump 10.14  Distrib 5.5.52-MariaDB, for Linux (x86_64)
--
-- Host: localhost    Database: northshore
-- ------------------------------------------------------
-- Server version	5.5.52-MariaDB

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `directory`
--

DROP TABLE IF EXISTS `directory`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8 */;
CREATE TABLE `directory` (
  `name` varchar(50) DEFAULT NULL,
  `type` enum('S','C','A') DEFAULT NULL,
  `capid` int(11) DEFAULT NULL,
  `gender` varchar(1) DEFAULT NULL,
  `rank` varchar(10) DEFAULT NULL,
  `rankDate` date DEFAULT NULL,
  `joined` date DEFAULT NULL,
  `renew` date DEFAULT NULL,
  `street` varchar(100) DEFAULT NULL,
  `city` varchar(25) DEFAULT NULL,
  `zip` varchar(12) DEFAULT NULL,
  `lat` float DEFAULT NULL,
  `lon` float DEFAULT NULL,
  `phone1` varchar(20) DEFAULT NULL,
  `phone1Type` varchar(3) DEFAULT NULL,
  `phone2` varchar(20) DEFAULT NULL,
  `phone2Type` varchar(3) DEFAULT NULL,
  `FBI` varchar(30) DEFAULT NULL,
  `DOB` date DEFAULT NULL,
  `password` varchar(60) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `parentemail1` varchar(50) DEFAULT NULL,
  `parentemail2` varchar(50) DEFAULT NULL,
  `comments` blob,
  `cellprovider` varchar(1) DEFAULT NULL,
  `alertlist` tinyint(1) DEFAULT NULL,
  `id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `isAdmin` tinyint(1) NOT NULL DEFAULT '0',
  `active` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`id`),
  UNIQUE KEY `capid` (`capid`)
) ENGINE=MyISAM AUTO_INCREMENT=152 DEFAULT CHARSET=latin1;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `directory`
--

LOCK TABLES `directory` WRITE;
/*!40000 ALTER TABLE `directory` DISABLE KEYS */;
/*!40000 ALTER TABLE `directory` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2017-06-11 21:12:19
