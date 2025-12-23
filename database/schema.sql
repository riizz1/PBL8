-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 23, 2025 at 09:28 AM
-- Server version: 8.0.30
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pbl8`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `user_id` int NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nidn` varchar(30) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(20) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text COLLATE utf8mb4_general_ci,
  `jenis_kelamin` enum('L','P') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(50) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `status_aktif` enum('aktif','nonaktif') COLLATE utf8mb4_general_ci DEFAULT 'aktif',
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `username`, `nama_lengkap`, `nidn`, `email`, `no_telepon`, `alamat`, `jenis_kelamin`, `jabatan`, `status_aktif`, `password`, `role_id`, `created_at`) VALUES
(1, 'superadmin', '', NULL, '', NULL, NULL, NULL, NULL, 'aktif', '$2y$10$qaxXzC496xa93Pr2mB6s5ee7toNS4CUtv2tXDRnxlNeXlxN7e4I/y', 1, '2025-12-08 07:05:21'),
(2, 'admin', '', NULL, '', NULL, NULL, NULL, NULL, 'aktif', '$2y$10$mMi35bIhnfJF9V7rC3HVC.4bX7rIYBjwD1Gi6fdW5Z79QQFgZKKxq', 2, '2025-12-17 05:56:29');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `jurusan_id` int NOT NULL,
  `nama_jurusan` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`jurusan_id`, `nama_jurusan`) VALUES
(5, 'Manajemen dan Bisnis'),
(7, 'Teknik Elektro'),
(4, 'Teknik Informatika'),
(6, 'Teknik Mesin');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `kategori_id` int NOT NULL,
  `nama_kategori` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `deskripsi` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`kategori_id`, `nama_kategori`, `deskripsi`) VALUES
(3, 'beasiswa', 'kategori ini berisi pengumuman khusus beasiswa'),
(4, '213', ''),
(5, 'ujian', 'kategori ini berisi tentang ujian');

-- --------------------------------------------------------

--
-- Table structure for table `mahasiswa`
--

CREATE TABLE `mahasiswa` (
  `mahasiswa_id` int NOT NULL,
  `nim` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `username` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan_id` int DEFAULT NULL,
  `prodi_id` int DEFAULT NULL,
  `kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`mahasiswa_id`, `nim`, `nama_lengkap`, `username`, `password`, `jurusan_id`, `prodi_id`, `kelas`, `email`, `alamat`, `created_at`) VALUES
(19, '', 'user', 'user', '$2y$10$YCx2hsYuqH5umcd8KL805OhYGWCK/7qgUB65zRlYMrCoprlkn0T6.', NULL, NULL, '', NULL, NULL, '2025-12-16 07:57:19'),
(23, '3312501027', 'Fariz Zikri Pohan', 'fariz', '$2y$10$ggm/WUCvTSjyJikIM3JW6umEb5RzzAVMcX3VJYU9bfJd4m88f12oe', 4, 5, 'IF1A-Pagi', 'farizpohan260107@gmail.com', 'Batam Center', '2025-12-23 07:59:40'),
(24, '33125010277', 'Fariz Zikri Pohan', 'farizz', '$2y$10$owgo4lnSmHmKKFM.p68FLucHWdPpCNTARhOBjeDq8xaBg1BftNaKq', 4, 10, 'TRPL1A-Pagi', 'farizzp44@gmail.com', 'Batam Center', '2025-12-23 08:00:39');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `pengumuman_id` int NOT NULL,
  `judul` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `isi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `target_type` enum('all','jurusan','prodi','kelas') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL DEFAULT 'all',
  `target_jurusan_id` int DEFAULT NULL,
  `target_prodi_id` int DEFAULT NULL,
  `target_kelas` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `kategori_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`pengumuman_id`, `judul`, `isi`, `target_type`, `target_jurusan_id`, `target_prodi_id`, `target_kelas`, `kategori_id`, `created_at`) VALUES
