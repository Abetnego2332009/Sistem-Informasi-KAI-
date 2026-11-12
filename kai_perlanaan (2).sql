-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Jun 08, 2026 at 07:21 AM
-- Server version: 11.4.8-MariaDB-log
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `kai_perlanaan`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(150) NOT NULL,
  `password` varchar(255) NOT NULL COMMENT 'bcrypt hash',
  `foto` varchar(255) DEFAULT NULL,
  `role` enum('superadmin','admin') NOT NULL DEFAULT 'admin',
  `last_login` datetime DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `nama`, `username`, `email`, `password`, `foto`, `role`, `last_login`, `created_at`, `updated_at`) VALUES
(1, 'Super Admin KAI', 'superadmin', 'superadmin@gmail.com', '$2y$10$Xwx97JAogGnPRqm4y7ce8.fqncvG6Gh1sw.zRd.Ng4TUtEr1VyKCe', 'admin_1779258564_825.jpg', 'superadmin', '2026-06-05 17:54:23', '2026-04-28 19:37:10', '2026-06-05 17:54:23'),
(5, 'Abdul', 'abdul', 'abdul@gmail.com', '$2y$10$Wew9SeUASu9GNoiGoqG9Duma6EBYAxYIkzvRI9hGsG4YD6mOCN2E.', NULL, 'admin', '2026-05-18 01:25:46', '2026-05-04 13:58:35', '2026-05-18 01:32:42');

-- --------------------------------------------------------

--
-- Table structure for table `berita`
--

CREATE TABLE `berita` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `ringkasan` text DEFAULT NULL,
  `isi` longtext NOT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `kategori` enum('Berita','Promo','Informasi','Kegiatan') NOT NULL DEFAULT 'Berita',
  `tgl_tayang` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `views` int(11) NOT NULL DEFAULT 0,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `berita`
--

INSERT INTO `berita` (`id`, `judul`, `slug`, `ringkasan`, `isi`, `foto`, `kategori`, `tgl_tayang`, `is_active`, `views`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'KAI Resmikan Fasilitas Ruang Tunggu Baru di Stasiun Perlanaan', 'kai-resmikan-fasilitas-ruang-tunggu-baru-stasiun-perlanaan', 'Stasiun Perlanaan kini memiliki ruang tunggu yang lebih luas, nyaman, dan dilengkapi AC serta colokan listrik gratis.', '<p>PT Kereta Api Indonesia (Persero) Divisi Regional I Sumatera Utara meresmikan fasilitas ruang tunggu baru di Stasiun Perlanaan.</p>', NULL, 'Berita', '2025-07-15', 1, 0, 1, '2026-04-28 19:37:10', '2026-05-18 00:04:59'),
(2, 'Promo Diskon 25% Tiket Ekonomi Agustus 2025', 'promo-diskon-25-persen-tiket-ekonomi-agustus-2025', 'Dapatkan potongan harga 25% untuk tiket kelas Ekonomi seluruh rute dari Stasiun Perlanaan, berlaku 1ï¿½10 Agustus 2025.', '<p>Dalam rangka merayakan HUT Kemerdekaan RI, KAI memberikan diskon spesial 25% untuk tiket kelas Ekonomi berlaku 1ï¿½10 Agustus 2025.</p>', NULL, 'Promo', '2025-07-18', 1, 389, 1, '2026-04-28 19:37:10', '2026-05-18 00:25:56'),
(3, 'KAI Tambah Armada Kereta Lokal Rute Medanï¿½Rantauprapat', 'kai-tambah-armada-kereta-lokal-medan-rantauprapat', 'Mulai September 2025, KAI akan mengoperasikan satu rangkaian kereta lokal tambahan rute Medanï¿½Rantauprapat.', '<p>Untuk mengakomodasi kebutuhan mobilitas masyarakat, KAI Divre I menambah satu rangkaian kereta lokal rute Medanï¿½Rantauprapat.</p>', NULL, 'Informasi', '2025-07-22', 1, 87, NULL, '2026-04-28 19:37:10', '2026-05-18 00:26:02');

-- --------------------------------------------------------

