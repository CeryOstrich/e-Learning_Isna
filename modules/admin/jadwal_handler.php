<?php
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'tambah':
        verifyCsrf();
        $ta_id    = (int)($_POST['tahun_ajaran_id'] ?? 0);
        $kelas_id = (int)($_POST['kelas_id']        ?? 0);
        $mapel_id = (int)($_POST['mapel_id']        ?? 0);
        $guru_id  = (int)($_POST['guru_id']         ?? 0);
        $hari     = $_POST['hari']       ?? null;
        $jam_m    = $_POST['jam_mulai']  ?? null;
        $jam_s    = $_POST['jam_selesai']?? null;

        if (!$ta_id || !$kelas_id || !$mapel_id || !$guru_id) {
            setFlash('error', 'Semua field wajib diisi.');
            redirectTo('index.php?page=a_jadwal_mengajar');
        }

        // Cek duplikasi (1 mapel di 1 kelas hanya boleh 1 guru)
        $cek = $db->queryOne(
            "SELECT id FROM jadwal_mengajar WHERE tahun_ajaran_id=? AND kelas_id=? AND mapel_id=?",
            'iii', [$ta_id, $kelas_id, $mapel_id]
        );
        if ($cek) {
            setFlash('error', 'Jadwal untuk mapel ini di kelas tersebut sudah ada.');
            redirectTo('index.php?page=a_jadwal_mengajar');
        }

        $db->execute(
            "INSERT INTO jadwal_mengajar (tahun_ajaran_id,kelas_id,mapel_id,guru_id,hari,jam_mulai,jam_selesai) VALUES (?,?,?,?,?,?,?)",
            'iiiisss', [$ta_id, $kelas_id, $mapel_id, $guru_id, $hari ?: null, $jam_m ?: null, $jam_s ?: null]
        );
        setFlash('success', 'Jadwal mengajar berhasil ditambahkan.');
        redirectTo('index.php?page=a_jadwal_mengajar');
        break;

    case 'hapus':
        $id = (int)($_GET['id'] ?? 0);
        if (!$id) { redirectTo('index.php?page=a_jadwal_mengajar'); }
        $db->execute("DELETE FROM jadwal_mengajar WHERE id=?", 'i', [$id]);
        setFlash('success', 'Jadwal berhasil dihapus.');
        redirectTo('index.php?page=a_jadwal_mengajar');
        break;

    default:
        redirectTo('index.php?page=a_jadwal_mengajar');
}
