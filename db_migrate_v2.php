<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();

try {
    // 1. Alter modul_item
    $db->execute("ALTER TABLE modul_item MODIFY COLUMN tipe ENUM('materi', 'kuis', 'live_class') NOT NULL");

    // 2. Alter kuis_soal
    // Cek apakah kolom tipe sudah ada
    $cek_tipe = $db->queryAll("SHOW COLUMNS FROM kuis_soal LIKE 'tipe'");
    if (empty($cek_tipe)) {
        $db->execute("ALTER TABLE kuis_soal ADD COLUMN tipe ENUM('pg', 'essay') NOT NULL DEFAULT 'pg'");
        $db->execute("ALTER TABLE kuis_soal ADD COLUMN poin_maksimal INT NOT NULL DEFAULT 10");
    }

    // 3. Create kuis_jawaban
    $db->execute("CREATE TABLE IF NOT EXISTS `kuis_jawaban` (
      `id` int(11) NOT NULL AUTO_INCREMENT,
      `item_id` int(11) NOT NULL,
      `siswa_id` int(11) NOT NULL,
      `soal_id` int(11) NOT NULL,
      `opsi_id` int(11) DEFAULT NULL,
      `jawaban_teks` text DEFAULT NULL,
      `poin_didapat` double DEFAULT 0,
      `created_at` timestamp DEFAULT CURRENT_TIMESTAMP,
      PRIMARY KEY (`id`),
      UNIQUE KEY `uq_jawaban_item_siswa_soal` (`item_id`, `siswa_id`, `soal_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");

    // 4. Pastikan kuis_hasil menyimpan status (opsional, tapi sudah ada skor)
    
} catch (Exception $e) {
    // Ignore if already exists/altered
}
?>
