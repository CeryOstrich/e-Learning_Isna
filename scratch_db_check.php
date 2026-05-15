<?php
require_once __DIR__ . '/config/database.php';
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT TABLE_NAME, CONSTRAINT_NAME, UPDATE_RULE, DELETE_RULE, REFERENCED_TABLE_NAME 
                        FROM information_schema.REFERENTIAL_CONSTRAINTS 
                        WHERE CONSTRAINT_SCHEMA = 'isnu_db' AND DELETE_RULE != 'CASCADE'");
$fks = [];
while ($row = $result->fetch_assoc()) {
    $fks[] = $row;
}
echo "Foreign keys WITHOUT CASCADE:\n";
print_r($fks);
?>
