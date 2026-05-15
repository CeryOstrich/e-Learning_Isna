<?php
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
verifyCsrf();

$db    = Database::getInstance();
$uid   = $_SESSION['user_id'];
$jm_id = (int)($_POST['jm_id'] ?? 0);

// Verifikasi jadwal milik guru ini
$jm = $db->queryOne(
    "SELECT jm.*, ta.id AS ta_id FROM jadwal_mengajar jm
     JOIN tahun_ajaran ta ON ta.id=jm.tahun_ajaran_id
     WHERE jm.id=? AND jm.guru_id=? AND ta.is_aktif=1",
    'ii', [$jm_id, $uid]
);

if (!$jm) {
    setFlash('error', 'Akses ditolak atau jadwal tidak valid.');
    redirectTo('index.php?page=g_nilai');
}

$nilaiPost = $_POST['nilai'] ?? [];
if (empty($nilaiPost)) {
    setFlash('warning','Tidak ada data nilai yang dikirim.');
    redirectTo("index.php?page=g_nilai&jm_id=$jm_id");
}

$db->getConn()->begin_transaction();
$count = 0;

try {
    foreach ($nilaiPost as $siswa_id => $data) {
        $siswa_id = (int)$siswa_id;
        $harian   = isset($data['harian']) && $data['harian'] !== '' ? (float)$data['harian'] : null;
        $uts      = isset($data['uts'])    && $data['uts']    !== '' ? (float)$data['uts']    : null;
        $uas      = isset($data['uas'])    && $data['uas']    !== '' ? (float)$data['uas']    : null;
        $catatan  = trim($data['catatan'] ?? '');

        // Hitung nilai akhir hanya jika minimal 1 nilai terisi
        $na    = null;
        $pred  = null;
        if ($harian !== null || $uts !== null || $uas !== null) {
            $na   = hitungNilaiAkhir($harian ?? 0, $uts ?? 0, $uas ?? 0);
            $pred = nilaiKePredikat($na);
        }

        // UPSERT: insert jika belum ada, update jika sudah
        $db->getConn()->query(
            "INSERT INTO nilai_akhir
                (tahun_ajaran_id, kelas_id, siswa_id, mapel_id, guru_id, nilai_harian, nilai_uts, nilai_uas, nilai_akhir, predikat, catatan_guru)
             VALUES
                ({$jm['ta_id']}, {$jm['kelas_id']}, $siswa_id, {$jm['mapel_id']}, $uid,
                " . ($harian !== null ? $harian : 'NULL') . ",
                " . ($uts    !== null ? $uts    : 'NULL') . ",
                " . ($uas    !== null ? $uas    : 'NULL') . ",
                " . ($na     !== null ? $na     : 'NULL') . ",
                " . ($pred   !== null ? "'" . $db->getConn()->real_escape_string($pred) . "'" : 'NULL') . ",
                '" . $db->getConn()->real_escape_string($catatan) . "')
             ON DUPLICATE KEY UPDATE
                nilai_harian=" . ($harian !== null ? $harian : 'NULL') . ",
                nilai_uts="    . ($uts    !== null ? $uts    : 'NULL') . ",
                nilai_uas="    . ($uas    !== null ? $uas    : 'NULL') . ",
                nilai_akhir="  . ($na     !== null ? $na     : 'NULL') . ",
                predikat="     . ($pred   !== null ? "'" . $db->getConn()->real_escape_string($pred) . "'" : 'NULL') . ",
                catatan_guru='" . $db->getConn()->real_escape_string($catatan) . "',
                guru_id=$uid"
        );
        $count++;
    }

    $db->getConn()->commit();
    setFlash('success', "Nilai $count siswa berhasil disimpan.");

} catch (Exception $e) {
    $db->getConn()->rollback();
    error_log('Nilai handler error: ' . $e->getMessage());
    setFlash('error', 'Terjadi kesalahan saat menyimpan nilai.');
}

redirectTo("index.php?page=g_nilai&jm_id=$jm_id");
