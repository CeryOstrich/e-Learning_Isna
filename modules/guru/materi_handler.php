<?php
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

// Helper: pastikan jadwal_mengajar ini milik guru yg login
function verifyJadwalOwner(Database $db, int $jm_id, int $guru_id): bool {
    $jm = $db->queryOne("SELECT guru_id FROM jadwal_mengajar WHERE id=?", 'i', [$jm_id]);
    return $jm && (int)$jm['guru_id'] === $guru_id;
}

switch ($action) {
    case 'tambah':
        verifyCsrf();
        $jm_id  = (int)($_POST['jadwal_mengajar_id'] ?? 0);
        $judul  = trim($_POST['judul'] ?? '');
        $desc   = trim($_POST['deskripsi'] ?? '');
        $link   = trim($_POST['link_eksternal'] ?? '');

        if (!$jm_id || !$judul) {
            setFlash('error', 'Kelas/Mapel dan judul wajib diisi.');
            redirectTo('index.php?page=g_materi');
        }
        if (!verifyJadwalOwner($db, $jm_id, $uid)) {
            setFlash('error', 'Akses ditolak.');
            redirectTo('index.php?page=g_materi');
        }

        $filePath = null;
        if (!empty($_FILES['file_materi']['name'])) {
            $filePath = uploadFile($_FILES['file_materi'], UPLOAD_MATERI, ALLOWED_DOC_EXT);
            if (!$filePath) {
                setFlash('error', 'Upload gagal. Pastikan format file PDF/DOCX/PPT dan ukuran maks 10MB.');
                redirectTo('index.php?page=g_materi');
            }
        }

        $db->execute(
            "INSERT INTO materi (jadwal_mengajar_id, judul, deskripsi, file_path, link_eksternal) VALUES (?,?,?,?,?)",
            'issss', [$jm_id, $judul, $desc ?: null, $filePath, $link ?: null]
        );
        setFlash('success', 'Materi berhasil diunggah.');
        redirectTo('index.php?page=g_materi');
        break;

    case 'hapus':
        $id = (int)($_GET['id'] ?? 0);
        $materi = $db->queryOne(
            "SELECT mt.*, jm.guru_id FROM materi mt JOIN jadwal_mengajar jm ON jm.id=mt.jadwal_mengajar_id WHERE mt.id=?",
            'i', [$id]
        );
        if (!$materi || (int)$materi['guru_id'] !== $uid) {
            setFlash('error', 'Akses ditolak atau materi tidak ditemukan.');
            redirectTo('index.php?page=g_materi');
        }
        // Hapus file dari disk
        if ($materi['file_path'] && file_exists(UPLOAD_MATERI . $materi['file_path'])) {
            unlink(UPLOAD_MATERI . $materi['file_path']);
        }
        $db->execute("DELETE FROM materi WHERE id=?", 'i', [$id]);
        setFlash('success', 'Materi berhasil dihapus.');
        redirectTo('index.php?page=g_materi');
        break;

    default:
        redirectTo('index.php?page=g_materi');
}
