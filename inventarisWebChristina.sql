-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Server version:               10.4.32-MariaDB - mariadb.org binary distribution
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
CREATE DATABASE IF NOT EXISTS `inventaris_web` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci */;
USE `inventaris_web`;

-- Dumping structure for table inventaris_web.barang
CREATE TABLE IF NOT EXISTS `barang` (
  `barang_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_id` int(11) DEFAULT 1,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `angka` float NOT NULL,
  `unit_id` int(11) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `kategori_barang` tinyint(2) NOT NULL DEFAULT 1 COMMENT '1=fastmoving, 2=slowmoving, 3=alat',
  `warna` varchar(255) DEFAULT NULL,
  `biaya_simpan_bulan` int(11) NOT NULL DEFAULT 0,
  `safety_stock` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `jenis_barang` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=mentah, 2=setjadi, 3=jadi, 4=noncomsum',
  PRIMARY KEY (`barang_id`),
  KEY `unit_id` (`unit_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `FK_barang_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`)
) ENGINE=InnoDB AUTO_INCREMENT=45 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.barang: ~17 rows (approximately)
DELETE FROM `barang`;
INSERT INTO `barang` (`barang_id`, `supplier_id`, `kode_barang`, `nama_barang`, `angka`, `unit_id`, `tipe`, `kategori_barang`, `warna`, `biaya_simpan_bulan`, `safety_stock`, `created_at`, `updated_at`, `jenis_barang`) VALUES
	(1, 1, 'A003', 'Benang Oren', 5, 1, 'Consumable', 1, 'Oren', 2500, 20, '2025-10-14 16:51:38', '2024-09-17 15:45:51', 1),
	(3, 1, 'B001', 'Kain Hijau', 6, 1, 'Consumable', 1, 'Hijau', 0, 0, '2024-09-02 16:27:12', '2024-09-17 16:23:19', 1),
	(4, 1, 'A001', 'Benang Merah', 3000, 7, 'Consumable', 2, 'Merah', 5000, 10, '2024-08-26 21:37:26', '2024-09-17 15:46:50', 1),
	(5, 1, 'B002', 'Kain Kuning', 3, 1, 'Consumable', 1, 'Kuning', 0, 0, '2024-09-02 15:59:24', '2024-09-17 16:23:29', 1),
	(9, 1, 'A004', 'Benang Polyester', 1000, 8, 'Consumable', 1, 'Putih', 3000, 20, '2024-09-17 16:20:33', '2024-09-17 16:20:33', 1),
	(10, 1, 'A005', 'Benang Polyester', 500, 8, 'Consumable', 1, 'Putih', 0, 0, '2024-09-17 16:21:33', '2024-09-17 16:21:33', 1),
	(11, 1, 'A002', 'Benang Rayon', 500, 8, 'Consumable', 1, 'Merah', 4000, 15, '2024-09-17 16:22:51', '2024-09-17 16:22:51', 1),
	(12, 1, 'M001', 'Mesin Bordir', 1, 9, 'Non Consumable', 1, 'Silver', 0, 0, '2024-09-17 16:25:12', '2024-09-17 16:25:12', 2),
	(13, 1, 'M002', 'Rangka Bordir', 5, 9, 'Non Consumable', 1, 'Hitam', 0, 0, '2024-09-17 16:25:55', '2024-09-17 16:25:55', 2),
	(14, 1, 'A006', 'Benang Merah', 250, 8, 'Consumable', 2, 'Merah', 0, 0, '2024-09-17 16:34:07', '2024-09-17 16:34:07', 1),
	(19, 1, 'A097', 'Kain Pecah Oren', 16, 8, 'Consumable', 1, 'Tidak ada', 0, 0, '2024-11-05 15:23:00', '2024-11-05 15:23:00', 1),
	(24, 1, 'A999', 'Kain Pecah Oren', 16, 1, 'Consumable', 1, 'Tidak ada', 0, 0, '2024-11-05 15:30:45', '2024-11-05 15:30:45', 1),
	(25, 1, 'A9999', 'Kain sambung merah', 16, 1, 'Consumable', 1, 'Tidak ada', 0, 0, '2024-11-05 15:36:28', '2024-11-05 15:36:28', 1),
	(26, 1, 'A9998', 'Kain sambung biru', 12, 6, 'Consumable', 1, 'Tidak ada', 0, 0, '2024-11-05 15:36:28', '2024-11-05 15:36:28', 1),
	(27, 1, 'A9997', 'Kain sambung hijau', 14, 8, 'Consumable', 1, 'Tidak ada', 0, 0, '2024-11-05 15:36:28', '2024-11-05 15:36:28', 1),
	(37, 1, 'ESTEH123', 'kerangka Badan', 1, 9, 'Non Consumable', 1, '', 0, 0, '2024-11-05 17:24:25', '2024-11-05 17:24:25', 2),
	(38, 1, 'ESTEH124', 'kerangka Mesin', 2, 9, 'Non Consumable', 1, '', 0, 0, '2024-11-05 17:24:25', '2024-11-05 17:24:25', 2);

-- Dumping structure for table inventaris_web.barangproduksi
CREATE TABLE IF NOT EXISTS `barangproduksi` (
  `barang_produksi_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_barang_produksi` varchar(255) NOT NULL DEFAULT 'P',
  `nama` varchar(200) NOT NULL,
  `nama_jenis` varchar(200) NOT NULL,
  `ukuran` varchar(255) NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`barang_produksi_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.barangproduksi: ~7 rows (approximately)
DELETE FROM `barangproduksi`;
INSERT INTO `barangproduksi` (`barang_produksi_id`, `kode_barang_produksi`, `nama`, `nama_jenis`, `ukuran`, `deskripsi`) VALUES
	(4, 'P002', 'Baju Merah', 'Baju Lengan Panjan', '25', 'Baju dengan kain katun'),
	(5, 'P001', 'Kaos Kaki Rajut hitam', 'Celana Pendek', '27', 'tidak ada'),
	(6, 'P003', 'Kaos', 'Baju', '25', '-'),
	(7, 'P004', '25-26 PTH', 'Kaus Kaki', '25', '-'),
	(8, 'P005', '21-22 PTH', 'Kaus Kaki', '21', ''),
	(9, 'P006', '23-24 PTH', 'Kaus Kaki', '23', ''),
	(10, 'P007', '25-26 HTM', 'Kaus Kaki', '25', '');

-- Dumping structure for table inventaris_web.barang_custom_pelanggan
CREATE TABLE IF NOT EXISTS `barang_custom_pelanggan` (
  `barang_custom_pelanggan_id` int(11) NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) NOT NULL,
  `kode_barang_custom` varchar(255) NOT NULL DEFAULT '',
  `nama_barang_custom` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`barang_custom_pelanggan_id`),
  KEY `pelanggan_id` (`pelanggan_id`),
  CONSTRAINT `FK_barang_custom_pelanggan_master_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `master_pelanggan` (`pelanggan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.barang_custom_pelanggan: ~2 rows (approximately)
DELETE FROM `barang_custom_pelanggan`;
INSERT INTO `barang_custom_pelanggan` (`barang_custom_pelanggan_id`, `pelanggan_id`, `kode_barang_custom`, `nama_barang_custom`, `created_at`, `updated_at`) VALUES
	(6, 1, 'BC-001', 'kaus kaki logo putih', '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(7, 1, 'TEST-1', 'kaos kaki bagus', '0000-00-00 00:00:00', '0000-00-00 00:00:00');

-- Dumping structure for table inventaris_web.bom_barang
CREATE TABLE IF NOT EXISTS `bom_barang` (
  `BOM_barang_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_produksi_id` int(11) DEFAULT NULL,
  `total_bahan_baku` int(11) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`BOM_barang_id`),
  KEY `barang_produksi_id` (`barang_produksi_id`),
  CONSTRAINT `FK__barangproduksi` FOREIGN KEY (`barang_produksi_id`) REFERENCES `barangproduksi` (`barang_produksi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.bom_barang: ~3 rows (approximately)
DELETE FROM `bom_barang`;
INSERT INTO `bom_barang` (`BOM_barang_id`, `barang_produksi_id`, `total_bahan_baku`, `created_at`, `updated_at`) VALUES
	(1, 10, 2, '2025-10-10 03:39:57', '2025-10-23 07:48:55'),
	(2, 7, 2, '2025-10-12 18:53:41', '2025-10-12 11:54:15'),
	(6, 6, 2, '2025-10-19 09:32:40', '2025-10-19 02:33:07');

-- Dumping structure for table inventaris_web.bom_custom
CREATE TABLE IF NOT EXISTS `bom_custom` (
  `BOM_custom_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_custom_pelanggan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty_per_unit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`BOM_custom_id`) USING BTREE,
  KEY `barang_produksi_id` (`barang_id`) USING BTREE,
  KEY `barang_custom_pelanggan` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_bom_custom_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  CONSTRAINT `FK_bom_custom_barang_custom_pelanggan` FOREIGN KEY (`barang_custom_pelanggan_id`) REFERENCES `barang_custom_pelanggan` (`barang_custom_pelanggan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.bom_custom: ~2 rows (approximately)
DELETE FROM `bom_custom`;
INSERT INTO `bom_custom` (`BOM_custom_id`, `barang_custom_pelanggan_id`, `barang_id`, `qty_per_unit`, `created_at`, `updated_at`) VALUES
	(4, 6, 4, 3, '2025-10-23 13:24:14', '2025-10-23 13:24:14'),
	(5, 7, 25, 1, '2025-10-23 13:24:14', '2025-10-23 13:24:14');

-- Dumping structure for table inventaris_web.bom_detail
CREATE TABLE IF NOT EXISTS `bom_detail` (
  `BOM_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `BOM_barang_id` int(11) NOT NULL DEFAULT 0,
  `barang_id` int(11) DEFAULT NULL,
  `qty_BOM` int(11) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`BOM_detail_id`),
  KEY `barang_id` (`barang_id`),
  KEY `BOM_barang_id` (`BOM_barang_id`),
  CONSTRAINT `FK__barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  CONSTRAINT `FK_bom_detail_bom_barang` FOREIGN KEY (`BOM_barang_id`) REFERENCES `bom_barang` (`BOM_barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.bom_detail: ~6 rows (approximately)
DELETE FROM `bom_detail`;
INSERT INTO `bom_detail` (`BOM_detail_id`, `BOM_barang_id`, `barang_id`, `qty_BOM`, `catatan`) VALUES
	(1, 1, 11, 1, 'tes'),
	(2, 1, 4, 2, ''),
	(3, 2, 1, 5, 't'),
	(4, 2, 9, 7, 'd'),
	(5, 6, 1, 1, 'a'),
	(6, 6, 11, 2, 's');

-- Dumping structure for table inventaris_web.data_perhitungan
CREATE TABLE IF NOT EXISTS `data_perhitungan` (
  `data_perhitungan_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `biaya_pesan` float NOT NULL,
  `biaya_simpan` float NOT NULL,
  `safety_stock` float NOT NULL,
  `lead_time_rerata` int(11) NOT NULL DEFAULT 0,
  `periode_mulasi` date NOT NULL,
  `periode_selesai` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`data_perhitungan_id`) USING BTREE,
  KEY `barang_id` (`barang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.data_perhitungan: ~0 rows (approximately)
DELETE FROM `data_perhitungan`;

-- Dumping structure for table inventaris_web.detail_gudang
CREATE TABLE IF NOT EXISTS `detail_gudang` (
  `detailGudang_id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_area_gudang` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`detailGudang_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.detail_gudang: ~0 rows (approximately)
DELETE FROM `detail_gudang`;

-- Dumping structure for table inventaris_web.eoq_rop
CREATE TABLE IF NOT EXISTS `eoq_rop` (
  `EOQ_ROP_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `biaya_pesan_snapshot` float NOT NULL DEFAULT 0,
  `biaya_simpan_snapshot` float NOT NULL DEFAULT 0,
  `safety_stock_snapshot` float NOT NULL DEFAULT 0,
  `lead_time_snapshot` int(11) NOT NULL,
  `demand_snapshot` float NOT NULL DEFAULT 0,
  `total_biaya_persediaan` float NOT NULL DEFAULT 0,
  `hasil_eoq` float DEFAULT NULL,
  `hasil_rop` float DEFAULT NULL,
  `periode` varchar(7) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`EOQ_ROP_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `FK_eoq_rop_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=27 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.eoq_rop: ~4 rows (approximately)
DELETE FROM `eoq_rop`;
INSERT INTO `eoq_rop` (`EOQ_ROP_id`, `barang_id`, `biaya_pesan_snapshot`, `biaya_simpan_snapshot`, `safety_stock_snapshot`, `lead_time_snapshot`, `demand_snapshot`, `total_biaya_persediaan`, `hasil_eoq`, `hasil_rop`, `periode`, `created_at`) VALUES
	(23, 1, 12000, 2500, 20, 3, 4300, 0, 203.17, 450, '202510', '2025-10-20 07:15:51'),
	(24, 4, 15000, 5000, 10, 3, 1760, 0, 20, 10, '202510', '2025-11-10 05:06:59'),
	(25, 9, 10000, 3000, 20, 1, 6020, 0, 200.33, 220.67, '202510', '2025-10-20 07:15:51'),
	(26, 11, 10000, 4000, 15, 1, 880, 0, 66.33, 44.33, '202510', '2025-10-20 07:15:51'),
	(27, 1, 12000, 2500, 20, 3, 3470, 0, 182.52, 367, '202511', '2025-11-10 05:02:41'),
	(28, 9, 10000, 3000, 20, 1, 4858, 0, 179.96, 181.93, '202511', '2025-11-10 05:02:41');

-- Dumping structure for table inventaris_web.forecast
CREATE TABLE IF NOT EXISTS `forecast` (
  `forecast_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_produksi_id` int(11) DEFAULT NULL,
  `barang_custom_pelanggan_id` int(11) DEFAULT NULL,
  `periode_forecast` varchar(7) NOT NULL DEFAULT '0',
  `nilai_alpha` float(1,1) NOT NULL,
  `mape_test` float NOT NULL,
  `hasil_forecast` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`forecast_id`),
  KEY `riwayat_penjualan_id` (`barang_produksi_id`) USING BTREE,
  KEY `barang_custom_pelanggan_id` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_forecast_barang_custom_pelanggan` FOREIGN KEY (`barang_custom_pelanggan_id`) REFERENCES `barang_custom_pelanggan` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_forecast_barangproduksi` FOREIGN KEY (`barang_produksi_id`) REFERENCES `barangproduksi` (`barang_produksi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=62 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.forecast: ~3 rows (approximately)
DELETE FROM `forecast`;
INSERT INTO `forecast` (`forecast_id`, `barang_produksi_id`, `barang_custom_pelanggan_id`, `periode_forecast`, `nilai_alpha`, `mape_test`, `hasil_forecast`, `created_at`, `updated_at`) VALUES
	(60, 10, NULL, '202510', 0.1, 10.67, 880, '2025-10-20 00:14:52', NULL),
	(61, 7, NULL, '202511', 0.1, 3061.95, 694, '2025-11-08 11:33:13', NULL);

-- Dumping structure for table inventaris_web.forecast_history
CREATE TABLE IF NOT EXISTS `forecast_history` (
  `forecast_history_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_produksi_id` int(11) NOT NULL,
  `periode_forecast` varchar(7) NOT NULL DEFAULT '0',
  `nilai_alpha` float(1,1) NOT NULL,
  `mape_test` float NOT NULL,
  `hasil_forecast` int(11) NOT NULL,
  `data_aktual` int(11) DEFAULT NULL,
  `selisih` int(11) DEFAULT NULL,
  `tanggal_dibuat` date DEFAULT NULL,
  PRIMARY KEY (`forecast_history_id`)
) ENGINE=InnoDB AUTO_INCREMENT=28 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.forecast_history: ~3 rows (approximately)
DELETE FROM `forecast_history`;
INSERT INTO `forecast_history` (`forecast_history_id`, `barang_produksi_id`, `periode_forecast`, `nilai_alpha`, `mape_test`, `hasil_forecast`, `data_aktual`, `selisih`, `tanggal_dibuat`) VALUES
	(25, 7, '202510', 0.1, 11.65, 860, 2, NULL, '2025-10-20'),
	(26, 10, '202510', 0.1, 10.67, 880, NULL, NULL, '2025-10-20'),
	(27, 7, '202511', 0.1, 3061.95, 694, NULL, NULL, '2025-11-08');

-- Dumping structure for table inventaris_web.gudang
CREATE TABLE IF NOT EXISTS `gudang` (
  `id_gudang` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `barang_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  `kode` tinyint(4) NOT NULL DEFAULT 1,
  `area_gudang` int(11) NOT NULL DEFAULT 1 COMMENT '1 = depan, 2= belakang, 3=atas, 4=garasiseberang, 5=areaproduksi',
  PRIMARY KEY (`id_gudang`),
  KEY `barang_id` (`barang_id`),
  KEY `user_id` (`user_id`),
  CONSTRAINT `gudang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  CONSTRAINT `gudang_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=261 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.gudang: ~69 rows (approximately)
DELETE FROM `gudang`;
INSERT INTO `gudang` (`id_gudang`, `tanggal`, `barang_id`, `user_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `catatan`, `created_at`, `update_at`, `kode`, `area_gudang`) VALUES
	(67, '2024-11-09', 14, 1, 0, 15, 15, 0, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40', 1, 1),
	(68, '2024-11-09', 19, 1, 0, 20, 0, 20, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40', 1, 1),
	(69, '2024-11-09', 3, 1, 0, 16, 16, 0, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40', 1, 1),
	(70, '2024-11-09', 19, 2, 20, 0, 5, 15, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26', 1, 1),
	(71, '2024-11-09', 19, 1, 15, 0, 2, 13, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26', 1, 1),
	(72, '2024-12-10', 4, 1, 0, 15, 15, 0, 'Verifikasi pemesanan ID: 273', '2024-12-10 17:23:26', '2024-12-10 17:23:26', 1, 1),
	(73, '2024-12-10', 11, 1, 0, 18, 0, 18, 'Verifikasi pemesanan ID: 273', '2024-12-10 17:23:26', '2024-12-10 17:23:26', 1, 1),
	(74, '2024-12-10', 14, 1, 0, 20, 20, 0, 'Verifikasi pemesanan ID: 273', '2024-12-10 17:23:26', '2024-12-10 17:23:26', 1, 1),
	(75, '2024-12-10', 11, 6, 18, 0, 4, 14, '', '2024-12-10 17:28:33', '2024-12-10 17:28:33', 1, 1),
	(76, '2024-12-11', 1, 1, 0, 14, 14, 0, 'Verifikasi pemesanan ID: 308', '2024-12-11 17:03:28', '2024-12-11 17:03:28', 1, 1),
	(77, '2024-12-11', 9, 1, 0, 15, 0, 15, 'Verifikasi pemesanan ID: 308', '2024-12-11 17:03:28', '2024-12-11 17:03:28', 1, 1),
	(78, '2024-12-11', 14, 1, 0, 5, 5, 0, 'Verifikasi pemesanan ID: 308', '2024-12-11 17:03:28', '2024-12-11 17:03:28', 1, 1),
	(79, '2024-12-11', 9, 2, 15, 0, 1, 14, '', '2024-12-11 17:04:10', '2024-12-11 17:04:10', 1, 1),
	(80, '2024-12-11', 11, 5, 14, 0, 12, 2, '', '2024-12-11 17:04:10', '2024-12-11 17:04:10', 1, 1),
	(81, '2025-07-20', 9, 1, 14, 0, 9, 5, 'guig', '2025-07-20 16:23:29', '2025-07-20 16:23:29', 1, 1),
	(82, '2025-07-25', 11, 2, 2, 0, 1, 1, 'rererer', '2025-07-25 17:04:38', '2025-07-25 17:04:38', 1, 1),
	(83, '2025-07-25', 9, 2, 5, 0, 1, 4, 'test', '2025-07-25 17:29:37', '2025-07-25 17:29:37', 1, 1),
	(84, '2025-08-03', 9, 1, 4, 0, 1, 3, '', '2025-08-03 16:59:44', '2025-08-03 16:59:44', 1, 1),
	(209, '2024-11-09', 14, 1, 0, 15, 0, 15, '', '2024-11-09 08:18:40', '2024-11-09 08:18:40', 2, 5),
	(210, '2024-11-09', 3, 1, 0, 16, 0, 16, '', '2024-11-09 08:18:40', '2024-11-09 08:18:40', 2, 5),
	(211, '2024-11-09', 19, 2, 0, 5, 0, 5, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26', 2, 5),
	(212, '2024-11-09', 19, 1, 5, 2, 0, 7, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26', 2, 5),
	(213, '2024-12-10', 4, 1, 0, 15, 0, 15, '', '2024-12-10 17:23:26', '2024-12-10 17:23:26', 2, 5),
	(214, '2024-12-10', 14, 1, 15, 20, 0, 35, '', '2024-12-10 17:23:26', '2024-12-10 17:23:26', 2, 5),
	(215, '2024-12-10', 11, 6, 0, 4, 0, 4, '', '2024-12-10 17:28:33', '2024-12-10 17:28:33', 2, 5),
	(216, '2024-12-11', 1, 1, 0, 14, 0, 14, '', '2024-12-11 17:03:28', '2024-12-11 17:03:28', 2, 5),
	(217, '2024-12-11', 14, 1, 35, 5, 0, 40, '', '2024-12-11 17:03:28', '2024-12-11 17:03:28', 2, 5),
	(218, '2024-12-11', 9, 2, 0, 1, 0, 1, '', '2024-12-11 17:04:10', '2024-12-11 17:04:10', 2, 5),
	(219, '2024-12-11', 11, 5, 4, 12, 0, 16, '', '2024-12-11 17:04:10', '2024-12-11 17:04:10', 2, 5),
	(220, '2025-07-20', 9, 1, 1, 9, 0, 10, '', '2025-07-20 16:23:29', '2025-07-20 16:23:29', 2, 5),
	(221, '2025-07-20', 9, 1, 10, 0, 5, 5, '', '2025-07-20 16:25:59', '2025-07-20 16:25:59', 2, 5),
	(222, '2025-07-25', 11, 2, 16, 1, 0, 17, '', '2025-07-25 17:04:38', '2025-07-25 17:04:38', 2, 5),
	(223, '2025-07-25', 9, 2, 5, 1, 0, 6, '', '2025-07-25 17:29:37', '2025-07-25 17:29:37', 2, 5),
	(224, '2025-07-25', 9, 2, 6, 0, 1, 5, '', '2025-07-25 17:30:40', '2025-07-25 17:30:40', 2, 5),
	(225, '2025-08-03', 9, 1, 5, 1, 0, 6, '', '2025-08-03 16:59:44', '2025-08-03 16:59:44', 2, 5),
	(226, '2025-09-09', 9, 1, 6, 0, 1, 5, '-', '2025-09-09 04:24:39', '2025-09-09 04:24:39', 1, 1),
	(227, '2025-09-09', 9, 1, 0, 1, 0, 1, '-', '2025-09-09 04:24:39', '2025-09-09 04:24:39', 1, 3),
	(228, '2025-09-10', 9, 1, 5, 0, 1, 4, '', '2025-09-10 01:01:35', '2025-09-10 01:01:35', 1, 1),
	(229, '2025-09-10', 4, 1, 0, 0, 2, -2, '', '2025-09-10 01:01:35', '2025-09-10 01:01:35', 1, 1),
	(230, '2025-09-10', 9, 1, 4, 7, 0, 11, 'Verifikasi pemesanan ID: 312', '2025-09-10 01:09:30', '2025-09-10 01:09:30', 1, 1),
	(231, '2025-09-19', 9, 1, 11, 0, 2, 9, 'Pindah ke Area 2 - test', '2025-09-19 03:01:51', '2025-09-19 03:01:51', 1, 1),
	(232, '2025-09-19', 9, 1, 0, 2, 0, 2, 'Pindah dari Area 1 - test', '2025-09-19 03:01:51', '2025-09-19 03:01:51', 1, 2),
	(234, '2025-09-19', 9, 1, 9, 2, 0, 11, 'Pindah dari Area 2 - ', '2025-09-19 03:21:26', '2025-09-19 03:21:26', 1, 1),
	(235, '2025-09-19', 9, 1, 2, 2, 1, 3, 'www | Penggunaan ID: 39', '2025-09-19 17:50:53', '2025-09-29 07:18:03', 1, 2),
	(236, '2025-09-20', 1, 1, 0, 0, 0, 0, 'Verifikasi pembelian ID: 243', '2025-09-20 17:24:31', '2025-09-20 17:24:31', 1, 1),
	(237, '2025-09-20', 4, 1, -2, 0, 0, -2, 'Verifikasi pembelian ID: 243', '2025-09-20 17:24:31', '2025-09-20 17:24:31', 1, 1),
	(238, '2025-09-20', 1, 1, 0, 11, 0, 11, 'Verifikasi pemesanan ID: 335', '2025-09-20 17:36:10', '2025-09-20 17:36:10', 1, 1),
	(239, '2025-09-20', 1, 1, 11, 10, 5, 16, 'Verifikasi pemesanan ID: 334 | Penggunaan ID: 35', '2025-09-20 17:37:21', '2025-09-27 06:48:45', 1, 1),
	(240, '2025-09-20', 4, 1, -2, 8, 2, 4, 'Verifikasi pemesanan ID: 334 | Penggunaan ID: 35', '2025-09-20 17:37:21', '2025-09-27 06:48:45', 1, 1),
	(241, '2025-09-20', 4, 1, 6, 0, 1, 5, 'Verifikasi pemesanan ID: 331 | Penggunaan ID: 35', '2025-09-20 17:57:16', '2025-09-27 06:48:45', 1, 2),
	(242, '2025-09-20', 9, 1, 7, 4, 0, 11, NULL, '2025-09-20 18:03:46', '2025-09-20 18:03:46', 2, 1),
	(243, '2025-09-20', 9, 1, 4, 4, 4, 4, 'Verifikasi pemesanan ID: 338', '2025-09-20 18:03:46', '2025-09-20 18:03:46', 1, 1),
	(244, '2025-09-29', 9, 1, 3, 0, 2, 1, 'Penggunaan ID: 40', '2025-09-29 07:25:18', '2025-09-29 07:25:18', 1, 2),
	(245, '2025-09-29', 9, 1, 11, 0, 2, 9, 'Digunakan Produksi ID: 41', '2025-09-29 07:33:39', '2025-09-29 07:33:39', 1, 1),
	(246, '2025-09-29', 9, 1, 11, 2, 0, 13, 'Transfer dari Gudang - Penggunaan ID: 41', '2025-09-29 07:33:39', '2025-09-29 07:33:39', 2, 5),
	(247, '2025-10-20', 4, 7, 4, 0, 2, 2, 'Digunakan Produksi ID: 44', '2025-10-20 07:06:04', '2025-10-20 07:06:04', 1, 1),
	(248, '2025-10-20', 4, 7, 15, 2, 0, 17, 'Transfer dari Gudang - Penggunaan ID: 44', '2025-10-20 07:06:04', '2025-10-20 07:06:04', 2, 5),
	(249, '2025-10-20', 4, 7, 5, 0, 2, 3, 'Digunakan Produksi ID: 44', '2025-10-20 07:06:04', '2025-10-20 07:06:04', 1, 2),
	(250, '2025-10-20', 4, 7, 17, 2, 0, 19, 'Transfer dari Gudang - Penggunaan ID: 44', '2025-10-20 07:06:04', '2025-10-20 07:06:04', 2, 5),
	(251, '2025-10-25', 9, 1, 9, 50, 0, 59, 'Verifikasi pemesanan ID: 361', '2025-10-25 07:16:45', '2025-10-25 07:16:45', 1, 2),
	(252, '2025-10-25', 1, 1, 16, 50, 0, 66, 'Verifikasi pemesanan ID: 361', '2025-10-25 07:16:45', '2025-10-25 07:16:45', 1, 2),
	(253, '2025-10-25', 1, 1, 66, 0, 20, 46, 'Digunakan Produksi ID: 65', '2025-10-25 07:46:56', '2025-10-25 07:46:56', 1, 2),
	(254, '2025-10-25', 1, 1, 14, 20, 0, 34, 'Transfer dari Gudang - Penggunaan ID: 65', '2025-10-25 07:46:56', '2025-10-25 07:46:56', 2, 5),
	(255, '2025-10-25', 9, 1, 59, 0, 28, 31, 'Digunakan Produksi ID: 65', '2025-10-25 07:46:56', '2025-10-25 07:46:56', 1, 2),
	(256, '2025-10-25', 9, 1, 13, 28, 0, 41, 'Transfer dari Gudang - Penggunaan ID: 65', '2025-10-25 07:46:56', '2025-10-25 07:46:56', 2, 5),
	(257, '2025-10-27', 4, 1, 17, 6, 0, 23, NULL, '2025-10-27 20:19:14', '2025-10-27 20:19:14', 2, 1),
	(258, '2025-10-27', 4, 1, 2, 6, 6, 2, 'Verifikasi pemesanan ID: 373', '2025-10-27 20:19:14', '2025-10-27 20:19:14', 1, 1),
	(259, '2025-10-27', 4, 1, 17, 6, 0, 23, NULL, '2025-10-27 20:24:37', '2025-10-27 20:24:37', 2, 1),
	(260, '2025-10-27', 4, 1, 23, 6, 6, 23, 'Verifikasi pemesanan ID: 374', '2025-10-27 20:24:37', '2025-10-27 20:24:37', 1, 1);

-- Dumping structure for table inventaris_web.jenis
CREATE TABLE IF NOT EXISTS `jenis` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_jenis` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.jenis: ~4 rows (approximately)
DELETE FROM `jenis`;
INSERT INTO `jenis` (`id`, `nama_jenis`, `deskripsi`) VALUES
	(1, 'Baju', 'Tshirt biasa'),
	(2, 'Baju Lengan Panjan', 'Baju dengan Lengan Panjang'),
	(3, 'Celana Pendek', 'Celana Dengan panjang 20cm'),
	(4, 'Kaus Kaki', 'Kaus kaki produksi sendiri');

-- Dumping structure for table inventaris_web.laporanproduksi
CREATE TABLE IF NOT EXISTS `laporanproduksi` (
  `laporan_id` int(11) NOT NULL AUTO_INCREMENT,
  `mesin_id` varchar(100) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) NOT NULL,
  `vs` int(11) DEFAULT NULL,
  `stitch` int(11) DEFAULT NULL,
  `kuantitas` int(11) NOT NULL,
  `bs` int(11) NOT NULL,
  `berat` varchar(200) DEFAULT NULL,
  `nama_barang` varchar(200) NOT NULL,
  PRIMARY KEY (`laporan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.laporanproduksi: ~5 rows (approximately)
DELETE FROM `laporanproduksi`;
INSERT INTO `laporanproduksi` (`laporan_id`, `mesin_id`, `shift_id`, `tanggal_kerja`, `nama_kerjaan`, `vs`, `stitch`, `kuantitas`, `bs`, `berat`, `nama_barang`) VALUES
	(36, '3', 38, '2024-12-10', 'SD 2 Solo', 1, 1, 55, 3, '', 'Baju Merah'),
	(37, '3', 38, '2024-12-09', 'SD 5 Gedangan', 1, 2, 2000, 1, '', 'Baju Merah'),
	(38, '1', 38, '2024-12-09', 'CV Lintas Sungai', NULL, NULL, 1000, 1, '20 Kg', 'Kaos Kaki Rajut hitam'),
	(39, '1', 39, '2024-12-11', 'SDN 12 Krajan', NULL, NULL, 2000, 2, '20 Kg', '6'),
	(40, '3', 40, '2025-01-08', 'SMP 12 Surabaya', 1, 5, 250, 2, '', '5');

-- Dumping structure for table inventaris_web.laporan_keluar
CREATE TABLE IF NOT EXISTS `laporan_keluar` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `barang` varchar(200) NOT NULL,
  `qty` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.laporan_keluar: ~0 rows (approximately)
DELETE FROM `laporan_keluar`;

-- Dumping structure for table inventaris_web.master_pelanggan
CREATE TABLE IF NOT EXISTS `master_pelanggan` (
  `pelanggan_id` int(11) NOT NULL AUTO_INCREMENT,
  `kode_pelanggan` varchar(255) NOT NULL DEFAULT '',
  `nama_pelanggan` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`pelanggan_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.master_pelanggan: ~1 rows (approximately)
DELETE FROM `master_pelanggan`;
INSERT INTO `master_pelanggan` (`pelanggan_id`, `kode_pelanggan`, `nama_pelanggan`) VALUES
	(1, 'P-01', 'TK Pelita Kasih');

-- Dumping structure for table inventaris_web.mesin
CREATE TABLE IF NOT EXISTS `mesin` (
  `mesin_id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `kategori` enum('1','2') NOT NULL,
  `deskripsi` text NOT NULL,
  PRIMARY KEY (`mesin_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.mesin: ~2 rows (approximately)
DELETE FROM `mesin`;
INSERT INTO `mesin` (`mesin_id`, `nama`, `kategori`, `deskripsi`) VALUES
	(1, 'Mesin Bordir', '2', 'test1'),
	(3, 'Mesin A13', '1', 'Ayam');

-- Dumping structure for table inventaris_web.nota
CREATE TABLE IF NOT EXISTS `nota` (
  `nota_id` int(11) NOT NULL AUTO_INCREMENT,
  `nama_konsumen` varchar(200) NOT NULL,
  `tanggal` date NOT NULL,
  `barang` varchar(255) NOT NULL,
  `harga` varchar(255) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `total_qty` int(11) NOT NULL,
  `total_harga` int(11) NOT NULL,
  PRIMARY KEY (`nota_id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.nota: ~1 rows (approximately)
DELETE FROM `nota`;
INSERT INTO `nota` (`nota_id`, `nama_konsumen`, `tanggal`, `barang`, `harga`, `qty`, `total_qty`, `total_harga`) VALUES
	(18, 'Test21', '2024-11-08', 'Baju Merah,Baju Merah', '1500,1500', '155,151', 306, 459000);

-- Dumping structure for table inventaris_web.pembelian
CREATE TABLE IF NOT EXISTS `pembelian` (
  `pembelian_id` int(11) NOT NULL AUTO_INCREMENT,
  `pemesanan_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `total_biaya` float NOT NULL,
  PRIMARY KEY (`pembelian_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  KEY `pemesanan_id` (`pemesanan_id`),
  CONSTRAINT `pembelian_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=292 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.pembelian: ~35 rows (approximately)
DELETE FROM `pembelian`;
INSERT INTO `pembelian` (`pembelian_id`, `pemesanan_id`, `user_id`, `total_biaya`) VALUES
	(217, 308, 1, 510000),
	(220, 311, 1, 0),
	(221, 312, 1, 60000),
	(223, 314, 1, 0),
	(227, 318, 1, 0),
	(229, 320, 1, 18000),
	(230, 321, 1, 5000),
	(231, 322, 1, 0),
	(236, 327, 1, 100000),
	(239, 330, 1, 0),
	(240, 331, 1, 0),
	(243, 334, 1, 66000),
	(244, 335, 1, 77000),
	(245, 336, 1, 20000),
	(246, 337, 1, 49000),
	(247, 338, 1, 16000),
	(248, 339, 1, 40000),
	(255, 346, 1, 0),
	(256, 347, 1, 0),
	(261, 352, 1, 0),
	(262, 353, 1, 0),
	(264, 355, 1, 0),
	(270, 361, 1, 550000),
	(272, 363, 1, 0),
	(273, 364, 1, 0),
	(275, 366, 1, 0),
	(276, 367, 1, 0),
	(277, 368, 1, 0),
	(278, 369, 1, 0),
	(279, 370, 1, 0),
	(280, 371, 1, 0),
	(282, 373, 1, 300000),
	(283, 374, 1, 300000),
	(286, 377, 1, 0),
	(291, 382, 1, 0);

-- Dumping structure for table inventaris_web.pembelian_detail
CREATE TABLE IF NOT EXISTS `pembelian_detail` (
  `belidetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `pembelian_id` int(11) NOT NULL,
  `pesandetail_id` int(11) NOT NULL,
  `cek_barang` decimal(10,0) NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `is_correct` tinyint(4) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`belidetail_id`),
  KEY `pembelian_id` (`pembelian_id`) USING BTREE,
  KEY `pesandetail_id` (`pesandetail_id`),
  KEY `supplier_id` (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=229 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.pembelian_detail: ~26 rows (approximately)
DELETE FROM `pembelian_detail`;
INSERT INTO `pembelian_detail` (`belidetail_id`, `pembelian_id`, `pesandetail_id`, `cek_barang`, `total_biaya`, `supplier_id`, `catatan`, `is_correct`, `created_at`, `updated_at`) VALUES
	(203, 217, 272, 15000, 210000, 1, NULL, 1, '2024-12-11 10:00:34', NULL),
	(204, 217, 273, 15000, 225000, 2, NULL, 1, '2024-12-11 10:00:34', NULL),
	(205, 217, 274, 15000, 75000, 3, NULL, 1, '2024-12-11 10:00:34', NULL),
	(206, 220, 275, 0, 0, 1, NULL, 1, '2025-07-25 10:03:37', NULL),
	(207, 221, 276, 15000, 60000, 1, NULL, 1, '2025-08-03 10:51:23', NULL),
	(208, 223, 277, 0, 0, 1, NULL, 1, '2025-09-08 21:40:23', NULL),
	(209, 229, 278, 6000, 18000, 1, NULL, 1, '2025-09-10 18:25:38', NULL),
	(210, 230, 279, 1000, 1000, 1, NULL, 1, '2025-09-10 18:26:04', NULL),
	(211, 230, 280, 2000, 4000, 1, NULL, 1, '2025-09-10 18:26:04', NULL),
	(212, 236, 281, 100000, 100000, 1, NULL, 1, '2025-09-10 19:23:39', NULL),
	(213, 240, 282, 0, 0, 1, NULL, 1, '2025-09-15 10:47:07', NULL),
	(214, 243, 283, 1000, 10000, 1, NULL, 1, '2025-09-20 09:36:18', NULL),
	(215, 243, 284, 8000, 56000, 1, NULL, 1, '2025-09-20 09:36:18', NULL),
	(216, 244, 285, 7000, 77000, 2, NULL, 1, '2025-09-20 09:37:13', NULL),
	(217, 245, 286, 4000, 20000, 2, NULL, 1, '2025-09-20 10:58:15', NULL),
	(218, 246, 287, 7000, 49000, 3, NULL, 1, '2025-09-20 10:59:43', NULL),
	(219, 247, 288, 4000, 16000, 1, NULL, 1, '2025-09-20 11:03:05', NULL),
	(220, 248, 289, 10000, 40000, 1, NULL, 1, '2025-09-22 00:01:21', NULL),
	(221, 262, 290, 0, 0, 0, NULL, 0, '2025-10-11 02:19:33', NULL),
	(222, 262, 291, 0, 0, 0, NULL, 0, '2025-10-11 02:19:34', NULL),
	(223, 270, 292, 5500, 275000, 3, NULL, 1, '2025-10-25 00:15:21', NULL),
	(224, 270, 293, 5500, 275000, 3, NULL, 1, '2025-10-25 00:15:21', NULL),
	(225, 282, 294, 50000, 300000, 3, NULL, 1, '2025-10-27 13:17:47', NULL),
	(226, 283, 295, 50000, 300000, 2, NULL, 1, '2025-10-27 13:20:23', NULL),
	(227, 286, 296, 0, 0, 0, NULL, 0, '2025-10-27 13:35:48', NULL),
	(228, 291, 297, 0, 0, 0, NULL, 0, '2025-10-28 21:22:21', NULL);

-- Dumping structure for table inventaris_web.pemesanan
CREATE TABLE IF NOT EXISTS `pemesanan` (
  `pemesanan_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `permintaan_id` int(11) DEFAULT NULL,
  `stock_rop_id` int(11) DEFAULT NULL,
  `tanggal` date NOT NULL,
  `total_item` float NOT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pemesanan_id`),
  KEY `user_id` (`user_id`),
  KEY `permintaan_id` (`permintaan_id`),
  KEY `stock_rop_id` (`stock_rop_id`),
  CONSTRAINT `FK_pemesanan_permintaan_pelanggan` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaan_pelanggan` (`permintaan_id`),
  CONSTRAINT `FK_pemesanan_stock_rop` FOREIGN KEY (`stock_rop_id`) REFERENCES `stock_rop` (`stock_rop_id`),
  CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=383 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.pemesanan: ~35 rows (approximately)
DELETE FROM `pemesanan`;
INSERT INTO `pemesanan` (`pemesanan_id`, `user_id`, `permintaan_id`, `stock_rop_id`, `tanggal`, `total_item`, `status`, `created_at`, `updated_at`) VALUES
	(308, 1, NULL, NULL, '2024-12-11', 3, 2, '2024-12-11 16:59:56', '2024-12-11 17:03:28'),
	(311, 1, NULL, NULL, '2025-07-25', 1, 1, '2025-07-25 17:02:39', '2025-09-19 18:09:33'),
	(312, 1, NULL, NULL, '2025-08-03', 1, 2, '2025-08-03 17:50:00', '2025-09-10 01:09:30'),
	(314, 1, NULL, NULL, '2025-09-09', 1, 1, '2025-09-09 04:40:02', '2025-09-10 01:08:35'),
	(318, 1, NULL, NULL, '2025-09-10', 0, 0, '2025-09-10 02:45:23', '2025-09-10 02:45:23'),
	(320, 1, NULL, NULL, '2025-09-11', 1, 1, '2025-09-11 01:25:22', '2025-09-20 17:18:26'),
	(321, 1, NULL, NULL, '2025-09-11', 2, 1, '2025-09-11 01:25:47', '2025-09-20 17:16:36'),
	(322, 1, NULL, NULL, '2025-09-11', 0, 0, '2025-09-11 01:27:36', '2025-09-11 01:27:36'),
	(327, 1, NULL, NULL, '2025-09-11', 1, 1, '2025-09-11 02:23:31', '2025-09-22 07:06:31'),
	(330, 1, NULL, NULL, '2025-09-15', 0, 0, '2025-09-15 15:29:22', '2025-09-15 15:29:22'),
	(331, 1, NULL, NULL, '2025-09-15', 1, 2, '2025-09-15 17:46:30', '2025-09-20 17:57:16'),
	(334, 1, NULL, NULL, '2025-09-20', 2, 2, '2025-09-20 16:35:46', '2025-09-20 17:37:21'),
	(335, 1, NULL, NULL, '2025-09-20', 1, 2, '2025-09-20 16:37:01', '2025-09-20 17:36:10'),
	(336, 1, NULL, NULL, '2025-09-20', 1, 2, '2025-09-20 17:57:58', '2025-09-20 17:58:57'),
	(337, 1, NULL, NULL, '2025-09-20', 1, 2, '2025-09-20 17:59:27', '2025-09-20 18:00:22'),
	(338, 1, NULL, NULL, '2025-09-20', 1, 2, '2025-09-20 18:02:47', '2025-09-20 18:03:46'),
	(339, 1, NULL, NULL, '2025-09-22', 1, 0, '2025-09-22 07:01:08', '2025-09-22 07:01:21'),
	(346, 1, NULL, NULL, '2025-09-23', 0, 0, '2025-09-23 07:26:37', '2025-09-23 07:26:37'),
	(347, 1, NULL, NULL, '2025-09-23', 0, 0, '2025-09-23 07:26:44', '2025-09-23 07:26:44'),
	(352, 1, NULL, NULL, '2025-10-11', 0, 0, '2025-10-11 08:56:21', '2025-10-11 08:56:21'),
	(353, 1, NULL, NULL, '2025-10-11', 2, 0, '2025-10-11 09:18:45', '2025-10-11 09:19:33'),
	(355, 1, NULL, NULL, '2025-10-14', 0, 0, '2025-10-14 19:18:35', '2025-10-14 19:18:35'),
	(361, 1, NULL, NULL, '2025-10-25', 2, 2, '2025-10-25 07:14:48', '2025-10-25 07:16:45'),
	(363, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 19:50:27', '2025-10-27 19:50:27'),
	(364, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 19:54:04', '2025-10-27 19:54:04'),
	(366, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:00:50', '2025-10-27 20:00:50'),
	(367, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:02:57', '2025-10-27 20:02:57'),
	(368, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:05:26', '2025-10-27 20:05:26'),
	(369, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:08:57', '2025-10-27 20:08:57'),
	(370, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:10:07', '2025-10-27 20:10:07'),
	(371, 1, 5, NULL, '2025-10-27', 0, 0, '2025-10-27 20:11:44', '2025-10-27 20:11:44'),
	(373, 1, 5, NULL, '2025-10-27', 1, 2, '2025-10-27 20:13:43', '2025-10-27 20:19:14'),
	(374, 1, 5, NULL, '2025-10-27', 1, 2, '2025-10-27 20:20:18', '2025-10-27 20:24:37'),
	(377, 1, 6, NULL, '2025-10-27', 1, 0, '2025-10-27 20:35:05', '2025-10-27 20:35:48'),
	(382, 1, NULL, 35, '2025-10-29', 1, 0, '2025-10-29 04:17:25', '2025-10-29 04:22:21');

-- Dumping structure for table inventaris_web.penggunaan
CREATE TABLE IF NOT EXISTS `penggunaan` (
  `penggunaan_id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `total_item_penggunaan` int(11) NOT NULL,
  `status_penggunaan` int(11) NOT NULL DEFAULT 0 COMMENT '0 = pending, 1 =approve',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `tanggal` date NOT NULL,
  PRIMARY KEY (`penggunaan_id`),
  KEY `user_id` (`user_id`),
  KEY `permintaan_id` (`permintaan_id`),
  CONSTRAINT `FK_penggunaan_permintaan_pelanggan` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaan_pelanggan` (`permintaan_id`),
  CONSTRAINT `FK_penggunaan_user` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.penggunaan: ~16 rows (approximately)
DELETE FROM `penggunaan`;
INSERT INTO `penggunaan` (`penggunaan_id`, `permintaan_id`, `user_id`, `total_item_penggunaan`, `status_penggunaan`, `created_at`, `updated_at`, `tanggal`) VALUES
	(34, NULL, 1, 1, 1, '2025-09-27 04:18:40', '2025-09-28 15:49:33', '2025-09-27'),
	(35, NULL, 1, 3, 1, '2025-09-27 04:41:43', '2025-09-27 06:48:45', '2025-09-27'),
	(36, NULL, 1, 1, 1, '2025-09-28 15:50:15', '2025-09-29 07:11:29', '2025-09-28'),
	(37, NULL, 1, 1, 1, '2025-09-28 15:51:14', '2025-09-28 15:54:19', '2025-09-28'),
	(38, NULL, 1, 1, 1, '2025-09-28 15:58:59', '2025-09-28 15:59:22', '2025-09-28'),
	(39, NULL, 1, 1, 1, '2025-09-29 07:17:42', '2025-09-29 07:18:03', '2025-09-29'),
	(40, NULL, 1, 1, 1, '2025-09-29 07:24:41', '2025-09-29 07:25:18', '2025-09-29'),
	(41, NULL, 1, 1, 1, '2025-09-29 07:33:25', '2025-09-29 07:33:39', '2025-09-29'),
	(44, NULL, 7, 2, 1, '2025-10-20 06:30:55', '2025-10-20 07:06:04', '2025-10-20'),
	(45, NULL, 1, 2, 0, '2025-10-23 15:01:30', '2025-10-23 15:01:51', '2025-10-23'),
	(51, NULL, 1, 0, 0, '2025-10-25 02:54:57', '2025-10-25 02:54:57', '2025-10-25'),
	(53, NULL, 1, 0, 0, '2025-10-25 06:46:07', '2025-10-25 06:46:07', '2025-10-25'),
	(54, NULL, 1, 0, 0, '2025-10-25 06:46:24', '2025-10-25 06:46:24', '2025-10-25'),
	(55, NULL, 1, 0, 0, '2025-10-25 06:50:07', '2025-10-25 06:50:07', '2025-10-25'),
	(56, NULL, 1, 0, 0, '2025-10-25 06:51:36', '2025-10-25 06:51:36', '2025-10-25'),
	(65, 9, 1, 2, 1, '2025-10-25 07:46:30', '2025-10-25 07:46:57', '2025-10-25');

-- Dumping structure for table inventaris_web.penggunaan_detail
CREATE TABLE IF NOT EXISTS `penggunaan_detail` (
  `gunadetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `penggunaan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `id_gudang` int(11) DEFAULT NULL,
  `jumlah_digunakan` int(11) NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`gunadetail_id`),
  KEY `barang_id` (`barang_id`),
  KEY `gudang_id` (`id_gudang`),
  KEY `pengggunaan_id` (`penggunaan_id`) USING BTREE,
  CONSTRAINT `FK_penggunaan_detail_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  CONSTRAINT `FK_penggunaan_detail_gudang` FOREIGN KEY (`id_gudang`) REFERENCES `gudang` (`id_gudang`),
  CONSTRAINT `FK_penggunaan_detail_penggunaan` FOREIGN KEY (`penggunaan_id`) REFERENCES `penggunaan` (`penggunaan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.penggunaan_detail: ~16 rows (approximately)
DELETE FROM `penggunaan_detail`;
INSERT INTO `penggunaan_detail` (`gunadetail_id`, `penggunaan_id`, `barang_id`, `id_gudang`, `jumlah_digunakan`, `catatan`, `created_at`, `updated_at`) VALUES
	(6, 35, 1, NULL, 5, 'dafds', '2025-09-27 06:48:44', '2025-09-27 06:48:44'),
	(7, 35, 4, NULL, 2, 'tes', '2025-09-27 06:48:45', '2025-09-27 06:48:45'),
	(8, 35, 4, NULL, 1, 'tes', '2025-09-27 06:48:45', '2025-09-27 06:48:45'),
	(21, 34, 1, NULL, 1, 'g', '2025-09-28 15:49:33', '2025-09-28 15:49:33'),
	(24, 37, 1, NULL, 1, 'n', '2025-09-28 15:54:19', '2025-09-28 15:54:19'),
	(26, 38, 1, NULL, 2, 'vv', '2025-09-28 15:59:22', '2025-09-28 15:59:22'),
	(27, 36, 9, NULL, 1, 'lkldk', '2025-09-29 07:11:29', '2025-09-29 07:11:29'),
	(29, 39, 9, 235, 1, 'testttt', '2025-09-29 07:18:03', '2025-09-29 07:18:03'),
	(31, 40, 9, 235, 2, 'lagii', '2025-09-29 07:25:18', '2025-09-29 07:25:18'),
	(33, 41, 9, 242, 2, 'bxbx', '2025-09-29 07:33:39', '2025-09-29 07:33:39'),
	(35, 44, 4, 240, 2, 'f', '2025-10-20 07:06:04', '2025-10-20 07:06:04'),
	(36, 44, 4, 241, 2, 'f', '2025-10-20 07:06:04', '2025-10-20 07:06:04'),
	(37, 45, 10, NULL, 1, 'f', '2025-10-23 15:01:51', '2025-10-23 15:01:51'),
	(38, 45, 11, NULL, 3, 's', '2025-10-23 15:01:51', '2025-10-23 15:01:51'),
	(45, 65, 1, 252, 20, 'Digunakan Untuk Permintaan : PP-009', '2025-10-25 07:46:56', '2025-10-25 07:46:56'),
	(46, 65, 9, 251, 28, 'Digunakan Untuk Permintaan : PP-009', '2025-10-25 07:46:56', '2025-10-25 07:46:56');

-- Dumping structure for table inventaris_web.permintaan_detail
CREATE TABLE IF NOT EXISTS `permintaan_detail` (
  `permintaan_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `permintaan_id` int(11) NOT NULL,
  `barang_produksi_id` int(11) DEFAULT NULL,
  `barang_custom_pelanggan_id` int(11) DEFAULT NULL,
  `qty_permintaan` int(11) DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`permintaan_detail_id`) USING BTREE,
  KEY `barang_produksi_id` (`barang_produksi_id`),
  KEY `barang_custom_pelanggan_id` (`barang_custom_pelanggan_id`),
  KEY `permintaan_penjualan_id` (`permintaan_id`) USING BTREE,
  CONSTRAINT `FK_detail_permintaan_barangproduksi` FOREIGN KEY (`barang_produksi_id`) REFERENCES `barangproduksi` (`barang_produksi_id`),
  CONSTRAINT `FK_permintaan_detail_barang_custom_pelanggan` FOREIGN KEY (`barang_custom_pelanggan_id`) REFERENCES `barang_custom_pelanggan` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_permintaan_detail_permintaan_pelanggan` FOREIGN KEY (`permintaan_id`) REFERENCES `permintaan_pelanggan` (`permintaan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.permintaan_detail: ~6 rows (approximately)
DELETE FROM `permintaan_detail`;
INSERT INTO `permintaan_detail` (`permintaan_detail_id`, `permintaan_id`, `barang_produksi_id`, `barang_custom_pelanggan_id`, `qty_permintaan`, `catatan`) VALUES
	(11, 7, NULL, 6, 50, ''),
	(12, 8, NULL, 6, 1, ''),
	(13, 9, 7, NULL, 4, 'd'),
	(17, 6, NULL, 6, 1, 'f'),
	(18, 5, NULL, 6, 2, 'ev'),
	(20, 11, 7, NULL, 2, 'tes');

-- Dumping structure for table inventaris_web.permintaan_pelanggan
CREATE TABLE IF NOT EXISTS `permintaan_pelanggan` (
  `permintaan_id` int(11) NOT NULL AUTO_INCREMENT,
  `pelanggan_id` int(11) DEFAULT NULL,
  `kode_permintaan` varchar(255) NOT NULL,
  `tipe_pelanggan` tinyint(4) NOT NULL DEFAULT 1 COMMENT '1=custom, 2=polos',
  `total_item_permintaan` int(11) DEFAULT NULL,
  `tanggal_permintaan` date NOT NULL,
  `status_permintaan` tinyint(4) NOT NULL COMMENT '0 pending, 1 on progress, 2 complete',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`permintaan_id`) USING BTREE,
  KEY `pelanggan_id` (`pelanggan_id`),
  CONSTRAINT `FK_permintaan_penjualan_master_pelanggan` FOREIGN KEY (`pelanggan_id`) REFERENCES `master_pelanggan` (`pelanggan_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.permintaan_pelanggan: ~6 rows (approximately)
DELETE FROM `permintaan_pelanggan`;
INSERT INTO `permintaan_pelanggan` (`permintaan_id`, `pelanggan_id`, `kode_permintaan`, `tipe_pelanggan`, `total_item_permintaan`, `tanggal_permintaan`, `status_permintaan`, `created_at`, `updated_at`) VALUES
	(5, 1, 'PP-005', 1, 1, '2025-10-20', 3, '0000-00-00 00:00:00', '2025-11-08 17:54:26'),
	(6, 1, 'PP-006', 1, 1, '2025-10-14', 0, '0000-00-00 00:00:00', '2025-10-25 08:50:38'),
	(7, 1, 'PP-007', 1, 1, '2025-10-22', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(8, 1, 'PP-008', 1, 1, '2025-10-25', 0, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(9, 1, 'PP-009', 2, 1, '2025-10-25', 1, '0000-00-00 00:00:00', '0000-00-00 00:00:00'),
	(11, 1, 'PP-010', 2, 1, '2025-10-28', 3, '2025-10-27 20:42:00', '2025-11-08 17:54:26');

-- Dumping structure for table inventaris_web.pesan_detail
CREATE TABLE IF NOT EXISTS `pesan_detail` (
  `pesandetail_id` int(11) NOT NULL AUTO_INCREMENT,
  `pemesanan_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `qty` float NOT NULL,
  `qty_terima` float DEFAULT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `langsung_pakai` tinyint(4) NOT NULL DEFAULT 0,
  `is_correct` tinyint(4) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`pesandetail_id`),
  KEY `pemesanan_id` (`pemesanan_id`),
  KEY `barang_id` (`barang_id`),
  CONSTRAINT `pesan_detail_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`pemesanan_id`),
  CONSTRAINT `pesan_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=298 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.pesan_detail: ~26 rows (approximately)
DELETE FROM `pesan_detail`;
INSERT INTO `pesan_detail` (`pesandetail_id`, `pemesanan_id`, `barang_id`, `qty`, `qty_terima`, `catatan`, `langsung_pakai`, `is_correct`, `created_at`, `update_at`) VALUES
	(272, 308, 1, 14, 14, '', 1, 1, '2024-12-11 17:00:34', '2024-12-11 17:03:26'),
	(273, 308, 9, 15, 15, '', 0, 1, '2024-12-11 17:00:34', '2024-12-11 17:03:26'),
	(274, 308, 14, 5, 5, '', 1, 1, '2024-12-11 17:00:34', '2024-12-11 17:03:26'),
	(275, 311, 1, 6, 0, 'ffrrs', 0, 0, '2025-07-25 17:03:36', '2025-07-25 17:03:36'),
	(276, 312, 9, 4, 7, 'k', 0, 1, '2025-08-03 17:51:23', '2025-09-10 01:09:14'),
	(277, 314, 11, 3, 5, '-', 0, 0, '2025-09-09 04:40:22', '2025-09-10 01:08:35'),
	(278, 320, 10, 3, 0, 'dssda', 0, 0, '2025-09-11 01:25:38', '2025-09-11 01:25:38'),
	(279, 321, 9, 1, 0, 'tes', 0, 0, '2025-09-11 01:26:04', '2025-09-11 01:26:04'),
	(280, 321, 11, 2, 0, 'tessss', 0, 0, '2025-09-11 01:26:04', '2025-09-11 01:26:04'),
	(281, 327, 9, 1, 1, 'fsvs', 0, 1, '2025-09-11 02:23:39', '2025-09-22 07:06:31'),
	(282, 331, 4, 0, 0, 'ojad', 0, 1, '2025-09-15 17:47:07', '2025-09-20 17:57:13'),
	(283, 334, 1, 10, 10, 'coba', 0, 1, '2025-09-20 16:36:17', '2025-09-20 17:37:17'),
	(284, 334, 4, 7, 8, 'cc', 0, 1, '2025-09-20 16:36:17', '2025-09-20 17:37:17'),
	(285, 335, 1, 11, 11, '', 0, 1, '2025-09-20 16:37:12', '2025-09-20 17:36:05'),
	(286, 336, 4, 5, 5, 'pakeee', 1, 1, '2025-09-20 17:58:15', '2025-09-20 17:58:54'),
	(287, 337, 1, 7, 7, 'opkkkeee', 1, 1, '2025-09-20 17:59:43', '2025-09-20 18:00:18'),
	(288, 338, 9, 4, 4, 'gas pke', 1, 1, '2025-09-20 18:03:05', '2025-09-20 18:03:43'),
	(289, 339, 9, 4, 0, 'p', 0, 0, '2025-09-22 07:01:21', '2025-09-22 07:01:21'),
	(290, 353, 9, 1, 0, 't', 0, 0, '2025-10-11 09:19:33', '2025-10-11 09:19:33'),
	(291, 353, 9, 2, 0, 'f', 0, 0, '2025-10-11 09:19:33', '2025-10-11 09:19:33'),
	(292, 361, 9, 50, 50, 'buat test', 0, 1, '2025-10-25 07:15:20', '2025-10-25 07:16:41'),
	(293, 361, 1, 50, 50, 'buat test', 0, 1, '2025-10-25 07:15:20', '2025-10-25 07:16:41'),
	(294, 373, 4, 6, 6, 'Digunakan Untuk Permintaan : PP-005', 1, 1, '2025-10-27 20:17:47', '2025-10-27 20:19:08'),
	(295, 374, 4, 6, 6, 'Digunakan Untuk Permintaan : PP-005', 1, 1, '2025-10-27 20:20:23', '2025-10-27 20:24:34'),
	(296, 377, 4, 3, 0, 'Digunakan Untuk Permintaan : PP-006', 1, 0, '2025-10-27 20:35:48', '2025-10-27 20:35:48'),
	(297, 382, 9, 200, 0, 'Pemesanan berdasarkan ROP periode Oktober 2025 (Stock: 24, ROP: 221)', 0, 0, '2025-10-29 04:22:21', '2025-10-29 04:22:21');

-- Dumping structure for table inventaris_web.report
CREATE TABLE IF NOT EXISTS `report` (
  `report_id` int(11) NOT NULL,
  PRIMARY KEY (`report_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.report: ~0 rows (approximately)
DELETE FROM `report`;

-- Dumping structure for table inventaris_web.riwayat_penjualan
CREATE TABLE IF NOT EXISTS `riwayat_penjualan` (
  `riwayat_penjualan_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_produksi_id` int(11) DEFAULT NULL,
  `barang_custom_pelanggan_id` int(11) DEFAULT NULL,
  `qty_penjualan` int(11) NOT NULL,
  `bulan_periode` varchar(7) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`riwayat_penjualan_id`),
  KEY `barang_produksi_id` (`barang_produksi_id`),
  KEY `barang_custom_pelanggan_id` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_riwayat_penjualan_barang_custom_pelanggan` FOREIGN KEY (`barang_custom_pelanggan_id`) REFERENCES `barang_custom_pelanggan` (`barang_custom_pelanggan_id`),
  CONSTRAINT `FK_riwayat_penjualan_barangproduksi` FOREIGN KEY (`barang_produksi_id`) REFERENCES `barangproduksi` (`barang_produksi_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.riwayat_penjualan: ~32 rows (approximately)
DELETE FROM `riwayat_penjualan`;
INSERT INTO `riwayat_penjualan` (`riwayat_penjualan_id`, `barang_produksi_id`, `barang_custom_pelanggan_id`, `qty_penjualan`, `bulan_periode`, `created_at`, `updated_at`) VALUES
	(1, 7, NULL, 856, '202408', '2025-10-11 13:41:49', NULL),
	(2, 10, NULL, 750, '202408', '2025-10-11 13:53:54', NULL),
	(3, 7, NULL, 800, '202409', '2025-10-11 13:54:52', NULL),
	(4, 10, NULL, 852, '202409', '2025-10-11 14:09:29', NULL),
	(5, 7, NULL, 672, '202410', '2025-10-11 14:10:07', NULL),
	(6, 10, NULL, 763, '202410', '2025-10-11 14:10:07', NULL),
	(7, 7, NULL, 852, '202411', '2025-10-11 14:10:37', NULL),
	(8, 10, NULL, 900, '202411', '2025-10-11 14:10:38', NULL),
	(10, 7, NULL, 780, '202412', '2025-10-11 09:38:11', NULL),
	(12, 10, NULL, 764, '202412', '2025-10-11 09:43:30', NULL),
	(13, 7, NULL, 679, '202501', '2025-10-11 09:43:30', NULL),
	(14, 10, NULL, 890, '202501', '2025-10-11 09:43:30', NULL),
	(15, 7, NULL, 762, '202502', '2025-10-11 09:46:13', NULL),
	(16, 10, NULL, 850, '202502', '2025-10-11 09:46:13', NULL),
	(17, 7, NULL, 680, '202503', '2025-10-11 09:46:13', NULL),
	(18, 10, NULL, 790, '202503', '2025-10-11 09:46:13', NULL),
	(19, 7, NULL, 590, '202503', '2025-10-11 09:46:13', NULL),
	(20, 10, NULL, 690, '202504', '2025-10-11 09:46:13', NULL),
	(21, 7, NULL, 860, '202504', '2025-10-11 09:46:13', NULL),
	(22, 10, NULL, 758, '202504', '2025-10-11 09:46:13', NULL),
	(23, 7, NULL, 865, '202505', '2025-10-11 09:46:13', NULL),
	(24, 10, NULL, 785, '202505', '2025-10-11 09:46:13', NULL),
	(25, 7, NULL, 777, '202506', '2025-10-11 09:46:13', NULL),
	(26, 10, NULL, 854, '202506', '2025-10-11 09:46:13', NULL),
	(27, 7, NULL, 989, '202507', '2025-10-11 09:46:13', NULL),
	(28, 10, NULL, 958, '202507', '2025-10-11 09:46:13', NULL),
	(29, 7, NULL, 763, '202508', '2025-10-12 12:45:11', NULL),
	(30, 10, NULL, 792, '202508', '2025-10-12 12:45:25', NULL),
	(36, 7, NULL, 890, '202509', '2025-10-14 02:56:55', NULL),
	(37, 10, NULL, 988, '202509', '2025-10-14 02:57:19', NULL),
	(38, NULL, 6, 2, '202510', '2025-11-08 10:54:26', NULL),
	(39, 7, NULL, 2, '202510', '2025-11-08 10:54:26', NULL);

-- Dumping structure for table inventaris_web.role
CREATE TABLE IF NOT EXISTS `role` (
  `id_role` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  PRIMARY KEY (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.role: ~5 rows (approximately)
DELETE FROM `role`;
INSERT INTO `role` (`id_role`, `nama`) VALUES
	(1, 'Super Admin'),
	(2, 'Admin'),
	(3, 'Operator'),
	(4, 'Gudang'),
	(5, 'Admin1');

-- Dumping structure for table inventaris_web.shift
CREATE TABLE IF NOT EXISTS `shift` (
  `shift_id` int(11) NOT NULL AUTO_INCREMENT,
  `tanggal` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift` enum('1','2') NOT NULL,
  `waktu_kerja` decimal(4,2) NOT NULL,
  `nama_operator` varchar(200) NOT NULL,
  `mulai_istirahat` time NOT NULL,
  `selesai_istirahat` time NOT NULL,
  `kendala` text NOT NULL,
  `ganti_benang` int(11) DEFAULT NULL,
  `ganti_kain` int(11) DEFAULT NULL,
  PRIMARY KEY (`shift_id`),
  KEY `user_id` (`user_id`) USING BTREE,
  CONSTRAINT `shift_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=41 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.shift: ~3 rows (approximately)
DELETE FROM `shift`;
INSERT INTO `shift` (`shift_id`, `tanggal`, `user_id`, `shift`, `waktu_kerja`, `nama_operator`, `mulai_istirahat`, `selesai_istirahat`, `kendala`, `ganti_benang`, `ganti_kain`) VALUES
	(38, '2024-12-10', 1, '1', 1.00, 'Dani', '12:00:00', '13:00:00', 'Tidak Ada', 12, 1),
	(39, '2024-12-11', 1, '1', 0.33, 'Yani', '12:00:00', '13:00:00', 'Tidak Ada', 2, 4),
	(40, '2025-01-08', 1, '1', 1.00, 'Dani', '12:00:00', '13:00:00', 'Tidak Ada', NULL, NULL);

-- Dumping structure for table inventaris_web.stock
CREATE TABLE IF NOT EXISTS `stock` (
  `stock_id` int(11) NOT NULL AUTO_INCREMENT,
  `tambah_stock` date NOT NULL,
  `barang_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`stock_id`),
  KEY `barang_id` (`barang_id`) USING BTREE,
  KEY `user_id` (`user_id`),
  CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=130 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.stock: ~19 rows (approximately)
DELETE FROM `stock`;
INSERT INTO `stock` (`stock_id`, `tambah_stock`, `barang_id`, `user_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `created_at`, `updated_at`) VALUES
	(111, '2024-11-09', 14, 1, 0, 15, 0, 15, '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
	(112, '2024-11-09', 3, 1, 0, 16, 0, 16, '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
	(113, '2024-11-09', 19, 2, 0, 5, 0, 5, '2024-11-09 08:20:26', '2024-11-09 08:20:26'),
	(114, '2024-11-09', 19, 1, 5, 2, 0, 7, '2024-11-09 08:20:26', '2024-11-09 08:20:26'),
	(115, '2024-12-10', 4, 1, 0, 15, 0, 15, '2024-12-10 17:23:26', '2024-12-10 17:23:26'),
	(116, '2024-12-10', 14, 1, 15, 20, 0, 35, '2024-12-10 17:23:26', '2024-12-10 17:23:26'),
	(117, '2024-12-10', 11, 6, 0, 4, 0, 4, '2024-12-10 17:28:33', '2024-12-10 17:28:33'),
	(118, '2024-12-11', 1, 1, 0, 14, 0, 14, '2024-12-11 17:03:28', '2024-12-11 17:03:28'),
	(119, '2024-12-11', 14, 1, 35, 5, 0, 40, '2024-12-11 17:03:28', '2024-12-11 17:03:28'),
	(120, '2024-12-11', 9, 2, 0, 1, 0, 1, '2024-12-11 17:04:10', '2024-12-11 17:04:10'),
	(121, '2024-12-11', 11, 5, 4, 12, 0, 16, '2024-12-11 17:04:10', '2024-12-11 17:04:10'),
	(122, '2025-07-20', 9, 1, 1, 9, 0, 10, '2025-07-20 16:23:29', '2025-07-20 16:23:29'),
	(123, '2025-07-20', 9, 1, 10, 0, 5, 5, '2025-07-20 16:25:59', '2025-07-20 16:25:59'),
	(124, '2025-07-25', 11, 2, 16, 1, 0, 17, '2025-07-25 17:04:38', '2025-07-25 17:04:38'),
	(125, '2025-07-25', 9, 2, 5, 1, 0, 6, '2025-07-25 17:29:37', '2025-07-25 17:29:37'),
	(126, '2025-07-25', 9, 2, 6, 0, 1, 5, '2025-07-25 17:30:40', '2025-07-25 17:30:40'),
	(127, '2025-08-03', 9, 1, 5, 1, 0, 6, '2025-08-03 16:59:44', '2025-08-03 16:59:44'),
	(128, '2025-09-10', 9, 1, 6, 1, 0, 7, '2025-09-10 01:01:35', '2025-09-10 01:01:35'),
	(129, '2025-09-10', 4, 1, 15, 2, 0, 17, '2025-09-10 01:01:35', '2025-09-10 01:01:35');

-- Dumping structure for table inventaris_web.stock_rop
CREATE TABLE IF NOT EXISTS `stock_rop` (
  `stock_rop_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `periode` varchar(7) DEFAULT NULL,
  `stock_barang` int(11) DEFAULT NULL,
  `safety_stock` int(11) DEFAULT NULL,
  `jumlah_eoq` int(11) DEFAULT NULL,
  `jumlah_rop` int(11) DEFAULT NULL,
  `pesan_barang` tinyint(4) DEFAULT 0 COMMENT '0=tidak, 1=ya',
  PRIMARY KEY (`stock_rop_id`),
  KEY `barang_id` (`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.stock_rop: ~4 rows (approximately)
DELETE FROM `stock_rop`;
INSERT INTO `stock_rop` (`stock_rop_id`, `barang_id`, `periode`, `stock_barang`, `safety_stock`, `jumlah_eoq`, `jumlah_rop`, `pesan_barang`) VALUES
	(33, 1, '202510', 41, 20, 203, 450, 0),
	(34, 4, '202510', 41, 10, 103, 186, 0),
	(35, 9, '202510', 24, 20, 200, 221, 0),
	(36, 11, '202510', 18, 15, 66, 44, 0);

-- Dumping structure for table inventaris_web.supplier
CREATE TABLE IF NOT EXISTS `supplier` (
  `supplier_id` int(11) NOT NULL AUTO_INCREMENT,
  `nama` varchar(200) NOT NULL,
  `notelfon` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `kota` varchar(200) NOT NULL,
  `kodepos` int(11) NOT NULL,
  `lead_time` int(11) NOT NULL,
  PRIMARY KEY (`supplier_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.supplier: ~12 rows (approximately)
DELETE FROM `supplier`;
INSERT INTO `supplier` (`supplier_id`, `nama`, `notelfon`, `alamat`, `kota`, `kodepos`, `lead_time`) VALUES
	(1, 'Toko Sumber Jaya', '081252807753', 'Jalan Jaya no 15', 'Surabaya', 22134, 0),
	(2, 'Toko Abadi', '082122224532', 'Jalan Mawar  1/11', 'Jakarta', 60113, 0),
	(3, 'Toko Bahagia Kasih', '098741365897', 'Jl. Krembangan 123', 'Surabaya', 94123, 0),
	(5, 'Bintang Mulia', '081928939223', 'Jl. new', 'Surabaya', 60313, 1),
	(6, 'SOLTEX', '082121723311', 'Jalan baru', 'Bandung', 778219, 3),
	(7, 'SENTRAL MANDIRI', '081234022134', 'Jalan bandung', 'Bandung', 60132, 3),
	(8, 'HONGTEX', '081234512134', 'Jalan jkt', 'Jakarta', 20340, 3),
	(9, 'AJI PITA', '082431718903', 'Jalan Aji', 'Bandung', 32303, 3),
	(10, 'IVAN ', '085234688191', 'Jalan Pann', 'Bandung', 26611, 3),
	(11, 'UD FAVOURITE', '085318281902', 'Jalan bang', 'Jombang', 60134, 2),
	(12, 'WARNA BENANG', '081236792911', 'Jl. Baya', 'Surabaya', 60132, 1),
	(13, 'TOKO MATAHARI', '083516271293', 'Jl. Unamed', 'Bandung', 60138, 3);

-- Dumping structure for table inventaris_web.supplier_barang
CREATE TABLE IF NOT EXISTS `supplier_barang` (
  `supplier_barang_id` int(11) NOT NULL AUTO_INCREMENT,
  `barang_id` int(11) NOT NULL,
  `total_supplier_barang` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supplier_barang_id`) USING BTREE,
  KEY `barang_id` (`barang_id`),
  KEY `idx_barang_utama` (`barang_id`),
  CONSTRAINT `FK_supplier_barang_barang` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.supplier_barang: ~4 rows (approximately)
DELETE FROM `supplier_barang`;
INSERT INTO `supplier_barang` (`supplier_barang_id`, `barang_id`, `total_supplier_barang`, `created_at`, `updated_at`) VALUES
	(16, 9, 1, '2025-10-18 16:34:21', NULL),
	(17, 11, 2, '2025-10-19 09:47:58', '2025-10-19 03:18:41'),
	(18, 4, 3, '2025-10-19 09:48:31', '2025-10-19 02:48:31'),
	(23, 1, 1, '2025-10-19 16:59:53', '2025-10-19 09:59:53');

-- Dumping structure for table inventaris_web.supplier_barang_detail
CREATE TABLE IF NOT EXISTS `supplier_barang_detail` (
  `supplier_barang_detail_id` int(11) NOT NULL AUTO_INCREMENT,
  `supplier_barang_id` int(11) NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `lead_time` float NOT NULL,
  `harga_per_kg` float NOT NULL DEFAULT 0,
  `biaya_pesan` float NOT NULL DEFAULT 0,
  `supp_utama` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0 = bukan, 1 = utama',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`supplier_barang_detail_id`),
  KEY `supplier_barang_id` (`supplier_barang_id`),
  KEY `supplier_id` (`supplier_id`),
  CONSTRAINT `FK_supplier_barang_detail_supplier` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  CONSTRAINT `FK_supplier_barang_detail_supplier_barang` FOREIGN KEY (`supplier_barang_id`) REFERENCES `supplier_barang` (`supplier_barang_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.supplier_barang_detail: ~8 rows (approximately)
DELETE FROM `supplier_barang_detail`;
INSERT INTO `supplier_barang_detail` (`supplier_barang_detail_id`, `supplier_barang_id`, `supplier_id`, `lead_time`, `harga_per_kg`, `biaya_pesan`, `supp_utama`, `created_at`, `updated_at`) VALUES
	(1, 16, 9, 3, 55000, 10000, 0, NULL, NULL),
	(2, 16, 3, 1, 50000, 10000, 1, NULL, NULL),
	(3, 17, 2, 1, 30000, 10000, 1, NULL, NULL),
	(4, 17, 2, 1, 32000, 15000, 0, NULL, NULL),
	(5, 18, 6, 3, 45000, 10000, 0, NULL, NULL),
	(6, 18, 2, 3, 45000, 15000, 1, NULL, NULL),
	(7, 18, 3, 3, 45000, 15000, 0, NULL, NULL),
	(10, 23, 3, 3, 35000, 12000, 1, NULL, NULL);

-- Dumping structure for table inventaris_web.unit
CREATE TABLE IF NOT EXISTS `unit` (
  `unit_id` int(11) NOT NULL AUTO_INCREMENT,
  `satuan` varchar(11) NOT NULL,
  PRIMARY KEY (`unit_id`) USING BTREE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.unit: ~7 rows (approximately)
DELETE FROM `unit`;
INSERT INTO `unit` (`unit_id`, `satuan`) VALUES
	(1, 'Meter'),
	(5, 'Kilo'),
	(6, 'Centimeter'),
	(7, 'Yard'),
	(8, 'Gulung'),
	(9, 'Unit'),
	(10, 'PCS');

-- Dumping structure for table inventaris_web.user
CREATE TABLE IF NOT EXISTS `user` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT,
  `id_role` int(11) NOT NULL,
  `nama_pengguna` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `kata_sandi` varchar(200) NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` datetime DEFAULT NULL,
  PRIMARY KEY (`user_id`),
  KEY `id_role` (`id_role`),
  CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Dumping data for table inventaris_web.user: ~7 rows (approximately)
DELETE FROM `user`;
INSERT INTO `user` (`user_id`, `id_role`, `nama_pengguna`, `email`, `kata_sandi`, `dibuat_pada`, `diperbarui_pada`) VALUES
	(1, 1, 'user1', 'user1@gmail.com', '123', '2024-08-04 14:59:38', '2024-08-04 14:59:38'),
	(2, 2, 'Jojo', 'jojo@gmail.com', '123', '2024-08-13 09:40:30', '2024-08-13 09:40:30'),
	(3, 3, 'Berttt', 'bert@gmail.com', '123', '2024-08-13 09:40:58', '2024-08-13 12:47:34'),
	(5, 1, 'Felix', 'felix@gmail.com', '123', '2024-08-27 03:00:27', '2024-08-27 03:03:10'),
	(6, 1, 'Christina', 'christina@gmail.com', '123', '2024-09-05 21:23:17', '2024-09-05 21:23:17'),
	(7, 4, 'test', 'test@gmail.com', '123', '2025-09-20 22:44:58', NULL),
	(8, 5, 'admin', 'admin@gmail.com', '123', '2025-09-26 15:28:37', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
