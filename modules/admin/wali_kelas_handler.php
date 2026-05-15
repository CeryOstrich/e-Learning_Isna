<?php
/**
 * modules/admin/wali_kelas_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=a_wali_kelas');
        exit;
    }

    $guru_id  = $_POST['guru_id'] ?? 0;
    $kelas_id = $_POST['kelas_id'] ?? 0;
    $ta_id    = $_POST['tahun_ajaran_id'] ?? 0;

    if ($action === 'add') {
        if ($guru_id && $kelas_id && $ta_id) {
            // Cek apakah guru sudah jadi wali kelas di TA ini
            $cekGuru = $db->queryOne("SELECT id FROM wali_kelas WHERE guru_id=? AND tahun_ajaran_id=?", 'ii', [$guru_id, $ta_id]);
            // Cek apakah kelas sudah punya wali kelas di TA ini
            $cekKelas = $db->queryOne("SELECT id FROM wali_kelas WHERE kelas_id=? AND tahun_ajaran_id=?", 'ii', [$kelas_id, $ta_id]);
            
            if ($cekGuru) {
                setFlash('error', 'Guru ini sudah menjadi wali kelas di kelas lain pada TA yang sama.');
            } elseif ($cekKelas) {
                setFlash('error', 'Kelas ini sudah memiliki wali kelas.');
            } else {
                $db->execute("INSERT INTO wali_kelas (guru_id, kelas_id, tahun_ajaran_id) VALUES (?, ?, ?)", 'iii', [$guru_id, $kelas_id, $ta_id]);
                setFlash('success', 'Wali kelas berhasil ditetapkan.');
            }
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        if ($id && $guru_id && $kelas_id && $ta_id) {
            $db->execute("UPDATE wali_kelas SET guru_id=?, kelas_id=?, tahun_ajaran_id=? WHERE id=?", 'iiii', [$guru_id, $kelas_id, $ta_id, $id]);
            setFlash('success', 'Wali kelas berhasil diupdate.');
        }
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute("DELETE FROM wali_kelas WHERE id=?", 'i', [$id]);
        setFlash('success', 'Wali kelas berhasil dihapus.');
    }
}

header('Location: ' . BASE_URL . '/index.php?page=a_wali_kelas');
exit;
