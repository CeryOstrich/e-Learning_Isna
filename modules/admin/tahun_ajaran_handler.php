<?php
/**
 * modules/admin/tahun_ajaran_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=a_tahun_ajaran');
        exit;
    }

    $nama = trim($_POST['nama'] ?? '');

    if ($action === 'add') {
        if ($nama) {
            $db->execute("INSERT INTO tahun_ajaran (nama, is_aktif) VALUES (?, 0)", 's', [$nama]);
            setFlash('success', 'Tahun Ajaran berhasil ditambahkan.');
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        if ($id && $nama) {
            $db->execute("UPDATE tahun_ajaran SET nama=? WHERE id=?", 'si', [$nama, $id]);
            setFlash('success', 'Tahun Ajaran berhasil diupdate.');
        }
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute("DELETE FROM tahun_ajaran WHERE id=?", 'i', [$id]);
        setFlash('success', 'Tahun Ajaran berhasil dihapus.');
    }
    elseif ($action === 'set_aktif') {
        $id = $_GET['id'] ?? 0;
        if ($id) {
            // Matikan semua TA
            $db->execute("UPDATE tahun_ajaran SET is_aktif=0");
            // Aktifkan yang dipilih
            $db->execute("UPDATE tahun_ajaran SET is_aktif=1 WHERE id=?", 'i', [$id]);
            setFlash('success', 'Tahun Ajaran berhasil diaktifkan.');
        }
    }
}

header('Location: ' . BASE_URL . '/index.php?page=a_tahun_ajaran');
exit;
