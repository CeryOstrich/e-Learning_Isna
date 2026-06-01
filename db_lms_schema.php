<?php
require_once __DIR__ . '/config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Modul (Bab)
$conn->query("CREATE TABLE IF NOT EXISTS `modul` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_mengajar_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_modul_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 2. Item Modul (Polymorphic: bisa 'materi' atau 'kuis')
$conn->query("CREATE TABLE IF NOT EXISTS `modul_item` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `modul_id` int(11) NOT NULL,
  `tipe` enum('materi','kuis') NOT NULL,
  `judul` varchar(255) NOT NULL,
  `isi_teks` longtext DEFAULT NULL,
  `file_path` varchar(255) DEFAULT NULL,
  `durasi_menit` int(11) DEFAULT NULL,
  `urutan` int(11) DEFAULT 0,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_item_modul` FOREIGN KEY (`modul_id`) REFERENCES `modul` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 3. Kuis Soal (Nempel ke modul_item tipe 'kuis')
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_soal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `pertanyaan` text NOT NULL,
  `urutan` int(11) DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_soal_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 4. Kuis Opsi (Opsi jawaban A, B, C, D)
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_opsi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `soal_id` int(11) NOT NULL,
  `teks` text NOT NULL,
  `is_benar` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_opsi_soal` FOREIGN KEY (`soal_id`) REFERENCES `kuis_soal` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 5. Progress Siswa (Menandai materi sudah dibaca)
$conn->query("CREATE TABLE IF NOT EXISTS `progress_materi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `diselesaikan_pada` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_progress` (`item_id`,`siswa_id`),
  CONSTRAINT `fk_prog_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_prog_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 6. Hasil Kuis Siswa
$conn->query("CREATE TABLE IF NOT EXISTS `kuis_hasil` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `item_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `skor` double DEFAULT 0,
  `diselesaikan_pada` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_hasil` (`item_id`,`siswa_id`),
  CONSTRAINT `fk_hasil_item` FOREIGN KEY (`item_id`) REFERENCES `modul_item` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_hasil_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Tabel LMS berhasil dibuat.";
