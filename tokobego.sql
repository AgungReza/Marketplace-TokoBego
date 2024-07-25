-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               8.0.30 - MySQL Community Server - GPL
-- Server OS:                    Win64
-- HeidiSQL Version:             12.1.0.6537
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

-- Dumping structure for table tokobego.orders
CREATE TABLE IF NOT EXISTS `orders` (
  `id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `order_date` datetime NOT NULL,
  `status` enum('pending','completed','canceled') NOT NULL,
  `shipping_address` text NOT NULL,
  `city` varchar(255) NOT NULL,
  `postal_code` varchar(20) NOT NULL,
  `country` varchar(255) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tokobego.orders: ~0 rows (approximately)
INSERT INTO `orders` (`id`, `user_id`, `order_date`, `status`, `shipping_address`, `city`, `postal_code`, `country`) VALUES
	(1, 2, '2024-07-25 20:54:13', 'completed', 'Sleman', 'Yogyakarta', '99999', 'Indonesia'),
	(2, 2, '2024-07-25 21:37:57', 'completed', 'Jl. Bibis, Mejing Kidul, RT.03/RW.09, Mejing Kidul, Ambarketawang, Kec. Gamping, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55294', 'Kab. Sleman', '55294', 'Indonesia'),
	(3, 3, '2024-07-25 22:20:19', 'completed', 'sleman', 'Kab. Sleman', '55294', 'Indonesia'),
	(4, 3, '2024-07-25 22:20:56', 'pending', 'Jl. Bibis, Mejing Kidul, RT.03/RW.09, Mejing Kidul, Ambarketawang, Kec. Gamping, Kabupaten Sleman, Daerah Istimewa Yogyakarta 55294', 'Kab. Sleman', '55294', 'Indonesia');

-- Dumping structure for table tokobego.order_details
CREATE TABLE IF NOT EXISTS `order_details` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `product_id` int NOT NULL,
  `quantity` int NOT NULL,
  `price` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `product_id` (`product_id`),
  CONSTRAINT `order_details_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`),
  CONSTRAINT `order_details_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tokobego.order_details: ~0 rows (approximately)
INSERT INTO `order_details` (`id`, `order_id`, `product_id`, `quantity`, `price`) VALUES
	(1, 1, 2, 1, 100000.00),
	(2, 2, 2, 1, 100000.00),
	(3, 3, 3, 1, 500000.00),
	(4, 4, 1, 1, 200000.00);

-- Dumping structure for table tokobego.products
CREATE TABLE IF NOT EXISTS `products` (
  `id` int NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tokobego.products: ~0 rows (approximately)
INSERT INTO `products` (`id`, `name`, `description`, `price`, `image`) VALUES
	(1, 'Medium Excavator - Catterpilar 320D', 'Caterpillar 320D adalah excavator hidrolik yang dirancang untuk efisiensi tinggi dan kinerja optimal di berbagai kondisi kerja. Dibekali dengan teknologi canggih dan komponen berkualitas, 320D menawarkan keandalan dan produktivitas yang luar biasa untuk proyek konstruksi, pertambangan, dan berbagai aplikasi industri lainnya.', 200000.00, './uploads/94e1a2a17a4dcc2ccbc71e8d479bdcaf.jpeg'),
	(2, 'Small Excavator - Caterpillar 318D2 L', 'Merupakan excavator kecil bisa digunakan untuk hal hal ringangan dengan daya kerja 84Kw, Bobot kerja 17800kg, kedalaman gali 6090mm', 100000.00, './uploads/337c580567b7a5c301a8d860919ce5ae.jpeg'),
	(3, 'Bulldozer - Komatsu D375A-6R', 'Komatsu D375A-6R digunakan di area pertambangan memiliki produktivitas kerja tinggi dan hemat bahan bakar. Dozer ini bertenaga sebesar 610 HP, sangat handal dalam pekerjaan ripping dan dozing.', 500000.00, './uploads/3f933fbce415b4fd47bbfa0a8438bc59.jpg');

-- Dumping structure for table tokobego.transactions
CREATE TABLE IF NOT EXISTS `transactions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `order_id` int NOT NULL,
  `transaction_date` datetime NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('pending','completed','failed') NOT NULL,
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tokobego.transactions: ~0 rows (approximately)

-- Dumping structure for table tokobego.users
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` int DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table tokobego.users: ~0 rows (approximately)
INSERT INTO `users` (`id`, `username`, `password`, `email`, `role`) VALUES
	(1, 'Caroline', '$2y$10$/QfQ5N.BBmPnCcWw9yWekuZXDEvSCfc6kg1q6ZSV5YuclDpdP/PQm', 'lina@gmail.com', 1),
	(2, 'Fahmyta', '$2y$10$MDc6Ar/1W0SLMIW93ZyQXu4r.CiP7vkM3NtyQcTI9sa5kFHbM/d1a', 'myta@gmail.com', 2),
	(3, 'Agung Reza', '$2y$10$QuEwGHl7ODxUV/a8T6sBouexMxHpr2UTJZhvDMIjiox3ob18Bc/y6', 'agung@gmail.com', 2);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
