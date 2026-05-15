<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$admins = $db->queryAll("SELECT id, nama, role, nis_nip FROM users WHERE role='admin'");
echo "<pre>";
print_r($admins);
echo "</pre>";
