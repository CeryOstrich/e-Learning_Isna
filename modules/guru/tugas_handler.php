<?php
/**
 * modules/guru/tugas_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=g_tugas');
        exit;
    }

    $jm_id     = $_POST['jadwal_mengajar_id'] ?? 0;
    $judul     = trim($_POST['judul'] ?? '');
    $deskripsi = trim($_POST['deskripsi'] ?? '');
    $deadline  = $_POST['deadline'] ?? '';

    // Verifikasi kepemilikan jadwal
    $cek = $db->queryOne("SELECT id FROM jadwal_mengajar WHERE id=? AND guru_id=?", 'ii', [$jm_id, $_SESSION['user_id']]);
    if (!$cek) {
        setFlash('error', 'Jadwal tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=g_tugas');
        exit;
    }

    if ($action === 'add') {
        if ($jm_id && $judul) {
            $filePath = uploadFile($_FILES['file_tugas'], UPLOAD_TUGAS, ALLOWED_FILE_EXT);
            if ($filePath === false && isset($_FILES['file_tugas']) && $_FILES['file_tugas']['error'] != UPLOAD_ERR_NO_FILE) {
                setFlash('error', 'Gagal upload file tugas.');
            } else {
                $db->execute(
                    "INSERT INTO tugas (jadwal_mengajar_id, judul, deskripsi, file_path, deadline) VALUES (?, ?, ?, ?, ?)",
                    'issss', [$jm_id, $judul, $deskripsi, $filePath ?: null, $deadline ?: null]
                );
                
                // Beri notif ke siswa di kelas tersebut
                $siswa = $db->queryAll(
                    "SELECT ks.user_id FROM kelas_siswa ks JOIN jadwal_mengajar jm ON jm.kelas_id = ks.kelas_id WHERE jm.id=?",
                    'i', [$jm_id]
                );
                foreach ($siswa as $s) {
                    $db->execute("INSERT INTO notifikasi (user_id, pesan, link) VALUES (?, ?, ?)", 'iss', [
                        $s['user_id'],
                        "Tugas Baru: " . $judul,
                        "?page=s_tugas"
                    ]);
                }
                
                setFlash('success', 'Tugas berhasil dibuat.');
            }
        }
    } 
    elseif ($action === 'nilai') {
        $pt_id = $_POST['pengumpulan_id'] ?? 0;
        $nilai = $_POST['nilai'] ?? 0;
        $feedback = $_POST['feedback_guru'] ?? '';
        
        $db->execute("UPDATE pengumpulan_tugas SET nilai=?, feedback_guru=? WHERE id=?", 'dsi', [$nilai, $feedback, $pt_id]);
        setFlash('success', 'Nilai tugas berhasil disimpan.');
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $tugas = $db->queryOne("SELECT t.id, t.file_path FROM tugas t JOIN jadwal_mengajar jm ON jm.id = t.jadwal_mengajar_id WHERE t.id=? AND jm.guru_id=?", 'ii', [$id, $_SESSION['user_id']]);
        
        if ($tugas) {
            if ($tugas['file_path'] && file_exists(UPLOAD_TUGAS . $tugas['file_path'])) {
                unlink(UPLOAD_TUGAS . $tugas['file_path']);
            }
            $db->execute("DELETE FROM tugas WHERE id=?", 'i', [$id]);
            setFlash('success', 'Tugas berhasil dihapus.');
        }
    }
}

header('Location: ' . BASE_URL . '/index.php?page=g_tugas');
exit;
