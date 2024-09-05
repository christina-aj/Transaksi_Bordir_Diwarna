-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 05, 2024 at 01:53 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

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
  `barang_id` int(11) NOT NULL,
  `kode_barang` varchar(255) NOT NULL,
  `nama_barang` varchar(255) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `harga` decimal(10,0) NOT NULL,
  `tipe` varchar(255) NOT NULL,
  `warna` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dumping data for table `barang`
--

INSERT INTO `barang` (`barang_id`, `kode_barang`, `nama_barang`, `unit_id`, `harga`, `tipe`, `warna`, `created_at`, `updated_at`) VALUES
(1, 'A003', 'Benang', 1, 15000, 'Consumable', 'Oren', NULL, '2024-09-02 17:43:50'),
(2, 'A009', 'Mesin jahit', 5, 150000, 'Non Consumable', 'Putih', '2024-09-01 16:23:20', '2024-09-01 16:23:20'),
(3, 'A010', 'Kain', 1, 100000, 'Consumable', 'Hijau', '2024-09-02 16:27:12', '2024-09-02 16:27:12'),
(4, 'A001', 'Benang', 1, 12000, 'Consumable', 'Merah', '2024-08-26 21:37:26', '2024-09-02 16:28:09'),
(5, 'A011', 'Kain', 1, 12000, 'Consumable', 'Kuning', '2024-09-02 15:59:24', '2024-09-02 16:28:21'),
(6, 'A002', 'Mesin', 5, 190000, 'Non Consumable', 'Merah', '2024-09-02 16:22:50', '2024-09-02 16:25:39');

-- --------------------------------------------------------

--
-- Table structure for table `item`
--

