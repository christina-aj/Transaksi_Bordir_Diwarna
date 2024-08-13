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


-- Dumping database structure for inventaris_web
CREATE DATABASE IF NOT EXISTS `inventaris_web` /*!40100 DEFAULT CHARACTER SET armscii8 COLLATE armscii8_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `inventaris_web`;

-- Dumping structure for table inventaris_web.consumable
CREATE TABLE IF NOT EXISTS `consumable` (
  `id` int NOT NULL AUTO_INCREMENT,
  `code` int NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `warna` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jenis` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jumlah` int NOT NULL,
  `harga` int NOT NULL,
  `total` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.consumable: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.item
CREATE TABLE IF NOT EXISTS `item` (
  `items_id` int NOT NULL AUTO_INCREMENT,
  `kategori` enum('consumable','non-consumable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `pembelian_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_belanja` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`items_id`),
  UNIQUE KEY `kategori` (`kategori`),
  UNIQUE KEY `pembelian_id` (`pembelian_id`),
  UNIQUE KEY `item_id` (`pembelian_id`),
  CONSTRAINT `item_ibfk_1` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`pembelian_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.item: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.laporan_produksi
CREATE TABLE IF NOT EXISTS `laporan_produksi` (
  `laporan_id` int NOT NULL AUTO_INCREMENT,
  `mesin_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `vs` int NOT NULL,
  `stitch` int NOT NULL,
  `kuantitas` int NOT NULL,
  `bs` int NOT NULL,
  PRIMARY KEY (`laporan_id`),
  UNIQUE KEY `mesin_id` (`mesin_id`,`shift_id`),
  KEY `shift_id` (`shift_id`),
  CONSTRAINT `laporan_produksi_ibfk_1` FOREIGN KEY (`shift_id`) REFERENCES `shift` (`shift_id`),
  CONSTRAINT `laporan_produksi_ibfk_2` FOREIGN KEY (`mesin_id`) REFERENCES `mesin` (`mesin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.laporan_produksi: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.mesin
CREATE TABLE IF NOT EXISTS `mesin` (
  `mesin_id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`mesin_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.mesin: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.migration
CREATE TABLE IF NOT EXISTS `migration` (
  `version` varchar(180) NOT NULL,
  `apply_time` int DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventaris_web.migration: ~1 rows (approximately)
INSERT INTO `migration` (`version`, `apply_time`) VALUES
	('m000000_000000_base', 1723548587);

-- Dumping structure for table inventaris_web.non_consumable
CREATE TABLE IF NOT EXISTS `non_consumable` (
  `nonconsumable_id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `stok` int NOT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `kondisi` enum('baru','siap','kosong') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`nonconsumable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.non_consumable: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.pembelian
CREATE TABLE IF NOT EXISTS `pembelian` (
  `pembelian_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `tempat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kategori` enum('consumable','non-consumable') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `total_biaya` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`pembelian_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.pembelian: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.penggunaan
CREATE TABLE IF NOT EXISTS `penggunaan` (
  `penggunaan_id` int NOT NULL AUTO_INCREMENT,
  `nonconsumable_id` int NOT NULL,
  `jumlah_digunakan` int NOT NULL,
  `tanggal_digunakan` date NOT NULL,
  PRIMARY KEY (`penggunaan_id`),
  UNIQUE KEY `nonconsumable_id` (`nonconsumable_id`),
  CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`nonconsumable_id`) REFERENCES `non_consumable` (`nonconsumable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.penggunaan: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.report: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.role: ~3 rows (approximately)
INSERT INTO `role` (`id_role`, `nama`) VALUES
	(1, 'Super Admin'),
	(2, 'Admin'),
	(3, 'Operator');

-- Dumping structure for table inventaris_web.shift
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `shift` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `waktu_kerja` enum('1','0.5','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_operator` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mulai_istirahat` time NOT NULL,
  `selesai_istirahat` time NOT NULL,
  `kendala` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ganti_benang` int NOT NULL,
  `ganti_kain` int NOT NULL,
  PRIMARY KEY (`shift_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.shift: ~0 rows (approximately)

-- Dumping structure for table inventaris_web.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notelfon` int NOT NULL,
  `alamat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kodepos` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.supplier: ~2 rows (approximately)
INSERT INTO `supplier` (`id`, `nama`, `notelfon`, `alamat`, `kota`, `kodepos`) VALUES
	(2, 'Toko Maju', 876384764, 'Kertajaya', 'Surabaya', 920192),
	(4, 'Sumber Energi', 815786546, 'Manyar 3', 'Surabaya', 423212);

-- Dumping structure for table inventaris_web.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.unit: ~1 rows (approximately)
INSERT INTO `unit` (`id`, `nama`) VALUES
	(1, 'Meter');

-- Dumping structure for table inventaris_web.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `id_role` int NOT NULL,
  `nama_pengguna` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `kata_sandi` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL,
  `authKey` varchar(255) DEFAULT NULL,
  `dibuat_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- Dumping data for table inventaris_web.user: ~5 rows (approximately)
INSERT INTO `user` (`user_id`, `id_role`, `nama_pengguna`, `kata_sandi`, `email`, `authKey`, `dibuat_pada`, `diperbarui_pada`) VALUES
	(1, 1, 'user1', '123', 'user1@gmail.com', '', '2024-08-04 14:59:38', '2024-08-04 14:59:38'),
	(2, 2, 'Jojo', '123', 'jojo@gmail.com', '', '2024-08-13 09:40:30', '2024-08-13 09:40:30'),
	(3, 3, 'Berttt', '123', 'bert@gmail.com', '', '2024-08-13 09:40:58', '2024-08-13 12:47:34'),
	(4, 3, 'Felix', '123', 'felix@gmail.com', '', '2024-08-13 11:17:26', '2024-08-13 11:17:26'),
	(5, 1, 'Christina', '123', 'chris@gmail.com', 'chris', '2024-08-13 16:23:41', '2024-08-13 16:23:41');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
