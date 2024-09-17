-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Sep 17, 2024 at 07:07 PM
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
  `kode_barang` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `nama_barang` varchar(255) CHARACTER SET utf8mb3 COLLATE utf8mb3_general_ci NOT NULL,
  `angka` float NOT NULL,
  `unit_id` int NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `tipe` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin NOT NULL,
  `warna` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL,
  `supplier_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`barang_id`, `kode_barang`, `nama_barang`, `angka`, `unit_id`, `harga`, `tipe`, `warna`, `supplier_id`, `created_at`, `updated_at`) VALUES
(1, 'A003', 'Benang Oren', 5, 1, 15000, 'Consumable', 'Oren', 1, NULL, '2024-09-17 15:45:51'),
(3, 'B001', 'Kain Hijau', 6, 1, 100000, 'Consumable', 'Hijau', 2, '2024-09-02 16:27:12', '2024-09-17 16:23:19'),
(4, 'A001', 'Benang Merah', 3000, 7, 12000, 'Consumable', 'Merah', 3, '2024-08-26 21:37:26', '2024-09-17 15:46:50'),
(5, 'B002', 'Kain Kuning', 3, 1, 12000, 'Consumable', 'Kuning', 2, '2024-09-02 15:59:24', '2024-09-17 16:23:29'),
(7, 'ZZZZ', 'Manik-manik', 2, 1, 15000, 'Consumable', 'Merah', 3, '2024-09-05 17:28:41', '2024-09-17 15:47:36'),
(9, 'A004', 'Benang Polyester', 1000, 8, 50000, 'Consumable', 'Putih', 1, '2024-09-17 16:20:33', '2024-09-17 16:20:33'),
(10, 'A005', 'Benang Polyester', 500, 8, 50000, 'Consumable', 'Putih', 3, '2024-09-17 16:21:33', '2024-09-17 16:21:33'),
(11, 'A002', 'Benang Rayon', 500, 8, 100000, 'Consumable', 'Merah', 2, '2024-09-17 16:22:51', '2024-09-17 16:22:51'),
(12, 'M001', 'Mesin Bordir', 1, 9, 50000000, 'Non Consumable', 'Silver', 1, '2024-09-17 16:25:12', '2024-09-17 16:25:12'),
(13, 'M002', 'Rangka Bordir', 5, 9, 2000000, 'Non Consumable', 'Hitam', 3, '2024-09-17 16:25:55', '2024-09-17 16:25:55'),
(14, 'A006', 'Benang Merah', 250, 8, 190000, 'Consumable', 'Merah', 1, '2024-09-17 16:34:07', '2024-09-17 16:34:07');

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
  `catatan` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `update_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `gudang`
--

