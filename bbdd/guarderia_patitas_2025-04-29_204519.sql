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
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_duenos`
--

/*!40000 ALTER TABLE `patitas_duenos` DISABLE KEYS */;
INSERT INTO `patitas_duenos` VALUES (1,'Daniel Alonso Palacios','danielalonsodaw@gmail.com','$2y$10$6.LRtCsYuRL0ppdrlfEgreMIna6nUWBlPCTd0bT7I60ePsBzaCq02',NULL,'2025-04-23 16:40:18'),(2,'Gabriela Pacheco Cepeda','etena@gmail.com','$2b$12$2J4Nf3tFRgl0Nk/6xGfVYOGih4FZ1rnm0bViVdD6p9D6k7FeBu5Hy','+34 729 146 851','2024-05-27 11:50:47'),(3,'Cecilio Yuste Mariño','pinillazacarias@hotmail.com','$2b$12$0LP5pP1pEfLTx2kD6nm6Oe8npAqD4JdRGWy5M0pNpxItxHZ8jZGPq','+34739 64 03 34','2020-11-18 03:47:26'),(4,'Ezequiel Vargas Saavedra','marciacid@gmail.com','$2b$12$shDb8FFZT8B1GL4vnPXuXe/mXjv0l2lsMsBiTy3G5mUXOP/IzE3.K','+34 981 611 128','2022-05-24 00:47:46'),(5,'Sebastian Sevilla Serrano','carriongil@arroyo.com','$2b$12$Gz3NvB3KdX8KrGGSC8J8d.7u11HEZn/cKzS04ztDZW2Y6EBqD7yRe','+34 711 778 996','2023-03-21 18:57:33'),(6,'Emelina Vidal','mblanca@aparicio.es','$2b$12$36HDf6mR9mVXZVmkqpMwFeHh9xpnOSovAAZ7CSZ1DlyDhkPuCJEBq','+34745 880 412','2024-06-25 05:43:28'),(7,'Marta Artigas Tena','lilloadelardo@cabo.com','$2b$12$ORwALlFUTiKk5RztqLg2Eu/JRDE.bOSoP28r1txddTgK7XdpK0abG','+34708 53 16 08','2023-10-15 18:12:40'),(8,'Maribel Amaya','luz19@madrigal-abad.net','$2b$12$03S1d8dNk2P4kVqqR5M78.4gNyqV6nheRhbbH1mwGgxHxfkOXJx0K','+34 725003427','2025-02-14 09:19:00'),(9,'Estrella Fábregas','florenciofalcon@yahoo.com','$2b$12$erSVvAGD8o5nQv8kz8u5Nu7dBSJfMW2CG.XrXxHrkya4kZn4rw69O','+34919 434 394','2021-08-22 09:24:12'),(10,'Reyna Ligia Fuentes Cánovas','oriquelme@yahoo.com','$2b$12$Vwb17Wx4pRbHzIP7Ax87Geuj32NNBt5s/Eb3DrdQHq0p1DhqHdQU.','+34 626 063 820','2023-10-16 03:50:26'),(11,'Saturnino Escalona Codina','hoyoscandela@cantero.es','$2b$12$uTh0A5CUWYMo6FQxS2aQveM7PU0dIhd5NKq8avjAadnFeQaWDcUQ2','+34 903142741','2021-12-23 07:35:24'),(12,'Nazario Azcona Castillo','cruz84@yahoo.com','$2b$12$e7mXyxA7Fuq4YwY/1CQ1DOpvQQaG5x3D5v2dOqON.v0f8E5V2UIWm','+34 922 621 549','2024-11-12 07:54:04'),(13,'Rosalva de Calvet','guiomarvalero@cornejo-perello.com','$2b$12$vpOxl9f7NUiVDAVruo0jR.Jj5krTPmOjhyfD5he11JKzbnvw1x/R.','+34948 609 933','2021-10-18 16:41:13'),(14,'Mar Plana Cáceres','marina96@gmail.com','$2b$12$VR5cTj9qblmkSt4xuKU3YOuCkmt/hkWduYHev7t03z2SSHz56MFai','+34 697 30 22 80','2020-05-05 12:53:52'),(15,'Luís del Méndez','qgiralt@egea.org','$2b$12$T8x53Zl70i0uByGGHAm1GOZ/O.kF8Zmwj7e0PYGf6D7Q2I8CnI1R2','+34 875 36 02 98','2023-01-12 15:26:30'),(16,'Eleuterio del Verdugo','asolano@hotmail.com','$2b$12$Rw5I1HU7eBdkrU9t.JgH..AMqFzoiEwlfHsnxgQ6b9U5r0N1/KqzS','+34 702 475 993','2022-08-02 09:57:59'),(17,'Gloria Lupe Pérez Iglesia','pancho23@sanmiguel.es','$2b$12$Yf1kTvddscQWZj50BG7e4e8pIbt4tE7Eu1Ff5MEbd5y7xj5/jX6Vm','+34 825 892 947','2020-07-18 09:42:50'),(18,'Rebeca Maribel Llobet Baena','rrobles@yahoo.com','$2b$12$8Itddzch3XcZ3UrbkXrEw.PS0k2l9kmZ0WUTXSPC9Z1D9g1rGJgsy','+34 701 696 563','2025-01-27 15:43:22'),(19,'Jaime Pascual Terrón','yacedo@tirado.es','$2b$12$eI6KX6GpFVb7PZzNDy5Z2uK7rXq3qbzAKIDPA9N2y/kQLR9KUgE4C','+34 646 304 107','2020-03-29 20:14:53'),(20,'Aureliano del Sans','solissevero@garrido.es','$2b$12$k8kPd0Iv4yI2x6h1RjBFcuN6bsXLc0cs6f6GiVL2qkLaBhgsBduTK','+34714 53 65 60','2024-03-25 00:54:02'),(21,'Valero Mateo Martínez','ninoariel@gmail.com','$2b$12$8LMXv4kJxqk/YJvZ.gddS.iGe7dW4bmfVdfYtAT72tF0fMi2B/0ya','+34 889 509 383','2023-11-15 04:40:18'),(22,'Martirio del Recio','bayonestrella@valdes.org','$2b$12$5WW4xE7FbN1vODPtzvn/RuOrk4CpaDQReWbgUbPN1hSGB4j9cxkKO','+34 713 93 11 23','2025-02-13 15:19:03'),(23,'Alex Lopez Adadia','nicodemolillo@yahoo.com','$2b$12$LU4OxGUD2P6uDCPKU3XgzO50GVQPNF0ee1bmOYcVi8rLgnOaDgD7q','+34739 74 30 43','2024-12-03 14:45:11'),(24,'Marcos Valero Salinas','marisol47@valles.com','$2b$12$ozTUSlHhrRBpjL/eBJ0CIeNdMdI9PZ3VZ2iAgXg0IXzqj5rf2tVRO','+34 729 91 08 13','2024-09-23 04:35:19'),(25,'Melania Román Cornejo','arnaizpaola@yahoo.com','$2b$12$gGzCpl6w1fflPEUzfsRg..SG1At1AXHIKjAO0CVqIkghz9FBXWAt2','+34 913 99 14 23','2020-10-19 12:23:58'),(26,'Ximena Belda Nuñez','armidaandrade@hotmail.com','$2b$12$MznAFz8pg6s9ZSwqwx3D.uGU3h2OCzqN7B3SrW7zY5gPoc15Hk0rq','+34 737 069 721','2024-06-07 16:49:27'),(27,'Pacífica Armida Orozco Puerta','dmiguel@yahoo.com','$2b$12$02qcxA.ZkS6IQu1OqjPae.GnHoR6Q69lhPdcACVLGr0MYPmd48IFW','+34 741256218','2021-09-10 02:17:49'),(28,'Carlito Arco-Bellido','abellanmelisa@yahoo.com','$2b$12$5vcR1O/9ykAzEY6p5ocNLuHxHVW22Xs7kFnwR65dmbtiMSIruyHH6','+34 709185440','2023-07-30 04:29:48'),(29,'Eusebia de Romeu','geraldocorbacho@hotmail.com','$2b$12$kGzMgDhDsh1bRoNdWpyiBuPVde.yHGV2jXbnC7GrL8Ql3R2fnl.Xu','+34 724948132','2021-10-07 16:42:11'),(30,'Candelaria Dueñas Martinez','deboraferrando@herrera.net','$2b$12$F9DrcnXM4rBD6aX4zi.zwOx6nmS1bDJSByzOvTydXTSfMPNqz3WZe','+34902206736','2022-02-20 08:32:08'),(31,'Aureliano Calleja Pont','carneroeufemia@soria-aranda.org','$2b$12$M20kM46dlT5VzQnB4Sc4ueCh1TzfyGKMJrcnCDmS9kZQfDrb8ik2u','+34927 47 03 82','2021-08-28 01:55:07'),(32,'Ale Olmedo Bayona','pdiego@casas.com','$2b$12$59okm1AHazxz4CJK3z4Tkuk1nqfpe4yGIMyWx5olqW3MWhMcvslPq','+34717 31 66 40','2024-12-14 13:32:33'),(33,'Baldomero Donaire Pla','gmontero@hotmail.com','$2b$12$EESzvfyUTMZcX6PUOnRzceKFrCk5raC1R3ekvfrhq6sr4jMTau6eq','+34 737 759 779','2022-11-20 07:20:41'),(34,'Felipa Martorell Campos','lbaquero@gmail.com','$2b$12$wPNoGplgOU7yxuRfBe1U.OupQyzsF8L7PKIUGhNoS5A9P1XyR3bcm','+34866 422 964','2022-04-23 20:59:18'),(35,'Perlita Hervás','artigaspastor@morcillo.com','$2b$12$nhWMi6QcaZnWcCcyM8fyhO7M9AC5PaIE3utb9LaQMHkG2vFFLxJ1G','+34 972017187','2021-06-04 23:49:01'),(36,'Rosalva Iglesias','hrobledo@gallardo.es','$2b$12$e3bC2BDxKN/f7djUzUI2fOp9BGIm9QU6DDY0z8zMwjJpYjtwHWT2a','+34740 868 219','2024-06-05 08:15:31'),(37,'Susana Cañellas','soriacandelario@yahoo.com','$2b$12$PVXwPdnJ8A/cSxHg0eK15u.CphHBZ6ZMcUWLz0lbHbkrrz84gpqZ2','+34 703220024','2024-12-16 12:15:57'),(38,'Aránzazu Saura','sigfridocalvo@naranjo.com','$2b$12$Qdc9rX48xu1pTCZTC8oaAuJrMkoeY65pjWPC/07QNVrIFlqEfkt1e','+34745081595','2023-12-21 08:52:47'),(39,'Alejo Iborra Casanova','flaviacarbajo@arnaiz.es','$2b$12$VcHj/KrRW99vB5M0tzAcUOo/NSEVa9op9YAgjdl9HffTcD10zPZmS','+34 724 83 83 15','2020-12-28 19:27:25'),(40,'Gregorio Castelló Lozano','nydiavillegas@carreno.com','$2b$12$KbsKkXMPyU8OZ3JAZUgpIex3wScZL9LZ4ezxzY7ltXj3ksGoShNS6','+34801 36 84 93','2024-06-04 18:13:52'),(41,'Melania Aranda Sancho','verdejoisabela@gmail.com','$2b$12$rVcd3hXwDpRDEJvxFQJkoObQANyB2fV4TIvKyOd24DWj8D4O9PyfG','+34979 02 07 78','2020-01-01 22:06:56'),(42,'Félix Jurado Diaz','flavio60@valenzuela.org','$2b$12$hixx7gTAmIGObyFcaN1P4.SgLX7ZmbuPgySmwpkhPplnPEmc5FVLe','+34 718 317 006','2021-06-27 07:20:57'),(43,'Tito Casanovas Abella','caminoitziar@artigas.com','$2b$12$hY88R2q5MdZ9jzz/kaRvO.t3kgfcTxYZKtxvSXYo5guuwbwGHjZmm','+34716647762','2021-09-29 23:34:23'),(44,'Teo Bas Valdés','heraclioperal@alemany.net','$2b$12$Fkf2pxI8OfE3pI9V7kFkdu2axfDB0PH/rUXd4u7Q/uoJw7Xth38Ui','+34680407403','2022-10-27 10:03:06'),(45,'Adrián Gil Viana','gasconheriberto@becerra.es','$2b$12$GjPWkF1DRYGRj6OmTtbgZ.5eHFrSrFfsYr4yWdQzPYgjP3rfShHK.','+34727 438 288','2020-01-20 18:48:26'),(46,'Fidel Bauzà','celia67@gmail.com','$2b$12$W9p5DBglnhI2GWDqPHcePefTLcwbx7PFEpoPt1Epx1n48DQk6F8/C','+34735 617 302','2024-10-10 19:00:29'),(47,'Leandra Gracia Menendez','zbenito@hotmail.com','$2b$12$zEC0km5m/Xr/EiA0x3hP7ORqsVFxevF8p3k9rRg4sGiL1U90Z1fVW','+34743 550 261','2021-08-01 10:30:00'),(48,'Fito Pinilla Nevado','chus60@reguera.es','$2b$12$y/FfPYb7BtpbH7e0Rj3tG.J/Zp0mmyh6VhcyJX7p3fGpKEvw0UTTe','+34 742 120 357','2021-08-29 05:21:40'),(49,'Ainoa del Machado','calixto11@yahoo.com','$2b$12$rZqAC9gqB9rWJlR4Zc4Y8ufxy3Ei8fwhQ2x14RD1Q9yUsHTUvMhdG','+34 687 276 119','2021-09-14 15:01:57'),(50,'Anselmo de Manrique','jose-manuel08@gmail.com','$2b$12$7O61euwx7HyVn0PCC5zY9eRhdPczBMTl6BzMIBD7OEojI2W/sX1Cm','+34734 34 17 59','2020-03-10 03:57:46'),(51,'Adelia Sevillano Arévalo','ecomas@elias-valle.net','$2b$12$3lscHzpC00lHzgm7i5zVCuLTPwKEr7OYlZbXrmEs9XmnLQX6PyGQ6','+34 728710787','2022-01-04 01:55:28');
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
  `propietario_tipo` enum('dueno','cuidador') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `propietario_id` int DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=152 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_mascotas`
