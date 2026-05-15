<?php
/**
 * modules/siswa/progress_materi_handler.php
 * Handler AJAX: Tandai item materi sebagai selesai + berikan XP.
 *
 * Request: POST /modules/siswa/progress_materi_handler.php
 * Body: { item_id: int, csrf_token: string }
 * Response: JSON { ok: bool, xp: int, level: int, badge_baru: array }
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['ok' => false, 'msg' => 'Method not allowed']);
    exit;
}

// Verifikasi CSRF
$token = $_POST['csrf_token'] ?? '';
if (!hash_equals($_SESSION['csrf_token'] ?? '', $token)) {
    http_response_code(419);
    echo json_encode(['ok' => false, 'msg' => 'Token tidak valid']);
    exit;
}

$db      = Database::getInstance();
$uid     = $_SESSION['user_id'];
$item_id = (int)($_POST['item_id'] ?? 0);

if (!$item_id) {
    echo json_encode(['ok' => false, 'msg' => 'item_id tidak valid']);
    exit;
}

// Verifikasi item ada dan tipe = 'materi'
$item = $db->queryOne("SELECT id, judul, tipe FROM modul_item WHERE id=? AND tipe='materi'", 'i', [$item_id]);
if (!$item) {
    echo json_encode(['ok' => false, 'msg' => 'Materi tidak ditemukan']);
    exit;
}

// Cek apakah sudah pernah ditandai selesai
$sudahSelesai = $db->queryOne(
    "SELECT id FROM progress_materi WHERE item_id=? AND siswa_id=?",
    'ii', [$item_id, $uid]
);

$xpBaru   = 0;
$badgeBaru = [];

if (!$sudahSelesai) {
    // Catat progress
    $db->execute(
        "INSERT INTO progress_materi (item_id, siswa_id, selesai_at) VALUES (?, ?, NOW())",
        'ii', [$item_id, $uid]
    );

    // Berikan XP
    $hasil   = Gamifikasi::tambahXP($uid, Gamifikasi::XP_MATERI_SELESAI, 'Menyelesaikan materi: ' . $item['judul']);
    $xpBaru  = Gamifikasi::XP_MATERI_SELESAI;
    $badgeBaru = $hasil['badge_baru'];

    // Simpan badge baru ke session juga (untuk halaman berikutnya)
    if (!empty($badgeBaru)) {
        $_SESSION['badge_baru'] = $badgeBaru;
    }

    echo json_encode([
        'ok'        => true,
        'xp_dapat'  => $xpBaru,
        'xp_total'  => $hasil['xp_total'],
        'level'     => $hasil['level'],
        'badge_baru'=> $badgeBaru,
        'msg'       => '+' . $xpBaru . ' XP — Materi selesai! 🎉',
    ]);
} else {
    // Sudah selesai sebelumnya
    echo json_encode([
        'ok'        => true,
        'xp_dapat'  => 0,
        'msg'       => 'Materi sudah ditandai selesai sebelumnya.',
        'badge_baru'=> [],
    ]);
}
