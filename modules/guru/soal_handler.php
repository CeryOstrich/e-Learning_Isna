<?php
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db  = Database::getInstance();
$uid = $_SESSION['user_id'];
$action = $_GET['action'] ?? $_POST['action'] ?? '';

switch ($action) {
    case 'tambah':
        verifyCsrf();
        $ujian_id   = (int)($_POST['ujian_id']      ?? 0);
        $tipe       = $_POST['tipe_soal']    ?? 'pilgan';
        $pertanyaan = trim($_POST['pertanyaan'] ?? '');
        $bobot      = (int)($_POST['bobot'] ?? 1);

        if (!$ujian_id || !$pertanyaan) {
            setFlash('error','Data soal tidak lengkap.');
            redirectTo("index.php?page=g_soal&ujian_id=$ujian_id");
        }

        // Verifikasi ujian milik guru
        $ujian = $db->queryOne(
            "SELECT u.id FROM ujian u JOIN jadwal_mengajar jm ON jm.id=u.jadwal_mengajar_id WHERE u.id=? AND jm.guru_id=?",
            'ii', [$ujian_id, $uid]
        );
        if (!$ujian) { setFlash('error','Akses ditolak.'); redirectTo('index.php?page=g_ujian'); }

        // Nomor soal otomatis
        $nomor = ($db->queryOne("SELECT MAX(nomor) mx FROM soal WHERE ujian_id=?", 'i', [$ujian_id])['mx'] ?? 0) + 1;

        if ($tipe === 'pilgan') {
            $jawaban = $_POST['jawaban_benar'] ?? 'a';
            $db->execute(
                "INSERT INTO soal (ujian_id,nomor,tipe_soal,pertanyaan,opsi_a,opsi_b,opsi_c,opsi_d,jawaban_benar,bobot)
                 VALUES (?,?,?,?,?,?,?,?,?,?)",
                'iisssssss i',
                [$ujian_id, $nomor, $tipe, $pertanyaan,
                 $_POST['opsi_a'] ?? '', $_POST['opsi_b'] ?? '',
                 $_POST['opsi_c'] ?? '', $_POST['opsi_d'] ?? '',
                 $jawaban, $bobot]
            );
        } else {
            $db->execute(
                "INSERT INTO soal (ujian_id,nomor,tipe_soal,pertanyaan,bobot) VALUES (?,?,?,?,?)",
                'iissi', [$ujian_id, $nomor, $tipe, $pertanyaan, $bobot]
            );
        }

        setFlash('success','Soal berhasil ditambahkan.');
        redirectTo("index.php?page=g_soal&ujian_id=$ujian_id");
        break;

    case 'hapus':
        $id       = (int)($_GET['id']       ?? 0);
        $ujian_id = (int)($_GET['ujian_id'] ?? 0);
        // Verifikasi soal milik ujian milik guru ini
        $soal = $db->queryOne(
            "SELECT s.id FROM soal s JOIN ujian u ON u.id=s.ujian_id
             JOIN jadwal_mengajar jm ON jm.id=u.jadwal_mengajar_id
             WHERE s.id=? AND jm.guru_id=?",
            'ii', [$id, $uid]
        );
        if (!$soal) { setFlash('error','Akses ditolak.'); redirectTo('index.php?page=g_ujian'); }
        $db->execute("DELETE FROM soal WHERE id=?", 'i', [$id]);
        setFlash('success','Soal berhasil dihapus.');
        redirectTo("index.php?page=g_soal&ujian_id=$ujian_id");
        break;

    default:
        redirectTo('index.php?page=g_ujian');
}
