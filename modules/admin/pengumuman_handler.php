<?php
/**
 * modules/admin/pengumuman_handler.php
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireRole('admin');
$db = Database::getInstance();

$action = $_GET['action'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (empty($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        setFlash('error', 'Token keamanan tidak valid.');
        header('Location: ' . BASE_URL . '/index.php?page=a_pengumuman');
        exit;
    }

    $judul  = trim($_POST['judul'] ?? '');
    $isi    = trim($_POST['isi'] ?? '');
    $target = $_POST['target_role'] ?? 'semua';
    $user_id = $_SESSION['user_id'];

    if ($action === 'add') {
        if ($judul && $isi) {
            $db->execute(
                "INSERT INTO pengumuman (judul, isi, target_role, created_by) VALUES (?, ?, ?, ?)", 
                'sssi', [$judul, $isi, $target, $user_id]
            );
            
            // Create notification for targeted users
            $users = [];
            if ($target === 'semua') {
                $users = $db->queryAll("SELECT id, no_hp FROM users WHERE is_active=1 AND id != ?", 'i', [$user_id]);
            } else {
                $users = $db->queryAll("SELECT id, no_hp FROM users WHERE is_active=1 AND role=?", 's', [$target]);
            }
            
            $wa_targets = [];
            foreach ($users as $u) {
                $db->execute("INSERT INTO notifikasi (user_id, pesan, link) VALUES (?, ?, ?)", 'iss', [
                    $u['id'],
                    "Pengumuman Baru: " . $judul,
                    "?page=dashboard"
                ]);
                
                // Kumpulkan nomor WA yang valid
                if (!empty($u['no_hp']) && strlen($u['no_hp']) >= 10) {
                    $wa_targets[] = $u['no_hp'];
                }
            }
            
            // Kirim pesan WhatsApp massal jika ada target
            if (!empty($wa_targets)) {
                $wa_message = "*PENGUMUMAN BARU*\n\n" . 
                              "*" . $judul . "*\n\n" . 
                              strip_tags($isi) . "\n\n" . 
                              "_Pesan otomatis dari E-Learning MTs_";
                
                // Gabungkan semua nomor dengan koma
                $targetString = implode(',', $wa_targets);
                
                // Kirim via Fonnte dengan delay 2 detik per pesan agar tidak diban
                sendFonnteWhatsApp($targetString, $wa_message, "2");
            }
            
            setFlash('success', 'Pengumuman berhasil disiarkan.');
        }
    } 
    elseif ($action === 'edit') {
        $id = $_POST['id'] ?? 0;
        if ($id && $judul && $isi) {
            $db->execute(
                "UPDATE pengumuman SET judul=?, isi=?, target_role=? WHERE id=?", 
                'sssi', [$judul, $isi, $target, $id]
            );
            setFlash('success', 'Pengumuman berhasil diupdate.');
        }
    }
} 
elseif ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if ($action === 'delete') {
        $id = $_GET['id'] ?? 0;
        $db->execute("DELETE FROM pengumuman WHERE id=?", 'i', [$id]);
        setFlash('success', 'Pengumuman berhasil dihapus.');
    }
}

header('Location: ' . BASE_URL . '/index.php?page=a_pengumuman');
exit;
