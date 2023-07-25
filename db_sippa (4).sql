-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jul 25, 2023 at 06:43 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 7.4.29

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
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

--
-- Dumping data for table `tb_detail_simulasi`
--

INSERT INTO `tb_detail_simulasi` (`id_detail_simulasi`, `id_simulasi`, `id_unit`, `id_kriteria`, `id_subkriteria`, `nilai_input`, `nilai_rata_rata`, `nilai_bobot`) VALUES
(192, 8, 22, 3, 12, 9, 0.36, 0.288),
(193, 8, 22, 3, 18, 9, 0.36, 0.072),
(194, 8, 23, 3, 12, 8, 0.32, 0.256),
(195, 8, 23, 3, 18, 8, 0.32, 0.064),
(196, 8, 24, 3, 12, 8, 0.32, 0.256),
(197, 8, 24, 3, 18, 8, 0.32, 0.064),
(325, 37, 24, 3, 12, 9, 0.45, 0.36),
(326, 37, 24, 3, 18, 7, 0.259259, 0.0518519),
(327, 37, 22, 3, 12, 10, 0.5, 0.4),
(328, 37, 22, 3, 18, 10, 0.37037, 0.0740741),
(385, 37, 23, 3, 12, 1, 0.05, 0.04),
(399, 37, 23, 3, 18, 10, 0.37037, 0.0740741),
(400, 50, 22, 3, 12, 10, 0.588235, 0.470588),
(401, 50, 22, 3, 18, 10, 0.5, 0.1),
(402, 50, 23, 3, 12, 7, 0.411765, 0.329412),
(403, 50, 23, 3, 18, 10, 0.5, 0.1),
(452, 63, 14, 1, 3, 7, 1, 0.15),
(453, 63, 14, 1, 1, 10, 1, 0.5),
(454, 63, 14, 1, 2, 10, 1, 0.3),
(455, 63, 14, 2, 5, 10, 1, 0.2),
(456, 63, 14, 2, 4, 0.3, 1, 0.8),
(457, 64, 15, 1, 1, 70, 0.0302376, 0.0151188),
(458, 64, 15, 1, 3, 3, 0.333333, 0.05),
(459, 64, 15, 2, 4, 0.4, 0.489796, 0.391837),
(460, 64, 15, 1, 2, 7, 0.0972222, 0.0291667),
(461, 64, 15, 1, 19, 0, 0, 0),
(462, 64, 15, 2, 5, 8, 0.470588, 0.0941176),
(463, 64, 19, 1, 1, 2245, 0.969762, 0.484881),
(464, 64, 19, 1, 2, 65, 0.902778, 0.270833),
(465, 64, 19, 1, 3, 6, 0.666667, 0.1),
(466, 64, 19, 1, 19, 2, 1, 0.05),
(467, 64, 19, 2, 4, 0.416667, 0.510204, 0.408164),
(468, 64, 19, 2, 5, 9, 0.529412, 0.105882);

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

--
-- Dumping data for table `tb_hasil`
--

