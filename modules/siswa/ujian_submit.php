<?php
/**
 * modules/siswa/ujian_submit.php
 * Submit final jawaban ujian + auto-score pilgan
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
verifyCsrf();

$db      = Database::getInstance();
$uid     = $_SESSION['user_id'];
$sesi_id = (int)($_POST['sesi_ujian_id'] ?? 0);
$ujian_id= (int)($_POST['ujian_id']      ?? 0);

// Verifikasi sesi milik siswa ini
$sesi = $db->queryOne(
    "SELECT * FROM sesi_ujian WHERE id=? AND siswa_id=? AND status='berlangsung'",
    'ii', [$sesi_id, $uid]
);

if (!$sesi) {
    setFlash('error','Sesi ujian tidak valid atau sudah selesai.');
    redirectTo('index.php?page=s_ujian');
}

$jawabanPost = $_POST['jawaban'] ?? [];
$soals = $db->queryAll("SELECT * FROM soal WHERE ujian_id=?", 'i', [$ujian_id]);

$skorPilgan = 0;
$db->getConn()->begin_transaction();

try {
    foreach ($soals as $s) {
        $soal_id = $s['id'];
        $jawaban = isset($jawabanPost[$soal_id]) ? trim($jawabanPost[$soal_id]) : null;
        $is_benar = null;

        if ($s['tipe_soal'] === 'pilgan' && $jawaban) {
            $is_benar = ($jawaban === $s['jawaban_benar']) ? 1 : 0;
            if ($is_benar) $skorPilgan += (int)$s['bobot'];
        }

        // INSERT or UPDATE jawaban
        $db->getConn()->query(
            "INSERT INTO jawaban_siswa (sesi_ujian_id, soal_id, jawaban, is_benar)
             VALUES ($sesi_id, $soal_id, " .
             ($jawaban ? "'" . $db->getConn()->real_escape_string($jawaban) . "'" : 'NULL') .
             ", " . ($is_benar !== null ? $is_benar : 'NULL') . ")
             ON DUPLICATE KEY UPDATE jawaban=VALUES(jawaban), is_benar=VALUES(is_benar)"
        );
    }

    // Update sesi: skor pilgan, status selesai
    $db->execute(
        "UPDATE sesi_ujian SET selesai_at=NOW(), skor_pilgan=?, skor_total=?, status='selesai' WHERE id=?",
        'iii', [$skorPilgan, $skorPilgan, $sesi_id]
    );

    $db->getConn()->commit();

} catch (Exception $e) {
    $db->getConn()->rollback();
    error_log('Ujian submit error: ' . $e->getMessage());
    setFlash('error','Terjadi kesalahan saat menyimpan jawaban. Coba lagi.');
    redirectTo("index.php?page=s_ujian_kerjakan&ujian_id=$ujian_id");
}

// Tampilkan skor jika ujian setting tampil_hasil = 1
$ujian = $db->queryOne("SELECT tampil_hasil FROM ujian WHERE id=?", 'i', [$ujian_id]);

// ── Gamifikasi: Tambah XP untuk menyelesaikan ujian ────────────────────────
Gamifikasi::tambahXP($uid, Gamifikasi::XP_KUIS_SELESAI, 'Menyelesaikan ujian/kuis');
// Bonus XP jika skor sempurna
if ($skorPilgan >= 100) {
    $hasilBonus = Gamifikasi::tambahXP($uid, Gamifikasi::XP_KUIS_SEMPURNA, 'Skor sempurna di kuis (bonus)');
    if (!empty($hasilBonus['badge_baru'])) {
        $_SESSION['badge_baru'] = $hasilBonus['badge_baru'];
    }
}
// ─────────────────────────────────────────────────────────────────────

if ($ujian['tampil_hasil']) {
    setFlash('success',"Ujian selesai! Skor pilihan ganda Anda: $skorPilgan poin.");
} else {
    setFlash('success','Jawaban Anda berhasil dikumpulkan. Skor akan diumumkan oleh guru.');
}

redirectTo('index.php?page=s_ujian');
