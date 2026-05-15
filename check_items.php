<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$tables = $db->queryAll("SELECT * FROM modul_item");
echo "<pre>";
print_r($tables);
echo "</pre>";
