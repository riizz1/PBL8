-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Nov 26, 2025 at 05:17 AM
-- Server version: 8.0.30
-- PHP Version: 8.1.10
SET
    SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

START TRANSACTION;

SET
    time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;

/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;

/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;

/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_pbl8`
--
-- --------------------------------------------------------
--
-- Table structure for table `kategori`
--
CREATE TABLE
    `kategori` (
        `kategori_id` int NOT NULL,
        `nama_kategori` varchar(100) NOT NULL,
        `deskripsi` varchar(100) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
--
-- Table structure for table `mahasiswa`
--
CREATE TABLE
    `mahasiswa` (
        `mahasiswa_id` int NOT NULL,
        `nim` varchar(20) NOT NULL,
        `nama_lengkap` varchar(100) NOT NULL,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `prodi` varchar(100) NOT NULL,
        `email` varchar(100) DEFAULT NULL,
        `alamat` text,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
--
-- Table structure for table `pengumuman`
--
CREATE TABLE
    `pengumuman` (
        `pengumuman_id` int NOT NULL,
        `judul` varchar(255) NOT NULL,
        `isi` text NOT NULL,
        `kategori_id` int DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
--
-- Table structure for table `roles`
--
CREATE TABLE
    `roles` (
        `role_id` int NOT NULL,
        `role_name` varchar(50) NOT NULL
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

-- --------------------------------------------------------
--
-- Table structure for table `users`
--
CREATE TABLE
    `users` (
        `user_id` int NOT NULL,
        `username` varchar(50) NOT NULL,
        `password` varchar(255) NOT NULL,
        `role_id` int DEFAULT NULL,
        `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci;

--
-- Indexes for dumped tables
--
--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori` ADD PRIMARY KEY (`kategori_id`),
ADD UNIQUE KEY `nama_kategori` (`nama_kategori`);

--
-- Indexes for table `mahasiswa`
--
ALTER TABLE `mahasiswa` ADD PRIMARY KEY (`mahasiswa_id`),
ADD UNIQUE KEY `nim` (`nim`),
ADD UNIQUE KEY `username` (`username`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman` ADD PRIMARY KEY (`pengumuman_id`),
ADD KEY `kategori_id` (`kategori_id`);

--
-- Indexes for table `roles`
--
ALTER TABLE `roles` ADD PRIMARY KEY (`role_id`),
ADD UNIQUE KEY `role_name` (`role_name`);

--
-- Indexes for table `users`
--
ALTER TABLE `users` ADD PRIMARY KEY (`user_id`),
ADD UNIQUE KEY `username` (`username`),
ADD KEY `fk_role` (`role_id`);

--
-- AUTO_INCREMENT for dumped tables
--
--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori` MODIFY `kategori_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `mahasiswa`
--
ALTER TABLE `mahasiswa` MODIFY `mahasiswa_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman` MODIFY `pengumuman_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `roles`
--
ALTER TABLE `roles` MODIFY `role_id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users` MODIFY `user_id` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--
--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman` ADD CONSTRAINT `pengumuman_ibfk_1` FOREIGN KEY (`kategori_id`) REFERENCES `kategori` (`kategori_id`);

--
-- Constraints for table `users`
--
ALTER TABLE `users` ADD CONSTRAINT `fk_role` FOREIGN KEY (`role_id`) REFERENCES `roles` (`role_id`) ON DELETE RESTRICT ON UPDATE CASCADE;

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;

/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;

/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;