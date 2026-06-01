-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: May 30, 2026 at 02:38 PM
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
-- Database: `isnu_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `forum_reaction`
--

CREATE TABLE `forum_reaction` (
  `id` int NOT NULL,
  `reply_id` int NOT NULL,
  `user_id` int NOT NULL,
  `reaction_type` varchar(20) NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `forum_reaction`
--

INSERT INTO `forum_reaction` (`id`, `reply_id`, `user_id`, `reaction_type`, `created_at`) VALUES
(9, 4, 45, '😂', '2026-05-16 02:32:02'),
(10, 4, 31, '😲', '2026-05-16 02:33:45'),
(11, 5, 31, '😢', '2026-05-16 02:34:12'),
(12, 5, 27, '👍', '2026-05-16 02:35:17'),
(13, 5, 47, '👍', '2026-05-16 02:35:45');

-- --------------------------------------------------------

--
-- Table structure for table `forum_reply`
--

CREATE TABLE `forum_reply` (
  `id` int NOT NULL,
  `thread_id` int NOT NULL,
  `parent_id` int DEFAULT NULL,
  `user_id` int NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `forum_reply`
--

INSERT INTO `forum_reply` (`id`, `thread_id`, `parent_id`, `user_id`, `pesan`, `created_at`) VALUES
(4, 2, NULL, 45, 'anu pak', '2026-05-16 02:31:42'),
(5, 2, NULL, 31, 'banyak koruptor pak', '2026-05-16 02:34:08'),
(6, 2, 4, 27, 'apa', '2026-05-16 02:35:08');

-- --------------------------------------------------------

--
-- Table structure for table `forum_thread`
--

CREATE TABLE `forum_thread` (
  `id` int NOT NULL,
  `jadwal_mengajar_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `dibuat_oleh` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `forum_thread`
--

INSERT INTO `forum_thread` (`id`, `jadwal_mengajar_id`, `judul`, `deskripsi`, `dibuat_oleh`, `created_at`) VALUES
(2, 10, 'pancasila', 'Apa tantangan terbesar dalam mengamalkan Pancasila pada masa sekarang?', 27, '2026-05-15 23:16:48');

-- --------------------------------------------------------

--
-- Table structure for table `jadwal_mengajar`
--

