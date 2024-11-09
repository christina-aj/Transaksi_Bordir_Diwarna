-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Nov 09, 2024 at 08:45 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.7

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventaris_web`
--

-- --------------------------------------------------------

--
-- Table structure for table `barang`
--

CREATE TABLE `barang` (
  `barang_id` int NOT NULL,
  `kode_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `angka` float NOT NULL,
  `unit_id` int NOT NULL,
  `tipe` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `warna` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`barang_id`, `kode_barang`, `nama_barang`, `angka`, `unit_id`, `tipe`, `warna`, `created_at`, `updated_at`) VALUES
(1, 'A003', 'Benang Ungu', 5, 1, 'Consumable', 'Oren', NULL, '2024-11-09 05:10:47'),
(3, 'B001', 'Kain Hijau', 6, 1, 'Consumable', 'Hijau', '2024-09-02 16:27:12', '2024-09-17 16:23:19'),
(4, 'A001', 'Benang Merah', 3000, 7, 'Consumable', 'Merah', '2024-08-26 21:37:26', '2024-09-17 15:46:50'),
(5, 'B002', 'Kain Kuning', 3, 1, 'Consumable', 'Kuning', '2024-09-02 15:59:24', '2024-09-17 16:23:29'),
(9, 'A004', 'Benang Polyester', 1000, 8, 'Consumable', 'Putih', '2024-09-17 16:20:33', '2024-09-17 16:20:33'),
(10, 'A005', 'Benang Polyester', 500, 8, 'Consumable', 'Putih', '2024-09-17 16:21:33', '2024-09-17 16:21:33'),
(11, 'A002', 'Benang Rayon', 500, 8, 'Consumable', 'Merah', '2024-09-17 16:22:51', '2024-09-17 16:22:51'),
(12, 'M001', 'Mesin Bordir', 1, 9, 'Non Consumable', 'Silver', '2024-09-17 16:25:12', '2024-09-17 16:25:12'),
(13, 'M002', 'Rangka Bordir', 5, 9, 'Non Consumable', 'Hitam', '2024-09-17 16:25:55', '2024-09-17 16:25:55'),
(14, 'A006', 'Benang Merah', 250, 8, 'Consumable', 'Merah', '2024-09-17 16:34:07', '2024-09-17 16:34:07'),
(19, 'A097', 'Kain Pecah Oren', 16, 8, 'Consumable', 'Tidak ada', '2024-11-05 15:23:00', '2024-11-05 15:23:00'),
(24, 'A999', 'Kain Pecah Oren', 16, 1, 'Consumable', 'Tidak ada', '2024-11-05 15:30:45', '2024-11-05 15:30:45'),
(25, 'A9999', 'Kain sambung merah', 16, 1, 'Consumable', 'Tidak ada', '2024-11-05 15:36:28', '2024-11-05 15:36:28'),
(26, 'A9998', 'Kain sambung biru', 12, 6, 'Consumable', 'Tidak ada', '2024-11-05 15:36:28', '2024-11-05 15:36:28'),
(27, 'A9997', 'Kain sambung hijau', 14, 8, 'Consumable', 'Tidak ada', '2024-11-05 15:36:28', '2024-11-05 15:36:28'),
(37, 'ESTEH123', 'kerangka Badan', 1, 9, 'Non Consumable', '', '2024-11-05 17:24:25', '2024-11-05 17:24:25'),
(38, 'ESTEH124', 'kerangka Mesin', 2, 9, 'Non Consumable', '', '2024-11-05 17:24:25', '2024-11-05 17:24:25'),
(39, 'A1234', 'Kain percah hitam', 15, 1, 'Consumable', 'Hitam', '2024-11-09 04:31:42', '2024-11-09 04:31:42'),
(40, 'A1235', 'Kain percah oren', 15, 5, 'Consumable', 'Oren', '2024-11-09 04:31:42', '2024-11-09 04:31:42'),
(41, 'A12346', 'Kain percah coklat', 12, 6, 'Consumable', 'Coklat', '2024-11-09 04:31:42', '2024-11-09 04:31:42'),
(42, 'A1233', 'jarum hijau', 16, 9, 'Consumable', 'oren', '2024-11-09 04:41:55', '2024-11-09 04:41:55'),
(43, 'A1232', 'jarum kuning', 12, 9, 'Consumable', 'kuning', '2024-11-09 04:41:55', '2024-11-09 04:41:55'),
(44, 'A122', 'jarum coklat', 12, 9, 'Consumable', 'coklat', '2024-11-09 04:46:15', '2024-11-09 04:46:15'),
(45, 'A121', 'jarum pink', 12, 9, 'Consumable', 'pink', '2024-11-09 04:46:15', '2024-11-09 04:46:15'),
(46, 'A111', 'test', 16, 1, 'Consumable', 'Tidak ada', '2024-11-09 04:50:19', '2024-11-09 04:50:19');

