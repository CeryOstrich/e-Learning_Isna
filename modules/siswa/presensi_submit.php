<?php
/**
 * modules/siswa/presensi_submit.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=s_presensi');
        exit;
    }

    $presensi_id = $_POST['presensi_id'] ?? 0;
    $user_id = $_SESSION['user_id'];

    // Cek apakah presensi valid dan berstatus buka
    $presensi = $db->queryOne("SELECT id, status FROM presensi WHERE id=?", 'i', [$presensi_id]);
    
    if (!$presensi || $presensi['status'] !== 'buka') {
        setFlash('error', 'Sesi presensi ini tidak ditemukan atau sudah ditutup.');
        header('Location: ' . BASE_URL . '/index.php?page=s_presensi');
        exit;
    }
    
    // Simpan kehadiran
    $db->execute(
        "INSERT INTO presensi_siswa (presensi_id, siswa_id, status_hadir, waktu_absen) VALUES (?, ?, 'hadir', NOW())
         ON DUPLICATE KEY UPDATE status_hadir='hadir', waktu_absen=NOW()",
        'ii', [$presensi_id, $user_id]
    );
    
    setFlash('success', 'Presensi Anda berhasil dicatat sebagai Hadir.');
}

header('Location: ' . BASE_URL . '/index.php?page=s_presensi');
exit;
