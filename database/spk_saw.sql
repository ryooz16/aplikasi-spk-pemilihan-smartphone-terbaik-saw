-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 26, 2026 at 07:03 AM
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
-- Database: `spk_saw`
--

-- --------------------------------------------------------

--
-- Table structure for table `kriteria`
--

CREATE TABLE `kriteria` (
  `id` int NOT NULL,
  `nama_kriteria` varchar(100) DEFAULT NULL,
  `bobot` float DEFAULT NULL,
  `tipe` enum('benefit','cost') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kriteria`
--

INSERT INTO `kriteria` (`id`, `nama_kriteria`, `bobot`, `tipe`) VALUES
(1, 'Harga', 0.25, 'cost'),
(2, 'Ram', 0.2, 'benefit'),
(3, 'Benchmark (Antutu)', 0.25, 'benefit'),
(4, 'Kamera', 0.15, 'benefit'),
(6, 'Storage', 0.15, 'benefit');

-- --------------------------------------------------------

--
-- Table structure for table `nilai`
--

CREATE TABLE `nilai` (
  `id` int NOT NULL,
  `id_smartphone` int DEFAULT NULL,
  `id_kriteria` int DEFAULT NULL,
  `nilai` float DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `nilai`
--

INSERT INTO `nilai` (`id`, `id_smartphone`, `id_kriteria`, `nilai`) VALUES
(46, 8, 1, 5499000),
(47, 8, 2, 8),
(48, 8, 3, 800000),
(49, 8, 4, 50),
(50, 8, 6, 128),
(51, 9, 1, 4999000),
(52, 9, 2, 12),
(53, 9, 3, 1150000),
(54, 9, 4, 64),
(55, 9, 6, 256),
(56, 10, 1, 3199000),
(57, 10, 2, 6),
(58, 10, 3, 420000),
(59, 10, 4, 48),
(60, 10, 6, 128),
(61, 11, 1, 2199000),
(62, 11, 2, 6),
(63, 11, 3, 270000),
(64, 11, 4, 50),
(65, 11, 6, 128);

-- --------------------------------------------------------

--
-- Table structure for table `smartphone`
--

CREATE TABLE `smartphone` (
  `id` int NOT NULL,
  `nama_smartphone` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `smartphone`
--

INSERT INTO `smartphone` (`id`, `nama_smartphone`) VALUES
(8, 'Samsung Galaxy A54'),
(9, 'Poco F5'),
(10, 'Xiaomi Redmi Note 12'),
(11, 'Oppo A58');

-- --------------------------------------------------------

--
-- Table structure for table `spesifikasi`
--

CREATE TABLE `spesifikasi` (
  `id` int NOT NULL,
  `id_smartphone` int DEFAULT NULL,
  `harga` int DEFAULT NULL,
  `ram` int DEFAULT NULL,
  `benchmark_antutu` int DEFAULT NULL,
  `kamera` int DEFAULT NULL,
  `storage` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `spesifikasi`
--

INSERT INTO `spesifikasi` (`id`, `id_smartphone`, `harga`, `ram`, `benchmark_antutu`, `kamera`, `storage`) VALUES
(26, 8, 5499000, 8, 800000, 50, 128),
(27, 9, 4999000, 12, 1150000, 64, 256),
(28, 10, 3199000, 6, 420000, 48, 128),
(29, 11, 2199000, 6, 270000, 50, 128);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kriteria`
--
ALTER TABLE `kriteria`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nilai`
--
ALTER TABLE `nilai`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_smartphone` (`id_smartphone`);

--
-- Indexes for table `smartphone`
--
ALTER TABLE `smartphone`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `spesifikasi`
--
ALTER TABLE `spesifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_spesifikasi_smartphone` (`id_smartphone`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kriteria`
--
ALTER TABLE `kriteria`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nilai`
--
ALTER TABLE `nilai`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=66;

--
-- AUTO_INCREMENT for table `smartphone`
--
ALTER TABLE `smartphone`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `spesifikasi`
--
ALTER TABLE `spesifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `nilai`
--
ALTER TABLE `nilai`
  ADD CONSTRAINT `fk_smartphone` FOREIGN KEY (`id_smartphone`) REFERENCES `smartphone` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `spesifikasi`
--
ALTER TABLE `spesifikasi`
  ADD CONSTRAINT `fk_spesifikasi_smartphone` FOREIGN KEY (`id_smartphone`) REFERENCES `smartphone` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
