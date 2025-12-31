-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 31, 2025 at 01:03 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

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
  `nama_lengkap` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `nidn` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `email` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `no_telepon` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `alamat` text CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci,
  `jenis_kelamin` enum('L','P') CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `jabatan` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci DEFAULT NULL,
  `password` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci NOT NULL,
  `role_id` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`user_id`, `username`, `nama_lengkap`, `nidn`, `email`, `no_telepon`, `alamat`, `jenis_kelamin`, `jabatan`, `password`, `role_id`, `created_at`) VALUES
(1, 'superadmin', '', NULL, '', NULL, NULL, NULL, NULL, '$2y$10$qaxXzC496xa93Pr2mB6s5ee7toNS4CUtv2tXDRnxlNeXlxN7e4I/y', 1, '2025-12-08 07:05:21'),
(2, 'admin', '', NULL, '', NULL, NULL, NULL, NULL, '$2y$10$mMi35bIhnfJF9V7rC3HVC.4bX7rIYBjwD1Gi6fdW5Z79QQFgZKKxq', 2, '2025-12-17 05:56:29'),
(4, '123', 'Fariz Zikri Pohan', '123', '123qtegd@gmail.com', '123', '123', NULL, NULL, '$2y$10$AGJlwOFl3mowmtQqpF1fDeEwkwESep/6jenWNetvKphoq3DdC8nnq', 2, '2025-12-30 07:06:38');

-- --------------------------------------------------------

--
-- Table structure for table `jurusan`
--

CREATE TABLE `jurusan` (
  `jurusan_id` int NOT NULL,
  `nama_jurusan` varchar(100) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `jurusan`
--

INSERT INTO `jurusan` (`jurusan_id`, `nama_jurusan`) VALUES
(2, 'Manajemen dan Bisnis'),
(4, 'Teknik Elektro'),
(1, 'Teknik Informatika'),
(3, 'Teknik Mesin');

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
(1, 'kelas mata kuliah', 'kategori ini khusus untuk pengumuman kelas matkul');

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
  `role_id` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `mahasiswa`
--

INSERT INTO `mahasiswa` (`mahasiswa_id`, `nim`, `nama_lengkap`, `username`, `password`, `jurusan_id`, `prodi_id`, `kelas`, `email`, `alamat`, `role_id`, `created_at`) VALUES
(1, '', '', 'user', '$2y$10$8CoVrsPiwLNQCurpADi8CeBVWNGMTIcc3nrxvw9gIJYx2BhJAcPh.', NULL, NULL, '', NULL, NULL, 3, '2025-12-30 02:50:49'),
(2, '3312501027', 'Fariz Zikri Pohan', 'fariz', '$2y$10$vKQIZplMJrzVcZ28YwJ14OBoUgEtLvAgeExwGxrtjFc8.jJ1gqjmy', 1, 1, 'IF1A-Pagi', 'farizpohan260107@gmail.com', 'Batam', 3, '2025-12-30 05:17:08'),
(12, '123', 'Fariz Zikri Pohan', 'farizz', '$2y$10$DkLxcx6bcMeUbIRDKd3eTeQ2VLK9rnlrNsQGVpPKrpwsQSLrmCLy2', 1, 1, 'IF1A-Pagi', 'farizpohan260@gmail.com', 'batam', 3, '2025-12-30 06:53:01'),
(13, '3312501030', 'Citra Anggun Batubara', 'citra', '$2y$10$3Vy6mZ1vkOX5HXKZgCGFruWmh63X75Q9e1jCGmVYnqlvucOP3FBni', 1, 6, 'TRPL1A-Pagi', 'anggunbatubara2007@gmail.com', 'batam', 3, '2025-12-30 09:33:38'),
(14, '12334567890-', '123', '123', '$2y$10$J5Pb.ZrCuB3l49sY0tSMYOJaY0aMwhw59ZCKGrCe.qC.agVphU46O', 2, 11, '123', '123@gmail.com', '123', 3, '2025-12-30 16:41:35'),
(15, '3312501029', 'Edo Christian Silaban', 'edo', '$2y$10$JusTv.u8Q/gH0VbGnhx8ROdoBMK93LLBbCdjY.yHREicw499wZYv.', 1, 1, 'IF1A-Pagi', 'edochristian@gmail.com', 'batam', 3, '2025-12-31 00:16:11');

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
  `created_by` int DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`pengumuman_id`, `judul`, `isi`, `target_type`, `target_jurusan_id`, `target_prodi_id`, `target_kelas`, `kategori_id`, `created_by`, `created_at`) VALUES
