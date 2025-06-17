-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 17, 2025 at 07:14 AM
-- Server version: 8.0.30
-- PHP Version: 8.2.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `hafalan`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_guru`
--

CREATE TABLE `tb_guru` (
  `id` int NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tanggallahir` date DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `no_hp` varchar(50) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `tb_hafalan_baru`
--

CREATE TABLE `tb_hafalan_baru` (
  `id` int NOT NULL,
  `id_santri` int NOT NULL,
  `tanggal` varchar(15) NOT NULL,
  `juz` varchar(100) NOT NULL,
  `surat` varchar(100) NOT NULL,
  `ayat` varchar(100) NOT NULL,
  `status` enum('Mengulang','Tidak Mengulang') DEFAULT 'Tidak Mengulang',
  `created_at` varchar(100) NOT NULL,
  `updated_at` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) DEFAULT NULL,
  `wali_kelas` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_kelas`
--

INSERT INTO `tb_kelas` (`id`, `nama_kelas`, `wali_kelas`, `created_at`, `updated_at`) VALUES
(1, '1A', 'Ustadz Ahmad Fauzi', '2023-09-09 13:21:00', '2023-09-12 05:10:13'),
(2, '2B', 'Ustadzah Fatimah', '2023-09-09 13:21:00', '2023-09-08 16:00:00'),
(3, '3C', 'Ustad Ali', '2023-09-09 13:21:00', '2023-09-08 16:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id` int NOT NULL,
  `username` varchar(100) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `level` enum('Administrator','Guru','Pimpinan') NOT NULL,
  `foto` varchar(255) DEFAULT 'anonim.png',
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id`, `username`, `nama`, `password`, `level`, `foto`, `tanggal`) VALUES
(1, 'admin', 'Fiza', '66b65567cedbc743bda3417fb813b9ba', 'Administrator', 'foto_1_Fiza.png', '2023-08-04 17:26:42'),
(18, 'pimpinan', 'Pimpinan', '11bec7ee11011dd766c7ffea04d140a5', 'Pimpinan', 'anonim.png', '2025-05-04 15:01:01'),
(19, '78910', '78910', 'd5e59b03f0891802e30325dc1057b9da', 'Guru', 'anonim.png', '2025-05-04 17:53:47');

-- --------------------------------------------------------

--
-- Table structure for table `tb_prestasi`
--

CREATE TABLE `tb_prestasi` (
  `id` int NOT NULL,
  `id_santri` int NOT NULL,
  `total_juz` varchar(100) NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `whatsapp_ortu` varchar(20) DEFAULT NULL,
  `status_notif` varchar(100) DEFAULT 'Gagal Terkirim',
  `path_sertifikat` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_prestasi`
--

INSERT INTO `tb_prestasi` (`id`, `id_santri`, `total_juz`, `tanggal`, `created_at`, `updated_at`, `whatsapp_ortu`, `status_notif`, `path_sertifikat`) VALUES
(11, 4, '30', '2025-06-17', '2025-06-17 13:44:20', '2025-06-17 13:44:21', '6285226204424', 'Terkirim', 'assets/certificates/sertifikat_11_Putri_Cahyani_20250617_134421.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tb_santri`
--

CREATE TABLE `tb_santri` (
  `id` bigint UNSIGNED NOT NULL,
  `nis` varchar(20) NOT NULL,
  `kelas` varchar(255) NOT NULL,
  `nama` varchar(255) NOT NULL,
  `tempatlahir` varchar(255) NOT NULL,
  `tanggallahir` varchar(255) NOT NULL,
  `nama_ortu` varchar(255) NOT NULL,
  `whatsapp_ortu` varchar(20) NOT NULL,
  `alamat` varchar(255) NOT NULL,
  `created_at` varchar(40) DEFAULT NULL,
  `updated_at` varchar(40) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tb_santri`
--

INSERT INTO `tb_santri` (`id`, `nis`, `kelas`, `nama`, `tempatlahir`, `tanggallahir`, `nama_ortu`, `whatsapp_ortu`, `alamat`, `created_at`, `updated_at`) VALUES
(4, '12345', '1A', 'Putri Cahyani', 'Makassar', '2000-08-14', 'Bagus', 'putri.ortu@example.c', 'Makassar', '2023-09-30 13:08:09', '2023-10-06 00:39:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_guru`
--
ALTER TABLE `tb_guru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_hafalan_baru`
--
ALTER TABLE `tb_hafalan_baru`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tb_santri`
--
ALTER TABLE `tb_santri`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_guru`
--
ALTER TABLE `tb_guru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `tb_hafalan_baru`
--
ALTER TABLE `tb_hafalan_baru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `tb_santri`
--
ALTER TABLE `tb_santri`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
