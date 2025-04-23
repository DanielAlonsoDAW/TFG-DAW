-- MySQL dump 10.13  Distrib 8.0.33, for Win64 (x86_64)
--
-- Host: localhost    Database: guarderia_patitas
-- ------------------------------------------------------
-- Server version	8.0.41

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
-- Table structure for table `patitas_cuidador_admite`
--

DROP TABLE IF EXISTS `patitas_cuidador_admite`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_cuidador_admite` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cuidador_id` int DEFAULT NULL,
  `tipo_mascota` enum('perro','gato') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tamano` enum('pequeño','mediano','grande') COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cuidador_id` (`cuidador_id`),
  CONSTRAINT `patitas_cuidador_admite_ibfk_1` FOREIGN KEY (`cuidador_id`) REFERENCES `patitas_cuidadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_cuidador_admite`
--

/*!40000 ALTER TABLE `patitas_cuidador_admite` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_cuidador_admite` ENABLE KEYS */;

--
-- Table structure for table `patitas_cuidador_servicios`
--

DROP TABLE IF EXISTS `patitas_cuidador_servicios`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_cuidador_servicios` (
  `id` int NOT NULL AUTO_INCREMENT,
  `cuidador_id` int DEFAULT NULL,
  `servicio` enum('Alojamiento','Visitas a domicilio','Paseos','Guardería de día') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cuidador_id` (`cuidador_id`),
  CONSTRAINT `patitas_cuidador_servicios_ibfk_1` FOREIGN KEY (`cuidador_id`) REFERENCES `patitas_cuidadores` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_cuidador_servicios`
--

/*!40000 ALTER TABLE `patitas_cuidador_servicios` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_cuidador_servicios` ENABLE KEYS */;

--
-- Table structure for table `patitas_cuidadores`
--

