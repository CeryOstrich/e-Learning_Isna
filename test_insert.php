<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
try {
    $db->execute("INSERT INTO users (nama, nis_nip, password, role) VALUES (?, ?, ?, ?)", 'ssss', ['test', '1234567890', 'pass', 'siswa']);
    echo "Insert berhasil.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
