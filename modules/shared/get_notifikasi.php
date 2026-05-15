<?php
/**
 * modules/shared/get_notifikasi.php
 * Endpoint AJAX untuk mengambil daftar notifikasi milik user yang login.
 */
require_once __DIR__ . '/../../bootstrap.php';
Auth::requireLogin();

header('Content-Type: application/json');

$db    = Database::getInstance();
$items = $db->queryAll(
    "SELECT id, pesan, link, dibaca, created_at
     FROM notifikasi
     WHERE user_id = ?
     ORDER BY created_at DESC
     LIMIT 15",
    'i',
    [$_SESSION['user_id']]
);

echo json_encode(['items' => $items]);
