<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$res = $db->queryAll("SHOW TRIGGERS");
echo "<pre>";
print_r($res);
echo "</pre>";
