<?php
require_once __DIR__ . '/config/database.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

$res = $conn->query("SELECT TABLE_NAME, CONSTRAINT_NAME, DELETE_RULE, REFERENCED_TABLE_NAME 
FROM information_schema.REFERENTIAL_CONSTRAINTS 
WHERE CONSTRAINT_SCHEMA = 'isnu_db' AND DELETE_RULE != 'CASCADE'");
$fks = [];
while ($r = $res->fetch_assoc()) $fks[] = $r;
echo "No cascade:\n";
print_r($fks);
?>
