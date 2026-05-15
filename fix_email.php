<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$mysqli = $db->getConnection();
$res = $mysqli->query("SHOW COLUMNS FROM users LIKE 'email'");
if ($res && $res->num_rows > 0) {
    $mysqli->query("ALTER TABLE users MODIFY email VARCHAR(255) NULL DEFAULT NULL");
    echo "Kolom email diubah menjadi boleh NULL.";
} else {
    echo "Kolom email sudah tidak ada.";
}