DROP TABLE IF EXISTS `patitas_cuidadores`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_cuidadores` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ciudad` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `pais` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci,
  `max_mascotas_dia` int DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=70 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_cuidadores`
--

/*!40000 ALTER TABLE `patitas_cuidadores` DISABLE KEYS */;
INSERT INTO `patitas_cuidadores` VALUES (1,'Daniel Alonso Palacios','danielalonsopalacios@gmail.com','$2y$10$MkpGkpWVlLLIyATzi1I3Wu5a1SjuSvKklqgjZ8QdN3yjCgG13PiGO','686123456','Calle Piqueras 31','Logroño','España','Amante de los animales, dueño de tres perritas encantadoras',2,'2025-04-23 16:41:57'),(2,'Lucía Herrera','lucia.herrera1@madrid.org','$2y$10$xJZqB3vV0qFaVnsgFnsm0O8hD0WdRcDbKJ3u.wCIi/ruMiUusE1wK','600300100','Calle Alcalá 100','Madrid','España','Veterinaria en formación con trato afectivo.',4,'2025-04-23 17:28:18'),(3,'Manuel Torres','manuel.torres2@madrid.org','$2y$10$NM8/S5z1eAAbtdlGpL9mCeSWUfE9qmYemdfjk3KwC.4tmwcvNRLSq','600300101','Gran Vía 32','Madrid','España','Tengo experiencia con animales nerviosos y agresivos.',3,'2025-04-23 17:28:18'),(4,'Raquel García','raquel.garcia3@madrid.org','$2y$10$0yNaVfY4jgJP59fFQCyhP.KU40.Eige6hMTYvBC04qca5ota0GrLm','600300102','Paseo del Prado 14','Madrid','España','Cuidado diurno y paseos por el Retiro.',2,'2025-04-23 17:28:18'),(5,'Álvaro Núñez','alvaro.nunez4@madrid.org','$2y$10$rE8Ooh3BjP1qrMXANlw14OTU8y8SatnhAeppYjBQ/TXzoC9tKdekG','600300103','Calle Atocha 85','Madrid','España','Ofrezco servicios de cuidado en domicilio del dueño.',3,'2025-04-23 17:28:18'),(6,'Marta Roca','marta.roca1@catalunya.cat','$2y$10$2JFMmQs/5OG8aEhFICSsYe1XttGVr0dwGv3OSRvxUQ3qGB34eUyXG','600200100','Carrer Aragó 125','Barcelona','España','Ofrezco cuidado diurno con actividades recreativas.',3,'2025-04-23 17:28:20'),(7,'Joan Ferrer','joan.ferrer2@catalunya.cat','$2y$10$tR/YBLrAdNdP8bVyNjekX.UkaA/kRH5WVln0dBKL20VYdzFAra5/O','600200101','Passeig de Gràcia 50','Barcelona','España','Tengo jardín privado y experiencia con gatos.',2,'2025-04-23 17:28:20'),(8,'Clara Vidal','clara.vidal3@catalunya.cat','$2y$10$G3QJNpdQtLhHfTxddw4Uaebdv2T0Wgt4sPNRtD7PYGAiGwHMS2dnW','600200102','Carrer de Sants 90','Barcelona','España','Mi casa es perfecta para animales pequeños.',4,'2025-04-23 17:28:20'),(9,'Pau Soler','pau.soler4@catalunya.cat','$2y$10$tZMHa2BIJLXwctcuxQEWzOaI8OQ3mWUdzssU44Cd6i4aUWl3kcdge','600200103','Av. Diagonal 200','Barcelona','España','Ofrezco cuidado nocturno con alimentación especializada.',3,'2025-04-23 17:28:20'),(10,'Laura Pérez','laura.perez1@galicia.gal','$2y$10$w48QX5fZyuH1hf6lRGjpyOJ9u0OploPMvihlsbNPuae9/pF6zJsES','600400100','Rúa do Franco 12','Santiago de Compostela','España','Soy estudiante de biología con experiencia cuidando perros.',3,'2025-04-23 17:28:22'),(11,'Pedro Blanco','pedro.blanco2@galicia.gal','$2y$10$xNaixgRW7cblSZuBeyRo/OeBXRuW004ieur4elrEO7.eZy/EoGcrK','600400101','Rúa da Senra 45','Santiago de Compostela','España','Tengo licencia para cuidar animales exóticos.',2,'2025-04-23 17:28:22'),(12,'Isabel Lago','isabel.lago3@galicia.gal','$2y$10$P9qB6ZhUtSdB5/t8hU2E4O0s1pSi2dha5m4Yjd0ut2cglBJrB69YK','600400102','Av. de Lugo 70','Santiago de Compostela','España','He trabajado en clínicas veterinarias.',4,'2025-04-23 17:28:22'),(13,'Marcos Castro','marcos.castro4@galicia.gal','$2y$10$C8LAJPjOJHCusJ1Cc7uK/ea7kvy.FdjpXbUt2/Ipb8XVKuo3/fkhe','600400103','Praza do Obradoiro 1','Santiago de Compostela','España','Cuido perros de tamaño mediano-grande.',3,'2025-04-23 17:28:22'),(14,'Ana Morales','ana.morales1@andalucia.com','$2y$10$LtePcGQ.hYbXDVeuT6glaO7T.OeRs.hFHX9f.h3o4I377yEEuLM3.','600100100','Calle Larios 14','Málaga','España','Cuidadora profesional con experiencia en razas grandes.',3,'2025-04-23 17:28:25'),(15,'Carlos Ruiz','carlos.ruiz2@andalucia.com','$2y$10$qSsYbeXwyTkwVF38NCOnm.JfMBtXvUt3S7WoJzu8S4nL12PuNtvxy','600100101','Paseo del Parque 20','Málaga','España','Voluntario en refugio animal desde 2018.',4,'2025-04-23 17:28:25'),(16,'Sofía Gómez','sofia.gomez3@andalucia.com','$2y$10$f/fzAjmPCGei7SfIYcKdJ.AZtZOAGtqvq8J3m7S7h/rJNyOcxwV0q','600100102','Av. de Andalucía 5','Málaga','España','Estudiante de veterinaria apasionada por los animales.',2,'2025-04-23 17:28:25'),(17,'Javier López','javier.lopez4@andalucia.com','$2y$10$R1KaLxrkC4qRKlHiqF6wgu2zjWIWO2Btzto9McSgY4oaFYnfIkBQS','600100103','Calle Victoria 30','Málaga','España','Tengo experiencia en cuidado de mascotas mayores.',3,'2025-04-23 17:28:25'),(18,'Sara Gil','sara.gil1@aragon.es','$2y$10$TixOmVrDQWNEYhqirtpPb.dCDOAkt2.HHnuRu0vb4/KVVp/lxaFa6','601110001','Calle Alfonso I 23','Zaragoza','España','Gran experiencia con animales pequeños y medianos.',3,'2025-04-23 17:28:29'),(19,'Luis Molina','luis.molina2@aragon.es','$2y$10$Q1zqQtfx2quWQFeSbfwCSOlCIqitatnE1uxsvSZoOACMaRoSSKZQC','601110002','Paseo Independencia 75','Zaragoza','España','Soy adiestrador canino con más de 5 años de experiencia.',4,'2025-04-23 17:28:29'),(20,'Carmen Navarro','carmen.navarro3@aragon.es','$2y$10$RfUJN4GDVpxS8q2A8Zo4UO.CCDIBJUq8nL1vgkCqvIxMcRErccY9G','601110003','Av. Goya 12','Zaragoza','España','Puedo administrar medicamentos a mascotas con necesidades especiales.',2,'2025-04-23 17:28:29'),(21,'Mario Sanz','mario.sanz4@aragon.es','$2y$10$O6fBabasgaeZ8zMomcwYo.FRjwIjdf2fTT2BYCcxX/5kIjufXyl5C','601110004','Calle del Coso 103','Zaragoza','España','Ofrezco cuidados en fines de semana y festivos.',3,'2025-04-23 17:28:29'),(22,'Elena Ruiz','elena.ruiz1@castillalamancha.es','$2y$10$CBOTHFl3y7C6VL2ZFidywe2PCvNrjoSaqR/58SOene.6TrrpDEPy6','601120001','Calle Comercio 14','Toledo','España','Cuidadora muy responsable y amante de los gatos.',3,'2025-04-23 17:28:32'),(23,'Ángel Moreno','angel.moreno2@castillalamancha.es','$2y$10$CBOTHFl3y7C6VL2ZFidywe2PCvNrjoSaqR/58SOene.6TrrpDEPy6','601120002','Calle Real del Arrabal 34','Toledo','España','Me adapto a todo tipo de razas. También ofrezco paseos largos.',4,'2025-04-23 17:28:32'),(24,'Patricia Díaz','patricia.diaz3@castillalamancha.es','$2y$10$a/.XHNLAqCESEiGe2G1vx.D95.LqHW3L2bjch7P86hNl8d6lyCika','601120003','Paseo de la Rosa 67','Toledo','España','Tengo experiencia cuidando animales exóticos.',2,'2025-04-23 17:28:32'),(25,'Rubén Ortega','ruben.ortega4@castillalamancha.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn0Zv/j4kH3k9Hy','601120004','Av. de Europa 22','Toledo','España','Tengo jardín privado y espacios cerrados para juegos.',3,'2025-04-23 17:28:32'),(26,'Cristina Ramos','cristina.ramos1@castillayleon.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn0Zv/j4kH3k9Ht','601130001','Calle Santiago 31','Valladolid','España','Cuidado individualizado para perros senior.',3,'2025-04-23 17:28:34'),(27,'David Pardo','david.pardo2@castillayleon.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn0Zv/j4kH3k7Ht','601130002','Paseo de Zorrilla 50','Valladolid','España','Adiestrador con experiencia en comportamiento animal.',4,'2025-04-23 17:28:34'),(28,'Alicia Montero','alicia.montero3@castillayleon.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn0Zv/j4lH3k7Ht','601130003','Plaza Mayor 8','Valladolid','España','Ofrezco recogida a domicilio dentro de la ciudad.',2,'2025-04-23 17:28:34'),(29,'Héctor Vidal','hector.vidal4@castillayleon.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn0Pv/j4lH3k7Ht','601130004','Calle Labradores 12','Valladolid','España','Tengo amplia experiencia en refugios y casas de acogida.',3,'2025-04-23 17:28:34'),(30,'Sandra Llopis','sandra.llopis1@gva.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF0WXn1Pv/j4lH3k7Ht','601140001','Av. del Puerto 45','Valencia','España','Paseos diarios, juegos y alimentación adaptada.',3,'2025-04-23 17:28:37'),(31,'José Navarro','jose.navarro2@gva.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpZYHj1QF2WXn1Pv/j4lH3k7Ht','601140002','Calle Colón 18','Valencia','España','Dispongo de casa con terraza y patio.',4,'2025-04-23 17:28:37'),(32,'Beatriz Flores','beatriz.flores3@gva.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1QF2WXn1Pv/j4lH3k7Ht','601140003','Calle Ruzafa 99','Valencia','España','Ofrezco cuidado personalizado en festivos y fines de semana.',2,'2025-04-23 17:28:37'),(33,'Toni Alarcón','toni.alarcon4@gva.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1QF3WXn1Pv/j4lH3k7Ht','601140004','Av. Aragón 35','Valencia','España','Experiencia cuidando perros grandes y con ansiedad.',3,'2025-04-23 17:28:37'),(34,'Pilar Hernández','pilar.hernandez1@extremadura.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1QT3WXn1Pv/j4lH3k7Ht','601150001','Calle Sta. Eulalia 11','Mérida','España','Con experiencia en animales con tratamiento veterinario.',3,'2025-04-23 17:28:41'),(35,'Antonio Serrano','antonio.serrano2@extremadura.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1QT3WXn1Pv/j4lH3p7Ht','601150002','Av. de Lusitania 27','Mérida','España','Ofrezco servicio de guardería diurna y nocturna.',4,'2025-04-23 17:28:41'),(36,'Lucía Cordero','lucia.cordero3@extremadura.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1ST3WXn1Pv/j4lH3p7Ht','601150003','Plaza de España 9','Mérida','España','Especialista en gatos y mascotas nerviosas.',2,'2025-04-23 17:28:41'),(37,'Joaquín Bravo','joaquin.bravo4@extremadura.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1ST3WXn5Pv/j4lH3p7Ht','601150004','Calle Trajano 15','Mérida','España','Paseos diarios y tiempo de juego garantizado.',3,'2025-04-23 17:28:41'),(38,'Natalia Suárez','natalia.suarez1@asturias.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1ST3WXn5Pv/j6lH3p7Ht','601160001','Calle Uría 19','Oviedo','España','Técnica en asistencia veterinaria.',3,'2025-04-23 17:28:43'),(39,'Iván García','ivan.garcia2@asturias.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnGcpTYHj1ST3WXn5Pv/j6lH5p7Ht','601160002','Calle Fruela 8','Oviedo','España','Experiencia en cuidados postoperatorios.',4,'2025-04-23 17:28:43'),(40,'Marina Pérez','marina.perez3@asturias.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpTYHj1ST3WXn5Pv/j6lH5p7Ht','601160003','Plaza del Fontán 3','Oviedo','España','Ofrezco alojamiento y cuidado nocturno.',2,'2025-04-23 17:28:43'),(41,'Alberto Menéndez','alberto.menendez4@asturias.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpTYHj5ST3WXn5Pv/j6lH5p7Ht','601160004','Calle Jovellanos 22','Oviedo','España','Ideal para mascotas tranquilas y de interior.',3,'2025-04-23 17:28:43'),(42,'Nerea Etxebarria','nerea.etxebarria1@navarra.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpTYHj5ST3WXn5Pv/j7lH5p7Ht','601170001','Av. de Carlos III 11','Pamplona','España','Cuidaré a tu mascota como si fuera mía.',3,'2025-04-23 17:28:46'),(43,'Mikel Iriarte','mikel.iriarte2@navarra.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpTYHj5ST3WXn6Pv/j7lH5p7Ht','601170002','Paseo Sarasate 4','Pamplona','España','Amplia experiencia en cuidados especiales.',4,'2025-04-23 17:28:46'),(44,'Laia Aguirre','laia.aguirre3@navarra.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpTYHj5ST3WXn6Pv/j7lH8p7Ht','601170003','Calle Estafeta 98','Pamplona','España','Me adapto a las rutinas de cada animal.',2,'2025-04-23 17:28:46'),(45,'Aitor Beloki','aitor.beloki4@navarra.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpGYHj5ST3WnZ6Pv/j7lH8p7Ht','601170004','Calle Mayor 33','Pamplona','España','Especialista en perros grandes.',3,'2025-04-23 17:28:46'),(46,'Julia Rivas','julia.rivas1@cantabria.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpGYHj5ST3WnZ6Pv/j7lH1p7Ht','601190001','Paseo Pereda 17','Santander','España','Me encantan los animales y tengo flexibilidad horaria.',3,'2025-04-23 17:28:48'),(47,'Hugo Peña','hugo.pena2@cantabria.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpGYHj5ST3WnZ6Pv/j7lH1p9Ht','601190002','Calle Vargas 66','Santander','España','Tengo patio y áreas al aire libre.',4,'2025-04-23 17:28:48'),(48,'Andrea Salas','andrea.salas3@cantabria.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpGYHj5ST3WnZ6Pv/j7lH1p9Kt','601190003','Calle Alta 40','Santander','España','Experiencia en guardería de mascotas.',2,'2025-04-23 17:28:48'),(49,'Iván Castaño','ivan.castano4@cantabria.es','$2y$10$X2Mc31dZUouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601190004','Calle San Fernando 100','Santander','España','Atiendo urgencias y noches.',3,'2025-04-23 17:28:48'),(50,'Ane Elorza','ane.elorza1@euskadi.eus','$2y$10$X2Mc31xZUouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601180001','Gran Vía 40','Bilbao','España','Con licencia para cuidado de mascotas con medicación.',3,'2025-04-23 17:28:52'),(51,'Unai Martínez','unai.martinez2@euskadi.eus','$2y$10$X2Mc31zZUouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601180002','Calle Licenciado Poza 21','Bilbao','España','Cuidador con certificación veterinaria.',4,'2025-04-23 17:28:52'),(52,'Irati Zabaleta','irati.zabaleta3@euskadi.eus','$2y$10$X2Mc31zZHouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601180003','Plaza Moyúa 8','Bilbao','España','Paseos largos y atención personalizada.',2,'2025-04-23 17:28:52'),(53,'Iker Aranburu','iker.aranburu4@euskadi.eus','$2y$10$X2Mc31zZHouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601180004','Calle Ercilla 9','Bilbao','España','Tengo zonas habilitadas en casa para juegos seguros.',3,'2025-04-23 17:28:52'),(54,'Celia Marín','celia.marin1@larioja.org','$2y$10$X2Mc31zZYouk4q364J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601200001','Calle Portales 25','Logroño','España','Gran amante de los animales con atención muy cercana.',3,'2025-04-23 17:28:55'),(55,'Daniel Bravo','daniel.bravo2@larioja.org','$2y$10$X2Mc31zZYouk4q354J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601200002','Av. de Colón 55','Logroño','España','Tengo disponibilidad completa para cuidado 24h.',4,'2025-04-23 17:28:55'),(56,'Rosa Fuentes','rosa.fuentes3@larioja.org','$2y$10$X2Mc31zZYouk4q352J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p9Kt','601200003','Calle Chile 14','Logroño','España','Ofrezco recogida a domicilio.',2,'2025-04-23 17:28:55'),(57,'Gonzalo Sáez','gonzalo.saez4@larioja.org','$2y$10$X2Mc31zZYouk4q352J3Z6OV6xnVcpGYHj5ST3XnZ6Pv/j7lH1p7Kt','601200004','Plaza del Mercado 10','Logroño','España','Cuidado profesional en ambiente familiar.',3,'2025-04-23 17:28:55'),(58,'Marta Vidal','marta.vidal1@baleares.es','$2y$10$X2Mc31zZYouk4q352J3Z6OV6xnVcpGYHj5ST1XnZ6Pv/j7lH1p7Kt','601210001','Calle Olmos 33','Palma','España','Cuidado de mascotas en casa y paseos cerca del mar.',3,'2025-04-23 17:28:57'),(59,'Toni Riera','toni.riera2@baleares.es','$2y$10$X3Mc31zZYouk4q352J3Z6OV6xnVcpGYHj5ST1XnZ6Pv/j7lH1p7Kt','601210002','Av. Jaime III 90','Palma','España','Amplio patio para juegos y experiencia con gatos.',4,'2025-04-23 17:28:57'),(60,'Núria Ferrer','nuria.ferrer3@baleares.es','$2y$10$X3Mc31rZYouk4q352J3Z6OV6xnVcpGYHj5ST1XnZ6Pv/j7lH1p7Kt','601210003','Calle Sindicato 12','Palma','España','Me adapto a horarios nocturnos y fines de semana.',2,'2025-04-23 17:28:57'),(61,'Pere Font','pere.font4@baleares.es','$2y$10$X3Mc31rZYouk2q352J3Z6OV6xnVcpGYHj5ST1XnZ6Pv/j7lH1p7Kt','601210004','Paseo del Borne 3','Palma','España','Veterinario jubilado con pasión por el cuidado animal.',3,'2025-04-23 17:28:57'),(62,'Lidia Hernández','lidia.hernandez1@canarias.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1XnZ6Pv/j7lH1p7Kt','601220001','Calle Castillo 77','Santa Cruz de Tenerife','España','Ofrezco cuidados especiales para animales mayores.',3,'2025-04-23 17:28:59'),(63,'Álvaro Ramos','alvaro.ramos2@canarias.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1RnZ6Pv/j7lH1p7Kt','601220002','Av. de Anaga 60','Santa Cruz de Tenerife','España','Cuidado y paseos en entorno costero tranquilo.',4,'2025-04-23 17:28:59'),(64,'Nayra León','nayra.leon3@canarias.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1RnZ6yv/j7lH1p7Kt','601220003','Calle Méndez Núñez 5','Santa Cruz de Tenerife','España','Soy voluntaria en protectora animal.',2,'2025-04-23 17:28:59'),(65,'Cristian Suárez','cristian.suarez4@canarias.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1RnZ6yv/g7lH1p7Kt','601220004','Calle del Pilar 10','Santa Cruz de Tenerife','España','Disponibilidad completa. Recogida y entrega.',3,'2025-04-23 17:28:59'),(66,'Beatriz Navarro','beatriz.navarro1@murcia.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1RnZ6ym/g7lH1p7Kt','601230001','Calle Trapería 44','Murcia','España','Entorno familiar y experiencia con animales pequeños.',3,'2025-04-23 17:29:02'),(67,'Manuel Cano','manuel.cano2@murcia.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST1RnZ2ym/g7lH1p7Kt','601230002','Gran Vía Escultor Salzillo 25','Murcia','España','Cuidado con atención médica si es necesario.',4,'2025-04-23 17:29:02'),(68,'Inés Molina','ines.molina3@murcia.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST5RnZ2ym/g7lH1p7Kt','601230003','Plaza de Santo Domingo 1','Murcia','España','Cuidado individual en mi casa o en la tuya.',2,'2025-04-23 17:29:02'),(69,'Diego Sáez','diego.saez4@murcia.es','$2y$10$X3Mc31rZYouk2q152J3Z6OV6xnVcpGYHj5ST5RnZ3ym/g7lH1p7Kt','601230004','Calle Jabonerías 17','Murcia','España','Ofrezco paseos grupales y actividad física diaria.',3,'2025-04-23 17:29:02');
/*!40000 ALTER TABLE `patitas_cuidadores` ENABLE KEYS */;

--
-- Table structure for table `patitas_duenos`
--

DROP TABLE IF EXISTS `patitas_duenos`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_duenos` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `contrasena` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `telefono` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_duenos`
--

/*!40000 ALTER TABLE `patitas_duenos` DISABLE KEYS */;
INSERT INTO `patitas_duenos` VALUES (1,'Daniel Alonso Palacios','danielalonsodaw@gmail.com','$2y$10$6.LRtCsYuRL0ppdrlfEgreMIna6nUWBlPCTd0bT7I60ePsBzaCq02',NULL,'2025-04-23 16:40:18');
/*!40000 ALTER TABLE `patitas_duenos` ENABLE KEYS */;

--
-- Table structure for table `patitas_facturas`
--

DROP TABLE IF EXISTS `patitas_facturas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_facturas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int DEFAULT NULL,
  `fecha_emision` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `archivo_pdf_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  CONSTRAINT `patitas_facturas_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `patitas_reservas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_facturas`
