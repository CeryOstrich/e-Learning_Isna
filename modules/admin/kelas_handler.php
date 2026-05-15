<?php
/**
 * modules/admin/kelas_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=a_kelas');
        exit;
    }

    $nama_kelas = trim($_POST['nama_kelas'] ?? '');
    $tingkat    = $_POST['tingkat'] ?? '';
    $ta_id      = $_POST['tahun_ajaran_id'] ?? 0;

    if ($action === 'add') {
        if ($nama_kelas && $tingkat && $ta_id) {
            $db->execute(
                "INSERT INTO kelas (nama_kelas, tingkat, tahun_ajaran_id) VALUES (?, ?, ?)",
                'ssi', [$nama_kelas, $tingkat, $ta_id]
            );
            setFlash('success', 'Kelas berhasil ditambahkan.');
        } else {
            setFlash('error', 'Harap isi semua kolom wajib.');
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        if ($id && $nama_kelas && $tingkat && $ta_id) {
            $db->execute(
                "UPDATE kelas SET nama_kelas=?, tingkat=?, tahun_ajaran_id=? WHERE id=?",
                'ssii', [$nama_kelas, $tingkat, $ta_id, $id]
            );
            setFlash('success', 'Kelas berhasil diupdate.');
        }
    }
    elseif ($action === 'add_siswa') {
        $kelas_id = $_POST['kelas_id'] ?? 0;
        $user_id  = $_POST['user_id'] ?? 0;
        
        if ($kelas_id && $user_id) {
            // Cek apakah sudah masuk kelas ini
            $cek = $db->queryOne("SELECT id FROM kelas_siswa WHERE kelas_id=? AND user_id=?", 'ii', [$kelas_id, $user_id]);
            if (!$cek) {
                // Cek nomor urut absen maksimal
                $max = $db->queryOne("SELECT MAX(no_absen) as m FROM kelas_siswa WHERE kelas_id=?", 'i', [$kelas_id]);
                $no = ($max['m'] ?? 0) + 1;
                
                $db->execute("INSERT INTO kelas_siswa (kelas_id, user_id, no_absen) VALUES (?, ?, ?)", 'iii', [$kelas_id, $user_id, $no]);
                setFlash('success', 'Siswa berhasil dimasukkan ke kelas.');
            } else {
                setFlash('error', 'Siswa sudah ada di kelas ini.');
            }
        }
        header("Location: " . BASE_URL . "/index.php?page=a_kelas_detail&id=$kelas_id");
        exit;
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute("DELETE FROM kelas WHERE id=?", 'i', [$id]);
        setFlash('success', 'Kelas berhasil dihapus.');
    }
    elseif ($action === 'remove_siswa') {
        $ks_id = $_GET['ks_id'] ?? 0;
        $kelas_id = $_GET['kelas_id'] ?? 0;
        $db->execute("DELETE FROM kelas_siswa WHERE id=?", 'i', [$ks_id]);
        setFlash('success', 'Siswa berhasil dikeluarkan dari kelas.');
        header("Location: " . BASE_URL . "/index.php?page=a_kelas_detail&id=$kelas_id");
        exit;
    }
}

header('Location: ' . BASE_URL . '/index.php?page=a_kelas');
exit;
