-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 24, 2025 at 07:33 PM
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
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggallahir` date DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `no_hp` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_guru`
--

INSERT INTO `tb_guru` (`id`, `nama`, `tanggallahir`, `email`, `no_hp`, `created_at`, `updated_at`) VALUES
(1, 'cece', '2025-06-23', 'cece@gmail.com', '086262626', '2025-06-23 06:49:36', '2025-06-23 13:49:36');

-- --------------------------------------------------------

--
-- Table structure for table `tb_hafalan_baru`
--

CREATE TABLE `tb_hafalan_baru` (
  `id` int NOT NULL,
  `id_santri` int NOT NULL,
  `id_kelas` int NOT NULL,
  `tanggal` varchar(15) COLLATE utf8mb4_unicode_ci NOT NULL,
  `juz` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `surat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ayat` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` enum('Mengulang','Tidak Mengulang') COLLATE utf8mb4_unicode_ci DEFAULT 'Tidak Mengulang',
  `created_at` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_hafalan_baru`
--

INSERT INTO `tb_hafalan_baru` (`id`, `id_santri`, `id_kelas`, `tanggal`, `juz`, `surat`, `ayat`, `status`, `created_at`, `updated_at`) VALUES
(9, 4, 1, '2025-06-25', 'Juz 14', 'An-Nahl', '2', 'Tidak Mengulang', '2025-06-24 19:06:27', '2025-06-24 19:06:27');

-- --------------------------------------------------------

--
-- Table structure for table `tb_kelas`
--

CREATE TABLE `tb_kelas` (
  `id` bigint UNSIGNED NOT NULL,
  `nama_kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `wali_kelas` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

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
  `username` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `level` enum('Administrator','Guru','Pimpinan') COLLATE utf8mb4_unicode_ci NOT NULL,
  `foto` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT 'anonim.png',
  `tanggal` datetime DEFAULT CURRENT_TIMESTAMP,
  `nip` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tanggal_lahir` date DEFAULT NULL,
  `no_hp` varchar(15) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id`, `username`, `nama`, `password`, `level`, `foto`, `tanggal`, `nip`, `tanggal_lahir`, `no_hp`) VALUES
(1, 'admin', 'Fiza', '66b65567cedbc743bda3417fb813b9ba', 'Administrator', 'foto_1_Fiza.png', '2023-08-04 17:26:42', NULL, NULL, NULL),
(18, 'pimpinan', 'Pimpinan', '11bec7ee11011dd766c7ffea04d140a5', 'Pimpinan', 'anonim.png', '2025-05-04 15:01:01', NULL, NULL, NULL),
(19, 'guru', 'ujang bustomi', 'd721003a44dc2b0b3eb89dae817813b9', 'Guru', 'anonim.png', '2025-05-04 17:53:47', '2121212121', '2025-06-25', '087666273322'),
(20, 'cece@gmail.com', 'cece', 'df8285646c555b8437309b179d44f59f', 'Guru', '', '2025-06-23 13:49:36', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tb_prestasi`
--

CREATE TABLE `tb_prestasi` (
  `id` int NOT NULL,
  `id_santri` int NOT NULL,
  `total_juz` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggal` date NOT NULL,
  `created_at` datetime DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `whatsapp_ortu` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_notif` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT 'Gagal Terkirim',
  `path_sertifikat` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_prestasi`
--

INSERT INTO `tb_prestasi` (`id`, `id_santri`, `total_juz`, `tanggal`, `created_at`, `updated_at`, `whatsapp_ortu`, `status_notif`, `path_sertifikat`) VALUES
(11, 4, '30', '2025-06-17', '2025-06-17 13:44:20', '2025-06-17 13:44:21', '6285226204424', 'Terkirim', 'assets/certificates/sertifikat_11_Putri_Cahyani_20250617_134421.pdf'),
(12, 4, '30', '2025-06-17', '2025-06-17 17:17:17', '2025-06-17 17:17:19', '6282325254577', 'Terkirim', 'assets/certificates/sertifikat_12_Putri_Cahyani_20250617_171718.pdf'),
(13, 4, '30', '2025-06-17', '2025-06-17 17:18:22', '2025-06-17 17:18:25', '6282388414833', 'Terkirim', 'assets/certificates/sertifikat_13_Putri_Cahyani_20250617_171823.pdf'),
(14, 4, '30', '2025-06-18', '2025-06-18 12:02:50', '2025-06-18 12:02:52', '6282388365095', 'Terkirim', 'assets/certificates/sertifikat_14_Putri_Cahyani_20250618_120251.pdf'),
(15, 4, '28', '2025-06-23', '2025-06-23 20:30:25', '2025-06-23 20:30:28', '6285226204424', 'Terkirim', 'assets/certificates/sertifikat_15_Putri_Cahyani_20250623_203026.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tb_santri`
--

CREATE TABLE `tb_santri` (
  `id` bigint UNSIGNED NOT NULL,
  `nis` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `kelas` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tempatlahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tanggallahir` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nama_ortu` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_ortu` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `alamat` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `updated_at` varchar(40) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tb_santri`
--

INSERT INTO `tb_santri` (`id`, `nis`, `kelas`, `nama`, `tempatlahir`, `tanggallahir`, `nama_ortu`, `email_ortu`, `alamat`, `created_at`, `updated_at`) VALUES
(4, '12345', '1A', 'Putri Cahyani', 'Makassar', '2000-08-14', 'Bagus', 'putri.ortu@example.c', 'Makassar', '2023-09-30 13:08:09', '2023-10-06 00:39:10'),
(7, '4444', '1A', 'Tegar', 'Pekanbaru', '2025-06-25', 'Raihan', 'ortu@gmail.com', 'asd', '2025-06-24 18:21:53', NULL);

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
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tb_hafalan_baru`
--
ALTER TABLE `tb_hafalan_baru`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `tb_kelas`
--
ALTER TABLE `tb_kelas`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;

--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `tb_prestasi`
--
ALTER TABLE `tb_prestasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `tb_santri`
--
ALTER TABLE `tb_santri`
  MODIFY `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
