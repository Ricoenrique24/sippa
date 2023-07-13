-- phpMyAdmin SQL Dump
-- version 4.5.1
-- http://www.phpmyadmin.net
--
-- Host: 127.0.0.1
-- Generation Time: Oct 26, 2022 at 06:15 AM
-- Server version: 10.1.19-MariaDB
-- PHP Version: 5.6.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_sippa`
--

-- --------------------------------------------------------

--
-- Table structure for table `tb_beranda`
--

CREATE TABLE `tb_beranda` (
  `id_b` int(11) NOT NULL,
  `jm1` float NOT NULL,
  `jml2` float NOT NULL,
  `tahun` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_detail_simulasi`
--

CREATE TABLE `tb_detail_simulasi` (
  `id_detail_simulasi` int(11) NOT NULL,
  `id_simulasi` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `id_kriteria` int(11) DEFAULT NULL,
  `id_subkriteria` int(11) NOT NULL,
  `nilai_input` float NOT NULL,
  `nilai_rata_rata` float NOT NULL,
  `nilai_bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_hasil`
--

CREATE TABLE `tb_hasil` (
  `id_hasil` int(11) NOT NULL,
  `id_simulasi` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `nilai_persentase` float NOT NULL,
  `nilai_anggaran` int(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_kriteria`
--

CREATE TABLE `tb_kriteria` (
  `id_kriteria` int(11) NOT NULL,
  `nama_kriteria` varchar(100) NOT NULL,
  `bobot_kriteria` float NOT NULL,
  `jenis_kriteria` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_kriteria`
--

INSERT INTO `tb_kriteria` (`id_kriteria`, `nama_kriteria`, `bobot_kriteria`, `jenis_kriteria`) VALUES
(1, 'Kriteria A', 75, 'jurusan'),
(2, 'Kriteria B', 25, 'jurusan'),
(3, 'Kriteria C', 100, 'nonjurusan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_nilai`
--

CREATE TABLE `tb_nilai` (
  `id_nilai` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `id_subkriteria` int(11) NOT NULL,
  `nilai_input` float NOT NULL,
  `nilai_bobot` float NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_pengguna`
--

CREATE TABLE `tb_pengguna` (
  `id_pengguna` int(11) NOT NULL,
  `username` varchar(100) NOT NULL,
  `nama_lengkap` varchar(100) NOT NULL,
  `password_pengguna` varchar(100) NOT NULL,
  `level` int(11) NOT NULL COMMENT '1 = pimpinan, 2 = superadmin, 3 = admin jurusan, 4 = admin non jurusan',
  `id_unit` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `username`, `nama_lengkap`, `password_pengguna`, `level`, `id_unit`, `created_at`) VALUES
(1, 'pimpinan', 'Pimpinan', '90973652b88fe07d05a4304f0a945de8', 1, 1, '2022-06-23 00:55:02'),
(2, 'superadmin', 'Satya', '17c4520f6cfd1ab53d8745e84681eb49', 2, 2, '2022-06-23 00:55:02'),
(3, 'adminjur1', 'Lorem Ipsum 3', '63736d30dff6f08ca0711f4e01ee9437', 3, 2, '2022-06-24 21:11:50'),
(4, 'adminjur2', 'Lorem Ipsum 4', '505a7b86cca0b0e85a383e3067bfbd73', 3, 2, '2022-06-24 21:12:12'),
(5, 'adminnonjur1', 'Lorem Ipsum 5', '25df83c576a8fd75384d915d1409cb8b', 4, 2, '2022-06-26 15:09:00'),
(6, 'adminnonjur2', 'Lorem Ipsum 6', '719564851c3af8ba3bc01193dd68db40', 4, 2, '2022-06-26 15:09:44');

-- --------------------------------------------------------

--
-- Table structure for table `tb_referensi`
--

CREATE TABLE `tb_referensi` (
  `id_ref` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- --------------------------------------------------------

--
-- Table structure for table `tb_simulasi`
--

CREATE TABLE `tb_simulasi` (
  `id_simulasi` int(11) NOT NULL,
  `tahun_simulasi` int(11) NOT NULL,
  `nominal_anggaran` float NOT NULL,
  `jenis_simulasi` int(11) NOT NULL COMMENT '1 = Jurusan / 2 = Non Jurusan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tb_subkriteria`
--

CREATE TABLE `tb_subkriteria` (
  `id_subkriteria` int(11) NOT NULL,
  `id_kriteria` int(11) NOT NULL,
  `nama_subkriteria` varchar(100) NOT NULL,
  `bobot_subkriteria` float NOT NULL,
  `jenis_subkriteria` varchar(100) NOT NULL COMMENT 'jurusan / nonjurusan'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_subkriteria`
--

INSERT INTO `tb_subkriteria` (`id_subkriteria`, `id_kriteria`, `nama_subkriteria`, `bobot_subkriteria`, `jenis_subkriteria`) VALUES
(1, 1, 'Jumlah Mahasiswa', 50, 'jurusan'),
(2, 1, 'Jumlah SDM', 30, 'jurusan'),
(3, 1, 'Jumlah Prodi', 20, 'jurusan'),
(4, 2, 'Capaian Akreditasi', 35, 'jurusan'),
(5, 2, 'Kepatuhan Kegiatan', 10, 'jurusan'),
(6, 2, 'Kepatuhan Perjanjian Kinerja', 10, 'jurusan'),
(7, 2, 'Capaian Perjanjian Kinerja', 10, 'jurusan'),
(8, 2, 'Jumlah Revisi Kegiatan', 10, 'jurusan'),
(9, 2, 'Jumlah Revisi RAB', 10, 'jurusan'),
(10, 2, 'Jumlah Kegiatan Baru', 10, 'jurusan'),
(11, 2, 'Realisasi Anggaran', 5, 'jurusan'),
(12, 3, 'Kepatuhan Kegiatan', 15, 'nonjurusan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_unit`
--

CREATE TABLE `tb_unit` (
  `id_unit` int(11) NOT NULL,
  `nama_unit` varchar(100) NOT NULL,
  `jenis_unit` int(11) NOT NULL COMMENT '1 = Jurusan, 2 = Non Jurusan'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_unit`
--

INSERT INTO `tb_unit` (`id_unit`, `nama_unit`, `jenis_unit`) VALUES
(1, 'TEKNOLOGI INFORMASI', 1),
(2, 'TEKNIK', 1),
(3, 'PERPUSTAKAAN', 2),
(4, 'POLIKLINIK', 2);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `tb_beranda`
--
ALTER TABLE `tb_beranda`
  ADD PRIMARY KEY (`id_b`);

--
-- Indexes for table `tb_detail_simulasi`
--
ALTER TABLE `tb_detail_simulasi`
  ADD PRIMARY KEY (`id_detail_simulasi`);

--
-- Indexes for table `tb_hasil`
--
ALTER TABLE `tb_hasil`
  ADD PRIMARY KEY (`id_hasil`);

--
-- Indexes for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  ADD PRIMARY KEY (`id_kriteria`);

--
-- Indexes for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  ADD PRIMARY KEY (`id_nilai`);

--
-- Indexes for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  ADD PRIMARY KEY (`id_pengguna`);

--
-- Indexes for table `tb_referensi`
--
ALTER TABLE `tb_referensi`
  ADD PRIMARY KEY (`id_ref`);

--
-- Indexes for table `tb_simulasi`
--
ALTER TABLE `tb_simulasi`
  ADD PRIMARY KEY (`id_simulasi`);

--
-- Indexes for table `tb_subkriteria`
--
ALTER TABLE `tb_subkriteria`
  ADD PRIMARY KEY (`id_subkriteria`);

--
-- Indexes for table `tb_unit`
--
ALTER TABLE `tb_unit`
  ADD PRIMARY KEY (`id_unit`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `tb_beranda`
--
ALTER TABLE `tb_beranda`
  MODIFY `id_b` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_detail_simulasi`
--
ALTER TABLE `tb_detail_simulasi`
  MODIFY `id_detail_simulasi` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_hasil`
--
ALTER TABLE `tb_hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_kriteria`
--
ALTER TABLE `tb_kriteria`
  MODIFY `id_kriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `tb_nilai`
--
ALTER TABLE `tb_nilai`
  MODIFY `id_nilai` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_pengguna`
--
ALTER TABLE `tb_pengguna`
  MODIFY `id_pengguna` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `tb_referensi`
--
ALTER TABLE `tb_referensi`
  MODIFY `id_ref` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_simulasi`
--
ALTER TABLE `tb_simulasi`
  MODIFY `id_simulasi` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `tb_subkriteria`
--
ALTER TABLE `tb_subkriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;
--
-- AUTO_INCREMENT for table `tb_unit`
--
ALTER TABLE `tb_unit`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
