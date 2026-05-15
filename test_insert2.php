<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
try {
    $db->execute("INSERT INTO users (nama, nis_nip, password, role) VALUES (?, ?, ?, ?)", 'ssss', ['test_user', '999888777', 'pass', 'siswa']);
    echo "SUCCESS_INSERT";
} catch (Exception $e) {
    echo "ERROR_INSERT: " . $e->getMessage();
}
