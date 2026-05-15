<?php
/**
 * modules/guru/presensi_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=g_presensi');
        exit;
    }

    $jm_id = $_POST['jadwal_mengajar_id'] ?? 0;
    $tgl   = $_POST['tanggal'] ?? '';
    $ke    = $_POST['pertemuan_ke'] ?? 1;
    $topik = trim($_POST['topik'] ?? '');

    // Verifikasi
    $cek = $db->queryOne("SELECT id FROM jadwal_mengajar WHERE id=? AND guru_id=?", 'ii', [$jm_id, $_SESSION['user_id']]);
    if (!$cek) {
        setFlash('error', 'Akses ditolak.');
        header('Location: ' . BASE_URL . '/index.php?page=g_presensi');
        exit;
    }

    if ($action === 'buka') {
        if ($jm_id && $tgl) {
            $db->execute(
                "INSERT INTO presensi (jadwal_mengajar_id, tanggal, pertemuan_ke, topik, status) VALUES (?, ?, ?, ?, 'buka')",
                'isis', [$jm_id, $tgl, $ke, $topik]
            );
            
            // Notify students
            $siswa = $db->queryAll("SELECT user_id FROM kelas_siswa ks JOIN jadwal_mengajar jm ON jm.kelas_id = ks.kelas_id WHERE jm.id=?", 'i', [$jm_id]);
            foreach ($siswa as $s) {
                $db->execute("INSERT INTO notifikasi (user_id, pesan, link) VALUES (?, ?, ?)", 'iss', [
                    $s['user_id'],
                    "Presensi dibuka untuk pertemuan ke-$ke",
                    "?page=s_presensi"
                ]);
            }
            
            setFlash('success', 'Sesi presensi berhasil dibuka.');
        }
    } 
    elseif ($action === 'update_siswa') {
        $presensi_id = $_POST['presensi_id'] ?? 0;
        $siswa_id    = $_POST['siswa_id'] ?? 0;
        $status      = $_POST['status_hadir'] ?? 'alpa';
        
        $cek_presensi = $db->queryOne(
            "SELECT p.id FROM presensi p JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id WHERE p.id=? AND jm.guru_id=?",
            'ii', [$presensi_id, $_SESSION['user_id']]
        );
        
        if ($cek_presensi) {
            $db->execute(
                "INSERT INTO presensi_siswa (presensi_id, siswa_id, status_hadir, waktu_absen) VALUES (?, ?, ?, NOW()) 
                 ON DUPLICATE KEY UPDATE status_hadir=?, waktu_absen=NOW()",
                'iiss', [$presensi_id, $siswa_id, $status, $status]
            );
            setFlash('success', 'Status kehadiran siswa berhasil diubah.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_presensi&id=$presensi_id");
        exit;
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'tutup') {
        $id = $_GET['id'] ?? 0;
        $db->execute(
            "UPDATE presensi p JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id 
             SET p.status='tutup' WHERE p.id=? AND jm.guru_id=?",
            'ii', [$id, $_SESSION['user_id']]
        );
        setFlash('success', 'Sesi presensi telah ditutup.');
    }
    elseif ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute(
            "DELETE p FROM presensi p JOIN jadwal_mengajar jm ON jm.id = p.jadwal_mengajar_id 
             WHERE p.id=? AND jm.guru_id=?",
            'ii', [$id, $_SESSION['user_id']]
        );
        setFlash('success', 'Sesi presensi berhasil dihapus.');
    }
}

header('Location: ' . BASE_URL . '/index.php?page=g_presensi');
exit;