(18, '123', 'isi', 'jurusan', 4, 5, NULL, 3, '2025-12-17 07:57:21'),
(19, '123', 'tes pengumuman', 'jurusan', 4, 5, 'IF1A-Pagi', 3, '2025-12-17 08:01:42'),
(20, 'judul', 'isi', 'all', NULL, NULL, NULL, 4, '2025-12-17 08:07:33'),
(21, 'judul2', 'isi', 'jurusan', 7, NULL, NULL, 3, '2025-12-17 08:10:47'),
(22, 'jadwal ujian ATS', 'isi pengumuman', 'jurusan', 4, NULL, NULL, 3, '2025-12-17 08:11:59'),
(23, 'jadwal ujian ATS', 'jadwal ujian untuk jurusan informatika akan dikeluarkan pada bulan januari 2026', 'jurusan', 4, 5, NULL, 3, '2025-12-23 08:12:02');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `prodi_id` int NOT NULL,
  `nama_prodi` varchar(100) NOT NULL,
  `jurusan_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`prodi_id`, `nama_prodi`, `jurusan_id`) VALUES
(5, 'Teknik Informatika-IF', 4),
(6, 'Teknologi Geomatika-GM', 4),
(7, 'Terapan Animasi-AN', 4),
(8, 'Terapan Teknologi Rekayasa Multimedia-TRM', 4),
(9, 'Terapan Rekayasa Keamanan Siber-RKS', 4),
(10, 'Terapan Rekayasa Perangkat Lunak-TRPL', 4),
(11, 'Teknik Komputer-TTK/S2', 4),
(12, 'Teknologi Permainan-TP', 4),
(13, 'Akuntansi-AK', 5),
(14, 'Akuntansi Manajerial-AM', 5),
(15, 'Administrasi Bisnis Terapan-AB', 5),
(16, 'Logistik Perdagangan Internasional-LPI', 5),
(17, 'Jalur Cepat Distribusi Barang-DB', 5),
(18, 'Teknik Elektronika Manufaktur-EM', 7),
(19, 'Teknologi Rekayasa Elektronika-TRE', 7),
(20, 'Teknik Instrumentasi-IN', 7),
(21, 'Teknik Mekatronika-MK', 7),
(22, 'Teknologi Rekayasa Pembangkit Energi-RPE', 7),
(23, 'Teknologi Rekayasa Robotika-TRR', 7),
(24, 'Teknik Mesin-MS', 6),
(25, 'Teknik Perawatan Pesawat Udara-TPPU', 6),
(26, 'Teknologi Rekayasa Konstruksi Perkapalan-TRKP', 6),
(27, 'Teknologi Rekayasa Pengelasan dan Fabrikasi-TRPF', 6),
(28, 'Program Profesi Insinyur-PSPPI', 6),
(29, 'Teknologi Rekayasa Metalurgi-MET', 6);

-- --------------------------------------------------------

--
-- Table structure for table `roles`
--

CREATE TABLE `roles` (
  `role_id` int NOT NULL,
  `role_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `roles`
--

INSERT INTO `roles` (`role_id`, `role_name`) VALUES
(2, 'dosen'),
(3, 'mahasiswa'),
(1, 'superadmin');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_role` (`role_id`);

--
-- Indexes for table `jurusan`
--
ALTER TABLE `jurusan`
  ADD PRIMARY KEY (`jurusan_id`),
  ADD UNIQUE KEY `nama_jurusan` (`nama_jurusan`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`kategori_id`),
  ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD PRIMARY KEY (`mahasiswa_id`),
  ADD UNIQUE KEY `nim` (`nim`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `fk_mhs_jurusan` (`jurusan_id`),
  ADD KEY `fk_mhs_prodi` (`prodi_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`pengumuman_id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `fk_pengumuman_jurusan` (`target_jurusan_id`),
  ADD KEY `fk_pengumuman_prodi` (`target_prodi_id`);

--
-- Indexes for table `prodi`
--
ALTER TABLE `prodi`
  ADD PRIMARY KEY (`prodi_id`),
  ADD UNIQUE KEY `nama_prodi` (`nama_prodi`),
  ADD KEY `jurusan_id` (`jurusan_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`role_id`),
  ADD UNIQUE KEY `role_name` (`role_name`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `jurusan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `mahasiswa_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `pengumuman_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `prodi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles`
  MODIFY `role_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  ADD CONSTRAINT `fk_mhs_jurusan` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`jurusan_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mhs_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`prodi_id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `fk_pengumuman_jurusan` FOREIGN KEY (`target_jurusan_id`) REFERENCES `jurusan` (`jurusan_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_pengumuman_prodi` FOREIGN KEY (`target_prodi_id`) REFERENCES `prodi` (`prodi_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`);

--
-- Constraints for table `prodi`
--
ALTER TABLE `prodi`
  ADD CONSTRAINT `prodi_ibfk_1` FOREIGN KEY (`jurusan_id`) REFERENCES `jurusan` (`jurusan_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
