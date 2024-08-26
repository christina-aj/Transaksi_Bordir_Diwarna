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


-- Dumping database structure for inventaris
CREATE DATABASE IF NOT EXISTS `inventaris` /*!40100 DEFAULT CHARACTER SET armscii8 COLLATE armscii8_bin */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `inventaris`;

-- Dumping structure for table inventaris.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `barang_id` int NOT NULL AUTO_INCREMENT,
  `kode_barang` varchar(255) COLLATE armscii8_bin NOT NULL,
  `nama_barang` varchar(255) COLLATE armscii8_bin NOT NULL,
  `unit_id` int NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `tipe` varchar(255) COLLATE armscii8_bin NOT NULL,
  `warna` varchar(255) COLLATE armscii8_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`barang_id`),
  UNIQUE KEY `unit_id` (`unit_id`) USING BTREE
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table inventaris.barang: ~0 rows (approximately)

-- Dumping structure for table inventaris.item
CREATE TABLE IF NOT EXISTS `item` (
  `id` int NOT NULL AUTO_INCREMENT,
  `kategori` enum('consumable','non-consumable') COLLATE utf8mb4_general_ci NOT NULL,
  `supplier_id` int NOT NULL,
  `unit_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `harga` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `total` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `tempat_belanja` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `kategori` (`kategori`),
  UNIQUE KEY `unit_id` (`unit_id`),
  UNIQUE KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.item: ~0 rows (approximately)

-- Dumping structure for table inventaris.laporan_produksi
CREATE TABLE IF NOT EXISTS `laporan_produksi` (
  `laporan_id` int NOT NULL AUTO_INCREMENT,
  `mesin_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `vs` int NOT NULL,
  `stitch` int NOT NULL,
  `kuantitas` int NOT NULL,
  `bs` int NOT NULL,
  PRIMARY KEY (`laporan_id`),
  UNIQUE KEY `mesin_id` (`mesin_id`,`shift_id`),
  KEY `shift_id` (`shift_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.laporan_produksi: ~0 rows (approximately)

-- Dumping structure for table inventaris.mesin
CREATE TABLE IF NOT EXISTS `mesin` (
  `mesin_id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`mesin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.mesin: ~0 rows (approximately)
INSERT INTO `mesin` (`mesin_id`, `nama`, `deskripsi`) VALUES
	(1, 'Mesin Bordir', 'test');

-- Dumping structure for table inventaris.pembelian
CREATE TABLE IF NOT EXISTS `pembelian` (
  `pembelian_id` int NOT NULL AUTO_INCREMENT,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `supplier_id` int NOT NULL,
  `total_biaya` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `kode_struk` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`pembelian_id`),
  UNIQUE KEY `user_id` (`user_id`),
  UNIQUE KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.pembelian: ~0 rows (approximately)

-- Dumping structure for table inventaris.pembelian_detail
CREATE TABLE IF NOT EXISTS `pembelian_detail` (
  `belidetail_id` int NOT NULL AUTO_INCREMENT,
  `pembelian_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga_barang` decimal(10,0) NOT NULL,
  `quantity_barang` float NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `catatan` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`belidetail_id`),
  UNIQUE KEY `pembelian_id` (`pembelian_id`),
  UNIQUE KEY `barang_id` (`barang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table inventaris.pembelian_detail: ~0 rows (approximately)

-- Dumping structure for table inventaris.penggunaan
CREATE TABLE IF NOT EXISTS `penggunaan` (
  `penggunaan_id` int NOT NULL AUTO_INCREMENT,
  `nonconsumable_id` int NOT NULL,
  `jumlah_digunakan` int NOT NULL,
  `tanggal_digunakan` date NOT NULL,
  PRIMARY KEY (`penggunaan_id`),
  UNIQUE KEY `nonconsumable_id` (`nonconsumable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.penggunaan: ~0 rows (approximately)

-- Dumping structure for table inventaris.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.report: ~0 rows (approximately)

-- Dumping structure for table inventaris.role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.role: ~3 rows (approximately)
INSERT INTO `role` (`id_role`, `nama`) VALUES
	(1, 'Super Admin'),
	(2, 'Admin'),
	(3, 'Operator');

-- Dumping structure for table inventaris.shift
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `user_id` int NOT NULL,
  `shift` enum('1','2') COLLATE utf8mb4_general_ci NOT NULL,
  `waktu_kerja` decimal(4,2) NOT NULL,
  `nama_operator` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `mulai_istirahat` time NOT NULL,
  `selesai_istirahat` time NOT NULL,
  `kendala` text COLLATE utf8mb4_general_ci NOT NULL,
  `ganti_benang` int NOT NULL,
  `ganti_kain` int NOT NULL,
  PRIMARY KEY (`shift_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.shift: ~0 rows (approximately)

-- Dumping structure for table inventaris.stock
CREATE TABLE IF NOT EXISTS `stock` (
  `stock_id` int NOT NULL AUTO_INCREMENT,
  `tambah_stock` date NOT NULL,
  `barang_id` int NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `user_id` int NOT NULL,
  `is_ready` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_id`),
  UNIQUE KEY `barang_id` (`barang_id`),
  UNIQUE KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- Dumping data for table inventaris.stock: ~0 rows (approximately)

-- Dumping structure for table inventaris.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `notelfon` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kodepos` int NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.supplier: ~2 rows (approximately)
INSERT INTO `supplier` (`id`, `nama`, `notelfon`, `alamat`, `kota`, `kodepos`) VALUES
	(1, 'Toko Sumber Jaya', '081252807753', 'Jalan Jaya no 15', 'Surabaya', 22134),
	(2, 'Toko Abadi', '082122224532', 'Jalan Mawar  1/11', 'Jakarta', 60113);

-- Dumping structure for table inventaris.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nama` varchar(11) COLLATE utf8mb4_general_ci NOT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.unit: ~2 rows (approximately)
INSERT INTO `unit` (`id`, `nama`) VALUES
	(4, 'Kilo'),
	(5, 'Kilo');

-- Dumping structure for table inventaris.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int NOT NULL AUTO_INCREMENT,
  `id_role` int NOT NULL,
  `nama_pengguna` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kata_sandi` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `authkey` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `id_role` (`id_role`),
  UNIQUE KEY `nama_pengguna` (`nama_pengguna`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris.user: ~3 rows (approximately)
INSERT INTO `user` (`user_id`, `id_role`, `nama_pengguna`, `email`, `kata_sandi`, `authkey`, `dibuat_pada`, `diperbarui_pada`) VALUES
	(1, 1, 'user1', 'user1@gmail.com', '123', '', '2024-08-04 14:59:38', '2024-08-04 14:59:38'),
	(2, 2, 'Jojo', 'jojo@gmail.com', '123', '', '2024-08-13 09:40:30', '2024-08-13 09:40:30'),
	(3, 3, 'Berttt', 'bert@gmail.com', '123', '', '2024-08-13 09:40:58', '2024-08-13 12:47:34');

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
