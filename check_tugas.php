<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$tables = $db->queryAll("SHOW TABLES LIKE 'tugas'");
echo "<pre>";
print_r($tables);
echo "</pre>";
