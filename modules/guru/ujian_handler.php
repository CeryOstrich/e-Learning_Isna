<?php
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

function verifyUjianOwner(Database $db, int $ujian_id, int $guru_id): array|null {
    return $db->queryOne(
        "SELECT u.* FROM ujian u JOIN jadwal_mengajar jm ON jm.id=u.jadwal_mengajar_id WHERE u.id=? AND jm.guru_id=?",
        'ii', [$ujian_id, $guru_id]
    );
}

switch ($action) {
    case 'buat':
        verifyCsrf();
        $jm_id       = (int)($_POST['jadwal_mengajar_id'] ?? 0);
        $judul        = trim($_POST['judul'] ?? '');
        $tipe         = $_POST['tipe'] ?? 'kuis';
        $durasi       = (int)($_POST['durasi_menit'] ?? 60);
        $waktu_mulai  = $_POST['waktu_mulai']  ?: null;
        $waktu_selesai= $_POST['waktu_selesai'] ?: null;
        $acak         = isset($_POST['acak_soal'])    ? 1 : 0;
        $tampil       = isset($_POST['tampil_hasil']) ? 1 : 0;

        if (!$jm_id || !$judul) {
            setFlash('error', 'Kelas/Mapel dan judul wajib diisi.');
            redirectTo('index.php?page=g_ujian');
        }

        // Verifikasi jadwal milik guru
        $jm = $db->queryOne("SELECT guru_id FROM jadwal_mengajar WHERE id=?", 'i', [$jm_id]);
        if (!$jm || (int)$jm['guru_id'] !== $uid) {
            setFlash('error', 'Akses ditolak.');
            redirectTo('index.php?page=g_ujian');
        }

        $id = $db->execute(
            "INSERT INTO ujian (jadwal_mengajar_id,judul,tipe,durasi_menit,waktu_mulai,waktu_selesai,acak_soal,tampil_hasil,status)
             VALUES (?,?,?,?,?,?,?,?,'draft')",
            'ississii',
            [$jm_id, $judul, $tipe, $durasi, $waktu_mulai, $waktu_selesai, $acak, $tampil]
        );

        setFlash('success', 'Ujian berhasil dibuat. Silakan tambahkan soal.');
        redirectTo("index.php?page=g_soal&ujian_id=$id");
        break;

    case 'aktifkan':
        $id = (int)($_GET['id'] ?? 0);
        $ujian = verifyUjianOwner($db, $id, $uid);
        if (!$ujian) { setFlash('error','Akses ditolak.'); redirectTo('index.php?page=g_ujian'); }

        $jmlSoal = $db->queryOne("SELECT COUNT(*) c FROM soal WHERE ujian_id=?", 'i', [$id])['c'] ?? 0;
        if ($jmlSoal < 1) { setFlash('error','Tidak bisa diaktifkan, belum ada soal.'); redirectTo('index.php?page=g_ujian'); }

        $db->execute("UPDATE ujian SET status='aktif' WHERE id=?", 'i', [$id]);
        setFlash('success','Ujian berhasil diaktifkan. Siswa sudah bisa mulai mengerjakan.');
        redirectTo('index.php?page=g_ujian');
        break;

    case 'selesaikan':
        $id = (int)($_GET['id'] ?? 0);
        if (!verifyUjianOwner($db, $id, $uid)) { setFlash('error','Akses ditolak.'); redirectTo('index.php?page=g_ujian'); }
        $db->execute("UPDATE ujian SET status='selesai' WHERE id=?", 'i', [$id]);
        // Finalize: auto-submit semua sesi yang masih berlangsung
        $db->execute("UPDATE sesi_ujian SET selesai_at=NOW(), status='selesai' WHERE ujian_id=? AND status='berlangsung'", 'i', [$id]);
        setFlash('success','Ujian berhasil diselesaikan.');
        redirectTo('index.php?page=g_ujian');
        break;

    case 'hapus':
        $id = (int)($_GET['id'] ?? 0);
        if (!verifyUjianOwner($db, $id, $uid)) { setFlash('error','Akses ditolak.'); redirectTo('index.php?page=g_ujian'); }
        $db->execute("DELETE FROM ujian WHERE id=?", 'i', [$id]);
        setFlash('success','Ujian berhasil dihapus.');
        redirectTo('index.php?page=g_ujian');
        break;

    default:
        redirectTo('index.php?page=g_ujian');
}