--

/*!40000 ALTER TABLE `patitas_facturas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_facturas` ENABLE KEYS */;

--
-- Table structure for table `patitas_mascotas`
--

DROP TABLE IF EXISTS `patitas_mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_mascotas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tipo` enum('perro','gato') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `raza` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `edad` int DEFAULT NULL,
  `tamaño` enum('pequeño','mediano','grande') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `observaciones` text COLLATE utf8mb4_general_ci,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `propietario_tipo` enum('dueno','cuidador') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `propietario_id` int DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_mascotas`
--

/*!40000 ALTER TABLE `patitas_mascotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_mascotas` ENABLE KEYS */;

--
-- Table structure for table `patitas_mensajes`
--

DROP TABLE IF EXISTS `patitas_mensajes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_mensajes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `remitente_tipo` enum('dueno','cuidador') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `remitente_id` int DEFAULT NULL,
  `destinatario_tipo` enum('dueno','cuidador') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `destinatario_id` int DEFAULT NULL,
  `mensaje` text COLLATE utf8mb4_general_ci,
  `fecha_envio` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_mensajes`
--

/*!40000 ALTER TABLE `patitas_mensajes` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_mensajes` ENABLE KEYS */;

--
-- Table structure for table `patitas_resenas`
--

DROP TABLE IF EXISTS `patitas_resenas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_resenas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int DEFAULT NULL,
  `duenio_id` int DEFAULT NULL,
  `cuidador_id` int DEFAULT NULL,
  `calificacion` int DEFAULT NULL,
  `comentario` text COLLATE utf8mb4_general_ci,
  `fecha_resena` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `duenio_id` (`duenio_id`),
  KEY `cuidador_id` (`cuidador_id`),
  CONSTRAINT `patitas_resenas_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `patitas_reservas` (`id`),
  CONSTRAINT `patitas_resenas_ibfk_2` FOREIGN KEY (`duenio_id`) REFERENCES `patitas_duenos` (`id`),
  CONSTRAINT `patitas_resenas_ibfk_3` FOREIGN KEY (`cuidador_id`) REFERENCES `patitas_cuidadores` (`id`),
  CONSTRAINT `patitas_resenas_chk_1` CHECK ((`calificacion` between 1 and 5))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_resenas`
--

/*!40000 ALTER TABLE `patitas_resenas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_resenas` ENABLE KEYS */;

--
-- Table structure for table `patitas_reserva_mascotas`
--

DROP TABLE IF EXISTS `patitas_reserva_mascotas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_reserva_mascotas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `reserva_id` int DEFAULT NULL,
  `mascota_id` int DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `reserva_id` (`reserva_id`),
  KEY `mascota_id` (`mascota_id`),
  CONSTRAINT `patitas_reserva_mascotas_ibfk_1` FOREIGN KEY (`reserva_id`) REFERENCES `patitas_reservas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `patitas_reserva_mascotas_ibfk_2` FOREIGN KEY (`mascota_id`) REFERENCES `patitas_mascotas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_reserva_mascotas`
--

/*!40000 ALTER TABLE `patitas_reserva_mascotas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_reserva_mascotas` ENABLE KEYS */;

--
-- Table structure for table `patitas_reservas`
--

DROP TABLE IF EXISTS `patitas_reservas`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_reservas` (
  `id` int NOT NULL AUTO_INCREMENT,
  `duenio_id` int DEFAULT NULL,
  `cuidador_id` int DEFAULT NULL,
  `fecha_inicio` date DEFAULT NULL,
  `fecha_fin` date DEFAULT NULL,
  `servicio` enum('Alojamiento','Visitas a domicilio','Paseos','Guardería de día') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `estado` enum('pendiente','confirmada','rechazada','cancelada','finalizada') COLLATE utf8mb4_general_ci DEFAULT 'pendiente',
  `total` decimal(10,2) DEFAULT NULL,
  `numero_mascotas` int DEFAULT NULL,
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `duenio_id` (`duenio_id`),
  KEY `cuidador_id` (`cuidador_id`),
  CONSTRAINT `patitas_reservas_ibfk_1` FOREIGN KEY (`duenio_id`) REFERENCES `patitas_duenos` (`id`),
  CONSTRAINT `patitas_reservas_ibfk_2` FOREIGN KEY (`cuidador_id`) REFERENCES `patitas_cuidadores` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_reservas`
--

/*!40000 ALTER TABLE `patitas_reservas` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_reservas` ENABLE KEYS */;

--
-- Dumping routines for database 'guarderia_patitas'
--
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-04-23 19:37:19
