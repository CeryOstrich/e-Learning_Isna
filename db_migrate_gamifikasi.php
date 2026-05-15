<?php
/**
 * db_migrate_gamifikasi.php
 * Auto-migration tabel gamifikasi (XP, Badge, Log).
 * Di-include sekali oleh index.php — aman dijalankan berulang.
 */
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();

try {
    // ── Tabel 1: user_xp ─────────────────────────────────────────────────────
    // Menyimpan total XP dan level setiap siswa.
    $db->execute("CREATE TABLE IF NOT EXISTS `user_xp` (
        `id`         INT AUTO_INCREMENT PRIMARY KEY,
        `user_id`    INT NOT NULL UNIQUE,
        `total_xp`   INT NOT NULL DEFAULT 0,
        `level`      INT NOT NULL DEFAULT 1,
        `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX `idx_user_xp_user` (`user_id`),
        INDEX `idx_user_xp_total` (`total_xp`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // ── Tabel 2: xp_log ──────────────────────────────────────────────────────
    // History setiap transaksi XP (untuk audit & animasi).
    $db->execute("CREATE TABLE IF NOT EXISTS `xp_log` (
        `id`          INT AUTO_INCREMENT PRIMARY KEY,
        `user_id`     INT NOT NULL,
        `jumlah`      INT NOT NULL,
        `keterangan`  VARCHAR(120) NOT NULL,
        `created_at`  TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX `idx_xp_log_user` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

    // ── Tabel 3: user_badge ──────────────────────────────────────────────────
    // Badge yang sudah diraih setiap user (many-to-many logis).
    $db->execute("CREATE TABLE IF NOT EXISTS `user_badge` (
        `id`          INT AUTO_INCREMENT PRIMARY KEY,
        `user_id`     INT NOT NULL,
        `badge_slug`  VARCHAR(60) NOT NULL,
        `earned_at`   TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        UNIQUE KEY `uq_user_badge` (`user_id`, `badge_slug`),
        INDEX `idx_user_badge_user` (`user_id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");

} catch (Exception $e) {
    // Abaikan error (tabel sudah ada, dll)
    error_log('Gamifikasi migration error: ' . $e->getMessage());
}
