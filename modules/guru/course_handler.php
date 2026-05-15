<?php
/**
 * modules/guru/course_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('guru');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=g_course');
        exit;
    }

    if ($action === 'add_modul') {
        $jm_id = $_POST['jadwal_mengajar_id'] ?? 0;
        $judul = trim($_POST['judul'] ?? '');
        $deskripsi = trim($_POST['deskripsi'] ?? '');
        
        $cek = $db->queryOne("SELECT id FROM jadwal_mengajar WHERE id=? AND guru_id=?", 'ii', [$jm_id, $_SESSION['user_id']]);
        if ($cek && $judul) {
            $urutan = $db->queryOne("SELECT MAX(urutan) as m FROM modul WHERE jadwal_mengajar_id=?", 'i', [$jm_id])['m'] ?? 0;
            $db->execute("INSERT INTO modul (jadwal_mengajar_id, judul, deskripsi, urutan) VALUES (?, ?, ?, ?)", 'issi', [$jm_id, $judul, $deskripsi, $urutan+1]);
            setFlash('success', 'Modul/Bab baru berhasil ditambahkan.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
        exit;
    }
    elseif ($action === 'add_live') {
        $jm_id = $_POST['jadwal_mengajar_id'] ?? 0;
        $modul_id = $_POST['modul_id'] ?? 0;
        $judul = trim($_POST['judul'] ?? '');
        $waktu = trim($_POST['waktu_mulai'] ?? '');
        $link = trim($_POST['link_vicon'] ?? '');
        
        $cek = $db->queryOne("SELECT m.id FROM modul m JOIN jadwal_mengajar jm ON jm.id=m.jadwal_mengajar_id WHERE m.id=? AND jm.guru_id=?", 'ii', [$modul_id, $_SESSION['user_id']]);
        if ($cek && $judul && $link) {
            $urutan = $db->queryOne("SELECT MAX(urutan) as m FROM modul_item WHERE modul_id=?", 'i', [$modul_id])['m'] ?? 0;
            $db->execute(
                "INSERT INTO modul_item (modul_id, tipe, judul, isi_teks, file_path, urutan) VALUES (?, 'live_class', ?, ?, ?, ?)",
                'isssi', [$modul_id, $judul, $waktu, $link, $urutan+1]
            );
            setFlash('success', 'Sesi Live Class berhasil dijadwalkan.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
        exit;
    }
}
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete_modul') {
        $id = $_GET['id'] ?? 0;
        $jm_id = $_GET['jm_id'] ?? 0;
        $cek = $db->queryOne("SELECT m.id FROM modul m JOIN jadwal_mengajar jm ON jm.id=m.jadwal_mengajar_id WHERE m.id=? AND jm.guru_id=?", 'ii', [$id, $_SESSION['user_id']]);
        if ($cek) {
            $db->execute("DELETE FROM modul WHERE id=?", 'i', [$id]);
            setFlash('success', 'Modul berhasil dihapus.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
        exit;
    }
    elseif ($action === 'delete_item') {
        $id = $_GET['id'] ?? 0;
        $jm_id = $_GET['jm_id'] ?? 0;
        $cek = $db->queryOne("SELECT mi.id FROM modul_item mi JOIN modul m ON m.id=mi.modul_id JOIN jadwal_mengajar jm ON jm.id=m.jadwal_mengajar_id WHERE mi.id=? AND jm.guru_id=?", 'ii', [$id, $_SESSION['user_id']]);
        if ($cek) {
            $db->execute("DELETE FROM modul_item WHERE id=?", 'i', [$id]);
            setFlash('success', 'Item berhasil dihapus.');
        }
        header("Location: " . BASE_URL . "/index.php?page=g_course&jm_id=$jm_id");
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php?page=g_course');
exit;