(1, 'mata kuliah dasproweb ', 'kelas akan dimulai pada jam 13.00 WIB', 'jurusan', 1, 1, NULL, 1, NULL, '2025-12-30 04:36:50'),
(2, 'tes', 'tes', 'jurusan', 3, 14, NULL, 1, NULL, '2025-12-30 04:56:07'),
(3, 'pergantian kelas mata kuliah dasproweb', 'pergantian jam matkul dasproweb', 'jurusan', 1, 1, NULL, 1, NULL, '2025-12-30 09:31:36'),
(4, 'mata kuliah daspro', 'kelas diadakan pada jam 19.00 WIB', 'jurusan', 1, 6, 'TRPL1A-Pagi', 1, NULL, '2025-12-30 09:34:28');

-- --------------------------------------------------------

--
-- Table structure for table `prodi`
--

CREATE TABLE `prodi` (
  `prodi_id` int NOT NULL,
  `nama_prodi` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `jurusan_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `prodi`
--

INSERT INTO `prodi` (`prodi_id`, `nama_prodi`, `jurusan_id`) VALUES
(1, 'Teknik Informatika-IF', 1),
(2, 'Teknologi Geomatika-GM', 1),
(3, 'Terapan Animasi-AN', 1),
(4, 'Terapan Teknologi Rekayasa Multimedia-TRM', 1),
(5, 'Terapan Rekayasa Keamanan Siber-RKS', 1),
(6, 'Terapan Rekayasa Perangkat Lunak-TRPL', 1),
(7, 'Teknik Komputer-TTK/S2', 1),
(8, 'Teknologi Permainan-TP', 1),
(9, 'Akuntansi-AK', 2),
(10, 'Akuntansi Manajerial-AM', 2),
(11, 'Administrasi Bisnis Terapan-AB', 2),
(12, 'Logistik Perdagangan Internasional-LPI', 2),
(13, 'Jalur Cepat Distribusi Barang-DB', 2),
(14, 'Teknik Mesin-MS', 3),
(15, 'Teknik Perawatan Pesawat Udara-TPPU', 3),
(16, 'Teknologi Rekayasa Konstruksi Perkapalan-TRKP', 3),
(17, 'Teknologi Rekayasa Pengelasan dan Fabrikasi-TRPF', 3),
(18, 'Program Profesi Insinyur-PSPPI', 3),
(19, 'Teknologi Rekayasa Metalurgi-MET', 3),
(20, 'Teknik Elektronika Manufaktur-EM', 4),
(21, 'Teknologi Rekayasa Elektronika-TRE', 4),
(22, 'Teknik Instrumentasi-IN', 4),
(23, 'Teknik Mekatronika-MK', 4),
(24, 'Teknologi Rekayasa Pembangkit Energi-RPE', 4),
(25, 'Teknologi Rekayasa Robotika-TRR', 4);

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
  ADD KEY `fk_mhs_prodi` (`prodi_id`),
  ADD KEY `fk_mhs_role` (`role_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`pengumuman_id`),
  ADD KEY `kategori_id` (`kategori_id`),
  ADD KEY `fk_pengumuman_jurusan` (`target_jurusan_id`),
  ADD KEY `fk_pengumuman_prodi` (`target_prodi_id`),
  ADD KEY `fk_pengumuman_admin` (`created_by`);

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
  MODIFY `user_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `jurusan`
--
ALTER TABLE `jurusan`
  MODIFY `jurusan_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa`
  MODIFY `mahasiswa_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `pengumuman_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `prodi`
--
ALTER TABLE `prodi`
  MODIFY `prodi_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

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
  ADD CONSTRAINT `fk_mhs_prodi` FOREIGN KEY (`prodi_id`) REFERENCES `prodi` (`prodi_id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_mhs_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `fk_pengumuman_admin` FOREIGN KEY (`created_by`) REFERENCES `admin` (`user_id`) ON DELETE SET NULL ON UPDATE CASCADE,
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