INSERT INTO `tb_hasil` (`id_hasil`, `id_simulasi`, `id_unit`, `nilai_persentase`, `nilai_anggaran`) VALUES
(41, 8, 22, 0.36, 72000000),
(42, 8, 23, 0.32, 64000000),
(43, 8, 24, 0.32, 64000000),
(116, 37, 22, 53.87, 1616100000),
(117, 37, 23, 0, 0),
(118, 37, 24, 46.13, 1383900000),
(151, 50, 22, 0.570588, 228000000),
(152, 50, 23, 0.429412, 172000000),
(153, 50, 24, 0, 0),
(173, 63, 14, 96, 48000000),
(174, 63, 15, 0, 0),
(175, 63, 16, 0, 0),
(176, 63, 17, 0, 0),
(177, 63, 18, 0, 0),
(178, 63, 19, 0, 0),
(179, 63, 20, 0, 0),
(180, 63, 21, 0, 0),
(181, 64, 14, 0, 0),
(182, 64, 15, 17.2619, 69040000),
(183, 64, 16, 0, 0),
(184, 64, 17, 0, 0),
(185, 64, 18, 0, 0),
(186, 64, 19, 82.7381, 330960000),
(187, 64, 20, 0, 0),
(188, 64, 21, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `tb_history_prodi`
--

CREATE TABLE `tb_history_prodi` (
  `id_history` bigint(20) NOT NULL,
  `id_simulasi` int(11) NOT NULL,
  `jurusan` varchar(50) NOT NULL,
  `prodi` varchar(50) NOT NULL,
  `akreditasi` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_history_prodi`
--

INSERT INTO `tb_history_prodi` (`id_history`, `id_simulasi`, `jurusan`, `prodi`, `akreditasi`) VALUES
(96, 63, 'PRODUKSI PERTANIAN', 'Produksi Tanaman Perkebunan', '-'),
(97, 63, 'PRODUKSI PERTANIAN', 'Produksi Tanaman Hortikultura', '-'),
(98, 63, 'PRODUKSI PERTANIAN', 'Budidaya Tanaman Perkebunan', '-'),
(99, 63, 'PRODUKSI PERTANIAN', 'Teknologi Produksi Tanaman Pangan', '-'),
(100, 63, 'PRODUKSI PERTANIAN', 'Teknologi Produksi Benih', '-'),
(101, 63, 'PRODUKSI PERTANIAN', 'Pengelolaan Perkebunan Kopi', '-'),
(102, 63, 'PRODUKSI PERTANIAN', 'Prodi Tambahan', '-'),
(103, 64, 'TEKNOLOGI PERTANIAN', 'Teknologi Rekayasa Pangan', 'B'),
(104, 64, 'TEKNOLOGI PERTANIAN', 'Teknologi Industri Pangan', 'B'),
(105, 64, 'TEKNOLOGI PERTANIAN', 'Keteknikan Pertanian', 'B'),
(106, 64, 'TEKNOLOGI INFORMASI', 'Teknik Informatika (PSDKU Sidoarjo)', 'BAIK'),
(107, 64, 'TEKNOLOGI INFORMASI', 'Teknik Informatika (PSDKU Nganjuk)', 'B'),
(108, 64, 'TEKNOLOGI INFORMASI', 'Teknik Komputer', 'A'),
(109, 64, 'TEKNOLOGI INFORMASI', 'Managemen Informatika', 'A'),
(110, 64, 'TEKNOLOGI INFORMASI', 'Teknik Informatika Reguler Kampus Utama', 'A'),
(111, 64, 'TEKNOLOGI INFORMASI', 'Bisnis Digital', 'BAIK');

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
(1, 'Kriteria Entitas Jurusan', 80, 'jurusan'),
(2, 'Kriteria Capaian Jurusan ', 20, 'jurusan'),
(3, 'Kriteria Capaian Non Jurusan', 100, 'nonjurusan');

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
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_pengguna`
--

INSERT INTO `tb_pengguna` (`id_pengguna`, `username`, `nama_lengkap`, `password_pengguna`, `level`, `id_unit`, `created_at`) VALUES
(1, 'pimpinan', 'Pimpinan', '27d7b07b58fc2fee70c4c01e9f129462', 1, 1, '2022-06-23 00:55:02'),
(2, 'superadmin', 'Satya', '27d7b07b58fc2fee70c4c01e9f129462', 2, 2, '2022-06-23 00:55:02');

-- --------------------------------------------------------

--
-- Table structure for table `tb_prodi`
--

CREATE TABLE `tb_prodi` (
  `id_prodi` int(11) NOT NULL,
  `id_unit` int(11) NOT NULL,
  `nama_prodi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_prodi`
--

INSERT INTO `tb_prodi` (`id_prodi`, `id_unit`, `nama_prodi`) VALUES
(1, 19, 'Teknik Informatika (PSDKU Sidoarjo)'),
(2, 19, 'Teknik Informatika (PSDKU Nganjuk)'),
(3, 15, 'Teknologi Rekayasa Pangan'),
(5, 19, 'Teknik Komputer'),
(6, 19, 'Managemen Informatika'),
(7, 19, 'Teknik Informatika Reguler Kampus Utama'),
(9, 19, 'Bisnis Digital'),
(10, 14, 'Produksi Tanaman Perkebunan'),
(12, 14, 'Produksi Tanaman Hortikultura'),
(13, 14, 'Budidaya Tanaman Perkebunan'),
(14, 14, 'Teknologi Produksi Tanaman Pangan'),
(15, 14, 'Teknologi Produksi Benih'),
(16, 14, 'Pengelolaan Perkebunan Kopi'),
(17, 15, 'Teknologi Industri Pangan'),
(18, 15, 'Keteknikan Pertanian'),
(19, 16, 'Produksi Ternak'),
(20, 16, 'Managemen Bisnis Unggas'),
(21, 16, 'Teknologi Pakan Ternak'),
(24, 14, 'Prodi Tambahan'),
(31, 20, 'Prodi Tambahan');

-- --------------------------------------------------------

--
-- Table structure for table `tb_referensi`
--

CREATE TABLE `tb_referensi` (
  `id_ref` int(11) NOT NULL,
  `keterangan` varchar(255) NOT NULL,
  `nama_file` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `tb_referensi`
--

INSERT INTO `tb_referensi` (`id_ref`, `keterangan`, `nama_file`) VALUES
(3, 'OTK Polije No 19 Tahun 2022', '1673858038990.pdf'),
(4, 'Manual Book (SIPPA)', '1675137191964.pdf');

-- --------------------------------------------------------

--
-- Table structure for table `tb_simulasi`
--

CREATE TABLE `tb_simulasi` (
  `id_simulasi` int(11) NOT NULL,
  `tahun_simulasi` int(11) NOT NULL,
  `nominal_anggaran` float NOT NULL,
  `jenis_simulasi` int(11) NOT NULL COMMENT '1 = Jurusan / 2 = Non Jurusan',
  `isApproval` int(11) NOT NULL COMMENT '0=NotApprove; 1=Waiting; 2=Approve',
  `keterangan` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `tb_simulasi`
--

INSERT INTO `tb_simulasi` (`id_simulasi`, `tahun_simulasi`, `nominal_anggaran`, `jenis_simulasi`, `isApproval`, `keterangan`) VALUES
(8, 2023, 200000000, 2, 2, 'pp'),
(37, 2025, 3000000000, 2, 0, ''),
(50, 2024, 400000000, 2, 0, ''),
(63, 2023, 50000000, 1, 0, ''),
(64, 2024, 400000000, 1, 0, '');

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
(3, 1, 'Jumlah Prodi', 15, 'jurusan'),
(4, 2, 'Capaian Akreditasi Prodi', 80, 'jurusan'),
(5, 2, 'Capaian Kinerja ', 20, 'jurusan'),
(12, 3, 'Capaian Kinerja', 80, 'nonjurusan'),
(18, 3, 'Realisasi Anggaran', 20, 'nonjurusan'),
(19, 1, 'Jumlah Prodi Internasional', 5, 'jurusan');

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
(14, 'PRODUKSI PERTANIAN', 1),
(15, 'TEKNOLOGI PERTANIAN', 1),
(16, 'PETERNAKAN', 1),
(17, 'MANAJEMEN AGRIBISNIS', 1),
(18, 'BAHASA, KOMUNIKASI, DAN PARIWISATA', 1),
(19, 'TEKNOLOGI INFORMASI', 1),
(20, 'KESEHATAN', 1),
(21, 'TEKNIK', 1),
(22, 'SUBAG. PERENCANAAN', 2),
(23, 'SUBAG. AKADEMIK', 2),
(24, 'SUBAG. KEMAHASISWAAN', 2);

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
-- Indexes for table `tb_history_prodi`
--
ALTER TABLE `tb_history_prodi`
  ADD PRIMARY KEY (`id_history`),
  ADD KEY `id_simulasi` (`id_simulasi`);

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
-- Indexes for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  ADD PRIMARY KEY (`id_prodi`),
  ADD KEY `id_unit` (`id_unit`);

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
  MODIFY `id_detail_simulasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=469;

--
-- AUTO_INCREMENT for table `tb_hasil`
--
ALTER TABLE `tb_hasil`
  MODIFY `id_hasil` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=189;

--
-- AUTO_INCREMENT for table `tb_history_prodi`
--
ALTER TABLE `tb_history_prodi`
  MODIFY `id_history` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=112;

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
-- AUTO_INCREMENT for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  MODIFY `id_prodi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- AUTO_INCREMENT for table `tb_referensi`
--
ALTER TABLE `tb_referensi`
  MODIFY `id_ref` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tb_simulasi`
--
ALTER TABLE `tb_simulasi`
  MODIFY `id_simulasi` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=65;

--
-- AUTO_INCREMENT for table `tb_subkriteria`
--
ALTER TABLE `tb_subkriteria`
  MODIFY `id_subkriteria` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `tb_unit`
--
ALTER TABLE `tb_unit`
  MODIFY `id_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `tb_history_prodi`
--
ALTER TABLE `tb_history_prodi`
  ADD CONSTRAINT `tb_history_prodi_ibfk_1` FOREIGN KEY (`id_simulasi`) REFERENCES `tb_simulasi` (`id_simulasi`);

--
-- Constraints for table `tb_prodi`
--
ALTER TABLE `tb_prodi`
  ADD CONSTRAINT `tb_prodi_ibfk_1` FOREIGN KEY (`id_unit`) REFERENCES `tb_unit` (`id_unit`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