INSERT INTO `gudang` (`id_gudang`, `tanggal`, `barang_id`, `user_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `catatan`, `created_at`, `update_at`) VALUES
(1, '2024-09-02', 1, 1, 0, 5, 1, 4, 'test', NULL, '2024-09-13 19:39:44'),
(2, '2024-09-02', 1, 1, 4, 15, 5, 14, 'test', '2024-09-13 19:44:28', '2024-09-13 19:44:28'),
(3, '2024-09-01', 3, 1, 0, 15, 5, 10, 'test', '2024-09-13 19:58:00', '2024-09-13 19:58:00');

-- --------------------------------------------------------

--
-- Table structure for table `laporanproduksi`
--

CREATE TABLE `laporanproduksi` (
  `laporan_id` int NOT NULL,
  `mesin_id` int NOT NULL,
  `shift_id` int NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) COLLATE utf8mb4_general_ci NOT NULL,
  `vs` int NOT NULL,
  `stitch` int NOT NULL,
  `kuantitas` int NOT NULL,
  `bs` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `laporanproduksi`
--

INSERT INTO `laporanproduksi` (`laporan_id`, `mesin_id`, `shift_id`, `tanggal_kerja`, `nama_kerjaan`, `vs`, `stitch`, `kuantitas`, `bs`) VALUES
(8, 2, 21, '2024-09-02', 'babi', 1, 2, 3, 4),
(9, 2, 22, '2024-09-03', 'babi', 1, 2, 3, 4),
(10, 1, 21, '2024-09-04', 'babi12', 1, 2, 24, 2),
(11, 1, 22, '2024-09-04', 'babi12', 1, 1, 52, 1),
(12, 2, 22, '2024-09-04', 'babi12', 1, 2, 52, 1),
(13, 1, 22, '2024-09-05', 'babi12', 1, 2, 55, 2),
(21, 1, 21, '2021-09-08', 'P12', 2, 2, 1555, 1),
(22, 1, 21, '2021-09-08', 'P12', 1, 2, 155, 1),
(23, 1, 23, '2024-09-05', 'Jahit', 123, 123, 123, 123),
(24, 2, 22, '2019-07-01', 'Nukang', 1, 1, 1, 1),
(25, 2, 21, '2019-07-12', 'Nukang', 1, 1, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mesin`
--

CREATE TABLE `mesin` (
  `mesin_id` int NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesin`
--

INSERT INTO `mesin` (`mesin_id`, `nama`, `deskripsi`) VALUES
(1, 'Mesin Bordir', 'test');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `pembelian_id` int NOT NULL,
  `user_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `supplier_id` int NOT NULL,
  `total_biaya` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `kode_struk` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`pembelian_id`, `user_id`, `tanggal`, `supplier_id`, `total_biaya`, `langsung_pakai`, `kode_struk`) VALUES
(1, 2, '2024-09-02', 1, '1751000', 0, '123'),
(7, 6, '2024-09-11', 2, '270000', 0, 'CHR0921'),
(8, 3, '2024-09-03', 2, '1380000', 0, 'APAJA123'),
(9, 3, '2024-10-01', 2, '0', 0, '4444'),
(10, 5, '2024-09-30', 1, '0', 0, 'STRE123'),
(11, 5, '2024-10-02', 1, '0', 0, 'STRET123'),
(12, 5, '2024-08-25', 3, '0', 0, 'ASD123');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `belidetail_id` int NOT NULL,
  `pembelian_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `harga_barang` decimal(10,0) NOT NULL,
  `quantity_barang` float NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `catatan` varchar(255) CHARACTER SET armscii8 COLLATE armscii8_bin DEFAULT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`belidetail_id`, `pembelian_id`, `barang_id`, `harga_barang`, `quantity_barang`, `total_biaya`, `catatan`, `langsung_pakai`, `created_at`, `updated_at`) VALUES
(15, 1, 1, 15000, 3, 45000, '', 0, '2024-09-04 21:14:34', '2024-09-04 21:14:34'),
(23, 1, 1, 15000, 6, 90000, '', 0, '2024-09-04 21:42:35', '2024-09-04 21:42:35'),
(27, 1, 3, 100000, 3, 300000, '', 0, '2024-09-04 21:55:25', '2024-09-04 21:55:25'),
(28, 1, 3, 100000, 5, 500000, '', 0, '2024-09-04 21:55:48', '2024-09-04 21:55:48'),
(31, 1, 3, 100000, 6, 600000, '', 1, '2024-09-04 22:05:32', '2024-09-04 22:05:32'),
(33, 1, 5, 12000, 12, 144000, '', 1, '2024-09-04 22:07:57', '2024-09-04 22:07:57'),
(34, 1, 5, 12000, 6, 72000, '', 0, '2024-09-04 22:08:27', '2024-09-04 22:09:48'),
(35, 7, 1, 15000, 6, 90000, '', 0, '2024-09-05 14:24:42', '2024-09-05 14:24:42'),
(36, 7, 1, 15000, 12, 180000, '', 1, '2024-09-05 14:25:18', '2024-09-05 14:25:18'),
(37, 8, 3, 100000, 12, 1200000, '', 0, '2024-09-05 17:27:18', '2024-09-05 17:27:18'),
(38, 8, 7, 15000, 12, 180000, '', 0, '2024-09-05 17:29:00', '2024-09-05 17:29:00');

-- --------------------------------------------------------

--
-- Table structure for table `penggunaan`
--

CREATE TABLE `penggunaan` (
  `penggunaan_id` int NOT NULL,
  `barang_id` int NOT NULL,
  `user_id` int NOT NULL,
  `jumlah_digunakan` int NOT NULL,
  `catatan` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `tanggal_digunakan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penggunaan`
--

INSERT INTO `penggunaan` (`penggunaan_id`, `barang_id`, `user_id`, `jumlah_digunakan`, `catatan`, `tanggal_digunakan`) VALUES
(5, 3, 3, 4, '4 digunakan', '2024-09-10'),
(6, 1, 6, 6, 'Digunakan untuk jahit', '2024-09-19'),
(7, 3, 5, 4, '4 item Digunakan dari stock', '2024-09-23'),
(8, 3, 1, 4, '4 digunakan', '2024-09-01');

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
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `kendala` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `ganti_benang` int NOT NULL,
  `ganti_kain` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `shift`
--

INSERT INTO `shift` (`shift_id`, `tanggal`, `user_id`, `shift`, `waktu_kerja`, `nama_operator`, `mulai_istirahat`, `selesai_istirahat`, `kendala`, `ganti_benang`, `ganti_kain`) VALUES
(21, '2024-06-12', 2, '2', 0.44, 'Joni', '12:00:00', '13:00:00', 'tidak ada', 1, 1),
(22, '2024-06-12', 2, '2', 0.33, 'Joni', '12:00:00', '13:00:00', 'test', 1, 1),
(23, '2024-09-05', 2, '2', 1.00, 'Koni', '12:00:00', '13:00:00', 'aewe', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `stock`
--

CREATE TABLE `stock` (
  `stock_id` int NOT NULL,
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
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dumping data for table `stock`
--

INSERT INTO `stock` (`stock_id`, `tambah_stock`, `barang_id`, `quantity_awal`, `quantity_masuk`, `quantity_keluar`, `quantity_akhir`, `user_id`, `is_ready`, `is_new`, `created_at`, `updated_at`) VALUES
(6, '2024-09-02', 1, 0, 3, 0, 3, 2, 0, 1, '2024-09-04 21:14:34', '2024-09-04 21:14:34'),
(10, '2024-09-02', 1, 3, 6, 0, 9, 2, 0, 1, '2024-09-04 21:42:35', '2024-09-04 21:42:35'),
(13, '2024-09-02', 3, 0, 3, 0, 3, 2, 0, 1, '2024-09-04 21:55:25', '2024-09-04 21:55:25'),
(14, '2024-09-02', 3, 3, 5, 0, 8, 2, 0, 1, '2024-09-04 21:55:48', '2024-09-04 21:55:48'),
(16, '2024-09-02', 3, 8, 0, 6, 8, 2, 1, 0, '2024-09-04 22:05:32', '2024-09-04 22:05:32'),
(18, '2024-09-02', 5, 0, 0, 12, 0, 2, 1, 0, '2024-09-04 22:07:57', '2024-09-04 22:07:57'),
(20, '2024-09-02', 5, 0, 6, 0, 6, 2, 0, 1, '2024-09-04 22:09:48', '2024-09-04 22:09:48'),
(23, '2024-09-10', 3, 8, 0, 4, 4, 3, 1, 0, '2024-09-05 11:31:32', '2024-09-05 11:31:32'),
(24, '2024-09-11', 1, 9, 6, 0, 15, 6, 0, 1, '2024-09-05 14:24:42', '2024-09-05 14:24:42'),
(25, '2024-09-11', 1, 15, 0, 12, 15, 6, 1, 0, '2024-09-05 14:25:18', '2024-09-05 14:25:18'),
(26, '2024-09-19', 1, 15, 0, 6, 9, 6, 1, 0, '2024-09-05 14:31:46', '2024-09-05 14:31:46'),
(27, '2024-09-03', 3, 4, 12, 0, 16, 3, 0, 1, '2024-09-05 17:27:18', '2024-09-05 17:27:18'),
(28, '2024-09-03', 7, 0, 12, 0, 12, 3, 0, 1, '2024-09-05 17:29:00', '2024-09-05 17:29:00'),
(29, '2024-09-10', 3, 16, 0, 4, 12, 3, 1, 0, '2024-09-07 11:58:18', '2024-09-07 11:58:18'),
(30, '2024-09-19', 1, 9, 0, 6, 3, 6, 1, 0, '2024-09-07 11:58:36', '2024-09-07 11:58:36'),
(31, '2024-09-23', 3, 12, 0, 4, 8, 5, 1, 0, '2024-09-10 16:22:13', '2024-09-10 16:22:13'),
(32, '2024-09-01', 3, 8, 0, 4, 4, 1, 1, 0, '2024-09-13 19:28:06', '2024-09-13 19:28:06');

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int NOT NULL,
  `nama` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `notelfon` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `alamat` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kota` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
  `satuan` varchar(11) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
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
  `nama_pengguna` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `kata_sandi` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
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
  ADD KEY `unit_id` (`unit_id`),
  ADD KEY `supplier_id` (`supplier_id`);

--
-- Indexes for table `gudang`
--
ALTER TABLE `gudang`
  ADD PRIMARY KEY (`id_gudang`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `laporanproduksi`
--
ALTER TABLE `laporanproduksi`
  ADD PRIMARY KEY (`laporan_id`);

--
-- Indexes for table `mesin`
--
ALTER TABLE `mesin`
  ADD PRIMARY KEY (`mesin_id`);

--
-- Indexes for table `pembelian`
--
ALTER TABLE `pembelian`
  ADD PRIMARY KEY (`pembelian_id`),
  ADD KEY `user_id` (`user_id`) USING BTREE,
  ADD KEY `supplier_id` (`supplier_id`) USING BTREE;

--
-- Indexes for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD PRIMARY KEY (`belidetail_id`),
  ADD KEY `barang_id` (`barang_id`) USING BTREE,
  ADD KEY `pembelian_id` (`pembelian_id`) USING BTREE;

--
-- Indexes for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD PRIMARY KEY (`penggunaan_id`),
  ADD KEY `barang_id` (`barang_id`),
  ADD KEY `user_id` (`user_id`);

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
  ADD KEY `user_id` (`user_id`) USING BTREE;

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
  MODIFY `barang_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `gudang`
--
ALTER TABLE `gudang`
  MODIFY `id_gudang` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `laporanproduksi`
--
ALTER TABLE `laporanproduksi`
  MODIFY `laporan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `mesin`
--
ALTER TABLE `mesin`
  MODIFY `mesin_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `pembelian_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `belidetail_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `penggunaan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `shift_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

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
  ADD CONSTRAINT `pembelian_ibfk_3` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
  ADD CONSTRAINT `pembelian_ibfk_4` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`);

--
-- Constraints for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  ADD CONSTRAINT `pembelian_detail_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`),
  ADD CONSTRAINT `pembelian_detail_ibfk_2` FOREIGN KEY (`pembelian_id`) REFERENCES `pembelian` (`pembelian_id`);

--
-- Constraints for table `penggunaan`
--
ALTER TABLE `penggunaan`
  ADD CONSTRAINT `penggunaan_ibfk_1` FOREIGN KEY (`barang_id`) REFERENCES `barang` (`barang_id`);

--
-- Constraints for table `shift`
--
ALTER TABLE `shift`
  ADD CONSTRAINT `shift_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`);

--
-- Constraints for table `stock`
--
ALTER TABLE `stock`
  ADD CONSTRAINT `stock_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `user` (`user_id`),
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
