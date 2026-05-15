<?php
/**
 * modules/siswa/tugas_submit.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('siswa');
$db = Database::getInstance();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=s_tugas');
        exit;
    }

    $tugas_id = $_POST['tugas_id'] ?? 0;
    $catatan  = trim($_POST['catatan'] ?? '');
    $user_id  = $_SESSION['user_id'];

    // Cek tugas
    $tugas = $db->queryOne("SELECT id, deadline FROM tugas WHERE id=?", 'i', [$tugas_id]);
    if (!$tugas) {
        setFlash('error', 'Tugas tidak ditemukan.');
        header('Location: ' . BASE_URL . '/index.php?page=s_tugas');
        exit;
    }
    
    // Cek deadline
    if ($tugas['deadline'] && strtotime($tugas['deadline']) < time()) {
        // Bisa dibiarkan mengumpulkan tapi dengan flag telat, atau ditolak. Kita biarkan tapi di UI terlihat telat.
    }

    // Cek apakah sudah pernah mengumpulkan
    $cek = $db->queryOne("SELECT id, file_path FROM pengumpulan_tugas WHERE tugas_id=? AND siswa_id=?", 'ii', [$tugas_id, $user_id]);
    
    $filePath = null;
    if (isset($_FILES['file_jawaban']) && $_FILES['file_jawaban']['error'] != UPLOAD_ERR_NO_FILE) {
        $filePath = uploadFile($_FILES['file_jawaban'], UPLOAD_TUGAS, ALLOWED_FILE_EXT);
        if ($filePath === false) {
            setFlash('error', 'Gagal upload file jawaban. Cek tipe dan ukuran file.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
    }

    if ($cek) {
        // Update (hapus file lama jika ada file baru)
        if ($filePath && $cek['file_path'] && file_exists(UPLOAD_TUGAS . $cek['file_path'])) {
            unlink(UPLOAD_TUGAS . $cek['file_path']);
        }
        
        $newFile = $filePath ? $filePath : $cek['file_path'];
        $db->execute(
            "UPDATE pengumpulan_tugas SET file_path=?, catatan=?, dikumpulkan_at=NOW() WHERE id=?",
            'ssi', [$newFile, $catatan, $cek['id']]
        );
        setFlash('success', 'Jawaban tugas berhasil diperbarui.');
        // ── Gamifikasi: XP update tugas (tidak dobel, hanya catat jika update) ──
    } else {
        // Insert baru
        if (!$filePath && empty($catatan)) {
            setFlash('error', 'Harap lampirkan file atau tulis catatan jawaban.');
            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit;
        }
        
        $db->execute(
            "INSERT INTO pengumpulan_tugas (tugas_id, siswa_id, file_path, catatan) VALUES (?, ?, ?, ?)",
            'iiss', [$tugas_id, $user_id, $filePath ?: null, $catatan]
        );

        // ── Gamifikasi: Tambah XP untuk pengumpulan tugas ────────────────────
        $tepat_waktu = !($tugas['deadline'] && strtotime($tugas['deadline']) < time());
        if ($tepat_waktu) {
            $hasil = Gamifikasi::tambahXP($user_id, Gamifikasi::XP_TUGAS_TEPAT_WAKTU, 'Mengumpulkan tugas tepat waktu');
        } else {
            $hasil = Gamifikasi::tambahXP($user_id, Gamifikasi::XP_TUGAS_TERLAMBAT, 'Mengumpulkan tugas (terlambat)');
        }
        // Simpan info badge baru ke session untuk ditampilkan di UI
        if (!empty($hasil['badge_baru'])) {
            $_SESSION['badge_baru'] = $hasil['badge_baru'];
        }
        // ─────────────────────────────────────────────────────────────────────

        setFlash('success', 'Jawaban tugas berhasil dikirim.' . ($tepat_waktu ? ' +' . Gamifikasi::XP_TUGAS_TEPAT_WAKTU . ' XP 🎉' : ' +' . Gamifikasi::XP_TUGAS_TERLAMBAT . ' XP'));
    }
}

header('Location: ' . $_SERVER['HTTP_REFERER']);
exit;
