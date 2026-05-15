<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
try {
    $db->execute("ALTER TABLE users DROP COLUMN email");
    echo "Kolom email berhasil dihapus dari tabel users.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