--

/*!40000 ALTER TABLE `patitas_mascotas` DISABLE KEYS */;
INSERT INTO `patitas_mascotas` VALUES (1,'Lucy','perro','Golden Retriever',8,'mediano','En casa es como una alfombra, pero le encanta jugar con otros perros.','dueno',1,'2025-04-29 17:50:17'),(2,'Asia','perro','Border collie',6,'mediano','Muy obediente y muy activa, le encanta perseguir palos y coger palos más grandes que ella.','dueno',1,'2025-04-29 17:50:17'),(3,'Aika','perro','Border collie',1,'mediano','Es una cachorra muy juguetona, pero obediente, le encanta jugar con otros perros.','dueno',1,'2025-04-29 17:50:17'),(4,'Max','gato','British Shorthair',9,'grande','Disfruta de largas caminatas.','dueno',2,'2025-02-13 01:22:26'),(5,'Luna','perro','Beagle',1,'mediano','Es extremadamente enérgico.','dueno',3,'2023-12-25 16:37:03'),(6,'Rocky','gato','Siamés',3,'grande','Obediente y protector.','dueno',4,'2024-12-07 19:40:18'),(7,'Simba','perro','Golden Retriever',5,'mediano','Adora estar en compañía.','dueno',5,'2024-07-13 07:12:41'),(8,'Milo','perro','Bulldog',9,'mediano','Le gusta correr mucho.','dueno',6,'2023-10-17 12:33:30'),(9,'Nala','perro','Chihuahua',2,'pequeño','Es curioso y siempre explora.','dueno',7,'2024-06-24 12:47:00'),(10,'Toby','gato','Maine Coon',13,'grande','Muy sociable con otros animales.','dueno',7,'2020-07-05 05:11:17'),(11,'Coco','gato','Siamés',11,'mediano','Prefiere ambientes tranquilos.','dueno',7,'2020-12-20 15:05:55'),(12,'Bella','gato','Maine Coon',13,'mediano','Es algo tímido con desconocidos.','dueno',8,'2022-02-20 00:57:04'),(13,'Zeus','perro','Labrador',14,'pequeño','Es muy cariñoso.','dueno',9,'2025-01-18 21:58:27'),(14,'Lola','gato','British Shorthair',8,'mediano','Es juguetón y activo.','dueno',10,'2025-02-24 12:49:26'),(15,'Lucky','gato','Esfinge',13,'grande','Es tranquilo y obediente.','dueno',11,'2024-10-27 11:55:08'),(16,'Sasha','perro','Chihuahua',10,'pequeño','Suele dormir mucho.','dueno',12,'2025-02-02 06:31:06'),(17,'Tommy','perro','Poodle',13,'pequeño','Es un excelente guardián.','dueno',12,'2020-06-29 09:20:00'),(18,'Maya','perro','Beagle',8,'pequeño','Aprende trucos fácilmente.','dueno',13,'2021-09-24 00:23:52'),(19,'Rex','perro','Pastor Alemán',12,'pequeño','Es muy cariñoso.','dueno',14,'2023-05-30 18:38:09'),(20,'Tigre','perro','Labrador',15,'mediano','Es juguetón y activo.','dueno',14,'2023-02-05 20:50:08'),(21,'Kira','gato','Azul Ruso',14,'grande','Obediente y protector.','dueno',15,'2024-02-12 20:37:24'),(22,'Bruno','perro','Labrador',9,'grande','Disfruta de largas caminatas.','dueno',15,'2023-04-17 12:29:48'),(23,'Daisy','perro','Bulldog',1,'grande','Prefiere ambientes tranquilos.','dueno',16,'2021-04-30 03:01:44'),(24,'Chispa','gato','Persa',14,'pequeño','Muy sociable con otros animales.','dueno',17,'2021-07-05 13:51:22'),(25,'Leo','gato','British Shorthair',1,'grande','Le gusta correr mucho.','dueno',17,'2020-03-04 16:40:32'),(26,'Thor','gato','Siamés',10,'mediano','Es curioso y siempre explora.','dueno',18,'2021-11-01 15:54:40'),(27,'Princesa','gato','Esfinge',8,'pequeño','Es extremadamente enérgico.','dueno',19,'2023-08-07 07:32:52'),(28,'Boby','gato','Maine Coon',6,'mediano','Adora estar en compañía.','dueno',20,'2023-01-09 06:03:25'),(29,'Ron','perro','Labrador',2,'pequeño','Suele dormir mucho.','dueno',21,'2023-08-13 22:30:09'),(30,'Mimi','gato','Persa',12,'pequeño','Es un excelente guardián.','dueno',22,'2021-01-28 08:50:12'),(31,'Sol','gato','Siamés',11,'mediano','Aprende trucos fácilmente.','dueno',22,'2020-12-08 03:47:57'),(32,'Fiona','perro','Bulldog',7,'mediano','Es algo tímido con desconocidos.','dueno',22,'2020-03-04 05:13:18'),(33,'Pipo','perro','Beagle',6,'grande','Es muy cariñoso.','dueno',23,'2024-02-04 16:51:48'),(34,'Chloe','gato','Azul Ruso',5,'pequeño','Disfruta de largas caminatas.','dueno',24,'2020-02-01 03:40:05'),(35,'Pecas','gato','Azul Ruso',5,'pequeño','Es extremadamente enérgico.','dueno',24,'2024-03-27 17:11:55'),(36,'Pelusa','perro','Labrador',6,'grande','Obediente y protector.','dueno',24,'2023-06-09 17:58:25'),(37,'Canela','perro','Labrador',7,'grande','Le gusta correr mucho.','dueno',25,'2021-12-04 04:23:28'),(38,'Blanco','perro','Beagle',7,'pequeño','Prefiere ambientes tranquilos.','dueno',26,'2022-12-19 08:26:25'),(39,'Negra','perro','Beagle',6,'pequeño','Muy sociable con otros animales.','dueno',27,'2023-07-04 01:17:01'),(40,'Manchitas','gato','Azul Ruso',4,'grande','Es juguetón y activo.','dueno',28,'2020-09-20 01:57:42'),(41,'Rocco','perro','Golden Retriever',9,'grande','Es curioso y siempre explora.','dueno',28,'2020-10-04 08:47:44'),(42,'Ares','gato','Maine Coon',12,'grande','Adora estar en compañía.','dueno',28,'2024-09-30 21:56:35'),(43,'Duna','perro','Labrador',5,'grande','Es tranquilo y obediente.','dueno',29,'2024-10-02 02:55:28'),(44,'Gala','gato','Esfinge',6,'pequeño','Suele dormir mucho.','dueno',30,'2023-05-02 01:05:10'),(45,'Bombón','gato','Azul Ruso',1,'pequeño','Es un excelente guardián.','dueno',30,'2022-09-10 04:53:46'),(46,'Nube','gato','Persa',10,'pequeño','Aprende trucos fácilmente.','dueno',31,'2022-11-28 12:50:15'),(47,'Copito','perro','Poodle',12,'mediano','Es muy cariñoso.','dueno',31,'2020-12-06 23:26:21'),(48,'Nieve','gato','Esfinge',9,'mediano','Le gusta correr mucho.','dueno',31,'2020-03-09 05:47:58'),(49,'Trueno','gato','Siamés',7,'pequeño','Es algo tímido con desconocidos.','dueno',32,'2022-08-27 15:41:57'),(50,'Rayito','perro','Pastor Alemán',3,'grande','Muy sociable con otros animales.','dueno',33,'2022-07-10 09:57:48'),(51,'Bimba','perro','Chihuahua',13,'mediano','Obediente y protector.','dueno',33,'2022-01-04 05:13:22'),(52,'Pirata','perro','Chihuahua',9,'mediano','Disfruta de largas caminatas.','dueno',34,'2024-12-10 06:43:29'),(53,'Golfo','gato','Esfinge',1,'pequeño','Prefiere ambientes tranquilos.','dueno',35,'2020-08-10 05:45:09'),(54,'Otto','perro','Labrador',5,'pequeño','Es extremadamente enérgico.','dueno',36,'2021-02-10 16:49:44'),(55,'Zoe','gato','Bengala',14,'pequeño','Es curioso y siempre explora.','dueno',37,'2020-02-23 02:00:04'),(56,'Mushu','gato','British Shorthair',2,'grande','Adora estar en compañía.','dueno',38,'2023-07-04 20:33:48'),(57,'Chester','perro','Beagle',11,'grande','Es juguetón y activo.','dueno',38,'2022-06-16 23:12:23'),(58,'Kovu','perro','Beagle',8,'mediano','Suele dormir mucho.','dueno',39,'2023-08-19 21:20:16'),(59,'Dante','perro','Pastor Alemán',4,'pequeño','Es un excelente guardián.','dueno',39,'2025-04-13 12:08:15'),(60,'Baloo','perro','Golden Retriever',3,'pequeño','Aprende trucos fácilmente.','dueno',40,'2020-10-26 00:06:28'),(61,'Arya','perro','Chihuahua',15,'pequeño','Es algo tímido con desconocidos.','dueno',40,'2024-10-05 11:37:36'),(62,'Koko','gato','British Shorthair',2,'pequeño','Le gusta correr mucho.','dueno',41,'2020-04-21 16:07:18'),(63,'Sombra','gato','Maine Coon',7,'pequeño','Muy sociable con otros animales.','dueno',42,'2022-06-16 03:21:31'),(64,'Bolt','gato','British Shorthair',6,'pequeño','Obediente y protector.','dueno',42,'2023-04-10 10:02:43'),(65,'Cookie','gato','Persa',10,'mediano','Disfruta de largas caminatas.','dueno',43,'2021-07-02 00:42:52'),(66,'Pepe','perro','Beagle',11,'pequeño','Es tranquilo y obediente.','dueno',44,'2024-04-16 00:55:57'),(67,'Kiara','perro','Poodle',4,'mediano','Prefiere ambientes tranquilos.','dueno',44,'2023-06-26 21:56:29'),(68,'Lilo','perro','Chihuahua',12,'grande','Es extremadamente enérgico.','dueno',44,'2023-07-02 07:22:01'),(69,'Stitch','perro','Golden Retriever',3,'mediano','Es muy cariñoso.','dueno',45,'2024-01-12 21:40:23'),(70,'Mocha','gato','Maine Coon',4,'grande','Es curioso y siempre explora.','dueno',46,'2021-02-15 09:21:37'),(71,'Canelo','perro','Poodle',3,'mediano','Adora estar en compañía.','dueno',46,'2022-12-19 12:26:20'),(72,'Violeta','perro','Pastor Alemán',2,'mediano','Es juguetón y activo.','dueno',47,'2021-10-03 03:26:45'),(73,'Pelusa','gato','Siamés',9,'grande','Suele dormir mucho.','dueno',48,'2024-11-18 20:21:40'),(74,'Nina','gato','Azul Ruso',13,'pequeño','Es un excelente guardián.','dueno',48,'2024-04-03 05:25:02'),(75,'Chispa','gato','Esfinge',3,'grande','Aprende trucos fácilmente.','dueno',49,'2020-10-20 13:56:01'),(76,'Jazz','perro','Pastor Alemán',6,'pequeño','Le gusta correr mucho.','dueno',50,'2024-07-13 10:14:14'),(77,'Duque','gato','Siamés',13,'mediano','Es algo tímido con desconocidos.','dueno',51,'2024-06-23 01:44:52'),(78,'Lucy','perro','Golden Retriever',8,'mediano','En casa es como una alfombra, pero le encanta jugar con otros perros.','cuidador',1,'2025-04-29 18:29:54'),(79,'Asia','perro','Border collie',6,'mediano','Muy obediente y muy activa, le encanta perseguir palos y coger palos más grandes que ella.','cuidador',1,'2025-04-29 18:29:54'),(80,'Aika','perro','Border collie',1,'mediano','Es una cachorra muy juguetona, pero obediente, le encanta jugar con otros perros.','cuidador',1,'2025-04-29 18:29:54'),(81,'Max','perro','Pastor Alemán',3,'grande','Es muy cariñoso.','cuidador',2,'2021-11-21 08:02:00'),(82,'Tigre','gato','Persa',6,'grande','Muy sociable con otros animales.','cuidador',3,'2025-04-21 11:10:27'),(83,'Leo','gato','Persa',2,'pequeño','Es algo tímido con desconocidos.','cuidador',4,'2025-03-24 21:19:57'),(84,'Tigre','gato','Bengala',14,'mediano','Le gusta correr mucho.','cuidador',5,'2020-03-17 11:25:01'),(85,'Sombra','gato','Persa',6,'mediano','Es curioso y siempre explora.','cuidador',6,'2023-10-05 20:49:42'),(86,'Negra','perro','Bulldog',10,'mediano','Es algo tímido con desconocidos.','cuidador',7,'2023-11-22 03:20:14'),(87,'Mimi','perro','Poodle',3,'mediano','Es algo tímido con desconocidos.','cuidador',7,'2021-10-12 02:47:08'),(88,'Nala','gato','Siamés',1,'grande','Es juguetón y activo.','cuidador',8,'2020-01-05 14:45:17'),(89,'Rocky','perro','Labrador',4,'mediano','Es tranquilo y obediente.','cuidador',8,'2024-07-05 20:45:39'),(90,'Rocky','perro','Bulldog',6,'grande','Prefiere ambientes tranquilos.','cuidador',9,'2021-05-01 09:48:37'),(91,'Ron','perro','Poodle',7,'grande','Es curioso y siempre explora.','cuidador',10,'2022-08-02 05:30:17'),(92,'Mimi','perro','Golden Retriever',5,'mediano','Prefiere ambientes tranquilos.','cuidador',11,'2025-04-11 17:05:44'),(93,'Pipo','gato','British Shorthair',10,'pequeño','Obediente y protector.','cuidador',11,'2022-07-16 13:26:32'),(94,'Daisy','gato','Maine Coon',5,'pequeño','Disfruta de largas caminatas.','cuidador',12,'2022-11-22 00:24:28'),(95,'Manchitas','perro','Pastor Alemán',4,'pequeño','Le gusta correr mucho.','cuidador',13,'2021-02-04 03:43:12'),(96,'Koko','perro','Chihuahua',6,'mediano','Muy sociable con otros animales.','cuidador',14,'2022-12-10 14:35:11'),(97,'Simba','perro','Poodle',3,'pequeño','Es juguetón y activo.','cuidador',15,'2022-12-28 05:12:53'),(98,'Manchitas','gato','British Shorthair',3,'grande','Disfruta de largas caminatas.','cuidador',15,'2022-02-13 15:31:11'),(99,'Max','gato','Esfinge',8,'pequeño','Le gusta correr mucho.','cuidador',16,'2022-06-11 12:00:53'),(100,'Duna','gato','Azul Ruso',2,'mediano','Muy sociable con otros animales.','cuidador',17,'2023-06-11 16:02:47'),(101,'Milo','perro','Chihuahua',3,'mediano','Le gusta correr mucho.','cuidador',18,'2024-10-20 08:41:55'),(102,'Leo','gato','Persa',6,'pequeño','Es muy cariñoso.','cuidador',19,'2023-02-25 08:26:54'),(103,'Sol','perro','Bulldog',8,'grande','Obediente y protector.','cuidador',20,'2022-07-05 20:32:27'),(104,'Sombra','perro','Poodle',12,'grande','Muy sociable con otros animales.','cuidador',20,'2022-05-13 08:04:10'),(105,'Bella','perro','Pastor Alemán',13,'grande','Es juguetón y activo.','cuidador',21,'2023-09-28 17:19:38'),(106,'Chispa','perro','Beagle',12,'pequeño','Es juguetón y activo.','cuidador',21,'2023-10-01 11:53:40'),(107,'Mushu','gato','British Shorthair',13,'grande','Prefiere ambientes tranquilos.','cuidador',22,'2025-02-18 10:11:36'),(108,'Bruno','perro','Labrador',9,'pequeño','Es juguetón y activo.','cuidador',23,'2023-10-22 18:38:15'),(109,'Maya','gato','Siamés',9,'pequeño','Prefiere ambientes tranquilos.','cuidador',24,'2023-08-11 00:46:15'),(110,'Pipo','perro','Golden Retriever',14,'grande','Obediente y protector.','cuidador',24,'2024-11-13 09:48:29'),(111,'Kovu','gato','Siamés',2,'mediano','Es juguetón y activo.','cuidador',25,'2020-03-10 15:32:02'),(112,'Leo','perro','Chihuahua',1,'mediano','Es algo tímido con desconocidos.','cuidador',26,'2023-06-18 21:21:00'),(113,'Thor','gato','Persa',2,'pequeño','Es algo tímido con desconocidos.','cuidador',26,'2025-04-21 23:27:07'),(114,'Sombra','perro','Bulldog',12,'mediano','Es muy cariñoso.','cuidador',27,'2021-06-27 20:57:30'),(115,'Toby','gato','Persa',6,'pequeño','Disfruta de largas caminatas.','cuidador',27,'2023-07-21 07:22:26'),(116,'Pecas','gato','Bengala',5,'grande','Le gusta correr mucho.','cuidador',28,'2023-10-22 12:01:37'),(117,'Chester','perro','Labrador',1,'grande','Disfruta de largas caminatas.','cuidador',28,'2020-03-05 19:47:27'),(118,'Blanco','perro','Golden Retriever',2,'mediano','Es curioso y siempre explora.','cuidador',29,'2020-06-16 19:00:57'),(119,'Arya','gato','Persa',3,'mediano','Es muy cariñoso.','cuidador',30,'2020-07-03 01:01:29'),(120,'Pirata','perro','Chihuahua',3,'mediano','Le gusta correr mucho.','cuidador',31,'2022-05-10 21:52:34'),(121,'Zoe','gato','British Shorthair',4,'grande','Prefiere ambientes tranquilos.','cuidador',31,'2025-01-29 22:46:19'),(122,'Pipo','perro','Labrador',13,'pequeño','Prefiere ambientes tranquilos.','cuidador',32,'2022-05-21 01:37:01'),(123,'Ron','perro','Chihuahua',7,'grande','Muy sociable con otros animales.','cuidador',33,'2021-03-13 05:56:55'),(124,'Bruno','perro','Chihuahua',1,'grande','Prefiere ambientes tranquilos.','cuidador',34,'2024-06-04 14:21:32'),(125,'Thor','perro','Golden Retriever',14,'mediano','Es muy cariñoso.','cuidador',35,'2024-06-20 12:18:35'),(126,'Milo','gato','Maine Coon',3,'pequeño','Disfruta de largas caminatas.','cuidador',36,'2022-09-20 10:59:26'),(127,'Mushu','perro','Beagle',14,'mediano','Obediente y protector.','cuidador',36,'2020-04-09 15:16:26'),(128,'Pecas','perro','Golden Retriever',2,'mediano','Es juguetón y activo.','cuidador',37,'2021-11-28 13:21:07'),(129,'Canela','gato','British Shorthair',11,'mediano','Muy sociable con otros animales.','cuidador',38,'2022-08-20 05:25:57'),(130,'Kovu','gato','Bengala',5,'grande','Es algo tímido con desconocidos.','cuidador',39,'2023-08-07 04:50:42'),(131,'Mushu','perro','Golden Retriever',4,'mediano','Le gusta correr mucho.','cuidador',40,'2022-05-19 23:45:47'),(132,'Ron','perro','Bulldog',7,'mediano','Le gusta correr mucho.','cuidador',41,'2021-03-08 22:55:58'),(133,'Pelusa','perro','Pastor Alemán',8,'pequeño','Es muy cariñoso.','cuidador',42,'2023-01-09 17:18:37'),(134,'Trueno','perro','Beagle',3,'mediano','Muy sociable con otros animales.','cuidador',42,'2024-10-08 00:57:41'),(135,'Ares','perro','Pastor Alemán',14,'pequeño','Es algo tímido con desconocidos.','cuidador',43,'2020-01-20 20:55:18'),(136,'Otto','perro','Labrador',5,'pequeño','Es curioso y siempre explora.','cuidador',44,'2020-09-02 04:57:22'),(137,'Kira','perro','Beagle',13,'pequeño','Es curioso y siempre explora.','cuidador',45,'2021-12-08 09:23:24'),(138,'Pirata','perro','Labrador',15,'mediano','Es juguetón y activo.','cuidador',45,'2022-07-24 18:25:03'),(139,'Milo','perro','Beagle',8,'mediano','Prefiere ambientes tranquilos.','cuidador',46,'2020-01-13 01:53:42'),(140,'Pipo','perro','Pastor Alemán',2,'grande','Es curioso y siempre explora.','cuidador',46,'2020-02-19 16:36:05'),(141,'Arya','gato','British Shorthair',13,'pequeño','Prefiere ambientes tranquilos.','cuidador',47,'2020-10-07 12:19:49'),(142,'Koko','gato','Siamés',8,'grande','Es tranquilo y obediente.','cuidador',47,'2021-11-21 10:16:46'),(143,'Pelusa','perro','Labrador',3,'pequeño','Es algo tímido con desconocidos.','cuidador',48,'2023-08-17 17:36:30'),(144,'Daisy','perro','Golden Retriever',10,'grande','Es juguetón y activo.','cuidador',48,'2021-10-05 19:40:53'),(145,'Maya','perro','Poodle',11,'mediano','Prefiere ambientes tranquilos.','cuidador',49,'2024-04-17 01:00:38'),(146,'Kovu','perro','Beagle',3,'mediano','Obediente y protector.','cuidador',49,'2022-09-19 09:45:03'),(147,'Ron','perro','Poodle',14,'mediano','Obediente y protector.','cuidador',50,'2022-04-13 06:35:51'),(148,'Boby','perro','Poodle',4,'mediano','Obediente y protector.','cuidador',50,'2023-09-27 04:03:09'),(149,'Kira','perro','Beagle',8,'grande','Prefiere ambientes tranquilos.','cuidador',51,'2022-08-15 17:57:10'),(150,'Chispa','gato','Esfinge',10,'mediano','Prefiere ambientes tranquilos.','cuidador',51,'2022-10-26 14:43:18'),(151,'Rayito','gato','Esfinge',9,'pequeño','Es algo tímido con desconocidos.','cuidador',52,'2021-11-20 06:04:18');
/*!40000 ALTER TABLE `patitas_mascotas` ENABLE KEYS */;

--
-- Table structure for table `patitas_mascotas_imagenes`
--

DROP TABLE IF EXISTS `patitas_mascotas_imagenes`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `patitas_mascotas_imagenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `mascota_id` int NOT NULL,
  `imagen_url` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `mascota_id` (`mascota_id`),
  CONSTRAINT `patitas_mascotas_imagenes_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `patitas_mascotas` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `patitas_mascotas_imagenes`
--

/*!40000 ALTER TABLE `patitas_mascotas_imagenes` DISABLE KEYS */;
/*!40000 ALTER TABLE `patitas_mascotas_imagenes` ENABLE KEYS */;

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

-- Dump completed on 2025-04-29 20:45:26