-- --------------------------------------------------------

--
-- Table structure for table `barangproduksi`
--

CREATE TABLE `barangproduksi` (
  `barang_id` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `nama_jenis` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `ukuran` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `barangproduksi`
--

INSERT INTO `barangproduksi` (`barang_id`, `nama`, `nama_jenis`, `ukuran`, `deskripsi`) VALUES
(4, 'Baju Merah', 'Baju Lengan Panjan', '25', 'Baju dengan kain katun');

-- --------------------------------------------------------

--
-- Table structure for table `gudang`
--

CREATE TABLE `gudang` (
  `id_gudang` int NOT NULL,
  `tanggal` date NOT NULL,
  `barang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`id_gudang`, `tanggal`, `barang_id`, `user_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `catatan`, `created_at`, `update_at`) VALUES
(67, '2024-11-09', 14, 1, 0, 15, 15, 0, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
(68, '2024-11-09', 19, 1, 0, 20, 0, 20, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
(69, '2024-11-09', 3, 1, 0, 16, 16, 0, 'Verifikasi pemesanan ID: 274', '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
(70, '2024-11-09', 19, 2, 20, 0, 5, 15, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26'),
(71, '2024-11-09', 19, 1, 15, 0, 2, 13, '', '2024-11-09 08:20:26', '2024-11-09 08:20:26');

-- --------------------------------------------------------

--
-- Table structure for table `jenis`
--

CREATE TABLE `jenis` (
  `id` int NOT NULL,
  `nama_jenis` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jenis`
--

INSERT INTO `jenis` (`id`, `nama_jenis`, `deskripsi`) VALUES
(1, 'Baju', 'Tshirt biasa'),
(2, 'Baju Lengan Panjan', 'Baju dengan Lengan Panjang'),
(3, 'Celana Pendek', 'Celana Dengan panjang 20cm');

-- --------------------------------------------------------

--
-- Table structure for table `laporanproduksi`
--

CREATE TABLE `laporanproduksi` (
  `laporan_id` int NOT NULL,
<<<<<<< Updated upstream
  `nama_mesin` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `shift_id` int NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
=======
  `nama_mesin` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `shift_id` int NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
>>>>>>> Stashed changes
  `vs` int NOT NULL DEFAULT '1',
  `stitch` int NOT NULL,
  `kuantitas` int NOT NULL,
  `bs` int NOT NULL,
<<<<<<< Updated upstream
  `nama_barang` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
=======
  `nama_barang` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
>>>>>>> Stashed changes
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Dumping data for table `laporanproduksi`
--

INSERT INTO `laporanproduksi` (`laporan_id`, `nama_mesin`, `shift_id`, `tanggal_kerja`, `nama_kerjaan`, `vs`, `stitch`, `kuantitas`, `bs`, `nama_barang`) VALUES
(26, 'Mesin Bordir', 22, '2024-10-30', 'text1', 21, 21, 1, 1, 'Baju Merah'),
(27, 'Mesin Bordir', 21, '2024-11-02', 'text1', 21, 21, 1, 1, 'Baju Merah'),
(28, 'Mesin Bordir', 21, '2024-11-02', 'text1', 21, 21, 1, 1, 'Baju Merah'),
(29, 'Mesin Bordir', 21, '2024-11-02', 'text11231', 21, 21, 1, 1, 'Baju Merah'),
(30, 'Mesin Bordir', 25, '2025-02-14', 'SD 2 Solo', 1, 1, 2515, 1, 'Baju Merah');

-- --------------------------------------------------------

--
-- Table structure for table `laporan_keluar`
--

CREATE TABLE `laporan_keluar` (
  `id` int NOT NULL,
<<<<<<< Updated upstream
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `barang` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
=======
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `barang` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `qty` int NOT NULL,
  `tanggal` date NOT NULL,
  `catatan` text COLLATE utf8mb4_general_ci NOT NULL
>>>>>>> Stashed changes
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporan_keluar`
--

INSERT INTO `laporan_keluar` (`id`, `nama`, `barang`, `qty`, `tanggal`, `catatan`) VALUES
(1, 'babi12', 'Baju Merah', 2, '2024-10-01', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `mesin`
--

CREATE TABLE `mesin` (
  `mesin_id` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesin`
--

INSERT INTO `mesin` (`mesin_id`, `nama`, `deskripsi`) VALUES
(1, 'Mesin Bordir', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `nota`
--

CREATE TABLE `nota` (
  `nota_id` int NOT NULL,
<<<<<<< Updated upstream
  `nama_konsumen` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `barang` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `harga` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `qty` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
=======
  `nama_konsumen` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `tanggal` date NOT NULL,
  `barang` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `harga` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
  `qty` varchar(255) COLLATE utf8mb4_general_ci NOT NULL,
>>>>>>> Stashed changes
  `total_qty` int NOT NULL,
  `total_harga` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nota`
--

INSERT INTO `nota` (`nota_id`, `nama_konsumen`, `tanggal`, `barang`, `harga`, `qty`, `total_qty`, `total_harga`) VALUES
(18, 'Test21', '2024-10-14', 'Baju Merah,Baju Merah', '1500,1500', '155,151', 306, 459000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `pembelian_id` int NOT NULL,
  `pemesanan_id` int NOT NULL,
  `user_id` int DEFAULT NULL,
  `total_biaya` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`pembelian_id`, `pemesanan_id`, `user_id`, `total_biaya`) VALUES
(181, 272, 1, 0),
(182, 273, 1, 5295000),
(183, 274, 1, 765000);

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `belidetail_id` int NOT NULL,
  `pembelian_id` int NOT NULL,
  `pesandetail_id` int NOT NULL,
  `cek_barang` decimal(10,0) NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `supplier_id` int NOT NULL,
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `is_correct` tinyint NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`belidetail_id`, `pembelian_id`, `pesandetail_id`, `cek_barang`, `total_biaya`, `supplier_id`, `catatan`, `is_correct`, `created_at`, `updated_at`) VALUES
(183, 181, 252, 0, 0, 0, NULL, 0, '2024-11-09 01:14:41', NULL),
(184, 181, 253, 0, 0, 0, NULL, 0, '2024-11-09 01:14:41', NULL),
(185, 181, 254, 0, 0, 0, NULL, 0, '2024-11-09 01:14:41', NULL),
(186, 182, 255, 25000, 375000, 1, NULL, 1, '2024-11-09 01:15:27', NULL),
(187, 182, 256, 140000, 2520000, 2, NULL, 1, '2024-11-09 01:15:27', NULL),
(188, 182, 257, 120000, 2400000, 3, NULL, 1, '2024-11-09 01:15:27', NULL),
(189, 183, 258, 15000, 225000, 1, NULL, 1, '2024-11-09 01:17:48', NULL),
(190, 183, 259, 15000, 300000, 1, NULL, 1, '2024-11-09 01:17:48', NULL),
(191, 183, 260, 15000, 240000, 2, NULL, 1, '2024-11-09 01:17:48', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pemesanan`
--

CREATE TABLE `pemesanan` (
  `pemesanan_id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `total_item` float NOT NULL,
  `status` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemesanan`
--

INSERT INTO `pemesanan` (`pemesanan_id`, `user_id`, `tanggal`, `total_item`, `status`, `created_at`, `updated_at`) VALUES
(272, 1, '2024-11-09', 3, 0, '2024-11-09 08:14:03', '2024-11-09 08:14:41'),
(273, 1, '2024-11-09', 3, 1, '2024-11-09 08:14:56', '2024-11-09 08:16:24'),
(274, 1, '2024-11-09', 3, 2, '2024-11-09 08:17:09', '2024-11-09 08:18:40');

-- --------------------------------------------------------

--
-- Table structure for table `penggunaan`
--

CREATE TABLE `penggunaan` (
  `penggunaan_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `jumlah_digunakan` int NOT NULL,
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_digunakan` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Dumping data for table `penggunaan`
--

INSERT INTO `penggunaan` (`penggunaan_id`, `barang_id`, `user_id`, `jumlah_digunakan`, `catatan`, `tanggal_digunakan`) VALUES
(45, 19, 2, 5, '', '2024-11-09'),
(46, 19, 1, 2, '', '2024-11-09');

-- --------------------------------------------------------

--
-- Table structure for table `pesan_detail`
--

CREATE TABLE `pesan_detail` (
  `pesandetail_id` int NOT NULL,
  `pemesanan_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `qty` float NOT NULL,
  `qty_terima` float DEFAULT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `langsung_pakai` tinyint NOT NULL DEFAULT '0',
  `is_correct` tinyint NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesan_detail`
--

INSERT INTO `pesan_detail` (`pesandetail_id`, `pemesanan_id`, `barang_id`, `qty`, `qty_terima`, `catatan`, `langsung_pakai`, `is_correct`, `created_at`, `update_at`) VALUES
(252, 272, 1, 12, 0, '', 1, 0, '2024-11-09 08:14:41', '2024-11-09 08:14:41'),
(253, 272, 9, 15, 0, '', 0, 0, '2024-11-09 08:14:41', '2024-11-09 08:14:41'),
(254, 272, 10, 12, 0, '', 1, 0, '2024-11-09 08:14:41', '2024-11-09 08:14:41'),
(255, 273, 4, 15, 0, '', 1, 0, '2024-11-09 08:15:27', '2024-11-09 08:15:27'),
(256, 273, 11, 18, 0, '', 0, 0, '2024-11-09 08:15:27', '2024-11-09 08:15:27'),
(257, 273, 14, 20, 0, '', 1, 0, '2024-11-09 08:15:27', '2024-11-09 08:15:27'),
(258, 274, 14, 15, 15, '', 1, 1, '2024-11-09 08:17:47', '2024-11-09 08:18:38'),
(259, 274, 19, 20, 20, '', 0, 1, '2024-11-09 08:17:47', '2024-11-09 08:18:38'),
(260, 274, 3, 16, 16, '', 1, 1, '2024-11-09 08:17:47', '2024-11-09 08:18:38');

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`id_role`, `nama`) VALUES
(1, 'Super Admin'),
(2, 'Admin'),
(3, 'Operator');

-- --------------------------------------------------------

--
-- Table structure for table `shift`
--

CREATE TABLE `shift` (
  `shift_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `user_id` int NOT NULL,
  `shift` enum('1','2') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `waktu_kerja` decimal(4,2) NOT NULL,
  `nama_operator` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `mulai_istirahat` time NOT NULL,
  `selesai_istirahat` time NOT NULL,
  `kendala` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'Tidak Ada',
  `ganti_benang` int NOT NULL,
  `ganti_kain` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`shift_id`, `tanggal`, `user_id`, `shift`, `waktu_kerja`, `nama_operator`, `mulai_istirahat`, `selesai_istirahat`, `kendala`, `ganti_benang`, `ganti_kain`) VALUES
(21, '2024-06-12', 2, '2', 0.44, 'Joni', '12:00:00', '13:00:00', 'tidak ada', 1, 1),
(22, '2024-06-12', 2, '2', 0.33, 'Joni', '12:00:00', '13:00:00', 'test', 1, 1),
(23, '2024-09-05', 2, '2', 1.00, 'Koni', '12:00:00', '13:00:00', 'aewe', 1, 1),
(24, '2024-11-09', 1, '1', 1.00, 'Doni', '12:00:00', '13:00:00', 'Tidak ada', 1, 1),
(25, '2025-02-15', 1, '1', 1.00, 'Lisa', '12:00:00', '13:00:00', 'Tidak Ada', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int NOT NULL,
  `tambah_stock` date NOT NULL,
  `barang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `tambah_stock`, `barang_id`, `user_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `created_at`, `updated_at`) VALUES
(111, '2024-11-09', 14, 1, 0, 15, 0, 15, '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
(112, '2024-11-09', 3, 1, 0, 16, 0, 16, '2024-11-09 08:18:40', '2024-11-09 08:18:40'),
(113, '2024-11-09', 19, 2, 0, 5, 0, 5, '2024-11-09 08:20:26', '2024-11-09 08:20:26'),
(114, '2024-11-09', 19, 1, 5, 2, 0, 7, '2024-11-09 08:20:26', '2024-11-09 08:20:26');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int NOT NULL,
  `nama` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `notelfon` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kodepos` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `nama`, `notelfon`, `alamat`, `kota`, `kodepos`) VALUES
(1, 'Toko Sumber Jaya', '081252807753', 'Jalan Jaya no 15', 'Surabaya', 22134),
(2, 'Toko Abadi', '082122224532', 'Jalan Mawar  1/11', 'Jakarta', 60113),
(3, 'Toko Bahagia Kasih', '098741365897', 'Jl. Krembangan 123', 'Surabaya', 94123);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unit_id` int NOT NULL,
  `satuan` varchar(11) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `satuan`) VALUES
(1, 'Meter'),
(5, 'Kilo'),
(6, 'Centimeter'),
(7, 'Yard'),
(8, 'Gulung'),
(9, 'Unit');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int NOT NULL,
  `id_role` int NOT NULL,
  `nama_pengguna` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `kata_sandi` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `diperbarui_pada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `id_role`, `nama_pengguna`, `email`, `kata_sandi`, `dibuat_pada`, `diperbarui_pada`) VALUES
(1, 1, 'user1', 'user1@gmail.com', '123', '2024-08-04 14:59:38', '2024-08-04 14:59:38'),
(2, 2, 'Jojo', 'jojo@gmail.com', '123', '2024-08-13 09:40:30', '2024-08-13 09:40:30'),
(3, 3, 'Berttt', 'bert@gmail.com', '123', '2024-08-13 09:40:58', '2024-08-13 12:47:34'),
(5, 1, 'Felix', 'felix@gmail.com', '123', '2024-08-27 03:00:27', '2024-08-27 03:03:10'),
(6, 1, 'Christina', 'christina@gmail.com', '123', '2024-09-05 21:23:17', '2024-09-05 21:23:17');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `barang`
--
ALTER TABLE `barang`
  ADD PRIMARY KEY (`barang_id`),
  ADD KEY `unit_id` (`unit_id`);

--
-- Indexes for table `barangproduksi`
--
ALTER TABLE `barangproduksi`
  ADD PRIMARY KEY (`barang_id`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id_gudang`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `jenis`
--
ALTER TABLE `jenis`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `laporanproduksi`
--
ALTER TABLE `laporanproduksi`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indexes for table `laporan_keluar`
--
ALTER TABLE `laporan_keluar`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `mesin`
--
ALTER TABLE `mesin`
  ADD PRIMARY KEY (`mesin_id`);

--
-- Indexes for table `nota`
--
ALTER TABLE `nota`
  ADD PRIMARY KEY (`nota_id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`pembelian_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `pemesanan_id` (`pemesanan_id`);

--
-- Indexes for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`belidetail_id`),
  ADD KEY `pembelian_id` (`pembelian_id`) USING BTREE,
  ADD KEY `pesandetail_id` (`pesandetail_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD PRIMARY KEY (`pemesanan_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD PRIMARY KEY (`penggunaan_id`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pesan_detail`
--
ALTER TABLE `pesan_detail`
  ADD PRIMARY KEY (`pesandetail_id`),
  ADD KEY `pemesanan_id` (`pemesanan_id`),
  ADD KEY `barang_id` (`barang_id`);

--
-- Indexes for table `report`
--
ALTER TABLE `report`
  ADD PRIMARY KEY (`report_id`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
  ADD PRIMARY KEY (`id_role`);

--
-- Indexes for table `shift`
--
ALTER TABLE `shift`
  ADD PRIMARY KEY (`shift_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE;

--
-- Indexes for table `stock`
--
ALTER TABLE `stock`
  ADD PRIMARY KEY (`stock_id`),
  ADD KEY `barang_id` (`barang_id`) USING BTREE,
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `supplier`
--
ALTER TABLE `supplier`
  ADD PRIMARY KEY (`supplier_id`);

--
-- Indexes for table `unit`
--
ALTER TABLE `unit`
  ADD PRIMARY KEY (`unit_id`) USING BTREE;

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `id_role` (`id_role`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `barang`
--
ALTER TABLE `barang`
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=52;

--
-- AUTO_INCREMENT for table `barangproduksi`
--
ALTER TABLE `barangproduksi`
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id_gudang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=72;

--
-- AUTO_INCREMENT for table `jenis`
--
ALTER TABLE `jenis`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laporanproduksi`
--
ALTER TABLE `laporanproduksi`
  MODIFY `laporan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `laporan_keluar`
--
ALTER TABLE `laporan_keluar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `mesin`
--
ALTER TABLE `mesin`
  MODIFY `mesin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nota`
--
ALTER TABLE `nota`
  MODIFY `nota_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `pembelian_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=184;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `belidetail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=192;

--
-- AUTO_INCREMENT for table `pemesanan`
--
ALTER TABLE `pemesanan`
  MODIFY `pemesanan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=275;

--
-- AUTO_INCREMENT for table `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `penggunaan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `pesan_detail`
--
ALTER TABLE `pesan_detail`
  MODIFY `pesandetail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=261;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `shift_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=115;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

--
-- Constraints for table `gudang`
--
ALTER TABLE `gudang`
  ADD CONSTRAINT `gudang_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  ADD CONSTRAINT `gudang_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD CONSTRAINT `pembelian_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `pemesanan`
--
ALTER TABLE `pemesanan`
  ADD CONSTRAINT `pemesanan_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`);

--
-- Constraints for table `pesan_detail`
--
ALTER TABLE `pesan_detail`
  ADD CONSTRAINT `pesan_detail_ibfk_1` FOREIGN KEY (`pemesanan_id`) REFERENCES `pemesanan` (`pemesanan_id`),
  ADD CONSTRAINT `pesan_detail_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`);

--
-- Constraints for table `shift`
--
ALTER TABLE `shift`
  ADD CONSTRAINT `shift_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_2` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`id_role`) REFERENCES `role` (`id_role`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
