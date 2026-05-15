<?php
/**
 * modules/admin/mapel_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validasi CSRF
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=a_mapel');
        exit;
    }

    $nama_mapel = trim($_POST['nama_mapel'] ?? '');
    $kode_mapel = trim($_POST['kode_mapel'] ?? '');
    $kelompok   = $_POST['kelompok_mapel_id'] ?? 0;

    if ($action === 'add') {
        if ($nama_mapel && $kelompok) {
            $db->execute(
                "INSERT INTO mapel (nama_mapel, kode_mapel, kelompok_mapel_id) VALUES (?, ?, ?)",
                'ssi', [$nama_mapel, $kode_mapel, $kelompok]
            );
            setFlash('success', 'Mata pelajaran berhasil ditambahkan.');
        } else {
            setFlash('error', 'Harap isi semua kolom wajib.');
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        if ($id && $nama_mapel && $kelompok) {
            $db->execute(
                "UPDATE mapel SET nama_mapel=?, kode_mapel=?, kelompok_mapel_id=? WHERE id=?",
                'ssii', [$nama_mapel, $kode_mapel, $kelompok, $id]
            );
            setFlash('success', 'Mata pelajaran berhasil diupdate.');
        }
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute("DELETE FROM mapel WHERE id=?", 'i', [$id]);
        setFlash('success', 'Mata pelajaran berhasil dihapus.');
    }
}

header('Location: ' . BASE_URL . '/index.php?page=a_mapel');
exit;
