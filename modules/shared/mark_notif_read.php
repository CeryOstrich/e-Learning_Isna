<?php
/**
 * modules/shared/mark_notif_read.php
 * Tandai satu atau semua notifikasi sebagai sudah dibaca.
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireLogin();

header('Content-Type: application/json');

$db = Database::getInstance();

if (!empty($_GET['all'])) {
    // Tandai semua notifikasi user sebagai dibaca
    $db->execute(
        "UPDATE notifikasi SET dibaca = 1 WHERE user_id = ?",
        'i', [$_SESSION['user_id']]
    );
} elseif (!empty($_GET['id'])) {
    $id = (int)$_GET['id'];
    // Pastikan notifikasi ini milik user yang login
    $db->execute(
        "UPDATE notifikasi SET dibaca = 1 WHERE id = ? AND user_id = ?",
        'ii', [$id, $_SESSION['user_id']]
    );
}

echo json_encode(['success' => true]);