CREATE TABLE `item` (
  `id` int(11) NOT NULL,
  `kategori` enum('consumable','non-consumable') NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `unit_id` int(11) NOT NULL,
  `jumlah` int(11) NOT NULL,
  `harga` varchar(200) NOT NULL,
  `total` varchar(200) NOT NULL,
  `tempat_belanja` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `laporanproduksi`
--

CREATE TABLE `laporanproduksi` (
  `laporan_id` int(11) NOT NULL,
  `mesin_id` int(11) NOT NULL,
  `shift_id` int(11) NOT NULL,
  `tanggal_kerja` date NOT NULL,
  `nama_kerjaan` varchar(200) NOT NULL,
  `vs` int(11) NOT NULL,
  `stitch` int(11) NOT NULL,
  `kuantitas` int(11) NOT NULL,
  `bs` int(11) NOT NULL
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
(22, 1, 21, '2021-09-08', 'P12', 1, 2, 155, 1);

-- --------------------------------------------------------

--
-- Table structure for table `mesin`
--

CREATE TABLE `mesin` (
  `mesin_id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `deskripsi` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mesin`
--

INSERT INTO `mesin` (`mesin_id`, `nama`, `deskripsi`) VALUES
(1, 'Mesin Bordir', 'test'),
(2, 'Mesin test', 'ada');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian`
--

CREATE TABLE `pembelian` (
  `pembelian_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `supplier_id` int(11) NOT NULL,
  `total_biaya` varchar(200) NOT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `kode_struk` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pembelian`
--

INSERT INTO `pembelian` (`pembelian_id`, `user_id`, `tanggal`, `supplier_id`, `total_biaya`, `langsung_pakai`, `kode_struk`) VALUES
(1, 2, '2024-09-02', 1, '120000', 1, '123'),
(2, 2, '2024-09-01', 1, '120000', 1, 'A123'),
(3, 5, '2024-09-03', 1, '120000', 0, '412'),
(4, 2, '2024-08-25', 2, '120000', 0, '612'),
(5, 5, '2024-09-03', 2, '120000', 0, '615');

-- --------------------------------------------------------

--
-- Table structure for table `pembelian_detail`
--

CREATE TABLE `pembelian_detail` (
  `belidetail_id` int(11) NOT NULL,
  `pembelian_id` int(11) NOT NULL,
  `barang_id` int(11) NOT NULL,
  `harga_barang` decimal(10,0) NOT NULL,
  `quantity_barang` float NOT NULL,
  `total_biaya` decimal(10,0) NOT NULL,
  `catatan` varchar(255) DEFAULT NULL,
  `langsung_pakai` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

--
-- Dumping data for table `pembelian_detail`
--

INSERT INTO `pembelian_detail` (`belidetail_id`, `pembelian_id`, `barang_id`, `harga_barang`, `quantity_barang`, `total_biaya`, `catatan`, `langsung_pakai`, `created_at`, `updated_at`) VALUES
(1, 3, 4, 12000, 12, 144000, '', 0, '2024-09-02 21:14:51', '2024-09-02 21:22:58'),
(2, 4, 5, 12000, 3, 36000, '', 1, '2024-09-02 21:24:21', '2024-09-02 21:24:21'),
(3, 3, 3, 100000, 6, 600000, '', 1, '2024-09-02 21:25:24', '2024-09-02 21:25:24');

-- --------------------------------------------------------

--
-- Table structure for table `penggunaan`
--

CREATE TABLE `penggunaan` (
  `penggunaan_id` int(11) NOT NULL,
  `kategori` enum('consumable','non-consumable') NOT NULL,
  `jumlah_digunakan` int(11) NOT NULL,
  `tanggal_digunakan` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report`
--

CREATE TABLE `report` (
  `report_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
  `id_role` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL
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
  `shift_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `user_id` int(11) NOT NULL,
  `shift` enum('1','2') NOT NULL,
  `waktu_kerja` decimal(4,2) NOT NULL,
  `nama_operator` varchar(200) NOT NULL,
  `mulai_istirahat` time NOT NULL,
  `selesai_istirahat` time NOT NULL,
  `kendala` text NOT NULL,
  `ganti_benang` int(11) NOT NULL,
  `ganti_kain` int(11) NOT NULL
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
  `stock_id` int(11) NOT NULL,
  `tambah_stock` date NOT NULL,
  `barang_id` int(11) NOT NULL,
  `quantity_awal` float NOT NULL,
  `quantity_masuk` float NOT NULL,
  `quantity_keluar` float NOT NULL,
  `quantity_akhir` float NOT NULL,
  `user_id` int(11) NOT NULL,
  `is_ready` tinyint(1) NOT NULL,
  `is_new` tinyint(1) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=armscii8 COLLATE=armscii8_bin;

-- --------------------------------------------------------

--
-- Table structure for table `supplier`
--

CREATE TABLE `supplier` (
  `supplier_id` int(11) NOT NULL,
  `nama` varchar(200) NOT NULL,
  `notelfon` varchar(200) NOT NULL,
  `alamat` varchar(200) NOT NULL,
  `kota` varchar(200) NOT NULL,
  `kodepos` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `supplier`
--

INSERT INTO `supplier` (`supplier_id`, `nama`, `notelfon`, `alamat`, `kota`, `kodepos`) VALUES
(1, 'Toko Sumber Jaya', '081252807753', 'Jalan Jaya no 15', 'Surabaya', 22134),
(2, 'Toko Abadi', '082122224532', 'Jalan Mawar  1/11', 'Jakarta', 60113);

-- --------------------------------------------------------

--
-- Table structure for table `unit`
--

CREATE TABLE `unit` (
  `unit_id` int(11) NOT NULL,
  `satuan` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `unit`
--

INSERT INTO `unit` (`unit_id`, `satuan`) VALUES
(1, 'Meter'),
(5, 'Kilo');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `id_role` int(11) NOT NULL,
  `nama_pengguna` varchar(200) NOT NULL,
  `email` varchar(200) NOT NULL,
  `kata_sandi` varchar(200) NOT NULL,
  `dibuat_pada` datetime NOT NULL DEFAULT current_timestamp(),
  `diperbarui_pada` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`user_id`, `id_role`, `nama_pengguna`, `email`, `kata_sandi`, `dibuat_pada`, `diperbarui_pada`) VALUES
(1, 1, 'user1', 'user1@gmail.com', '123', '2024-08-04 14:59:38', '2024-08-04 14:59:38'),
(2, 2, 'Jojo', 'jojo@gmail.com', '123', '2024-08-13 09:40:30', '2024-08-13 09:40:30'),
(3, 3, 'Berttt', 'bert@gmail.com', '123', '2024-08-13 09:40:58', '2024-08-13 12:47:34'),
(5, 1, 'Felix', 'felix@gmail.com', '123', '2024-08-27 03:00:27', '2024-08-27 03:03:10');

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
-- Indexes for table `item`
--
ALTER TABLE `item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `kategori` (`kategori`),
  ADD KEY `supplier_id` (`supplier_id`) USING BTREE,
  ADD KEY `unit_id` (`unit_id`) USING BTREE;

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
  ADD KEY `kategori` (`kategori`) USING BTREE;

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
  ADD PRIMARY KEY (`shift_id`);

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
  MODIFY `barang_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `item`
--
ALTER TABLE `item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `laporanproduksi`
--
ALTER TABLE `laporanproduksi`
  MODIFY `laporan_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `mesin`
--
ALTER TABLE `mesin`
  MODIFY `mesin_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pembelian`
--
ALTER TABLE `pembelian`
  MODIFY `pembelian_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `pembelian_detail`
--
ALTER TABLE `pembelian_detail`
  MODIFY `belidetail_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `penggunaan`
--
ALTER TABLE `penggunaan`
  MODIFY `penggunaan_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
  MODIFY `id_role` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `shift`
--
ALTER TABLE `shift`
  MODIFY `shift_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `stock`
--
ALTER TABLE `stock`
  MODIFY `stock_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `supplier`
--
ALTER TABLE `supplier`
  MODIFY `supplier_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `unit`
--
ALTER TABLE `unit`
  MODIFY `unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barang`
--
ALTER TABLE `barang`
  ADD CONSTRAINT `barang_ibfk_1` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

--
-- Constraints for table `item`
--
ALTER TABLE `item`
  ADD CONSTRAINT `item_ibfk_1` FOREIGN KEY (`supplier_id`) REFERENCES `supplier` (`supplier_id`),
  ADD CONSTRAINT `item_ibfk_2` FOREIGN KEY (`unit_id`) REFERENCES `unit` (`unit_id`);

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
