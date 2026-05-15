<?php
require_once __DIR__ . '/config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Live Class
$conn->query("CREATE TABLE IF NOT EXISTS `live_class` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kelas_id` int(11) NOT NULL,
  `mapel_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `waktu_mulai` datetime NOT NULL,
  `link_vicon` varchar(255) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 2. Kuis Soal V2 (mendukung essay & PG)
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_soal_v2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuis_id` int(11) NOT NULL,
  `tipe` enum('pg','essay') NOT NULL DEFAULT 'pg',
  `pertanyaan` text NOT NULL,
  `poin_maksimal` int(11) DEFAULT 10,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 3. Kuis Opsi V2 (opsi jawaban PG)
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_opsi_v2` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soal_id` int(11) NOT NULL,
  `teks_opsi` text NOT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_opsi_soal_v2` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal_v2` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 4. Kuis Jawaban Siswa
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_jawaban_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuis_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `soal_id` int(11) NOT NULL,
  `opsi_id` int(11) DEFAULT NULL,
  `jawaban_teks` text DEFAULT NULL,
  `poin_didapat` double DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_jawaban_siswa` (`siswa_id`, `soal_id`),
  CONSTRAINT `fk_jawaban_soal` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal_v2` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 5. Kuis Nilai (Skor Total Siswa)
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_nilai` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `kuis_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `total_nilai` double DEFAULT 0,
  `waktu_submit` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_nilai_siswa` (`kuis_id`, `siswa_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Tabel fitur Live Class dan Kuis (PG & Essay) berhasil dibuat!";
?>