CREATE TABLE `jadwal_mengajar` (
  `id` int NOT NULL,
  `tahun_ajaran_id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `mapel_id` int NOT NULL,
  `guru_id` int NOT NULL,
  `hari` varchar(20) DEFAULT NULL,
  `jam_mulai` time DEFAULT NULL,
  `jam_selesai` time DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `jadwal_mengajar`
--

INSERT INTO `jadwal_mengajar` (`id`, `tahun_ajaran_id`, `kelas_id`, `mapel_id`, `guru_id`, `hari`, `jam_mulai`, `jam_selesai`) VALUES
(4, 1, 3, 7, 27, NULL, NULL, NULL),
(5, 1, 4, 7, 27, NULL, NULL, NULL),
(6, 1, 6, 7, 27, NULL, NULL, NULL),
(7, 1, 7, 7, 27, NULL, NULL, NULL),
(8, 1, 5, 7, 27, NULL, NULL, NULL),
(10, 1, 9, 7, 27, NULL, NULL, NULL),
(11, 1, 6, 1, 10, NULL, NULL, NULL),
(12, 1, 7, 1, 10, NULL, NULL, NULL),
(13, 1, 9, 1, 12, NULL, NULL, NULL),
(14, 1, 3, 1, 12, NULL, NULL, NULL),
(15, 1, 10, 1, 12, NULL, NULL, NULL),
(16, 1, 6, 13, 15, NULL, NULL, NULL),
(17, 1, 7, 13, 15, NULL, NULL, NULL),
(18, 1, 4, 13, 15, NULL, NULL, NULL),
(19, 1, 5, 13, 15, NULL, NULL, NULL),
(20, 1, 11, 13, 15, NULL, NULL, NULL),
(21, 1, 9, 13, 16, NULL, NULL, NULL),
(22, 1, 3, 13, 16, NULL, NULL, NULL),
(23, 1, 10, 13, 16, NULL, NULL, NULL),
(24, 1, 4, 1, 10, NULL, NULL, NULL),
(25, 1, 5, 1, 10, NULL, NULL, NULL),
(26, 1, 11, 1, 10, NULL, NULL, NULL),
(27, 1, 6, 9, 25, NULL, NULL, NULL),
(28, 1, 7, 9, 25, NULL, NULL, NULL),
(29, 1, 9, 9, 26, NULL, NULL, NULL),
(30, 1, 3, 9, 26, NULL, NULL, NULL),
(31, 1, 10, 9, 26, NULL, NULL, NULL),
(32, 1, 4, 9, 26, NULL, NULL, NULL),
(33, 1, 5, 9, 26, NULL, NULL, NULL),
(34, 1, 11, 9, 26, NULL, NULL, NULL),
(35, 1, 6, 4, 17, NULL, NULL, NULL),
(36, 1, 7, 4, 17, NULL, NULL, NULL),
(37, 1, 9, 4, 17, NULL, NULL, NULL),
(38, 1, 3, 4, 17, NULL, NULL, NULL),
(39, 1, 10, 4, 17, NULL, NULL, NULL),
(43, 1, 6, 12, 11, NULL, NULL, NULL),
(44, 1, 7, 12, 11, NULL, NULL, NULL),
(45, 1, 9, 12, 11, NULL, NULL, NULL),
(46, 1, 3, 12, 11, NULL, NULL, NULL),
(47, 1, 10, 12, 11, NULL, NULL, NULL),
(48, 1, 4, 12, 11, NULL, NULL, NULL),
(49, 1, 5, 12, 11, NULL, NULL, NULL),
(50, 1, 11, 12, 11, NULL, NULL, NULL),
(51, 1, 6, 14, 20, NULL, NULL, NULL),
(52, 1, 7, 14, 20, NULL, NULL, NULL),
(53, 1, 9, 14, 20, NULL, NULL, NULL),
(54, 1, 3, 14, 20, NULL, NULL, NULL),
(55, 1, 10, 14, 20, NULL, NULL, NULL),
(56, 1, 4, 14, 20, NULL, NULL, NULL),
(57, 1, 5, 14, 20, NULL, NULL, NULL),
(58, 1, 11, 14, 20, NULL, NULL, NULL),
(59, 1, 9, 2, 9, NULL, NULL, NULL),
(60, 1, 3, 2, 9, NULL, NULL, NULL),
(61, 1, 10, 2, 9, NULL, NULL, NULL),
(62, 1, 6, 2, 17, NULL, NULL, NULL),
(63, 1, 7, 2, 17, NULL, NULL, NULL),
(64, 1, 4, 2, 17, NULL, NULL, NULL),
(65, 1, 5, 2, 17, NULL, NULL, NULL),
(66, 1, 11, 2, 17, NULL, NULL, NULL),
(67, 1, 6, 8, 13, NULL, NULL, NULL),
(68, 1, 7, 8, 13, NULL, NULL, NULL),
(69, 1, 9, 8, 13, NULL, NULL, NULL),
(70, 1, 3, 8, 13, NULL, NULL, NULL),
(71, 1, 10, 8, 13, NULL, NULL, NULL),
(72, 1, 4, 8, 13, NULL, NULL, NULL),
(73, 1, 5, 8, 13, NULL, NULL, NULL),
(74, 1, 11, 8, 13, NULL, NULL, NULL),
(75, 1, 9, 6, 14, NULL, NULL, NULL),
(76, 1, 3, 6, 14, NULL, NULL, NULL),
(77, 1, 10, 6, 14, NULL, NULL, NULL),
(78, 1, 4, 6, 14, NULL, NULL, NULL),
(79, 1, 5, 6, 14, NULL, NULL, NULL),
(80, 1, 11, 6, 14, NULL, NULL, NULL),
(81, 1, 6, 6, 14, NULL, NULL, NULL),
(82, 1, 7, 6, 14, NULL, NULL, NULL),
(83, 1, 4, 4, 18, NULL, NULL, NULL),
(84, 1, 5, 4, 18, NULL, NULL, NULL),
(85, 1, 11, 4, 18, NULL, NULL, NULL),
(86, 1, 6, 5, 19, NULL, NULL, NULL),
(87, 1, 7, 5, 19, NULL, NULL, NULL),
(88, 1, 9, 5, 19, NULL, NULL, NULL),
(89, 1, 3, 5, 19, NULL, NULL, NULL),
(90, 1, 10, 5, 19, NULL, NULL, NULL),
(91, 1, 4, 5, 19, NULL, NULL, NULL),
(92, 1, 5, 5, 19, NULL, NULL, NULL),
(93, 1, 11, 5, 19, NULL, NULL, NULL),
(94, 1, 4, 11, 21, NULL, NULL, NULL),
(95, 1, 5, 11, 21, NULL, NULL, NULL),
(96, 1, 11, 11, 21, NULL, NULL, NULL),
(97, 1, 6, 11, 21, NULL, NULL, NULL),
(98, 1, 7, 11, 21, NULL, NULL, NULL),
(99, 1, 9, 15, 22, NULL, NULL, NULL),
(100, 1, 3, 15, 22, NULL, NULL, NULL),
(101, 1, 10, 15, 22, NULL, NULL, NULL),
(102, 1, 9, 3, 24, NULL, NULL, NULL),
(103, 1, 9, 10, 23, NULL, NULL, NULL),
(104, 1, 3, 10, 23, NULL, NULL, NULL),
(105, 1, 10, 10, 23, NULL, NULL, NULL),
(106, 1, 4, 10, 23, NULL, NULL, NULL),
(107, 1, 5, 10, 23, NULL, NULL, NULL),
(108, 1, 11, 10, 23, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `kelas`
--

CREATE TABLE `kelas` (
  `id` int NOT NULL,
  `tahun_ajaran_id` int NOT NULL,
  `nama_kelas` varchar(50) NOT NULL,
  `tingkat` enum('7','8','9') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas`
--

INSERT INTO `kelas` (`id`, `tahun_ajaran_id`, `nama_kelas`, `tingkat`) VALUES
(3, 1, 'VII B', '7'),
(4, 1, 'VIII A', '8'),
(5, 1, 'VIII B', '8'),
(6, 1, 'IX A', '9'),
(7, 1, 'IX B', '9'),
(9, 1, 'VII A', '7'),
(10, 1, 'VII C', '7'),
(11, 1, 'VIII C', '8');

-- --------------------------------------------------------

--
-- Table structure for table `kelas_siswa`
--

CREATE TABLE `kelas_siswa` (
  `id` int NOT NULL,
  `kelas_id` int NOT NULL,
  `user_id` int NOT NULL,
  `no_absen` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelas_siswa`
--

INSERT INTO `kelas_siswa` (`id`, `kelas_id`, `user_id`, `no_absen`) VALUES
(28, 9, 30, 1),
(29, 9, 35, 2),
(30, 9, 33, 3),
(31, 9, 45, 4),
(32, 9, 40, 5),
(33, 9, 36, 6),
(34, 9, 47, 7),
(35, 9, 34, 8),
(36, 9, 32, 9),
(37, 9, 44, 10),
(38, 9, 41, 11),
(39, 9, 28, 12),
(40, 9, 29, 13),
(41, 9, 43, 14),
(42, 9, 37, 15),
(43, 9, 46, 16),
(44, 9, 39, 17),
(45, 9, 31, 18),
(46, 9, 38, 19),
(47, 9, 42, 20),
(48, 3, 65, 1),
(49, 3, 62, 2),
(50, 3, 53, 3),
(51, 3, 66, 4),
(52, 3, 63, 5),
(53, 3, 64, 6),
(54, 3, 58, 7),
(55, 3, 57, 8),
(56, 3, 54, 9),
(57, 3, 49, 10),
(58, 3, 59, 11),
(59, 3, 52, 12),
(60, 3, 50, 13),
(61, 3, 69, 14),
(62, 3, 56, 15),
(63, 3, 55, 16),
(64, 3, 51, 17),
(65, 3, 48, 18),
(66, 3, 68, 19),
(67, 3, 67, 20),
(68, 3, 60, 21),
(69, 3, 61, 22),
(70, 10, 81, 1),
(71, 10, 74, 2),
(72, 10, 80, 3),
(73, 10, 77, 4),
(74, 10, 72, 5),
(75, 10, 88, 6),
(76, 10, 79, 7),
(77, 10, 82, 8),
(78, 10, 87, 9),
(79, 10, 73, 10),
(80, 10, 83, 11),
(81, 10, 78, 12),
(82, 10, 84, 13),
(83, 10, 75, 14),
(84, 10, 76, 15),
(85, 10, 85, 16),
(86, 10, 86, 17),
(87, 4, 89, 1),
(88, 4, 97, 2),
(89, 4, 105, 3),
(90, 4, 106, 4),
(91, 4, 98, 5),
(92, 4, 91, 6),
(93, 4, 94, 7),
(94, 4, 107, 8),
(95, 4, 99, 9),
(96, 4, 90, 10),
(97, 4, 100, 11),
(98, 4, 101, 12),
(99, 4, 93, 13),
(100, 4, 95, 14),
(101, 4, 92, 15),
(102, 4, 96, 16),
(103, 4, 102, 17),
(104, 4, 104, 18),
(105, 4, 103, 19),
(106, 4, 108, 20),
(107, 5, 112, 1),
(108, 5, 109, 2),
(109, 5, 110, 3),
(110, 5, 111, 4),
(111, 5, 118, 5),
(112, 5, 119, 6),
(113, 5, 125, 7),
(114, 5, 113, 8),
(115, 5, 120, 9),
(116, 5, 126, 10),
(117, 5, 114, 11),
(118, 5, 121, 12),
(119, 5, 128, 13),
(120, 5, 129, 14),
(121, 5, 122, 15),
(122, 5, 123, 16),
(123, 5, 116, 17),
(124, 5, 124, 18),
(125, 5, 127, 19),
(126, 5, 117, 20),
(127, 5, 115, 21),
(128, 11, 141, 1),
(129, 11, 145, 2),
(130, 11, 130, 3),
(131, 11, 132, 4),
(132, 11, 146, 5),
(133, 11, 133, 6),
(134, 11, 147, 7),
(135, 11, 140, 8),
(136, 11, 139, 9),
(137, 11, 143, 10),
(138, 11, 142, 11),
(139, 11, 137, 12),
(140, 11, 144, 13),
(141, 11, 136, 14),
(142, 11, 135, 15),
(143, 11, 138, 16),
(144, 11, 134, 17),
(145, 11, 131, 18),
(146, 6, 158, 1),
(147, 6, 165, 2),
(148, 6, 150, 3),
(149, 6, 161, 4),
(150, 6, 156, 5),
(151, 6, 153, 6),
(152, 6, 163, 7),
(153, 6, 149, 8),
(154, 6, 159, 9),
(155, 6, 154, 10),
(156, 6, 172, 11),
(157, 6, 157, 12),
(158, 6, 169, 13),
(159, 6, 167, 14),
(160, 6, 168, 15),
(161, 6, 170, 16),
(162, 6, 162, 17),
(163, 6, 164, 18),
(164, 6, 155, 19),
(165, 6, 160, 20),
(166, 6, 171, 21),
(167, 6, 152, 22),
(168, 6, 148, 23),
(169, 6, 151, 24),
(170, 6, 166, 25),
(171, 7, 192, 1),
(172, 7, 174, 2),
(173, 7, 181, 3),
(174, 7, 196, 4),
(175, 7, 189, 5),
(176, 7, 195, 6),
(177, 7, 183, 7),
(178, 7, 178, 8),
(179, 7, 173, 9),
(180, 7, 175, 10),
(181, 7, 182, 11),
(182, 7, 187, 12),
(183, 7, 188, 13),
(184, 7, 180, 14),
(185, 7, 193, 15),
(186, 7, 177, 16),
(187, 7, 194, 17),
(188, 7, 179, 18),
(189, 7, 190, 19),
(190, 7, 176, 20),
(191, 7, 184, 21),
(192, 7, 186, 22),
(193, 7, 185, 23),
(194, 7, 191, 24);

-- --------------------------------------------------------

--
-- Table structure for table `kelompok_mapel`
--

CREATE TABLE `kelompok_mapel` (
  `id` int NOT NULL,
  `nama` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kelompok_mapel`
--

INSERT INTO `kelompok_mapel` (`id`, `nama`) VALUES
(1, 'Agama'),
(2, 'Umum'),
(3, 'Muatan Lokal');

-- --------------------------------------------------------

--
-- Table structure for table `kuis_hasil`
--

CREATE TABLE `kuis_hasil` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `skor` double DEFAULT '0',
  `diselesaikan_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kuis_hasil`
--

INSERT INTO `kuis_hasil` (`id`, `item_id`, `siswa_id`, `skor`, `diselesaikan_pada`) VALUES
(12, 10, 45, 100, '2026-05-15 23:27:22'),
(13, 10, 47, 85, '2026-05-15 23:52:02'),
(14, 10, 31, 30, '2026-05-15 23:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `kuis_jawaban`
--

CREATE TABLE `kuis_jawaban` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `soal_id` int NOT NULL,
  `opsi_id` int DEFAULT NULL,
  `jawaban_teks` text,
  `poin_didapat` double DEFAULT '0',
  `is_benar` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kuis_jawaban`
--

INSERT INTO `kuis_jawaban` (`id`, `item_id`, `siswa_id`, `soal_id`, `opsi_id`, `jawaban_teks`, `poin_didapat`, `is_benar`, `created_at`) VALUES
(1, 5, 7, 1, 3, NULL, 10, 1, '2026-05-15 14:06:44'),
(2, 6, 7, 2, 6, NULL, 0, 0, '2026-05-15 14:06:54'),
(3, 5, 8, 1, 1, NULL, 0, 0, '2026-05-15 14:10:39'),
(4, 6, 8, 2, 5, NULL, 0, 0, '2026-05-15 14:10:42'),
(5, 10, 45, 3, 9, NULL, 0, 0, '2026-05-15 23:23:12'),
(6, 10, 45, 4, 15, NULL, 10, 1, '2026-05-15 23:23:12'),
(7, 10, 45, 5, 19, NULL, 10, 1, '2026-05-15 23:23:12'),
(8, 10, 45, 6, 23, NULL, 10, 1, '2026-05-15 23:23:12'),
(9, 10, 45, 8, NULL, 'Pancasila adalah dasar negara dan ideologi bangsa Indonesia yang digunakan sebagai pedoman dalam kehidupan bermasyarakat, berbangsa, dan bernegara. Pancasila berasal dari bahasa Sanskerta, yaitu “Panca” yang berarti lima dan “Sila” yang berarti dasar atau prinsip.', 10, 0, '2026-05-15 23:27:22'),
(10, 10, 45, 9, NULL, 'Ketuhanan Yang Maha Esa\r\nKemanusiaan yang adil dan beradab\r\nPersatuan Indonesia\r\nKerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan\r\nKeadilan sosial bagi seluruh rakyat Indonesia', 15, 0, '2026-05-15 23:27:22'),
(11, 10, 45, 10, NULL, 'Soekarno\r\nMohammad Hatta\r\nMohammad Yamin\r\nSoepomo', 15, 0, '2026-05-15 23:27:22'),
(12, 10, 45, 11, NULL, 'Makna sila ketiga, yaitu “Persatuan Indonesia”, adalah seluruh rakyat Indonesia harus menjaga persatuan dan kesatuan bangsa walaupun memiliki perbedaan suku, agama, budaya, dan bahasa. Sila ini mengajarkan rasa cinta tanah air dan semangat persaudaraan.', 15, 0, '2026-05-15 23:27:22'),
(13, 10, 45, 12, NULL, 'Bersikap adil kepada semua teman\r\nMembantu orang yang membutuhkan\r\nMenghormati hak orang lain tanpa membeda-bedakan', 15, 0, '2026-05-15 23:27:22'),
(14, 10, 47, 3, 12, NULL, 0, 0, '2026-05-15 23:46:41'),
(15, 10, 47, 4, 15, NULL, 10, 1, '2026-05-15 23:46:41'),
(16, 10, 47, 5, 19, NULL, 10, 1, '2026-05-15 23:46:41'),
(17, 10, 47, 6, 21, NULL, 0, 0, '2026-05-15 23:46:41'),
(22, 10, 47, 8, NULL, 'Pancasila adalah dasar negara dan ideologi bangsa Indonesia yang digunakan sebagai pedoman dalam kehidupan bermasyarakat, berbangsa, dan bernegara. Pancasila berasal dari bahasa Sanskerta, yaitu “Panca” yang berarti lima dan “Sila” yang berarti dasar atau prinsip.', 10, 0, '2026-05-15 23:52:02'),
(23, 10, 47, 9, NULL, 'Ketuhanan Yang Maha Esa\r\nKemanusiaan yang adil dan beradab\r\nPersatuan Indonesia\r\nKerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan\r\nKeadilan sosial bagi seluruh rakyat Indonesia', 15, 0, '2026-05-15 23:52:02'),
(24, 10, 47, 10, NULL, 'Mohammad Hatta\r\nSoekarno\r\nMohammad Yamin\r\nSoepomo', 15, 0, '2026-05-15 23:52:02'),
(25, 10, 47, 11, NULL, 'Makna sila ketiga, yaitu “Persatuan Indonesia”, adalah seluruh rakyat Indonesia harus menjaga persatuan dan kesatuan bangsa walaupun memiliki perbedaan suku, agama, budaya, dan bahasa. Sila ini mengajarkan rasa cinta tanah air dan semangat persaudaraan.', 15, 0, '2026-05-15 23:52:02'),
(26, 10, 47, 12, NULL, 'Bersikap adil kepada semua teman\r\nMembantu orang yang membutuhkan\r\nMenghormati hak orang lain tanpa membeda-bedakan', 10, 0, '2026-05-15 23:52:02'),
(27, 10, 31, 3, 10, NULL, 10, 1, '2026-05-15 23:53:30'),
(28, 10, 31, 4, 15, NULL, 10, 1, '2026-05-15 23:53:30'),
(29, 10, 31, 5, 19, NULL, 10, 1, '2026-05-15 23:53:30'),
(30, 10, 31, 6, 21, NULL, 0, 0, '2026-05-15 23:53:30'),
(31, 10, 31, 8, NULL, '...', 0, 0, '2026-05-15 23:53:30'),
(32, 10, 31, 9, NULL, '...', 0, 0, '2026-05-15 23:53:30'),
(33, 10, 31, 10, NULL, '...', 0, 0, '2026-05-15 23:53:30'),
(34, 10, 31, 11, NULL, '...', 0, 0, '2026-05-15 23:53:30'),
(35, 10, 31, 12, NULL, '...', 0, 0, '2026-05-15 23:53:30');

-- --------------------------------------------------------

--
-- Table structure for table `kuis_opsi`
--

CREATE TABLE `kuis_opsi` (
  `id` int NOT NULL,
  `soal_id` int NOT NULL,
  `teks` text NOT NULL,
  `is_benar` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kuis_opsi`
--

INSERT INTO `kuis_opsi` (`id`, `soal_id`, `teks`, `is_benar`) VALUES
(9, 3, 'Lima aturan', 0),
(10, 3, 'Lima dasar', 1),
(11, 3, 'Lima budaya', 0),
(12, 3, 'Lima bangsa', 0),
(13, 4, 'Mohammad Hatta', 0),
(14, 4, 'Soepomo', 0),
(15, 4, 'Soekarno', 1),
(16, 4, 'Mohammad Yamin', 0),
(17, 5, 'Persatuan Indonesia', 0),
(18, 5, 'Keadilan sosial bagi seluruh rakyat Indonesia', 0),
(19, 5, 'Ketuhanan Yang Maha Esa', 1),
(20, 5, 'Kemanusiaan yang adil dan beradab', 0),
(21, 6, 'Bermusyawarah', 0),
(22, 6, 'Rajin beribadah', 0),
(23, 6, 'Menolong teman D. Menolong teman D. Cinta tanah air', 1),
(24, 6, 'Menolong teman D. Cinta tanah ai', 0);

-- --------------------------------------------------------

--
-- Table structure for table `kuis_soal`
--

CREATE TABLE `kuis_soal` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `pertanyaan` text NOT NULL,
  `urutan` int DEFAULT '0',
  `tipe` enum('pg','essay') NOT NULL DEFAULT 'pg',
  `poin_maksimal` int NOT NULL DEFAULT '10'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `kuis_soal`
--

INSERT INTO `kuis_soal` (`id`, `item_id`, `pertanyaan`, `urutan`, `tipe`, `poin_maksimal`) VALUES
(3, 10, 'Pancasila berasal dari bahasa Sanskerta yang berarti ....', 1, 'pg', 10),
(4, 10, 'Tokoh yang pertama kali mengemukakan istilah Pancasila adalah ....', 2, 'pg', 10),
(5, 10, 'Bunyi sila pertama Pancasila adalah ....', 3, 'pg', 10),
(6, 10, 'Sikap yang sesuai dengan sila kedua adalah ....', 4, 'pg', 10),
(8, 10, 'Jelaskan pengertian Pancasila!', 5, 'essay', 15),
(9, 10, 'Sebutkan bunyi sila-sila Pancasila secara lengkap!', 6, 'essay', 15),
(10, 10, 'Siapa saja tokoh yang berperan dalam perumusan Pancasila?', 7, 'essay', 15),
(11, 10, 'Jelaskan makna sila ketiga Pancasila!', 8, 'essay', 15),
(12, 10, 'Sebutkan tiga contoh pengamalan sila kelima dalam kehidupan sehari-hari!', 9, 'essay', 15);

-- --------------------------------------------------------

--
-- Table structure for table `mapel`
--

CREATE TABLE `mapel` (
  `id` int NOT NULL,
  `kelompok_mapel_id` int NOT NULL,
  `nama_mapel` varchar(100) NOT NULL,
  `kode_mapel` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `mapel`
--

INSERT INTO `mapel` (`id`, `kelompok_mapel_id`, `nama_mapel`, `kode_mapel`) VALUES
(1, 2, 'Matematika', '180'),
(2, 1, 'Akidah Akhlak', '302'),
(3, 1, 'Al-Qur\'An Hadis', '301'),
(4, 1, 'Fikih', '303'),
(5, 1, 'Sejarah Kebudayaan Islam', '304'),
(6, 1, 'Bahasa Arab', '305'),
(7, 2, 'Pendidikan Kewarganegaraan (PKN)', '127'),
(8, 2, 'Bahasa Indonesia', '156'),
(9, 2, 'Ilmu Pengetahuan Alam (IPA)', '190'),
(10, 2, 'Ilmu Pengetahuan Sosial (IPS)', '200'),
(11, 2, 'Bahasa Inggris', '160'),
(12, 3, 'PJOK', '220'),
(13, 3, 'Seni Budaya', '217'),
(14, 3, 'TIK', '224'),
(15, 3, 'Mulok', '880');

-- --------------------------------------------------------

--
-- Table structure for table `modul`
--

CREATE TABLE `modul` (
  `id` int NOT NULL,
  `jadwal_mengajar_id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modul`
--

INSERT INTO `modul` (`id`, `jadwal_mengajar_id`, `judul`, `deskripsi`, `urutan`, `created_at`) VALUES
(3, 10, 'Bab 1 : Pancasila', '', 1, '2026-05-15 15:23:51');

-- --------------------------------------------------------

--
-- Table structure for table `modul_item`
--

CREATE TABLE `modul_item` (
  `id` int NOT NULL,
  `modul_id` int NOT NULL,
  `tipe` enum('materi','kuis','live_class') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi_teks` longtext,
  `file_path` varchar(255) DEFAULT NULL,
  `durasi_menit` int DEFAULT NULL,
  `urutan` int DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `modul_item`
--

INSERT INTO `modul_item` (`id`, `modul_id`, `tipe`, `judul`, `isi_teks`, `file_path`, `durasi_menit`, `urutan`, `created_at`) VALUES
(9, 3, 'materi', 'Pengertian Pancasila', '<p data-start=\"20\" data-end=\"428\"><span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Pancasila</span> adalah dasar negara dan ideologi bangsa Indonesia. Pancasila berasal dari bahasa Sanskerta, yaitu “Panca” yang berarti lima dan “Sila” yang berarti dasar atau prinsip. Jadi, Pancasila memiliki arti lima dasar yang dijadikan pedoman dalam kehidupan berbangsa dan bernegara. Pancasila juga menjadi pandangan hidup bangsa Indonesia dalam bersikap dan bertindak sehari-hari.</p><p data-start=\"430\" data-end=\"887\">Pancasila dirumuskan pada sidang <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">BPUPKI</span> tahun 1945. Tokoh-tokoh yang berperan penting dalam perumusan Pancasila antara lain <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Soekarno</span>, <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Mohammad Hatta</span>, <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Mohammad Yamin</span>, dan <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Soepomo</span>. Pada tanggal 1 Juni 1945, <span class=\"hover:entity-accent entity-underline inline cursor-pointer align-baseline\">Soekarno</span> menyampaikan rumusan dasar negara yang kemudian dikenal dengan nama Pancasila.</p><p data-start=\"889\" data-end=\"921\">Adapun bunyi Pancasila adalah:</p><ol data-start=\"922\" data-end=\"1154\">\r\n<li data-start=\"922\" data-end=\"950\">\r\nKetuhanan Yang Maha Esa\r\n</li>\r\n<li data-start=\"951\" data-end=\"989\">\r\nKemanusiaan yang adil dan beradab\r\n</li>\r\n<li data-start=\"990\" data-end=\"1014\">\r\nPersatuan Indonesia\r\n</li>\r\n<li data-start=\"1015\" data-end=\"1103\">\r\nKerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan\r\n</li>\r\n<li data-start=\"1104\" data-end=\"1154\">\r\nKeadilan sosial bagi seluruh rakyat Indonesia\r\n</li>\r\n</ol><p data-start=\"1156\" data-end=\"1536\">Sila pertama, “Ketuhanan Yang Maha Esa”, mengandung makna bahwa bangsa Indonesia percaya dan bertakwa kepada Tuhan Yang Maha Esa. Setiap warga negara bebas memeluk agama dan beribadah sesuai keyakinannya masing-masing. Sikap yang mencerminkan sila pertama misalnya menghormati pemeluk agama lain, menjaga toleransi antarumat beragama, dan tidak memaksakan agama kepada orang lain.</p><p data-start=\"1538\" data-end=\"1821\">Sila kedua, “Kemanusiaan yang adil dan beradab”, mengajarkan bahwa setiap manusia memiliki derajat yang sama dan harus diperlakukan dengan adil serta beradab. Contoh penerapannya adalah saling menolong, menghormati orang lain, bersikap sopan, dan menjunjung tinggi nilai kemanusiaan.</p><p data-start=\"1823\" data-end=\"2115\">Sila ketiga, “Persatuan Indonesia”, memiliki makna bahwa seluruh rakyat Indonesia harus menjaga persatuan dan kesatuan bangsa walaupun memiliki perbedaan suku, agama, budaya, dan bahasa. Contoh penerapan sila ini adalah mencintai tanah air, menjaga kerukunan, dan tidak membeda-bedakan teman.</p><p data-start=\"2117\" data-end=\"2488\">Sila keempat, “Kerakyatan yang dipimpin oleh hikmat kebijaksanaan dalam permusyawaratan/perwakilan”, mengajarkan pentingnya musyawarah dalam mengambil keputusan bersama. Setiap orang harus menghargai pendapat orang lain dan tidak memaksakan kehendak. Contohnya adalah melakukan musyawarah saat menentukan keputusan bersama dan menerima hasil keputusan dengan lapang dada.</p><p data-start=\"2490\" data-end=\"2752\">Sila kelima, “Keadilan sosial bagi seluruh rakyat Indonesia”, berarti setiap warga negara berhak mendapatkan keadilan dan kesejahteraan. Contoh penerapannya yaitu bersikap adil kepada semua orang, membantu sesama yang membutuhkan, dan menghormati hak orang lain.</p><p>\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n\r\n</p><p data-start=\"2754\" data-end=\"3169\">Pancasila memiliki beberapa fungsi penting, yaitu sebagai dasar negara, pandangan hidup bangsa, ideologi negara, dan sumber dari segala sumber hukum di Indonesia. Nilai-nilai yang terkandung dalam Pancasila meliputi nilai ketuhanan, kemanusiaan, persatuan, kerakyatan, dan keadilan. Nilai-nilai tersebut harus diterapkan dalam kehidupan sehari-hari agar tercipta masyarakat yang damai, bersatu, adil, dan sejahtera.</p>', '078a04021356358be9d607515a9eb84d.pdf', NULL, 1, '2026-05-15 15:36:50'),
(10, 3, 'kuis', 'pancasila', '', NULL, 15, 2, '2026-05-15 15:39:24'),
(11, 3, 'materi', 'materi 3', '', '350c87d54452785e385f5357445afe06.pdf', NULL, 3, '2026-05-20 16:27:01'),
(13, 3, 'kuis', 'h', 'iu', NULL, 15, 4, '2026-05-21 02:55:00');

-- --------------------------------------------------------

--
-- Table structure for table `notifikasi`
--

CREATE TABLE `notifikasi` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `pesan` text NOT NULL,
  `link` varchar(255) DEFAULT NULL,
  `dibaca` tinyint(1) DEFAULT '0',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `notifikasi`
--

INSERT INTO `notifikasi` (`id`, `user_id`, `pesan`, `link`, `dibaca`, `created_at`) VALUES
(2, 30, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(3, 35, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(4, 33, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(5, 45, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(6, 40, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(7, 36, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(8, 47, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(9, 34, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(10, 32, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(11, 44, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(12, 41, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(13, 28, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(14, 29, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(15, 43, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(16, 37, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(17, 46, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(18, 39, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(19, 31, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(20, 38, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(21, 42, 'Forum Baru: pancasila', '?page=s_forum', 0, '2026-05-15 23:16:48'),
(22, 9, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(23, 10, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(24, 11, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(25, 12, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(26, 13, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(27, 14, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(28, 15, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(29, 16, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(30, 17, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(31, 18, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(32, 19, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(33, 20, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(34, 21, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(35, 22, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(36, 23, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(37, 24, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(38, 25, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(39, 26, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(40, 27, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(41, 28, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(42, 29, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(43, 30, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(44, 31, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(45, 32, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(46, 33, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(47, 34, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(48, 35, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(49, 36, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(50, 37, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(51, 38, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(52, 39, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(53, 40, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(54, 41, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(55, 42, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(56, 43, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(57, 44, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(58, 45, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(59, 46, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(60, 47, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(61, 48, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(62, 49, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(63, 50, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(64, 51, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(65, 52, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(66, 53, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(67, 54, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(68, 55, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(69, 56, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(70, 57, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(71, 58, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(72, 59, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(73, 60, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(74, 61, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(75, 62, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(76, 63, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(77, 64, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(78, 65, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(79, 66, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(80, 67, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(81, 68, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(82, 69, 'Pengumuman Baru: Rekreasi', '?page=dashboard', 0, '2026-05-16 02:48:47'),
(83, 28, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(84, 29, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(85, 30, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(86, 31, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(87, 32, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(88, 33, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(89, 34, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(90, 35, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(91, 36, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(92, 37, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(93, 38, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(94, 39, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(95, 40, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(96, 41, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(97, 42, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(98, 43, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(99, 44, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(100, 45, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(101, 46, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(102, 47, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:27'),
(103, 48, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(104, 49, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(105, 50, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(106, 51, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(107, 52, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(108, 53, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(109, 54, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(110, 55, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(111, 56, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(112, 57, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(113, 58, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(114, 59, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(115, 60, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(116, 61, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(117, 62, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(118, 63, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(119, 64, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(120, 65, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(121, 66, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(122, 67, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(123, 68, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(124, 69, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(125, 72, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(126, 73, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(127, 74, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(128, 75, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(129, 76, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(130, 77, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(131, 78, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(132, 79, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(133, 80, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(134, 81, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(135, 82, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(136, 83, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(137, 84, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(138, 85, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(139, 86, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(140, 87, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(141, 88, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(142, 89, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(143, 90, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(144, 91, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(145, 92, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(146, 93, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(147, 94, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(148, 95, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(149, 96, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(150, 97, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(151, 98, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(152, 99, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(153, 100, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(154, 101, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(155, 102, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(156, 103, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(157, 104, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(158, 105, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(159, 106, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(160, 107, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(161, 108, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(162, 109, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(163, 110, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(164, 111, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(165, 112, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(166, 113, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(167, 114, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(168, 115, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(169, 116, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(170, 117, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(171, 118, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(172, 119, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(173, 120, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(174, 121, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(175, 122, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(176, 123, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(177, 124, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(178, 125, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(179, 126, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(180, 127, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(181, 128, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(182, 129, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(183, 130, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(184, 131, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(185, 132, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(186, 133, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(187, 134, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(188, 135, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(189, 136, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(190, 137, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(191, 138, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(192, 139, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(193, 140, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(194, 141, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(195, 142, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(196, 143, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(197, 144, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(198, 145, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(199, 146, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(200, 147, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(201, 148, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(202, 149, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(203, 150, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(204, 151, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(205, 152, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(206, 153, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(207, 154, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(208, 155, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(209, 156, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(210, 157, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(211, 158, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(212, 159, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(213, 160, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(214, 161, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(215, 162, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(216, 163, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(217, 164, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(218, 165, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(219, 166, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(220, 167, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(221, 168, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(222, 169, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(223, 170, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(224, 171, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(225, 172, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(226, 173, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(227, 174, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(228, 175, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(229, 176, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(230, 177, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(231, 178, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(232, 179, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(233, 180, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(234, 181, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(235, 182, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(236, 183, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(237, 184, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(238, 185, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(239, 186, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(240, 187, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(241, 188, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(242, 189, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(243, 190, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(244, 191, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(245, 192, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(246, 193, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(247, 194, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(248, 195, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(249, 196, 'Pengumuman Baru: Ujian Tengah Semester', '?page=dashboard', 0, '2026-05-19 13:57:28'),
(250, 28, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(251, 29, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(252, 30, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(253, 31, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(254, 32, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(255, 33, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(256, 34, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(257, 35, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(258, 36, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(259, 37, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(260, 38, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(261, 39, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(262, 40, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(263, 41, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(264, 42, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(265, 43, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(266, 44, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(267, 45, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(268, 46, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(269, 47, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(270, 48, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(271, 49, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(272, 50, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(273, 51, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(274, 52, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(275, 53, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(276, 54, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(277, 55, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(278, 56, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(279, 57, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(280, 58, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(281, 59, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(282, 60, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(283, 61, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(284, 62, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(285, 63, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(286, 64, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(287, 65, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(288, 66, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(289, 67, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(290, 68, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(291, 69, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(292, 72, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(293, 73, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(294, 74, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(295, 75, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(296, 76, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(297, 77, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(298, 78, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(299, 79, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(300, 80, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(301, 81, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(302, 82, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(303, 83, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(304, 84, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(305, 85, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(306, 86, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(307, 87, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(308, 88, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(309, 89, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(310, 90, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(311, 91, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(312, 92, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(313, 93, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(314, 94, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(315, 95, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(316, 96, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(317, 97, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(318, 98, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(319, 99, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(320, 100, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(321, 101, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(322, 102, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(323, 103, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(324, 104, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(325, 105, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(326, 106, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(327, 107, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(328, 108, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(329, 109, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(330, 110, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(331, 111, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(332, 112, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(333, 113, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(334, 114, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(335, 115, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(336, 116, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(337, 117, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(338, 118, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(339, 119, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(340, 120, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(341, 121, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(342, 122, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(343, 123, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(344, 124, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(345, 125, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(346, 126, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(347, 127, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(348, 128, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(349, 129, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(350, 130, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(351, 131, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(352, 132, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(353, 133, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(354, 134, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(355, 135, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(356, 136, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(357, 137, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(358, 138, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(359, 139, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(360, 140, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(361, 141, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(362, 142, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(363, 143, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(364, 144, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(365, 145, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(366, 146, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(367, 147, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(368, 148, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(369, 149, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(370, 150, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(371, 151, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(372, 152, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(373, 153, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(374, 154, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(375, 155, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(376, 156, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(377, 157, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(378, 158, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(379, 159, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(380, 160, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(381, 161, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(382, 162, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(383, 163, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(384, 164, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(385, 165, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(386, 166, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(387, 167, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(388, 168, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(389, 169, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(390, 170, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(391, 171, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(392, 172, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(393, 173, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(394, 174, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(395, 175, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(396, 176, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(397, 177, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(398, 178, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(399, 179, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(400, 180, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(401, 181, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(402, 182, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(403, 183, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(404, 184, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(405, 185, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(406, 186, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(407, 187, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(408, 188, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(409, 189, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(410, 190, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(411, 191, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(412, 192, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(413, 193, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(414, 194, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(415, 195, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(416, 196, 'Pengumuman Baru: Memperingati Hari Pendidikan Nasional', '?page=dashboard', 0, '2026-05-19 14:12:12'),
(417, 9, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(418, 10, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(419, 11, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(420, 12, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(421, 13, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(422, 14, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(423, 15, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(424, 16, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(425, 17, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(426, 18, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(427, 19, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(428, 20, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(429, 21, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(430, 22, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(431, 23, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(432, 24, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(433, 25, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(434, 26, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(435, 27, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(436, 28, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(437, 29, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(438, 30, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(439, 31, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(440, 32, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(441, 33, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(442, 34, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(443, 35, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(444, 36, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(445, 37, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(446, 38, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(447, 39, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(448, 40, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(449, 41, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(450, 42, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(451, 43, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(452, 44, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(453, 45, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(454, 46, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(455, 47, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(456, 48, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(457, 49, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(458, 50, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(459, 51, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(460, 52, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(461, 53, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(462, 54, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(463, 55, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(464, 56, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(465, 57, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(466, 58, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(467, 59, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(468, 60, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(469, 61, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(470, 62, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(471, 63, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(472, 64, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(473, 65, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(474, 66, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(475, 67, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(476, 68, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(477, 69, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(478, 72, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(479, 73, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(480, 74, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(481, 75, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(482, 76, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(483, 77, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(484, 78, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(485, 79, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(486, 80, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(487, 81, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(488, 82, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(489, 83, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(490, 84, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(491, 85, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(492, 86, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(493, 87, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(494, 88, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(495, 89, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(496, 90, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(497, 91, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(498, 92, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(499, 93, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(500, 94, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(501, 95, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(502, 96, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(503, 97, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(504, 98, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(505, 99, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(506, 100, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(507, 101, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(508, 102, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(509, 103, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(510, 104, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(511, 105, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46');
INSERT INTO `notifikasi` (`id`, `user_id`, `pesan`, `link`, `dibaca`, `created_at`) VALUES
(512, 106, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(513, 107, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(514, 108, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(515, 109, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(516, 110, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(517, 111, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(518, 112, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(519, 113, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(520, 114, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(521, 115, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(522, 116, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(523, 117, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(524, 118, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(525, 119, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(526, 120, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(527, 121, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(528, 122, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(529, 123, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(530, 124, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(531, 125, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(532, 126, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(533, 127, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(534, 128, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(535, 129, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(536, 130, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(537, 131, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(538, 132, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(539, 133, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(540, 134, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(541, 135, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(542, 136, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(543, 137, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(544, 138, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(545, 139, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(546, 140, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(547, 141, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(548, 142, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(549, 143, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(550, 144, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(551, 145, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(552, 146, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(553, 147, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(554, 148, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(555, 149, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(556, 150, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(557, 151, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(558, 152, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(559, 153, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(560, 154, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(561, 155, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(562, 156, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(563, 157, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(564, 158, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(565, 159, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(566, 160, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(567, 161, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(568, 162, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(569, 163, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(570, 164, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(571, 165, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(572, 166, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(573, 167, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(574, 168, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(575, 169, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(576, 170, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(577, 171, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(578, 172, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(579, 173, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(580, 174, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(581, 175, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(582, 176, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(583, 177, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(584, 178, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(585, 179, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(586, 180, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(587, 181, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(588, 182, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(589, 183, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(590, 184, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(591, 185, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(592, 186, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(593, 187, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(594, 188, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(595, 189, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(596, 190, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(597, 191, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(598, 192, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(599, 193, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(600, 194, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(601, 195, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(602, 196, 'Pengumuman Baru: kdjfcniwnkjfen', '?page=dashboard', 0, '2026-05-20 15:31:46'),
(603, 9, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(604, 10, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(605, 11, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(606, 12, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(607, 13, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(608, 14, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(609, 15, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(610, 16, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(611, 17, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(612, 18, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(613, 19, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(614, 20, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(615, 21, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(616, 22, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(617, 23, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(618, 24, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(619, 25, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(620, 26, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(621, 27, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(622, 28, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(623, 29, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(624, 30, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(625, 31, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(626, 32, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(627, 33, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(628, 34, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(629, 35, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(630, 36, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(631, 37, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(632, 38, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(633, 39, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(634, 40, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(635, 41, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(636, 42, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(637, 43, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(638, 44, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(639, 45, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(640, 46, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(641, 47, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(642, 48, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(643, 49, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(644, 50, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(645, 51, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(646, 52, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(647, 53, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(648, 54, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(649, 55, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(650, 56, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(651, 57, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(652, 58, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(653, 59, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(654, 60, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(655, 61, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(656, 62, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(657, 63, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(658, 64, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(659, 65, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(660, 66, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(661, 67, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(662, 68, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(663, 69, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(664, 72, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(665, 73, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(666, 74, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(667, 75, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(668, 76, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(669, 77, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(670, 78, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(671, 79, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(672, 80, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(673, 81, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(674, 82, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(675, 83, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(676, 84, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(677, 85, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(678, 86, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(679, 87, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(680, 88, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(681, 89, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(682, 90, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(683, 91, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(684, 92, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(685, 93, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(686, 94, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(687, 95, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:58'),
(688, 96, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(689, 97, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(690, 98, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(691, 99, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(692, 100, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(693, 101, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(694, 102, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(695, 103, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(696, 104, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(697, 105, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(698, 106, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(699, 107, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(700, 108, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(701, 109, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(702, 110, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(703, 111, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(704, 112, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(705, 113, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(706, 114, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(707, 115, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(708, 116, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(709, 117, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(710, 118, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(711, 119, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(712, 120, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(713, 121, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(714, 122, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(715, 123, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(716, 124, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(717, 125, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(718, 126, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(719, 127, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(720, 128, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(721, 129, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(722, 130, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(723, 131, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(724, 132, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(725, 133, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(726, 134, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(727, 135, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(728, 136, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(729, 137, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(730, 138, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(731, 139, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(732, 140, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(733, 141, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(734, 142, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(735, 143, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(736, 144, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(737, 145, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(738, 146, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(739, 147, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(740, 148, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(741, 149, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(742, 150, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(743, 151, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(744, 152, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(745, 153, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(746, 154, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(747, 155, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(748, 156, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(749, 157, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(750, 158, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(751, 159, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(752, 160, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(753, 161, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(754, 162, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(755, 163, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(756, 164, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(757, 165, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(758, 166, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(759, 167, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(760, 168, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(761, 169, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(762, 170, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(763, 171, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(764, 172, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(765, 173, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(766, 174, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(767, 175, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(768, 176, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(769, 177, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(770, 178, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(771, 179, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(772, 180, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(773, 181, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(774, 182, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(775, 183, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(776, 184, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(777, 185, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(778, 186, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(779, 187, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(780, 188, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(781, 189, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(782, 190, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(783, 191, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(784, 192, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(785, 193, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(786, 194, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(787, 195, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(788, 196, 'Pengumuman Baru: mjgjj', '?page=dashboard', 0, '2026-05-20 15:32:59'),
(789, 65, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(790, 62, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(791, 53, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(792, 66, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(793, 63, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(794, 64, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(795, 58, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(796, 57, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(797, 54, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(798, 49, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(799, 59, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(800, 52, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(801, 50, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(802, 69, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(803, 56, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(804, 55, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(805, 51, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(806, 48, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(807, 68, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(808, 67, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(809, 60, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(810, 61, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:25'),
(811, 65, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(812, 62, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(813, 53, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(814, 66, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(815, 63, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(816, 64, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(817, 58, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(818, 57, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(819, 54, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(820, 49, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(821, 59, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(822, 52, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(823, 50, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(824, 69, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(825, 56, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(826, 55, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(827, 51, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(828, 48, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(829, 68, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(830, 67, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(831, 60, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(832, 61, 'Forum Baru: a', '?page=s_forum', 0, '2026-05-20 16:31:40'),
(833, 65, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(834, 62, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(835, 53, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(836, 66, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(837, 63, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(838, 64, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(839, 58, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(840, 57, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(841, 54, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(842, 49, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(843, 59, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(844, 52, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(845, 50, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(846, 69, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(847, 56, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(848, 55, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:05'),
(849, 51, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06'),
(850, 48, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06'),
(851, 68, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06'),
(852, 67, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06'),
(853, 60, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06'),
(854, 61, 'Forum Baru: gg', '?page=s_forum', 0, '2026-05-20 16:36:06');

-- --------------------------------------------------------

--
-- Table structure for table `pengumpulan_tugas`
--

CREATE TABLE `pengumpulan_tugas` (
  `id` int NOT NULL,
  `tugas_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `catatan` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `nilai` double DEFAULT NULL,
  `feedback_guru` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `dikumpulkan_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pengumuman`
--

CREATE TABLE `pengumuman` (
  `id` int NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `target_role` enum('semua','guru','siswa') DEFAULT 'semua',
  `created_by` int NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `pengumuman`
--

INSERT INTO `pengumuman` (`id`, `judul`, `isi`, `target_role`, `created_by`, `created_at`) VALUES
(1, 'Rekreasi', 'Tanggal 16/05/2026 kita akan mengadakan rekreasi, diharapkan hadir sebelum jam 7, informasi langsung dari ibu kepala sekolah', 'semua', 1, '2026-05-16 02:48:47'),
(2, 'Ujian Tengah Semester', 'Diharapkan Siswa Untuk mencukur rambutnya minimal 2cm, jika lewat dari 2cm akan dikenakan sanksi', 'siswa', 1, '2026-05-19 13:57:27'),
(3, 'Memperingati Hari Pendidikan Nasional', 'Diumumkan keepada seluruh siswa MTs PP DDI Al-Barakah, dalam rangka memperingati hari Pendidikan Nasional tanggal 2 Mei 2026, sekolah akan melaksanakan upacara. Sehubung dengan hal itu, semua siswa diharapkan hadir di halaman sekolah tepat pukul 07.00 mengenakan pakaian seragam merah-putih.', 'siswa', 1, '2026-05-19 14:12:12'),
(4, 'kdjfcniwnkjfen', 'rejhfrnvkb', 'semua', 1, '2026-05-20 15:31:46'),
(5, 'mjgjj', 'j', 'semua', 1, '2026-05-20 15:32:58');

-- --------------------------------------------------------

--
-- Table structure for table `presensi`
--

CREATE TABLE `presensi` (
  `id` int NOT NULL,
  `jadwal_mengajar_id` int NOT NULL,
  `tanggal` date NOT NULL,
  `pertemuan_ke` int DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `status` enum('buka','tutup') DEFAULT 'buka',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `presensi_siswa`
--

CREATE TABLE `presensi_siswa` (
  `id` int NOT NULL,
  `presensi_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `status_hadir` enum('hadir','izin','sakit','alpa') DEFAULT 'alpa',
  `waktu_absen` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `progress_materi`
--

CREATE TABLE `progress_materi` (
  `id` int NOT NULL,
  `item_id` int NOT NULL,
  `siswa_id` int NOT NULL,
  `diselesaikan_pada` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `progress_materi`
--

INSERT INTO `progress_materi` (`id`, `item_id`, `siswa_id`, `diselesaikan_pada`) VALUES
(10, 9, 45, '2026-05-15 23:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `tahun_ajaran`
--

CREATE TABLE `tahun_ajaran` (
  `id` int NOT NULL,
  `nama` varchar(50) NOT NULL,
  `is_aktif` tinyint(1) DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `tahun_ajaran`
--

INSERT INTO `tahun_ajaran` (`id`, `nama`, `is_aktif`) VALUES
(1, '2025/2026 Ganjil', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tugas`
--

CREATE TABLE `tugas` (
  `id` int NOT NULL,
  `jadwal_mengajar_id` int NOT NULL,
  `judul` varchar(200) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `deskripsi` text CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci,
  `file_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deadline` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int NOT NULL,
  `nama` varchar(100) NOT NULL,
  `no_hp` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('admin','guru','siswa') NOT NULL,
  `nis_nip` varchar(50) DEFAULT NULL,
  `foto_profil` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT '1',
  `last_login` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `login_attempts` int DEFAULT '0',
  `login_locked_until` varchar(255) DEFAULT NULL,
  `last_login_attempt` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `nama`, `no_hp`, `password`, `role`, `nis_nip`, `foto_profil`, `is_active`, `last_login`, `created_at`, `login_attempts`, `login_locked_until`, `last_login_attempt`) VALUES
(1, 'Administrator', NULL, '$2y$10$OsmeXrnbuafQYKqGUFv5KeYSCx02sDyiI.4x5cDT29yp8rk/BOIbK', 'admin', 'admin', '1778902271_Screenshot20260206185436.png', 1, '2026-05-30 22:28:21', '2026-05-06 04:07:04', 0, NULL, NULL),
(9, 'Ahmad Safwan', NULL, '$2y$12$pC5Db2wPMiz/FGGC5LXEKeSD2YZiUyFHMlKYvM5B2a.Myuu.8gPhW', 'guru', '087799540261', NULL, 1, NULL, '2026-05-15 14:40:19', 0, NULL, NULL),
(10, 'Amirullah', NULL, '$2y$12$Sw8ZK8JT4jKqVAUetNA5auWdX6VHNAMr60ofpXuaOAR/rJRZ/6PUq', 'guru', '6049762664110073', NULL, 1, NULL, '2026-05-15 14:41:14', 0, NULL, NULL),
(11, 'Arimin', NULL, '$2y$12$VmJGLln9YcorPY/aRrKcG.lFbl6Pfc23oiErCEH7CVgwa1ZJGvMxO', 'guru', '5563743648200073', NULL, 1, NULL, '2026-05-15 14:41:40', 0, NULL, NULL),
(12, 'Ayu Kurnia', NULL, '$2y$12$yp8I8CkVme1Ve2sqMpql8e/V2UszQGhyWP/EBSS2hkLeKKbpi3h/y', 'guru', '7314025912990002', NULL, 1, NULL, '2026-05-15 14:42:04', 0, NULL, NULL),
(13, 'Darnia', NULL, '$2y$12$bcUkjv..2LtF7aM9mpk0susMX0QlC2OWF6hUlBg.exyL0g5LJjwwy', 'guru', '6746751652300042', NULL, 1, NULL, '2026-05-15 14:42:28', 0, NULL, NULL),
(14, 'Devi Lestari', NULL, '$2y$12$reIX9G838SihhSRVQWEy2ODfFasrifhidkxtHWjRZiFuz6P.rbqVq', 'guru', '40308839199001', NULL, 1, NULL, '2026-05-15 14:42:48', 0, NULL, NULL),
(15, 'Eka Putri Awaliah', NULL, '$2y$12$tcBloZmA3lX.UMHwuNnSG.CRdAt2Xpb8hXufICBJhNIJEbr0wPNEK', 'guru', '40308847190001', NULL, 1, NULL, '2026-05-15 14:43:07', 0, NULL, NULL),
(16, 'Ering Karlina', NULL, '$2y$12$7Eqrdck8ZgoW2D3s4NrdK.0cQ.A9zysqsHnSQ34AGVXod6P3VbXHK', 'guru', '7314024911910001', NULL, 1, NULL, '2026-05-15 14:43:26', 0, NULL, NULL),
(17, 'Firdaus', NULL, '$2y$12$u.rfZs73LpZOQ1CEvJqlA.waMkgAW9inD6Z8JmN9FoKx7357i223m', 'guru', '1260762664200003', NULL, 1, NULL, '2026-05-15 14:43:47', 0, NULL, NULL),
(18, 'Hasbullah Achmad', NULL, '$2y$12$FN7d.9oJ0ld9sJFs9ZNbzuJBrOChTYheX8X5w.i/65tF2T7HL9fnm', 'guru', '7314020711900004', NULL, 1, NULL, '2026-05-15 14:44:04', 0, NULL, NULL),
(19, 'Idil', NULL, '$2y$12$gtgWtFIzFT3RQ.gJHgKCgOyHh1adPvSC/8ySHF5wBwumMzQlQqe1y', 'guru', '3861765667120002', NULL, 1, NULL, '2026-05-15 14:44:23', 0, NULL, NULL),
(20, 'Kurniah', NULL, '$2y$12$YJv/E8ZEr5NWq9oNAdl8.uBL/Aq0JHL8lLtzq..NXdmoIAJUnWp/y', 'guru', '6437762663300072', NULL, 1, NULL, '2026-05-15 14:44:40', 0, NULL, NULL),
(21, 'Marlina', NULL, '$2y$12$R7y8X2bIVcRcPVEeQpzodeMCET6Vtv5dBkJ/Q0ae2RWT/vzpaDEju', 'guru', '7434750653300013', NULL, 1, NULL, '2026-05-15 14:44:56', 0, NULL, NULL),
(22, 'Muh Ansar Tahir', NULL, '$2y$12$nB0BdvivuvqOg.XAzEbjxOQW5AhUD6YXhIIYajvmzeRP1556LvSeO', 'guru', '7314022705990001', NULL, 1, NULL, '2026-05-15 14:45:15', 0, NULL, NULL),
(23, 'Andi Nurwaqiah', NULL, '$2y$12$60QF4NV1BGPlkioBauDbiOdSfV9gz3JDx5KvARAWB.r0bFkAk4jhK', 'guru', '40308839175001', NULL, 1, NULL, '2026-05-15 14:45:32', 0, NULL, NULL),
(24, 'Ramayana', NULL, '$2y$12$jOF3AmO.VpSy5/yvjNHIDO5R8iYc1vnGspAzMU.Z2C/ww5ed2ywdW', 'guru', '40316583192002', NULL, 1, NULL, '2026-05-15 14:45:52', 0, NULL, NULL),
(25, 'Rahmatullah', NULL, '$2y$12$3g6KGTbf8QicW84cxEO8pefEYBHDQQ3WD.UB7Ue4GLfNMSJ9t81VW', 'guru', '73140217129660001', NULL, 1, NULL, '2026-05-15 14:46:09', 0, NULL, NULL),
(26, 'Sri Handayani', NULL, '$2y$12$qrea/mzlrt7rbwYOP5CYre2Swr1VCZebqKjMSl87rfJGk0sxTkxdG', 'guru', '40308839198001', NULL, 1, NULL, '2026-05-15 14:46:32', 0, NULL, NULL),
(27, 'Suharyono', NULL, '$2y$12$9N80UDLfRBEjO/7mXOJf8OVl.TrXDC1DCRWUQU3nB9q3TrGNFL4rO', 'guru', '40308839188002', NULL, 1, '2026-05-30 22:28:49', '2026-05-15 14:46:57', 0, NULL, NULL),
(28, 'Muhammad Ayyub. S', NULL, '$2y$12$2Ntyp8.rryRYzyH2jwQKEuNAZ990rBhMAxxcDnCJo5655sPoGY7ey', 'siswa', '3137838994', NULL, 1, NULL, '2026-05-15 15:00:40', 0, NULL, NULL),
(29, 'Muhammad Wildan Usman', NULL, '$2y$12$yZlypXTzQkte3U53slhoKO1Dx/pdm4RYySuN0HjqSBKC86NeIgklm', 'siswa', '3132067300', NULL, 1, NULL, '2026-05-15 15:00:58', 0, NULL, NULL),
(30, 'AHMAD HAIQAL', NULL, '$2y$12$uG2d7Wufani3BTWL1cdEoepL7wh8kv7zZRJO22.qzIxWzrMu6wQe6', 'siswa', '0137381926', NULL, 1, '2026-05-18 14:59:11', '2026-05-15 15:01:14', 0, NULL, NULL),
(31, 'SHAKILA AZZAHRA', NULL, '$2y$12$.sR4hBI/RmZXN88xniMVKujfE0p0bR.xNiuXKSYE1y7wZYdnFHlte', 'siswa', '3135969345', NULL, 1, '2026-05-16 10:33:29', '2026-05-15 15:01:29', 0, NULL, NULL),
(32, 'MUH. DANIL', NULL, '$2y$12$VEXvxtRRrupG0TLtrhaISulUC1OQY5FHQrVQH2gqDCpOF1CMzvuYm', 'siswa', '3121908311', NULL, 1, NULL, '2026-05-15 15:01:44', 0, NULL, NULL),
(33, 'ALYA USMAN', NULL, '$2y$12$1TuUMNRA7lCdFNXKHrv5sOOeL6bpVq9yzhAGFyhMM1c9M8Q7HZL16', 'siswa', '3128030085', NULL, 1, NULL, '2026-05-15 15:01:58', 0, NULL, NULL),
(34, 'MUH. ADZZAN', NULL, '$2y$12$iKeMicU8vijxwRWvwPNQK.WhZu7/8zjD9CYUL5oWTTYvzM/yUEr8a', 'siswa', '3136738207', NULL, 1, NULL, '2026-05-15 15:02:13', 0, NULL, NULL),
(35, 'ALFIANSYAH', NULL, '$2y$12$9cphRvn0o2pOcvI1KloXO.N8CF/791qwLKxAr8G4rsnKJeoTnSXqW', 'siswa', '3131384938', NULL, 1, NULL, '2026-05-15 15:02:27', 0, NULL, NULL),
(36, 'M. RUSYDAN ABDI', NULL, '$2y$12$L//SsFqr4pZl1NIw/Hlzbeer6NWR0TrQ1ceX2sOlDnsrkyFY.NG.S', 'siswa', '0131870552', NULL, 1, NULL, '2026-05-15 15:02:42', 0, NULL, NULL),
(37, 'NUR MAGAPIRA', NULL, '$2y$12$fKOlWQeoNBX5DZ6jdBSFOOIL8hEQGMsODD62PycQci1/teSxaJ0Ta', 'siswa', '3135250988', NULL, 1, '2026-05-16 16:34:58', '2026-05-15 15:03:37', 0, NULL, NULL),
(38, 'WASIATUL IMMIL', NULL, '$2y$12$Yg3nYacEJbYKZm0rWzx4v.G0IfLFXCRNTQ9vzCHF5MxKP0Tx5D9im', 'siswa', '3124605695', NULL, 1, NULL, '2026-05-15 15:03:53', 0, NULL, NULL),
(39, 'SAHRA', NULL, '$2y$12$DLBmVjIE/fih/I1BO9OX1en/S4q9N4c8MipXklvLkZGkWMYhD4e2K', 'siswa', '0128570921', NULL, 1, NULL, '2026-05-15 15:04:07', 0, NULL, NULL),
(40, 'Din Ahsan Kamil', NULL, '$2y$12$A/ATF0ikrPd.ySrr9bLCFOsJQpvGnYfG2kqd/BnNTIH9T6cMKFkdS', 'siswa', '3137136869', NULL, 1, NULL, '2026-05-15 15:04:21', 0, NULL, NULL),
(41, 'MUH. SUBHAN', NULL, '$2y$12$Mlv04MBACSLnit.NZiYhFedeYxe472Sw3Akg.O36q4Mf6Ah40o8Ui', 'siswa', '0126024967', NULL, 1, NULL, '2026-05-15 15:04:35', 0, NULL, NULL),
(42, 'ZAHRATUL AEN MAWARDI', NULL, '$2y$12$B1Q88gFYpBCjgxsiowaF6e69XJRhkB4aQmpY.mYjTqmg5RhGsGlGK', 'siswa', '3121103255', NULL, 1, NULL, '2026-05-15 15:04:48', 0, NULL, NULL),
(43, 'NADIA SHAFWA SAHARUDDIN', NULL, '$2y$12$hxvAI7.MvLNXnL0ul45oyuA413DgHsIJWoJ29VBfFB/0rgc.D.WWi', 'siswa', '3137841518', NULL, 1, NULL, '2026-05-15 15:05:05', 0, NULL, NULL),
(44, 'MUH. HIDAYATULLAH. FIRDAUS', NULL, '$2y$12$XiKZvRb2uTNeGLqVFrFVve7uAITKynjKixC18kh6H04mbgRA9oM0O', 'siswa', '3132055954', NULL, 1, NULL, '2026-05-15 15:05:19', 0, NULL, NULL),
(45, 'ARDI', '081936698105', '$2y$12$H4URHK1YYepngkkOfEvj.Or8DC8Z2qrKcqvoVMNf63XvmOIzBMSz.', 'siswa', '3126806156', NULL, 1, '2026-05-22 08:47:33', '2026-05-15 15:05:34', 0, NULL, NULL),
(46, 'QATRINI NASIR', NULL, '$2y$12$BhXYt3.QK2/f/O.ey/Eya.jEYR303HgNSYliPMFo9JMjuzsRlyuDe', 'siswa', '3136964820', NULL, 1, NULL, '2026-05-15 15:05:55', 0, NULL, NULL),
(47, 'MUH IHSAN', NULL, '$2y$12$LbXl0RjDQ8tSclp/XRlh1eDxVlwcLhEV5EqkMeyvOEAvFquMuotUW', 'siswa', '0132085074', NULL, 1, '2026-05-16 10:35:32', '2026-05-15 15:06:13', 0, NULL, NULL),
(48, 'MUHAMMAD MIFTHA SA\'BANG', NULL, '$2y$12$NydqHDZEmoBCd8mx2/6PCe7mNGI9wuNTlsJ9jjBp5H7X3nroJct6m', 'siswa', '0133485521', NULL, 1, NULL, '2026-05-15 15:13:35', 0, NULL, NULL),
(49, 'ANDI SHARIMUL ADLI', NULL, '$2y$12$DgSKmiXJRSC01wdcrLfK0.ox8L9ei7.1ZvcKE3bdE8AhIz6aAgiZi', 'siswa', '3122533084', NULL, 1, NULL, '2026-05-15 15:13:57', 0, NULL, NULL),
(50, 'IQRIMA SAFRUDDIN', NULL, '$2y$12$HzqV0Olmg/AXofKMaR/gyOoU.c8VuqGcuDX525PgMar7vL.xLdm7y', 'siswa', '0123574798', NULL, 1, NULL, '2026-05-15 15:14:09', 0, NULL, NULL),
(51, 'MUHAMMAD HAIKAL', NULL, '$2y$12$/WVUqIkXKjiDakjsMhR2eedHHs/k9Vya8n.hNzATPfTWU9xWx3mmW', 'siswa', '3137565167', NULL, 1, NULL, '2026-05-15 15:14:21', 0, NULL, NULL),
(52, 'FAKHIRA SALWA NABILA H', NULL, '$2y$12$.iwmxJzGhFSlY2feGa3yCuaYTzCdnM73CG5AvmH3xswpa681W0PVW', 'siswa', '0136988055', NULL, 1, NULL, '2026-05-15 15:14:32', 0, NULL, NULL),
(53, 'AHMAD AZDHAR SAPUTRA', NULL, '$2y$12$pBl0HYCb4msfDVZE9wDpH.rZZWaklKr6EvogL0igTLDlxq7YCnhra', 'siswa', '3125019827', NULL, 1, NULL, '2026-05-15 15:14:48', 0, NULL, NULL),
(54, 'ANDI MUHAMAD FITRA RAMADAN', NULL, '$2y$12$806fxJnDJh1mR1YhhZndmeAJF8uBF8t4TXwFSf7GqFO/ZQjALQeZC', 'siswa', '3139996792', NULL, 1, NULL, '2026-05-15 15:14:59', 0, NULL, NULL),
(55, 'MUHAMMAD ALIF', NULL, '$2y$12$hiG8BQXFDnaslB9AOwZBkuj2vsTyj3D3NIg0VUJWSXsjejVnba6ce', 'siswa', '3132822734', NULL, 1, NULL, '2026-05-15 15:15:09', 0, NULL, NULL),
(56, 'Muh. Akbar F', NULL, '$2y$12$USYOjTs49ZWTDGVP6E.0E.Yl/FEDcVOFB0XnUIc.K7uhq70wi/axa', 'siswa', '3138320741', NULL, 1, NULL, '2026-05-15 15:15:20', 0, NULL, NULL),
(57, 'AMMAR ALI FALIH', NULL, '$2y$12$CW94NefPJerC8P1rYPRtX.y84Bkyqoinrf2Wy4xUmGafVnDItXrxC', 'siswa', '0121967867', NULL, 1, NULL, '2026-05-15 15:15:32', 0, NULL, NULL),
(58, 'ANDI AHMAD GHALI', NULL, '$2y$12$9m/5aDLyH7jLmYC7e4DdDuxCXZ7Y5QajXIaTUJAdp2zMKGLbPCgzq', 'siswa', '3137684961', NULL, 1, NULL, '2026-05-15 15:15:43', 0, NULL, NULL),
(59, 'AULIA DARMAN', NULL, '$2y$12$JB0.EijPNuN4ehXO8y5fO.9n7vtog6rEf1puc4cB8QxdMEFmm9wXm', 'siswa', '0123829863', NULL, 1, NULL, '2026-05-15 15:15:53', 0, NULL, NULL),
(60, 'NAURATUL JANNAH', NULL, '$2y$12$LH8wbFMkzqX9hyRC/67ZO.exhtbwzJ5Up4g5d27.9iyV4P7Di2K3C', 'siswa', '0129724837', NULL, 1, NULL, '2026-05-15 15:16:07', 0, NULL, NULL),
(61, 'PUTRI RAHMADANI', NULL, '$2y$12$dqCNln41IvLHZ.KD4gVs.O.Wu1S2NT0MiUvSmqB7U.St32bI4os3G', 'siswa', '0126445338', NULL, 1, NULL, '2026-05-15 15:16:19', 0, NULL, NULL),
(62, 'ADIBAH RASYIQAH', NULL, '$2y$12$P.2VVQb/CBkQFWTZ6.D5a.Xyv3.KXIvT6k8zGUUb.UZTuUITzaB0a', 'siswa', '3137853167', NULL, 1, NULL, '2026-05-15 15:16:30', 0, NULL, NULL),
(63, 'AHMAD SYAHRIR', NULL, '$2y$12$87o8.fTzOX0yShRywHzubuCAcVdAUnMzOAsfeJm6G7c9oTNh4i48K', 'siswa', '0124598507', NULL, 1, NULL, '2026-05-15 15:16:45', 0, NULL, NULL),
(64, 'ALYA AZZAHRA', NULL, '$2y$12$Etv0MUZox4hxeb0HGCyiY.BE0USAW6PXeJs3G1K7fIKNvr6.Xz75i', 'siswa', '3138440070', NULL, 1, NULL, '2026-05-15 15:16:58', 0, NULL, NULL),
(65, 'ADELIA', NULL, '$2y$12$TE8aMknk9ryQCYC333sY/uno0aE8oQk.x1DpAwoFqePHU3jKYzoEO', 'siswa', '3138937581', NULL, 1, NULL, '2026-05-15 15:17:09', 0, NULL, NULL),
(66, 'AHMAD RAIS MUTTAQIL', NULL, '$2y$12$LzfYDj73m6mrvP57lXNUSekk0nTWC8yD0V.4dV45/1XyiXPaBsAsi', 'siswa', '3129628652', NULL, 1, NULL, '2026-05-15 15:17:19', 0, NULL, NULL),
(67, 'MUHAMMAD SYAWAL', NULL, '$2y$12$rJnOesqoBNDXFpOlfQ7QQefQzDrAqLziJw8Avc5dFvIUTb7sbjv3.', 'siswa', '3121701908', NULL, 1, NULL, '2026-05-15 15:17:30', 0, NULL, NULL),
(68, 'MUHAMMAD SYAHIR', NULL, '$2y$12$.HldxJSGacqyFQZV.YA4ZuG7a9uawAOFDFUPk5t81xRKrYTIJSYjK', 'siswa', '3122196174', NULL, 1, NULL, '2026-05-15 15:17:40', 0, NULL, NULL),
(69, 'MUH FADILAH KAMSYAH', NULL, '$2y$12$/nzkrEixywAkre6WS1np1.N8ezsl1uZHBWaxvXaJJ5CI2APsCSUHu', 'siswa', '0129185073', NULL, 1, NULL, '2026-05-15 15:17:50', 0, NULL, NULL),
(72, 'Moh. Ibrahim Mansur', NULL, '$2y$12$7nnYrh/1oVWm9Lbj8amWV.88EmIPWOCaXJ//aV2yE55yWTWByEfU2', 'siswa', '0138101907', NULL, 1, NULL, '2026-05-16 02:59:45', 0, NULL, NULL),
(73, 'MUH. RIFQY SHABIR', NULL, '$2y$12$8A.8SUrizkSRayw21cJUke7dDyqXQJOFBr2S1zh85eu6UWNN.Db5e', 'siswa', '3127898673', NULL, 1, NULL, '2026-05-16 03:00:06', 0, NULL, NULL),
(74, 'AHMAD FADIL', NULL, '$2y$12$DsXCS852DYJZDxkO2nQ/8u5z9Te0vpaWG9Z2s.WOaGegsIZdg5IpO', 'siswa', '0132404694', NULL, 1, NULL, '2026-05-16 03:00:17', 0, NULL, NULL),
(75, 'NUR AINUN', NULL, '$2y$12$0tsof4rUzXBLswZh3jNo2O2hYsb4r6KMto3cX.v36gq7GbunIZs7m', 'siswa', '0139010563', NULL, 1, NULL, '2026-05-16 03:00:28', 0, NULL, NULL),
(76, 'SYAFI\'AH LIYANA ZAHIRA', NULL, '$2y$12$Ab3gjzMVaSbJK2nwWG4mFen6/kP6CBYoAWCSbpZytYHhgP13TR1Ia', 'siswa', '0124661039', NULL, 1, NULL, '2026-05-16 03:00:40', 0, NULL, NULL),
(77, 'Andi Abd. Hafizh', NULL, '$2y$12$uDtyfuvJK9IlCrxUXHpQJeZP3F54sxHzeHmXKja3BpjkbdMV2fvRy', 'siswa', '3126499815', NULL, 1, NULL, '2026-05-16 03:00:50', 0, NULL, NULL),
(78, 'MUHAMMAD FAIQ MUHARRAM', NULL, '$2y$12$vo9wFaPsIXEhx8hEtWT2oeR9Nt5tniAIq/hHC2VCn6Suv3ZF.hZBe', 'siswa', '3121765260', NULL, 1, NULL, '2026-05-16 03:01:00', 0, NULL, NULL),
(79, 'MUH. AZHABIL FATHAR', NULL, '$2y$12$ISSZt82ofnSodRW769wLOe64t9.xfDxSaFh/q0JU4bbr8M0xPaRI6', 'siswa', '0132025887', NULL, 1, NULL, '2026-05-16 03:01:12', 0, NULL, NULL),
(80, 'AMIRUL AZZAM. MZ', NULL, '$2y$12$/rF2chDUsuHu8tg3l2gNiOTFRvCOiPZuQ2lArGRrwP/xxKytu5q1a', 'siswa', '3137303913', NULL, 1, NULL, '2026-05-16 03:01:24', 0, NULL, NULL),
(81, 'Abid Fitrah Arizal', '', '$2y$12$6tJPYEImdG2Tq6wxPQIJVeNEhPS5B12xUnUYAOHHRrzPjMEeo0JSe', 'siswa', '0139974129', NULL, 1, NULL, '2026-05-16 03:01:35', 0, NULL, NULL),
(82, 'muh. nur hisyam kadir', NULL, '$2y$12$4osMDIkw/yK50ksHPuck9.cXjM6t9wnQTBvHLMjup/rfZ1pjGXTo2', 'siswa', '0137276443', NULL, 1, NULL, '2026-05-16 03:02:25', 0, NULL, NULL),
(83, 'MUHAMMAD AYYUB', NULL, '$2y$12$GJkVcoAjWETY0rLO0w0IlOBL3U08HWR1Id8/VGFqBspi9tLQpMYAy', 'siswa', '3133147619', NULL, 1, NULL, '2026-05-16 03:02:38', 0, NULL, NULL),
(84, 'NUR AFIFAH', NULL, '$2y$12$2rOPPKFIjOZvI7ReIIp0POcHnxv6br7IY0O6qj0Nfk1EfQBy/ic1m', 'siswa', '3125973393', NULL, 1, NULL, '2026-05-16 03:02:48', 0, NULL, NULL),
(85, 'ZAKA PUTRA HENDRA', NULL, '$2y$12$lwHxctE/yyQZJLLn/QMKpOv2tymcsX61hbu5.A368qfu58/rlA0qu', 'siswa', '0121737978', NULL, 1, NULL, '2026-05-16 03:03:00', 0, NULL, NULL),
(86, 'ZAKI PUTRA HENDRA', NULL, '$2y$12$wDCNIyBKaHZCHbmv.BVgDu/xfttO9AtoKfyeBvega4GRXv4F6iVbq', 'siswa', '3127400827', NULL, 1, NULL, '2026-05-16 03:03:10', 0, NULL, NULL),
(87, 'MUH. RIFQI', NULL, '$2y$12$JbqlaV0nbw5Vv3r1gkyLaOJz72EPlpHJJSJu9AYXEKvdZOQKDHeRG', 'siswa', '0137346352', NULL, 1, NULL, '2026-05-16 03:03:21', 0, NULL, NULL),
(88, 'MUH IDHAN', NULL, '$2y$12$val6nFRUVbicFbaTKvjtiO2usJUwTB9uw0QMfMn6/tnYMqLckZX92', 'siswa', '3134765810', NULL, 1, NULL, '2026-05-16 03:03:31', 0, NULL, NULL),
(89, 'AMANDA LESTARI', NULL, '$2y$12$tYVRYE8CN2hibSuv19cP4O7c27UT3awS/t4xtzeQQrVdB3rlAICtK', 'siswa', '0126820318', NULL, 1, NULL, '2026-05-16 03:06:29', 0, NULL, NULL),
(90, 'MUHAMMAD ADNAN FATUR RAHMAN', NULL, '$2y$12$c8NXuo4sJKRmFCS08qL5aunhXI65qxU0/tsjh35QtaKtic3YSaFG6', 'siswa', '3126784874', NULL, 1, NULL, '2026-05-16 03:06:43', 0, NULL, NULL),
(91, 'MUH. FAHRUL BAHARUDDIN', NULL, '$2y$12$vSM/7ZDflx2FhxW2uJ/43OPOzfSqDSSQxjVIHvEDnMiwSaOSWf5y2', 'siswa', '0119606596', NULL, 1, NULL, '2026-05-16 03:06:53', 0, NULL, NULL),
(92, 'NABILAH.S', NULL, '$2y$12$9TS0WDn.ipTL4QWAykyrRusdyCdEUtVlvCypYRC3EYXCfabKFJKva', 'siswa', '0119583735', NULL, 1, NULL, '2026-05-16 03:07:13', 0, NULL, NULL),
(93, 'MUHAMMAD SABRI', NULL, '$2y$12$sokn/LF5Lw9ZmGtnR6xhnuwUrTPagNxPteqbi3eddnCF166mMmAjO', 'siswa', '0119469674', NULL, 1, NULL, '2026-05-16 03:07:25', 0, NULL, NULL),
(94, 'MUH. FATHUR RAHMAN', NULL, '$2y$12$8db0OGcdNEJOmpDTMIwr0.hSSccQZWJ09RCkINLRonf0alKnIAsJa', 'siswa', '0118856220', NULL, 1, NULL, '2026-05-16 03:07:35', 0, NULL, NULL),
(95, 'MUZAKKIR NOVRIANSYAH', NULL, '$2y$12$kYKD.vAtgmpXblrRf2oiq.mWXBpixRnd8C9C2OUFu2FqbTk8MH0WO', 'siswa', '0112024386', NULL, 1, NULL, '2026-05-16 03:07:47', 0, NULL, NULL),
(96, 'NAJWA AWALIYA', NULL, '$2y$12$9VN/rxTaxAX6gRDcuc4k8uaCBLvTSgAmRA8s4K7gNA6inAPCAZIPK', 'siswa', '0121441830', NULL, 1, NULL, '2026-05-16 03:07:55', 0, NULL, NULL),
(97, 'AYATUL HUSNAH', NULL, '$2y$12$ZM2G9TX1BevaEe2j9rbP7eRYItfALSXHab2t3FWlE8dpypFGpxDJa', 'siswa', '0117617170', NULL, 1, NULL, '2026-05-16 03:08:05', 0, NULL, NULL),
(98, 'MIFTAHUL JANNAH', NULL, '$2y$12$HG/XPEQja7tEpNTmv2lbReqaBmexnIVZ.h08YMZ1qShY/O4b91mfy', 'siswa', '0113176218', NULL, 1, NULL, '2026-05-16 03:08:15', 0, NULL, NULL),
(99, 'MUH. SYAWAL USMAN', NULL, '$2y$12$TUszc6qaygpB.MWerNDTOek8RKQJXhFUKD.jWhQzAZmcDaQ8efF9S', 'siswa', '0114769077', NULL, 1, NULL, '2026-05-16 03:08:24', 0, NULL, NULL),
(100, 'MUHAMMAD AZZAM', NULL, '$2y$12$3TICy8BwbTT/eY/mdSkeyevPEAoQRRDiFuGbE369d32quUQHIbXhC', 'siswa', '0121687660', NULL, 1, NULL, '2026-05-16 03:08:33', 0, NULL, NULL),
(101, 'MUHAMMAD FADHIL', NULL, '$2y$12$qgrbpfwtEaLbHw36yzBj6OBWUsNen6jBgJaxuogm.BsBmtahzMdM2', 'siswa', '0121060468', NULL, 1, NULL, '2026-05-16 03:08:48', 0, NULL, NULL),
(102, 'NUR HAFISAH', NULL, '$2y$12$nfbiYzk9CRP05IHxLTIDxud/BcaJV29HKMEs2A/g7KVrvCckQxQpO', 'siswa', '0118113766', NULL, 1, NULL, '2026-05-16 03:08:59', 0, NULL, NULL),
(103, 'NURUL ASYILA', NULL, '$2y$12$Gy3p2oZQDoqLWItr.TMIFelqv5IuQVP5vs/.0yXUsehBo4m1kJcLC', 'siswa', '0115719632', NULL, 1, NULL, '2026-05-16 03:09:09', 0, NULL, NULL),
(104, 'NURUL ASYIFA', NULL, '$2y$12$rmKApJ2u2PAgeKoJN0Tej.CjgImfOvhrL6EWw7sCZVggWg0eCZp3y', 'siswa', '0116454258', NULL, 1, NULL, '2026-05-16 03:09:20', 0, NULL, NULL),
(105, 'HAENUL SULTAN', NULL, '$2y$12$F8dCCqb2AkqUHQHKF1r0weB5TmCASrTeYUY7F5CSuuB0FGutXwWOO', 'siswa', '115282513', NULL, 1, NULL, '2026-05-16 03:09:30', 0, NULL, NULL),
(106, 'IBNU ABDILLAH', NULL, '$2y$12$/fEb6R8TyDaYy48fZQeZ8uQ.fK6KL2FMU9egQa6.C5/3HYyBN1jqO', 'siswa', '0113411212', NULL, 1, NULL, '2026-05-16 03:09:40', 0, NULL, NULL),
(107, 'MUH. FIRMANSYAH ADIL', NULL, '$2y$12$lZa.3TH8gAG5FRkVeuHlCOYk3iGBRor3YB9vyQmG5AsI.DoJhzzRS', 'siswa', '0124745798', NULL, 1, NULL, '2026-05-16 03:09:49', 0, NULL, NULL),
(108, 'SULFAHRI', NULL, '$2y$12$ur2Wn0hp4fcqCbH1XO1bauR80XExO834AQXr3T.cQa.TC0wYdGHKS', 'siswa', '0106847758', NULL, 1, NULL, '2026-05-16 03:09:57', 0, NULL, NULL),
(109, 'ABDULLAH', '', '$2y$12$ujt24hmKazH6w7zuQYor/uW9Ut98gNoroQ1kQekY7ixxHDdD6LBii', 'siswa', '0112654271', NULL, 1, NULL, '2026-05-16 03:14:02', 0, NULL, NULL),
(110, 'AHMAD FAIZUL', NULL, '$2y$12$ZpmrJV5jhHiedV9ItQo1PeMwgp78alPzjR0Crak50D1urZqKyjAz2', 'siswa', '3120251590', NULL, 1, NULL, '2026-05-16 03:14:19', 0, NULL, NULL),
(111, 'AHMAD FAJRUL', NULL, '$2y$12$TKaP8MJwS0tY0O6LCpW/S.gCq9648YLjPg.dabs0BqCrjHWSBzjLq', 'siswa', '0117855491', NULL, 1, NULL, '2026-05-16 03:15:13', 0, NULL, NULL),
(112, 'A. SUBAEDA', '', '$2y$12$K8V4gkC6WScJTZFEkTs3yuWjl9AG62w.nO68d8Zxc08NecPIVtRLK', 'siswa', '0122551343', NULL, 1, NULL, '2026-05-16 03:15:43', 0, NULL, NULL),
(113, 'IBRAHIM', NULL, '$2y$12$ZZcuH7im3ojYBWdL0M7FHey42la/XX0VZY7um2MYyHLiADcInmkhy', 'siswa', '0116463917', NULL, 1, NULL, '2026-05-16 03:19:24', 0, NULL, NULL),
(114, 'KHAIRUL AZZAM AL MUZAMMIL', NULL, '$2y$12$XImUcO77ZVTGLd8FK6j1K.s4LwBvqS3ddTbCwCBoLBAurInqGR8J.', 'siswa', '0122792882', NULL, 1, NULL, '2026-05-16 03:19:50', 0, NULL, NULL),
(115, 'SULTAN AKBAR', NULL, '$2y$12$sp/oZUABC/J.tDETg21qh.xA4IcxWnwWvwaZISAKPsGRsKw.TBmTC', 'siswa', '0112541606', NULL, 1, NULL, '2026-05-16 03:20:09', 0, NULL, NULL),
(116, 'NUR ASHANTI. D', NULL, '$2y$12$akdl9etRNF5N738PpATbTuN/1Lq40WZDf0A7ZYCqEWq7vzmbeSccO', 'siswa', '115041626', NULL, 1, NULL, '2026-05-16 03:20:25', 0, NULL, NULL),
(117, 'SITTI RAHMA', NULL, '$2y$12$JtEnKgHMLlRO6ekmX1D4sOrxtzpDQhGIHMBwTlXKTY0oq6p3rkb6e', 'siswa', '103014958', NULL, 1, NULL, '2026-05-16 03:22:30', 0, NULL, NULL),
(118, 'AHMAD MAULID', NULL, '$2y$12$lmk25jbqYwXzsZ9eJ569VO.j67VCRY2WLZPAsmxuDBxFp1wL9bDZe', 'siswa', '123016471', NULL, 1, NULL, '2026-05-16 03:22:46', 0, NULL, NULL),
(119, 'DIN SYAKIR ARDIN', NULL, '$2y$12$IROTqXKuymkCMv.mrBeHiOfV3yBjw9juL4uzKM2COt/eJs/Ts9gFe', 'siswa', '122231109', NULL, 1, NULL, '2026-05-16 03:23:01', 0, NULL, NULL),
(120, 'ILMAN UMAR', NULL, '$2y$12$V87ZtoPY9GQbteWdoqKKH.knE6kc0vx5JqS7s2UXB0ngswIMyboOO', 'siswa', '0125155612', NULL, 1, NULL, '2026-05-16 03:24:40', 0, NULL, NULL),
(121, 'MUH. AHSAN', NULL, '$2y$12$Z4/1pVC8Xw4F8n5U/a/9putw2J77iM1YBy9/PiFxA1OQKQHZr8rKq', 'siswa', '112228080', NULL, 1, NULL, '2026-05-16 03:24:53', 0, NULL, NULL),
(122, 'NUR ALIQAH', NULL, '$2y$12$2sdyvQKdK2ILkFpsFuISp.DKmlfxYcgfA5Tisp4jmGNbzblYOZ/7m', 'siswa', '0123132662', NULL, 1, NULL, '2026-05-16 03:25:08', 0, NULL, NULL),
(123, 'NUR AQILAH', NULL, '$2y$12$JdqUEOsx/Km0mn62XDC2duCC1mt5qiRyIqJ4hCO3qM5BEKhQ3dyiy', 'siswa', '0129578657', NULL, 1, NULL, '2026-05-16 03:25:51', 0, NULL, NULL),
(124, 'NUR AZIZAH', NULL, '$2y$12$MKvnRBsdI4EAW0xODmvfXep1HS4mmQXTa2Odl6ccA0/zf3g9Ucgt2', 'siswa', '121799137', NULL, 1, NULL, '2026-05-16 03:26:05', 0, NULL, NULL),
(125, 'FAHMI FATIMAH AZ ZAHRAH', NULL, '$2y$12$5zfyKxF4XbmJQD.5FjDzNOlPv1L2JzbBqUU/MoBWOrKJLtilInl4.', 'siswa', '0128454080', NULL, 1, NULL, '2026-05-16 03:26:23', 0, NULL, NULL),
(126, 'KHAIRIYYAH', NULL, '$2y$12$2/WqyYUi.FbvtqaaQ4ldMeLjP/6clOyw0Ws7ihHHhGtVPwUDaz34i', 'siswa', '0128284552', NULL, 1, NULL, '2026-05-16 03:26:43', 0, NULL, NULL),
(127, 'NURUL SYAFIRA', NULL, '$2y$12$aoZ1zYPHO3SgfkBg/aOjUuzXgPz4A2sghF9hPOrHHLoGqgPspKBdu', 'siswa', '0122030662', NULL, 1, NULL, '2026-05-16 03:26:58', 0, NULL, NULL),
(128, 'MUH.ANGGA NUR CAHYADI', NULL, '$2y$12$zpWYk7CjlumHE/2x2CuUHedYRX145yvn80sflTlSRUkxQmoyQnaxO', 'siswa', '0118120336', NULL, 1, NULL, '2026-05-16 03:27:15', 0, NULL, NULL),
(129, 'MUH.RAJJAB', NULL, '$2y$12$f3BYUPF9fzoLMOTmXjI5peEfr48LeUpqBRQYzlze3UOZB0ShFjIs.', 'siswa', '3127960266', NULL, 1, NULL, '2026-05-16 03:27:31', 0, NULL, NULL),
(130, 'AGUNG MAULANA ARAS', NULL, '$2y$12$PV594SIRfG4Jh3YIblRkSehjHnFrhZ3LSrHP5RpgH97TCTJEAvsae', 'siswa', '0119582400', NULL, 1, NULL, '2026-05-16 07:10:02', 0, NULL, NULL),
(131, 'WULAN APRI RAHMAYANI', NULL, '$2y$12$5eY6idqhWOpzF/1RzXFz.etMAiwrJ69TCyQtXMT9RYSL5kEKiMF2y', 'siswa', '0126971257', NULL, 1, NULL, '2026-05-16 07:10:12', 0, NULL, NULL),
(132, 'AMIROH FATHINAH RAIS', NULL, '$2y$12$PCrrvzMZ60LTlbiEBKqKFeOGIe75hk86ts8h9SGPHB6bQLXmy3PQC', 'siswa', '0123187863', NULL, 1, NULL, '2026-05-16 07:12:20', 0, NULL, NULL),
(133, 'ATIKAH AMALIYAH HALKAM', NULL, '$2y$12$ImP6153M1mWHMUpRXJDaseR3O4XdtaWhzJWo2aG.EnH.zkBnU7dL6', 'siswa', '3118576962', NULL, 1, NULL, '2026-05-16 07:12:31', 0, NULL, NULL),
(134, 'NUR AZIMAH', NULL, '$2y$12$SJLrR7T6OMrnBqrfQsFkCeZV7j4JYkKAp4wAnA/CGgkRFjWaDtESO', 'siswa', '127288632', NULL, 1, NULL, '2026-05-16 07:12:42', 0, NULL, NULL),
(135, 'MUHAMMAD AZHAR', NULL, '$2y$12$kfxFomO5Jm4FqlxHvjgYueJS9QBDP7fJR1dh0TGRO5kPKHAr.f6Jm', 'siswa', '0126548067', NULL, 1, NULL, '2026-05-16 07:12:53', 0, NULL, NULL),
(136, 'MUH.TAQWA', NULL, '$2y$12$tLAY9EcTa1LHXSpHCQsbk.41wdDL0IEt3OgopuBCT3HBmqHnaB7vK', 'siswa', '0117724518', NULL, 1, NULL, '2026-05-16 07:13:11', 0, NULL, NULL),
(137, 'MUH.AL FAIZ', NULL, '$2y$12$P4.N9VZZhlpZeclmIlgU9emOtcLOByE.dzi8d.uKDdJh0fuFY/Ay.', 'siswa', '0122864557', NULL, 1, NULL, '2026-05-16 07:13:32', 0, NULL, NULL),
(138, 'MUHAMMAD FAUZAN', NULL, '$2y$12$om4WljVWV6fqCjBgGU3FAeRipUwDm4Fu0350DK.SwkJZDrUNWqWTi', 'siswa', '0126768059', NULL, 1, NULL, '2026-05-16 07:15:17', 0, NULL, NULL),
(139, 'MUH FAHRY NURDIN', NULL, '$2y$12$xokFQHts6a6yIr6VZB0OJuk0A7249EXwcctzJ8xuY8/3g71hIj7pC', 'siswa', '0115235862', NULL, 1, NULL, '2026-05-16 07:15:25', 0, NULL, NULL),
(140, 'M. NURLAN', NULL, '$2y$12$HRDxkU.zqCrAHTO014qjbOqYVHK6Wxin7KsErWrJ0nfDSOylcjQFa', 'siswa', '3119634560', NULL, 1, NULL, '2026-05-16 07:15:37', 0, NULL, NULL),
(141, 'ABRAHAM SYAH', '', '$2y$12$GxMWL.wa6yV/mLFA4zLkzOpdvnJUxvF3C8ECmKiKGIv6/jJus5Vyi', 'siswa', '3129734036', NULL, 1, NULL, '2026-05-16 07:15:52', 0, NULL, NULL),
(142, 'MUH. YASIN', NULL, '$2y$12$O4K/ZRZFrcAWpLReSDLjOu1NmspmtXPPK9kqMo4XRQQtUXn5sVITa', 'siswa', '0112870769', NULL, 1, NULL, '2026-05-16 07:16:01', 0, NULL, NULL),
(143, 'MUH. KHAIRUL HADI RUSLI', NULL, '$2y$12$eAnEHsu8YpG6xOMRBJpA1.JBp87/LxCP59I3gJOKCO1w/H9hehZQu', 'siswa', '0116605710', NULL, 1, NULL, '2026-05-16 07:16:16', 0, NULL, NULL),
(144, 'MUH.FIKRI AL FIROSYID', NULL, '$2y$12$cbdPXs.UDWr4JIqn1CACY.232l6w3jvqPrXcdboq7QgcFm1U6gnd2', 'siswa', '0124597588', NULL, 1, NULL, '2026-05-16 07:16:25', 0, NULL, NULL),
(145, 'ABYAN AMMAR', '', '$2y$12$v8Z9.HeQX8R9BFqrie8epOH6YSoQx8Al2VS.6maQoA/h0RzhMyv6G', 'siswa', '0121144179', NULL, 1, NULL, '2026-05-16 07:16:35', 0, NULL, NULL),
(146, 'AMMAR MUSTAJAB. S', NULL, '$2y$12$UXjhvNhglVhtdl4IgDB5w.rPjwGgbt6chzIKRfMKq6nRMKxt8C9za', 'siswa', '3127525068', NULL, 1, NULL, '2026-05-16 07:16:47', 0, NULL, NULL),
(147, 'GUSTINA RAMADHANI', NULL, '$2y$12$YE1w8lSGZL2TLrcri39fjOiFzacP6MgxF9nnzzW0E3/AxIFZgHKDm', 'siswa', '3112922070', NULL, 1, NULL, '2026-05-16 07:16:57', 0, NULL, NULL),
(148, 'SYAHRATUL AINI', NULL, '$2y$12$aZnD/zA12QSO6IdZdwDFoOiOftdtKy2kJPc8DRQZYKQFEE1tr9oUC', 'siswa', '0106598834', NULL, 1, NULL, '2026-05-16 07:19:53', 0, NULL, NULL),
(149, 'FADHIL SAHLY', NULL, '$2y$12$3kxHKDHxSTUJFpcyUE0iyOghVErWV.xxj/R7zw7DkNR3GZD4awu2q', 'siswa', '0104337641', NULL, 1, NULL, '2026-05-16 07:20:02', 0, NULL, NULL),
(150, 'ANDI IRWANSYAH FIRLY', NULL, '$2y$12$ryNokPViYQlLWpabWk7vcu7BG2YOvvz3Or.r.cBP8uJfo7b64BidK', 'siswa', '0101994781', NULL, 1, NULL, '2026-05-16 07:20:11', 0, NULL, NULL),
(151, 'SYAHRIL', NULL, '$2y$12$s/UlmOKMoZDKPDMq0jAepOnwWBselHQQDU4jMRItJbQpkOfVF44ma', 'siswa', '0118980358', NULL, 1, NULL, '2026-05-16 07:20:20', 0, NULL, NULL),
(152, 'RESKI SRIWIJAYA', NULL, '$2y$12$WXCyhsgrWtvV5oGMJio31.CRFPovy532AeeFwr5DZkju5JZmsJdom', 'siswa', '0115336064', NULL, 1, NULL, '2026-05-16 07:20:30', 0, NULL, NULL),
(153, 'DESY ISNAENI', NULL, '$2y$12$rA3N2W7ppvF23Sh3EJHfiemmUdkJ4qfUmQhu241CYTpulZ.i1xS0a', 'siswa', '0106510881', NULL, 1, NULL, '2026-05-16 07:20:39', 0, NULL, NULL),
(154, 'IZZAH KHARIAH', NULL, '$2y$12$OmMO7xQxmeQp5FrllT6ZVuYEhCeLa4sEckp7oIFAzXvynKSZzBhuK', 'siswa', '0114847328', NULL, 1, NULL, '2026-05-16 07:20:50', 0, NULL, NULL),
(155, 'NUR SALSABILA', NULL, '$2y$12$oSmGSXy6lI1osf0oZn6YeOiBwT0a8RsQnLG4E./Cj4jUAvY1FLJ/O', 'siswa', '0117383336', NULL, 1, NULL, '2026-05-16 07:21:03', 0, NULL, NULL),
(156, 'AYATUL HUSNA', NULL, '$2y$12$0epgTkgMGanKy9l30LA.2.srFBZDRE9/6vswbBr8OUz0JLTH3v8na', 'siswa', '0116767798', NULL, 1, NULL, '2026-05-16 07:21:16', 0, NULL, NULL),
(157, 'M. RISWANDI. H', NULL, '$2y$12$Ia79Ia.cQXXFD5F3XlghleEWAfK9XW0qjjgbEp9OE8VBT7u08gzm2', 'siswa', '0101602566', NULL, 1, NULL, '2026-05-16 07:21:25', 0, NULL, NULL),
(158, 'AHMAD RUSDI RAMADHAN', NULL, '$2y$12$ezJtt8/e/o0pusO2WIj1.enzyAZxtgPRuw277S8c9zAav22fCGqgC', 'siswa', '0116428831', NULL, 1, NULL, '2026-05-16 07:21:35', 0, NULL, NULL),
(159, 'DIAN NOVITA', NULL, '$2y$12$q4To4iGkAwj3YExnvKkFz.xaUfGYBRQPWbnQOMuNfjPCjLIAOnHrS', 'siswa', '0103128891', NULL, 1, NULL, '2026-05-16 07:21:43', 0, NULL, NULL),
(160, 'NURUL AMIRAH', NULL, '$2y$12$bdOq2vxIH0BvT/R8COZsjeONmGxk4BpxIO4vaDFhT5oN.6z1ukJUO', 'siswa', '0113220864', NULL, 1, NULL, '2026-05-16 07:21:53', 0, NULL, NULL),
(161, 'ANGGA SYAWAL SYAPUTRA', NULL, '$2y$12$GLfFpKUfJZ.5bPZTay7pAu.cn6pidKGOLYns3lnFQ2N71RBP4iiti', 'siswa', '0102620432', NULL, 1, NULL, '2026-05-16 07:22:02', 0, NULL, NULL),
(162, 'NUR HIJIR ISMA', NULL, '$2y$12$rD7qgtVvawsUxJq5K.7l2uz8c4FYaWYo0zq6.7xVdMnkS33Oz0ptK', 'siswa', '0104185970', NULL, 1, NULL, '2026-05-16 07:22:17', 0, NULL, NULL),
(163, 'DIAN NURAMAL PAU', NULL, '$2y$12$xlBKEHsI1eZICRh0a4KxpOx6LxA52rlyypJy6PKNGzaiyYbr31zf6', 'siswa', '0117925585', NULL, 1, NULL, '2026-05-16 07:22:28', 0, NULL, NULL),
(164, 'NUR MALASARI', NULL, '$2y$12$QxM/ljsghwtLdwXs6wOYG.b79Mb4/lupixQk4XJ32l7otJc733zQ2', 'siswa', '0118602459', NULL, 1, NULL, '2026-05-16 07:22:37', 0, NULL, NULL),
(165, 'AHMAD SYAWIR', NULL, '$2y$12$xhwHLhazfYAGntN/zpgnV.DQYlkbfKpMFYfDaBpaqFhARrexnMtBC', 'siswa', '0101668507', NULL, 1, NULL, '2026-05-16 07:22:56', 0, NULL, NULL),
(166, 'WINDA SARI', NULL, '$2y$12$yzBhWMdxNsoUaIJtoy4O9ehuRD.xdZIJS7t6bc8ayb1iPEZi3FhXG', 'siswa', '101129398', NULL, 1, NULL, '2026-05-16 07:23:07', 0, NULL, NULL),
(167, 'MUH. AKBAR', NULL, '$2y$12$UDN4HUKQR17a4yHQmxP98uCndDYV6MagNTrgKrisISp3M9PT7zcdK', 'siswa', '0112479002', NULL, 1, NULL, '2026-05-16 07:23:16', 0, NULL, NULL),
(168, 'MUH. AL-IMRAN', NULL, '$2y$12$cdMCAVrAIdedx8xWyvtm7ePy311GaQyVGa7oFa1423l1.9Vob4aKS', 'siswa', '0122093886', NULL, 1, NULL, '2026-05-16 07:23:26', 0, NULL, NULL),
(169, 'MUH. ADI', NULL, '$2y$12$c5eGeUdcFrvjDhmP8Vo3juRb9qkcQdMWNe3mAwbC/TOzvr06w6VWC', 'siswa', '0106925553', NULL, 1, NULL, '2026-05-16 07:23:36', 0, NULL, NULL),
(170, 'MUH. IKHSAN KASMIN', NULL, '$2y$12$RAuLLwS66bb5sJj37tEUeeYAwz8n9/JqZ172dyLUzlYAqEQKTd656', 'siswa', '0109233716', NULL, 1, NULL, '2026-05-16 07:23:49', 0, NULL, NULL),
(171, 'RESKI RAMADHANI', NULL, '$2y$12$ogaB1JCldEtliNfAkmmtPuml4GjUI6ILmdF4rg4c.AioHQnR2vqb.', 'siswa', '0104436713', NULL, 1, NULL, '2026-05-16 07:23:59', 0, NULL, NULL),
(172, 'JANNATUL HUSNA', NULL, '$2y$12$ROnMK9MOjleWs1A5hsAGK.30Pz0b.MVrMHmT3JshPEaNz6rj4hhyu', 'siswa', '0115794947', NULL, 1, NULL, '2026-05-16 07:24:08', 0, NULL, NULL),
(173, 'FERDI', NULL, '$2y$12$D.ooH/1txYmSyTr6hvTO8.8wLE55UihTaVTk7i7ZyHJUnS0jF7.pO', 'siswa', '0094547386', NULL, 1, NULL, '2026-05-16 07:26:59', 0, NULL, NULL),
(174, 'AINI AMIRA', NULL, '$2y$12$m/gNQRHeHwWD8Z0csPq3AOrqdIeqWIxxCWOSIvtxrc2/S/JhU.7mO', 'siswa', '0118243821', NULL, 1, NULL, '2026-05-16 07:27:08', 0, NULL, NULL),
(175, 'FERI', NULL, '$2y$12$addMtU0o7AJCPFvTOVPoyOnt4Moe2wLZfCrsT188V7wGgvqnKVoxK', 'siswa', '0108336550', NULL, 1, NULL, '2026-05-16 07:27:19', 0, NULL, NULL),
(176, 'NAJWA', NULL, '$2y$12$UCu94mfBfaH4yZCdhwRya.StA2spNMJ4t83I1VK9brD5VdX26kpAe', 'siswa', '0115844897', NULL, 1, NULL, '2026-05-16 07:27:30', 0, NULL, NULL),
(177, 'MUFIDAH ULFA RUSLI', NULL, '$2y$12$NWANbpjSssFjptnjNPcJ4eT5bcNpC.6AhbJSQs.D8oUruCSaCJ4uC', 'siswa', '0112309147', NULL, 1, NULL, '2026-05-16 07:27:42', 0, NULL, NULL),
(178, 'ESA RISQIAN', NULL, '$2y$12$15AZtDs0HQbRpmuLV2OtDOmco5hMnCY1QkJKgo/fnOEvxcViffMgO', 'siswa', '0106756493', NULL, 1, NULL, '2026-05-16 07:27:53', 0, NULL, NULL),
(179, 'MUH. IQBAL', NULL, '$2y$12$MI6AmQy/5thwaAxag360TOppVcYOdgrsjpfRJiw51vT8WzGAdGwym', 'siswa', '0113883094', NULL, 1, NULL, '2026-05-16 07:28:03', 0, NULL, NULL),
(180, 'INDY HAWIRDAH', NULL, '$2y$12$TvDTh8YBcVTYoFLE5d1CReVKfIKqB1YmHkNwS0HREWbH/lrjqk6mK', 'siswa', '0116768382', NULL, 1, NULL, '2026-05-16 07:28:12', 0, NULL, NULL),
(181, 'AL MUHTADHI BILLAH FH', NULL, '$2y$12$KVnlGmY1G9EikZcIHhYp0ecT8R6QCxF1qndhlZQPfh7vDvs/pghyO', 'siswa', '0108998139', NULL, 1, NULL, '2026-05-16 07:28:23', 0, NULL, NULL),
(182, 'HASYIM SYAMSUDDIN', NULL, '$2y$12$29eOQc5p/IazEC0qOlyZDOu0qzodUneOQfTMPRRqEZjyDjCczKHye', 'siswa', '0109070329', NULL, 1, NULL, '2026-05-16 07:28:31', 0, NULL, NULL),
(183, 'BISYARAH MAJDINA', NULL, '$2y$12$OK.Yrsqdj6L8qNHYRuO6selIJutS7oGlgp5YPb1OlqX6aW83RrWaa', 'siswa', '0118376288', NULL, 1, NULL, '2026-05-16 07:28:49', 0, NULL, NULL),
(184, 'NUR ALIFAH RAHMADANI', NULL, '$2y$12$Oop1yuyXvIrwCehQk/jbQuKfQHhu3AvjYYw0Q/Ptzg44AOhXhNUiG', 'siswa', '0103273755', NULL, 1, NULL, '2026-05-16 07:28:58', 0, NULL, NULL),
(185, 'SITTI NURAISYAH', NULL, '$2y$12$RmX8glNc9i5dowhyudO91.IPn2U8U7vY.6rVBM2RlI5TVFb1g.1m2', 'siswa', '0105000706', NULL, 1, NULL, '2026-05-16 07:29:06', 0, NULL, NULL),
(186, 'RISKA AWALIA', NULL, '$2y$12$yFDytGxFlHqevS8KaDER8er0IAozCieOgz7/X/pC3m9FlGxeNdHYW', 'siswa', '0106166062', NULL, 1, NULL, '2026-05-16 07:29:14', 0, NULL, NULL),
(187, 'HAYATUL HUSNA', NULL, '$2y$12$Bb.7lCST5jz8.xa86r5Xs.7F5RMJFRhWqLslyVnp0W/yBhvaHP142', 'siswa', '0115596382', NULL, 1, NULL, '2026-05-16 07:29:23', 0, NULL, NULL),
(188, 'INAYATULLAH', NULL, '$2y$12$i8fEtAJgDnGSnJFqNw/j/.ZoVZjhlMo22q9ZQi.d4rMFjX7MxnHR.', 'siswa', '0117591465', NULL, 1, NULL, '2026-05-16 07:29:31', 0, NULL, NULL),
(189, 'ASIFAH SRI UTARI. K', NULL, '$2y$12$/ZbkZTvCEi3UMBQKkwRm1.x7muw39.3101cgMm6nS/Lw73.uKL4FC', 'siswa', '0113953644', NULL, 1, NULL, '2026-05-16 07:29:40', 0, NULL, NULL),
(190, 'MUH. SYAWIR', NULL, '$2y$12$HY2WL4IzagdzDnwmJni38.nNHishyJWI9Go84drf3Gf32JJHtCEam', 'siswa', '0109249058', NULL, 1, NULL, '2026-05-16 07:29:51', 0, NULL, NULL),
(191, 'ZULFIKRAN', NULL, '$2y$12$CYrb3PW69NB3W2MNhmEOG.Q207f5AHX2dGziuj60/mlVP1ypq3dFu', 'siswa', '0103706472', NULL, 1, NULL, '2026-05-16 07:29:58', 0, NULL, NULL),
(192, 'A. MUH. MUFLI SYUKUR', '', '$2y$12$ihpC3Go1O9H3jdiwyddbNuSNpHG0FAifjW5riKizcbLLynJrGwZSC', 'siswa', '0119619660', NULL, 1, NULL, '2026-05-16 07:30:08', 0, NULL, NULL),
(193, 'M. RESKY ANUGRAH', NULL, '$2y$12$hHNz8nkF1cYRN73TYzUsAeiI0YFIDopMUDzR9JncF3Rm2K45xNyX.', 'siswa', '0114930021', NULL, 1, NULL, '2026-05-16 07:30:17', 0, NULL, NULL),
(194, 'MUH NUR', NULL, '$2y$12$zhsenPqte4N6qnKFW1j52udCTvKQ2HSrkbV6JWpD1cYrVfUNj0eES', 'siswa', '0118904825', NULL, 1, NULL, '2026-05-16 07:30:26', 0, NULL, NULL),
(195, 'ASRI RAHAYU', NULL, '$2y$12$btcB0nG6RHq/oxNUEbgwXu4QTPZPXGrv/UmVpr.ABMjMt06tWxQv.', 'siswa', '103453369', NULL, 1, NULL, '2026-05-16 07:30:35', 0, NULL, NULL),
(196, 'ANDI MUAMMAR RIYAL', NULL, '$2y$12$LoU29Q1x7BwsCmvdRaOf1.kv3F5PYip.5BlYigt3aJfLSn0G0ALcS', 'siswa', '0117869988', NULL, 1, NULL, '2026-05-16 07:30:43', 0, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_badge`
--

CREATE TABLE `user_badge` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `badge_slug` varchar(60) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `earned_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_badge`
--

INSERT INTO `user_badge` (`id`, `user_id`, `badge_slug`, `earned_at`) VALUES
(1, 8, 'pemula_bersemangat', '2026-05-15 14:10:37'),
(2, 45, 'pemula_bersemangat', '2026-05-15 23:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `user_xp`
--

CREATE TABLE `user_xp` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `total_xp` int NOT NULL DEFAULT '0',
  `level` int NOT NULL DEFAULT '1',
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user_xp`
--

INSERT INTO `user_xp` (`id`, `user_id`, `total_xp`, `level`, `updated_at`) VALUES
(1, 8, 65, 2, '2026-05-15 14:10:45'),
(6, 45, 30, 1, '2026-05-16 02:31:42'),
(8, 47, 15, 1, '2026-05-15 23:52:02'),
(9, 31, 20, 1, '2026-05-16 02:34:08');

-- --------------------------------------------------------

--
-- Table structure for table `xp_log`
--

CREATE TABLE `xp_log` (
  `id` int NOT NULL,
  `user_id` int NOT NULL,
  `jumlah` int NOT NULL,
  `keterangan` varchar(120) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `xp_log`
--

INSERT INTO `xp_log` (`id`, `user_id`, `jumlah`, `keterangan`, `created_at`) VALUES
(1, 8, 10, 'Menyelesaikan materi bacaan', '2026-05-15 14:10:37'),
(2, 8, 15, 'Menyelesaikan kuis', '2026-05-15 14:10:39'),
(3, 8, 15, 'Menyelesaikan kuis', '2026-05-15 14:10:42'),
(4, 8, 10, 'Menghadiri Live Class', '2026-05-15 14:10:44'),
(5, 8, 15, 'Menyelesaikan kuis', '2026-05-15 14:10:45'),
(6, 45, 15, 'Menyelesaikan kuis', '2026-05-15 23:27:22'),
(7, 45, 10, 'Menyelesaikan materi bacaan', '2026-05-15 23:34:08'),
(8, 47, 15, 'Menyelesaikan kuis', '2026-05-15 23:52:02'),
(9, 31, 15, 'Menyelesaikan kuis', '2026-05-15 23:53:30'),
(10, 45, 5, 'Posting di forum diskusi', '2026-05-16 02:31:42'),
(11, 31, 5, 'Posting di forum diskusi', '2026-05-16 02:34:08');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forum_reaction`
--
ALTER TABLE `forum_reaction`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_reaction` (`reply_id`,`user_id`),
  ADD KEY `fk_reaction_user` (`user_id`);

--
-- Indexes for table `forum_reply`
--
ALTER TABLE `forum_reply`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_reply_thread` (`thread_id`),
  ADD KEY `fk_reply_user` (`user_id`),
  ADD KEY `fk_reply_parent` (`parent_id`);

--
-- Indexes for table `forum_thread`
--
ALTER TABLE `forum_thread`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_forum_jm` (`jadwal_mengajar_id`),
  ADD KEY `fk_forum_user` (`dibuat_oleh`);

--
-- Indexes for table `jadwal_mengajar`
--
ALTER TABLE `jadwal_mengajar`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_jm_ta` (`tahun_ajaran_id`),
  ADD KEY `fk_jm_kelas` (`kelas_id`),
  ADD KEY `fk_jm_mapel` (`mapel_id`),
  ADD KEY `fk_jm_guru` (`guru_id`);

--
-- Indexes for table `kelas`
--
ALTER TABLE `kelas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_kelas_ta` (`tahun_ajaran_id`);

--
-- Indexes for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_ks_kelas` (`kelas_id`),
  ADD KEY `fk_ks_user` (`user_id`);

--
-- Indexes for table `kelompok_mapel`
--
ALTER TABLE `kelompok_mapel`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_hasil` (`item_id`,`siswa_id`),
  ADD KEY `fk_hasil_siswa` (`siswa_id`);

--
-- Indexes for table `kuis_jawaban`
--
ALTER TABLE `kuis_jawaban`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_jawaban_item_siswa_soal` (`item_id`,`siswa_id`,`soal_id`);

--
-- Indexes for table `kuis_opsi`
--
ALTER TABLE `kuis_opsi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_opsi_soal` (`soal_id`);

--
-- Indexes for table `kuis_soal`
--
ALTER TABLE `kuis_soal`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_soal_item` (`item_id`);

--
-- Indexes for table `mapel`
--
ALTER TABLE `mapel`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_mapel_kelompok` (`kelompok_mapel_id`);

--
-- Indexes for table `modul`
--
ALTER TABLE `modul`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_modul_jm` (`jadwal_mengajar_id`);

--
-- Indexes for table `modul_item`
--
ALTER TABLE `modul_item`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_item_modul` (`modul_id`);

--
-- Indexes for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_tugas_siswa` (`tugas_id`,`siswa_id`),
  ADD KEY `idx_pt_tugas` (`tugas_id`),
  ADD KEY `idx_pt_siswa` (`siswa_id`);

--
-- Indexes for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_pengumuman_user` (`created_by`);

--
-- Indexes for table `presensi`
--
ALTER TABLE `presensi`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_presensi_jm` (`jadwal_mengajar_id`);

--
-- Indexes for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_presensi_siswa` (`presensi_id`,`siswa_id`),
  ADD KEY `fk_ps_siswa` (`siswa_id`);

--
-- Indexes for table `progress_materi`
--
ALTER TABLE `progress_materi`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_progress` (`item_id`,`siswa_id`),
  ADD KEY `fk_prog_siswa` (`siswa_id`);

--
-- Indexes for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tugas`
--
ALTER TABLE `tugas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_tugas_jadwal` (`jadwal_mengajar_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_badge`
--
ALTER TABLE `user_badge`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_user_badge` (`user_id`,`badge_slug`),
  ADD KEY `idx_user_badge_user` (`user_id`);

--
-- Indexes for table `user_xp`
--
ALTER TABLE `user_xp`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `idx_user_xp_user` (`user_id`),
  ADD KEY `idx_user_xp_total` (`total_xp`);

--
-- Indexes for table `xp_log`
--
ALTER TABLE `xp_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_xp_log_user` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forum_reaction`
--
ALTER TABLE `forum_reaction`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `forum_reply`
--
ALTER TABLE `forum_reply`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `forum_thread`
--
ALTER TABLE `forum_thread`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `jadwal_mengajar`
--
ALTER TABLE `jadwal_mengajar`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `kelas`
--
ALTER TABLE `kelas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=195;

--
-- AUTO_INCREMENT for table `kelompok_mapel`
--
ALTER TABLE `kelompok_mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `kuis_jawaban`
--
ALTER TABLE `kuis_jawaban`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=36;

--
-- AUTO_INCREMENT for table `kuis_opsi`
--
ALTER TABLE `kuis_opsi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT for table `kuis_soal`
--
ALTER TABLE `kuis_soal`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `mapel`
--
ALTER TABLE `mapel`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT for table `modul`
--
ALTER TABLE `modul`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `modul_item`
--
ALTER TABLE `modul_item`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `notifikasi`
--
ALTER TABLE `notifikasi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=855;

--
-- AUTO_INCREMENT for table `pengumpulan_tugas`
--
ALTER TABLE `pengumpulan_tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `pengumuman`
--
ALTER TABLE `pengumuman`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `presensi`
--
ALTER TABLE `presensi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `progress_materi`
--
ALTER TABLE `progress_materi`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `tahun_ajaran`
--
ALTER TABLE `tahun_ajaran`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tugas`
--
ALTER TABLE `tugas`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=198;

--
-- AUTO_INCREMENT for table `user_badge`
--
ALTER TABLE `user_badge`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `user_xp`
--
ALTER TABLE `user_xp`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `xp_log`
--
ALTER TABLE `xp_log`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `forum_reaction`
--
ALTER TABLE `forum_reaction`
  ADD CONSTRAINT `fk_reaction_reply` FOREIGN KEY (`reply_id`) REFERENCES `forum_reply` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reaction_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_reply`
--
ALTER TABLE `forum_reply`
  ADD CONSTRAINT `fk_reply_parent` FOREIGN KEY (`parent_id`) REFERENCES `forum_reply` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_thread` FOREIGN KEY (`thread_id`) REFERENCES `forum_thread` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `forum_thread`
--
ALTER TABLE `forum_thread`
  ADD CONSTRAINT `fk_forum_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_forum_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `jadwal_mengajar`
--
ALTER TABLE `jadwal_mengajar`
  ADD CONSTRAINT `fk_jm_guru` FOREIGN KEY (`guru_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jm_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jm_mapel` FOREIGN KEY (`mapel_id`) REFERENCES `mapel` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_jm_ta` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas`
--
ALTER TABLE `kelas`
  ADD CONSTRAINT `fk_kelas_ta` FOREIGN KEY (`tahun_ajaran_id`) REFERENCES `tahun_ajaran` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kelas_siswa`
--
ALTER TABLE `kelas_siswa`
  ADD CONSTRAINT `fk_ks_kelas` FOREIGN KEY (`kelas_id`) REFERENCES `kelas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ks_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kuis_hasil`
--
ALTER TABLE `kuis_hasil`
  ADD CONSTRAINT `fk_hasil_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_hasil_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kuis_opsi`
--
ALTER TABLE `kuis_opsi`
  ADD CONSTRAINT `fk_opsi_soal` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `kuis_soal`
--
ALTER TABLE `kuis_soal`
  ADD CONSTRAINT `fk_soal_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `mapel`
--
ALTER TABLE `mapel`
  ADD CONSTRAINT `fk_mapel_kelompok` FOREIGN KEY (`kelompok_mapel_id`) REFERENCES `kelompok_mapel` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modul`
--
ALTER TABLE `modul`
  ADD CONSTRAINT `fk_modul_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `modul_item`
--
ALTER TABLE `modul_item`
  ADD CONSTRAINT `fk_item_modul` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `notifikasi`
--
ALTER TABLE `notifikasi`
  ADD CONSTRAINT `notifikasi_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pengumuman`
--
ALTER TABLE `pengumuman`
  ADD CONSTRAINT `fk_pengumuman_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensi`
--
ALTER TABLE `presensi`
  ADD CONSTRAINT `fk_presensi_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `presensi_siswa`
--
ALTER TABLE `presensi_siswa`
  ADD CONSTRAINT `fk_ps_presensi` FOREIGN KEY (`presensi_id`) REFERENCES `presensi` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_ps_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `progress_materi`
--
ALTER TABLE `progress_materi`
  ADD CONSTRAINT `fk_prog_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_prog_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
