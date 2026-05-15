<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$tables = $db->queryAll("DESCRIBE kuis_hasil");
echo "<pre>";
print_r($tables);
echo "</pre>";
