-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 03, 2025 at 11:56 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `db_perpustakaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `buku`
--

CREATE TABLE `buku` (
  `id_buku` int(11) NOT NULL,
  `judul_buku` varchar(125) NOT NULL,
  `kategori_buku` varchar(125) NOT NULL,
  `penerbit_buku` varchar(125) NOT NULL,
  `pengarang` varchar(125) NOT NULL,
  `tahun_terbit` varchar(125) NOT NULL,
  `isbn` int(50) NOT NULL,
  `j_buku_baik` varchar(125) NOT NULL,
  `j_buku_rusak` varchar(125) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku`
--

INSERT INTO `buku` (`id_buku`, `judul_buku`, `kategori_buku`, `penerbit_buku`, `pengarang`, `tahun_terbit`, `isbn`, `j_buku_baik`, `j_buku_rusak`, `barcode`) VALUES
(1, 'Tes', 'Sastra', 'Gunawan', 'Tes', '2006', 3124, '11', '1', NULL),
(2, 'hjvhjv', 'Sastra', 'Gunawan', 'aadad', '2021', 2627362, '2', '1', NULL),
(3, 'hjvhjv', 'Sastra', 'Gunawan', 'aadad', '2016', 12344, '11', '1', NULL),
(4, '34354hyhhy', 'Sains', 'Gunawan', 'Tes', '2017', 15545, '3', '1', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `buku_kurikulum`
--

CREATE TABLE `buku_kurikulum` (
  `id_buku_kurikulum` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `id_kurikulum` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku_kurikulum`
--

INSERT INTO `buku_kurikulum` (`id_buku_kurikulum`, `id_buku`, `id_kurikulum`) VALUES
(1, 1, 1),
(2, 2, 1),
(3, 3, 1);

-- --------------------------------------------------------

--
-- Table structure for table `buku_unit`
--

CREATE TABLE `buku_unit` (
  `id_buku_unit` int(11) NOT NULL,
  `id_buku` int(11) NOT NULL,
  `barcode` varchar(50) NOT NULL,
  `kondisi` enum('baik','rusak') NOT NULL DEFAULT 'baik',
  `status` enum('tersedia','dipinjam') NOT NULL DEFAULT 'tersedia'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `buku_unit`
--

INSERT INTO `buku_unit` (`id_buku_unit`, `id_buku`, `barcode`, `kondisi`, `status`) VALUES
(1, 1, 'BK00001-001', 'baik', 'tersedia'),
(2, 2, 'BK00002-001', 'baik', 'tersedia'),
(3, 3, 'BK00003-001', 'baik', 'tersedia'),
(4, 4, 'BK00004-001', 'baik', 'tersedia'),
(5, 1, 'BK00001-002', 'baik', 'tersedia'),
(6, 2, 'BK00002-002', 'baik', 'tersedia'),
(7, 3, 'BK00003-002', 'baik', 'tersedia'),
(8, 4, 'BK00004-002', 'baik', 'tersedia'),
(9, 1, 'BK00001-003', 'baik', 'dipinjam'),
(10, 3, 'BK00003-003', 'baik', 'tersedia'),
(11, 4, 'BK00004-003', 'baik', 'tersedia'),
(12, 1, 'BK00001-004', 'baik', 'tersedia'),
(13, 3, 'BK00003-004', 'baik', 'tersedia'),
(14, 1, 'BK00001-005', 'baik', 'tersedia'),
(15, 3, 'BK00003-005', 'baik', 'tersedia'),
(16, 1, 'BK00001-006', 'baik', 'tersedia'),
(17, 3, 'BK00003-006', 'baik', 'tersedia'),
(18, 1, 'BK00001-007', 'baik', 'tersedia'),
(19, 3, 'BK00003-007', 'baik', 'tersedia'),
(20, 1, 'BK00001-008', 'baik', 'tersedia'),
(21, 3, 'BK00003-008', 'baik', 'tersedia'),
(22, 1, 'BK00001-009', 'baik', 'tersedia'),
(23, 3, 'BK00003-009', 'baik', 'tersedia'),
(24, 1, 'BK00001-010', 'baik', 'tersedia'),
(25, 3, 'BK00003-010', 'baik', 'tersedia'),
(26, 1, 'BK00001-011', 'baik', 'tersedia'),
(27, 3, 'BK00003-011', 'baik', 'tersedia');

-- --------------------------------------------------------

--
-- Table structure for table `identitas`
--

CREATE TABLE `identitas` (
  `id_identitas` int(11) NOT NULL,
  `nama_app` varchar(50) NOT NULL,
  `alamat_app` text NOT NULL,
  `email_app` varchar(125) NOT NULL,
  `nomor_hp` char(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `identitas`
--

INSERT INTO `identitas` (`id_identitas`, `nama_app`, `alamat_app`, `email_app`, `nomor_hp`) VALUES
(1, 'MTsn 1 Luwu', 'Pendidikan No.1 No.5, Bajo, Kec. Belopa, Kabupaten Luwu, Sulawesi Selatan 91995\nno tlp: (0471) 3314365', 'contact@e-perpus.com', '0228298492');

-- --------------------------------------------------------

--
-- Table structure for table `kategori`
--

CREATE TABLE `kategori` (
  `id_kategori` int(11) NOT NULL,
  `kode_kategori` varchar(50) NOT NULL,
  `nama_kategori` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kategori`
--

INSERT INTO `kategori` (`id_kategori`, `kode_kategori`, `nama_kategori`) VALUES
(1, 'KT-001', 'Sastra'),
(2, 'KT-002', 'Sains');

-- --------------------------------------------------------

--
-- Table structure for table `kurikulum`
--

CREATE TABLE `kurikulum` (
  `id_kurikulum` int(11) NOT NULL,
  `kode_kurikulum` varchar(50) NOT NULL,
  `nama_kurikulum` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `kurikulum`
--

INSERT INTO `kurikulum` (`id_kurikulum`, `kode_kurikulum`, `nama_kurikulum`) VALUES
(1, 'KK-001', '2013'),
(2, 'KK-002', 'KTSP');

-- --------------------------------------------------------

--
-- Table structure for table `pemberitahuan`
--

CREATE TABLE `pemberitahuan` (
  `id_pemberitahuan` int(11) NOT NULL,
  `isi_pemberitahuan` varchar(255) NOT NULL,
  `level_user` varchar(125) NOT NULL,
  `status` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pemberitahuan`
--

INSERT INTO `pemberitahuan` (`id_pemberitahuan`, `isi_pemberitahuan`, `level_user`, `status`) VALUES
(1, '<i class=\'fa fa-exchange\'></i> #Gunawan Telah meminjam Buku', 'Admin', 'Sudah dibaca'),
(2, '<i class=\'fa fa-repeat\'></i> #Gunawan Telah mengembalikan Buku', 'Admin', 'Sudah dibaca');

-- --------------------------------------------------------

--
-- Table structure for table `peminjaman`
--

CREATE TABLE `peminjaman` (
  `id_peminjaman` int(11) NOT NULL,
  `nama_anggota` varchar(125) NOT NULL,
  `judul_buku` varchar(125) NOT NULL,
  `tanggal_peminjaman` varchar(125) NOT NULL,
  `tanggal_pengembalian` varchar(50) NOT NULL,
  `kondisi_buku_saat_dipinjam` varchar(125) NOT NULL,
  `kondisi_buku_saat_dikembalikan` varchar(125) NOT NULL,
  `denda` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `peminjaman`
--

INSERT INTO `peminjaman` (`id_peminjaman`, `nama_anggota`, `judul_buku`, `tanggal_peminjaman`, `tanggal_pengembalian`, `kondisi_buku_saat_dipinjam`, `kondisi_buku_saat_dikembalikan`, `denda`) VALUES
(1, 'Gunawan', 'Tes', '18-07-2025', '18-07-2025', 'Baik', 'Baik', 'Tidak ada'),
(2, 'sadadsfs', 'Tes', '2025-07-27', '2025-08-03', 'baik', 'baik', '0'),
(3, 'Gunawan', 'Tes', '2025-07-27', '2025-08-17', 'baik', 'baik', '0'),
(4, 'Gunawan', 'Tes', '2025-07-23', '2025-08-03', 'baik', 'baik', '5000'),
(5, 'Gunawan', 'Tes', '2025-08-03', '2025-08-10', 'baik', 'baik', '0'),
(6, 'Gunawan', 'Tes', '2025-08-03', '2025-08-13', 'baik', 'baik', '0'),
(7, 'Gunawan', 'Tes', '2025-08-03', '2025-08-10', 'baik', 'rusak', '0'),
(8, 'Gunawan', 'Tes', '2025-08-03', '2025-08-10', 'baik', 'baik', '0'),
(9, 'Gunawan', 'Tes', '2025-08-03', '2025-08-10', 'baik', 'rusak', '0');

-- --------------------------------------------------------

--
-- Table structure for table `penerbit`
--

CREATE TABLE `penerbit` (
  `id_penerbit` int(11) NOT NULL,
  `kode_penerbit` varchar(125) NOT NULL,
  `nama_penerbit` varchar(50) NOT NULL,
  `verif_penerbit` varchar(25) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `penerbit`
--

INSERT INTO `penerbit` (`id_penerbit`, `kode_penerbit`, `nama_penerbit`, `verif_penerbit`) VALUES
(1, 'P001', 'Gunawan', 'Terverifikasi');

-- --------------------------------------------------------

--
-- Table structure for table `pesan`
--

CREATE TABLE `pesan` (
  `id_pesan` int(11) NOT NULL,
  `penerima` varchar(50) NOT NULL,
  `pengirim` varchar(50) NOT NULL,
  `judul_pesan` varchar(50) NOT NULL,
  `isi_pesan` text NOT NULL,
  `status` varchar(50) NOT NULL,
  `tanggal_kirim` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `pesan`
--

INSERT INTO `pesan` (`id_pesan`, `penerima`, `pengirim`, `judul_pesan`, `isi_pesan`, `status`, `tanggal_kirim`) VALUES
(1, 'Gunawan', 'Administrator', 'Izin Bertanya?', 'Izin Bertanya? Pak', 'Sudah dibaca', '08-01-2022');

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id_user` int(11) NOT NULL,
  `kode_user` varchar(25) NOT NULL,
  `nis` char(20) NOT NULL,
  `fullname` varchar(125) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(50) NOT NULL,
  `kelas` varchar(50) NOT NULL,
  `alamat` varchar(225) NOT NULL,
  `foto` varchar(255) DEFAULT 'default-avatar.png',
  `verif` varchar(50) NOT NULL,
  `role` varchar(50) NOT NULL,
  `join_date` varchar(125) NOT NULL,
  `terakhir_login` varchar(125) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id_user`, `kode_user`, `nis`, `fullname`, `username`, `password`, `kelas`, `alamat`, `foto`, `verif`, `role`, `join_date`, `terakhir_login`) VALUES
(1, '-', '-', 'Administrator', 'admin', 'admin', '-', '-', 'default-avatar.png', 'Iya', 'Admin', '04-05-2021', '03-08-2025 ( 20:23:40 )'),
(2, 'AP001', '123', 'Gunawan', 'gunawan', 'Gunawan', 'Guru', 'Bandungs', 'user_2_1753623601.jpg', 'Tidak', 'Anggota', '08-01-2022', '04-08-2025 ( 04:40:23 )'),
(3, 'AP002', '23232424242424242424', 'sadadsfs', 'ikyalfito ', 'ikyalfito', 'XII - Farmasi', 'sdsdsds', 'default-avatar.png', 'Tidak', 'Anggota', '24-07-2025', ''),
(4, 'AP003', '232434', 'sadad', 'fito', 'ikyalfito', 'VIII e', 'asasa', 'default-avatar.png', 'Tidak', 'Anggota', '27-07-2025', ''),
(5, 'AP004', '43546464', 'sadadsfs', 'perpusjkt', 'ikyalfito', 'VIII d', 'effefe', 'default-avatar.png', 'Tidak', 'Anggota', '27-07-2025', ''),
(6, 'AP005', '2435353', 'aaaaaaaa', 'user', 'user', 'VIII d', 'asaff', 'default-avatar.png', 'Tidak', 'Anggota', '27-07-2025', ''),
(7, 'AP006', '25666', 'afif', 'afif', '123', 'VIII c', 'afif', 'default-avatar.png', 'Tidak', 'Anggota', '27-07-2025', ''),
(8, 'AP007', '233545', 'dora', 'dora', 'ikyalfito', 'VIII f', 'asadsf', 'user_8_1753624197.png', 'Tidak', 'Anggota', '27-07-2025', '27-07-2025 ( 20:49:46 )'),
(9, 'AP008', '12321243', 'adam', 'fito', '123', 'VIII g', '123', 'default-avatar.png', 'Tidak', 'Anggota', '27-07-2025', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `buku`
--
ALTER TABLE `buku`
  ADD PRIMARY KEY (`id_buku`),
  ADD UNIQUE KEY `barcode` (`barcode`);

--
-- Indexes for table `buku_kurikulum`
--
ALTER TABLE `buku_kurikulum`
  ADD PRIMARY KEY (`id_buku_kurikulum`),
  ADD KEY `id_buku` (`id_buku`),
  ADD KEY `id_kurikulum` (`id_kurikulum`);

--
-- Indexes for table `buku_unit`
--
ALTER TABLE `buku_unit`
  ADD PRIMARY KEY (`id_buku_unit`),
  ADD UNIQUE KEY `barcode` (`barcode`),
  ADD KEY `id_buku` (`id_buku`);

--
-- Indexes for table `identitas`
--
ALTER TABLE `identitas`
  ADD PRIMARY KEY (`id_identitas`);

--
-- Indexes for table `kategori`
--
ALTER TABLE `kategori`
  ADD PRIMARY KEY (`id_kategori`);

--
-- Indexes for table `kurikulum`
--
ALTER TABLE `kurikulum`
  ADD PRIMARY KEY (`id_kurikulum`);

--
-- Indexes for table `pemberitahuan`
--
ALTER TABLE `pemberitahuan`
  ADD PRIMARY KEY (`id_pemberitahuan`);

--
-- Indexes for table `peminjaman`
--
ALTER TABLE `peminjaman`
  ADD PRIMARY KEY (`id_peminjaman`);

--
-- Indexes for table `penerbit`
--
ALTER TABLE `penerbit`
  ADD PRIMARY KEY (`id_penerbit`);

--
-- Indexes for table `pesan`
--
ALTER TABLE `pesan`
  ADD PRIMARY KEY (`id_pesan`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id_user`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `buku`
--
ALTER TABLE `buku`
  MODIFY `id_buku` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `buku_kurikulum`
--
ALTER TABLE `buku_kurikulum`
  MODIFY `id_buku_kurikulum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `buku_unit`
--
ALTER TABLE `buku_unit`
  MODIFY `id_buku_unit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `identitas`
--
ALTER TABLE `identitas`
  MODIFY `id_identitas` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kategori`
--
ALTER TABLE `kategori`
  MODIFY `id_kategori` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `kurikulum`
--
ALTER TABLE `kurikulum`
  MODIFY `id_kurikulum` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `pemberitahuan`
--
ALTER TABLE `pemberitahuan`
  MODIFY `id_pemberitahuan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `peminjaman`
--
ALTER TABLE `peminjaman`
  MODIFY `id_peminjaman` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `penerbit`
--
ALTER TABLE `penerbit`
  MODIFY `id_penerbit` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pesan`
--
ALTER TABLE `pesan`
  MODIFY `id_pesan` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id_user` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `buku_kurikulum`
--
ALTER TABLE `buku_kurikulum`
  ADD CONSTRAINT `buku_kurikulum_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE,
  ADD CONSTRAINT `buku_kurikulum_ibfk_2` FOREIGN KEY (`id_kurikulum`) REFERENCES `kurikulum` (`id_kurikulum`) ON DELETE CASCADE;

--
-- Constraints for table `buku_unit`
--
ALTER TABLE `buku_unit`
  ADD CONSTRAINT `buku_unit_ibfk_1` FOREIGN KEY (`id_buku`) REFERENCES `buku` (`id_buku`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