--
-- Table structure for table `faq`
--

CREATE TABLE `faq` (
  `id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `jawaban` text NOT NULL,
  `kategori` varchar(100) DEFAULT 'Umum',
  `urutan` tinyint(4) NOT NULL DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `faq`
--

INSERT INTO `faq` (`id`, `pertanyaan`, `jawaban`, `kategori`, `urutan`, `is_active`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'Bagaimana cara membeli tiket kereta dari Stasiun Perlanaan?', 'Tiket dapat dibeli di loket stasiun (05.00ï¿½21.00 WIB), aplikasi KAI Access, website kai.id, atau mitra resmi.', 'Tiket', 1, 1, 1, '2026-04-28 19:37:10', '2026-05-04 22:06:49'),
(2, 'Apakah tiket bisa dibatalkan atau diubah jadwalnya?', 'Ya, pembatalan dan perubahan jadwal dapat dilakukan maksimal 30 menit sebelum keberangkatan. Biaya admin 25% untuk pembatalan.', 'Tiket', 2, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(3, 'Berapa batas bawaan bagasi yang diizinkan?', 'Bagasi gratis: Ekonomi 20 kg, Bisnis 30 kg, Eksekutif 40 kg. Kelebihan dikenakan biaya tambahan.', 'Bagasi', 3, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(4, 'Apakah anak-anak memerlukan tiket sendiri?', 'Anak <3 tahun tidak perlu tiket (dipangku). Anak 3–10 tahun 50% tarif dewasa. Di atas 10 tahun tarif penuh.', 'Tiket', 4, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(5, 'Apa yang harus dilakukan jika kereta terlambat lebih dari 60 menit?', 'Penumpang berhak mengajukan pembatalan dan mendapatkan refund penuh tanpa potongan. Hubungi loket atau Call Center 121.', 'Jadwal', 5, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(6, 'Apakah tersedia fasilitas untuk penumpang berkebutuhan khusus?', 'Ya, tersedia akses ramp kursi roda, toilet difabel, dan prioritas loket untuk penumpang berkebutuhan khusus.', 'Fasilitas', 6, 1, 1, '2026-04-28 19:37:10', '2026-05-02 04:31:13'),
(7, 'Bagaimana cara menghubungi petugas stasiun?', 'Konter informasi, telepon (0621) 441-2871, atau Call Center KAI 24 jam: 121 / WhatsApp 0811-1211-900.', 'Kontak', 7, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(8, 'Apakah ada layanan antar-jemput dari stasiun?', 'Belum ada shuttle resmi. Tersedia ojek, angkutan umum, atau kendaraan pribadi. Parkir 24 jam tersedia.', 'Fasilitas', 8, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `fasilitas`
--

CREATE TABLE `fasilitas` (
  `id` int(11) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `ikon` varchar(50) DEFAULT NULL COMMENT 'Bootstrap Icons class name',
  `deskripsi` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` tinyint(4) NOT NULL DEFAULT 0,
  `admin_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `fasilitas`
--

INSERT INTO `fasilitas` (`id`, `nama`, `ikon`, `deskripsi`, `is_active`, `urutan`, `admin_id`, `updated_at`) VALUES
(1, 'Toilet Umum', 'bi-droplet-fill', 'Tersedia toilet pria dan wanita yang bersih', 1, 1, 1, '2026-04-28 19:37:10'),
(2, 'Mushola', 'bi-moon-stars-fill', 'Tempat ibadah yang bersih dan nyaman', 1, 2, 1, '2026-04-28 19:37:10'),
(3, 'Area Parkir', 'bi-p-circle-fill', 'Parkir kendaraan roda 2 dan roda 4', 1, 3, 1, '2026-04-28 19:37:10'),
(4, 'Loket Tiket', 'bi-ticket-perforated-fill', 'Pembelian tiket langsung di stasiun', 1, 4, 1, '2026-04-28 19:37:10'),
(5, 'WiFi Gratis', 'bi-wifi', 'Akses internet gratis di ruang tunggu', 1, 5, 1, '2026-04-28 19:37:10'),
(6, 'Stop Kontak', 'bi-lightning-charge-fill', 'Colokan daya tersedia di ruang tunggu', 1, 6, 1, '2026-04-28 19:37:10'),
(7, 'Akses Difabel', 'bi-universal-access', 'Ramp dan jalur khusus kursi roda', 1, 7, 1, '2026-04-28 19:37:10'),
(8, 'CCTV 24 Jam', 'bi-camera-video-fill', 'Pengawasan keamanan sepanjang hari', 1, 8, 1, '2026-04-28 19:37:10'),
(9, 'Kantin', 'bi-cup-hot-fill', 'Warung makan dan minuman', 1, 9, 1, '2026-04-28 19:37:10'),
(10, 'Petugas Keamanan', 'bi-shield-fill-check', 'Satpam berjaga sepanjang hari', 1, 10, 1, '2026-04-28 19:37:10'),
(11, 'Penitipan Bagasi', 'bi-bag-fill', 'Layanan simpan barang sementara', 1, 11, 1, '2026-04-28 19:37:10'),
(12, 'P3K', 'bi-heart-pulse-fill', 'Kotak pertolongan pertama tersedia', 1, 12, 1, '2026-04-28 19:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `harga_tiket`
--

CREATE TABLE `harga_tiket` (
  `id` int(11) NOT NULL,
  `stasiun_asal_id` int(11) NOT NULL,
  `stasiun_tujuan_id` int(11) NOT NULL,
  `kelas_id` int(11) NOT NULL,
  `harga` int(11) NOT NULL COMMENT 'Harga dalam Rupiah',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `harga_tiket`
--

INSERT INTO `harga_tiket` (`id`, `stasiun_asal_id`, `stasiun_tujuan_id`, `kelas_id`, `harga`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 2, 2, 85000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(5, 1, 3, 2, 50000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(6, 1, 3, 3, 90000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(7, 1, 4, 1, 60000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(8, 1, 4, 2, 110000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(12, 1, 5, 3, 110000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(14, 1, 6, 2, 28000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(15, 1, 6, 3, 50000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(19, 2, 1, 1, 45000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(20, 2, 1, 2, 85000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(23, 3, 1, 2, 50000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(24, 3, 1, 3, 90000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(25, 4, 1, 1, 60000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(26, 4, 1, 2, 110000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(29, 5, 1, 2, 60000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(30, 5, 1, 3, 110000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(32, 6, 1, 2, 28000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(33, 6, 1, 3, 50000, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(41, 1, 2, 1, 45000, 1, '2026-05-01 16:45:33', '2026-05-01 16:45:33'),
(42, 1, 3, 1, 30000, 1, '2026-05-01 16:45:34', '2026-05-04 11:06:41'),
(43, 1, 5, 1, 30000, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(44, 1, 5, 2, 60000, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(45, 1, 2, 3, 150000, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(46, 1, 4, 3, 190000, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `info_stasiun`
--

CREATE TABLE `info_stasiun` (
  `id` int(11) NOT NULL,
  `nama_stasiun` varchar(100) NOT NULL,
  `kode_stasiun` varchar(10) NOT NULL,
  `kelas_stasiun` enum('Besar','Madya','Kecil','Halte') NOT NULL DEFAULT 'Kecil',
  `alamat` text NOT NULL,
  `desa` varchar(100) DEFAULT NULL,
  `kecamatan` varchar(100) DEFAULT NULL,
  `kabupaten` varchar(100) DEFAULT NULL,
  `provinsi` varchar(100) DEFAULT NULL,
  `kode_pos` varchar(10) DEFAULT NULL,
  `latitude` decimal(10,7) DEFAULT NULL,
  `longitude` decimal(10,7) DEFAULT NULL,
  `telepon` varchar(25) DEFAULT NULL,
  `email` varchar(150) DEFAULT NULL,
  `website` varchar(200) DEFAULT NULL,
  `deskripsi` text DEFAULT NULL,
  `sejarah` text DEFAULT NULL,
  `jumlah_jalur` tinyint(4) DEFAULT 2,
  `ketinggian_mdpl` smallint(6) DEFAULT NULL,
  `divre` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `updated_by` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `info_stasiun`
--

INSERT INTO `info_stasiun` (`id`, `nama_stasiun`, `kode_stasiun`, `kelas_stasiun`, `alamat`, `desa`, `kecamatan`, `kabupaten`, `provinsi`, `kode_pos`, `latitude`, `longitude`, `telepon`, `email`, `website`, `deskripsi`, `sejarah`, `jumlah_jalur`, `ketinggian_mdpl`, `divre`, `foto`, `updated_by`, `updated_at`) VALUES
(1, 'Stasiun Perlanaan', 'PLN', 'Kecil', 'Desa Perlanaan, Kec. Sei Bamban, Kab. Serdang Bedagai, Sumatera Utara 20991', 'Perlanaan', 'Sei Bamban', 'Serdang Bedagai', 'Sumatera Utara', '20991', 3.3700000, 99.0300000, '(0621) 441-2871', 'info.perlanaan@kai.id', 'https://www.kai.id', 'Stasiun Perlanaan adalah stasiun kereta api kelas III yang terletak di Kabupaten Serdang Bedagai, Sumatera Utara.', 'Stasiun Perlanaan dibangun pada masa pemerintahan Hindia Belanda sebagai bagian dari jaringan kereta api Sumatera Timur.', 4, 22, 'Divre I Sumatera Utara & Aceh', 'stasiun_1777671883.png', 1, '2026-05-04 11:01:38');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_kereta`
--

CREATE TABLE `jadwal_kereta` (
  `id` int(11) NOT NULL,
  `kereta_id` int(11) NOT NULL,
  `tipe` enum('keberangkatan','kedatangan') NOT NULL,
  `stasiun_asal_id` int(11) NOT NULL COMMENT 'FK ke stasiun_rute',
  `stasiun_tujuan_id` int(11) NOT NULL COMMENT 'FK ke stasiun_rute',
  `via` varchar(200) DEFAULT NULL,
  `jam` time NOT NULL,
  `hari_operasi` enum('Setiap Hari','Senin-Jumat','Sabtu-Minggu','Senin-Sabtu') NOT NULL DEFAULT 'Setiap Hari',
  `status` enum('Normal','Terlambat','Dibatalkan') NOT NULL DEFAULT 'Normal',
  `keterlambatan_menit` smallint(6) DEFAULT 0,
  `keterangan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jadwal_kereta`
--

INSERT INTO `jadwal_kereta` (`id`, `kereta_id`, `tipe`, `stasiun_asal_id`, `stasiun_tujuan_id`, `via`, `jam`, `hari_operasi`, `status`, `keterlambatan_menit`, `keterangan`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'keberangkatan', 1, 2, 'Tebingtinggi, Kisaran', '05:30:00', 'Setiap Hari', 'Terlambat', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-02 04:53:17'),
(2, 2, 'keberangkatan', 1, 4, 'Kisaran', '07:45:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(3, 3, 'keberangkatan', 1, 3, 'Langsung', '10:15:00', 'Senin-Jumat', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(4, 4, 'keberangkatan', 1, 2, 'Tebingtinggi', '13:00:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(5, 5, 'keberangkatan', 1, 5, 'Langsung', '16:20:00', 'Sabtu-Minggu', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(6, 6, 'keberangkatan', 1, 4, 'Kisaran', '19:50:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(7, 2, 'kedatangan', 4, 1, 'Kisaran', '06:10:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(8, 1, 'kedatangan', 2, 1, 'Tebingtinggi', '09:30:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(9, 4, 'kedatangan', 3, 1, 'Langsung', '11:55:00', 'Senin-Jumat', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(10, 4, 'kedatangan', 4, 1, 'Kisaran', '14:40:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34'),
(11, 6, 'kedatangan', 2, 1, 'Tebingtinggi', '20:25:00', 'Setiap Hari', 'Normal', 0, NULL, 1, '2026-05-01 16:45:34', '2026-05-01 16:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `jam_operasional`
--

CREATE TABLE `jam_operasional` (
  `id` int(11) NOT NULL,
  `layanan` varchar(100) NOT NULL,
  `jam_buka` time NOT NULL,
  `jam_tutup` time NOT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `urutan` tinyint(4) NOT NULL DEFAULT 0,
  `admin_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `jam_operasional`
--

INSERT INTO `jam_operasional` (`id`, `layanan`, `jam_buka`, `jam_tutup`, `keterangan`, `is_active`, `urutan`, `admin_id`, `updated_at`) VALUES
(1, 'Loket Tiket', '05:00:00', '21:00:00', 'Termasuk hari libur nasional', 1, 1, 1, '2026-04-28 19:37:10'),
(2, 'Informasi & Pengaduan', '06:00:00', '20:00:00', '', 1, 2, 1, '2026-05-02 14:50:15'),
(3, 'Parkir Kendaraan', '00:00:00', '23:59:00', '24 Jam Penuh', 1, 3, 1, '2026-04-28 19:37:10'),
(4, 'Mushola', '00:00:00', '23:59:00', 'Terbuka setiap saat', 1, 4, 1, '2026-04-28 19:37:10'),
(5, 'Toilet Umum', '05:00:00', '22:00:00', NULL, 1, 5, 1, '2026-04-28 19:37:10'),
(6, 'Kantin / Warung', '05:30:00', '21:00:00', 'Jam bisa berbeda tiap tenant', 1, 6, 1, '2026-04-28 19:37:10'),
(7, 'Penitipan Bagasi', '06:00:00', '20:00:00', NULL, 1, 7, 1, '2026-04-28 19:37:10');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_tiket`
--

CREATE TABLE `kelas_tiket` (
  `id` int(11) NOT NULL,
  `nama_kelas` enum('Ekonomi','Bisnis','Eksekutif') NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `warna_badge` varchar(30) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kelas_tiket`
--

INSERT INTO `kelas_tiket` (`id`, `nama_kelas`, `deskripsi`, `warna_badge`) VALUES
(1, 'Ekonomi', 'Kelas ekonomi dengan tarif terjangkau.', 'success'),
(2, 'Bisnis', 'Kelas bisnis dengan fasilitas lebih nyaman, kursi recline dan AC.', 'primary'),
(3, 'Eksekutif', 'Kelas eksekutif premium dengan kenyamanan tertinggi.', 'warning');

-- --------------------------------------------------------

--
-- Table structure for table `kereta`
--

CREATE TABLE `kereta` (
  `id` int(11) NOT NULL,
  `nomor_ka` varchar(10) NOT NULL,
  `nama_ka` varchar(100) NOT NULL,
  `jenis` enum('Lokal','Ekspres','Campuran') NOT NULL DEFAULT 'Lokal',
  `keterangan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `kereta`
--

INSERT INTO `kereta` (`id`, `nomor_ka`, `nama_ka`, `jenis`, `keterangan`, `is_active`, `created_at`) VALUES
(1, '73', 'Sriwijaya', 'Lokal', NULL, 1, '2026-05-01 16:45:34'),
(2, '74', 'Putri Deli', 'Ekspres', NULL, 1, '2026-05-01 16:45:34'),
(3, '81', 'Sribilah', 'Ekspres', NULL, 1, '2026-05-01 16:45:34'),
(4, '82', 'Bandar Raya', 'Lokal', NULL, 1, '2026-05-01 16:45:34'),
(5, '85', 'Dolok Martumbur', 'Lokal', NULL, 1, '2026-05-01 16:45:34'),
(6, '90', 'Putri Deli Malam', 'Ekspres', NULL, 1, '2026-05-01 16:45:34');

-- --------------------------------------------------------

--
-- Table structure for table `pengaturan_sistem`
--

CREATE TABLE `pengaturan_sistem` (
  `id` int(11) NOT NULL,
  `kunci` varchar(100) NOT NULL,
  `nilai` text DEFAULT NULL,
  `label` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengaturan_sistem`
--

INSERT INTO `pengaturan_sistem` (`id`, `kunci`, `nilai`, `label`) VALUES
(1, 'nama_website', 'Stasiun Perlanaan  KAI Info Layanan', 'Nama Website'),
(2, 'deskripsi_website', 'Portal resmi informasi layanan Stasiun Kereta Api Perlanaan', 'Deskripsi Website'),
(3, 'email_kontak', 'info.perlanaan@kai.id', 'Email Kontak'),
(4, 'telepon_utama', '(0621) 441-2871', 'Nomor Telepon Stasiun'),
(5, 'call_center', '121', 'Call Center KAI'),
(6, 'whatsapp_cs', '0811-1211-900', 'WhatsApp CS'),
(7, 'ticker_aktif', '1', 'Tampilkan Ticker Pengumuman'),
(8, 'max_berita_beranda', '6', 'Jumlah Berita di Beranda'),
(9, 'google_maps_embed', 'https://www.google.com/maps/embed?pb=!1m17!1m12!1m3!1d3796.219578860422!2d99.02742507497216!3d3.3699999966046668!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m2!1m1!2zM8KwMjInMTIuMCJOIDk5wrAwMSc0OC4wIkU!5e1!3m2!1sid!2sid!4v1777673326030!5m2!1sid!2sid\" width=\"600\" height=\"450\" style=\"border:0;\" allowfullscreen=\"\" loading=\"lazy\" referrerpolicy=\"no-referrer-when-downgrade\"', 'URL Embed Google Maps'),
(10, 'max_pengumuman_beranda', '6', 'Jumlah Pengumuman di Beranda');

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `tipe` enum('Gangguan','Keterlambatan','Umum','Darurat') NOT NULL DEFAULT 'Umum',
  `prioritas` enum('Tinggi','Sedang','Rendah') NOT NULL DEFAULT 'Sedang',
  `tgl_mulai` date NOT NULL,
  `tgl_selesai` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `admin_id` int(11) DEFAULT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `tipe`, `prioritas`, `tgl_mulai`, `tgl_selesai`, `is_active`, `admin_id`, `created_at`, `updated_at`) VALUES
(1, 'Perbaikan Jalur KM 120 – Layanan Terdampak Sementara', 'Pekerjaan perbaikan jalur di KM 120 menyebabkan keterlambatan beberapa rangkaian kereta. Perkiraan keterlambatan 30–60 menit.', 'Keterlambatan', 'Tinggi', '2025-07-20', '2025-07-25', 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(2, 'Penerapan Tiket Elektronik Mulai Agustus 2025', 'KAI menerapkan sistem tiket elektronik sepenuhnya mulai Agustus 2025. Seluruh penumpang diwajibkan membawa KTP saat check-in.', 'Umum', 'Sedang', '2025-07-10', NULL, 1, 1, '2026-04-28 19:37:10', '2026-04-28 19:37:10'),
(3, 'Loket Tiket Buka Lebih Awal ï¿½ Masa Lebaran', 'Dalam rangka melayani lonjakan penumpang masa Lebaran, loket tiket Stasiun Perlanaan dibuka mulai pukul 04.00 WIB.', 'Umum', 'Sedang', '2025-03-20', '2025-04-05', 1, NULL, '2026-04-28 19:37:10', '2026-05-01 20:29:22'),
(4, 'Pengumuman Kedua', 'Isi Lah Gak Tau', 'Gangguan', 'Tinggi', '2026-05-01', '2026-05-02', 0, 1, '2026-05-01 20:37:45', '2026-05-01 22:32:55');

-- --------------------------------------------------------

--
-- Table structure for table `stasiun_rute`
--

CREATE TABLE `stasiun_rute` (
  `id` int(11) NOT NULL,
  `kode` varchar(10) NOT NULL,
  `nama` varchar(100) NOT NULL,
  `kota` varchar(100) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `stasiun_rute`
--

INSERT INTO `stasiun_rute` (`id`, `kode`, `nama`, `kota`, `is_active`) VALUES
(1, 'PLN', 'Perlanaan', 'Serdang Bedagai', 1),
(2, 'MDN', 'Medan', 'Medan', 1),
(3, 'KIS', 'Kisaran', 'Asahan', 1),
(4, 'RAP', 'Rantauprapat', 'Labuhan Batu', 1),
(5, 'TBT', 'Tebingtinggi', 'Tebingtinggi', 1),
(6, 'SEI', 'Sei Rampah', 'Serdang Bedagai', 1);

-- --------------------------------------------------------

--
-- Table structure for table `statistik_pengunjung`
--

CREATE TABLE `statistik_pengunjung` (
  `id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `total_kunjungan` int(11) NOT NULL DEFAULT 0,
  `halaman_beranda` int(11) DEFAULT 0,
  `halaman_jadwal` int(11) DEFAULT 0,
  `halaman_harga` int(11) DEFAULT 0,
  `halaman_stasiun` int(11) DEFAULT 0,
  `halaman_berita` int(11) DEFAULT 0,
  `halaman_faq` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `statistik_pengunjung`
--

INSERT INTO `statistik_pengunjung` (`id`, `tanggal`, `total_kunjungan`, `halaman_beranda`, `halaman_jadwal`, `halaman_harga`, `halaman_stasiun`, `halaman_berita`, `halaman_faq`) VALUES
(1, '2025-07-18', 312, 120, 95, 48, 22, 18, 9),
(2, '2025-07-19', 278, 105, 82, 41, 20, 21, 9),
(3, '2025-07-20', 401, 155, 118, 62, 30, 28, 8),
(4, '2025-07-21', 356, 140, 105, 55, 25, 24, 7),
(5, '2025-07-22', 289, 110, 88, 44, 18, 20, 9),
(6, '2025-07-23', 198, 80, 60, 30, 12, 11, 5),
(7, '2025-07-24', 221, 88, 68, 34, 14, 12, 5);

-- --------------------------------------------------------

--
-- Table structure for table `tarif_khusus`
--

CREATE TABLE `tarif_khusus` (
  `id` int(11) NOT NULL,
  `kategori` varchar(100) NOT NULL,
  `ikon_emoji` varchar(10) DEFAULT NULL,
  `persen_diskon` tinyint(4) NOT NULL,
  `syarat` text DEFAULT NULL,
  `keterangan` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `admin_id` int(11) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `tarif_khusus`
--

INSERT INTO `tarif_khusus` (`id`, `kategori`, `ikon_emoji`, `persen_diskon`, `syarat`, `keterangan`, `is_active`, `admin_id`, `updated_at`) VALUES
(1, 'Lansia (?60 tahun)', 'ðŸ‘´', 20, 'KTP dengan tanggal lahir yang menunjukkan usia ?60 tahun', 'Berlaku untuk semua kelas', 1, 1, '2026-05-01 17:20:13'),
(2, 'Penyandang Disabilitas', 'â™¿', 30, 'Kartu identitas difabel resmi yang dikeluarkan pemerintah', 'Berlaku untuk semua kelas', 1, 1, '2026-05-02 04:31:05'),
(3, 'Anak (3–10 tahun)', '👶', 50, 'Akte kelahiran atau kartu keluarga', 'Di bawah 3 tahun gratis (dipangku), tidak mendapat tempat duduk', 1, 1, '2026-04-28 19:37:10'),
(4, 'TNI / Polri', '🎖️', 15, 'Kartu Tanda Anggota (KTA) aktif', 'Berlaku saat bertugas maupun tidak', 1, 1, '2026-04-28 19:37:10'),
(5, 'Mahasiswa', '🎓', 10, 'Kartu Tanda Mahasiswa (KTM) aktif', 'Hanya berlaku untuk kelas Ekonomi', 1, 1, '2026-04-28 19:37:10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indexes for table `berita`
--
ALTER TABLE `berita`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`),
  ADD KEY `fk_berita_admin` (`admin_id`);

--
-- Indexes for table `faq`
--
ALTER TABLE `faq`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_faq_admin` (`admin_id`);

--
-- Indexes for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_fasilitas_admin` (`admin_id`);

--
-- Indexes for table `harga_tiket`
--
ALTER TABLE `harga_tiket`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_harga` (`stasiun_asal_id`,`stasiun_tujuan_id`,`kelas_id`),
  ADD KEY `fk_harga_asal` (`stasiun_asal_id`),
  ADD KEY `fk_harga_tujuan` (`stasiun_tujuan_id`),
  ADD KEY `fk_harga_kelas` (`kelas_id`);

--
-- Indexes for table `info_stasiun`
--
ALTER TABLE `info_stasiun`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_stasiun_admin` (`updated_by`);

--
-- Indexes for table `jadwal_kereta`
--
ALTER TABLE `jadwal_kereta`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jadwal_kereta` (`kereta_id`),
  ADD KEY `fk_jadwal_asal` (`stasiun_asal_id`),
  ADD KEY `fk_jadwal_tujuan` (`stasiun_tujuan_id`);

--
-- Indexes for table `jam_operasional`
--
ALTER TABLE `jam_operasional`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jam_admin` (`admin_id`);

--
-- Indexes for table `kelas_tiket`
--
ALTER TABLE `kelas_tiket`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kereta`
--
ALTER TABLE `kereta`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `nomor_ka` (`nomor_ka`);

--
-- Indexes for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kunci` (`kunci`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengumuman_admin` (`admin_id`);

--
-- Indexes for table `stasiun_rute`
--
ALTER TABLE `stasiun_rute`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `kode` (`kode`);

--
-- Indexes for table `statistik_pengunjung`
--
ALTER TABLE `statistik_pengunjung`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tanggal` (`tanggal`);

--
-- Indexes for table `tarif_khusus`
--
ALTER TABLE `tarif_khusus`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_tarif_admin` (`admin_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `berita`
--
ALTER TABLE `berita`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `faq`
--
ALTER TABLE `faq`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `fasilitas`
--
ALTER TABLE `fasilitas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `harga_tiket`
--
ALTER TABLE `harga_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=47;

--
-- AUTO_INCREMENT for table `info_stasiun`
--
ALTER TABLE `info_stasiun`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `jadwal_kereta`
--
ALTER TABLE `jadwal_kereta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `jam_operasional`
--
ALTER TABLE `jam_operasional`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `kelas_tiket`
--
ALTER TABLE `kelas_tiket`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kereta`
--
ALTER TABLE `kereta`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `pengaturan_sistem`
--
ALTER TABLE `pengaturan_sistem`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `stasiun_rute`
--
ALTER TABLE `stasiun_rute`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `statistik_pengunjung`
--
ALTER TABLE `statistik_pengunjung`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `tarif_khusus`
--
ALTER TABLE `tarif_khusus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `berita`
--
ALTER TABLE `berita`
  ADD CONSTRAINT `fk_berita_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `faq`
--
ALTER TABLE `faq`
  ADD CONSTRAINT `fk_faq_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `fasilitas`
--
ALTER TABLE `fasilitas`
  ADD CONSTRAINT `fk_fasilitas_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `harga_tiket`
--
ALTER TABLE `harga_tiket`
  ADD CONSTRAINT `fk_harga_asal` FOREIGN KEY (`stasiun_asal_id`) REFERENCES `stasiun_rute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_harga_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas_tiket` (`id`),
  ADD CONSTRAINT `fk_harga_tujuan` FOREIGN KEY (`stasiun_tujuan_id`) REFERENCES `stasiun_rute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `info_stasiun`
--
ALTER TABLE `info_stasiun`
  ADD CONSTRAINT `fk_stasiun_admin` FOREIGN KEY (`updated_by`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `jadwal_kereta`
--
ALTER TABLE `jadwal_kereta`
  ADD CONSTRAINT `fk_jadwal_asal` FOREIGN KEY (`stasiun_asal_id`) REFERENCES `stasiun_rute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_jadwal_kereta` FOREIGN KEY (`kereta_id`) REFERENCES `kereta` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jadwal_tujuan` FOREIGN KEY (`stasiun_tujuan_id`) REFERENCES `stasiun_rute` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `jam_operasional`
--
ALTER TABLE `jam_operasional`
  ADD CONSTRAINT `fk_jam_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `fk_pengumuman_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `tarif_khusus`
--
ALTER TABLE `tarif_khusus`
  ADD CONSTRAINT `fk_tarif_admin` FOREIGN KEY (`admin_id`) REFERENCES `admin` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
