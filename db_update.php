<?php
require_once __DIR__ . '/config/database.php';

$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// 1. Tabel Pengumuman
$conn->query("CREATE TABLE IF NOT EXISTS `pengumuman` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `judul` varchar(255) NOT NULL,
  `isi` text NOT NULL,
  `target_role` enum('semua','guru','siswa') DEFAULT 'semua',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_pengumuman_user` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 2. Tabel Presensi (Absensi Harian per Jadwal)
$conn->query("CREATE TABLE IF NOT EXISTS `presensi` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_mengajar_id` int(11) NOT NULL,
  `tanggal` date NOT NULL,
  `pertemuan_ke` int(11) DEFAULT NULL,
  `topik` varchar(255) DEFAULT NULL,
  `status` enum('buka','tutup') DEFAULT 'buka',
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_presensi_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 3. Tabel Detail Presensi Siswa
$conn->query("CREATE TABLE IF NOT EXISTS `presensi_siswa` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `presensi_id` int(11) NOT NULL,
  `siswa_id` int(11) NOT NULL,
  `status_hadir` enum('hadir','izin','sakit','alpa') DEFAULT 'alpa',
  `waktu_absen` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `uq_presensi_siswa` (`presensi_id`,`siswa_id`),
  CONSTRAINT `fk_ps_presensi` FOREIGN KEY (`presensi_id`) REFERENCES `presensi` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_ps_siswa` FOREIGN KEY (`siswa_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 4. Tabel Forum Thread
$conn->query("CREATE TABLE IF NOT EXISTS `forum_thread` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `jadwal_mengajar_id` int(11) NOT NULL,
  `judul` varchar(255) NOT NULL,
  `deskripsi` text DEFAULT NULL,
  `dibuat_oleh` int(11) NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_forum_jm` FOREIGN KEY (`jadwal_mengajar_id`) REFERENCES `jadwal_mengajar` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_forum_user` FOREIGN KEY (`dibuat_oleh`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

// 5. Tabel Forum Reply
$conn->query("CREATE TABLE IF NOT EXISTS `forum_reply` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `thread_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `pesan` text NOT NULL,
  `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  CONSTRAINT `fk_reply_thread` FOREIGN KEY (`thread_id`) REFERENCES `forum_thread` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_reply_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

echo "Tabel tambahan berhasil dibuat.";
