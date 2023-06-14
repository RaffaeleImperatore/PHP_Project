-- MySQL dump 10.13  Distrib 8.0.31, for Win64 (x86_64)
--
-- Host: localhost    Database: travel
-- ------------------------------------------------------
-- Server version	8.0.31

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `paesi`
--

DROP TABLE IF EXISTS `paesi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `paesi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nome` varchar(35) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_paesi_nome` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `paesi`
--

LOCK TABLES `paesi` WRITE;
/*!40000 ALTER TABLE `paesi` DISABLE KEYS */;
INSERT INTO `paesi` VALUES (6,'Austria'),(2,'Francia'),(4,'Germania'),(7,'Inghilterra'),(8,'Irlanda'),(1,'Italia'),(5,'Portogallo'),(3,'Spagna'),(9,'Svizzera');
/*!40000 ALTER TABLE `paesi` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `viaggi`
--

DROP TABLE IF EXISTS `viaggi`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `viaggi` (
  `id` int NOT NULL AUTO_INCREMENT,
  `paese_partenza` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `paese_destinazione` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `posti_rimasti` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `paese_partenza` (`paese_partenza`),
  KEY `paese_destinazione` (`paese_destinazione`),
  CONSTRAINT `viaggi_ibfk_1` FOREIGN KEY (`paese_partenza`) REFERENCES `paesi` (`nome`),
  CONSTRAINT `viaggi_ibfk_2` FOREIGN KEY (`paese_destinazione`) REFERENCES `paesi` (`nome`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `viaggi`
--

LOCK TABLES `viaggi` WRITE;
/*!40000 ALTER TABLE `viaggi` DISABLE KEYS */;
INSERT INTO `viaggi` VALUES (1,'Italia','Francia',12),(2,'Italia','Germania',3),(3,'Portogallo','Svizzera',3),(4,'Irlanda','Portogallo',9),(5,'Austria','Germania',7),(6,'Francia','Italia',13),(7,'Francia','Inghilterra',14),(8,'Germania','Italia',20),(9,'Germania','Austria',10),(10,'Germania','Svizzera',12),(11,'Austria','Irlanda',11);
/*!40000 ALTER TABLE `viaggi` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2023-06-14 15:00:17
