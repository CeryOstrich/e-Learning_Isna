<?php
require_once __DIR__ . '/bootstrap.php';
$db = Database::getInstance();
$res = $db->queryAll("SELECT TABLE_NAME, COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = 'isnu_db' AND COLUMN_NAME = 'email'");
echo "<pre>";
print_r($res);
echo "</pre>";
