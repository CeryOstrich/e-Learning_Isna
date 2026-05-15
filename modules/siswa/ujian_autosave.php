<?php
/**
 * modules/siswa/ujian_autosave.php
 * Auto-save jawaban setiap 30 detik (dipanggil dari JS CBT)
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
header('Content-Type: application/json');

$db      = Database::getInstance();
$uid     = $_SESSION['user_id'];
$sesi_id = (int)($_POST['sesi_ujian_id'] ?? 0);

// Verifikasi sesi milik siswa
$sesi = $db->queryOne(
    "SELECT id FROM sesi_ujian WHERE id=? AND siswa_id=? AND status='berlangsung'",
    'ii', [$sesi_id, $uid]
);

if (!$sesi) {
    echo json_encode(['success' => false, 'msg' => 'Sesi tidak valid']);
    exit;
}

$jawabanPost = $_POST['jawaban'] ?? [];
$saved = 0;

foreach ($jawabanPost as $soal_id => $jawaban) {
    $soal_id = (int)$soal_id;
    $jawaban = trim($jawaban);
    if (!$soal_id || $jawaban === '') continue;

    $db->getConn()->query(
        "INSERT INTO jawaban_siswa (sesi_ujian_id, soal_id, jawaban)
         VALUES ($sesi_id, $soal_id, '" . $db->getConn()->real_escape_string($jawaban) . "')
         ON DUPLICATE KEY UPDATE jawaban=VALUES(jawaban)"
    );
    $saved++;
}

echo json_encode(['success' => true, 'saved' => $saved, 'time' => date('H:i:s')]);
