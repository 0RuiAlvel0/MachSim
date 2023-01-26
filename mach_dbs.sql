-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.32-0buntu0.20.04.1 - (Ubuntu)
-- Server OS:                    Linux
-- HeidiSQL Version:             12.3.0.6589
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Dumping database structure for zodiak_mach
CREATE DATABASE IF NOT EXISTS `zodiak_mach` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `zodiak_mach`;

-- Dumping structure for table zodiak_mach.machine_info
CREATE TABLE IF NOT EXISTS `machine_info` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` text NOT NULL,
  `is_running` tinyint DEFAULT NULL,
  `has_fault_trigger` int DEFAULT NULL,
  `last_start_time` timestamp NULL DEFAULT NULL,
  `last_stop_time` timestamp NULL DEFAULT NULL,
  `start_duration` int DEFAULT NULL COMMENT 'In seconds value from 0 to normal range',
  `stop_duration` int DEFAULT NULL COMMENT 'In seconds time from 0 to normal range',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table zodiak_mach.machine_info: ~1 rows (approximately)
INSERT INTO `machine_info` (`id`, `name`, `is_running`, `has_fault_trigger`, `last_start_time`, `last_stop_time`, `start_duration`, `stop_duration`) VALUES
	(1, 'Machine 1', 1, 0, NULL, NULL, NULL, NULL);

-- Dumping structure for table zodiak_mach.machine_parameters
CREATE TABLE IF NOT EXISTS `machine_parameters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `machine_id` text NOT NULL,
  `name` text NOT NULL,
  `setpoint` float DEFAULT NULL,
  `normal_min` float NOT NULL DEFAULT '0',
  `normal_max` float NOT NULL DEFAULT '0',
  `abnormal_min` float NOT NULL DEFAULT '0',
  `abnormal_max` float NOT NULL DEFAULT '0',
  `relation_to_id` int NOT NULL DEFAULT '0',
  `relation_type` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT 'what direction to follow related id "follows" or "reverse"',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table zodiak_mach.machine_parameters: ~2 rows (approximately)
INSERT INTO `machine_parameters` (`id`, `machine_id`, `name`, `setpoint`, `normal_min`, `normal_max`, `abnormal_min`, `abnormal_max`, `relation_to_id`, `relation_type`) VALUES
	(1, '1', 'Voltage', 300.1, 0, 620, 445, 625, 2, 'follows'),
	(2, '1', 'Current', 100, 0, 200, -1, 35.5, 0, '0');


-- Dumping database structure for zodiak_mach_data
CREATE DATABASE IF NOT EXISTS `zodiak_mach_data` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `zodiak_mach_data`;

-- Dumping structure for table zodiak_mach_data.sensor_data
CREATE TABLE IF NOT EXISTS `sensor_data` (
  `time` datetime DEFAULT NULL,
  `location_id` int DEFAULT NULL,
  `sensor_id` int DEFAULT NULL,
  `value` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table zodiak_mach_data.sensor_data: ~2 rows (approximately)
INSERT INTO `sensor_data` (`time`, `location_id`, `sensor_id`, `value`) VALUES
	('2023-01-26 05:30:01', 1, 1, 0),
	('2023-01-26 05:30:01', 1, 2, 0),
	('2023-01-26 05:35:01', 1, 1, 290.403),
	('2023-01-26 05:35:01', 1, 2, 99.9592);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
